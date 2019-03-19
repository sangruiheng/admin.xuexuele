<?php

namespace App\Admin\Certification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Certification extends Model
{

    protected $table = "s_users";
    protected $table_users_info = "s_users_info";
    protected $table_users_msg = "s_users_msg";


    public $timestamps = false;

    //获取首页列表 模糊查询
    public function getLists($request){
        $is_normal = $request->get('is_normal');
        $uid = $request->get('uid');
        $name = $request->get('name');
        $phone = $request->get('phone');
        $certification = DB::table($this->table_users_info.' as a')->join($this->table.' as b','b.id','=','a.uid');

        //查询条件账号状态
        if (!empty($is_normal)) {
            $certification->where('b.certificationstate', '=', $is_normal);
        }

        if (!empty($uid)) {
            $certification->where('a.id', '=', $uid);
        }

        //查询条件name 昵称
        if ($request->name != "") {
            $namekeyword = $request->name;
            $certification->where(function ($certification) use ($namekeyword) {
                $certification->where('b.nickname', 'like', '%' . $namekeyword . '%');
            });
        }
        //查询条件 手机号
        if ($request->phone != "") {
            $phone = $request->phone;
            $certification->where(function ($certification) use ($phone) {
                $certification->where('b.phone', 'like', '%' . $phone . '%');
            });
        }
        $data['total'] = $certification->count();//获取总数量
        $page = intval($request->get('page')) ? intval($request->get('page')) : 1;
        $paged = intval($request->get('limit')) ? intval($request->get('limit')) : 15;
        $number = ($page - 1) * $paged;
        $data['lists'] = $certification->select('a.id','b.nickname','b.phone','a.create_time','b.certificationstate')->offset($number)->limit($paged)->orderBy('id', 'desc')->get();
        return returnData('查询成功', 1, $data);
    }



    //获取详情信息
    public function getDetail($id){
        $certification = DB::table($this->table. ' as b')
            ->join($this->table_users_info. ' as c',"c.uid","=","b.id");
        $data = $certification->select('b.id','b.phone','b.identity','b.nickname','b.wisdombean','b.creditscore','b.pk','b.certificationstate','b.sex','b.birthday','b.constellation','b.city','c.name','c.identitycard','c.identityimg','c.schoolname','c.education','c.profession')->where('c.id','=',$id)->first();
        return $data;
    }


    //更新状态
    public function certificationStatus($request)
    {
        $id = $request->get('id');

        $user =DB::table($this->table)->where('id', $id)->first();

        $res = DB::table($this->table)->where('id', $id)->update(['certificationstate'=>$request->get('status'),'creditscore'=>($user->creditscore+30)]);

        $status=$request->get('status');
        if($status==2){
            
            DB::table($this->table_users_msg)->insertGetId(
            ['uid' =>$id , 'is_read' => 1,'heading'=>'审核结果','content'=>'您提交的实名认证信息已通过审核，去查看>>','create_time' =>date("Y-m-d H:i:s")]);

        }
        if($status==4)
        {   
            DB::table($this->table_users_msg)->insertGetId(
            ['uid' =>$id , 'is_read' => 1,'heading'=>'审核结果','content'=>'您提交的实名认证信息审核未通过，请重新填写。','create_time' =>date("Y-m-d H:i:s")]);

        }

        if($res){
            return returnData('操作成功', 1);
        }else{
            return returnData('操作失败');
        }
    }


}
