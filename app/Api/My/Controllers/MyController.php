<?php
namespace App\Api\My\Controllers;

use App\Api\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Api\My\Models\My;
use App\Classlib\FormCheck;
use Illuminate\Support\Facades\Cache;
use App\Classlib\JSSDK;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 首页
 */
class MyController extends Controller
{

    public $timestamps   = false;
    /**
     * [__construct 构造方法]
     */
    public function __construct()
    {
        $this->My = new My();
        $this->FormCheck = new FormCheck();
    }

    /**
     * 个人中心-设置
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function Setup(Request $request){
        //判断USER_ID是否存在
        $phone = $this->FormCheck->isEmpty($request->uid,'用户id');
        if (!$phone->code) {
            return result($phone->msg);
        }
        $phone = $this->FormCheck->isEmpty($request->identity,'身份');
        if (!$phone->code) {
            return result($phone->msg);
        }

        $res = DB::table('s_users')->where('id',$request->uid)->update(['identity' => $request->identity]);

        return $this->response('true',1,$res,$request->apilog_id);

    }

    /**
     * 个人中心-头像上传
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function HeadPortrait(Request $request){
        // $file = $request->file("file");
        // if ($file->isValid()) {
        //     // 获取文件相关信息
        //     $originalName = $file->getClientOriginalName(); // 文件原名
        //     $ext = $file->getClientOriginalExtension();     // 扩展名
        //     $realPath = $file->getRealPath();   //临时文件的绝对路径
        //     $type = $file->getClientMimeType();     // image/jpeg
        //     // 上传文件
        //     $filename = "uploads/" . date("Y/m") . "/" . date('YmdHis') . uniqid() . '.' . $ext;
        //     // 使用我们新建的uploads本地存储空间（目录）
        //     $result = Storage::disk('local')->put($filename, file_get_contents($realPath));
        //     $data['thumb'] = $filename;
        //     $data['img'] = url($filename);
        //     if ($result) {
        //         //向数据库中记录图片信
        //         return result("上传成功", 1,$data);
        //     } else {
        //         return result("上传失败");
        //     }
        // }

        $base_dir = 'uploads/images/';
        if($request->fileurl)
        {
          $base_dir = $request->fileurl;
        }
        if(!is_dir($base_dir))//目录是否存在
        {
            mkdir($base_dir);
        }

        $base_img = str_replace('data:image/jpeg;base64,','', $request->fileUpload);
        $clientName = $base_dir.date('YmdHis').floor(microtime()).rand(1000,9999).'.jpg';
        $ifp = fopen( $clientName, "wb" );
        fwrite( $ifp, base64_decode( $base_img) );
        fclose( $ifp );

        $data['url']    = asset($clientName);//页面显示路径
        return $this->response('true',1,$data);
        

    }


    /**
     * 保存用户信息
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function Preservation(Request $request){
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->headimg,'头像');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->nickname,'昵称');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->sex,'性别');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->birthday,'生日');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->constellation,'星座');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->city,'所在城市或区域');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->introduction,'个人简介');
        if (!$uid->code) {
            return result($uid->msg);
        }

        //验证数据
        $updateData = [
            'headimg' => $request->headimg,
            'nickname' => $request->nickname,
            'sex' => $request->sex,
            'birthday' => $request->birthday,
            'constellation' => $request->constellation,
            'city' => $request->city,
            'introduction' => $request->introduction,
        ];
        $data = DB::table('s_users')->where("id",$request->uid)->update($updateData);

//        if(!empty($data)){
            return $this->response('true',1,$data,$request->apilog_id);
//        }else{
//            return $this->response('false',0);
//        }
    }


    /**
     * 实名认证
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function Identity(Request $request){
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->name,'姓名');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->identitycard,'身份证号码');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->identityimg,'照片图像');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->schoolname,'学校名称');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->education,'学历');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $uid = $this->FormCheck->isEmpty($request->profession,'专业');
        if (!$uid->code) {
            return result($uid->msg);
        }

        $user = DB::table('s_users')->where("id",$request->uid)->first();
        if($user->certificationstate==2){
            return $this->response('认证成功，请勿重复认证',0);
        }

        $info =DB::table('s_users_info')->where("uid",$request->uid)->count();
        if($info==0){
            //验证数据
            $insertData = [
                'uid' => $request->uid,
                'name' => $request->name,
                'identitycard' => $request->identitycard,
                'identityimg' => $request->identityimg,
                'schoolname' => $request->schoolname,
                'education' => $request->education,
                'profession' => $request->profession,
            ];
            $data = DB::table('s_users_info')->insertGetId($insertData);
        }else{
            $updateInfoData = [
                
                'name' => $request->name,
                'identitycard' => $request->identitycard,
                'identityimg' => $request->identityimg,
                'schoolname' => $request->schoolname,
                'education' => $request->education,
                'profession' => $request->profession,
            ];
            $data=DB::table('s_users_info')->where('uid', $request->uid)->update($updateInfoData);
        }
        
        if($data){
            //修改用户审核状态为审核中
            $updateData = [
                'certificationstate' => 3,
            ];
            DB::table('s_users')->where("id",$request->uid)->update($updateData);
            return $this->response('true',1,$data,$request->apilog_id);
        }else{
            return $this->response('false',0);
        }
    }

    /**
     * 用户关注列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function FollowList(Request $request)
    {

        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }

        $data = DB::table('s_album_follow')
            ->select('followid')
            ->where('uid', '=', $request->uid)
            ->get();
        if (!empty($data)) {
//            p($data);die;
            $array=[];
            foreach($data as $key=>$value){
                //查询被关注用户的用户名
                $userinfo = DB::table('s_users')->select('nickname','headimg')->where("id",$value->followid)->first();
                $array[$key]['nickname'] = $userinfo->nickname;
                $array[$key]['followid'] = $value->followid;
                $array[$key]['headimg'] = $userinfo->headimg;

            }
        } else {
            $array=[];
        }
            return $this->response('true', 1, $array, $request->apilog_id);

    }

    /**
     * 用户关注详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function FollowDetail(Request $request)
    {

        //判断followid是否存在
        $followid = $this->FormCheck->isEmpty($request->followid, '被关注人ID');
        if (!$followid->code) {
            return result($followid->msg);
        }

        $res = DB::table('s_album_follow as a')->join('s_users as b','b.id','=','a.followid')
            ->select('b.id','b.headimg','b.nickname','b.introduction') 
            ->where('a.followid', '=', $request->followid)
            ->first();
        if (!empty($res)) {
            $data['uid']=$res->id;
            $data['headimg']=$res->headimg;
            $data['nickname']=$res->nickname;
            $data['introduction']=$res->introduction;
            $data['albumlist']=DB::table('s_album')
            ->select('*') 
            ->where('uid', '=', $res->id)
            ->get();
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }
            

    }

    /**
     * 我的课程
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function AlbumList(Request $request)
    {

        //判断uid是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }

        $data=DB::table('s_album as a')->join('s_album_course as b', 'a.id', '=', 'b.albumid')
            ->join('s_album_course_unlock as c', 'b.id', '=', 'c.courseid')
            ->select('a.id','a.albumname','a.albumcontent','a.albumimg','a.create_time') 
            // ->where('c.uid', '=', $request->uid)
            // ->orderBy('c.create_time', 'desc')
            ->groupBy('a.id','a.albumname','a.albumcontent','a.albumimg','a.create_time','c.uid')
            ->having('c.uid', '=', $request->uid)
            ->get();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $res=DB::table('s_album_course_unlock as a')->join('s_album_course as b', 'a.courseid', '=', 'b.id')
                ->select('a.*') 
                ->where('b.albumid', '=', $value->id)
                ->where('a.uid', '=', $request->uid)
                ->count();

                $value->unlocknum=$res;
            }
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }
            

    }

    /**
     * 课程管理列表
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function AlbumManageList(Request $request)
    {

        //判断uid是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }

        $wisdombean=DB::table('s_users_wisdombeanuse')
            ->where('uid', '=', $request->uid)
            ->where(function ($query) {
                $query->where('type', '=', 4)->orwhere('type', '=', 3);
            })
            ->sum('wisdombean');
        $data['wisdombean']=$wisdombean;

        $data['albumlist']=DB::table('s_album')
            ->select('*') 
            ->where('uid', '=', $request->uid)
            ->get();
        if (!empty($data)) {
            foreach ($data['albumlist'] as $k=>$v) 
            {   
                
                $coursenum = DB::table('s_album_course')->where('isdelete',0)->where('albumid', $v->id)->count('id');
                           
                $v->coursenum=$coursenum ;

            }
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }
            

    }

    
    /**
     * 新增专辑
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function AlbumAdd(Request $request)
    {
        //判断uid是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        //判断albumimg是否存在
        $albumimg = $this->FormCheck->isEmpty($request->albumimg, '专辑封面');
        if (!$albumimg->code) {
            return result($albumimg->msg);
        }
        //判断albumname是否存在
        $albumname = $this->FormCheck->isEmpty($request->albumname, '专辑名称');
        if (!$albumname->code) {
            return result($albumname->msg);
        }
        //判断albumcontent是否存在
        $albumcontent = $this->FormCheck->isEmpty($request->albumcontent, '专辑简介');
        if (!$albumcontent->code) {
            return result($albumcontent->msg);
        }
        $data = [
            'uid' => $request->uid,
            'albumimg' => $request->albumimg,
            'albumname' => $request->albumname,
            'albumcontent' => $request->albumcontent,
        ];
        $res = DB::table('s_album')->insertGetId($data);
        
        return $this->response('true',1,$res);
        
            

    }

    /**
     * 删除专辑
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function AlbumDelete(Request $request)
    {

        //判断id是否存在
        $id = $this->FormCheck->isEmpty($request->id, '专辑ID');
        if (!$id->code) {
            return result($id->msg);
        }

        //判断是否有课程
        $course = DB::table('s_album_course')->where('isdelete',0)->where('albumid',$request->id)->count();
        if($course>0){
          return $this->response('请将专题下课程删除，然后删除专题',0);  
        }

        $res = DB::table('s_album')->where('id',$request->id)->delete();
        $coursedel=DB::table('s_album_course')->where('albumid',$request->id)->delete();
        if($res==0){
            return $this->response('false',0);
        }else{
            return $this->response('true',1,$res);
        }
        
        
            

    }


    /**
     * 编辑专辑
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function AlbumEdit(Request $request)
    {
        // 判断uid是否存在
        $id = $this->FormCheck->isEmpty($request->id, '专辑ID');
        if (!$id->code) {
            return result($id->msg);
        }
        //判断albumimg是否存在
        $albumimg = $this->FormCheck->isEmpty($request->albumimg, '专辑封面');
        if (!$albumimg->code) {
            return result($albumimg->msg);
        }
        //判断albumname是否存在
        $albumname = $this->FormCheck->isEmpty($request->albumname, '专辑名称');
        if (!$albumname->code) {
            return result($albumname->msg);
        }
        //判断albumcontent是否存在
        $albumcontent = $this->FormCheck->isEmpty($request->albumcontent, '专辑简介');
        if (!$albumcontent->code) {
            return result($albumcontent->msg);
        }
        $data = [
            'albumimg' => $request->albumimg,
            'albumname' => $request->albumname,
            'albumcontent' => $request->albumcontent,
        ];
        $res = DB::table('s_album')->where('id', $request->id)->update($data);
        
        
        return $this->response('true',1,$res);
        
            

    }

    /**
     * 个人中心用户信息
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function UserInfo(Request $request){
        //判断USER_ID是否存在
        $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        if (!$uid->code) {
            return result($uid->msg);
        }
        $data= DB::table('s_users')
            ->select('*')
            ->where('id','=',$request->uid)
            ->get();

        if(!empty($data)){
            return $this->response('true',1,$data,$request->apilog_id);
        }else{
            return $this->response('false',0);
        }

    }


     /**
     * 专辑详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function AlbumDetail(Request $request){
        //判断USER_ID是否存在
        $albumid = $this->FormCheck->isEmpty($request->albumid,'专辑id');
        if (!$albumid->code) {
            return result($albumid->msg);
        }
        // $uid = $this->FormCheck->isEmpty($request->uid,'用户');
        // if (!$uid->code) {
        //     return result($uid->msg);
        // }
        

        $res = DB::table('s_album')
            ->select('*') 
            ->where('id', '=', $request->albumid)
            ->first();
        if (!empty($res)) {
            $data['id']=$res->id;
            $data['albumname']=$res->albumname;
            $data['albumimg']=$res->albumimg;
            $data['albumcontent']=$res->albumcontent;
            $data['courselist']=DB::table('s_album_course')
            ->select('*') 
            ->where('albumid', '=', $res->id)
            ->where('isdelete',0)
            ->orderBy('id','desc')
            ->get();

            
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }

    }

    /**
     * 上传图片
     * @author lina
     * @param Request $request
     * @return json
     */
    public function ImgUpload(Request $request){
        $base_dir = 'uploads/images/';
        if($request->fileurl)
        {
          $base_dir = $request->fileurl;
        }
        if(!is_dir($base_dir))//目录是否存在
        {
            mkdir($base_dir);
        }

        if ($request->isMethod('post')) {
            $file = $request->file('picture');

          // 文件是否上传成功
            if ($file->isValid()) {
                $entension = $file -> getClientOriginalExtension();   // 扩展名
                $pic = array("png", "jpg","jpeg", "bmp", "gif","PNG", "JPG","JPEG", "BMP", "GIF");
                $filesize = $file->getSize();
                if($filesize>8388608)
                {
                    $code    = '10002';
                    $msg     = '文件大小不能超过8M！';
                    return $this->response('文件大小不能超过8M！', 0);
                }
                else
                {
                    if (in_array($entension, $pic))
                    {
                        $imgurl=$base_dir;
                        $clientName = date('YmdHis').floor(microtime()).rand(1000,9999);
                        $file -> move($imgurl,$clientName.'.'.$entension);

                        $code   = 0;
                        $msg    = '上传成功！';
                        $data    = asset($imgurl.$clientName.'.'.$entension);//页面显示路径
                        $url=$imgurl.$clientName.'.'.$entension;
                        return $this->response('true',1,$data);
                    }
                    else
                    {
                        $code   = '10001';
                        $msg    = '文件格式不正确！';
                       return $this->response('文件格式不正确！', 0);
                    }
                }
            }
            else{
                return $this->response('false', 0);
            }
        }

       
    }

