<?php

/******************************************************
 * @CopyRight 2014-2018 易创互联
 * @website www.huimor.com
 * @auth tzchao
 * @datetime 2018-04-17
 * @name 插件视图生成控制器
 *****************************************************/

namespace App\Admin\Plug\Controllers;

use App\Admin\Menu\Models\Menu;
use App\Admin\Plug\Models\Install;
use App\Admin\Plug\Models\Plug;
use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;
use Illuminate\Support\Facades\DB;

class PlugController extends Controller
{
    protected $installType = array(
        1 => '后台模块',
        2 => 'API模块',
        3 => '类库安装'
    );

    public function __construct(){
        $this->formCheck = new FormCheck();
        $this->plug      = new Plug();
        //指定安装路径
        $this->installAdminPath = app_path('/Admin');
        $this->installApiPath = app_path('/Api');
        $this->resourcePath = public_path('resource');
    }

    public function install(Request $request){
        $plugId = $request->plug_id;
        $downUrl = $request->down_url;
        $plugKey = $request->plug_key;
        $plugType = $request->plug_type;
        $menuLabel = $request->menu_label;
        $checkDownUrl = $this->formCheck->isEmpty($downUrl,lang('Plug','plug_down_url'));
        if(!$checkDownUrl->code){
            return result($checkDownUrl->msg);
        }
        $checkPlugKey = $this->formCheck->isEmpty($plugKey,lang('Plug','parameter_error'));
        if(!$checkPlugKey->code){
            return result($checkPlugKey->msg);
        }
        if($plugType == 1){
            //检测安装环境
            $res = $this->testingEnvironment($downUrl,$plugKey,$plugId,$plugType,$menuLabel);
            if(!$res->code){
                return result($res->msg);
            }
            $result = $this->adminPlugInstall($downUrl,$plugKey,$plugId);
        }elseif($plugType == 2){
            $result = $this->apiPlugInstall($downUrl,$plugKey);
        }elseif($plugType == 3){
            $result = $this->classPlugInstall($downUrl,$plugKey);
        }else{
            return result(lang('Plug','install_type_error'));
        }
        return result($result->msg,$result->code,$result->data);
    }

    //获取已安装插件列表
    public function installedLists(Request $request){
        $dataContent = $this->plug->installedLists($request);
        if (count($dataContent['lists'])) {
            foreach ($dataContent['lists'] as $data) {
                $data->type_name = $this->installType[$data->type];
                //获取用户等级
                //$data->status_name = $this->status[$data->status];
            }
        } else {
            $dataContent['lists'] = array();
        }
        return result("请求成功",1,$dataContent);
    }

    protected function adminPlugInstall($downUrl,$plugKey,$plugId){
        //下载安装文件到本地
        $tmpFile = getFile($downUrl,$this->installAdminPath);
        if(!$tmpFile){
            return returnData(lang('Plug','install_file_down_fail'));
        }else{
            //文件解压缩
            $zip = new \ZipArchive();
            if ($zip->open($this->installAdminPath.'/'.$tmpFile) === TRUE) {
                $zip->extractTo($this->installAdminPath);//假设解压缩到在当前路径下images文件夹的子文件夹php
                $zip->close();//关闭处理的zip文件
                //删除临时压缩文件
                if (!unlink($this->installAdminPath.'/'.$tmpFile)){
                    return returnData(lang('Plug','tmp_file_delete_fail'));
                }else{
                    //文件资源迁移
                    if(is_dir($this->installAdminPath.'/'.ucfirst($plugKey).'/resource')){
                        $res = copyDir($this->installAdminPath.'/'.ucfirst($plugKey).'/resource',public_path('/resource'));
                        if($res){ //静态资源迁移成功，删除目录
                            delDir($this->installAdminPath.'/'.ucfirst($plugKey).'/resource');
                        }
                    }
                    //迁移数据库资源
                    $databaseDir = $this->installAdminPath.'/'.ucfirst($plugKey).'/databases';
                    $handle = opendir($databaseDir);
                    if($handle){
                        while(($fl = readdir($handle)) !== false){
                            $temp = $databaseDir.DIRECTORY_SEPARATOR.$fl;
                            //如果不加  $fl!='.' && $fl != '..'  则会造成把$dir的父级目录也读取出来
                            if(is_dir($temp) && $fl!='.' && $fl != '..'){
                                read_all($temp);
                            }else{
                                if($fl!='.' && $fl != '..'){
                                    $sql = file_get_contents($temp);
                                    $sqlArr = explode(';', $sql);
                                    $mysqli = new \mysqli(env('DB_HOST'),env('DB_USERNAME'),env('DB_PASSWORD'),env('DB_DATABASE'));
                                    foreach ($sqlArr as $value) {
                                        $mysqli->query($value);
                                    }
                                    $mysqli->close();
                                }
                            }
                        }
                    }
                    //删除数据库文件
                    delDir($databaseDir);
                    $install = new Install();
                    $menuSetting = include($this->installAdminPath.'/'.ucfirst($plugKey).'/install.php');
                    if(is_array($menuSetting)){
                        foreach ($menuSetting as $data){
                            $install->menu($data['menu'],$data['menuPermission']);
                        }
                    }
                    //安装完成后，将安装信息写入数据库
                    $plugUrl = 'http://plug.huimor.com/api/plugs/info/'.$plugId;
                    $plugInfo = json_decode(curlHttpsGet($plugUrl));
                    DB::table('plug_installs')->insertGetId(objectToArray($plugInfo->data));
                    return returnData(lang('Plug','install_success'),1);
                }
            }else{
                return returnData(lang('Plug','zip_file_not_exist'));
            }
        }
    }

