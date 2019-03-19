<?php

namespace App\Admin\Reporting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Reporting extends Model
{

    protected $table = "s_regular";
    protected $table_album = "s_album";
    protected $table_album_course = "s_album_course";
    protected $table_users = "s_users";


    public $timestamps = false;

    //获取首页列表 模糊查询
    public function getLists($request){
        $is_normal = $request->get('is_normal');
        $albumname = $request->get('albumname');
        $coursename = $request->get('coursename');
        $reporting = DB::table($this->table.' as a')
                            ->join($this->table_album.' as b','a.albumid','=','b.id')
                            ->join($this->table_album_course.' as c','a.courseid','=','c.id')
                            ->join($this->table_users.' as d','a.uid','=','d.id')
                            ->join($this->table_users.' as e','a.reportedid','=','e.id');
        //查询条件账号状态
        if (!empty($is_normal)) {
            $reporting->where('a.state', '=', $is_normal);
        }
        //查询条件name 昵称
        if ($request->albumname != "") {
            $namekeyword = $request->albumname;
            $reporting->where(function ($reporting) use ($namekeyword) {
                $reporting->where('b.albumname', 'like', '%' . $namekeyword . '%');
            });
        }
        //查询条件name 昵称
        if ($request->coursename != "") {
            $namekeyword = $request->coursename;
            $reporting->where(function ($reporting) use ($namekeyword) {
                $reporting->where('c.coursename', 'like', '%' . $namekeyword . '%');
            });
        }

        $data['total'] = $reporting->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $reporting->select('a.id','c.coursename','b.albumname','d.nickname','a.create_time','a.classify','a.state','e.nickname as albumuser')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        return returnData('查询成功', 1, $data);
    }



    //获取详情信息
    public function getDetail($id){
        $reporting = DB::table($this->table.' as a')
                            ->join($this->table_album.' as b','a.albumid','=','b.id')
                            ->join($this->table_album_course.' as c','a.courseid','=','c.id')
                            ->join($this->table_users.' as d','a.uid','=','d.id')
                            ->join($this->table_users.' as e','a.reportedid','=','e.id');
        $data = $reporting->select('a.id','a.create_time','a.classify','a.state','b.albumname','b.albumimg','b.albumcontent','c.coursename','c.coursetxt','c.courseimg','c.coursecontent','c.coursevoice','d.nickname','e.nickname as albumuser')->where('a.id','=',$id)->first();
        return $data;
    }


    //更新状态
    public function ReportingStatus($request)
    {
        $id = $request->get('id');
        //举报成功
        if($request->get('status')==2){
           $res = DB::table($this->table)->where('id', $id)->update(['state'=>$request->get('status')]); 
           //删除课程
           $courseid= DB::table($this->table)->where('id', $id)->first();
           $course = DB::table('s_album_course')->where('id', $courseid->courseid)->update(['isdelete'=>1]);
           $courseinfo = DB::table('s_album_course as a')->join('s_album as b','a.albumid','=','b.id')->where('a.id', $courseid->courseid)->select('a.*','b.uid')->first();
          
           //发送站内信
           $msg = DB::table('s_users_msg')->insertGetId(
            ['heading' =>'课程违规', 'content' => '您的课程“'.$courseinfo->coursename.'”存在违规现象，已被删除，请联系平台进行恢复','uid'=>$courseinfo->uid,'create_time' =>date("Y-m-d H:i:s")]); 
        }
        //举报驳回
        if($request->get('status')==3){
            $res = DB::table($this->table)->where('id', $id)->delete(); 
        }
        //恢复
        if($request->get('status')==4){
            $courseid= DB::table($this->table)->where('id', $id)->first();
            $course = DB::table('s_album_course')->where('id', $courseid->courseid)->update(['isdelete'=>0]);
            $res = DB::table($this->table)->where('id', $id)->delete(); 
        }
        
        

        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败');
        }
    }
    //驳回
    /*public function delete($request){
        $id = $request->get('id');
        $res=DB::table($this->table)->where('id',$id)->delete();
        if(!$res){
            return returnData("删除失败！",0);
        }else{
            return returnData("删除成功！",1);
        }
    }*/
}


