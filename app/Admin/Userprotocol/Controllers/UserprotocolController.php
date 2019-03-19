<?php

namespace App\Admin\Userprotocol\Controllers;

use App\Admin\Userprotocol\Models\Userprotocol;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class UserprotocolController extends Controller
{
    public function __construct()
    {
        $this->userprotocol = new Userprotocol();
        $this->formCheck = new FormCheck();
    }
    /***
     * 
     * @param Request $request
     */
    public function userprotocol(Request $request)
    {
            $result = $this->userprotocol->getDetail();
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Userprotocol.Views.userprotocol")
                ->with("result", $result)
                ->with("thisAction", $menuInfo->url)
                ->with("title", $menuInfo->title);
    }

    
    //更新
    public function update(Request $request)
    {
        $formCheck = new FormCheck();
        $Content = $formCheck->isEmpty($request->content,"奖励名称");
        if(!$Content->code){
            return result($Content->msg);
        }
        
        $result = $this->userprotocol->updateUserprotocol($request);
        $this->actionLog("更新奖励规则");
        return result($result->msg, $result->code, $result->data);
    }
    
}
