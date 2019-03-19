<?php

namespace App\Admin\Subscribe\Controllers;

use App\Admin\Subscribe\Models\Subscribe;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class SubscribeController extends Controller
{
    public function __construct()
    {
        $this->subscribe = new Subscribe();
        $this->formCheck = new FormCheck();
    }
    /***
     * 
     * @param Request $request
     */
    public function subscribe(Request $request)
    {
        $abnormal = new Subscribe();
        $result = $abnormal->getLists($request);
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Subscribe.Views.subscribe")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title)
            ->with("data", $result->data);
    }

    /**
     * 充值管理
     * 获取列表信息列表
     * @param Request $request
     */
    //数据获取列表
    public function getLists(Request $request){
        $abnormal = new Subscribe();
        $result = $abnormal->getLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    

}
