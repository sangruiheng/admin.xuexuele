<?php

namespace App\Admin\Rewardcontent\Controllers;

use App\Admin\Rewardcontent\Models\Rewardcontent;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;


class RewardcontentController extends Controller
{
    public function __construct()
    {
        $this->rewardcontent = new Rewardcontent();
        $this->formCheck = new FormCheck();
    }
    /***
     * 
     * @param Request $request
     */
    public function index(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Rewardcontent.Views.index")
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
        $abnormal = new Rewardcontent();
        $result = $abnormal->getLists($request);
        return result($result->msg, $result->code, $result->data);

    }
    /**
     * 删除
     * @param Request $request
     */
    //删除
    public function delete(Request $request){
        $abnormal = new Rewardcontent();
        $result = $abnormal->deleteReward($request);
        return result($result->msg, $result->code, $result->data);

    }
    /*
    *文章详情
    */
    public function contentview(Request $request, $id)
    {
        if ($id) {
            $result = $this->rewardcontent->getDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Rewardcontent.Views.contentdetail")
                ->with("result", $result)
                ->with("thisAction", "/rewardcontent")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    public function updateContent(Request $request)
    {
        $this->actionLog("更新文章奖励");
        $formCheck = new FormCheck();
        $HeadingTitle = $formCheck->isEmpty($request->heading,"内容名称");
        if(!$HeadingTitle->code){
            return result($HeadingTitle->msg);
        }
        $Image = $this->formCheck->isEmpty($request->img,"封面图片");
        if(!$Image->code){
            return result($Image->msg);
        }
        $Article = $this->formCheck->isEmpty($request->article,"奖励内容");
        if(!$Article->code){
            return result($Article->msg);
        }
        $result = $this->rewardcontent->updateContent($request);
        return result($result->msg, $result->code);
    }
    /***
     * 音频
     * @param Request $request
     */
    public function voiceview(Request $request, $id)
    {
        if ($id) {
            $result = $this->rewardcontent->getDetail($id);
            $menuInfo = getMenuFromPath($request->path());
            return view("Admin.Rewardcontent.Views.voicedetail")
                ->with("result", $result)
                ->with("thisAction", "/rewardcontent")
                ->with("title", $menuInfo->title);
        } else {
            return result('参数错误！');
        }
    }
    public function updateVoice(Request $request)
    {
        $this->actionLog("更新音频奖励");
        $formCheck = new FormCheck();
        $HeadingTitle = $formCheck->isEmpty($request->heading,"内容名称");
        if(!$HeadingTitle->code){
            return result($HeadingTitle->msg);
        }
        $Image = $this->formCheck->isEmpty($request->img,"封面图片");
        if(!$Image->code){
            return result($Image->msg);
        }
        $Voice = $this->formCheck->isEmpty($request->voice,"封面图片");
        if(!$Voice->code){
            return result($Voice->msg);
        }
        $result = $this->rewardcontent->updateVoice($request);
        return result($result->msg, $result->code);
    }
    //新增文章
    public function addcontentview(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Rewardcontent.Views.addcontent")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }
    public function addcontent(Request $request){
        $formCheck = new FormCheck();
        $HeadingTitle = $formCheck->isEmpty($request->heading,"内容名称");
        if(!$HeadingTitle->code){
            return result($HeadingTitle->msg);
        }
        $Image = $this->formCheck->isEmpty($request->img,"封面图片");
        if(!$Image->code){
            return result($Image->msg);
        }
        $Article = $this->formCheck->isEmpty($request->article,"奖励内容");
        if(!$Article->code){
            return result($Article->msg);
        }

        $this->actionLog("新增文章");
        $result = $this->rewardcontent->addcontent($request);
        return result($result->msg, $result->code);
    }
    //新增音频
    public function addvoiceview(Request $request)
    {
        $menuInfo = getMenuFromPath($request->path());
        return view("Admin.Rewardcontent.Views.addvoice")
            ->with("thisAction", $menuInfo->url)
            ->with("title", $menuInfo->title);
    }
    public function addvoice(Request $request){
        $formCheck = new FormCheck();
        $HeadingTitle = $formCheck->isEmpty($request->heading,"内容名称");
        if(!$HeadingTitle->code){
            return result($HeadingTitle->msg);
        }
        $Image = $this->formCheck->isEmpty($request->img,"封面图片");
        if(!$Image->code){
            return result($Image->msg);
        }
        $Voice = $this->formCheck->isEmpty($request->voice,"封面图片");
        if(!$Voice->code){
            return result($Voice->msg);
        }
        $this->actionLog("新增音频");
        $result = $this->rewardcontent->addvoice($request);
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

    
}
