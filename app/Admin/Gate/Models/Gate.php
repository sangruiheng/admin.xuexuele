<?php

namespace App\Admin\Gate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Gate extends Model
{

    protected $table = "s_gate";

    public $timestamps = false;
    /**
    * 充值管理
    * 获取首页列表 模糊查询
    **/
    public function getLists($request){
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
                $gate->where('gatename', 'like', '%' . $namekeyword . '%');
            });
        }
        
        $data['total'] = $gate->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $gate->select('*')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        //获取关卡题目数量
        foreach($data['lists'] as &$value){
            $value->subject_sum = DB::table('s_gate_subject')
                ->select('*')
                ->where('gate_id',$value->id)
                ->count();
        }
        return returnData('查询成功', 1, $data);
    }

    public function getDetail($id){
        $data = DB::table('s_gate as a')
        ->leftJoin('s_album_course as b','a.courserid','=','b.id')
        ->leftJoin('s_gate_reword as c','a.gaterewordid','=','c.id')
        ->select('a.*','b.coursename','c.type','c.heading')->where('a.id',$id)
        ->first();
        return $data;
    }
    
    //更新
    public function updateGate($request)
    {   
        if($request->teshu!=0){
            $specialreward=1;
            $gaterewordid=$request->gaterewordid;
        }else{
            $specialreward=2;
            $gaterewordid=0;
        }
        if($request->ispicvideo==1){
            $hintcontent=$request->pictureurl;
        }else{
            $hintcontent=$request->videourl;
        }

        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->update([
                'rewardbeans'=>$request->rewardbeans,
                'pkvalue'=>$request->pk,
                'specialreward'=>$specialreward,
                'gaterewordid'=>$gaterewordid,
                'hintcontent'=>$hintcontent,
                'hintcontent_txt'=>$request->hintcontenttxt,
                'options'=>$request->showchoosetext,
                'answer'=>$request->answer,
                'courserid'=>$request->courserid,
                'answerwisdombeanuse'=>$request->answerwisdombeanuse,
                'contenttype'=>$request->ispicvideo,
                'alert_id' => $request->alert_id,
                'alert_errid' => $request->alert_errid,

        ]);
        if($res){
            return returnData('操作成功', 1,$id);
        }else{
            return returnData('操作失败',0,$id);
        }
    }
    //添加
    public function addGate($request){
        $newdata = DB::table('s_gate')->select('*')->orderBy('id','desc')->first();
        $countid = DB::table('s_gate')->count();
        if($request->teshu!=0){
            $specialreward=1;
            $gaterewordid=$request->gaterewordid;
        }else{
            $specialreward=2;
            $gaterewordid=0;
        }
        if($request->ispicvideo==1){
            $hintcontent=$request->pictureurl;
        }else{
            $hintcontent=$request->videourl;
        }

        $gatename='第'.($countid+1).'关';

        $data = DB::table($this->table)->insertGetId(
            [   
                'id'=>$countid+1,
                'gatename'=>$gatename,
                'rewardbeans'=>$request->rewardbeans,
                'pkvalue'=>$request->pk,
                'specialreward'=>$specialreward,
                'gaterewordid'=>$gaterewordid,
                'hintcontent'=>$hintcontent,
                'hintcontent_txt'=>$request->hintcontenttxt,
                'options'=>$request->showchoosetext,
                'answer'=>$request->answer,
                'courserid'=>$request->courserid,
                'answerwisdombeanuse'=>$request->answerwisdombeanuse,
                'contenttype'=>$request->ispicvideo,
                'create_time' =>date("Y-m-d H:i:s"),
                'alert_id' => $request->alert_id,
                'alert_errid' => $request->alert_errid,
            ]
        );
        return returnData('新增成功', 1, $data);
    }

    //课程列表
    public function courselist($request){
        $data = DB::table('s_album_course as a')->join('s_album as b','b.id','=','a.albumid')
        ->select('a.id as id','a.coursename','a.create_time','a.albumid','b.id as bid','b.albumname','b.type')
        ->where('a.isdelete',0);
        if($request->type!=''){
            $data =$data->where('b.type',$request->type);
        }
        if($request->keywords!=''){
            $data =$data->where('a.coursename','like','%'.$request->keywords.'%');
        }
        
        $data =$data->get();
        return returnData('查询成功', 0, $data);
    }

    //课程列表
    public function rewordlist($request){
        $data = DB::table('s_gate_reword')->select('*');
        $data =$data->where('type',$request->type);
        if($request->keywords!=''){
            $data =$data->where('heading','like','%'.$request->keywords.'%');
        }
        
        $data =$data->get();
        return returnData('查询成功', 0, $data);
    }

    //最新关卡
    public function newgate($request){
        $data = DB::table('s_gate')->count();
        return returnData('查询成功', 0, $data);
    }

    //获取全部弹窗
    public function alertList(){
        $data = DB::table('s_gate_alert')->get();
//        return returnData('查询成功', 0, $data);
        return $data;
    }
}


