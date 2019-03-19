<?php

namespace App\Api;

use App\Api\Log\Models\ApiRequestLog;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /*
     * api response data
     * @param $msg 响应结果
     * @param $code 响应代码
     * @param $data 响应数据
     * @param $apilogId 请求日志ID  0 1 1002
     */
    public function response($msg, $code = 0, $data = "")
    {
        $res = array();
        $res['msg'] = $msg;
        $res['code'] = $code;
        $res['data'] = $data;
        $apiRequestData = Session::get('apiRequestData');
        $res['time_used'] = round((microtime(true) - $apiRequestData->request_start_time) * 1000,4);//;
        $apilog = new ApiRequestLog();
        $apilog->updateLogApiRequest($res);
        return json_encode($res);
    }
    /*
     * api response data
     * @param $msg 响应结果
     * @param $code 响应代码
     * @param $data 响应数据
     * @param $apilogId 请求日志ID  0 1 1002
     */
    public function Resources($msg, $code = 0, $data = "")
    {
        $res = array();
        $res['msg'] = $msg;
        $res['code'] = $code;
        $res['data'] = $data;
        $apiRequestData = Session::get('apiRequestData');
        $res['time_used'] = round((microtime(true) - $apiRequestData->request_start_time) * 1000,4);//;
        $apilog = new ApiRequestLog();
        $apilog->updateLogApiRequest($res);
        return json_encode($res);
    }
}