    protected function apiPlugInstall($downUrl,$plugKey,$plugId){
        //下载安装文件到本地
        $tmpFile = getFile($downUrl,$this->installApiPath);
        if(!$tmpFile){
            return returnData(lang('Plug','install_file_down_fail'));
        }else{
            //文件解压缩
            $zip = new \ZipArchive();
            if ($zip->open($this->installApiPath.'/'.$tmpFile) === TRUE) {
                $zip->extractTo($this->installApiPath);//假设解压缩到在当前路径下images文件夹的子文件夹php
                $zip->close();//关闭处理的zip文件
                //删除临时压缩文件
                if (!unlink($this->installApiPath.'/'.$tmpFile)){
                    return returnData(lang('Plug','tmp_file_delete_fail'));
                }else{
                    //迁移数据库资源
                    $databaseDir = $this->installApiPath.'/'.ucfirst($plugKey).'/databases';
                    $handle = opendir($databaseDir);
                    if($handle){
                        while(($fl = readdir($handle)) !== false){
                            $temp = $databaseDir.DIRECTORY_SEPARATOR.$fl;
                            //如果不加  $fl!='.' && $fl != '..'  则会造成把$dir的父级目录也读取出来
                            if(is_dir($temp) && $fl!='.' && $fl != '..'){
                                read_all($temp);
                            }else{
                                if($fl!='.' && $fl != '..'){
                                    $sql = file_get_contents($temp);
                                    $sqlArr = explode(';', $sql);
                                    $mysqli = new \mysqli(env('DB_HOST'),env('DB_USERNAME'),env('DB_PASSWORD'),env('DB_DATABASE'));
                                    foreach ($sqlArr as $value) {
                                        $mysqli->query($value);
                                    }
                                    $mysqli->close();
                                }
                            }
                        }
                    }
                    //删除数据库文件
                    delDir($databaseDir);
                    //安装完成后，将安装信息写入数据库
                    $plugUrl = 'http://plug.huimor.com/api/plugs/info/'.$plugId;
                    $plugInfo = json_decode(curlHttpsGet($plugUrl));
                    DB::table('plug_installs')->insertGetId(objectToArray($plugInfo->data));
                    return returnData(lang('Plug','install_success'),1);
                }
            }else{
                return returnData(lang('Plug','zip_file_not_exist'));
            }
        }
    }

    //检测安装环境
    protected function testingEnvironment($downUrl,$plugKey,$plugId,$plugType,$menuLabel){
        if($plugType == 1){
            //判断模块安装目录是否已存在
            if(is_dir($this->installAdminPath.'/'.ucfirst($plugKey))){
                return returnData(lang('Plug','plug_install_dir_exist'));
            }
            //检测安装文件是否存在
            if(!fileExists($downUrl)){
                return returnData(lang('Plug','plug_file_not_exist'));
            }
            //检测资源文件是否存在
            if(is_dir(public_path('/resource/js/'.strtolower($plugKey)))){
                return returnData(lang('Plug','plug_resource_dir_js_exist'));
            }
            //检测数据库信息
            $res = Menu::where('menu_label',$menuLabel)->count();
            if($res){
                return returnData(lang('Plug','plug_sql_exist'));
            }
            return returnData(lang('Plug','check_pass'),1);
        }
    }
}