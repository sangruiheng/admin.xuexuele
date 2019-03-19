<?php

namespace App\Admin\Reporting\Controllers;

use App\Admin\Reporting\Models\Reporting;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class ReportingController extends Controller
{
    public function __construct()
    {
        $this->reporting = new Reporting();
        $this->formCheck = new FormCheck();
    }
    /***
     * 视图
     * @param Request $request
     */
    public function index(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Reporting.Views.index")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    /**
     * 获取列表信息列表
     * @param Request $request
     */
    //数据获取列表
    public function getLists(Request $request){
        $abnormal = new Reporting();
        $result = $abnormal->getLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    //更新审核状态
    public function upStatus(Request $request)
    {
//        if (!actionIsView("stop_certification")) {
//            return redirect(adminurl("/unauthorized"));
//        }
        $result = $this->reporting->ReportingStatus($request);
        $this->actionLog("更新实名认证审核状态");
        return result($result->msg, $result->code, $result->data);
    }
    //驳回
    /*public function delete(Request $request){
        $abnormal = new Reporting();
        $result = $abnormal->delete($request);
        return result($result->msg, $result->code, $result->data);

    }*/

    //查看信息审核详情视图
    public function checkview(Request $request, $id)
    {
        if ($id) {
            $result = $this->reporting->getDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Reporting.Views.checkdetail")
                ->with("result", $result)
                ->with("thisAction", "/reporting")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    //查看信息恢复详情视图
    public function recoveryview(Request $request, $id)
    {
        if ($id) {
            $result = $this->reporting->getDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Reporting.Views.recoverydetail")
                ->with("result", $result)
                ->with("thisAction", "/reporting")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }

}
