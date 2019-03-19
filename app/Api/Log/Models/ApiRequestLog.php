<?php

namespace App\Api\Log\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ApiRequestLog extends Model
{
    /**接口名称注册
     * @var array
     */
    protected $apiArr = array(
        "api/login"=>"用户登录",
        "api/register"=>"用户注册",
        "api/getsms"=>"获取验证码",
        "api/forget"=>"忘记密码",
    );

    protected $table_api_request = "log_api_requests";

    /**写入接口请求数据日志
     * @param $requestData
     * @return int
     */
    public function logApiRequest($requestData){
        $activePath = $requestData->path();
        $clientIp     = $requestData->ip();
        $data = $requestData->all();
        $requestContent = $data;
        $apiUrl = $requestData->url();
        if(isset($this->apiArr[$activePath])){
            $logData['typename'] = $this->apiArr[$activePath];
        }else{
            $logData['typename'] = $activePath;
        }
        $logData['ip'] = $clientIp;
        $logData['api_url'] = $apiUrl;
        $logData['content'] = json_encode($requestContent);
        $logData['create_date'] = date("Y-m-d H:i:s");
        $logData['time_used'] = 0;
        return DB::table($this->table_api_request)->insertGetId($logData);
    }

    //更新日志请求
    public function updateLogApiRequest($responseData=""){
        $apiRequestData = Session::get('apiRequestData');
        DB::table($this->table_api_request)
            ->where("id",$apiRequestData->apilog_id)
            ->update(["response"=>json_encode($responseData),"response_datetime"=>date("Y-m-d H:i:s"),"time_used"=>$responseData['time_used']]);
    }

    //定时清理api请求日志
    public function clear($time=1){
        $clearTime = time() - $time * 3600*24;
        DB::table($this->table_api_request)->where("created_at","<",date("Y-m-d H:i:s",$clearTime))->delete();
    }
}
