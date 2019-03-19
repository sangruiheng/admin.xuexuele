<?php

namespace App\Admin\Signreward\Controllers;

use App\Admin\Signreward\Models\Signreward;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class SignrewardController extends Controller
{
    public function __construct()
    {
        $this->signreward = new Signreward();
        $this->formCheck = new FormCheck();
    }
    /***
     * 
     * @param Request $request
     */
    public function signreward(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Signreward.Views.signreward")
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
        $abnormal = new Signreward();
        $result = $abnormal->getLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    /**
     * 删除
     * @param Request $request
     */
    //删除
    public function delete(Request $request){
        $abnormal = new Signreward();
        $result = $abnormal->deleteSignreward($request);
        return result($result->msg, $result->code, $result->data);

    }
    //详情
    public function signrewardview(Request $request, $id)
    {
        if ($id) {
            $result = $this->signreward->getDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Signreward.Views.signrewarddetail")
                ->with("result", $result)
                ->with("thisAction", "/signreward")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    //更新
    public function update(Request $request)
    {
        $formCheck = new FormCheck();
        $Name = $formCheck->isEmpty($request->name,"奖励名称");
        if(!$Name->code){
            return result($Name->msg);
        }
        $Day = $formCheck->isEmpty($request->day,"签到天数");
        if(!$Day->code){
            return result($Day->msg);
        }
        $Rewordbeans = $formCheck->isEmpty($request->rewordbeans,"奖励智慧豆");
        if(!$Rewordbeans->code){
            return result($Rewordbeans->msg);
        }
        $result = $this->signreward->updateSignreward($request);
        $this->actionLog("更新奖励规则");
        return result($result->msg, $result->code, $result->data);
    }
    //添加
    public function addsignreward(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Signreward.Views.addsignreward")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }
    public function add(Request $request)
    {
        $formCheck = new FormCheck();
        $Name = $formCheck->isEmpty($request->name,"奖励名称");
        if(!$Name->code){
            return result($Name->msg);
        }
        $Day = $formCheck->isEmpty($request->day,"签到天数");
        if(!$Day->code){
            return result($Day->msg);
        }
        $Rewordbeans = $formCheck->isEmpty($request->rewordbeans,"奖励智慧豆");
        if(!$Rewordbeans->code){
            return result($Rewordbeans->msg);
        }
        $result = $this->signreward->addSignreward($request);
        $this->actionLog("添加奖励规则");
        return result($result->msg, $result->code);
    }
}
