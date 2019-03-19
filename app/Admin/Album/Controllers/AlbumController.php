<?php

namespace App\Admin\Album\Controllers;

use App\Admin\Album\Models\Album;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class AlbumController extends Controller
{
    public function __construct()
    {
        $this->album = new Album();
        $this->formCheck = new FormCheck();
    }
    //查看内容详情视图
    public function albumview(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Album.Views.albumindex")
                ->with("thisAction", $menuInfo->url)
                ->with("title", $menuInfo->title);
    }

    //查看内容详情视图
    public function albumviewdetail(Request $request, $id)
    {
        if ($id) {
            $result = $this->album->getAlbumDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Album.Views.albumviewdetail")
                    ->with("result", $result)
                    ->with("thisAction", "/album")
                    ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    //查看课程内容详情视图
    public function albumcourseviewdetail(Request $request, $id)
    {
        if ($id) {
            $result = $this->album->getAlbumCourseDetail($id);
            $score = $this->album->ScoreList($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Album.Views.albumcourseviewdetail")
                    ->with("result", $result)
                    ->with("id", $id)
                    ->with("score", $score)
                    ->with("thisAction", "/album")
                    ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }

    //查看课程列表视图
    public function albumcourseindex(Request $request, $id)
    {
        if ($id) {
//            $result = $this->user->getalbumDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Album.Views.albumcourseindex")
                   ->with("id", $id)
                    ->with("thisAction", "/album")
                    ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }


    /**
     * 获取列表信息
     * @param Request $request
     */
    //数据获取列表
    public function getalbumLists(Request $request){
        $abnormal = new Album();
        $result = $abnormal->getalbumtLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    /**
     * 获取课程列表信息
     * @param Request $request
     */
    //数据获取列表
    public function getalbumcourseLists(Request $request, $id){
        $abnormal = new Album();
        $result = $abnormal->getalbumtcourseLists($request, $id);
        return result($result->msg, $result->code, $result->data);

    }
    /**
     * 获取课程评论列表信息
     * @param Request $request
     */
    //数据获取列表
    public function getalbumcoursecommentLists(Request $request, $id){
        $abnormal = new Album();
        $result = $abnormal->getalbumtcoursecommentLists($request, $id);
        return result($result->msg, $result->code, $result->data);

    }

    /**
     * 删除专辑
     * @param Request $request
     */
    //删除专辑
    public function delete(Request $request){
        $abnormal = new Album();
        $result = $abnormal->deleteAlbum($request);
        return result($result->msg, $result->code, $result->data);

    }

    /**
     * 删除课程
     * @param Request $request
     */
    public function coursedelete(Request $request){
        $abnormal = new Album();
        $result = $abnormal->deleteAlbumCourse($request);
        return result($result->msg, $result->code, $result->data);

    }

    /**
     * 删除评论
     * @param Request $request
     */
    public function coursecommentdelete(Request $request){
        $abnormal = new Album();
        $result = $abnormal->deleteAlbumCourseComment($request);
        return result($result->msg, $result->code, $result->data);

    }

    //更新专辑
    public function update(Request $request)
    {
        $formCheck = new FormCheck();
        
        $result = $this->album->updateAlbum($request);
        $this->actionLog("更新专辑");
        return result($result->msg, $result->code, $result->data);
    }

    //添加专辑
    public function addalbum(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Album.Views.addalbum")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }
    public function add(Request $request)
    {
        $formCheck = new FormCheck();
        
        $result = $this->album->addAlbum($request);
        $this->actionLog("添加专辑");
        return result($result->msg, $result->code);
    }
    //添加课程
    public function addalbumcourse(Request $request,$id)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Album.Views.addalbumcourse")
            ->with("id", $id)
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }
    public function addcourse(Request $request)
    {
        $formCheck = new FormCheck();
        
        $result = $this->album->addCourse($request);
        $this->actionLog("添加课程");
        return result($result->msg, $result->code);
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
                        
                        $size = getimagesize($scr); 
                        $width = $size[0]; 
                        if($width <330){ 
                            return result('图片宽度必须大于330px',1);
                        }
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
