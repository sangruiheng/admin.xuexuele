<?php

namespace App\Admin\Manage\Controllers;

use App\Admin\Manage\Models\Admin;
use App\Classlib\FormCheck;
use App\Admin\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function login(Request $request){
        $username = $request->username;
        $password = $request->password;
        $formCheck = new FormCheck();
        $checkUserName = $formCheck->isEmpty($username,"登录账号");
        if(!$checkUserName->code){
            return result($checkUserName->msg);
        }

        $checkPassword = $formCheck->isEmpty($password,"登录密码");
        if(!$checkPassword->code){
            return result($checkPassword->msg);
        }
        //执行后台登录操作
        $admin = new Admin();
        $loginRes = $admin->checkPassword($username,$password);
        if(empty($loginRes->code)){
            return result($loginRes->msg);
        }else{
            $this->actionLog("登录后台");
            return result("登录成功！",1);
        }
    }

    public function logout(){
        $this->actionLog("退出后台");
        Session::flush();
        Cookie::queue('adminInfo', null , -1);
        return result("退出成功",1);
    }
}
