<?php

namespace App\Admin\Gate\Controllers;

use App\Admin\Gate\Models\Gate;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;
use Illuminate\Support\Facades\DB;


class GateController extends Controller
{
    public function __construct()
    {
        $this->gate = new Gate();
        $this->formCheck = new FormCheck();
    }
    /***
     * 
     * @param Request $request
     */
    public function index(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Gate.Views.index")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    /**
     * 
     * 获取列表信息列表
     * @param Request $request
     */
    //数据获取列表
    public function getLists(Request $request){
        $abnormal = new Gate();
        $result = $abnormal->getLists($request);
        return result($result->msg, $result->code, $result->data);

    }

    //查看详情视图
    public function gateview(Request $request, $id)
    {
        if ($id) {
            $result = $this->gate->getDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            $alertList = $this->gate->alertList();
//            dump($result);
            return view("Admin.Gate.Views.gatedetails")
                ->with("result", $result)
                ->with("thisAction", "/gate")
                ->with("title", $menuInfo->title)
                ->with('alertList', $alertList);
        } else {
            return result('参数错误！');
        }
    }
    //更新
    public function update(Request $request)
    {
        $formCheck = new FormCheck();
        
        $result = $this->gate->updateGate($request);
        $this->actionLog("更新关卡");
        return result($result->msg, $result->code, $result->data);
    }

    //添加
    public function addgate(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        $result = $this->gate->newgate($request);
        $newname = $result->data+1;
        $alertList = $this->gate->alertList();
        return view("Admin.Gate.Views.addgate")
            ->with("thisAction", "/gate")
            ->with("title", $menuInfo->title)
            ->with("newname", $newname)
            ->with('alertList', $alertList);
    }

    public function add(Request $request)
    {
        $formCheck = new FormCheck();
        
        $result = $this->gate->addGate($request);
        $this->actionLog("新增关卡");
        return result($result->msg, $result->code);
    }


    //查看关卡中是否有题目
    public function isCreateSubject(){


        $gate = DB::table('s_gate')
            ->select('*')
//            ->join('s_gate_subject', 's_gate.id', '=', 's_gate_subject.gate_id')
            ->orderBy('s_gate.id', 'DESC')
            ->first();
        $subject = DB::table('s_gate_subject')
            ->select('*')
            ->where('gate_id',$gate->id)
            ->get()->toArray();


        $data = DB::table('s_gate')
            ->select('*')
            ->get();
        foreach($data as $value){
            $subject_sum = DB::table('s_gate_subject')
                ->select('*')
                ->where('gate_id',$value->id)
                ->count();
            if($subject_sum==0){
                return result('前面关卡有未添加题目,请先添加题目！', 400,$subject);
            }
        }
        if(!$subject){
            return result('前面关卡有未添加题目,请先添加题目！', 400,$subject);
        }
        return result('OK', 200,$subject);
    }

    public function courselist(Request $request)
    {
        
        return view("Admin.Gate.Views.courselist");
    }

    public function getcourselist(Request $request)
    {
        
        
        $result = $this->gate->courselist($request);
        
        return result($result->msg, $result->code,$result->data);
    }

    public function rewordlist(Request $request)
    {
        
        return view("Admin.Gate.Views.rewordlist");
    }

    public function getrewordlist(Request $request)
    {
        
        
        $result = $this->gate->rewordlist($request);
        
        return result($result->msg, $result->code,$result->data);
    }


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
                    return result('文件大小不能超过8M！', 0);
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
                        return result('true',1,$data);
                    }
                    else
                    {
                        $code   = '10001';
                        $msg    = '文件格式不正确！';
                       return result('文件格式不正确！', 0);
                    }
                }
            }
            else{
                return result('false', 0);
            }
        }
    }

    public function uploadsvideo(Request $request)
    {
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
                // $houzhui = array("mp3", "wma","MP3", "WMA");
                $filesize = $file->getSize();
                if($filesize>346030080)
                {
                    $code    = '10002';
                    $msg     = '文件大小不能超过330M！';
                    return result('文件大小不能超过330M！', 0);
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
                        return result('true',1,$data);
                    // }
                    // else
                    // {
                    //     $code   = '10001';
                    //     $msg    = '文件格式不正确！';
                    //    return result('文件格式不正确！', 0);
                    // }
                }
            }
            else{
                return result('false', 0);
            }
        }
    }
}
