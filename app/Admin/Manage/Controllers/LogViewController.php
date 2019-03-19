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

class LogViewController extends Controller
{
    public function index(Request $request){
        $this->actionLog("查看日志",$request->ip());
        $menuInfo = getMenuFromPath($request->path());
        return view($this->viewPath('log'))
            ->with("thisAction",$menuInfo->url)
            ->with("title",$menuInfo->title)
            ->with("total",0);
    }

}