<?php
/*
 * @name 图片上传操作
 * @auth tzchao
 * @time 2018-03-06
 * 依赖扩展 intervention/image
 */
namespace App\Classlib;

use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FileUpload
{

    /*
     * 图片上传的两种方式
     * 1、传统方式，通过表单进行input上传
     * 2、base64位编码方式上传
     */
    protected $imageUploadType = array(
        1 => "imageFileUpload",
        2 => "imageBase64Upload"
    );

    //默认裁剪尺寸
    protected $defaultCutWidth = 300;
    protected $defaultCutHeight = 300;

    /*
     *允许上传的图片类型
     * 不在此类型中的图片格式禁止上传
     */
    protected $allowImgType = array('jpg','jpeg','png','gif');

    /*
     * 生成保存图片信息
     * @param  string 生成图片的格式，默认为jpg
     * @return object
     * @return object param savePath 图片存储路径
     * @return object param imageName 图片存储名称
     */
    protected function imageSaveInfo($exitName="jpg"){
        $data = (object)array();
        $data->savePath = "uploads/".date("Y/m");
        $data->imageName = uniqid().".".$exitName;
        return $data;
    }

    /*
     * 图片上传入口
     * @param file  要上传的文件资源
     * @param number  type 文件上传类型，默认为base64 1，form上传，2、base64位上传
     * @param number  $isCut 图片是否裁剪 1、是，2、否
     * @param array  $cutSize 图片裁剪尺寸
     */
    public function imageUpload($file,$type = 2,$isCut = 1,array $cutSize = array()){
        $requestData = (object)array();
        $requestData->uploadType  = $type ? $type : 2; //文件上传类型1input上传，2、base64位上传,默认为2
        $requestData->isCut       = $isCut ? $isCut : 0; //是否裁剪，1裁剪，0不裁剪
        $requestData->cutSize     = arrayToObject($cutSize ? $cutSize : $this->cutSize);
        $requestData->image   = $file;
        //数据验证
        if(!isset($this->imageUploadType[$requestData->uploadType])){
            return returnData("上传方式不正确");
        }
        //执行上传操作
        if($requestData->uploadType==1){
            $result = $this->imageFileUpload($requestData);
        }else{
            $result = $this->imageBase64Upload($requestData);
        }

        return returnData($result->msg,$result->code,$result->data);
    }

    /*
     * 传统方式上传图片
     */
    protected function imageFileUpload(Request $request){
        $imgFile = $request->file("image");
        if ($imgFile->isValid()) {
            $imgExtName = $imgFile->getClientOriginalExtension();     // 扩展名
            $tempPath = $imgFile->getRealPath();   //临时文件的绝对路径
            //验证图片格式是否合法
            if(!in_array($imgExtName,$this->allowImgType)){
                return returnData("图片格式不合法");
            }
            //获取图片存储信息
            $imageSaveInfo = $this->imageSaveInfo($imgExtName);
            $originalUrl = $imageSaveInfo->path.$imageSaveInfo->imageName;
            // 使用我们新建的uploads本地存储空间（目录）
            $result = Storage::disk('local')->put($originalUrl, file_get_contents($tempPath));
            if($result){
                //判断是否有裁剪操作
                if($request->isCut){
                    $imgResources = Image::make($originalUrl);
                    $imgResources->resize($request->width, $request->height, function($constraint){
                        $constraint->aspectRatio();
                    });
                    //生成缩略图路径
                    $thumbnailUrl = $originalUrl.$request->width."X".$request->height.".".$imgExtName;
                    $imgResources->save($thumbnailUrl);
                    $data['source_src'] = $originalUrl;
                    $data['thumb_src']    = $thumbnailUrl;
                }else{
                    $data['source_src'] = $originalUrl;
                }
                return returnData("图片上传成功！",1,$data);
            }else{
                return returnData("图片上传失败");
            }
        }else{
            return returnData("上传文件不存在");
        }
    }


    //base64位上传
    protected function imageBase64Upload($request){
        $imgBase64 = $request->image;
        if($imgBase64){
            $base64Image = str_replace(' ', '+', $imgBase64);
            //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64Image, $result)){
                //进行数据验证
                if(!in_array($result[2],$this->allowImgType)){
                    return returnData("图片格式不合法");
                }
                //生成图片名称
                $imageUrl = "uploads/".date("Y/m")."/".date('YmdHis').uniqid() . '.' . $result[2];
                Storage::disk('local')->put($imageUrl, base64_decode(str_replace($result[1], '', $imgBase64)));
                $data['source'] = $imageUrl;
                //判断是否需要裁剪
                if($request->isCut){
                    $img = Image::make($imageUrl);
                    //获取裁剪尺寸
                    if(count($request->cutSize)){
                        foreach ($request->cutSize as $key=>$value){
                            $thumbnailUrl = $imageUrl."$value->imageWidth"."x".$value->imageHeight.".".$result[2];
                            $img->resize($value->imageWidth, $value->imageHeight, function($constraint){       // 调整图像的宽到300，并约束宽高比(高自动)
                                $constraint->aspectRatio();
                            });
                            if($img->save($thumbnailUrl)){
                                $data['thumb'][$key] = $thumbnailUrl;
                            }
                        }
                    }else{
                        $thumbnailUrl = $imageUrl.$this->defaultCutWidth."x".$this->defaultCutHeight.".".$result[2];
                        $img = Image::make($imageUrl);
                        $img->resize($this->defaultCutWidth, $this->defaultCutHeight, function($constraint){       // 调整图像的宽到300，并约束宽高比(高自动)
                            $constraint->aspectRatio();
                        });
                        if($img->save($thumbnailUrl)){
                            $data['thumb'] = $thumbnailUrl;
                        }
                    }
                }
                return returnData("图片上传成功",1,$data);
            }else{
                return returnData("图片上传失败！");
            }
        }else{
            return returnData("图片不存在");
        }
    }

    //视频上传
    public function uploadVideo(Request $request){
        $file = $request->file("file");
        if ($file->isValid()) {
            // 获取文件相关信息
            $originalName = $file->getClientOriginalName(); // 文件原名
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $type = $file->getClientMimeType();     // image/jpeg
            // 上传文件
            $filename = "videos/".date("Y/m")."/".date('YmdHis').uniqid() . '.' . $ext;
            // 使用我们新建的uploads本地存储空间（目录）
            $bool = Storage::disk('local')->put($filename, file_get_contents($realPath));
            if($bool){
                $data['url'] = $filename;
                return result("视频上传成功！",1,$data);
            }else{
                $data['url'] = $filename;
                return result("视频上传失败！");
            }
        }
    }
}