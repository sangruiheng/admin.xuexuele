<?php
namespace App\Api\Wisdom\Controllers;

use App\Api\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Api\Wisdom\Models\Wisdom;
use App\Classlib\FormCheck;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Classlib\Weixinpay;
use App\Classlib\AlipayTradeAppPayRequest;
use App\Classlib\AopClient;
use App\Classlib\WeChatAppPay;


/**
 * 首页
 */
class WisdomController extends Controller
{
    protected $users = "s_users";
    protected $signin = "s_signin";
    protected $msg = "s_users_msg";
    public $timestamps   = false;
    /**
     * [__construct 构造方法]
     */
    public function __construct()
    {
        $this->Wisdom = new Wisdom();
        $this->FormCheck = new FormCheck();
    }


    /**
     * 获取当前URL GetHost.
     * @author   < php@163.com>
     * @return string
     */
    private function GetHost($request)
    {
        $Url = $request->getUri();
        $UrlInfo = parse_url($Url);
        $HostStr = $UrlInfo['scheme'].'://'.$UrlInfo['host'];

        return $HostStr;
    }

    /**
     * 获取智慧社列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function WisdomList(Request $request){
        //判断USER_ID是否存在
        $page_size = 10;
        $request->page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        // sort 1=>按时间(综合排序) 2=>按购买数量 3=> 按智慧豆数量 4=>按评分
        if($request->sort == 1) {
            $sort = 'create_time';
            $type = 'DESC';
        }elseif($request->sort == 2) {
            $sort = 'buysum';
            $type = 'DESC';
        }elseif($request->sort == 3) {
            $sort = 'wisdombean';
            $type = 'DESC';
        }elseif($request->sort == 4) {
            $sort = 'coursescore';
            $type = 'DESC';
        }else{
            $sort = 'id';
            $type = 'ASC';
        }
        $data= DB::table('s_album_course')
            ->select('id','coursename','courseimg','wisdombean','coursescore')
            ->where('isdelete',0)
            ->orderBy($sort,"$type")
            ->offset(($request->page-1)*$page_size)->limit($page_size)
            ->get();

            if(!empty($data)){
                return $this->response('true',1,$data,$request->apilog_id);
            }else{
                return $this->response('false',0);
            }
    }


    /**
     * 获取智慧社详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function WisdomDetail(Request $request){
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $courseid = $this->FormCheck->isEmpty($request->courseid,'课程');
        if (!$courseid->code) {
            return result($courseid->msg);
        }
        // $bannersort = $this->FormCheck->isEmpty($request->bannersort,'广告位');
        // if (!$bannersort->code) {
        //     return result($bannersort->msg);
        // }
        $sort = $this->FormCheck->isEmpty($request->sort,'排序方式');
        if (!$sort->code) {
            return result($sort->msg);
        }

        $data['list'] = DB::table('s_album '.' as a')
                ->join('s_users'.' as b','b.id','=','a.uid')
                ->join('s_album_course'.' as c','a.id','=','c.albumid')
                ->select('b.id','b.headimg','b.identity','b.nickname','c.albumid','c.coursename','c.coursecontent','c.coursetxt','c.commentsum','c.coursevoice','c.coursescore','c.coursetime','c.wisdombean','c.free','c.courseimg','c.id as courseid','c.sharesum')
                ->where('c.id','=',$request->courseid)
                ->first();
                //判断课程是否需要解锁 再判断是否已经解锁
                if($data['list']->free == 1){
                    $data['lock'] = 1;
                }else{
                    //查询解锁表，查看是否解锁
                    $res= DB::table('s_album_course_unlock')
                        ->select('id')
                        ->where('uid','=',$request->uid)
                        ->where('courseid','=',$request->courseid)
                        ->first();
                        if($res){
                            $data['lock'] = 1;
                        }else{
                            $data['lock'] = 2;
                        }
                }


            if(!empty($data)){
                $isFollow = DB::table('s_album_follow')
                ->where('uid', '=', $request->uid)
                ->where('followid', '=', $data['list']->id)
                ->count();

                if($isFollow>0){
                    $data['isfollow']=1;
                }else{
                    $data['isfollow']=0;
                }

                $isScore = DB::table('s_album_course_score')
                ->where('uid', '=', $request->uid)
                ->where('courseid', '=', $request->courseid)
                ->count();

                if($isScore>0){
                    $data['isscore']=1;
                }else{
                    $data['isscore']=0;
                }
            if($request->sort == 5){
                //上一课程id
                $last = DB::table('s_album_course')
                    ->select('*')->where('isdelete',0)->where('albumid', $data['list']->albumid)->where('id','<',$request->courseid)->orderBy('id','desc')->first();
                if($last){
                    $lastdata =$last->id;
                }else{
                    $lastdata ='';
                }
                
                //下一课程id
                $next = DB::table('s_album_course')
                ->select('*')->where('isdelete',0)->where('albumid', $data['list']->albumid)->where('id','>',$request->courseid)->orderBy('id','asc')->first();
                if($next){
                    $nextdata =$next->id; 
               }else{
                    $nextdata ='';
               }
                
            }else{

                if($request->sort == 1) {
                    $sort = 'create_time';
                    $type = 'DESC';
                }elseif($request->sort == 2) {
                    $sort = 'buysum';
                    $type = 'DESC';
                }elseif($request->sort == 3) {
                    $sort = 'wisdombean';
                    $type = 'DESC';
                }elseif($request->sort == 4) {
                    $sort = 'coursescore';
                    $type = 'DESC';
                }else{
                    $sort = 'id';
                    $type = 'ASC';
                }

                $alldata= DB::table('s_album_course')
                    ->select('*')
                    ->where('isdelete',0)
                    ->orderBy($sort,"$type")
                   
                    ->get();
                $datacount= DB::table('s_album_course')
                    ->select('*')
                    ->where('isdelete',0)
                    ->orderBy($sort,"$type")
                   
                    ->count();
                $wz=0;
                foreach ($alldata as $key=>$singledata) {
                    if($singledata->id==$request->courseid){
                        $wz=$key;
                        break;
                    }
                }


                
                
                if($wz==0){
                    $lastdata ='';
                }else{
                    foreach ($alldata as $key=>$singledata) {
                        if($key==($wz-1)){
                            $lastdata=$singledata->id;
                            break;
                        }
                    } 
                }
                
                
                if($wz==($datacount-1)){
                    $nextdata ='';
                }else{
                    foreach ($alldata as $key=>$singledata) {
                        if($key==($wz+1)){
                            $nextdata=$singledata->id;
                            break;
                        }
                    } 
                }
            }
            

            if($lastdata){
                $data['lastid']=$lastdata;
            }else{
                $data['lastid']='';
            }
            if($nextdata){
                $data['nextid']=$nextdata;
            }else{
                $data['nextid']='';
            }


            $banner=DB::table('s_banner')
                 ->select('*')->where('sort',$request->bannersort)->first();
            if($banner){
                $data['banner']=$banner; 
            }else{
               $data['banner']=''; 
            }

            if($request->uid==$data['list']->id){
                $data['lock'] = 1;
                $data['isfollow'] = 1;
                $data['isscore'] = 1;
            }

                return $this->response('true',1,$data,$request->apilog_id);
            }else{
                return $this->response('false',0);
            }
    }

      /**
     * 获取智慧社解锁专辑课程列表详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function WisdomDetailList(Request $request){
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->courseid,'课程');
        if (!$uid->code) {
            return result($uid->msg);
        }

        //通过课程id查询同一专辑下所有课程及价格
        $res = DB::table('s_album_course')
            ->select('albumid')
            ->where('id','=',$request->courseid)
            ->first();
        if($res->albumid){

            $data['albumname'] = DB::table('s_album '.' as a')
                            ->join('s_album_course'.' as c','a.id','=','c.albumid')
                            ->select('a.albumname')
                            ->where('c.albumid','=',$res->albumid)
                            ->first();
            $data['list'] = DB::table('s_album '.' as a')
                            ->join('s_album_course'.' as c','a.id','=','c.albumid')
                            ->select('c.albumid','c.coursename','c.wisdombean','c.free','c.courseimg')
                            ->where('c.albumid','=',$res->albumid)
                            ->where('c.isdelete',0)
                            ->get();
            //课程智慧豆总数量
            $data['sum'] = DB::table('s_album_course')
                ->where('albumid','=',$res->albumid)
                ->where('isdelete',0)
                ->sum('wisdombean');
            $userwisdombean = DB::table('s_users')->select('wisdombean')
                ->where('id','=',$request->uid)
                ->first();
            $data['userwisdombean']= $userwisdombean->wisdombean;
            if(!empty($data)){
                return $this->response('true',1,$data,$request->apilog_id);
            }else{
                return $this->response('false',0);
            }
        }else{
            return $this->response('false',0);
        }

    }



    /**
     * 获取智慧社课程解锁
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function WisdomUnlock(Request $request){
        //判断USER_ID是否存在
        $courseid = $this->FormCheck->isEmpty($request->courseid,'课程');
        if (!$courseid->code) {
            return result($courseid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        // $uid = $this->FormCheck->isEmpty($request->wisdombean,'解锁需要的智慧豆');
        // if (!$uid->code) {
        //     return result($uid->msg);
        // }
        //事务处理
        //加一条购买解锁记录
            DB::beginTransaction();
            try {
                $data = [
                    'uid' => $request->uid,
                    'courseid' => $request->courseid,
                ];
                $count = DB::table('s_album_course_unlock')->where('uid',$request->uid)->where('courseid',$request->courseid)->count();
                if ($count>0) {
                    throw new \Exception('已有记录，请勿重复购买');
                }
                //判断用户剩余智慧豆
                $course = DB::table('s_album_course')->select('*')->where('id',$request->courseid)->first();
                $userhave = DB::table('s_users')->select('*')->where('id',$request->uid)->first();
                if ($course->wisdombean>$userhave->wisdombean) {
                    throw new \Exception('用户智慧豆不足');
                }

                $res = DB::table('s_album_course_unlock')->insertGetId($data);
                if (!$res) {
                    throw new \Exception('入库解锁表失败');
                }
                if($res){
                    //将用户表里的智慧豆减去消耗的智慧豆数量
                    // $course = DB::table('s_album_course')->select('*')->where('id',$request->courseid)->first();
                    $resdata = DB::update('update s_users set wisdombean = (wisdombean - ' .$course->wisdombean. ') where id= ? ', [$request->uid]);

                    // if (!$resdata) {
                    //     throw new \Exception('更新用户表里的智慧豆失败');
                    // }

                    
                    $platrewardbeans = DB::table('s_regular_score')->select('*')->where('id','2')->first();
                    //解锁课程
                    $wisdombeanusedata = [
                        'uid' => $request->uid,
                        'type' => 1,
                        'wisdombean' => $course->wisdombean,
                        'rewardplatform' => ($platrewardbeans->platrewardbeans/100)*$course->wisdombean
                    ];
                    //插入订单信息
                    $wisdombeanuse = DB::table('s_users_wisdombeanuse')->insertGetId($wisdombeanusedata);
                    if (!$wisdombeanuse) {
                        throw new \Exception('插入订单信息失败');
                    }
                   $album = DB::table('s_album')->select('*')->where('id',$course->albumid)->first();
                    //插入订单明细
                    $wisdombeanusedetaildata = [
                        'wisdombeanuseid' => $wisdombeanuse,
                        'albumid' => $course->albumid,
                        // 'coursenum' => 1,
                        'albumname' => $album->albumname,
                        'albumuid' => $album->uid,
                        'courseid' => $course->id,
                    ];
                    $wisdombeanusedetail = DB::table('s_users_wisdombeanuse_details')->insertGetId($wisdombeanusedetaildata);

                    if (!$wisdombeanusedetail) {
                        throw new \Exception('插入订单详情信息失败');
                    }

                    //导师收入
                    
                    $daoshidata = [
                        'uid' => $album->uid,
                        'type' => 4,
                        'wisdombean' => $course->wisdombean-($platrewardbeans->platrewardbeans/100)*$course->wisdombean,
                        'rewardplatform' => 0
                    ];
                    //插入订单信息
                    $daoshi = DB::table('s_users_wisdombeanuse')->insertGetId($daoshidata);
                    if (!$daoshi) {
                        throw new \Exception('插入订单信息失败');
                    }
                    $shouru =$course->wisdombean-($platrewardbeans->platrewardbeans/100)*$course->wisdombean;
                    //将用户表里导师的智慧豆加上
                    $daoshidata = DB::update('update s_users set wisdombean = (wisdombean + ' .$shouru. ') where id= ? ', [$album->uid]);
                    // if (!$daoshidata) {
                    //     throw new \Exception('更新用户表里的智慧豆失败');
                    // }
                    //增加获得的智慧豆
                    $courseget = DB::update('update s_album_course set buysum = (buysum + ' .$shouru. ') where id= ? ', [$course->id]);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->response($e->getMessage(),0);
            }

        return $this->response('true',1,$res,$request->apilog_id);
    }

    /**
     * 广告列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function BannerList(Request $request){
        //判断USER_ID是否存在

        $data= DB::table('s_banner')
            ->select('*')
            ->orderBy('sort',"asc")
            ->get();

        if(!empty($data)){
            return $this->response('true',1,$data,$request->apilog_id);
        }else{
            return $this->response('false',0);
        }

    }

    /**
     * 判断是否关注
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function Follow(Request $request)
    {
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->followid, '被关注人id');
        if (!$uid->code) {
            return result($uid->msg);
        }

        $data = DB::table('s_album_follow')
            ->select('id')
            ->where('uid', '=', $request->uid)
            ->where('followid', '=', $request->followid)
            ->first();

        if (!empty($data)) {
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            return $this->response('false', 0);
        }
    }


    /**
     * 点击关注
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function FollowSpot(Request $request){
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->followid,'被关注人id');
        if (!$uid->code) {
            return result($uid->msg);
        }
        //查看是否已经关注 如果没有 就加关注
        $data= DB::table('s_album_follow')
            ->select('id')
            ->where('uid','=',$request->uid)
            ->where('followid','=',$request->followid)
            ->first();

        if(!empty($data)){
            return $this->response('true',1,$data,$request->apilog_id);
        }else{
            $data = [
                'uid' => $request->uid,
                'followid' => $request->followid,
            ];
            $res = DB::table('s_album_follow')->insertGetId($data);
            return $this->response('true',1,$res,$request->apilog_id);
        }
    }
        /**
     * 取消关注
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function FollowNot(Request $request)
    {
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->followid, '被关注人id');
        if (!$uid->code) {
            return result($uid->msg);
        }
        //查看是否已经关注
        $data = DB::table('s_album_follow')
            ->select('id')
            ->where('uid', '=', $request->uid)
            ->where('followid', '=', $request->followid)
            ->first();

        if (!empty($data)) {
            DB::table('s_album_follow')
                ->where('uid', '=', $request->uid)
                ->where('followid', '=', $request->followid)
                ->delete();
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {

            return $this->response('true', 1, $data, $request->apilog_id);
        }
    }

    /**
     * 判断是否可评分
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function Score(Request $request)
    {
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->courseid, '课程id');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $data = DB::table('s_album_course_score')
            ->select('id')
            ->where('uid', '=', $request->uid)
            ->where('courseid', '=', $request->courseid)
            ->first();

        if (!empty($data)) {
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            return $this->response('false', 0);
        }
    }

    /**
     * 点击评分
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function ScoreDo(Request $request){
//        echo 11;die;
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->courseid,'课程id');
        if (!$uid->code) {
            return result($uid->msg);
        }
        if(!is_numeric($request->coursescore) || strpos($request->coursescore,".") !== false){
            return result('评分异常');
        }
        if(!is_numeric($request->wisdombean) || strpos($request->wisdombean,".") !== false){
            return result('打赏的智慧豆异常');
        }

        $data = [
            'uid' => $request->uid,
            'coursescore' => $request->coursescore,
            'courseid' => $request->courseid,
            'wisdombean' => $request->wisdombean,
        ];
        $res['insertid'] = DB::table('s_album_course_score')->insertGetId($data);
//        if($res['insertid']){
            //修改平均评分
        $count = DB::table('s_album_course_score')->select('id')->where('courseid','=',$request->courseid)->count();

        $sum   = DB::table('s_album_course_score')->where('courseid','=',$request->courseid)->sum('coursescore');

        $res['average'] =  $score = round($sum/$count,1);
//        p($score);die;
            //将平均分更新到课程表里
            DB::table('s_album_course')->where('id', $request->courseid)->update(['coursescore'=>$score]);

        //扣除打赏人豆子
            $userpay = [
                'uid' => $request->uid,
                'type' => 2,
                'wisdombean' => $request->wisdombean
            ];
            DB::table('s_users_wisdombeanuse')->insertGetId($userpay);
            $resuserpay = DB::update('update s_users set wisdombean = (wisdombean - ' .$request->wisdombean. ') where id= ? ', [$request->uid]);

        //导师收入、课程收入
            $course = DB::table('s_album_course')->select('*')->where('id',$request->courseid)->first();
            $album = DB::table('s_album')->select('*')->where('id',$course->albumid)->first();
            $userget = [
                'uid' => $album->uid,
                'type' => 3,
                'wisdombean' => $request->wisdombean
            ];
            DB::table('s_users_wisdombeanuse')->insertGetId($userget);
            $resuserpay = DB::update('update s_users set wisdombean = (wisdombean + ' .$request->wisdombean. ') where id= ? ', [$album->uid]);

            DB::update('update s_album_course set buysum = (buysum + ' .$request->wisdombean. ') where id= ? ', [$request->courseid]);


            return $this->response('true',1,$res,$request->apilog_id);
//        }else{
//            return $this->response('false',0);
//        }
    }

 /**
     * 课程总打赏数量
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function ScoreWisdombeanSum(Request $request){
        $uid = $this->FormCheck->isEmpty($request->courseid,'课程id');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $data   = DB::table('s_album_course_score')->where('courseid','=',$request->courseid)->sum('wisdombean');
        return $this->response('true',1,$data,$request->apilog_id);

    }

 /**
     * 各级评分数量列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function ScoreList(Request $request){
        $uid = $this->FormCheck->isEmpty($request->courseid,'课程id');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        // $data= DB::table('s_album_course_score')
        //     ->select('coursescore',DB::raw('count(id) as total'))
        //     ->where('courseid','=',$request->courseid)
        //     ->where('uid','=',$request->uid)
        //     ->groupBy('coursescore')
        //     ->get();
        $data =  array(array('coursescore'=>'3','sum'=>'0'),array('coursescore'=>'5','sum'=>'0'),array('coursescore'=>'7','sum'=>'0'),array('coursescore'=>'10','sum'=>'0'));

        foreach ($data as $k=>$v) 
        {   
            
            $res = DB::table('s_album_course_score')
            ->select('coursescore',DB::raw('count(id) as total'))
            ->where('courseid','=',$request->courseid)
            // ->where('uid','=',$request->uid)
            ->where('coursescore','=',$v['coursescore'])
            ->groupBy('coursescore')
            ->first();
            
            if($res){
               $data[$k]['sum']=$res->total; 
            }
            
            
            
        }
        return $this->response('true',1,$data,$request->apilog_id);

    }



    /**
     * [GetAccountBalanceInfo 获取账户余额]
     * @param [type] $request [description]
     */
    public function GetAccountBalanceInfo(Request $request){
        $data = [];
        $data = $this->Wisdom->GetAccountBalanceInfo($request);
        if (empty($data)) {
            $data['moneybag'] = 0.00;
        }
        return $this->response('true',1,$data);
    }


