<?php

namespace App\Admin\Album\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Album extends Model
{
    protected $table = "s_users";
    protected $table_users_info = "s_users_info";
    protected $table_album = "s_album";
    protected $table_album_course = "s_album_course";
    protected $table_album_course_comment = "s_album_course_comment";

    public $timestamps = false;

    //获取首页列表 模糊查询-专辑
    public function getalbumtLists($request){
        $nameid = $request->get('nameid');
        $album = DB::table($this->table_album)->where('type',2);

        //查询条件账号状态
        if (!empty($nameid)) {
            $album->where('id', '=', $nameid);
        }

        //查询条件 名称
        if ($request->albumname != "") {
            $namekeyword = $request->albumname;
            $album->where(function ($album) use ($namekeyword) {
                $album->where('albumname', 'like', '%' . $namekeyword . '%');
            });
        }

        $data['total'] = $album->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $album->select('*')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
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


    //获取首页列表 模糊查询-课程
    public function getalbumtcourseLists($request, $id){
        $album = DB::table($this->table_album_course);
        $data['total'] = $album->where('albumid', '=', $id)->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $album->select('*')->where('isdelete',0)->where('albumid', '=', $id)->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        //通过id号获取课程数量
        foreach($data['lists'] as $k=>$v){
            //获取id [id] => 41
            if(!empty($v->id)){
                $id = $v->id;
                $albumcount = DB::table($this->table_album_course)->where('albumid',$id)->count(); //获取发布问题数量
                //将查询结果赋值
                $v->albumcount = $albumcount;
            }
        }


        return returnData('查询成功', 1, $data);
    }

    //获取首页列表 模糊查询-评论
    public function getalbumtcoursecommentLists($request, $id){
        $album = DB::table($this->table_album_course_comment)->where('pid', '=', 0);
        $data['total'] = $album->where('pid', '=', 0)->where('courseid', '=', $id)->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $album->select('*')->where('courseid', '=', $id)->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
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
    
    //获取详情信息-专辑
    public function getAlbumDetail($id){
        $album = DB::table($this->table_album);
        $data = $album->select('*')->where('id','=',$id)->first();
        return $data;
    }

    //获取详情信息-课程
    public function getAlbumCourseDetail($id){
        $album = DB::table($this->table_album_course);
        $data = $album->select('*')->where('id','=',$id)->first();
        return $data;
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
    //更新专辑
    public function updateAlbum($request)
    {
        $id = $request->get('id');
        $res = DB::table($this->table_album)->where('id', $id)->update(['albumname'=>$request->albumname,'albumimg'=>$request->albumimg,'albumcontent'=>$request->albumcontent]);
       // return $id;
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败');
        }
    }
    //添加专辑
    public function addAlbum($request){
        //获取平台用户id 
        $user = DB::table('s_users')->where('isadmin',1)->first();
        if($user){
            $data = DB::table($this->table_album)->insertGetId(
                ['albumname'=>$request->albumname,'albumimg'=>$request->albumimg,'albumcontent'=>$request->albumcontent,'create_time' =>date("Y-m-d H:i:s"),'uid'=>$user->id,'type'=>2]
            );
            return returnData('新增成功', 1, $data);
        }else{
            return returnData('请先添加平台用户', 0);
        }
    }
     //添加课程
    public function addCourse($request){
        if($request->free and $request->free==1){
            $free=1;
            $wisdombean=0;
        }else{
            $free=2;
            $wisdombean=$request->wisdombean;
        }
        $data = DB::table($this->table_album_course)->insertGetId(
            ['albumid'=>$request->albumid,'coursename'=>$request->coursename,'courseimg'=>$request->courseimg,'coursetxt'=>$request->coursetxt,'coursecontent'=>$request->coursecontent,'coursevoice'=>$request->coursevoice,'free'=>$free,'wisdombean'=>$wisdombean,'coursetime'=>$request->coursetime,'create_time' =>date("Y-m-d H:i:s")]
        );
        return returnData('新增成功', 1, $data);
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
