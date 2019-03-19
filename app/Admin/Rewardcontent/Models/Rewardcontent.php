<?php

namespace App\Admin\Rewardcontent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Rewardcontent extends Model
{

    protected $table = "s_gate_reword";

    public $timestamps = false;
    /**
    * 
    * 获取首页列表 模糊查询
    **/
    public function getLists($request){
        $type = $request->get('type');
        $id = $request->get('id');
        $heading = $request->get('heading');
        $rewardcontent = DB::table($this->table);
        //查询条件账号状态
        if (!empty($type)) {
            $rewardcontent->where('type', '=', $type);
        }

        if (!empty($id)) {
            $rewardcontent->where('id', '=', $id);
        }
        //查询条件name 昵称
        if ($request->heading != "") {
            $namekeyword = $request->heading;
            $rewardcontent->where(function ($rewardcontent) use ($namekeyword) {
                $rewardcontent->where('heading', 'like', '%' . $namekeyword . '%');
            });
        }

        $data['total'] = $rewardcontent->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $rewardcontent->select('*')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        return returnData('查询成功', 1, $data);
    }
    //获取详情信息
    public function getDetail($id){
        $data = DB::table($this->table)->select('*')->where('id','=',$id)->first();
        return $data;
    }
    //删除
    public function deleteReward($request){
        $id = $request->get('id');
        $res=DB::table($this->table)->where('id',$id)->delete();
        if(!$res){
            return returnData("删除失败！",0);
        }else{
            return returnData("删除成功！",1);
        }
    }
    //更新文章
    public function updateContent($request)
    {
        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->where('type','=',1)->update(['heading'=>$request->heading,'img'=>$request->img,'article'=>$request->article]);
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败');
        }
    }
    //更新音频
    public function updateVoice($request)
    {
        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->where('type','=',2)->update(['heading'=>$request->heading,'img'=>$request->img,'voice'=>$request->voice]);
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败');
        }
    }
    //新增文章
    public function addcontent($request){
        $data = DB::table($this->table)->insertGetId(
            ['type'=>1,'heading'=>$request->heading,'img'=>$request->img,'article'=>$request->article,'create_time' =>date("Y-m-d H:i:s")]
        );
        return returnData('新增成功', 1, $data);
    }

    
    //新增音频
    public function addvoice($request){
        $data = DB::table($this->table)->insertGetId(
            ['type'=>2,'heading'=>$request->heading,'img'=>$request->img,'voice'=>$request->voice,'create_time' =>date("Y-m-d H:i:s")]
        );
        return returnData('新增成功', 1, $data);
    }
}