    /**
     * 钱包充值记录
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function WalletRecord(Request $request){
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }

        $data= DB::table('s_order')
            ->select('*')
            ->where('uid','=',$request->uid)
            ->where('state',2)->orderBy('id','desc')
            ->get();

        if(!empty($data)){
            return $this->response('true',1,$data,$request->apilog_id);
        }else{
            $data=[];
            return $this->response('true',1,$data,$request->apilog_id);
        }

    }





    /**
     * 支付宝支付账户充值 AliPay.
     * @author 
     * @param Request $request
     * @return string
     */
    public function AliPay(Request $request)
    {
        // $request->UserId 用户id
        // $request->order_money 充值金额
        // $request->equipment 设备信息（ios、android）
        $UserId = $this->FormCheck->isEmpty($request->uid, '用户id');
        if (!$UserId->code) {
            return result($UserId->msg);
        }

        $order_no = get_ordernumber();
        $request->order_no = $order_no;

        $str = $request->uid;
        Cache::put('order_nu:'.$order_no, $str, 5); //存储到cookie里

        // 添加订单信息
        $request->complaint = 2;
        // 添加消费记录
        $request->origin = 1; // 支付方式 支付宝
        $res = $this->Wisdom->AddOrderInfo($request);
        if (!$res) {
            return $this->response('订单添加失败', 0, json_decode("{}"));
        }

        // // 创建支付单。
        $alipay = app('alipay.mobile');
        $alipay->notify_url = $this->GetHost($request).'/api/Wisdom/AliPayNotify';
        $alipay->setOutTradeNo($order_no);
        $alipay->setTotalFee($res['order_money']);
        $alipay->setSubject('账户充值-'.$res['order_money']);
        $alipay->setBody('账户充值'."*{$res['order_money']}");
        // 返回签名后的支付参数给支付宝移动端的SDK。
        $dataStr = $alipay->getPayPara();
        $data = [];
        $res_info = [];
        $info = explode('&', urldecode($dataStr));
        foreach ($info as $key => $value) {
            $str_info = explode('=', $value);
            $data[$str_info[0]] = $str_info[1];
        }

        $body = $data['body'];
        $out_trade_no = $data['out_trade_no'];
        $passback_params = 'passback';
        $product_code = 'QUICK_MSECURITY_PAY';
        $subject = $data['subject'];
        $total_amount = $res['order_money'];

        $alipayAppPayConfig = Config::get('pay.alipay.app');
        $aop = new AopClient();
        $aop->appId = $alipayAppPayConfig['appId'];
        $aop->rsaPrivateKey = $alipayAppPayConfig['rsaPrivateKey'];
        $aop->signType = $alipayAppPayConfig['signType'];
        $AppPayRequest = new AlipayTradeAppPayRequest();

        $bizcontent = "{\"body\":\"$body\","
            . "\"subject\": \"$subject\","
            . "\"out_trade_no\": \"$out_trade_no\","
            . "\"total_amount\": \"$total_amount\","
            . "\"passback_params\": \"$passback_params\","
            . "\"product_code\":\"QUICK_MSECURITY_PAY\""
            . "}";

        $AppPayRequest->setNotifyUrl($this->GetHost($request).'/api/Wisdom/AliPayNotify'); // $alipayAppPayConfig['notifyUrl']
        $AppPayRequest->setBizContent($bizcontent);
        $response = $aop->sdkExecute($AppPayRequest);

        return result('创建APP预支付成功', 1, $response);



        //<---------------------------------->
        // // 创建支付单。
        // $alipay = app('alipay.web');
        //
        // $alipay->notify_url = $this->GetHost($request) . "/api/Wisdom/AliPayNotify";
        //
        // $alipay->setOutTradeNo($order_no);
        // $alipay->setTotalFee($res['order_money']);
        // $alipay->setSubject('账户充值-'.$res['order_money']);
        // $alipay->setBody('账户充值'."*{$res['order_money']}");
        //
        // $alipay->setQrPayMode('4'); //该设置为可选，添加该参数设置，支持二维码支付。
        //
        // // 跳转到支付页面。
        // return redirect()->to($alipay->getPayLink());
    }

