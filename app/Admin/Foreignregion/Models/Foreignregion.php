<?php

namespace App\Admin\Foreignregion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Foreignregion extends Model
{
    protected $table = "s_regular_country";

    public $timestamps = false;
    
    /**
    * 
    * 获取首页列表 模糊查询
    **/
    public function getLists($request){
        $foreignregion = DB::table($this->table.' as a');
        
       
        $data['total'] = $foreignregion->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $foreignregion->select('a.id','a.country','a.city')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        return returnData('查询成功', 1, $data);
    }
    //删除
    public function deleteForeignregion($request){
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
        $foreignregion = DB::table($this->table);
        $data = $foreignregion->select('*')->where('id','=',$id)->first();
        $data->cityArr = explode(' ', trim(str_replace(',',' ',$data->city)));
        return $data;
    }
    //更新
    public function updateForeignregion($request)
    {
        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->update(['country'=>$request->country,'city'=>$request->citylist]);
        
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败',$id);
        }
    }
    //添加
    public function addForeignregion($request){
        $data = DB::table($this->table)->insertGetId(
            ['country'=>$request->country,'city'=>$request->citylist]
        );
        return returnData('新增成功', 1, $data);
    }
}


