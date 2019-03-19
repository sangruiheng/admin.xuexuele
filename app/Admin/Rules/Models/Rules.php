<?php

namespace App\Admin\Rules\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Rules extends Model
{
    protected $table = "s_regular_score";

    public $timestamps = false;
    
    /**
    * 
    * 获取首页列表 模糊查询
    **/
    public function getLists($request){
        $rules = DB::table($this->table.' as a');
        
       
        $data['total'] = $rules->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $rules->select('*')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();

        $user = DB::table('s_users')->where('isadmin',1)->first();
        

        foreach ($data['lists'] as $list) {
            if($list->id==4){
                if($user){
                    $list->platename=$user->nickname;
                }else{
                    $list->platename='';
                }
            }
        }
        return returnData('查询成功', 1, $data);
    }
   
    //获取详情信息
    public function getDetail($id){
        $rules = DB::table($this->table);
        $data = $rules->select('*')->where('id','=',$id)->first();
        return $data;
    }
    //更新
    public function upsharingreward($request)
    {
        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->update(['sharerewardbeans'=>$request->sharerewardbeans]);
       // return $id;
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败',$id);
        }
    }
    //更新
    public function upwisdombeanreward($request)
    {
        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->update(['platrewardbeans'=>$request->platrewardbeans]);
       // return $id;
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败',$id);
        }
    }
    //更新充值金额
    public function uprechargeamount($request)
    {
        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->update(['money'=>$request->moneylist]);
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败',$id);
        }
    }
    //充值金额
    public function getRechargeamount($id){
        $rules = DB::table($this->table);
        $data = $rules->select('*')->where('id','=',$id)->first();
        $data->moneyArr = explode(' ', trim(str_replace(',',' ',$data->money)));
        return $data;
    }

    //平台信息
    public function getPlatform(){
        $data = DB::table('s_users')->where('isadmin',1)->first();

        return $data;
    }
    
    //更新平台信息
    public function updateplatform($request)
    {   
        $data = DB::table('s_users')->where('isadmin',1)->first();
        if($data){
            $res = DB::table('s_users')->where('id', $data->id)->update(['nickname'=>$request->nickname,'headimg'=>$request->headimg,'introduction'=>$request->introduction]); 
        }else{
            $res = DB::table('s_users')->insertGetId(
                    [
                        'nickname'=>$request->nickname,
                        'headimg'=>$request->headimg,
                        'introduction'=>$request->introduction,
                        'isadmin'=>1
                    ]
                );
        }
        
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败',0);
        }
    }
}


