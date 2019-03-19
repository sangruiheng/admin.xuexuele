<?php

namespace App\Admin\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class User extends Model
{

    protected $table = "s_users";
    protected $table_users_info = "s_users_info";
    protected $table_album = "s_album";
    protected $table_album_course = "s_album_course";
    protected $table_album_course_comment = "s_album_course_comment";

    public $timestamps = false;

    //获取首页列表 模糊查询
    public function getLists($request){
        $is_normal = $request->get('is_normal');
        $certificationstate = $request->get('certificationstate');
        $uid = $request->get('uid');
        $name = $request->get('name');
        $phone = $request->get('phone');
        $user = DB::table($this->table)->where('isadmin',0);

        //查询条件账号状态
        if (!empty($is_normal)) {
            $user->where('userstate', '=', $is_normal);
        }

        if (!empty($certificationstate)) {
            $user->where('certificationstate', '=', $certificationstate);
        }

        if (!empty($uid)) {
            $user->where('id', '=', $uid);
        }

        //查询条件name 昵称
        if ($request->name != "") {
            $namekeyword = $request->name;
            $user->where(function ($user) use ($namekeyword) {
                $user->where('nickname', 'like', '%' . $namekeyword . '%');
            });
        }
        //查询条件 手机号
        if ($request->phone != "") {
            $phone = $request->phone;
            $user->where(function ($user) use ($phone) {
                $user->where('phone', 'like', '%' . $phone . '%');
            });
        }
        $data['total'] = $user->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $user->select('*')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        return returnData('查询成功', 1, $data);
    }

    //获取首页列表 模糊查询
    public function getalbumtLists($request, $id){
        $nameid = $request->get('nameid');
        $user = DB::table($this->table_album);

        //查询条件账号状态
        if (!empty($nameid)) {
            $user->where('id', '=', $nameid);
        }

        //查询条件 名称
        if ($request->albumname != "") {
            $namekeyword = $request->albumname;
            $user->where(function ($user) use ($namekeyword) {
                $user->where('albumname', 'like', '%' . $namekeyword . '%');
            });
        }

        $data['total'] = $user->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $user->select('*')->where('uid', '=', $id)->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        //通过id号获取课程数量
        foreach($data['lists'] as $k=>$v){
            //获取id [id] => 41
            if(!empty($v->id)){
                $id = $v->id;
                $albumcount = DB::table($this->table_album_course)->where('isdelete',0)->where('albumid',$id)->count(); //获取发布问题数量
                //将查询结果赋值
                $v->albumcount = $albumcount;
            }
        }


        return returnData('查询成功', 1, $data);
    }


    //获取首页列表 模糊查询
    public function getalbumtcourseLists($request, $id){
        $user = DB::table($this->table_album_course)->where('isdelete',0);
        $data['total'] = $user->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $user->select('*')->where('albumid', '=', $id)->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        //通过id号获取课程数量
        foreach($data['lists'] as $k=>$v){
            //获取id [id] => 41
            if(!empty($v->id)){
                $id = $v->id;
                $albumcount = DB::table($this->table_album_course)->where('isdelete',0)->where('albumid',$id)->count(); //获取发布问题数量
                //将查询结果赋值
                $v->albumcount = $albumcount;
            }
        }


        return returnData('查询成功', 1, $data);
    }

    //获取首页列表 模糊查询
    public function getalbumtcoursecommentLists($request, $id){
        $user = DB::table($this->table_album_course_comment)->where('pid', '=', 0);
        $data['total'] = $user->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $user->select('*')->where('courseid', '=', $id)->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        foreach($data['lists'] as $k=>$v){
            if(!empty($v->uid)){
                $id = $v->uid;
                $userinfo = DB::table($this->table)->select('nickname')->where('id',$id)->first(); //获取发布问题数量
                //将查询结果赋值
                $v->username = $userinfo->nickname;
            }
        }
        return returnData('查询成功', 1, $data);
    }



    //获取详情信息
    public function getDetail($id){
        $user = DB::table($this->table. ' as b')
            ->leftjoin($this->table_users_info. ' as c',"c.uid","=","b.id");
        $data = $user->select('b.id','b.phone','b.nickname','b.wisdombean','b.creditscore','b.pk','b.certificationstate','b.sex','b.birthday','b.constellation','b.city','c.name','c.identitycard','c.identityimg','c.schoolname','c.education','c.profession')->where('b.id','=',$id)->first();
        return $data;
    }

    //获取详情信息
    public function getAlbumDetail($id){
        $user = DB::table($this->table_album);
        $data = $user->select('*')->where('id','=',$id)->first();
        return $data;
    }

    //获取详情信息
    public function getAlbumCourseDetail($id){
        $user = DB::table($this->table_album_course);
        $data = $user->select('*')->where('id','=',$id)->first();
        return $data;
    }

    //更新用户账号状态
    public function userStatus($request)
    {
        $id = $request->get('id');
        $res = DB::table($this->table)->where('id', $id)->update(['userstate'=>$request->get('status'),'remember_token'=>$request->get('status')]);
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败');
        }
    }

    //删除专辑
    public function deleteAlbum($request){
        $id = $request->get('id');
        $res=DB::table($this->table_album)->where('id',$id)->delete();
        $res=DB::table($this->table_album_course)->where('albumid',$id)->delete();
        if(!$res){
            return returnData("删除失败！",0);
        }else{
            return returnData("删除成功！",1);
        }
    }
    //删除课程
    public function deleteAlbumCourse($request){
        $id = $request->get('id');
        $res=DB::table($this->table_album_course)->where('id',$id)->delete();
        if(!$res){
            return returnData("删除失败！",0);
        }else{
            return returnData("删除成功！",1);
        }
    }

    //删除评论
    public function deleteAlbumCourseComment($request){
        $id = $request->get('id');
        $comment=DB::table($this->table_album_course_comment)->where('id',$id)->first();
        $res=DB::table($this->table_album_course_comment)->where('id',$id)->delete();
         //数量统计
        $count = DB::table('s_album_course_comment')->where('courseid',$comment->courseid)->where('pid',0)->count();
        $course = DB::table('s_album_course')->where('id',$comment->courseid)->update(['commentsum' => $count]);
        if(!$res){
            return returnData("删除失败！",0);
        }else{
            return returnData("删除成功！",1);
        }
    }

    public function ScoreList($id){
      
        // $data =  array(array('coursescore'=>'3','sum'=>'0'),array('coursescore'=>'5','sum'=>'0'),array('coursescore'=>'7','sum'=>'0'),array('coursescore'=>'10','sum'=>'0'));

        // foreach ($data as $k=>$v) 
        // {   
            
            $data = DB::table('s_album_course_score')
            ->select('coursescore',DB::raw('count(id) as total'))
            ->where('courseid','=',$id)
            // ->where('uid','=',$request->uid)
            // ->where('coursescore','=',$v['coursescore'])
            ->groupBy('coursescore')
            ->get();
            
            // if($res){
            //    $data[$k]['sum']=$res->total; 
            // }
            
            
            
        // }
        return $data;

    }
}
