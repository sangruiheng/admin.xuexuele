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

class PageController extends Controller
{
    public function __construct(){
        $this->formCheck = new FormCheck();
        $this->page = new Page();
    }

    //编辑单页内容
    public function update(Request $request){
        $checkId = $this->formCheck->isEmpty($request->id,"单页ID");
        if(!$checkId->code){
            return result($checkId->msg);
        }
        $result = $this->page->pageUpdate($request);
        return result($result->msg,$result->code);
    }

    //数据获取列表
    public function lists(Request $request){
        $dataContent = $this->page->getPageLists($request);
        return result("请求成功", 1, $dataContent);
    }
}