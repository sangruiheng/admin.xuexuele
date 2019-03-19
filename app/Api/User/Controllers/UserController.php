<?php

namespace App\Api\User\Controllers;

use App\Api\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Api\User\Models\User;
use App\Classlib\FormCheck;
use App\Classlib\AliyunSms;
use Illuminate\Support\Facades\Cache;
use App\Classlib\Weixinpay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/**
 * 首页
 */
class UserController extends Controller
{
    protected $users = "s_users";
    protected $signin = "s_signin";
    protected $msg = "s_users_msg";
    public $timestamps = false;
    private $PayConf = [
        'APPID' => 'wxafdbc6d1d393dd28',
        'MCHID' => '1504259671',
        'KEY' => 'b3091e4029a23233280bcc92d13abf8a',
    ];

    /**
     * [__construct 构造方法]
     */
    public function __construct()
    {
        $this->AliyunSms = new AliyunSms();
        $this->User = new User();
        $this->FormCheck = new FormCheck();
    }


    //启动页
    public function getStartupPage()
    {
        $result = DB::table('s_startup')
            ->where('id', '=', 1)
            ->where('is_disable', '=', 0)
            ->first();
        if ($result) {
            return $this->response('true', 1, $result);
        } else {
            return $this->response('false', 0);
        }
    }


    //首页弹窗
    //隔一段时间弹一次
    public function getAdvertisementAlert(Request $request)
    {

        //type  标识为1是从登陆页进来
        $phone = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$phone->code) {
            return result($phone->msg);
        }
        $result = '';
        $user = DB::table('s_users')
            ->select('*')
            ->where('id', '=', $request->uid)
            ->first();
        if (time() > $user->last_time + 86400 || $request->type == 1) {
            $result = DB::table('s_advertisement')
                ->where('id', '=', 1)
                ->where('is_disable', '=', 0)
                ->first();
            DB::table('s_users')->where('id', '=', $request->uid)->update(['last_time' => time()]);
        }

