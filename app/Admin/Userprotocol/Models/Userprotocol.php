<?php

namespace App\Admin\Userprotocol\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Userprotocol extends Model
{
    protected $table = "s_regular_registermember";

    public $timestamps = false;
    
    
    //获取详情信息
    public function getDetail(){
        $userprotocol = DB::table($this->table);
        $data = $userprotocol->select('*')->first();
        return $data;
    }
    //更新
    public function updateUserprotocol($request)
    {
        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->update(['content'=>$request->content]);
       // return $id;
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败',$id);
        }
    }
}