    /**
     * 支付宝支付充值回调 AliPayNotify.
     * @author   < php@163.com>
     * @param Request $request
     */
    public function AliPayNotify(Request $request)
    {
        $order_no = $request->out_trade_no;
        if (empty($order_no)) {
            return $this->Resources('false', 0, ['msg' => '订单验证失败']);
        }
        //$ResInfoStr = '42_2018080115114702091302';
        $ResInfoStr = Cache::get('order_nu:'.$order_no); //获取cookie里的数据
        Cache::put('order_nu:'.$order_no, '', 5); //存储到cookie里
        if (empty($ResInfoStr)) {
            return $this->Resources('false', 0, ['msg' => '订单验证失败']);
        }
        $StrInfo = explode('_', $ResInfoStr);
        $request->state = 1;
        $request->UserId = $StrInfo[0];
        // 充值金额几次统计
        $request->type = 4;
        $res = $this->Wisdom->UpdateAccountInfo($request);
        if ($res) {
            return $this->Resources('true', 1, ['msg' => '订单验证成功']);
        } else {
            return $this->Resources('false', 0, ['msg' => '订单验证失败']);
        }
    }






    /**
     * 微信支付账户充值 WalletOrderWxPay.
     * @author weis
     * @param Request $request
     * @return json
     */
    public function WalletOrderWxPay(Request $request)
    {
        // $request->UserId 用户id
        // $request->order_money 充值金额
        // $request->equipment 设备信息（ios、android）
        $UserId = $this->FormCheck->isEmpty($request->uid, '用户id');
        if (!$UserId->code) {
            return result($UserId->msg);
        }

        $order_no = get_ordernumber();
        $request->order_no = $order_no;
        $notify_url = $this->GetHost($request) . '/api/Wisdom/WxPayNotify';
        // $notify_url = 'http://mweb.recallg.cn/WxPayNotify'; //
//        $str = $request->UserId.'_'.$request->equipment;
        Cache::put('order_nu:'.$order_no, $request->uid, 5); //存储到cookie里

        // 添加订单信息
        $request->complaint = 1;
        // 添加消费记录
        $request->origin = 1; // 支付方式 微信
        $res = $this->Wisdom->AddOrderInfo($request);
        if (!$res) {
            return $this->response('订单添加失败', 0, json_decode("{}"));
        }

        $request->fee = $res['wisdombean'] * 100;

        $wechatAppPay = new WeChatAppPay($this->PayConf['APPID'], $this->PayConf['MCHID'], $notify_url,$this->PayConf['KEY']);
        $params['body'] = '账户充值-'.$request->fee;
        $params['out_trade_no'] = $order_no;
        $params['attach'] = 'attach';
        $params['total_fee'] = $request->fee;
        $params['trade_type'] = 'APP';
        $result = $wechatAppPay->unifiedOrder( $params );

        // 创建APP端预支付参数
        $res = @$wechatAppPay->getAppPayParams( $result['prepay_id'] );
        return $this->response('下单成功', 1, $res);

        // // 实例化微信支付类
        // $WeixinpayObj = new Weixinpay($this->PayConf);
        //
        // $timeint = time();
        // $goods_info = $request->goods_info;
        // $orderInfo = [
        //     'body' => '账户充值-'.$request->fee,
        //     'total_fee' => $request->fee,
        //     'fee_type' => 'CNY',
        //     'out_trade_no' => $order_no,
        //     'timeStamp' => $timeint,
        // ];
        // $result = $WeixinpayObj->unifiedOrder($orderInfo, $notify_url, 'APP');
        // $result['timeStamp'] = $timeint;
        // return $this->Resources('下单成功', 1, $result);
    }