        if ($result) {
            return $this->response('true', 1, $result);
        } else {
            return $this->response('false', 0);
        }
    }


    /**
     * 获取当前URL GetHost
     * @author weishuo <524651703@qq.com>
     * @return string
     */
    private function GetHost($request)
    {
        $Url = $request->getUri();
        $UrlInfo = parse_url($Url);
        $HostStr = $UrlInfo['scheme'] . "://" . $UrlInfo['host'];
        return $HostStr;
    }

    /**
     * 验证短信动态码 CheckSmsCode
     * @author weis <524651703@qq.com>
     * @param Request $request
     * @return boolean
     */
    private function CheckSmsCode($request)
    {
        $code = $request->SmsCode;
        $res = Cache::get('mobileSmsCode_' . $code);//获取cookie里的数据
        if (!empty($res)) {
            return true;
        }
        return false;
    }

    public function setToken()
    {
        // 生成一个不会重复的字符串
        $str = md5(uniqid(md5(microtime(true)), true));
        // 进行加密
        $str = sha1($str);
        return $str;
    }

    /**
     * 用户登录 Login
     * @author weis <524651703@qq.com>
     * @param Request $request
     * @return json
     */
    public function Login(Request $request)
    {

        //判断USER_ID是否存在
        $phone = $this->FormCheck->isEmpty($request->phone, '手机号');
        if (!$phone->code) {
            return result($phone->msg);
        }
        $password = $this->FormCheck->isEmpty($request->password, '密码');
        if (!$password->code) {
            return result($password->msg);
        }
        $UserInfo = $this->User->GetUserInfo($request);
        if (!empty($UserInfo) && empty($UserInfo->code)) {
            $request->UserId = $UserInfo->id;
            $UserInfo->remember_token = $this->setToken();
            $request->token = $UserInfo->remember_token;
            $request->UserId = $UserInfo->id;
            $request->expire_time = date('Y-m-d H:i:s', strtotime("+7 days"));
            $this->User->UpdateTokenInfo($request);
            if ($UserInfo->userstate == 2) {
                return $this->response('账号被禁用', 2);
            }
            $UserInfo->UserId = $UserInfo->id;
            $UserInfo->token = $UserInfo->remember_token;
            return $this->response('true', 1, $UserInfo, $request->apilog_id);
        } else if ($UserInfo->code == 1) {
            return $this->response('密码错误', 0);
        } else if ($UserInfo->code == 2) {
            return $this->response('账号不存在', 0);
        } else {
            return $this->response('登录失败', 0);
        }
    }

    /**
     * 检验手机号码是否已注册 UserPhone
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserPhone(Request $request)
    {
        //判断USER_ID是否存在
        $phone = $this->FormCheck->isEmpty($request->phone, '手机号');
        if (!$phone->code) {
            return result($phone->msg);
        }
        $UserInfo = $this->User->GetUserInfoPhone($request);
        if ($UserInfo->code == 1) {
            return $this->response('账号已存在', 1);
        } else if ($UserInfo->code == 2) {
            return $this->response('账号不存在', 0);
        }
    }


    /**
     * 签到
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserSignin(Request $request)
    {
//        echo 11;die;
        //判断USER_ID是否存在
        $phone = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$phone->code) {
            return result($phone->msg);
        }
        //判断是否已经签到
        $date = date("Y-m-d");
        $datemonth = date("Y-m");

        $Info = DB::table('s_signin')
            ->select('id')
            ->where('uid', '=', $request->uid)
            ->where('signdate', '=', $date)
            ->first();
//        echo $Info;die;
        if (!empty($Info)) {
            return $this->response('已经签到，不可重复', 0);
        } else {
            DB::beginTransaction();
            try {
                $data = [
                    'uid' => $request->uid,
                    'signdate' => $date,
                    'signmonth' => $datemonth,
                ];
                $res = DB::table('s_signin')->insertGetId($data);
                // if($res){
                //统计当前签到天数，如果有奖励，这给奖励
                $days = DB::table('s_signin')
                    ->where('uid', '=', $request->uid)
                    ->where('signmonth', '=', $datemonth)
                    ->count('id');
                //查询签到奖励规则表
                $ruleInfo = DB::table('s_signin_rule')
                    ->select('rewordbeans')
                    ->where('day', '=', $days)
                    ->first();
                if (!empty($ruleInfo->rewordbeans)) {
                    //更新签到表奖励的智慧豆
                    $ressiginin = DB::table('s_signin')->where('uid', '=', $request->uid)
                        ->where('signdate', '=', $date)->update(['rewordbeans' => $ruleInfo->rewordbeans]);
                    //更新用户表里的智慧豆数量
                    $resuid = DB::update('update s_users set wisdombean = (wisdombean +' . $ruleInfo->rewordbeans . ') where id= ? ', [$request->uid]);
//                            if (!$res) {
//                                throw new \Exception('更新用户表里的智慧豆更改失败');
//                            }
                    $rewordbeans = $ruleInfo->rewordbeans;
                    //添加记录
                    $wisdombeanusedata = [
                        'uid' => $request->uid,
                        'type' => 8,
                        'wisdombean' => $ruleInfo->rewordbeans
                    ];

                    $userwisdombean = DB::table('s_users_wisdombeanuse')->insertGetId($wisdombeanusedata);
                } else {
                    $rewordbeans = 0;
                }


                // }
                DB::commit();
                return $this->response('true', 1, $rewordbeans, $request->apilog_id);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->response('false', 0);
            }

        }
    }

    /**
     * 获取用户当月签到天数和获取的智慧豆
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserSigninTotal(Request $request)
    {
//        echo 11;die;
        //判断USER_ID是否存在
        $phone = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$phone->code) {
            return result($phone->msg);
        }
        //获取用户当月签到天数和获取的智慧豆
//        $date = date("Y-m-d");
        $datemonth = date("Y-m");

        $data['days'] = DB::table('s_signin')
            ->where('uid', '=', $request->uid)
            ->where('signmonth', '=', $datemonth)
            ->count('id');

        $data['beans'] = DB::table('s_signin')
            ->where('uid', '=', $request->uid)
            ->where('signmonth', '=', $datemonth)
            ->sum('rewordbeans');

        $data['datelist'] = DB::table('s_signin')->select('signdate')
            ->where('uid', '=', $request->uid)
            ->where('signmonth', '=', $datemonth)
            ->get();

        if (!empty($data)) {
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            $data['days'] = 0;
            $data['beans'] = 0;
            return $this->response('true', 1, $data, $request->apilog_id);
        }
    }


    /**
     * 站内信列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserMessage(Request $request)
    {
        //判断USER_ID是否存在
        $phone = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$phone->code) {
            return result($phone->msg);
        }
        $data = objectToArray(DB::table('s_users_msg')
            ->select('*')
            ->where('uid', '=', $request->uid)
            ->orderBy('id', 'desc')
            ->get()->toArray());
        if ($data) {
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }
    }

    /**
     * 站内信详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserMessageInfo(Request $request)
    {
//        echo 11;die;
        //判断USER_ID是否存在
        $phone = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$phone->code) {
            return result($phone->msg);
        }
        //判断USER_ID是否存在
        $messageid = $this->FormCheck->isEmpty($request->messageid, '信息id');
        $data = DB::table('s_users_msg')
            ->select('*')
            ->where('uid', '=', $request->uid)
            ->where('id', '=', $request->messageid)
            ->first();
        if ($data) {
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }
    }

    /**
     * 站内信更新为已读
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserMessageIsread(Request $request)
    {

        //判断ID是否存在
        $pid = $this->FormCheck->isEmpty($request->id, '列表id');
        if (!$pid->code) {
            return result($pid->msg);
        }
        $data = [
            'is_read' => 2,
        ];

        $res = DB::table('s_users_msg')->where('id', $request->id)->update($data);
//        p($res);die;
        return $this->response('true', 1, $res);
    }

    /**
     * 站内信删除
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserMessageDelete(Request $request)
    {
        //判断ID是否存在
        $pid = $this->FormCheck->isEmpty($request->id, '列表id');
        if (!$pid->code) {
            return result($pid->msg);
        }
        $res = DB::table('s_users_msg')->where('id', $request->id)->delete();
        return $this->response('true', 1, $res);
    }

    /**
     * 闯关列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserGate(Request $request)
    {
        //判断ID是否存在
        $pid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$pid->code) {
            return result($pid->msg);
        }
        //需要传分页 需要处理

        //循环遍历所有关卡
        $data = objectToArray(DB::table('s_gate')
            ->select('*')
            ->get()->toArray());

        if ($data) {
            //查询该用户的通关情况
            foreach ($data as $key => $value) {

                $Info = DB::table('s_gate_record')
                    ->select('id', 'time', 'rewordbeans')
                    ->where('uid', '=', $request->uid)
                    ->where('gateid', '=', $value['id'])
                    ->first();

                //如果有数据，就查询通关获得金豆和时间
                if (!empty($Info)) {
                    //记录闯关情况 1成功 2失败
                    $data[$key]['state'] = 1;
                    $data[$key]['time'] = $Info->time;
                    $data[$key]['rewordbeans'] = $Info->rewordbeans;
                } else {
                    //记录闯关情况 1成功 2失败
                    $data[$key]['state'] = 2;
                    $data[$key]['time'] = 0;
                    $data[$key]['rewordbeans'] = 0;
                }
            }
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            return $this->response('true', 1);
        }
    }

    /**
     * 闯关全网排名
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserGateSort(Request $request)
    {
        //判断ID是否存在
        $gateid = $this->FormCheck->isEmpty($request->gateid, '关卡');
        if (!$gateid->code) {
            return result($gateid->msg);
        }

        //闯关全网排名前50
        $data = DB::table('s_gate_record ' . ' as a')
            ->join('s_users' . ' as b', 'b.id', '=', 'a.uid')
            ->select('b.nickname', 'b.headimg', 'a.time', 'a.rewordbeans')
            ->where('a.gateid', '=', $request->gateid)
            ->limit(50)
            ->orderBy('a.time', 'asc')
            ->get();
        if ($data) {
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            return $this->response('false', 0);
        }
    }

    /**
     * 关卡详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    /*    public function UserGateDetail(Request $request)
        {
            //判断ID是否存在
            $gateid = $this->FormCheck->isEmpty($request->gateid, '关卡');
            if (!$gateid->code) {
                return result($gateid->msg);
            }
            //判断ID是否存在
            $uid = $this->FormCheck->isEmpty($request->uid, '用户');
            if (!$uid->code) {
                return result($uid->msg);
            }
            // $user=DB::table('s_users')->where('id',$request->uid)->first();
            // if($user->manvalue<3){
            //     return $this->response('体力值不足！',0);
            // }

            $data = DB::table('s_gate')
                ->select('*')
                ->where('id', '=', $request->gateid)
                ->first();
            if ($data) {
                // foreach ($data as $key) {
                $data->options = explode(",", $data->options);
                $data->answer = explode(",", $data->answer);

                $nextgateid = $data->id + 1;

                $nextgate = DB::table('s_gate')
                    ->where('id', $nextgateid)->count();
                if ($nextgate > 0) {
                    $data->nextgateid = $nextgateid;
                } else {
                    $data->nextgateid = '';
                }

                // }
                //是否查看过答案

                // $isanswer = DB::table('s_gate_answer')
                //             ->where('gateid', $request->gateid)->where('uid', $request->uid)
                //             ->count();

                // if($isanswer>0){
                //     $data->isanswer=1;
                // }else{
                //     $data->isanswer=0;
                // }

                $user = DB::table('s_users')
                    ->select('*')->where('id', $request->uid)->first();
                $data->userwisdombean = $user->wisdombean;
                $data->manvalue = $user->manvalue;

                return $this->response('true', 1, $data, $request->apilog_id);
            } else {
                return $this->response('false', 0);
            }
        }*/


    public function UserGateDetail(Request $request)
    {
        //判断ID是否存在
        $gateid = $this->FormCheck->isEmpty($request->gateid, '关卡');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        //判断ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        // $user=DB::table('s_users')->where('id',$request->uid)->first();
        // if($user->manvalue<3){
        //     return $this->response('体力值不足！',0);
        // }

        $data = DB::table('s_gate')
            ->select('*')
            ->join('s_gate_subject', 's_gate.id', '=', 's_gate_subject.gate_id')
            ->where('s_gate.id', '=', $request->gateid)
            ->orderBy('s_gate_subject.sort', 'ASC')
            ->get()->toArray();
//        dump($data[0]->gatename);
//        if(!$data){
//            return $this->response('该关卡暂无题目', 0);
//        }
        $i = 1;
        foreach ($data as &$value) {
            $value->options = explode(",", $value->options);
            $value->answer = explode(",", $value->answer);
            $value->subject_name = '第' . $i++ . '题';
        }
        $result['subject'] = $data;
        if ($result['subject']) {

            $nextgateid = $result['subject'][0]->gate_id + 1;

            $nextgate = DB::table('s_gate')
                ->where('id', $nextgateid)->count();

            //关卡中是否有题目
            $nextgate1 = DB::table('s_gate_subject')
                ->where('gate_id', $nextgateid)->count();
            if ($nextgate > 0 && $nextgate1) {
                $result['nextgateid'] = $nextgateid;
            } else {
                $result['nextgateid'] = '';
            }

            // }
            //是否查看过答案

            // $isanswer = DB::table('s_gate_answer')
            //             ->where('gateid', $request->gateid)->where('uid', $request->uid)
            //             ->count();

            // if($isanswer>0){
            //     $data->isanswer=1;
            // }else{
            //     $data->isanswer=0;
            // }

            $user = DB::table('s_users')
                ->select('*')->where('id', $request->uid)->first();
            $result['userwisdombean'] = $user->wisdombean;
            $result['manvalue'] = $user->manvalue;

            //关卡弹窗
            $alert_id = $result['subject'][0]->alert_id;
            $alert_errid = $result['subject'][0]->alert_errid;
            $gate_alert = DB::table('s_gate_alert')
                ->select('*')->where('id', $alert_id)->first();
            $result['gate_alert'] = $gate_alert;
            $gate_erralert = DB::table('s_gate_alert')
                ->select('*')->where('id', $alert_errid)->first();
            $result['gate_erralert'] = $gate_erralert;

            return $this->response('true', 1, $result, $request->apilog_id);
        } else {
            return $this->response('false', 0);
        }
    }


    /**
     * 挑战关卡
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserGateChallenge(Request $request)
    {
        //判断ID是否存在
        $gateid = $this->FormCheck->isEmpty($request->gateid, '关卡');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        $gateid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        // $gateid = $this->FormCheck->isEmpty($request->answer,'提交答案');
        // if (!$gateid->code) {
        //     return result($gateid->msg);
        // }
        $gateid = $this->FormCheck->isEmpty($request->time, '时间');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        // $user=DB::table('s_users')->where('id',$request->uid)->first();
        // if($user->manvalue<3){
        //     return $this->response('体力值不足！',0);
        // }
        // $gateid = $this->FormCheck->isEmpty($request->rewordbeans,'智慧豆');
        // if (!$gateid->code) {
        //     return result($gateid->msg);
        // }

        //检验用户提交的答案是否正确
        $data = DB::table('s_gate')
            ->select('id', 'rewardbeans', 'pkvalue', 'specialreward', 'gaterewordid', 'alert_id')
            ->where('id', '=', $request->gateid)
//            ->where('answer','=',$request->answer)
            ->first();
        //关卡弹窗
        $alert_id = $data->alert_id;

        if ($data && $request->answer == 1) {
            $user = DB::table('s_users')->where('id', $request->uid)->first();
            if ($user->manvalue < 3) {
                return $this->response('体力值不足！', 0);
            }

            //判断是否是用户第一次答题(答题记录)
            $data1 = DB::table('s_gate_record')
                ->select('id', 'time')
                ->where('gateid', '=', $request->gateid)
                ->where('uid', '=', $request->uid)
                ->first();
            $data->time = $request->time;
            if ($data1) {
                //判断时间大小 如果新的时间小于数据库时间就更新
                if ($data1->time > $request->time) {
                    //更新数据
                    $updateData = array(
                        "time" => $request->time,
                    );
                    DB::table('s_gate_record')->where('gateid', '=', $request->gateid)->where("uid", $request->uid)->update($updateData);
                }

                $data->isfirst = 1;

            } else {
                //第一次答题，插入记录 入库
                $insertData = array(
                    "uid" => $request->uid,
                    "gateid" => $request->gateid,
                    "time" => $request->time,
                    "rewordbeans" => $data->rewardbeans,
                );
                DB::table('s_gate_record')->insertGetId($insertData);
                $data->isfirst = 0;

                if ($data->specialreward == 1) {
                    //获得特殊奖励
                    $insertSpe = array(
                        "uid" => $request->uid,
                        "gateid" => $request->gateid,
                        "gaterewordid" => $data->gaterewordid,

                    );
                    DB::table('s_baobox')->insertGetId($insertSpe);
                }

                //PK体力智慧豆操作
                $user = DB::table('s_users')->where('id', $request->uid)->first();
                //更新数据

                DB::table('s_users')->where('id', $request->uid)->update(['manvalue' => ($user->manvalue - 3), 'pk' => ($data->pkvalue + $user->pk), 'wisdombean' => ($user->wisdombean + $data->rewardbeans)]);

                $insertWisdombean = array(
                    "uid" => $request->uid,
                    "type" => 5,
                    "wisdombean" => $data->rewardbeans,

                );
                DB::table('s_users_wisdombeanuse')->insertGetId($insertWisdombean);
            }


            $usernew = DB::table('s_users')->where('id', $request->uid)->first();
            $data->manvalue = $usernew->manvalue;

            //弹窗
            $gate_alert = DB::table('s_gate_alert')
                ->select('*')->where('id', $alert_id)->first();
            $data->gate_alert = $gate_alert;

            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            $user = DB::table('s_users')->where('id', $request->uid)->first();
            //更新数据
            if (($user->manvalue) >= 3) {
                DB::table('s_users')->where('id', $request->uid)->update(['manvalue' => ($user->manvalue - 3)]);
            }
            // DB::table('s_users')->where('id',$request->uid)->update(['manvalue'=>($user->manvalue-3)]);
            $data = DB::table('s_users')->select('manvalue')->where('id', $request->uid)->first();

            //弹窗
            $gate_alert = DB::table('s_gate_alert')
                ->select('*')->where('id', $alert_id)->first();
            $data->gate_alert = $gate_alert;

            return $this->response('闯关失败', 1, $data);
        }

    }


    /**
     * 挑战关卡
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserGateChallenge1(Request $request)
    {
        //判断ID是否存在
        $gateid = $this->FormCheck->isEmpty($request->gateid, '关卡');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        $gateid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        // $gateid = $this->FormCheck->isEmpty($request->answer,'提交答案');
        // if (!$gateid->code) {
        //     return result($gateid->msg);
        // }
        $gateid = $this->FormCheck->isEmpty($request->time, '时间');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        // $user=DB::table('s_users')->where('id',$request->uid)->first();
        // if($user->manvalue<3){
        //     return $this->response('体力值不足！',0);
        // }
        // $gateid = $this->FormCheck->isEmpty($request->rewordbeans,'智慧豆');
        // if (!$gateid->code) {
        //     return result($gateid->msg);
        // }

        //检验用户提交的答案是否正确
        $data = DB::table('s_gate')
            ->select('id', 'rewardbeans', 'pkvalue', 'specialreward', 'gaterewordid')
            ->where('id', '=', $request->gateid)
//            ->where('answer', '=', $request->answer)
            ->first();
        if ($data) {
            $user = DB::table('s_users')->where('id', $request->uid)->first();
            if ($user->manvalue < 3) {
                return $this->response('体力值不足！', 0);
            }

            //判断是否是用户第一次答题(答题记录)
            $data1 = DB::table('s_gate_record')
                ->select('id', 'time')
                ->where('gateid', '=', $request->gateid)
                ->where('uid', '=', $request->uid)
                ->first();
            $data->time = $request->time;
            if ($data1) {
                //判断时间大小 如果新的时间小于数据库时间就更新
                if ($data1->time > $request->time) {
                    //更新数据
                    $updateData = array(
                        "time" => $request->time,
                    );
                    DB::table('s_gate_record')->where('gateid', '=', $request->gateid)->where("uid", $request->uid)->update($updateData);
                }

                $data->isfirst = 1;

            } else {
                //第一次答题，插入记录 入库
                $insertData = array(
                    "uid" => $request->uid,
                    "gateid" => $request->gateid,
                    "time" => $request->time,
                    "rewordbeans" => $data->rewardbeans,
                );
                DB::table('s_gate_record')->insertGetId($insertData);
                $data->isfirst = 0;

                if ($data->specialreward == 1) {
                    //获得特殊奖励
                    $insertSpe = array(
                        "uid" => $request->uid,
                        "gateid" => $request->gateid,
                        "gaterewordid" => $data->gaterewordid,

                    );
                    DB::table('s_baobox')->insertGetId($insertSpe);
                }

                //PK体力智慧豆操作
                $user = DB::table('s_users')->where('id', $request->uid)->first();
                //更新数据

                DB::table('s_users')->where('id', $request->uid)->update(['manvalue' => ($user->manvalue - 3), 'pk' => ($data->pkvalue + $user->pk), 'wisdombean' => ($user->wisdombean + $data->rewardbeans)]);

                $insertWisdombean = array(
                    "uid" => $request->uid,
                    "type" => 5,
                    "wisdombean" => $data->rewardbeans,

                );
                DB::table('s_users_wisdombeanuse')->insertGetId($insertWisdombean);
            }


            $usernew = DB::table('s_users')->where('id', $request->uid)->first();
            $data->manvalue = $usernew->manvalue;
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            $user = DB::table('s_users')->where('id', $request->uid)->first();
            //更新数据
            if (($user->manvalue) >= 3) {
                DB::table('s_users')->where('id', $request->uid)->update(['manvalue' => ($user->manvalue - 3)]);
            }
            // DB::table('s_users')->where('id',$request->uid)->update(['manvalue'=>($user->manvalue-3)]);
            $data = DB::table('s_users')->select('manvalue')->where('id', $request->uid)->first();

            return $this->response('闯关失败', 1, $data);
        }

    }


    /**
     * 百宝箱
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserBaobox(Request $request)
    {
        //判断ID是否存在
//        $gateid = $this->FormCheck->isEmpty($request->gateid,'关卡');
//        if (!$gateid->code) {
//            return result($gateid->msg);
//        }
        $gateid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        $gateid = $this->FormCheck->isEmpty($request->page, '当前页');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        $page_size = 10;
        //获取列表内容
        $data = DB::table('s_baobox as a')
            ->join('s_gate_reword' . ' as b', 'b.id', '=', 'a.gaterewordid')
            ->join('s_gate' . ' as c', 'c.id', '=', 'a.gateid')
            ->select('a.id', 'b.type', 'c.gatename', 'b.heading')
            ->where('uid', '=', $request->uid)
            ->offset(($request->page - 1) * $page_size)->limit($page_size)
            ->get();
        if ($data) {
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            return $this->response('false', 0);
        }

    }

    /**
     * 百宝箱详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserBaoboxDetail(Request $request)
    {
        //判断ID是否存在
        $gateid = $this->FormCheck->isEmpty($request->id, '详情id');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        $boxid = DB::table('s_baobox')
            ->select('*')
            ->where('id', '=', $request->id)
            ->first();

        //获取列表内容
        $data = DB::table('s_gate_reword')
            ->select('*')
            ->where('id', '=', $boxid->gaterewordid)
            ->get();
        if ($data) {
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            return $this->response('false', 0);
        }

    }

    /**
     * 原住民
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserResident(Request $request)
    {

        //检验用户提交的答案是否正确
        $data = DB::table('s_users')->count('id');
        if ($data) {
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            return $this->response('false', 0);
        }

    }


    /*
     * 生成随机字符串
     * @param int $length 生成随机字符串的长度
     * @param string $char 组成随机字符串的字符串
     * @return string $string 生成的随机字符串
     */
    private function str_rand($length = 7, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        if (!is_int($length) || $length < 0) {
            return false;
        }

        $string = '';
        for ($i = $length; $i > 0; $i--) {
            $string .= $char[mt_rand(0, strlen($char) - 1)];
        }

        return $string;
    }

    /**
     * 用户注册 UserRegisterInfo
     * @author weis <524651703@qq.com>
     * @param Request $request
     * @return json
     */
    public function UserRegisterInfo(Request $request)
    {
        $phone = $this->FormCheck->isEmpty($request->phone, '手机号');
        if (!$phone->code) {
            return result($phone->msg);
        }
        $password = $this->FormCheck->isEmpty($request->password, '密码');
        if (!$password->code) {
            return result($password->msg);
        }
        $password = $this->FormCheck->password($request->password, 3);
        if (!$password->code) {
            return result($password->msg);
        }
        //验证两次密码是否一致
        if ($request->password !== $request->repassword) {
            return result('两次密码不一致');
        }
        $name = $this->FormCheck->isEmpty($request->identity, '身份');
        if (!$name->code) {
            return result($name->msg);
        }
        $name = $this->FormCheck->isEmpty($request->headimg, '头像');
        if (!$name->code) {
            return result($name->msg);
        }
        $nickname = $this->FormCheck->isEmpty($request->nickname, '昵称');
        if (!$nickname->code) {
            return result($nickname->msg);
        }
        $sex = $this->FormCheck->isEmpty($request->sex, '用户性别');
        if (!$sex->code) {
            return result($sex->msg);
        }
        $birthday = $this->FormCheck->isEmpty($request->birthday, '用户出生年月日');
        if (!$birthday->code) {
            return result($birthday->msg);
        }
        $education = $this->FormCheck->isEmpty($request->constellation, '星座');
        if (!$education->code) {
            return result($education->msg);
        }
        $city = $this->FormCheck->isEmpty($request->city, '用户所在城市');
        if (!$city->code) {
            return result($city->msg);
        }

        $SmsCode = $this->FormCheck->isEmpty($request->SmsCode, '短信验证码');
        if (!$SmsCode->code) {
            return result($SmsCode->msg);
        }
//        $request->nickname = $this->str_rand();
        // $res = $this->CheckSmsCode($request);
        $code = DB::table('phone_code')->where('phone', $request->phone)->where('isUse', 1)->orderBy('id', 'desc')->first();
        if ($code) {
            if ($code->code == $request->SmsCode) {

                $UserInfo = $this->User->GetPhoneUserData($request);
                if (!empty($UserInfo)) {
                    return $this->Resources('注册失败，用户手机号已被注册', 0, json_decode('{}'));
                }
                $request->token = $this->setToken();

                $UserId = $this->User->UserRegisterInfo($request);
                if ($UserId) {
                    return $this->Resources('true', 1, ['UserId' => $UserId, 'token' => $request->token, 'moneybag' => 0]);
                } else {
                    return $this->Resources('注册失败', 0, json_decode('{}'));
                }
            }
            return $this->Resources('短信验证码失效', 0, json_decode('{}'));
        }
        return $this->Resources('短信验证码失效', 0, json_decode('{}'));
    }

    /**
     * 重置用户密码 UpdataUserPasswdInfo
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param Request $request
     * @return json
     */
    public function UpdataUserPasswdInfo(Request $request)
    {
        $phone = $this->FormCheck->isEmpty($request->phone, '手机号');
        if (!$phone->code) {
            return result($phone->msg);
        }
        $password = $this->FormCheck->isEmpty($request->password, '密码');
        if (!$password->code) {
            return result($password->msg);
        }
        $password = $this->FormCheck->password($request->password, 3);
        if (!$password->code) {
            return result($password->msg);
        }
        //验证两次密码是否一致
        if ($request->password !== $request->repassword) {
            return result('两次密码不一致');
        }
        $SmsCode = $this->FormCheck->isEmpty($request->SmsCode, '短信验证码');
        if (!$SmsCode->code) {
            return result($SmsCode->msg);
        }
//        $request->nickname = $this->str_rand();
        /*
            短信验证逻辑
        */
        $code = DB::table('phone_code')->where('phone', $request->phone)->where('isUse', 1)->orderBy('id', 'desc')->first();
        // $res = $this->CheckSmsCode($request);
        if ($code) {
            if ($code->code == $request->SmsCode) {
                $resPasswd = $this->User->UpdataUserPasswdData($request);
                if ($resPasswd) {
                    return $this->response('修改成功', 1, []);
                } else {
                    return $this->response('修改失败', 0, []);
                }
            }
            return $this->Resources('短信验证码失效', 0, json_decode('{}'));
        }

        return $this->Resources('短信验证码失效', 0, json_decode('{}'));
    }

    /**
     * 通过用户id获取用户信息 GetUserData
     * @author weis <524651703@qq.com>
     * @param Request $request
     * @return json
     */
    public function GetUserData(Request $request)
    {
        $UserData = $this->User->GetUserData($request);
        return $this->response('true', 1, $UserData);
    }

    /**
     * 忘记密码 ForgetPasswdUp
     * @author weis <524651703@qq.com>
     * @param Request $request
     * @return json
     */
    public function ForgetPasswdUp(Request $request)
    {
        $phone = $this->FormCheck->isEmpty($request->phone, '手机号');
        if (!$phone->code) {
            return result($phone->msg);
        }
        $password = $this->FormCheck->isEmpty($request->password, '密码');
        if (!$password->code) {
            return result($password->msg);
        }
        $UserInfo = $this->User->GetPhoneUserData($request);
        if (empty($UserInfo)) {
            return $this->Resources('账号不存在', 0, json_decode('{}'));
        }
        // $res = $this->CheckSmsCode($request);
        // if ($res) {
        $state = $this->User->ForgetPasswdHandle($request);
        if ($state) {
            return $this->Resources('密码修改成功', 1, json_decode('{}'));
        }
        return $this->Resources('密码重置失败', 0, json_decode('{}'));
        // } else {
        //     return $this->Resources('验证码错误',0,json_decode('{}'));
        // }
    }

    /**
     * 发送短信验证码 SendSmsInfo
     * @author weis <524651703@qq.com>
     * @param Request $request
     * @return json
     */
    public function SendSmsInfo(Request $request)
    {
//echo 11;die;
        // $request->type
        // $request->phoneNumbers
        // $request->day
        // $request->name
        $templateParam = [];
        $code = rand(100000, 999999);
        $phoneNumbers = $request->phoneNumbers;
        if (empty($phoneNumbers)) {
            return $this->response('缺少参数', 0, []);
        }
        //判断是否注册
        $isuser = DB::table('s_users')->where('phone', $request->phoneNumbers)->count();
        if ($isuser > 0 && $request->type == 'res') {
            return $this->response('手机号码已注册，请在找回密码中找回密码', 0, []);
        }

        $type = !empty($request->type) ? $request->type : 'login';
        $SmsTemplateInfo = [
            'res' => 'SMS_153675277',//'SMS_123672846',
            'login' => 'SMS_153675277',//'SMS_127060095',// 赠
//            'Tconfirm' => 'SMS_134325264',
//            'yzm' => 'SMS_100965125',
            'forget' => 'SMS_153675277'//'SMS_126255018'
        ];
        $signName = '学学乐';
        $templateCode = $SmsTemplateInfo[$type];
//        echo $templateCode;die;
        if ($type == 'res') {
            $templateParam['code'] = $code;
        }
        if ($type == 'login') {
            $templateParam['code'] = $code;
        }
//        if ($type == 'Tconfirm' && !empty($request->day) && !empty($request->name)) {
//            $templateParam['day'] = $request->day;
//            $templateParam['name'] = $request->name;
//        }
//        if ($type == 'yzm') {
//            $templateParam['code'] = $code;
//        }
        if ($type == 'forget') {
            $templateParam['code'] = $code;
        }
        if (empty($templateParam)) {
            return $this->response('缺少参数', 0);
        }

        $res = $this->AliyunSms->sendSms($signName, $templateCode, $phoneNumbers, $templateParam);

        // $accessKeyId = env("ALISMS_KEY");
        // echo "ALISMS_KEY:".$accessKeyId."</br>";
        // $accessKeySecret = env("ALISMS_SECRETKEY");
        // echo "ALISMS_SECRETKEY:".$accessKeySecret."</br>";

        // p($this->AliyunSms);
//         p($res);exit;
        //return $this->response('发送成功',1,$res);
        if (!empty($res->Code) && $res->Code == 'OK') {
            // Cache::put('mobileSmsCode_'.$code, $code, 5);//存储到cookie里
            $Data = [
                'phone' => $phoneNumbers,
                'code' => $code,
                'expireTime' => Carbon::now('Asia/Shanghai')->addMinutes(5),
                'sendTime' => Carbon::now('Asia/Shanghai')
            ];
            DB::table('phone_code')->insert($Data);
            return $this->response('发送成功', 1, ['code' => '']);
        }
        return $this->response('发送失败', 0);
    }

    public function VerifySmsInfo(Request $request)
    {
        $code = DB::table('phone_code')->where('phone', $request->phone)->where('expireTime', '>=', Carbon::now('Asia/Shanghai'))->where('isUse', 0)->orderBy('id', 'desc')->first();
        if ($code) {

            if ($code->code == $request->code) {

                $res = DB::table('phone_code')->where('id', $code->id)->update(['isUse' => 1]);
                return $this->response('验证成功', 1);
            } else {
                return $this->response('验证码错误', 0);
            }
        } else {
            return $this->response('验证码失效', 0);
        }


    }


    /**
     * 查询全国城市列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function CityList(Request $request)
    {
        //判断是否xie带参数
        // 查询全国省级列表
        $arealib = DB::table('arealib as a')->join('arealib as b', 'a.pid', '=', 'b.id');
        //如果有查询条件
        if (!empty($request->city)) {
            $arealib->where('a.name', 'like', "%{$request->city}%");
        }
        $arealib->where('b.pid', '0');
        $data = $arealib->select(DB::raw('a.*,CONCAT(upper((LEFT(a.nid,1))),substring(a.nid, 2)) as nid'))
            ->orderBy('a.id', 'ASC')
            ->get();


//        $data = objectToArray(DB::table('s_users_msg')
//            ->select('*')
//            ->where('uid','=',$request->uid)
//            ->orderBy('id', 'desc')
//            ->get()->toArray());
        if ($data) {
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }
    }

    public function CityListAll(Request $request)
    {
        //判断是否xie带参数
        // 查询全国省级列表

        //如果有查询条件

        $data = array('A' => array(), 'B' => array(), 'C' => array(), 'D' => array(), 'E' => array(), 'F' => array(), 'G' => array(), 'H' => array(), 'I' => array(), 'J' => array(), 'K' => array(), 'L' => array(), 'M' => array(), 'N' => array(), 'O' => array(), 'P' => array(), 'Q' => array(), 'R' => array(), 'S' => array(), 'T' => array(), 'U' => array(), 'V' => array(), 'W' => array(), 'X' => array(), 'Y' => array(), 'Z' => array());

        foreach ($data as $k => $v) {
            $str = strtolower($k);
            $arealib = DB::table('arealib as a')->join('arealib as b', 'a.pid', '=', 'b.id');
            $arealib->where('a.nid', 'like', $str . '%');
            $arealib->where('b.pid', '0');
            $vdata = $arealib->select('a.*')
                ->orderBy('a.id', 'ASC')
                ->get();
            $data[$k] = $vdata;


        }
        if ($data) {
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }
    }


    /**
     * 推荐课程列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserBaoboxCourse(Request $request)
    {
        //判断ID是否存在
//        $gateid = $this->FormCheck->isEmpty($request->gateid,'关卡');
//        if (!$gateid->code) {
//            return result($gateid->msg);
//        }
        $gateid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        $gateid = $this->FormCheck->isEmpty($request->page, '当前页');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        $page_size = 10;
        //获取列表内容
        $data = DB::table('s_gate_answer as a')
            ->join('s_album_course' . ' as b', 'b.id', '=', 'a.courseid')
            ->join('s_gate' . ' as c', 'c.id', '=', 'a.gateid')
            ->select('a.id', 'c.gatename', 'b.coursename', 'b.courseimg', 'a.courseid')
            ->where('a.uid', '=', $request->uid)
            ->where('b.isdelete', '=', 0)
            ->offset(($request->page - 1) * $page_size)->limit($page_size)
            ->get();
        if ($data) {
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            return $this->response('false', 0);
        }

    }

    /**
     * 百宝箱详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserBaoboxCourseDetail(Request $request)
    {
        //判断ID是否存在
        $gateid = $this->FormCheck->isEmpty($request->id, '详情id');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        // //判断ID是否存在
        // $gateid = $this->FormCheck->isEmpty($request->id,'详情id');
        // if (!$gateid->code) {
        //     return result($gateid->msg);
        // }
        $boxid = DB::table('s_gate_answer')
            ->select('*')
            ->where('id', '=', $request->id)
            ->first();


        //获取列表内容
        $data = DB::table('s_album_course')
            ->select('*')
            ->where('id', '=', $boxid->courseid)
            ->first();
        if ($data) {
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            return $this->response('false', 0);
        }

    }

    public function UserRegular(Request $request)
    {
        $data = DB::table('s_regular_registermember')
            ->select('*')
            ->first();
        if ($data) {
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            return $this->response('false', 0);
        }
    }

}
