<?php

namespace App\Admin\Subject\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Subject extends Model
{

    protected $table = "s_gate_subject";

    public $timestamps = false;


    public function getLists($request)
    {
        $name = $request->get('name');
        $id = $request->get('id');
        $gate_id = $request->get('gate_id');
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

        $data['total'] = $gate->where('gate_id', $gate_id)->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $gate->select('*')->where('gate_id', $gate_id)->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        return returnData('查询成功', 1, $data);
    }


    public function addGateSubject($request){
//        $newdata = DB::table('s_gate')->select('*')->orderBy('id','desc')->first();
//        $countid = DB::table('s_gate')->count();
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

//        $gatename='第'.($countid+1).'关';

        $data = DB::table($this->table)->insertGetId(
            [
//                'id'=>$countid+1,
                'title'=>$request->title,
                'sort'=>$request->sort,
                'gate_id'=>$request->gate_id,
//                'specialreward'=>$specialreward,
//                'gaterewordid'=>$gaterewordid,
                'hintcontent'=>$hintcontent,
                'hintcontent_txt'=>$request->hintcontenttxt,
                'options'=>$request->showchoosetext,
                'answer'=>$request->answer,
//                'courserid'=>$request->courserid,
//                'answerwisdombeanuse'=>$request->answerwisdombeanuse,
                'contenttype'=>$request->ispicvideo,
//                'create_time' =>date("Y-m-d H:i:s"),
//                'alert_id' => $request->alert_id
            ]
        );
        return returnData('新增成功', 1, $data);
    }


    public function getSubjectDetail($id){
//        $data = DB::table('s_gate as a')
//            ->leftJoin('s_album_course as b','a.courserid','=','b.id')
//            ->leftJoin('s_gate_reword as c','a.gaterewordid','=','c.id')
//            ->select('a.*','b.coursename','c.type','c.heading')->where('a.id',$id)
//            ->first();

        $subject = DB::table($this->table);
        $data = $subject->select('*')->where('id','=',$id)->first();

        return $data;
    }

    public function editGateSubject($request){
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

            'title'=>$request->title,
            'sort'=>$request->sort,
            'gate_id'=>$request->gate_id,

//            'rewardbeans'=>$request->rewardbeans,
//            'pkvalue'=>$request->pk,
//            'specialreward'=>$specialreward,
//            'gaterewordid'=>$gaterewordid,
            'hintcontent'=>$hintcontent,
            'hintcontent_txt'=>$request->hintcontenttxt,
            'options'=>$request->showchoosetext,
            'answer'=>$request->answer,
//            'courserid'=>$request->courserid,
//            'answerwisdombeanuse'=>$request->answerwisdombeanuse,
            'contenttype'=>$request->ispicvideo
        ]);
        if($res){
            return returnData('操作成功', 1,$id);
        }else{
            return returnData('操作失败',0,$id);
        }
    }


    //获取所有关卡
    public function getAllGate(){
        $data = DB::table('s_gate')->orderBy('id', 'desc')->get();
        return $data;
    }

    //删除题目
    public function deleteSubject($request){
        $id = $request->get('id');
        $res=DB::table($this->table)->where('id',$id)->delete();
        if(!$res){
            return returnData("删除失败！",0);
        }else{
            return returnData("删除成功！",1);
        }
    }








}


