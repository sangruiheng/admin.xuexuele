<?php

namespace App\Admin\Rules\Controllers;

use App\Admin\Rules\Models\Rules;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class RulesController extends Controller
{
    public function __construct()
    {
        $this->rules = new Rules();
        $this->formCheck = new FormCheck();
    }
    /***
     * 
     * @param Request $request
     */
    public function ruleindex(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Rules.Views.ruleindex")
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
        $abnormal = new Rules();
        $result = $abnormal->getLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    //分享奖励
    public function sharingreward(Request $request, $id)
    {
        if ($id) {
            $result = $this->rules->getDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Rules.Views.sharingreward")
                ->with("result", $result)
                ->with("thisAction", "/rules")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    //智慧豆打赏
    public function wisdombeanreward(Request $request, $id)
    {
        if ($id) {
            $result = $this->rules->getDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Rules.Views.wisdombeanreward")
                ->with("result", $result)
                ->with("thisAction", "/rules")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    //充值金额
    public function rechargeamount(Request $request, $id)
    {
        if ($id) {
            $result = $this->rules->getRechargeamount($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Rules.Views.rechargeamount")
                ->with("result", $result)
                ->with("thisAction", "/rules")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    //更新分享奖励
    public function upsharingreward(Request $request)
    {
        $formCheck = new FormCheck();
        
        $result = $this->rules->upsharingreward($request);
        $this->actionLog("更新");
        return result($result->msg, $result->code, $result->data);
    }
    //更新智慧豆打赏
    public function upwisdombeanreward(Request $request)
    {
        $formCheck = new FormCheck();
        $Platrewardbeans = $formCheck->isEmpty($request->platrewardbeans,"智慧豆奖励比例");
        if(!$Platrewardbeans->code){
            return result($Platrewardbeans->msg);
        }
        $result = $this->rules->upwisdombeanreward($request);
        $this->actionLog("更新");
        return result($result->msg, $result->code, $result->data);
    }
    //更新充值金额
    public function uprechargeamount(Request $request)
    {
        $formCheck = new FormCheck();
        $Money = $formCheck->isEmpty($request->money,"充值金额");
        if(!$Money->code){
            return result($Money->msg);
        }
        $result = $this->rules->uprechargeamount($request);
        $this->actionLog("更新");
        return result($result->msg, $result->code, $result->data);
    }


    //平台
    public function platform(Request $request)
    {
        
        $result = $this->rules->getPlatform();
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Rules.Views.platform")
                ->with("result", $result)
                ->with("thisAction", "/rules")
                ->with("title", $menuInfo->title);
        
    }

    //更新平台信息
    public function updateplatform(Request $request)
    {
        $formCheck = new FormCheck();
        $nickname = $formCheck->isEmpty($request->nickname,"名称");
        if(!$nickname->code){
            return result($nickname->msg);
        }
        $headimg = $formCheck->isEmpty($request->headimg,"头像");
        if(!$headimg->code){
            return result($headimg->msg);
        }
        $introduction = $formCheck->isEmpty($request->introduction,"介绍");
        if(!$introduction->code){
            return result($introduction->msg);
        }
        $result = $this->rules->updateplatform($request);
        $this->actionLog("更新");
        return result($result->msg, $result->code, $result->data);
    }
    
    
}