    /**
     * 微信支付回调地址 WxPayNotify.
     * @author   < php@163.com>
     * @param Request $request
     * @return json
     */
    public function WxPayNotify(Request $request)
    {
        $result = '';
        $WeixinpayObj = new Weixinpay($this->PayConf);
        // 获取xml
        $xml = $request->getContent();
        // 转成php数组
        $data = $WeixinpayObj->toArray($xml);
        // 保存原sign
        $data_sign = $data['sign'];
        // sign不参与签名
        unset($data['sign']);
        $sign = $WeixinpayObj->makeSign($data);
        // 判断签名是否正确  判断支付状态
        if ($sign === $data_sign && $data['return_code'] == 'SUCCESS' && $data['result_code'] == 'SUCCESS') {
            $result = $data;
        } else {
            $result = false;
        }
        // 返回状态给微信服务器
        if (!empty($result)) {

            $order_no = $result['out_trade_no'];
            if (empty($order_no)) {
                return $this->Resources('false', 0, ['msg' => '订单验证失败']);
            }
            //$ResInfoStr = '42_2018080115114702091302';
            $ResInfoStr = Cache::get('order_nu:'.$order_no); //获取cookie里的数据
            Cache::put('order_nu:'.$order_no, '', 5); //存储到cookie里
            if (empty($ResInfoStr)) {
                return $this->Resources('false', 0, ['msg' => '订单验证失败']);
            }
            $StrInfo = explode('_', $ResInfoStr);
            $request->state = 1;
            $request->UserId = $StrInfo[0];
            $request->out_trade_no = $order_no;
            $request->trade_no = $result['transaction_id'];
            $res = $this->Wisdom->UpdateAccountInfo($request);
//            // 充值金额几次统计
//            $request->type = 4;
//            $this->User->StatisticsUserPayInfo($request);
            if ($res) {
                return $this->Resources('订单验证成功', 1, []);
            } else {
                return $this->Resources('订单验证失败', 0, []);
            }
        }

        return $this->Resources('订单验证失败', 0, []);
    }











