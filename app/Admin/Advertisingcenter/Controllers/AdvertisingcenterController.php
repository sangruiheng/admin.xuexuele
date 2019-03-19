<?php

namespace App\Admin\Advertisingcenter\Controllers;

use App\Admin\Advertisingcenter\Models\Advertisingcenter;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class AdvertisingcenterController extends Controller
{
    public function __construct()
    {
        $this->advertising = new Advertisingcenter();
        $this->formCheck = new FormCheck();
    }
    /***
     * 消息通知
     * @param Request $request
     */
    public function notice(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Advertisingcenter.Views.notice")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }

    /**
     * 消息通知
     * 获取列表信息列表
     * @param Request $request
     */
    //数据获取列表
    public function getNoticeLists(Request $request){
        $abnormal = new Advertisingcenter();
        $result = $abnormal->getNoticeLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    
    public function noticeview(Request $request, $id)
    {
        if ($id) {
            $result = $this->advertising->getNoticeDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Advertisingcenter.Views.noticedetail")
                ->with("result", $result)
                ->with("thisAction", "/notice")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    //添加消息
    public function noticeaddview(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Advertisingcenter.Views.addnotice")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }
    public function addnotice(Request $request){
        $formCheck = new FormCheck();
        $HeadingTitle = $formCheck->isEmpty($request->heading,"消息标题");
        if(!$HeadingTitle->code){
            return result($HeadingTitle->msg);
        }
        $Content = $formCheck->isEmpty($request->content,"消息内容");
        if(!$Content->code){
            return result($Content->msg);
        }
        $this->actionLog("消息发布");
        $result = $this->advertising->addnotice($request);
        return result($result->msg, $result->code);
    }
    /***
     * 广告管理
     * @param Request $request
     */
    public function advertisement(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Advertisingcenter.Views.advertisement")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }
    /**
     * 广告管理
     * 获取列表信息列表
     * @param Request $request
     */
    //数据获取列表
    public function getAdvertisementLists(Request $request){
        $abnormal = new Advertisingcenter();
        $result = $abnormal->getAdvertisementLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    public function advertisementview(Request $request, $id)
    {
        if ($id) {
            $result = $this->advertising->getAdvertisementDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Advertisingcenter.Views.advertisementdetail")
                ->with("result", $result)
                ->with("thisAction", "/advertisement")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    public function upStatus(Request $request)
    {
        $this->actionLog("更新广告信息");
        $formCheck = new FormCheck();
        $HeadingTitle = $formCheck->isEmpty($request->heading,"广告标题");
        if(!$HeadingTitle->code){
            return result($HeadingTitle->msg);
        }
        $Image = $this->formCheck->isEmpty($request->image,"广告封面");
        if(!$Image->code){
            return result($Image->msg);
        }
        $result = $this->advertising->AdvertisementStatus($request);
        return result($result->msg, $result->code);
    }
    public function upload(Request $request){
        $base64 = $request->imgpath;
        $base64_image = str_replace(' ', '+', $base64);
        $type = $request->type ? $request->type : 1;
        //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
            //匹配成功
            if($result[2] == 'jpeg'){
                $image_name = time().uniqid().'.jpg';
                //纯粹是看jpeg不爽才替换的
            }else{
                $image_name = time().uniqid().'.'.$result[2];
            }

            $image_file = "web/".date("Y-m")."/{$image_name}";
            $resize_image_file = "web/".date("Y-m")."/200x200x{$image_name}";
            //服务器文件存储路径
            $imgPath = Storage::disk('local')->put($image_file, base64_decode(str_replace($result[1], '', $base64_image)));
            $img = Image::make('storage/'.$image_file);
            $img->resize(300, null, function($constraint){       // 调整图像的宽到300，并约束宽高比(高自动)
                $constraint->aspectRatio();
            });
            if($img->save('storage/'.$resize_image_file)){
                if($type==1){
                    Setting::where("key","=","web_logo")->update(["value"=>$resize_image_file]);
                }elseif($type==2){
                    Setting::where("key","=","web_qrcode")->update(["value"=>$resize_image_file]);
                }
                $data['logo'] = $resize_image_file;
                return result("上传成功！",1,$data);
            }else{
                $data['logo'] = $image_file;
                return result("上传成功！",1,$data);
            }
        }else{
            return result("上传失败！");
        }
    }

    public function advertisementaddview(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Advertisingcenter.Views.addadvertisement")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }
    public function addadvertisement(Request $request){
        $formCheck = new FormCheck();
        $HeadingTitle = $formCheck->isEmpty($request->heading,"广告标题");
        if(!$HeadingTitle->code){
            return result($HeadingTitle->msg);
        }
        $Image = $this->formCheck->isEmpty($request->image,"广告封面");
        if(!$Image->code){
            return result($Image->msg);
        }
        $this->actionLog("发布广告");
        $result = $this->advertising->addadvertisement($request);
        return result($result->msg, $result->code);
    }
}
