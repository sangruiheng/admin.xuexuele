<?php

namespace App\Admin\Advert\Controllers;

use App\Admin\Advert\Models\Advert;
use App\Admin\Gate\Models\Gate;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class AdvertController extends Controller
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
        return view("Admin.Advert.Views.index")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }


    public function getAdvert(Request $request)
    {
        $advertModel = new Advert();
        $result = $advertModel->getAdvert($request);
        return result($result->msg, $result->code, $result->data);
    }


    public function editAdvert(Request $request, $id)
    {
        if ($id) {
            $advertModel = new Advert();
            $result = $advertModel->getAdverttDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Advert.Views.editadvert")
                ->with("result", $result)
                ->with("thisAction", "/advert")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }



    public function updateAdvert(Request $request)
    {
        $formCheck = new FormCheck();
        $HeadingTitle = $formCheck->isEmpty($request->title, "弹窗名称");
        if (!$HeadingTitle->code) {
            return result($HeadingTitle->msg);
        }
        $Image = $this->formCheck->isEmpty($request->img, "封面图片");
        if (!$Image->code) {
            return result($Image->msg);
        }
        $Article = $this->formCheck->isEmpty($request->url, "弹窗链接");
        if (!$Article->code) {
            return result($Article->msg);
        }
        $advertModel = new Advert();
        $result = $advertModel->updateAdvert($request);
        $this->actionLog("更新广告弹窗");
        return result($result->msg, $result->code, $result->data);
    }


    public function disableAdvert(Request $request){
        $advertModel = new Advert();
        $result = $advertModel->disableAdvert($request);
        $this->actionLog("更新禁用按钮");
        return result($result->msg, $result->code, $result->data);
    }

}
