<?php

namespace App\Admin\Signreward\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Signreward extends Model
{
    protected $table = "s_signin_rule";

    public $timestamps = false;
    
    /**
    * 
    * 获取首页列表 模糊查询
    **/
    public function getLists($request){
        $signreward = DB::table($this->table.' as a');
        
       
        $data['total'] = $signreward->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $signreward->select('a.id','a.name','a.day','a.rewordbeans')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        return returnData('查询成功', 1, $data);
    }
    //删除
    public function deleteSignreward($request){
        $id = $request->get('id');
        $res=DB::table($this->table)->where('id',$id)->delete();
        if(!$res){
            return returnData("删除失败！",0);
        }else{
            return returnData("删除成功！",1);
        }
    }
    //获取详情信息
    public function getDetail($id){
        $signreward = DB::table($this->table);
        $data = $signreward->select('*')->where('id','=',$id)->first();
        return $data;
    }
    //更新
    public function updateSignreward($request)
    {
        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->update(['name'=>$request->name,'day'=>$request->day,'rewordbeans'=>$request->rewordbeans]);
       // return $id;
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败',$id);
        }
    }
    //添加
    public function addSignreward($request){
        $data = DB::table($this->table)->insertGetId(
            ['name'=>$request->name,'day'=>$request->day,'create_time' =>date("Y-m-d H:i:s"),'rewordbeans'=>$request->rewordbeans]
        );
        return returnData('新增成功', 1, $data);
    }
}


