<?php

namespace App\Admin\System\Controllers;

use App\Admin\Controller;
use App\Admin\System\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function base(Request $request){
        $this->actionLog("查看系统设置");
        $menuInfo = getMenuFromPath($request->path());
        $adminInfo = Session::get("adminInfo");
        $setting = new Setting();
        //获取设置信息
        $settingLists = $setting->getSettingLists();
        if(!count($settingLists)){
            $settingLists = $setting->getDefaultSettingLists();
            foreach ($settingLists as $data){
                $data->value = "";
            }
        }
        //p($settingLists);
        //获取站点LOGO信息
        $logoUrl = $setting->getWebLogo();
        $qrcodeUrl = $setting->getWebQrcode();
        return view("System.Views.baseSetting")
            ->with("roleid",$adminInfo->role_id)
            ->with("thisAction",$menuInfo->url)
            ->with("settingLists",$settingLists)
            ->with("qrcode",$qrcodeUrl)
            ->with("logo",$logoUrl)
            ->with("title",$menuInfo->title);
    }
    //保存系统基本设置
    public function saveBase(Request $request){
        $this->actionLog("更新系统设置");
        $setting = new Setting();
        $result = $setting->saveBase($request);
        return result($result->msg,$result->code);
    }

    public function baseSetRegion(Request $request){
        $regionId = $request->region_id;
        Session::put("adminRegionId",$regionId);
        return result("切换成功",1);
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
            $resize_image_file = "web/".date("Y-m")."/300x300x{$image_name}";
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