    /**
     * 上传音频
     * @author lina
     * @param Request $request
     * @return json
     */
    public function VoiceUpload(Request $request){
        $base_dir = 'uploads/voice/';
        if($request->fileurl)
        {
          $base_dir = $request->fileurl;
        }
        if(!is_dir($base_dir))//目录是否存在
        {
            mkdir($base_dir);
        }

        if ($request->isMethod('post')) {
            $file = $request->file('voicefile');
          // 文件是否上传成功
            if ($file->isValid()) {

                $entension = $file -> getClientOriginalExtension();   // 扩展名
                $houzhui = array("mp3", "wma","MP3", "WMA");
                $filesize = $file->getSize();
                if($filesize>346030080)
                {
                    $code    = '10002';
                    $msg     = '文件大小不能超过330M！';
                    return $this->response('文件大小不能超过330M！', 0);
                }
                else
                {
                    // if (in_array($entension, $houzhui))
                    // {
                        $voiceurl=$base_dir;
                        $clientName = date('YmdHis').floor(microtime()).rand(1000,9999);
                        $file -> move($voiceurl,$clientName.'.'.$entension);

                        $code   = 0;
                        $msg    = '上传成功！';
                        $data    = asset($voiceurl.$clientName.'.'.$entension);//页面显示路径
                        $url=$voiceurl.$clientName.'.'.$entension;
                        return $this->response('true',1,$data);
                    // }
                    // else
                    // {
                    //     $code   = '10001';
                    //     $msg    = '文件格式不正确！';
                    //    return $this->response('文件格式不正确！', 0);
                    // }
                }
            }
            else{
                return $this->response('false', 0);
            }
        }

       
    }

