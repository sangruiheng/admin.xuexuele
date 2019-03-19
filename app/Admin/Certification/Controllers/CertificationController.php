<?php

namespace App\Admin\Certification\Controllers;

use App\Admin\Certification\Models\Certification;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class CertificationController extends Controller
{
    public function __construct()
    {
        $this->certification = new Certification();
        $this->formCheck = new FormCheck();
    }
    /***
     * 视图
     * @param Request $request
     */
    public function index(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Certification.Views.index")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    /**
     * 获取列表信息列表
     * @param Request $request
     */
    //数据获取列表
    public function getLists(Request $request){
        $abnormal = new Certification();
        $result = $abnormal->getLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    //更新审核状态
    public function upStatus(Request $request)
    {
//        if (!actionIsView("stop_certification")) {
//            return redirect(adminurl("/unauthorized"));
//        }
        $result = $this->certification->certificationStatus($request);
        $this->actionLog("更新实名认证审核状态");
        return result($result->msg, $result->code, $result->data);
    }

    //查看用户详情视图
    public function view(Request $request, $id)
    {
        if ($id) {
            $result = $this->certification->getDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Certification.Views.detail")
                ->with("result", $result)
                ->with("thisAction", "/certification")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }


    //调取报警信息
//    public function call(Request $request){
//        $abnormal = new Certification();
//        $res = $abnormal->call();
//        if($res > 0){
//            return result('程序触发报错警报，请及时查看',1);
//        }else{
//            return result('正常');
//        }
//    }


}
