<?php

namespace App\Admin\Financial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Financial extends Model
{

    protected $table = "s_order";
    protected $table_users = "s_users";
    protected $table_album = "s_album";
    protected $table_album_course = "s_album_course";
    protected $table_users_wisdombeanuse = "s_users_wisdombeanuse";
    protected $table_users_wisdombeanuse_details = "s_users_wisdombeanuse_details";

    public $timestamps = false;
    /**
    * 充值管理
    * 获取首页列表 模糊查询
    **/
    public function getRechargeLists($request){
        $datatime =  $request->datetime;
        $is_normal = $request->get('is_normal');
        $name = $request->get('name');
        $phone = $request->get('phone');
        $financial = DB::table($this->table.' as a')
                            ->join($this->table_users.' as b','a.uid','=','b.id')->where('a.state',2);
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
                $financial->whereBetween('a.create_time', [$startDate, $endDate]);
            }
        }

        if (!empty($is_normal)) {
            $financial->where('a.complaint', '=', $is_normal);
        }
        //查询条件name 昵称
        if ($request->name != "") {
            $namekeyword = $request->name;
            $financial->where(function ($financial) use ($namekeyword) {
                $financial->where('b.nickname', 'like', '%' . $namekeyword . '%');
            });
        }
        //查询条件 手机号
        if ($request->phone != "") {
            $phone = $request->phone;
            $financial->where(function ($financial) use ($phone) {
                $financial->where('b.phone', 'like', '%' . $phone . '%');
            });
        }

        $data['total'] = $financial->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['rechargelists'] = $financial->select('a.id','a.create_time','a.complaint','b.nickname','b.phone','a.wisdombean')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        return returnData('查询成功', 1, $data);
    }
    /**
    * 订单管理
    * 获取首页列表 模糊查询
    **/
    public function getOrdersLists($request){
        $datatime =  $request->datetime;
        $id = $request->get('id');
        $name = $request->get('name');
        $nickname = $request->get('nickname');
        $albumname = $request->get('albumname');
        $financial = DB::table($this->table_users_wisdombeanuse.' as a')
                            ->join($this->table_users_wisdombeanuse_details.' as b','b.wisdombeanuseid','=','a.id')
                            ->join($this->table_users.' as c','a.uid','=','c.id')
                            // ->join($this->table_album.' as d','b.albumid','=','d.id')
                            // ->join($this->table_album_course.' as e','b.courseid','=','e.id')
                            ->where('a.type',1);
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
                $financial->whereBetween('a.create_time', [$startDate, $endDate]);
            }
        }
        if (!empty($id)) {
            $financial->where('a.id', '=', $id);
        }
        //查询条件购买用户
        if ($request->name != "") {
            $namekeyword = $request->name;
            $financial->where(function ($financial) use ($namekeyword) {
                $financial->where('c.nickname', 'like', '%' . $namekeyword . '%');
            });
        }
        //查询条件所属导师
        if ($request->nickname != "") {
            $namekeyword = $request->nickname;
            $financial->where(function ($financial) use ($namekeyword) {
                $financial->where('c.nickname', 'like', '%' . $namekeyword . '%');
            });
        }
        //查询条件 专辑名称
        if ($request->albumname != "") {
            $albumname = $request->albumname;
            $financial->where(function ($financial) use ($albumname) {
                $financial->where('b.albumname', 'like', '%' . $albumname . '%');
            });
        }



        $data['total'] = $financial->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['orderslists'] = $financial->select('a.id','a.create_time','c.nickname','c.phone','b.albumuid','b.albumname','a.wisdombean','a.rewardplatform')->offset($number)->limit($paged)->orderBy('id', 'asc')
        ->groupBy('a.id','a.create_time','c.nickname','c.phone','b.albumname','a.wisdombean','a.rewardplatform','b.albumuid')
        ->get();
        foreach ($data['orderslists'] as $orders) {
            $coursenum=DB::table($this->table_users_wisdombeanuse_details)->where('wisdombeanuseid',$orders->id)->count();
            $orders->coursenum=$coursenum;
            $albumuser = DB::table($this->table_users)->where('id',$orders->albumuid)->first();
            $orders->albumuser=$albumuser->nickname;

        }
        return returnData('查询成功', 1, $data);
    }


    /**
    * 充值管理导出
    * 获取首页列表 模糊查询
    **/
    public function getRechargeListsExcel($request){
        $datatime =  $request->datetime;
        $is_normal = $request->get('is_normal');
        $name = $request->get('name');
        $phone = $request->get('phone');
        $financial = DB::table($this->table.' as a')
                            ->join($this->table_users.' as b','a.uid','=','b.id')->where('a.state',2);
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
                $financial->whereBetween('a.create_time', [$startDate, $endDate]);
            }
        }

        if (!empty($is_normal)) {
            $financial->where('a.complaint', '=', $is_normal);
        }
        //查询条件name 昵称
        if ($request->name != "") {
            $namekeyword = $request->name;
            $financial->where(function ($financial) use ($namekeyword) {
                $financial->where('b.nickname', 'like', '%' . $namekeyword . '%');
            });
        }
        //查询条件 手机号
        if ($request->phone != "") {
            $phone = $request->phone;
            $financial->where(function ($financial) use ($phone) {
                $financial->where('b.phone', 'like', '%' . $phone . '%');
            });
        }

        // $data['total'] = $financial->count();//获取总数量
        // $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        // $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        // $number = ($page - 1) * $paged;
        $data= $financial->select('a.id','a.create_time','a.complaint','b.nickname','b.phone','a.wisdombean')->orderBy('id', 'asc')->get();
        return $data;//returnData('查询成功', 1, $data);
    }


    /**
    * 订单管理导出
    * 获取首页列表 模糊查询
    **/
    public function getOrdersListsExcel($request){
        $datatime =  $request->datetime;
        $id = $request->get('id');
        $name = $request->get('name');
        $nickname = $request->get('nickname');
        $albumname = $request->get('albumname');
        $financial = DB::table($this->table_users_wisdombeanuse.' as a')
                            ->join($this->table_users_wisdombeanuse_details.' as b','b.wisdombeanuseid','=','a.id')
                            ->join($this->table_users.' as c','a.uid','=','c.id')
                            // ->join($this->table_album.' as d','b.albumid','=','d.id')
                            // ->join($this->table_album_course.' as e','b.courseid','=','e.id')
                            ->where('a.type',1);
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
                $financial->whereBetween('a.create_time', [$startDate, $endDate]);
            }
        }
        if (!empty($id)) {
            $financial->where('a.id', '=', $id);
        }
        //查询条件购买用户
        if ($request->name != "") {
            $namekeyword = $request->name;
            $financial->where(function ($financial) use ($namekeyword) {
                $financial->where('c.nickname', 'like', '%' . $namekeyword . '%');
            });
        }
        //查询条件所属导师
        if ($request->nickname != "") {
            $namekeyword = $request->nickname;
            $financial->where(function ($financial) use ($namekeyword) {
                $financial->where('c.nickname', 'like', '%' . $namekeyword . '%');
            });
        }
        //查询条件 专辑名称
        if ($request->albumname != "") {
            $albumname = $request->albumname;
            $financial->where(function ($financial) use ($albumname) {
                $financial->where('b.albumname', 'like', '%' . $albumname . '%');
            });
        }

        // $data['total'] = $financial->count();//获取总数量
        // $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        // $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        // $number = ($page - 1) * $paged;
        $data = $financial->select('a.id','a.create_time','c.nickname','c.phone','b.albumuid','b.albumname','a.wisdombean','a.rewardplatform')->orderBy('id', 'asc')
        ->groupBy('a.id','a.create_time','c.nickname','c.phone','b.albumname','a.wisdombean','a.rewardplatform','b.albumuid')
        ->get();
        foreach ($data as $orders) {
            $coursenum=DB::table($this->table_users_wisdombeanuse_details)->where('wisdombeanuseid',$orders->id)->count();
            $orders->coursenum=$coursenum;
            $albumuser = DB::table($this->table_users)->where('id',$orders->albumuid)->first();
            $orders->albumuser=$albumuser->nickname;

        }
        return $data;//returnData('查询成功', 1, $data);
    }

}


