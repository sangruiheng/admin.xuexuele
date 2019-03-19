<?php

namespace App\Admin\Plug\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;


class Plug extends Model
{
    protected $table = "plug_installs";

    //获取用户列表
    public function getUserLists($request){
        $page = isset($request->page) ? $request->page : 0;
        $pageSize = isset($request->page_size) ? $request->page_size : 12;
        $pageStart = ($page-1) * $pageSize;
        $query = DB::table($this->table)->leftjoin($this->table_details,$this->table.'.id','=',$this->table_details.'.user_id');
        //按关键词进行查询
        if($request->keyword!=""){
            $keyword = $request->keyword;
            $query->where(function($query)use ($keyword){
                $query->where($this->table_details.'.nickname','like','%'.$keyword.'%')
                    ->orWhere($this->table.'.mobile','like','%'.$keyword.'%')
                    ->orWhere($this->table.'.email','like','%'.$keyword.'%')
                    ->orWhere($this->table.'.account','like','%'.$keyword.'%');
            });
        }
        $data['count'] = $query->count();
        $data['lists'] = $query->orderBy($this->table_details.".create_date","DESC")->offset($pageStart)->limit($pageSize)->get();
        return $data;
    }

    //获取已安装插件列表
    public function installedLists($request){
        $page = isset($request->page) ? $request->page : 0;
        $pageSize = isset($request->page_size) ? $request->page_size : 12;
        $pageStart = ($page-1) * $pageSize;
        $query = DB::table($this->table);
        //按关键词进行查询
        $data['count'] = $query->count();
        $data['lists'] = $query->orderBy("create_date","DESC")->offset($pageStart)->limit($pageSize)->get();
        return $data;
    }
}
