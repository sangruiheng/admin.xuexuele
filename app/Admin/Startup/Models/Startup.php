<?php

namespace App\Admin\Startup\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Startup extends Model
{

    protected $table = "s_startup";

    public $timestamps = false;


    public function getAdvert($request)
    {
        $name = $request->get('name');
        $id = $request->get('id');
        $gate = DB::table($this->table);
        //查询条件账号状态
        if (!empty($id)) {
            $gate->where('id', '=', $id);
        }
        //查询条件name 昵称
        if ($request->name != "") {
            $namekeyword = $request->name;
            $gate->where(function ($gate) use ($namekeyword) {
                $gate->where('title', 'like', '%' . $namekeyword . '%');
            });
        }

        $data['total'] = $gate->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
//        $data['lists'] = $gate->select('*')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        $data['lists'] = $gate->select('*')->where('id','=',1)->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        return returnData('查询成功', 1, $data);
    }


    public function getstartupDetail(){
        $gateAlert = DB::table($this->table);
        $data = $gateAlert->select('*')->where('id','=',1)->first();
        return $data;
    }



    public function updateStartup($request){
        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->update(['image_path'=>$request->img]);
        // return $id;
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败');
        }
    }

    public function disableStartup($request){
        $id = $request->get('startup_id');
        $is_disable = $request->is_disable == 0 ? 1 : 0;
        $res = DB::table($this->table)->where('id', $id)->update(['is_disable'=>$is_disable]);
        // return $id;
        $adv = DB::table($this->table)->where('id', $id)->first();
        if($res){
            return returnData('操作成功', 1,$adv->is_disable);
        }else{
            return returnData('操作失败');
        }
    }


}


