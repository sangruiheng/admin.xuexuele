<?php

namespace App\Admin\Gatealert\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Gatealert extends Model
{

    protected $table = "s_gate_alert";

    public $timestamps = false;


    public function getLists($request)
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
        $data['lists'] = $gate->select('*')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        return returnData('查询成功', 1, $data);
    }


    public function addContent($request)
    {
        $data = DB::table($this->table)->insertGetId(
            ['title' => $request->title, 'image_path' => $request->img, 'url' => $request->url]
        );
        return returnData('新增成功', 1, $data);
    }


    public function getGateAlertDetail($id){
        $gateAlert = DB::table($this->table);
        $data = $gateAlert->select('*')->where('id','=',$id)->first();
        return $data;
    }

    public function updateGateAlert($request){
        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->update(['title'=>$request->title,'image_path'=>$request->img,'url'=>$request->url]);
        // return $id;
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败');
        }
    }

    public function deleteGateAlert($request){
        $id = $request->get('id');
        $res=DB::table($this->table)->where('id',$id)->delete();
        if(!$res){
            return returnData("删除失败！",0);
        }else{
            return returnData("删除成功！",1);
        }
    }


}


