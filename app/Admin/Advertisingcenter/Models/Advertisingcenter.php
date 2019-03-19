<?php

namespace App\Admin\Advertisingcenter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Advertisingcenter extends Model
{

    protected $table_users_msg = "s_users_msg";
    protected $table_users = "s_users";
    protected $table_banner = "s_banner";

    public $timestamps = false;
    /**
    * 消息通知
    * 获取首页列表 模糊查询
    **/
    public function getNoticeLists($request){
        $heading = $request->get('heading');
        $advertising = DB::table('s_msg_info');
                            // ->join($this->table_users. ' as b','a.uid','=','b.id');
        
        //查询条件name 昵称
        if ($request->heading != "") {
            $namekeyword = $request->heading;
            $advertising->where(function ($advertising) use ($namekeyword) {
                $advertising->where('heading', 'like', '%' . $namekeyword . '%');
            });
        }

        $data['total'] = $advertising->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['noticelists'] = $advertising->select('*')->offset($number)->limit($paged)->orderBy('id', 'desc')->get();
        return returnData('查询成功', 1, $data);
    }
    //获取详情信息
    public function getNoticeDetail($id){
        $advertising = DB::table('s_msg_info');
        $data = $advertising->select('*')->where('id','=',$id)->first();
        return $data;
    }
    //消息发布
    public function addnotice($request){
        //$adminInfo = Session::get("adminInfo");
        if ($request->identity2 && $request->identity1) {
            $data=DB::table($this->table_users)->select('*')->get();
            $usertype='侠客 导师';
        }else{
            if ($request->identity2) {
                 $data=DB::table($this->table_users)->select('*')->where('identity','=',2)->get();
                 $usertype='侠客';
            }else if ($request->identity1) {
                $data=DB::table($this->table_users)->select('*')->where('identity','=',1)->get();
                $usertype='导师';
            }
        }

        DB::table('s_msg_info')->insertGetId(
            ['heading' =>$request->heading, 'content' => $request->content,'usertype'=>$usertype,'create_time' =>date("Y-m-d H:i:s")]);

        foreach ($data as $value) {
            
            DB::table($this->table_users_msg)->insertGetId(
            ['uid' =>$value->id , 'is_read' => 1,'heading'=>$request->heading,'content'=>$request->content,'create_time' =>date("Y-m-d H:i:s")]);
        }
        return returnData('新增成功', 1, $data);
    }


    /**
    * 广告管理
    * 获取首页列表 模糊查询
    **/
    public function getAdvertisementLists($request){
        $name = $request->get('heading');
        $advertising = DB::table($this->table_banner. ' as a');
        
        //查询条件name 昵称
        if ($request->name != "") {
            $namekeyword = $request->name;
            $advertising->where(function ($advertising) use ($namekeyword) {
                $advertising->where('a.heading', 'like', '%' . $namekeyword . '%');
            });
        }

        $data['total'] = $advertising->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['advertisementlists'] = $advertising->select('a.id','a.create_time','a.heading','a.image','a.sort')->offset($number)->limit($paged)->orderBy('id', 'asc')->get();
        return returnData('查询成功', 1, $data);
    }
    //获取详情信息
    public function getAdvertisementDetail($id){
        $advertising = DB::table($this->table_banner);
        $data = $advertising->select('*')->where('id','=',$id)->first();
        return $data;
    }
    //更新状态
    public function AdvertisementStatus($request)
    {   $id = $request->get('id');
        $issort =  DB::table($this->table_banner)->where('sort',$request->sort)->where('id','<>',$id)->count();
        if($issort>0){
           return returnData('位置重复，请重新填写', 0); 
        }

        
        $res = DB::table($this->table_banner)->where('id', $id)->update(['heading'=>$request->heading,'sort'=>$request->sort,'image'=>$request->image,'content'=>$request->content]);
        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败');
        }
    }
    //广告发布
    public function addadvertisement($request){
        $issort =  DB::table($this->table_banner)->where('sort',$request->sort)->count();
        if($issort>0){
           return returnData('位置重复，请重新填写', 0); 
        }

        $data = DB::table($this->table_banner)->insertGetId(
            ['heading'=>$request->heading,'image'=>$request->image,'create_time' =>date("Y-m-d H:i:s"),'sort'=>$request->sort,'content'=>$request->content]
        );
        return returnData('新增成功', 1, $data);
    }
}


