<?php

namespace App\Admin\Gatealert\Controllers;

use App\Admin\Gate\Models\Gate;
use App\Admin\Gatealert\Models\Gatealert;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class GatealertController extends Controller
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
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Gatealert.Views.index")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    /**
     *
     * 获取列表信息列表
     * @param Request $request
     */
    //数据获取列表
    public function getLists(Request $request){
        $getealertModel = new Gatealert();
        $result = $getealertModel->getLists($request);
        return result($result->msg, $result->code, $result->data);

    }


    //添加弹窗显示
    public function addGateAlert(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Gatealert.Views.addgatealert")
            ->with("thisAction", "/gatealert")
            ->with("title", $menuInfo->title);
    }

    //添加弹窗内容
    public function addContent(Request $request){

        $formCheck = new FormCheck();
        $HeadingTitle = $formCheck->isEmpty($request->title,"弹窗名称");
        if(!$HeadingTitle->code){
            return result($HeadingTitle->msg);
        }
        $Image = $this->formCheck->isEmpty($request->img,"封面图片");
        if(!$Image->code){
            return result($Image->msg);
        }
        $Article = $this->formCheck->isEmpty($request->url,"弹窗链接");
        if(!$Article->code){
            return result($Article->msg);
        }

        $this->actionLog("新增弹窗");
        $getealertModel = new Gatealert();
        $result = $getealertModel->addContent($request);
        return result($result->msg, $result->code);
    }


    //修改弹窗内容显示
    public function editGateAlert(Request $request, $id)
    {
        if ($id) {
            $getealertModel = new Gatealert();
            $result = $getealertModel->getGateAlertDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Gatealert.Views.editgatealert")
                ->with("result", $result)
                ->with("thisAction", "/gatealert")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }

    //修改弹窗消息
    public function updateGateAlert(Request $request){
        $formCheck = new FormCheck();
        $HeadingTitle = $formCheck->isEmpty($request->title,"弹窗名称");
        if(!$HeadingTitle->code){
            return result($HeadingTitle->msg);
        }
        $Image = $this->formCheck->isEmpty($request->img,"封面图片");
        if(!$Image->code){
            return result($Image->msg);
        }
        $Article = $this->formCheck->isEmpty($request->url,"弹窗链接");
        if(!$Article->code){
            return result($Article->msg);
        }
        $getealertModel = new Gatealert();
        $result = $getealertModel->updateGateAlert($request);
        $this->actionLog("更新弹窗消息");
        return result($result->msg, $result->code, $result->data);
    }


    //删除专辑
    public function deleteGateAlert(Request $request){
        $getealertModel = new Gatealert();
        $result = $getealertModel->deleteGateAlert($request);
        return result($result->msg, $result->code, $result->data);

    }

}