    /**
     * 新增课程
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function CourseAdd(Request $request){
        //判断uid是否存在
        // $uid = $this->FormCheck->isEmpty($request->uid, '用户');
        // if (!$uid->code) {
        //     return result($uid->msg);
        // }

        //判断albumid是否存在
        $albumid = $this->FormCheck->isEmpty($request->albumid, '专辑ID');
        if (!$albumid->code) {
            return result($albumid->msg);
        }

        //判断coursevoice是否存在
        $coursevoice = $this->FormCheck->isEmpty($request->coursevoice, '音频地址');
        if (!$coursevoice->code) {
            return result($coursevoice->msg);
        }

        //判断coursetime是否存在
        $coursetime = $this->FormCheck->isEmpty($request->coursetime, '音频时长');
        if (!$coursetime->code) {
            return result($coursetime->msg);
        }


        //判断coursename是否存在
        $coursename = $this->FormCheck->isEmpty($request->coursename, '课程名称');
        if (!$coursename->code) {
            return result($coursename->msg);
        }
        //判断coursetxt是否存在
        $coursetxt = $this->FormCheck->isEmpty($request->coursetxt, '课程简介');
        if (!$coursetxt->code) {
            return result($coursetxt->msg);
        }

        //判断courseimg是否存在
        $courseimg = $this->FormCheck->isEmpty($request->courseimg, '课程封面');
        if (!$courseimg->code) {
            return result($courseimg->msg);
        }
        //判断coursecontent是否存在
        $coursecontent = $this->FormCheck->isEmpty($request->coursecontent, '课程文字');
        if (!$coursecontent->code) {
            return result($coursecontent->msg);
        }
        //判断free是否存在
        $free = $this->FormCheck->isEmpty($request->free, '是否免费');
        if (!$free->code) {
            return result($free->msg);
        }

        
        if($request->free==1){
            $wisdombean = 0;
        }else{
            //判断wisdombean是否存在
            $wisdombean = $this->FormCheck->isEmpty($request->wisdombean, '智慧豆');
            if (!$wisdombean->code) {
                return result($wisdombean->msg);
            }

            $wisdombean = $request->wisdombean;
        }
        
        $data = [
            'albumid' => $request->albumid,
            'coursename' => $request->coursename,
            'coursetxt' => $request->coursetxt,
            'courseimg' => $request->courseimg,
            'coursecontent' => $request->coursecontent,
            'coursevoice' => $request->coursevoice,
            'free' => $request->free,
            'wisdombean' => $wisdombean,
            'coursetime' => $request->coursetime,
        ];
        $res = DB::table('s_album_course')->insertGetId($data);
        
        return $this->response('true',1,$res);

    }

    /**
     * 编辑课程
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function CourseEdit(Request $request){
        // 判断uid是否存在
        $courseid = $this->FormCheck->isEmpty($request->courseid, '课程ID');
        if (!$courseid->code) {
            return result($courseid->msg);
        }

        //判断albumid是否存在
        // $albumid = $this->FormCheck->isEmpty($request->albumid, '专辑ID');
        // if (!$albumid->code) {
        //     return result($albumid->msg);
        // }

        //判断coursevoice是否存在
        $coursevoice = $this->FormCheck->isEmpty($request->coursevoice, '音频地址');
        if (!$coursevoice->code) {
            return result($coursevoice->msg);
        }

        //判断coursetime是否存在
        $coursetime = $this->FormCheck->isEmpty($request->coursetime, '音频时长');
        if (!$coursetime->code) {
            return result($coursetime->msg);
        }


        //判断coursename是否存在
        $coursename = $this->FormCheck->isEmpty($request->coursename, '课程名称');
        if (!$coursename->code) {
            return result($coursename->msg);
        }
        //判断coursetxt是否存在
        $coursetxt = $this->FormCheck->isEmpty($request->coursetxt, '课程简介');
        if (!$coursetxt->code) {
            return result($coursetxt->msg);
        }

        //判断courseimg是否存在
        $courseimg = $this->FormCheck->isEmpty($request->courseimg, '课程封面');
        if (!$courseimg->code) {
            return result($courseimg->msg);
        }
        //判断coursecontent是否存在
        $coursecontent = $this->FormCheck->isEmpty($request->coursecontent, '课程文字');
        if (!$coursecontent->code) {
            return result($coursecontent->msg);
        }
        //判断free是否存在
        $free = $this->FormCheck->isEmpty($request->free, '是否免费');
        if (!$free->code) {
            return result($free->msg);
        }

        
        if($request->free==1){
            $wisdombean = 0;
        }else{

            //判断wisdombean是否存在
            $wisdombean = $this->FormCheck->isEmpty($request->wisdombean, '智慧豆');
            if (!$wisdombean->code) {
                return result($wisdombean->msg);
            }
            
            $wisdombean = $request->wisdombean;
        }
        
        $data = [
            
            'coursename' => $request->coursename,
            'coursetxt' => $request->coursetxt,
            'courseimg' => $request->courseimg,
            'coursecontent' => $request->coursecontent,
            'coursevoice' => $request->coursevoice,
            'free' => $request->free,
            'wisdombean' => $wisdombean,
            'coursetime' => $request->coursetime,
        ];
        $res = DB::table('s_album_course')->where('id', $request->courseid)->update($data);
        
        return $this->response('true',1,$res);

    }

    /**
     * 课程详情
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function CourseDetail(Request $request){
        // 判断courseid是否存在
        $courseid = $this->FormCheck->isEmpty($request->courseid, '课程ID');
        if (!$courseid->code) {
            return result($courseid->msg);
        }

        $data = DB::table('s_album_course')
            ->select('*')->where('id', $request->courseid)->first();

        $scores =  array(array('coursescore'=>'3','sum'=>'0'),array('coursescore'=>'5','sum'=>'0'),array('coursescore'=>'7','sum'=>'0'),array('coursescore'=>'10','sum'=>'0'));

        foreach ($scores as $k=>$v) 
        {   
            
            $res = DB::table('s_album_course_score')
            ->select('coursescore',DB::raw('count(id) as total'))
            ->where('courseid','=',$request->courseid)
            
            ->where('coursescore','=',$v['coursescore'])
            ->groupBy('coursescore')
            ->first();
            
            if($res){
               $scores[$k]['sum']=$res->total; 
            }
            
            
            
        }
        $data->scores=$scores;

        //上一课程id
        $lastdata = DB::table('s_album_course')
            ->select('*')->where('albumid', $data->albumid)->where('id','<',$data->id)->orderBy('id','desc')->first();

        //下一课程id
        $nextdata = DB::table('s_album_course')
            ->select('*')->where('albumid', $data->albumid)->where('id','>',$data->id)->orderBy('id','asc')->first();

        if($lastdata){
            $data->lastid=$lastdata->id;
        }else{
            $data->lastid='';
        }
        if($nextdata){
            $data->nextid=$nextdata->id;
        }else{
            $data->nextid='';
        }
        
        
        if (!empty($data)) {
            
            return $this->response('true', 1, $data);
        } else {
            return $this->response('false', 0);
        }

    }

    /**
     * 删除课程
     * @author weishuo
     * @param Request $request
     * @return json
     */
    public function CourseDelete(Request $request)
    {

        //判断id是否存在
        $courseid = $this->FormCheck->isEmpty($request->courseid, '课程ID');
        if (!$courseid->code) {
            return result($courseid->msg);
        }

        
        $res = DB::table('s_album_course')->where('id',$request->courseid)->delete();
        if($res==0){
            return $this->response('false',0);
        }else{
            return $this->response('true',1,$res);
        }
        
        
            

    }