    /**
     * 钱包充值
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function WalletOrder(Request $request)
    {
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->wisdombean, '充值智慧豆数量');
        if (!$uid->code) {
            return result($uid->msg);
        }

        $dataUP = [
            'uid' => $request->uid,
            'wisdombean' => $request->wisdombean,
        ];
        $data = DB::table('s_order')->insertGetId($dataUP);

        if ($data) {
            return $this->response('true', 1, $data, $request->apilog_id);
        } else {
            return $this->response('false', 0);
        }
    }

    /**
     * 智慧豆使用记录
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function WalletWisdombeanUse(Request $request){
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $data = DB::table('s_users_wisdombeanuse')
            ->select('*')
            ->where('uid', '=', $request->uid)
            ->orderBy('id','desc')
            ->get();
        if($data){
            return $this->response('true',1,$data,$request->apilog_id);
        }else{
            return $this->response('false',0);
        }
    }


    /**
     * 课程留言列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function CommentList(Request $request){
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->courseid,'课程');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $request->page_size = 10;
        $request->page = !empty($request->page) ? $request->page : 1;
        $data['list'] = DB::table('s_album_course_comment')
            ->select('*')
            ->where('courseid', '=', $request->courseid)
            ->where('pid', '=', 0)
            ->offset(($request->page-1)*($request->page_size))
            ->limit(10)
            ->orderBy('id','DESC')
            ->get();
        $commentcount=DB::table('s_album_course')->where('id', '=', $request->courseid)->first();
        $data['commentcount']=$commentcount->commentsum;
        //加入头像 用户名
        foreach($data['list'] as $k=>$v){
//            echo $v->uid;die;
            //获取uid [uid] => 1
            if(!empty($v->uid)){
                $id = $v->uid;
                $userinfo = DB::table($this->users)->select('nickname','headimg')->where('id',$id)->first();
                //将查询结果赋值
                $v->userinfo = $userinfo;
            }
            //判断是否有回复信息 pid = id
            $res = DB::table('s_album_course_comment')->select('*')->where('pid',$v->id)->first();
            if($res){
                //将查询结果赋值
                $v->reply = $res->content;
            }else{
                //将查询结果赋值
                $v->reply = '';
            }


        }




        if($data){
            return $this->response('true',1,$data,$request->apilog_id);
        }else{
            return $this->response('false',0);
        }
    }

    /**
     * 课程留言发布
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function CommentRelease(Request $request){
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->courseid,'课程');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->content,'内容');
        if (!$uid->code) {
            return result($uid->msg);
        }
        //对内容要加判断？？？

        $data = [
            'uid' => $request->uid,
            'courseid' => $request->courseid,
            'content' => $request->content,
        ];
        $res = DB::table('s_album_course_comment')->insertGetId($data);

        //数量统计
        $count = DB::table('s_album_course_comment')->where('courseid',$request->courseid)->where('pid',0)->count();
        $course = DB::table('s_album_course')->where('id',$request->courseid)->update(['commentsum' => $count]);

        if($res){
            return $this->response('true',1);
        }else{
            return $this->response('false',0);
        }
    }

    /**
     * 课程留言发布
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function CommentReply(Request $request){
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->courseid,'课程');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->uid,'导师id');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->content,'内容');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->commentid,'评论列表的id');
        if (!$uid->code) {
            return result($uid->msg);
        }
        //对内容要加判断？？？

        $data = [
            'uid' => $request->uid,
            'courseid' => $request->courseid,
            'content' => $request->content,
            'pid' => $request->commentid,
        ];
        $res = DB::table('s_album_course_comment')->insertGetId($data);

        if($res){
            return $this->response('true',1);
        }else{
            return $this->response('false',0);
        }

    }

    /**
     * 专辑列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function AlbumCourseList(Request $request){
        //判断USER_ID是否存在
        $albumid = $this->FormCheck->isEmpty($request->albumid,'专辑id');
        if (!$albumid->code) {
            return result($albumid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        

        $res = DB::table('s_album as a')->leftjoin('s_album_course as b','b.albumid','=','a.id')
            ->select('a.*') 
            ->where('a.id', '=', $request->albumid)
            ->first();
        if (!empty($res)) {
            $data['id']=$res->id;
            $data['albumname']=$res->albumname;
            $data['albumimg']=$res->albumimg;
            $data['albumcontent']=$res->albumcontent;
            $data['courselist']=DB::table('s_album_course')
            ->select('*') 
            ->where('albumid', '=', $res->id)
            ->where('isdelete',0)
            ->get();

            foreach ($data['courselist'] as $k=>$v) 
            {   
                
                $islock = DB::table('s_album_course_unlock')->where('courseid', $v->id)->where('uid', $request->uid)->count('id');
                
                if($islock>0){
                    $v->islock=1;
                }else{
                    $v->islock=0;
                }
                if($request->uid==$res->uid){
                    $v->islock=1;
                }
                
                
            }
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }

    }

    /**
     * 充值金额列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function MoneyRuleList(Request $request){
        
        
        $res = DB::table('s_regular_score')
            ->select('money')
            ->where('id', '=', '3')
            ->first();
        if (!empty($res)) {
            
            $newstr = substr($res->money,0,strlen($res->money)-1); 
            $data=explode(',',$newstr);
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }

    }

    /**
     * 导师详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function TutorDetail(Request $request)
    {

        //判断followid是否存在
        $id = $this->FormCheck->isEmpty($request->id, '导师id');
        if (!$id->code) {
            return result($id->msg);
        }

        $res = DB::table('s_users')
            ->select('id','headimg','nickname','introduction') 
            ->where('id', '=', $request->id)
            ->first();
        if (!empty($res)) {
            $data['uid']=$res->id;
            $data['headimg']=$res->headimg;
            $data['nickname']=$res->nickname;
            $data['introduction']=$res->introduction;
            $data['albumlist']=DB::table('s_album')
            ->select('*') 
            ->where('uid', '=', $res->id)
            ->get();
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }
            

    }

    /**
     * 举报
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function Regular(Request $request)
    {

        //判断uid是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        //判断courseid是否存在
        $courseid = $this->FormCheck->isEmpty($request->courseid, '课程id');
        if (!$courseid->code) {
            return result($courseid->msg);
        }
        //判断classify是否存在
        $classify = $this->FormCheck->isEmpty($request->classify, '举报类型');
        if (!$classify->code) {
            return result($classify->msg);
        }

        DB::beginTransaction();
        try {
            $isregular = DB::table('s_regular')->where('courseid',$request->courseid)->count();
            if($isregular>0){
                DB::rollBack();
                return $this->response('true',1);
            }
            $reportedid= DB::table('s_album as a')->join('s_album_course as b', 'a.id', '=', 'b.albumid')
                ->select('a.uid','a.id') 
                ->where('b.id', '=', $request->courseid)
                ->first();
            
            $data = [
                'uid' => $request->uid,
                'albumid'=>$reportedid->id,
                'reportedid' => $reportedid->uid,
                'courseid' => $request->courseid,
                'classify' => $request->classify,
                'state' => 1,
            ];
            $res = DB::table('s_regular')->insertGetId($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response($e->getMessage(),0);
        }
        
        return $this->response('true',1,$res);
            

    }



    /**
     * 获取智慧社全部课程解锁
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function WisdomUnlockAll(Request $request){
        //判断USER_ID是否存在
        $albumid = $this->FormCheck->isEmpty($request->albumid,'课程');
        if (!$albumid->code) {
            return result($albumid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }

        //事务处理
        //加一条购买解锁记录
            DB::beginTransaction();
            try {
                
                
                //判断用户剩余智慧豆
                $coursesum = DB::table('s_album_course')->whereNotExists(function ($query) use ($request) {
                                    $query->select(DB::raw(1))
                                          ->from('s_album_course_unlock')
                                          ->whereRaw('s_album_course_unlock.courseid = s_album_course.id')->whereRaw('s_album_course_unlock.uid = '.$request->uid);
                                })->where('albumid',$request->albumid)
                                ->where('isdelete',0)
                                ->sum('s_album_course.wisdombean');
                
                $userhave = DB::table('s_users')->select('*')->where('id',$request->uid)->first();
                if ($coursesum>$userhave->wisdombean) {
                    throw new \Exception('用户智慧豆不足');
                }

                $coursedata = DB::table('s_album_course')->whereNotExists(function ($query) use ($request) {
                                    $query->select(DB::raw(1))
                                          ->from('s_album_course_unlock')
                                          ->whereRaw('s_album_course_unlock.courseid = s_album_course.id')->whereRaw('s_album_course_unlock.uid = '.$request->uid);
                                })->where('albumid',$request->albumid)
                                ->where('isdelete',0)
                                ->get();
                $platrewardbeans = DB::table('s_regular_score')->select('*')->where('id','2')->first();


                $wisdombeanall=0;

                foreach ($coursedata as $coursewisdombean) {
                    $wisdombeanall=$wisdombeanall+$coursewisdombean->wisdombean;
                }
                //解锁课程
                $wisdombeanusedata = [
                    'uid' => $request->uid,
                    'type' => 1,
                    'wisdombean' => $wisdombeanall,
                    'rewardplatform' => ($platrewardbeans->platrewardbeans/100)*$wisdombeanall
                ];
                //插入订单信息
                $wisdombeanuse = DB::table('s_users_wisdombeanuse')->insertGetId($wisdombeanusedata);


                if (!$wisdombeanuse) {
                    throw new \Exception('插入订单信息失败');
                }

                foreach ($coursedata as $course) {
                    $data = [
                        'uid' => $request->uid,
                        'courseid' => $course->id,
                    ];

                    $res = DB::table('s_album_course_unlock')->insertGetId($data);
                    if (!$res) {
                        throw new \Exception('入库解锁表失败');
                    }
                    if($res){
                        //将用户表里的智慧豆减去消耗的智慧豆数量
                        // $course = DB::table('s_album_course')->select('*')->where('id',$request->courseid)->first();
                        $resdata = DB::update('update s_users set wisdombean = (wisdombean - ' .$course->wisdombean. ') where id= ? ', [$request->uid]);
                        
                        // if (!$resdata) {
                        //     throw new \Exception('更新用户表里的智慧豆失败');
                        // }

                        
                        
                        $album = DB::table('s_album')->select('*')->where('id',$course->albumid)->first();

                        //插入订单明细
                        $wisdombeanusedetaildata = [
                            'wisdombeanuseid' => $wisdombeanuse,
                            'albumid' => $course->albumid,
                            // 'coursenum' => 1,
                            'courseid' => $course->id,
                            'albumname' => $album->albumname,
                            'albumuid' => $album->uid,
                        ];
                        $wisdombeanusedetail = DB::table('s_users_wisdombeanuse_details')->insertGetId($wisdombeanusedetaildata);

                        if (!$wisdombeanusedetail) {
                            throw new \Exception('插入订单详情信息失败');
                        }

                        //导师收入
                        
                        $daoshidata = [
                            'uid' => $album->uid,
                            'type' => 4,
                            'wisdombean' => $course->wisdombean-($platrewardbeans->platrewardbeans/100)*$course->wisdombean,
                            'rewardplatform' => 0
                        ];
                        //插入订单信息
                        $daoshi = DB::table('s_users_wisdombeanuse')->insertGetId($daoshidata);
                        if (!$daoshi) {
                            throw new \Exception('插入订单信息失败');
                        }
                        $shouru =$course->wisdombean-($platrewardbeans->platrewardbeans/100)*$course->wisdombean;
                        //将用户表里导师的智慧豆加上
                        $daoshidata = DB::update('update s_users set wisdombean = (wisdombean + ' .$shouru. ') where id= ? ', [$album->uid]);
                        // if (!$daoshidata) {
                        //     throw new \Exception('更新用户表里的智慧豆失败');
                        // }
                        $courseget = DB::update('update s_album_course set buysum = (buysum + ' .$shouru. ') where id= ? ', [$course->id]);
                    }
                
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->response($e->getMessage(),0);
            }

        return $this->response('true',1,$res,$request->apilog_id);
    }


    /**
     * 解锁单一课程详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UnlockCourseDetail(Request $request){
        // 判断courseid是否存在
        $courseid = $this->FormCheck->isEmpty($request->courseid, '课程ID');
        if (!$courseid->code) {
            return result($courseid->msg);
        }

        // 判断uid是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }

        $res = DB::table('s_album_course')
            ->select('id','coursename','wisdombean','albumid')->where('id', $request->courseid)->first();

        if (!empty($res)) {
            $album = DB::table('s_album')
            ->select('*')->where('id', $res->albumid)->first();

            $user = DB::table('s_users')
            ->select('*')->where('id', $request->uid)->first();

            $data['albumid']=$album->id;
            $data['albumname']=$album->albumname;
            $data['totalwisdombean']=$res->wisdombean;
            $data['courselist']=$res;
            $data['userwisdombean']=$user->wisdombean;
            

            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }

    }

    /**
     * 解锁所有课程详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UnlockAllCourseDetail(Request $request){
        // 判断albumid是否存在
        $albumid = $this->FormCheck->isEmpty($request->albumid, '专辑id');
        if (!$albumid->code) {
            return result($albumid->msg);
        }

        // 判断uid是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }

        $res = DB::table('s_album')
            ->select('*')->where('id', $request->albumid)->first();

        if (!empty($res)) {
            $course = DB::table('s_album_course')->select('id','coursename','wisdombean','albumid')->whereNotExists(function ($query) use ($request) {
                                    $query->select(DB::raw(1))
                                          ->from('s_album_course_unlock')
                                          ->whereRaw('s_album_course_unlock.courseid = s_album_course.id')->whereRaw('s_album_course_unlock.uid = '.$request->uid);
                                })->where('albumid',$request->albumid)
                                ->where('isdelete',0)
                                ->get();
            $totalwisdombean=DB::table('s_album_course')->whereNotExists(function ($query) use ($request) {
                                    $query->select(DB::raw(1))
                                          ->from('s_album_course_unlock')
                                          ->whereRaw('s_album_course_unlock.courseid = s_album_course.id')->whereRaw('s_album_course_unlock.uid = '.$request->uid);
                                })->where('albumid',$request->albumid)
                                ->where('isdelete',0)
                                ->sum('wisdombean');

            $user = DB::table('s_users')
            ->select('*')->where('id', $request->uid)->first();

            $data['albumid']=$res->id;
            $data['albumname']=$res->albumname;
            $data['totalwisdombean']=$totalwisdombean;
            $data['courselist']=$course;
            $data['userwisdombean']=$user->wisdombean;
            

            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }

    }

    /**
     * 智慧塔
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function WisdomIndex(Request $request){
        // 判断uid是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }


        // 判断iscurrent是否存在
        $iscurrent = $this->FormCheck->isEmpty($request->iscurrent, '是否当前关卡');
        if (!$iscurrent->code) {
            return result($iscurrent->msg);
        }

        // // 判断pagesize是否存在
        // $pagesize = $this->FormCheck->isEmpty($request->pagesize, '每页条数');
        // if (!$pagesize->code) {
        //     return result($pagesize->msg);
        // }

        $pagesize = 6;
        $pageindex=1;

        //关卡
        if($request->iscurrent==1){
            $currentgate = DB::table('s_gate_record')
                            ->where('uid',$request->uid)->max('gateid');
            if($currentgate){
                $pageindex =floor($currentgate/6)+1;
                $pagesize =$pageindex*6;
            }
        }
        if($request->iscurrent==2){
            $pageindex=$request->pageindex;
            $pagesize = 6*$pageindex;
        }
        
        
        $res = DB::table('s_gate')
                ->select('*')
                        // ->skip($request->pagesize*($request->pageindex-1))
                        ->take($pagesize)->get();
                        

               
        
        if (!empty($res)) {

            $rescount =  DB::table('s_gate')->select('*')->count();
            if($pagesize<$rescount){
                $rescount=$pagesize;
            }
            $pushcount =  $rescount%6;


            if($pushcount!=0){
                $pushcount=6-$pushcount;
                $arr = json_decode($res,true);
                for ($i=0; $i < $pushcount; $i++) { 
                   array_push($arr,'');  
                   $arr=  $arr;
                }
                $res=$arr;
               
            }else{
               $res=json_decode($res,true);
            }
            
            //闯关情况
            foreach($res as $key=>$v){
                
                if($res[$key]!=''){
                    $isrecord = DB::table('s_gate_record')
                                ->where('uid',$request->uid)->where('gateid',$res[$key]['id'])->count();

                    $res[$key]['islock']=0;  
                    $res[$key]['time']='';
                    $res[$key]['rewordbeans']='';
                    $res[$key]['results']='';

                    if($isrecord>0){
                        $record = DB::table('s_gate_record')->select('*')
                                ->where('uid',$request->uid)->where('gateid',$res[$key]['id'])->first();
                        $res[$key]['islock']=1;
                        $res[$key]['time']=$record->time;
                        $res[$key]['rewordbeans']=$record->rewordbeans;
                        $res[$key]['results']=$record->results;
                    }else{
                        $lastid=$res[$key]['id']-1;
                        $newrecord = DB::table('s_gate_record')
                                ->where('uid',$request->uid)->where('gateid',$lastid)->where('results',2)->count();
                        if($newrecord>0){
                           $res[$key]['islock']=1; 
                       }else{
                           $res[$key]['islock']=0;  
                       }
                    }
                    if($res[$key]['id']==1){
                        $res[$key]['islock']=1; 
                    }
                    $nextgateid = $res[$key]['id']+1;
                    $nextgate = DB::table('s_gate')
                                ->where('id',$nextgateid)->count();
                    if($nextgate>0){
                        $res[$key]['nextgateid']=$nextgateid;
                    }else{
                        $res[$key]['nextgateid']='';
                    }
                }

            }
            
            $data['gatelist']=$res;

            //用户相关信息
            $user =  DB::table('s_users')->select('*')
                            ->where('id',$request->uid)->first();
            // if($user->firstlogin==1){
            //     $data['firstlogin'] =1;
            //     $userfirstlogin =  DB::table('s_users')
            //         ->where('id', $request->uid)
            //         ->update(['firstlogin' =>2]);

            // }else{
            //     $data['firstlogin'] =2;
            // }
            //是否闯过第一关
            $gaterecord =  DB::table('s_gate_record')->select('*')
                             ->where('gateid',1)->where('uid',$request->uid)->where('results',2)->count();
            if($gaterecord>0){
                $data['firstlogin'] =2;
            }else{
                $data['firstlogin'] =1;
            }

            $data['manvalue'] = $user->manvalue;
            $data['wisdombean'] = $user->wisdombean;
            $data['pk'] = $user->pk;

            //未读站内信
            $msgcount =  DB::table('s_users_msg')
                            ->where('uid',$request->uid)->where('is_read',1)->count();

            $data['msgcount'] = $msgcount;

            //特殊关卡通关数量
            $spgatecount = DB::table('s_baobox as a')->join('s_gate_reword as b','a.gaterewordid','=','b.id')
                            ->where('a.uid',$request->uid)->count();
            //推荐课程
            $coursecount = DB::table('s_gate_answer as a')->join('s_album_course as b','a.courseid','=','b.id')
                            ->where('a.uid',$request->uid)->where('b.isdelete',0)->count();
            //百宝箱
            $data['spgatecount'] = $spgatecount+$coursecount;

            //用户数量
            $usercount =DB::table('s_users')->count();
            $data['usercount'] = $usercount;
            $data['pageindex'] = $pageindex;

            //是否已签到
            $date = date("Y-m-d");
            

            $signcount= DB::table('s_signin')
                ->where('uid','=',$request->uid)
                ->where('signdate','=',$date)
                ->count();

            if($signcount>0){
                $data['issign'] = 1;
            }else{
                $data['issign'] = 0;
            }

            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }

    }

    /**
     * 查看答案
     * @author weishuo
     * @param Request $request
     * @return json
     */
/*    public function GateAnswer(Request $request){
        //判断ID是否存在
        $gateid = $this->FormCheck->isEmpty($request->gateid,'关卡');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        //判断ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
       


        $data = DB::table('s_gate as a')->leftJoin('s_album_course as b','a.courserid','=','b.id')
                            ->select('a.id','a.answer','a.courserid','a.answerwisdombeanuse','b.id as bid','b.coursename','b.wisdombean','b.courseimg','b.coursescore')
                            ->where('a.id', $request->gateid)->first();
        //判断豆子是否足够
        $userwisdombean =  DB::table('s_users')->where('id', $request->uid)->first();
        if($data->answerwisdombeanuse>$userwisdombean->wisdombean){
           return $this->response('智慧豆不足',0); 
        }

        //扣除豆子
        $data->answer=explode(",", $data->answer);

        $wisdombeanusedata = [
            'uid' => $request->uid,
            'type' => 7,
            'wisdombean' => $data->answerwisdombeanuse,
            'rewardplatform' => 0
        ];
        //插入订单信息
        $wisdombeanuse = DB::table('s_users_wisdombeanuse')->insertGetId($wisdombeanusedata);
        $userdata =  DB::table('s_users')->select('*')
                    ->where('id', $request->uid)->first();
        $user =  DB::table('s_users')
                    ->where('id', $request->uid)
                    ->update(['wisdombean' => ($userdata->wisdombean-$data->answerwisdombeanuse)]);
        

        //是否查看过答案
        $isanswer = DB::table('s_gate_answer')
                            ->where('gateid', $request->gateid)->where('uid', $request->uid)
                            ->count();

        if($isanswer==0 and $data->courserid!=''){

            

            //插入已查看记录

            $insertData = array(
                "uid"=>$request->uid,
                "gateid" =>$request->gateid,
                "courseid" =>$data->courserid,
                "create_time" =>date("Y-m-d H:i:s"),
            );
            DB::table('s_gate_answer')->insertGetId($insertData); 

        }

        //是否解锁
        $iscourse = DB::table('s_album_course_unlock')
                            ->where('uid', $request->uid)->where('courseid',$data->courserid)
                            ->count();
        if($iscourse==0 and $data->courserid!=''){
            //解锁课程
            $insertunlock = array(
                "uid"=>$request->uid,
                
                "courseid" =>$data->courserid,
                "create_time" =>date("Y-m-d H:i:s"),
            );
            DB::table('s_album_course_unlock')->insertGetId($insertunlock); 
        }
            
        return $this->response('true',1,$data,$request->apilog_id);

    }*/


