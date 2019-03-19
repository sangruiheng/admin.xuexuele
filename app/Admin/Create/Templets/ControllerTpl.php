<?php
/**
 * @CopyRight 易创互联 www.huimor.com
 * @name 控制器生成模板
 * @auth tzchao
 * @time 2018-06-012
 */

namespace App\Admin\Create\Templets;

use App\Admin\Controller;

class ControllerTpl extends Controller
{
    /**
     * @param $moduleResult  模块信息
     * @param $menuResult    目录信息
     * @return object|void  返回对象
     */
    public function tplCont($moduleResult,$menuResult)
    {
        //创建模板视图
        $data['controller_view'] = $this->tplView($moduleResult,$menuResult);
        $data['controller'] = $this->tplController($moduleResult,$menuResult);
        return arrayToObject($data);
    }

    /**
     * 创建视图控制器模板
     * @param $moduleResult  模块信息
     * @param $menuResult    目录信息
     * @return string  返回字符串
     */
    protected function tplView($moduleResult,$menuResult){
        $module = 'App'.str_replace("/","\\",$moduleResult->data->module_dir);
        $menu = $menuResult->data;
        $action = $moduleResult->data->action;
        $moduleControllerName = ucfirst($moduleResult->data->name).'ViewController';
        $createDate = date('Y-m-d');
        $str=<<<startData
<?php
/**
 * @CopyRight 易创互联 www.huimor.com
 * @name 控制器生成模板
 * @auth 
 * @time $createDate
 */
namespace $module\Controllers;

use Illuminate\Http\Request;
use App\Admin\Controller;

class $moduleControllerName extends Controller
{

startData;
        //生成列表页视图
        if(isset($action->lists)){
            $str.=<<<listData
    
    //列表页视图
    public function index(Request \$request)
    {
        \$menuInfo = getMenuFromPath(\$request->path());
        return view(\$this->viewPath())
            ->with('thisAction', \$menuInfo->url)
            ->with('title', \$menuInfo->title);
    }
    
listData;
        }
        //生成添加视图
        if(isset($action->add)){
            $str.=<<<addData
    
    //添加视图
    public function add(Request \$request)
    {
        \$menuInfo = getMenuFromPath(\$request->path());
        return view(\$this->viewPath())
            ->with('thisAction', '$menu->url')
            ->with('title', '$menu->menu_name');
    }
    
addData;
        }

        //生成编辑视图
        if(isset($action->edit)){
            $str.=<<<addData
    
    //添加视图
    public function edit(Request \$request)
    {
        \$menuInfo = getMenuFromPath(\$request->path());
        return view(\$this->viewPath())
            ->with('thisAction', '$menu->url')
            ->with('title', '$menu->menu_name');
    }
    
addData;
        }

        //生成结尾信息
        $str.=<<<endData
        
        
        
}
endData;

        return $str;
    }

    /**
     * 创建控制器模板
     * @param $moduleResult  模块信息
     * @param $menuResult    目录信息
     * @return string  返回字符串
     */
    protected function tplController($moduleResult,$menuResult){
        $module = 'App'.str_replace("/","\\",$moduleResult->data->module_dir);
        $menu = $menuResult->data;
        $action = $moduleResult->data->action;
        $moduleControllerName = ucfirst($moduleResult->data->name).'Controller';
        $moduleName = strtolower($moduleResult->data->name);
        $moduleDir = ucfirst($moduleResult->data->model_dir);
        $createDate = date('Y-m-d');
        $listsAction = $moduleName.'->getLists($request)';
        $str=<<<startData
<?php
/**
 * @CopyRight 易创互联 www.huimor.com
 * @name $menu->menu_name
 * @auth 
 * @time $createDate
 */
namespace $module\Controllers;

use Illuminate\Http\Request;
use App\Admin\Controller;
use App\Admin\\$moduleDir\Models\\$moduleName;

class $moduleControllerName extends Controller
{

startData;

        //生成列表页视图
        if(isset($action->lists)){
            $str.=<<<listData
    public function __construct(){
        \$this->$moduleName = new $moduleName();
    }
    
    //数据获取列表
    public function lists(Request \$request){
        \$dataLists = \$this->$listsAction;
        return result('数据获取成功',1,\$dataLists);
    }
    
listData;
        }
        //生成添加视图
        if(isset($action->add)){
            $str.=<<<addData
    
    //添加操作
    public function add(Request \$request)
    {
        
        
    }
    
addData;
        }

        if(isset($action->edit)){
            $str.=<<<editData
    
    //修改操作
    public function edit(Request \$request)
    {
        
    }
    
editData;
        }

        //删除操作
        if(isset($action->del)){
            $str.=<<<delData
    
    //删除操作
    public function delete(Request \$request)
    {
        
    }
    
delData;
        }

        //生成结尾信息
        $str.=<<<endData
        
}
endData;

        return $str;
    }
}
