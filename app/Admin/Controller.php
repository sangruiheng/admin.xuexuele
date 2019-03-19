<?php

namespace App\Admin;

use App\Admin\Manage\Models\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function actionLog($message,$ip = ''){
        $log = new Log();
        $log->writeLog($message,$ip);
    }

    //获取视图路径
    public function viewPath($tpl = ''){
        $action = Route::current()->getActionName();
        $appDir = config('view.app.admin');
        list($routePath,$method) = explode('@',$action);
        list($class, $app , $module) = explode('\\', $routePath);
        $tpl = $tpl ? $tpl : $method;
        $viewPath = $appDir.'.'.$module.'.Views.'.$tpl;
        //p($viewPath);die;
        return $viewPath;
    }
}