    /**
     * 微信JSSDK
     * @author lina
     * @param Request $request
     * @return json
     */
    public function WxConfig(Request $request)
    {

        
        $jssdk = new JSSDK('wxa4c2d2fa1bbfe161','de68d65398a44d5fbfa777f14abebd90',$request->url);
        $signPackage = $jssdk->getSignPackage();
        if ($signPackage) {
            return $this->response('true',1,$signPackage);
        }else{
            return $this->response('false',0);
        }
        
        
            

    }

    /**
     * 测试回复
     * @author lina
     * @param Request $request
     * @return json
     */
    // public function TestHuifu(Request $request)
    // {

        
    //     $userlist = DB::table('s_users')->get();
    //     foreach ($userlist as $user) {
            
    //         if($user->manvalue<30){
    //              DB::table('s_users')->where('id',$user->id)->update(['manvalue'=>($user->manvalue+1)]);
    //         }
    //     }
        
    //     return $this->response('true',1);
            

    // }

    public function uploadspic(Request $request)
    {
        
        
        $base_dir = 'uploads/images/';
        if($request->fileurl)
        {
          $base_dir = $request->fileurl;
        }
        if(!is_dir($base_dir))//目录是否存在
        {
            mkdir($base_dir);
        }

        if ($request->isMethod('post')) {
            $file = $request->file('file');

          // 文件是否上传成功
            if ($file->isValid()) {
                $entension = $file -> getClientOriginalExtension();   // 扩展名
                $pic = array("png", "jpg","jpeg", "bmp", "gif","PNG", "JPG","JPEG", "BMP", "GIF");
                $filesize = $file->getSize();
                if($filesize>8388608)
                {
                    $code    = '10002';
                    $msg     = '文件大小不能超过8M！';
                    return result('文件大小不能超过8M！', 1);
                }
                else
                {
                    if (in_array($entension, $pic))
                    {
                        $imgurl=$base_dir;
                        $clientName = date('YmdHis').floor(microtime()).rand(1000,9999);
                        $file -> move($imgurl,$clientName.'.'.$entension);

                        $code   = 0;
                        $msg    = '上传成功！';
                        $scr    = asset($imgurl.$clientName.'.'.$entension);//页面显示路径
                        $url=$imgurl.$clientName.'.'.$entension;
                        $data['src']=$scr;
                        $data['title']=$clientName;
                        
                        return result('true',0,$data);
                    }
                    else
                    {
                        $code   = '10001';
                        $msg    = '文件格式不正确！';
                       return result('文件格式不正确！',1);
                    }
                }
            }
            else{
                return result('false', 1);
            }
        }
    }
}
