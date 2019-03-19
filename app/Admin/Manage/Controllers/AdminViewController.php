<?php

namespace App\Admin\Manage\Controllers;

use App\Admin\Manage\Models\Admin;
use App\Admin\Manage\Models\Role;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Admin\Controller;

class AdminViewController extends Controller
{
    public function __construct(){
        $this->admin = new Admin();
        $this->formCheck = new FormCheck();
    }

    public function index(Request $request){
        $this->actionLog("查看管理员");
        $adminLists = $this->admin->getAdminLists();
        $menuInfo = getMenuFromPath($request->path());
        return view($this->viewPath('admin'))
            ->with("adminLists",$adminLists)
            ->with("thisAction",$menuInfo->url)
            ->with("title",$menuInfo->title);
    }

    //添加用户视图
    public function add(){
        //获取角色列表
        $role = new Role();
        $roleLists = $role->getRoleLists();
        $menuInfo = getMenuFromPath(env("BACKSTAGE_PREFIX")."/admins");
        return view($this->viewPath())
            ->with("thisAction",$menuInfo->url)
            ->with("title",$menuInfo->title)
            ->with("formAction","addForm")
            ->with("roleLists",$roleLists);
    }

    //编辑用户视图
    public function edit(Request $request,$id){
        //获取角色列表
        $role = new Role();
        $roleLists = $role->getRoleLists();
        $adminInfo = $this->admin->getAdminInfo($id);
        $adminInfo->avator_url = imageShow($adminInfo->avator,'100x100',url('images/default.png'));
        return view($this->viewPath())
            ->with("thisAction",'/admins')
            ->with("formAction","editForm")
            ->with("title","用户管理")
            ->with("roleLists",$roleLists)
            ->with("adminInfo",$adminInfo);
    }

    public function profile(){
        $adminInfo = Session::get('adminInfo');
        //获取角色列表
        $role = new Role();
        $roleLists = $role->getRoleLists();
        $adminInfo = $this->admin->getAdminInfo($adminInfo->id);
        $adminInfo->avator_url = imageShow($adminInfo->avator,'100x100',url('images/default.png'));
        return view($this->viewPath())
            ->with("thisAction",'/admins')
            ->with("formAction","editForm")
            ->with("title","用户管理")
            ->with("roleLists",$roleLists)
            ->with("adminInfo",$adminInfo);
    }

    public function repass(){
        return view($this->viewPath())
                ->with('title','密码修改');
    }
}