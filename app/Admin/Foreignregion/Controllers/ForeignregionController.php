<?php

namespace App\Admin\Foreignregion\Controllers;

use App\Admin\Foreignregion\Models\Foreignregion;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class ForeignregionController extends Controller
{
    public function __construct()
    {
        $this->foreignregion = new Foreignregion();
        $this->formCheck = new FormCheck();
    }
    /***
     * 
     * @param Request $request
     */
    public function foreignregion(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Foreignregion.Views.foreignregion")
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
        $abnormal = new Foreignregion();
        $result = $abnormal->getLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    /**
     * 删除
     * @param Request $request
     */
    //删除
    public function delete(Request $request){
        $abnormal = new Foreignregion();
        $result = $abnormal->deleteForeignregion($request);
        return result($result->msg, $result->code, $result->data);

    }
    //详情
    public function foreignregionview(Request $request, $id)
    {
        if ($id) {
            $result = $this->foreignregion->getDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Foreignregion.Views.foreignregiondetail")
                ->with("result", $result)
                ->with("thisAction", "/foreignregion")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    //更新
    public function update(Request $request)
    {
        $formCheck = new FormCheck();
        $Country = $formCheck->isEmpty($request->country,"国家名称");
        if(!$Country->code){
            return result($Country->msg);
        }
        $City = $formCheck->isEmpty($request->city,"城市名称");
        if(!$City->code){
            return result($City->msg);
        }
        $result = $this->foreignregion->updateForeignregion($request);
        $this->actionLog("更新国家信息");
        return result($result->msg, $result->code, $result->data);
    }
    //添加
    public function addforeignregion(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Foreignregion.Views.addforeignregion")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }
    public function add(Request $request)
    {
        $formCheck = new FormCheck();
        $Country = $formCheck->isEmpty($request->country,"国家名称");
        if(!$Country->code){
            return result($Country->msg);
        }
        $City = $formCheck->isEmpty($request->city,"城市名称");
        if(!$City->code){
            return result($City->msg);
        }
        
        $result = $this->foreignregion->addForeignregion($request);
        $this->actionLog("添加国家");
        return result($result->msg, $result->code);
    }
}
