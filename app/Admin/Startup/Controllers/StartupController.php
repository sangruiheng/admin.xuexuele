<?php

namespace App\Admin\Startup\Controllers;

use App\Admin\Advert\Models\Advert;
use App\Admin\Gate\Models\Gate;
use App\Admin\Startup\Models\Startup;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class StartupController extends Controller
{
    public function __construct()
    {
        $this->gate = new Gate();
        $this->formCheck = new FormCheck();
    }

    /***
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
            $startupModel = new Startup();
            $result = $startupModel->getstartupDetail();
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Startup.Views.editstartup")
                ->with("result", $result)
                ->with("thisAction", "/startup")
                ->with("title", $menuInfo->title);
    }


    public function updateStartup(Request $request)
    {
        $formCheck = new FormCheck();

        $Image = $this->formCheck->isEmpty($request->img, "封面图片");
        if (!$Image->code) {
            return result($Image->msg);
        }
        $startupModel = new Startup();
        $result = $startupModel->updateStartup($request);
        $this->actionLog("更新启动页");
        return result($result->msg, $result->code, $result->data);
    }


    public function disableStartup(Request $request){
        $startupModel = new Startup();
        $result = $startupModel->disableStartup($request);
        $this->actionLog("更新禁用按钮");
        return result($result->msg, $result->code, $result->data);
    }

}
