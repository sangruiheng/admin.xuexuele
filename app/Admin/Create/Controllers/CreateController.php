<?php

namespace App\Admin\Create\Controllers;

use App\Admin\Create\Templets\ControllerTpl;
use App\Admin\Create\Templets\LanguageTpl;
use App\Admin\Create\Templets\ModelTpl;
use App\Admin\Create\Templets\ResourceJsTpl;
use App\Admin\Create\Templets\ViewAddTpl;
use App\Admin\Create\Templets\ViewEditTpl;
use App\Admin\Create\Templets\ViewListsTpl;
use App\Admin\Manage\Models\Role;
use App\Admin\Menu\Models\Menu;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateController extends Controller
{

    public function __construct(){
        $this->formCheck = new FormCheck();
        $this->controllerTpl = new ControllerTpl();
        $this->languageTpl = new LanguageTpl();
        $this->modelTpl = new ModelTpl();
        $this->viewListTpl = new ViewListsTpl();
        $this->viewAddTpl = new ViewAddTpl();
        $this->viewEditTpl = new ViewEditTpl();
        $this->resourceJsTpl = new ResourceJsTpl();
    }

    //创建模块
    public function index(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        $menu = new Menu();
        $menuLists = $menu->getMenuList();
        return view($this->viewPath())
            ->with("firstMenuList",$menuLists)
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    //创建模块
    pubLic function create(Request $request){
        //验证模块信息
        $moduleResult = $this->checkModuleInfo($request);
        if(!$moduleResult->code){
            return result($moduleResult->msg,$moduleResult->code);
        }
        //验证菜单信息
        $menuResult = $this->checkMenuInfo($request);
        if(!$menuResult->code){
            return result($menuResult->msg,$menuResult->code);
        }

        //执行创建模块操作
        $controller = $moduleResult->data->module_dir.'/Controllers/';
        $controllerName = $moduleResult->data->controller_name;
        $controllerViewName = $moduleResult->data->controller_view_name;
        $languages  = $moduleResult->data->module_dir.'/Languages/';
        $models  = $moduleResult->data->module_dir.'/Models/';
        $modelName = $moduleResult->data->model_name;
        $views  = $moduleResult->data->module_dir.'/Views/';
        //创建控制器文件
        $controllerCont = $this->controllerTpl->tplCont($moduleResult,$menuResult);
        if(isset($moduleResult->data->action->lists) || isset($moduleResult->data->action->add) || isset($moduleResult->data->action->edit)){
            Storage::disk('app')->put($controller.'/'.$controllerName,$controllerCont->controller);
            Storage::disk('app')->put($controller.'/'.$controllerViewName,$controllerCont->controller_view);
        }else{
            Storage::disk('app')->put($controller.'/'.$controllerName,$controllerCont->controller);
        }
        $languageTpl = $this->languageTpl->tplCont();
        //创建语言包文件
        Storage::disk('app')->put($languages.'/zh-cn.php',$languageTpl);
        //创建模块文件
        $modelCont = $this->modelTpl->tplCont($moduleResult,$menuResult);
        Storage::disk('app')->put($models.'/'.$modelName,$modelCont);

        //创建视图文件
        if(isset($request->action['lists'])){
            $listsTpl = $this->viewListTpl->tplCont($moduleResult);
            Storage::disk('app')->put($views.'/index.blade.php',$listsTpl);
        }
        if(isset($request->action['add'])){
            $addTpl = $this->viewAddTpl->tplCont($moduleResult);
            Storage::disk('app')->put($views.'/add.blade.php',$addTpl);
        }
        if(isset($request->action['edit'])){
            $editTpl = $this->viewEditTpl->tplCont($moduleResult);
            Storage::disk('app')->put($views.'/edit.blade.php',$editTpl);
        }

        //执行创建css文件及js文件操作
        if($moduleResult->data->css){
            $reourceCssPath = $moduleResult->data->resource_css_path;
            Storage::disk('local')->put($reourceCssPath.'/'.$moduleResult->data->name.'.css','');
        }
        if($moduleResult->data->js){
            if(isset($request->action['lists'])){
                $moduleJsTplLists = $this->resourceJsTpl->tplCont($menuResult,'lists');
                $reourceJsPath = $moduleResult->data->resource_js_path;
                Storage::disk('local')->put($reourceJsPath.'/'.$moduleResult->data->name.'.js',$moduleJsTplLists);
            }
            if(isset($request->action['add']) || isset($request->action['edit'])){
                $moduleJsTplLists = $this->resourceJsTpl->tplCont($menuResult,'add');
                $reourceJsPath = $moduleResult->data->resource_js_path;
                Storage::disk('local')->put($reourceJsPath.'/'.'add.edit.js',$moduleJsTplLists);
            }
        }

        //执行创建菜单信息
        $addData = array(
            "title"=>htmlspecialchars($menuResult->data->menu_name),
            "menu_label" => uniqid(),
            "url"  =>$request->url,
            "icon_class"=>htmlspecialchars($menuResult->data->icon_class),
            "parent_id"=>intval($menuResult->data->parent_id),
            "sort"=>0,
            "create_date"=>date("Y-m-d H:i:s"),
            "update_date"=>date("Y-m-d H:i:s"),
        );
        $lasrtId = DB::table('admin_menus')->insertGetId($addData);
        if($lasrtId){
            //更新排序
            DB::table('admin_menus')->where("id","=",$lasrtId)->update(["sort"=>$lasrtId]);
            $role = new Role();
            $role->addAuthMenu($lasrtId);//为管理员添加管理权限
            //DB::table('admin_menus')->where("id","=",$lasrtId)->update(["sort"=>$lasrtId]);
        }else{
            return result("菜单信息写入失败！");
        }
        return result('模块创建成功，请自行创建路由');
    }

    //验证模块写入信息合法性
    protected function checkModuleInfo($request){
        $moduleData = array();
        $moduleDir = $request->dir;
        $formCheck = new FormCheck();
        $moduleData['controller_name'] = ucfirst($request->dir).'Controller.php';
        $moduleData['controller_view_name'] = ucfirst($request->dir).'ViewController.php';
        $moduleData['model_dir'] = $request->dir;
        $moduleData['model_name'] = ucfirst($request->name).'.php';
        $moduleData['name'] = $request->name;
        $checkModuleDir = $formCheck->isEmpty($moduleDir,'模块目录');
        if(!$checkModuleDir->code){
            return returnData($checkModuleDir->msg);
        }

        $moduleData['module_name'] = $request->name;
        $checkModuleName = $formCheck->isEmpty($request->name,'模块名称');
        if(!$checkModuleName->code){
            return returnData($checkModuleName->msg);
        }

        $moduleData['css'] = $request->css;
        $moduleData['js']  = $request->js;

        $module = app_path('Admin');
        $resourcePath = public_path('resources/');

        //验证模块目录是否存在
        if(is_dir($module.'/'.ucfirst($moduleDir))){
            return returnData('模块目录已存在');
        }
        $moduleData['module_dir'] = '/Admin/'.ucfirst($moduleDir);
        if($moduleData['css']){
            //验证css目录是否存在
            if(is_dir($resourcePath.'/css/'.strtolower($moduleData['model_dir']))){
                return returnData('css目录'.strtolower($moduleData['model_dir']).'已存在');
            }
            $moduleData['resource_css_path'] = 'resource/css/'.strtolower($moduleData['model_dir']);
        }
        if($moduleData['js']){
            //验证css目录是否存在
            if(is_dir($resourcePath.'/js/'.strtolower($moduleData['model_dir']))){
                return returnData('JS目录'.strtolower($moduleData['model_dir']).'已存在');
            }
            $moduleData['resource_js_path'] = 'resource/js/'.strtolower($moduleData['model_dir']);
        }
        $moduleData['action'] = $request->action;
        return returnData('验证通过',1,$moduleData);
    }

    //验证菜单信息合法性
    protected function checkMenuInfo($request){
        $menuData = array();
        //菜单名称验证
        if(isset($request->menuname) && $request->menuname){
            $menuData['menu_name'] = $request->menuname;
        }
        //上级菜单验证
        if(isset($request->parent_id)){
            $menuData['parent_id'] = $request->parent_id;
        }
        //菜单URL验证
        if(isset($request->url) && $request->url){
            $menuData['url'] = $request->url;
            if (preg_match("/[\x7f-\xff]/", $request->url)) {
                return returnData('菜单URL不能含有中文');
            }
        }
        //菜单图标验证
        if(isset($request->icon_class) && $request->icon_class){
            $menuData['icon_class'] = $request->icon_class;
        }
        return returnData('验证通过',1,$menuData);
    }
}
