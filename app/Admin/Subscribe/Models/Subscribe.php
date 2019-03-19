<?php

namespace App\Admin\Subscribe\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Subscribe extends Model
{
    protected $table_users = "s_users";
    protected $table_album = "s_album";
    protected $table_album_course = "s_album_course";
    protected $table_users_wisdombeanuse = "s_users_wisdombeanuse";
    protected $table_users_wisdombeanuse_details = "s_users_wisdombeanuse_details";

    public $timestamps = false;
    
    /**
    * 
    * 获取首页列表 模糊查询
    **/
    public function getLists($request){
        $datatime =  $request->datetime;
        $subscribe = DB::table($this->table_album_course.' as a')
                            ->join($this->table_album.' as b','a.albumid','=','b.id')
                            ->join($this->table_users.' as c','b.uid','=','c.id')
                            ->join($this->table_users_wisdombeanuse_details.' as d','d.courseid','=','a.id')
                            ->join($this->table_users_wisdombeanuse.' as e','d.wisdombeanuseid','=','e.id')
                            ->where('a.isdelete',0);
        //按指定时间进行查询
        if($request->datetime!=""){
            $floatStartDate = substr($datatime,0,10);
            $startDate = $floatStartDate." 00:00:00";
            $floatEndDate   = substr($datatime,-10);
            $endDate   = $floatEndDate." 00:00:00";
            if(strtotime($startDate) > time()){

            }else if(strtotime($startDate) > strtotime($endDate)){

            }else{
                //处理时间包含开始时间和结束时间
                $startDateStr = strtotime($startDate) - 1;
                $endDateStr   = strtotime($endDate) + (24*3600-1);

                $startDate = date("Y-m-d H:i:s",$startDateStr);
                $endDate   = date("Y-m-d H:i:s",$endDateStr);
                $subscribe->whereBetween('a.create_time', [$startDate, $endDate]);
            }
        }
        //$studysum = DB::table($this->table_album_course.' as a')->sum('a.studysum');
        //$data['studysumcount']=$studysum;
        $data['total'] = $subscribe->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $subscribe->select('a.id','a.coursename','b.albumname','c.nickname','a.studysum','e.wisdombean','e.rewardplatform')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        $studycount = DB::table($this->table_album_course)->sum('studysum'); 
        $data['studycount']= $studycount;

        $wisdombeancount = DB::table($this->table_users_wisdombeanuse)->sum('wisdombean'); 
        $data['wisdombeancount']= $wisdombeancount;

        $rewardplatformcount = DB::table($this->table_users_wisdombeanuse)->sum('rewardplatform'); 
        $data['rewardplatformcount']= $rewardplatformcount;
        
        return returnData('查询成功', 1, $data);
    }

}