    public function GateAnswer(Request $request){
        //判断ID是否存在
        $gateid = $this->FormCheck->isEmpty($request->gateid,'关卡');
        if (!$gateid->code) {
            return result($gateid->msg);
        }
        //判断ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }



        $data = DB::table('s_gate as a')->leftJoin('s_album_course as b','a.courserid','=','b.id')
            ->select('a.id','a.answer','a.courserid','a.answerwisdombeanuse','b.id as bid','b.coursename','b.wisdombean','b.courseimg','b.coursescore')
            ->where('a.id', $request->gateid)->first();
        $subject = DB::table('s_gate_subject as s')->select('s.answer')->where('s.id', $request->subject_id)->first();
        $data->answer = $subject->answer;
        //判断豆子是否足够
        $userwisdombean =  DB::table('s_users')->where('id', $request->uid)->first();
        if($data->answerwisdombeanuse>$userwisdombean->wisdombean){
            return $this->response('智慧豆不足',0);
        }

        //扣除豆子
        $data->answer=explode(",", $data->answer);

        $wisdombeanusedata = [
            'uid' => $request->uid,
            'type' => 7,
            'wisdombean' => $data->answerwisdombeanuse,
            'rewardplatform' => 0
        ];
        //插入订单信息
        $wisdombeanuse = DB::table('s_users_wisdombeanuse')->insertGetId($wisdombeanusedata);
        $userdata =  DB::table('s_users')->select('*')
            ->where('id', $request->uid)->first();
        $user =  DB::table('s_users')
            ->where('id', $request->uid)
            ->update(['wisdombean' => ($userdata->wisdombean-$data->answerwisdombeanuse)]);


        //是否查看过答案
        $isanswer = DB::table('s_gate_answer')
            ->where('gateid', $request->gateid)->where('uid', $request->uid)->where('subject_id', $request->subject_id)
            ->count();

        if($isanswer==0 and $data->courserid!=''){



            //插入已查看记录

            $insertData = array(
                "uid"=>$request->uid,
                "gateid" =>$request->gateid,
                "courseid" =>$data->courserid,
                "create_time" =>date("Y-m-d H:i:s"),
                "subject_id" =>$request->subject_id,
            );
            DB::table('s_gate_answer')->insertGetId($insertData);

        }

        //是否解锁
        $iscourse = DB::table('s_album_course_unlock')
            ->where('uid', $request->uid)->where('courseid',$data->courserid)
            ->count();
        if($iscourse==0 and $data->courserid!=''){
            //解锁课程
            $insertunlock = array(
                "uid"=>$request->uid,

                "courseid" =>$data->courserid,
                "create_time" =>date("Y-m-d H:i:s"),
            );
            DB::table('s_album_course_unlock')->insertGetId($insertunlock);
        }

        return $this->response('true',1,$data,$request->apilog_id);

    }


