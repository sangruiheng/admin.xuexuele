<?php
/*
 * @name 单页管理
 * @auth tzchao
 * @time 2017-11-15
 */

namespace App\Admin\Page\Controllers;

use App\Admin\Page\Models\Page;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;

class PageViewController extends Controller
{
    public function __construct()
    {
        $this->formCheck = new FormCheck();
        $this->page = new Page();
    }

    public function index(Request $request)
    {
        $this->actionLog("查看单页");
        $menuInfo = getMenuFromPath($request->path());
        return view($this->viewPath())
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    public function edit(Request $request, $id)
    {
        $this->actionLog("修改单页");
        //获取单页详情
        $dataContent = $this->page->getPageInfo($id);
        return view($this->viewPath())
            ->with("id",$id)
            ->with("dataContent",$dataContent)
            ->with("thisAction", "/pages")
            ->with("title","编辑内容");
    }

    public function view(Request $request, $id)
    {
        //获取单页详情
        $dataContent = $this->page->getPageInfo($id);
        return view($this->viewPath('info'))
            ->with("dataContent",$dataContent)
            ->with("thisAction", "/pages")
            ->with("title","查看内容");
    }
}