<?php

namespace App\Admin\Plug\Controllers;

use App\Admin\Plug\Models\Plug;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;

class PlugViewController extends Controller
{
    protected $status = array(
        0 => '待审核',
        1 => '已发布',
        2 => '审核失败'
    );

    public function __construct(){
        $this->formCheck = new FormCheck();
    }

    public function index(Request $request){
        $this->actionLog(Lang('Plug','view_Plug_lists'),$request->ip());
        $menuInfo = getMenuFromPath($request->path());
        //获取已安装插件总数
        $installedCount = Plug::count();
        //获取插件总数量
        $plugResult = json_decode(curlHttpsGet('http://plug.huimor.com/api/plugs/total'));
        $plugCount = $plugResult->data;
        return view($this->viewPath())
            ->with('installedCount',$installedCount)
            ->with('plugCount',$plugCount)
            ->with("thisAction",$menuInfo->url)
            ->with("title",$menuInfo->title);
    }
    //获取插件详情
    public function details(Request $request,$id){
        $plugResult = json_decode(curlHttpsGet('http://plug.huimor.com/api/plugs/details/'.$id));
        $plugInfo = $plugResult->data;
        return view($this->viewPath())
            ->with('status',$this->status)
            ->with('dataContent',$plugInfo)
            ->with("thisAction","/plugs")
            ->with("title",lang('Plug','plug_details'));
    }

    //安装后获取插件详情
    public function view(Request $request,$id){
        $plugResult = json_decode(curlHttpsGet('http://plug.huimor.com/api/plugs/details/'.$id));
        $plugInfo = $plugResult->data;
        return view($this->viewPath())
            ->with('status',$this->status)
            ->with('dataContent',$plugInfo)
            ->with("thisAction","/plugs")
            ->with("title",lang('Plug','plug_details'));
    }

}