    /**
     * 打赏比例
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function PlatreWardbeans(Request $request){
        $data=DB::table('s_regular_score')->where('id',2)->first();
        return $this->response('true', 1, $data);
    }

    /**
     * 播放统计
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function CourseStudy(Request $request){
        //判断ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        //判断ID是否存在
        $courseid = $this->FormCheck->isEmpty($request->courseid,'课程');
        if (!$courseid->code) {
            return result($courseid->msg);
        }

        $insertstudy = array(
            "uid"=>$request->uid,
            
            "courseid" =>$request->courseid,
            "create_time" =>date("Y-m-d H:i:s"),
        );
        $data=DB::table('s_album_course_study')->insertGetId($insertstudy); 
        
        $course = DB::update('update s_album_course set studysum = (studysum + 1) where id= ? ', [$request->courseid]);
        return $this->response('true', 1, $data);
    }


    /**
     * 分享智慧社详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function WisdomDetailShare(Request $request){
        
        $courseid = $this->FormCheck->isEmpty($request->courseid,'课程');
        if (!$courseid->code) {
            return result($courseid->msg);
        }

       
        $data =  DB::table('s_album '.' as a')
                ->join('s_users'.' as b','b.id','=','a.uid')
                ->join('s_album_course'.' as c','a.id','=','c.albumid')
                ->select('b.id','b.headimg','b.identity','b.nickname','c.albumid','c.coursename','c.coursecontent','c.coursetxt','c.commentsum','c.coursevoice','c.coursescore','c.coursetime','c.wisdombean','c.free','c.courseimg','c.id as courseid','c.sharesum')
                ->where('c.id','=',$request->courseid)
                ->first();
        // $resdata = DB::update('update s_album_course set sharesum = (sharesum+1) where id= ? ', [$request->courseid]);        
        if(!empty($data)){
            return $this->response('true',1,$data,$request->apilog_id);
        }else{
            return $this->response('false',0);
        }
        

    }

    public function WisdomShareAdd(Request $request){
        
        $courseid = $this->FormCheck->isEmpty($request->courseid,'课程');
        if (!$courseid->code) {
            return result($courseid->msg);
        }

        $data = DB::update('update s_album_course set sharesum = (sharesum+1) where id= ? ', [$request->courseid]);        
        if(!empty($data)){
            return $this->response('true',1,$data,$request->apilog_id);
        }else{
            return $this->response('false',0);
        }
        

    }


}
