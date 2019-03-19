<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tymon\JWTAuth\Middleware;

use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class GetUserFromToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        if (! $token = $this->auth->setRequest($request)->getToken()) {
            $res['msg'] = "未登录";
            $res['code'] = "1002";
            return Response::make($res, 200);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            $res['msg'] = "未登录token_expired";
            $res['code'] = "1002";
            $res['status_code'] = $e->getStatusCode();
            return Response::make($res, 200);
        } catch (JWTException $e) {
            $res['msg'] = "未登录";
            $res['code'] = "1002";
            $res['status_code'] = $e->getStatusCode();
            return Response::make($res, 200);
        }

        if (!$user) {
            $res['msg'] = "用户不存在";
            $res['code'] = "0";
            return Response::make($res, 200);
        }
        $this->events->fire('tymon.jwt.valid', $user);
        return $next($request);

        /*
        try {
            //从数据库中获取是否真正退出
            $user = new User();
            $rememberToken = $user->getRememberToken();
            if($rememberToken && $rememberToken==$token){ //未真正退出，写入缓存
                Cache::put("remember_token",$token,7*24*60);
            }else{
                $res['msg'] = "未登录";
                $res['code'] = "1002";
                return Response::make($res, 200);
            }
            $serverToken = Cache::get("remember_token");
            if(!$serverToken){
                //从数据库中获取是否真正退出
                $user = new User();
                $rememberToken = $user->getRememberToken();
                if($rememberToken && $rememberToken==$token){ //未真正退出，写入缓存
                    Cache::put("remember_token",$token,7*24*60);
                }else{
                    $res['msg'] = "未登录";
                    $res['code'] = "1002";
                    return Response::make($res, 200);
                }
            }else{
                if($serverToken!=$token){
                    $res['msg'] = "未登录";
                    $res['code'] = "1002";
                    return Response::make($res, 200);
                }else{
                    $user = $this->auth->authenticate($token);
                }
            }
        } catch (TokenExpiredException $e) {
            $res['msg'] = "未登录";
            $res['code'] = "1002";
            return Response::make($res, 200);
            //return $this->respond('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            $res['msg'] = "未登录";
            $res['code'] = "1002";
            $res['status_code'] = $e->getStatusCode();
            return Response::make($res, 200);
            //return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }

        if (! $user) {
            $res['msg'] = "用户不存在";
            $res['code'] = "0";
            return Response::make($res, 200);
            //return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

        $this->events->fire('tymon.jwt.valid', $user);
        */
    }
}
