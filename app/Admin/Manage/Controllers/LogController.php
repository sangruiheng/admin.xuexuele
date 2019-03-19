<?php
/*
 * @name 系统日志
 * @auth tzchao
 * @time 2017-10-24
 */
namespace App\Admin\Manage\Controllers;

use App\Admin\Manage\Models\Log;
use App\Classlib\FormCheck;
use App\Admin\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct(){
        $this->formCheck = new FormCheck();
        $this->log = new Log();
    }

    //数据获取列表
    public function lists(Request $request){
        $logData = $this->log->getLogLists($request,"lists");
        return result("请求成功",1,$logData);
    }

}