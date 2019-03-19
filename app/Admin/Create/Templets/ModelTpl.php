<?php
/**
 * @CopyRight 易创互联 www.huimor.com
 * @name 语言文件生成模板
 * @auth tzchao
 * @time 2018-06-012
 */

namespace App\Admin\Create\Templets;

use App\Admin\Controller;

class ModelTpl extends Controller
{
    /**
     * @return string  字符串
     */
    public function tplCont($moduleResult,$menuResult)
    {
        $module = 'App'.str_replace("/","\\",$moduleResult->data->module_dir);
        $menu = $menuResult->data;
        $action = $moduleResult->data->action;
        $modelName = ucfirst($moduleResult->data->name);
        $moduleName = strtolower($moduleResult->data->name);
        $createDate = date('Y-m-d');
        $str=<<<startData
<?php
/**
 * @CopyRight 易创互联 www.huimor.com
 * @name $menu->menu_name
 * @auth 
 * @time $createDate
 */
namespace $module\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class $modelName extends Model
{
   
startData;

        //生成列表页视图
        if(isset($action->lists)){
            $str.=<<<listData
    
    public \$timestamps = false
    
    //数据获取列表
    public function getLists(\$request){
        \$page = isset(\$request->page) ? \$request->page : 0;
        \$pageSize = isset(\$request->pageSize) ? \$request->pageSize : 15;
        \$pageStart = (\$page-1) * \$pageSize;
        \$query = DB::table(\$this->table);
        \$datatime =  \$request->datetime;
        //按指定时间进行查询
        if(\$request->datetime!=""){
            \$floatStartDate = substr(\$datatime,0,10);
            \$startDate = \$floatStartDate." 00:00:00";
            \$floatEndDate   = substr(\$datatime,-10);
            \$endDate   = \$floatEndDate." 00:00:00";
            //处理时间包含开始时间和结束时间
            \$startDateStr = strtotime(\$startDate) - 1;
            \$endDateStr   = strtotime(\$endDate) + (24*3600-1);

            \$startDate = date("Y-m-d H:i:s",\$startDateStr);
            \$endDate   = date("Y-m-d H:i:s",\$endDateStr);
            \$query->whereBetween('create_date', [\$startDate, \$endDate]);
        }
        //按关键词进行查询
        if(\$request->keyword!=""){
            \$keyword = \$request->keyword;
            \$query->where(function(\$query)use (\$keyword){
                \$query->where('message','like','%'.\$keyword.'%')
                    ->orWhere('file','like','%'.\$keyword.'%');
            });
        }
        \$data['count'] = \$query->count();
        \$data['lists'] = \$query->orderBy("create_date","DESC")->offset(\$pageStart)->limit(\$pageSize)->get();
        return \$data;
    }
    
listData;
        }
        //生成添加视图
        if(isset($action->add)){
            $str.=<<<addData
    
    //添加操作
    public function add(\$addData)
    {
        
        
    }
    
addData;
        }

        if(isset($action->edit)){
            $str.=<<<editData
    
    //修改操作
    public function edit(\$editData)
    {
        
    }
    
editData;
        }

        //删除操作
        if(isset($action->del)){
            $str.=<<<delData
    
    //删除操作
    public function delete(\$id)
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
