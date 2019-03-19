<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Api\Log\Models\ApiRequestLog;
use App\Api\User\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $logData['request_start_time'] = microtime(true);
        //写入接口请求日志
        $apilog = new ApiRequestLog();
        $logData['apilog_id'] = $apilog->logApiRequest($request);
        Session::put('apiRequestData',arrayToObject($logData)); //记录执行开始时间
        //api授权区域
        if(!empty($request->uid)){
            if(!empty($request->token)){

                $user = DB::table('s_users')
                ->select('*')
                ->where('remember_token','=',$request->token)
                ->where('id',$request->uid)
                ->where('expire_time','>',Carbon::now())
                ->count();
                if($user<=0){
                    return response()->json([
                        'code' => 10000,
                        'msg' => 'token无效，请重新登录！',
                        'data' => ""
                    ], 200);
                }
            }else{
                return response()->json([
                    'code' => 10000,
                    'msg' => 'token无效，请重新登录！',
                    'data' => ""
                ], 200);
            }
            
        }

        return $next($request);
    }

    
}
