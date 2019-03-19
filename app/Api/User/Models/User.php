<?php
namespace App\Api\User\Models;

use App\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class User extends Model
{

    public $timestamps   = false;
    protected $user = "s_users";
    protected $subject = "s_subject";
    protected $UsersSubject = "s_users_subject";
    protected $UsersDefinesubject = "s_users_definesubject";
    protected $AppBannerBegin = 's_banner_begin';
    protected $MineAccountDetails = 's_mine_account_details';
    protected $TouristUsers = 's_tourist_users';
    protected $RegularBuymember = 's_regular_buymember';

    protected $prefix = "s_"; // 表前缀
    /**
     * [__get 自动获取表名]
     * @param  [type] $Attributename [description]
     * @return [type]                [description]
     */
    public function __get($Attributename) {
        return $this->prefix.strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $Attributename));
    }

    /**
     * 游客登录 TouristUsersInfo
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return array
     */
    public function TouristUsersInfo($request){
        // 通过游客设备判断用户是否存在
        $request->expire_time = date('Y-m-d H:i:s',strtotime("+7 days"));
        $getUserInfo= objectToArray(DB::table($this->TouristUsers)->where('id', '=', $request->EquipmentId)->first());
        if (empty($getUserInfo)) {
            $time = date('Y-m-d H:i:s',time());
            $data = [
                'id' => $request->EquipmentId,
                'username' => $request->EquipmentId,
                'remember_token' => $request->token,
                'expire_time' => $request->expire_time,
                'create_time' => $time
            ];
            $res = DB::table($this->TouristUsers)->insert($data);
            if ($res) {
                return [
                    'UserId' => $request->EquipmentId,
                    'remember_token' => $request->token
                ];
            }else{
                return false;
            }
        }else{
            $res = DB::table($this->TouristUsers)->where('id', $getUserInfo['id'])->update(['remember_token' => $request->token,'expire_time' => $request->expire_time]);
            if ($res) {
                return [
                    'UserId' => $request->EquipmentId,
                    'remember_token' => $request->token
                ];
            }else{
                return false;
            }
        }
    }
    /**
     * [GetUserInfo 获取用户信息]
     * @param [$request http参数]
     * @return [array]
     * @author jzz
     */
    public function GetUserInfo($request)
    {
        $pwd = !empty($request->password) ? $request->password : '';
        $getUserInfo= DB::table($this->user)
            ->select('*')
            ->where('phone','=',$request->phone)
            ->first();
        if (empty($getUserInfo)) {
            return arrayToObject(['code' => 2]);
        }
        if (!empty($getUserInfo) && Hash::check($pwd, $getUserInfo->password)) {
            unset($getUserInfo->password);
            $getUserInfo->code = '';
            return $getUserInfo;
        }else{
            return arrayToObject(['code' => 1]);
        }
    }

    /**
     * 修改用户密码 UpdataUserPasswdData
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return array
     */
    public function UpdataUserPasswdData($request){
        if (!empty($request->password) ) {
            $Passwd = bcrypt(trim($request->password));
            $updateData = [
                'password' => $Passwd
            ];
            return DB::table($this->user)->where("phone",$request->phone)->update($updateData);
        }
        return false;
    }

    /**
     * [GetUserInfo 检验手机号码是否已注册]
     * @param [$request http参数]
     * @return [array]
     * @author jzz
     */
    public function GetUserInfoPhone($request)
    {
        $getUserInfo= DB::table($this->user)
            ->select('id','phone')
            ->where('phone','=',$request->phone)
            ->first();
        if (!empty($getUserInfo)) {
            return arrayToObject(['code' => 1]);
        } else{
            return arrayToObject(['code' => 2]);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function UpdateTokenInfo($request){
        if (empty($request->UserId)) {
           return false;
        }
        if (empty($request->token)) {
           return false;
        }
        if (empty($request->expire_time)) {
           return false;
        }
        return DB::table('s_users')->where('id', $request->UserId)->update(['remember_token' => $request->token,'expire_time' => $request->expire_time, 'last_time' => time()]);
    }

    /**
     * 用户注册 UserRegisterInfo
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return json
     */
    public function UserRegisterInfo($request){

        $pwd = !empty($request->password) ? bcrypt(trim($request->password)) : '';
        $data = [
            'phone' => $request->phone,
            'password' => $pwd,
            'nickname' => $request->nickname,
            'sex' => $request->sex,
            'birthday' => $request->birthday,
            'city' => $request->city,
            'identity' => $request->identity,
            'headimg' => $request->headimg,
            'constellation' => $request->constellation,
            'firstlogin' => 1,
            'remember_token' => $request->token,
            'last_time' => time()
        ];

        if (!empty($data)) {
            //推荐人收入
            if(!empty($request->recommenduid)){
                $platrewardbeans = DB::table('s_regular_score')->select('*')->where('id','1')->first();
                $wisdombeanusedata = [
                    'uid' => $request->recommenduid,
                    'type' => 6,
                    'wisdombean' => $platrewardbeans->sharerewardbeans,

                ];
                //插入订单信息
                $wisdombeanuse = DB::table('s_users_wisdombeanuse')->insertGetId($wisdombeanusedata);
                $recommenduser = DB::update('update s_users set wisdombean = (wisdombean + ' .$platrewardbeans->sharerewardbeans. ') where id= ? ', [$request->recommenduid]);
            }

            return  DB::table($this->user)->insertGetId($data);
        }else{
            return false;
        }
    }

    /**
     * 获取用户数据 GetUserData
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return array
     */
    public function GetUserData($request){
        if (empty($request->UserId)) {
            return [];
        }
        $UserInfo = objectToArray(DB::table($this->user)
            ->select('id','nickname','score','degree','vip','validity','moneybag')
            ->whereIn('id', $request->UserId)
            ->first());
        return $UserInfo;
    }

    /**
     * 通过用户手机号获取用户数据 GetPhoneUserData
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return array
     */
    public function GetPhoneUserData($request){
        if (empty($request->phone)) {
            return [];
        }
        $UserInfo = objectToArray(DB::table($this->user)
            ->select('*')
            ->where('phone', '=', $request->phone)
            ->first());
        return $UserInfo;
    }

    /**
     * 获取用户调研列表数据 GetUserSurveyData
     * @author jiazhizhong <jiazhizhong@163.com>
     * @param [type] $request
     * @return void
     */
    public function GetUserSurveyData($request){
        $pid = !empty($request->pid) ? $request->pid : [0];
        $getSurveyData = DB::table($this->subject)
            ->whereIn('pid',$pid)
            ->get()
            ->toArray();
        return $getSurveyData;
    }

    /**
     * 添加用户专题数据 AddSurveyData
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $data
     * @return void
     */
    public function AddSurveyData($data){
        if (!empty($data)) {
            return  DB::table($this->UsersSubject)->insert($data);
        }else{
            return false;
        }
    }

    /**
     * 通过用户id获取用户专题数据 GetSurveyData
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return array
     */
    public function GetSurveyData($request){
        if (empty($request->UserId)) {
            return [];
        }
        $UserSurveyData = objectToArray(DB::table($this->UsersSubject)
            ->where('uid', '=', $request->UserId)
            ->first());
        return $UserSurveyData;
    }

    /**
     * 添加用户自定义专题数据 AddCustomSurveyData
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $data
     * @return void
     */
    public function AddCustomSurveyData($data){
        if (!empty($data)) {
            return  DB::table($this->UsersDefinesubject)->insert($data);
        }else{
            return false;
        }
    }

    /**
     * 获取启动页banner图片  GetStartUpImg
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @return array
     */
    public function GetStartUpImg(){
        $GetStartUpImgInfo = DB::table($this->AppBannerBegin)
            ->where('state', '=', 1)
            ->orderby('top','DESC')
            ->orderby('sort','ASC')
            ->get()
            ->toArray();
        return $GetStartUpImgInfo;
    }

    /**
     * 修改用户密码 ForgetPasswdHandle
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return void
     */
    public function ForgetPasswdHandle($request){
        $password = bcrypt(trim($request->password));
        return DB::table($this->user)->where('phone', $request->phone)->update(['password' => $password]);
    }



    /**
     * 更新用户vip状态 UpdateUserVip
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return array
     */
    public function UpdateUserVip($request, $RegularBuyData){
        if (empty($request->UserId)) {
            return false;
        }
        if (empty($request->Month)) {
            return false;
        }
        // 事务开始
        DB::beginTransaction();
        try{
            $UserInfo = objectToArray(DB::table($this->user)
                ->where('id', '=', $request->UserId)
                ->first());
            if (!empty($UserInfo)) {
                // 更新用户vip状态信息
                $time = time();
                $timeint = strtotime($UserInfo['validity']);
                if ($timeint < $time) {
                    $UserInfo['validity'] = date('Y-m-d',time());
                }
                $validity = date('Y-m-d H:i:s',strtotime("{$UserInfo['validity']} +{$request->Month} month"));
                DB::table($this->user)->where('id', $request->UserId)->lockForUpdate()->update(['vip' => 2, 'validity' => $validity]);
                // 添加消费记录
                $DateTime = date('Y-m-d H:i:s',time());
                $price = $RegularBuyData['price'];
                $data = [
                    'uid' => $request->UserId,
                    'origin' => 3,
                    'detail' => $RegularBuyData['heading'],
                    'money' => $price,
                    'order_no' => $request->order_no,
                    'statue' => 2,
                    'create_time' => $DateTime,
                ];
                DB::table($this->MineAccountDetails)->insert($data);
                // 更新用户账户余额数据
                $MineAccountDetails = objectToArray(DB::table($this->MineAccountDetails)
                    ->select('money')
                    ->where('order_no', '=', $request->out_trade_no)
                    ->first());
                $AccountBalanceInfo = objectToArray(DB::table($this->user)
                    ->select('moneybag')
                    ->where('id', '=', $request->UserId)
                    ->first());
                if ($AccountBalanceInfo['moneybag'] < $price) {
                    DB::rollback();//事务回滚
                    return ['msg' => '余额不足','code' => false];
                }

                $moneybag = !empty($AccountBalanceInfo['moneybag']) ? $AccountBalanceInfo['moneybag'] : 0;
                $moneybag = bcsub($moneybag,$price,2);
                // 修改消费记录数据状态
                DB::table($this->user)->where('id', $request->UserId)->update(['moneybag' => $moneybag]);
                // 修改消费记录数据状态
                DB::table($this->MineAccountDetails)->where('order_no', $request->out_trade_no)->lockForUpdate()->update(['trade_no' => $request->trade_no, 'statue' => 2]);
            }
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();//事务回滚
            // 修改消费记录数据状态
            DB::table($this->MineAccountDetails)->where('order_no', $request->out_trade_no)->lockForUpdate()->update(['trade_no' => $request->trade_no, 'statue' => 3]);
            $time_s = date('Y-m-d H::i:s',time());
            $logData = [
                'typename' => 'api/storeInfo/ConfirmOrder_php_log',
                'ip' => '127.0.0.2',
                'api_url' => 'api/storeInfo/ConfirmOrder_php_log',
                'content' => $e->getMessage(),
                'response' => json_encode($request->all()),
                'created_at' => $time_s,
                'response_datetime' => $time_s
            ];
            $LogId = DB::table('log_api_requests')->insertGetId($logData);
            return false;
        }

    }

    /**
     * [AddMineMessage description]
     * @param [type] $request [description]
     */
    public function AddMineMessage($request){
        $timeStr = date('Y-m-d H:i:s',time());
        $Data = [
            'uid' => $request->UserId,
            'type' => 4,
            'content' => $request->MsgContent,
            'data' => '[]',
            'create_time' => $timeStr
        ];
        return  DB::table($this->MineMessage)->insert($Data);
    }

    /**
     * [GetRegularBuymember 查询会员购买规则]
     * @author jiazhizhong
     * @param [type] $request [description]
     */
    public function GetRegularBuymember($request){
        if (empty($request->VipRegId)) {
            return [];
        }
        $RegularBuymemberData = objectToArray(DB::table($this->RegularBuymember)
            ->where('id', '=', $request->VipRegId)
            ->first());
        return $RegularBuymemberData;
    }

    /**
     * 更新用户设备id HandleEquipmentId
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return void
     */
    public function HandleEquipmentId($request){
        return DB::table($this->user)->where('id', $request->UserId)->update(['devicenumber' => $request->EquipmentId]);
    }

}
