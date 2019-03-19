<?php

namespace App\Admin\User\Controllers;

use App\Admin\User\Models\User;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class UserController extends Controller
{
    public function __construct()
    {
        $this->user = new User();
        $this->formCheck = new FormCheck();
    }
    /***
     * 视图
     * @param Request $request
     */
    public function index(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.User.Views.index")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    /**
     * 获取列表信息列表
     * @param Request $request
     */
    //数据获取列表
    public function getLists(Request $request){
        $abnormal = new User();
        $result = $abnormal->getLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    //更新用户账号状态
    public function userStatus(Request $request)
    {
//        if (!actionIsView("stop_user")) {
//            return redirect(adminurl("/unauthorized"));
//        }
        $result = $this->user->userStatus($request);
        $this->actionLog("更新用户账号状态");
        return result($result->msg, $result->code, $result->data);
    }

    //查看用户详情视图
    public function view(Request $request, $id)
    {
        if ($id) {
            $result = $this->user->getDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.User.Views.detail")
                ->with("result", $result)
                ->with("thisAction", "/users")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }

    //查看内容详情视图
    public function albumview(Request $request, $id)
    {
        if ($id) {

            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.User.Views.albumindex")
                    ->with("id", $id)
                    ->with("thisAction", "/users")
                    ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }

    //查看内容详情视图
    public function albumviewdetail(Request $request, $id)
    {
        if ($id) {
            $result = $this->user->getAlbumDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.User.Views.albumviewdetail")
                    ->with("result", $result)
                    ->with("thisAction", "/users")
                    ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    //查看课程内容详情视图
    public function albumcourseviewdetail(Request $request, $id)
    {
        if ($id) {
            $result = $this->user->getAlbumCourseDetail($id);
            $score = $this->user->ScoreList($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.User.Views.albumcourseviewdetail")
                    ->with("result", $result)
                    ->with("id", $id)
                    ->with("score", $score)
                    ->with("thisAction", "/users")
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
            return view("Admin.User.Views.albumcourseindex")
                   ->with("id", $id)
                    ->with("thisAction", "/users")
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
    public function getalbumLists(Request $request, $id){
        $abnormal = new User();
        $result = $abnormal->getalbumtLists($request, $id);
        return result($result->msg, $result->code, $result->data);

    }
    /**
     * 获取课程列表信息
     * @param Request $request
     */
    //数据获取列表
    public function getalbumcourseLists(Request $request, $id){
        $abnormal = new User();
        $result = $abnormal->getalbumtcourseLists($request, $id);
        return result($result->msg, $result->code, $result->data);

    }
    /**
     * 获取课程评论列表信息
     * @param Request $request
     */
    //数据获取列表
    public function getalbumcoursecommentLists(Request $request, $id){
        $abnormal = new User();
        $result = $abnormal->getalbumtcoursecommentLists($request, $id);
        return result($result->msg, $result->code, $result->data);

    }

    /**
     * 删除专辑
     * @param Request $request
     */
    //删除专辑
    public function delete(Request $request){
        $abnormal = new User();
        $result = $abnormal->deleteAlbum($request);
        return result($result->msg, $result->code, $result->data);

    }

    /**
     * 删除课程
     * @param Request $request
     */
    public function coursedelete(Request $request){
        $abnormal = new User();
        $result = $abnormal->deleteAlbumCourse($request);
        return result($result->msg, $result->code, $result->data);

    }

    /**
     * 删除评论
     * @param Request $request
     */
    public function coursecommentdelete(Request $request){
        $abnormal = new User();
        $result = $abnormal->deleteAlbumCourseComment($request);
        return result($result->msg, $result->code, $result->data);

    }

    //调取报警信息
//    public function call(Request $request){
//        $abnormal = new User();
//        $res = $abnormal->call();
//        if($res > 0){
//            return result('程序触发报错警报，请及时查看',1);
//        }else{
//            return result('正常');
//        }
//    }


}
