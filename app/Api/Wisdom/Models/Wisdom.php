<?php
namespace App\Api\Wisdom\Models;

use App\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Wisdom extends Model
{

    public $timestamps   = false;
    protected $user = "s_users";
    protected $subject = "s_subject";
    protected $WisdomsSubject = "s_users_subject";
    protected $WisdomsDefinesubject = "s_users_definesubject";
    protected $AppBannerBegin = 's_banner_begin';
    protected $MineAccountDetails = 's_mine_account_details';
    protected $TouristWisdoms = 's_tourist_users';
    protected $RegularBuymember = 's_regular_buymember';

    protected $prefix = "s_"; // 表前缀

    /**
     * 添加充值订单信息 AddOrderInfo
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return void
     */
    public function AddOrderInfo($request){

        $create_time = date('Y-m-d H:i:s',time());
        DB::beginTransaction();
        try{
            // 添加订单数据
            $data = [
                'uid' => $request->uid,
                'complaint' => $request->complaint,
                'order_no' => $request->order_no,
                // 'wx_transaction_id' => $request->wx_transaction_id,
                'wisdombean' => $request->order_money*100,
                'create_time' => $create_time
            ];
            $res = DB::table('s_order')->insertGetId($data);
            if ($res) {
                DB::commit();
                return $data;
            }
            DB::rollback();//事务回滚
            return false;
        }catch (\Exception $e){
            DB::rollback();//事务回滚
            return false;
        }
    }

    /**
     * [UpdateUserAccountInfo 修改账户余额信息]
     * @param [type] $request [description]
     */
    public function UpdateAccountInfo($request){
        
        if (empty($request->trade_no)) {
            
            return false;
        }
        if (empty($request->out_trade_no)) {
            return false;
        }
       
        $trade_no=$request->trade_no;
        
        
        DB::beginTransaction();
        try{
            // 修改充值记录数据
            $order =DB::table('s_order')->where('order_no', $request->out_trade_no)->first();
            if($order->state==2){
                DB::rollback();//事务回滚
                return false;
            }
            DB::table('s_order')->where('order_no', $request->out_trade_no)->update(['wx_transaction_id' => $trade_no]);

            // 更新用户账户余额数据
            $MineAccountDetails = DB::table('s_order')
                ->select('wisdombean','id')
                ->where('order_no', '=', $request->out_trade_no)
                ->first();
            $AccountBalanceInfo = DB::table('s_users')
                ->select('wisdombean')
                ->where('id', '=', $order->uid)
                ->first();
            // $moneybag = !empty($AccountBalanceInfo['moneybag']) ? $AccountBalanceInfo['moneybag'] : 0;
            // $money = !empty($MineAccountDetails['wisdombean']) ? $MineAccountDetails['wisdombean'] : 0;
            // $moneybag = bcadd($money,$moneybag,2);
            // 修改消费记录数据状态
            DB::table('s_users')->where('id', $order->uid)->update(['wisdombean' => $AccountBalanceInfo->wisdombean+$MineAccountDetails->wisdombean]);
            DB::table('s_order')->where('id', $MineAccountDetails->id)->update(['state' => 2]);
            DB::commit();
            return true;
        }catch (\Exception $e){
            echo $e->getMessage();
            DB::rollback();//事务回滚
            return false;
        }
    }


    public function UpdateAccountInfoWx($out_trade_no,$transaction_id){
        
        
        DB::beginTransaction();
        try{
            // 修改充值记录数据
            $order =DB::table('s_order')->where('order_no',$out_trade_no)->first();
            if($order->state==2){
                DB::rollback();//事务回滚
                return false;
            }
            DB::table('s_order')->where('order_no', $out_trade_no)->update(['wx_transaction_id' => $transaction_id]);

            // 更新用户账户余额数据
            $MineAccountDetails = DB::table('s_order')
                ->select('wisdombean','id')
                ->where('order_no', '=', $out_trade_no)
                ->first();
            $AccountBalanceInfo = DB::table('s_users')
                ->select('wisdombean')
                ->where('id', '=', $order->uid)
                ->first();
            // $moneybag = !empty($AccountBalanceInfo['moneybag']) ? $AccountBalanceInfo['moneybag'] : 0;
            // $money = !empty($MineAccountDetails['wisdombean']) ? $MineAccountDetails['wisdombean'] : 0;
            // $moneybag = bcadd($money,$moneybag,2);
            // 修改消费记录数据状态
            DB::table('s_users')->where('id', $order->uid)->update(['wisdombean' => $AccountBalanceInfo->wisdombean+$MineAccountDetails->wisdombean]);
            DB::table('s_order')->where('id', $MineAccountDetails->id)->update(['state' => 2]);
            DB::commit();
            return true;
        }catch (\Exception $e){
            echo $e->getMessage();
            DB::rollback();//事务回滚
            return false;
        }
    }

    /**
     * [GetAccountBalanceInfo 获取账户余额信息]
     * @param [type] $request [description]
     */
    public function GetAccountBalanceInfo($request){
        $AccountBalanceInfo = objectToArray(DB::table('s_users')
            ->select('*')
            ->where('id', '=', $request->uid)
            ->first());
        return $AccountBalanceInfo;
    }
















    /**
     * [__get 自动获取表名]
     * @param  [type] $Attributename [description]
     * @return [type]                [description]
     */
    public function __get($Attributename) {
        return $this->prefix.strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $Attributename));
    }





















    /**
     * 游客登录 TouristWisdomsInfo
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return array
     */
    public function TouristWisdomsInfo($request){
        // 通过游客设备判断用户是否存在
        $request->expire_time = date('Y-m-d H:i:s',strtotime("+7 days"));
        $getWisdomInfo= objectToArray(DB::table($this->TouristWisdoms)->where('id', '=', $request->EquipmentId)->first());
        if (empty($getWisdomInfo)) {
            $time = date('Y-m-d H:i:s',time());
            $data = [
                'id' => $request->EquipmentId,
                'username' => $request->EquipmentId,
                'remember_token' => $request->token,
                'expire_time' => $request->expire_time,
                'create_time' => $time
            ];
            $res = DB::table($this->TouristWisdoms)->insert($data);
            if ($res) {
                return [
                    'WisdomId' => $request->EquipmentId,
                    'remember_token' => $request->token
                ];
            }else{
                return false;
            }
        }else{
            $res = DB::table($this->TouristWisdoms)->where('id', $getWisdomInfo['id'])->update(['remember_token' => $request->token,'expire_time' => $request->expire_time]);
            if ($res) {
                return [
                    'WisdomId' => $request->EquipmentId,
                    'remember_token' => $request->token
                ];
            }else{
                return false;
            }
        }
    }
    /**
     * [GetWisdomInfo 获取用户信息]
     * @param [$request http参数]
     * @return [array]
     * @author jzz
     */
    public function GetWisdomInfo($request)
    {
        $pwd = !empty($request->password) ? $request->password : '';
        $getWisdomInfo= DB::table($this->user)
                ->select('*')
                ->where('phone','=',$request->phone)
                ->first();
        if (empty($getWisdomInfo)) {
            return arrayToObject(['code' => 2]);
        }
        if (!empty($getWisdomInfo) && Hash::check($pwd, $getWisdomInfo->password)) {
            unset($getWisdomInfo->password);
            $getWisdomInfo->code = '';
            return $getWisdomInfo;
        }else{
            return arrayToObject(['code' => 1]);
        }
    }

    /**
     * 修改用户密码 UpdataWisdomPasswdData
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return array
     */
    public function UpdataWisdomPasswdData($request){
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
     * [GetWisdomInfo 检验手机号码是否已注册]
     * @param [$request http参数]
     * @return [array]
     * @author jzz
     */
    public function GetWisdomInfoPhone($request)
    {
        $getWisdomInfo= DB::table($this->user)
            ->select('id','phone')
            ->where('phone','=',$request->phone)
            ->first();
        if (!empty($getWisdomInfo)) {
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
//    public function UpdateTokenInfo($request){
//        if (empty($request->WisdomId)) {
//            return false;
//        }
//        if (empty($request->token)) {
//            return false;
//        }
//        if (empty($request->expire_time)) {
//            return false;
//        }
//        return DB::table('s_users')->where('id', $request->WisdomId)->update(['remember_token' => $request->token,'expire_time' => $request->expire_time]);
//    }

    /**
     * 用户注册 WisdomRegisterInfo
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return json
     */
    public function WisdomRegisterInfo($request){

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
        ];
        if (!empty($data)) {
            return  DB::table($this->user)->insertGetId($data);
        }else{
            return false;
        }
    }

    /**
     * 获取用户数据 GetWisdomData
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return array
     */
    public function GetWisdomData($request){
        if (empty($request->WisdomId)) {
            return [];
        }
        $WisdomInfo = objectToArray(DB::table($this->user)
                        ->select('id','nickname','score','degree','vip','validity','moneybag')
                        ->whereIn('id', $request->WisdomId)
                        ->first());
        return $WisdomInfo;
    }

    /**
     * 通过用户手机号获取用户数据 GetPhoneWisdomData
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return array
     */
    public function GetPhoneWisdomData($request){
        if (empty($request->phone)) {
            return [];
        }
        $WisdomInfo = objectToArray(DB::table($this->user)
                        ->select('*')
                        ->where('phone', '=', $request->phone)
                        ->first());
        return $WisdomInfo;
    }

    /**
     * 获取用户调研列表数据 GetWisdomSurveyData
     * @author jiazhizhong <jiazhizhong@163.com>
     * @param [type] $request
     * @return void
     */
    public function GetWisdomSurveyData($request){
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
            return  DB::table($this->WisdomsSubject)->insert($data);
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
        if (empty($request->WisdomId)) {
            return [];
        }
        $WisdomSurveyData = objectToArray(DB::table($this->WisdomsSubject)
                        ->where('uid', '=', $request->WisdomId)
                        ->first());
        return $WisdomSurveyData;
    }

    /**
     * 添加用户自定义专题数据 AddCustomSurveyData
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $data
     * @return void
     */
    public function AddCustomSurveyData($data){
        if (!empty($data)) {
            return  DB::table($this->WisdomsDefinesubject)->insert($data);
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
     * 更新用户vip状态 UpdateWisdomVip
     * @author jiazhizhong <jiazhizhongphp@163.com>
     * @param [type] $request
     * @return array
     */
    public function UpdateWisdomVip($request, $RegularBuyData){
        if (empty($request->WisdomId)) {
            return false;
        }
        if (empty($request->Month)) {
            return false;
        }
        // 事务开始
        DB::beginTransaction();
        try{
            $WisdomInfo = objectToArray(DB::table($this->user)
                            ->where('id', '=', $request->WisdomId)
                            ->first());
            if (!empty($WisdomInfo)) {
                // 更新用户vip状态信息
                $time = time();
                $timeint = strtotime($WisdomInfo['validity']);
                if ($timeint < $time) {
                    $WisdomInfo['validity'] = date('Y-m-d',time());
                }
                $validity = date('Y-m-d H:i:s',strtotime("{$WisdomInfo['validity']} +{$request->Month} month"));
                DB::table($this->user)->where('id', $request->WisdomId)->lockForUpdate()->update(['vip' => 2, 'validity' => $validity]);
                // 添加消费记录
                $DateTime = date('Y-m-d H:i:s',time());
                $price = $RegularBuyData['price'];
                $data = [
                    'uid' => $request->WisdomId,
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
                            ->where('id', '=', $request->WisdomId)
                            ->first());
                if ($AccountBalanceInfo['moneybag'] < $price) {
                    DB::rollback();//事务回滚
                    return ['msg' => '余额不足','code' => false];
                }

                $moneybag = !empty($AccountBalanceInfo['moneybag']) ? $AccountBalanceInfo['moneybag'] : 0;
                $moneybag = bcsub($moneybag,$price,2);
                // 修改消费记录数据状态
                DB::table($this->user)->where('id', $request->WisdomId)->update(['moneybag' => $moneybag]);
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
            'uid' => $request->WisdomId,
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
        return DB::table($this->user)->where('id', $request->WisdomId)->update(['devicenumber' => $request->EquipmentId]);
    }

}
