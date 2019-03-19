<?php

namespace App\Admin\Manage\Controllers;

use App\Admin\Controller;

class LoginViewController extends Controller
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


    public function login(){
        return view($this->viewPath());
    }

    public function unauthorized(){
        return view($this->viewPath())
            ->with("title","未授权")
            ->with("thisAction","/");
    }
}
