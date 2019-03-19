<?php

namespace App\Admin\Abnormal\Controllers;

use App\Admin\Abnormal\Models\Abnormal;
use Illuminate\Http\Request;
use App\Admin\Controller;

class AbnormalController extends Controller
{
    /***
     * 分类列表视图
     * @param Request $request
     */
    public function index(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view($this->viewPath())
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    /**
     * 获取错误信息列表
     * @param Request $request
     */
    //数据获取列表
    public function getLists(Request $request){
        $abnormal = new Abnormal();
        $dataContent = $abnormal->getLists($request);
        return result("请求成功", 1, $dataContent);
    }

    //调取报警信息
    public function call(Request $request){
        $abnormal = new Abnormal();
        $res = $abnormal->call();
//        if($res > 0){
//            return result('程序触发报错警报，请及时查看',1);
//        }else{
            return result('正常');
//        }
    }
}
