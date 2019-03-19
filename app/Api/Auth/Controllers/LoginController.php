<?php
/*
 * @name 系统日志
 * @auth tzchao
 * @time 2017-10-24
 */
namespace App\Api\Auth\Controllers;

use App\Classlib\FormCheck;
use App\Api\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(){
        $this->formCheck = new FormCheck();
        //$this->apilog = new ApiLog();
    }

    public function index(Request $request){
        $data = array();
        return $this->response(lang('Auth','request_success','Api'),1,$data);
    }
}