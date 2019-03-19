<?php
/****************************************
 * @copyRight 易创互联（www.huimor.com）
 * @auth tzchao
 * @time 2018-04-08
 * Function golbal
 * 全局函数库
 ***************************************/

/*
 * 自定义格式化打印函数
 * @param string、object、array
 * @return 格式化后台数据
 */
function p($str){
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}
//根据时间生成随机订单号
function get_ordernumber()
{
    $number = date("Y-m-d H:i:s", time());
    $number = preg_replace('/[^0-9]/', '', $number);
    $random = 100000000 + rand(0, 99999999);
    $random = substr($random, 1);
    return $number . $random;
}
/*
 *格式化图片真实路径，并检测图片是否存在，若不存在自动输出默认图片
 * @param 图片路径
 * $param string 图片尺寸
 * @param string url 默认图片地址
 */
function imageShow($imageUrl,$imageSize = false , $defaultUrl=""){
    if($imageSize){
        //处理图片地址
        $imgResArr = explode('.', $imageUrl);
        $imgExt = $imgResArr[count($imgResArr)-1];
        $imgSrc = url($imageUrl.$imageSize.'.'.$imgExt);
        if($defaultUrl){
            return $imgSrc;
            //return $imgSrc && fileExists($imgSrc) ? $imgSrc : $defaultUrl;
        }else{
            //return  $imgSrc && fileExists($imgSrc) ? $imgSrc : url("images/nopic.png");
            return $imgSrc;
        }
    }else{
        if($defaultUrl){
            return $imageUrl && fileExists(url($imageUrl)) ? url($imageUrl) : $defaultUrl;
        }else{
            return $imageUrl && fileExists(url($imageUrl)) ? url($imageUrl) : url("images/nopic.png");
        }
    }
}

/**
 * 获取 IP  地理位置
 * 淘宝IP接口
 * @Return: array
 */
function getCity($ip = '')
{
    $url = "http://api.map.baidu.com/location/ip?ip=".$ip."&ak=esZ0C6dOvy0rBtEtOe7E9AZnZSYCkUvV&coor=bd09ll";
    $curl = curl_init($url); 
    curl_setopt($curl, CURLOPT_NOBODY, true); 
    $result = curl_exec($curl); 

    if($result!==false){
        $ipInfo = json_decode(file_get_contents($url),true);
        $data = [];
        // 接口异常默认值
        $data['country'] = '未知';
        $data['city'] = '未知';
        if($ipInfo['status'] == 0){
            // 正常
            $data = $ipInfo;
            $data['country'] = '中国';
            $data['city'] = $ipInfo['content']['address'];
        } 
    }else{
        $data = [];
        // 接口异常默认值
        $data['country'] = '未知';
        $data['city'] = '未知';
    }
    
    return $data;
}

/*
 * 价格拆分，返回数组，索引0为整数部分，1为小数点后两位部份
 * @param price
 */
function themePrice($price){
    if (!empty($price)) {
        return $price = explode(".", $price);
    }
}


/*
 * array uniqueRand( int $min, int $max, int $num )
 * 生成一定数量的不重复随机数
 * $min 和 $max: 指定随机数的范围
 * $num: 指定生成数量
 */
function uniqueRand($min, $max, $num){
    //初始化变量为0
    $count = 0;
    //建一个新数组
    $return = array();
    while ($count < $num) {
        //在一定范围内随机生成一个数放入数组中
        $return[] = mt_rand($min, $max);
        //去除数组中的重复值用了“翻翻法”，就是用array_flip()把数组的key和value交换两次。这种做法比用 array_unique() 快得多。
        $return = array_flip(array_flip($return));
        //将数组的数量存入变量count中
        $count = count($return);
    }
    //为数组赋予新的键名
    shuffle($return);
    return $return;
}

/*
 * 数组 转 对象
 * @param array $arr 数组
 * @return object
 */
function arrayToObject($arr){
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array') {
            $arr[$k] = (object)arrayToObject($v);
        }
    }
    return (object)$arr;
}
function test($a){

    return $a;
}

/*
 * 语言包调取函数
 * @param $module 模块
 * @param $key 关键值
 * @return string 返回语言
 */
function lang($module,$key,$namespace = 'Admin'){
    $lang = env("APP_LANG");
    $langPath = app_path($namespace.'/'.$module.'/Languages/');
    $langCont =  include($langPath.$lang.".php");
    return $langCont[$key];
}

/*
 * 对象 转 数组
 * @param array $arr 数组
 * @return object
 */
function objectToArray($array){
    if (is_object($array)) {
        $array = (array)$array;
    }
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $array[$key] = objectToArray($value);
        }
    }
    return $array;
}

/*
 * 数组 转 XML
 * @param array $arr 数组
 * @return XML
 */
function arrayToXml($arr){
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<PACKET TYPE="RESPONSE"><BODY><PAY_RESULT>';
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $xml .= "<" . $key . ">" . arrayToXml($val) . "</" . $key . ">";
        } else {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
        }
    }
    $xml .= "</PAY_RESULT></BODY></PACKET> ";
    return $xml;
}

/*
 * 获取远程图片
 * @param url 远程图片地址
 * @param saveDir   保存路径，不输入默认保存到跟目录
 * @param filenmae  文件名称
 * param type  获取文件方式
 * @return array
 */
function getImage($url, $saveDir = '', $filename = '',$type = ''){
    if (trim($url) == '') {
        return array('file_name' => '', 'save_path' => '', 'error' => 1);
    }
    if (trim($saveDir) == '') {
        $save_dir = './';
    }
    if (trim($filename) == '') {//保存文件名
        $ext = strrchr($url, '.');
        if ($ext != '.gif' && $ext != '.jpg') {
            return array('file_name' => '', 'save_path' => '', 'error' => 3);
        }
        $filename = time() . $ext;
    }
    if (0 !== strrpos($save_dir, '/')) {
        $save_dir .= '/';
    }
    //创建保存目录
    if (!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {
        return array('file_name' => '', 'save_path' => '', 'error' => 5);
    }
    //获取远程文件所采用的方法
    if ($type) {
        $ch = curl_init();
        $timeout = 3;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $img = curl_exec($ch);
        //amazon https://forums.aws.amazon.com/message.jspa?messageID=196878
        curl_close($ch);
    } else {
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
    }
    //$size=strlen($img);
    //文件大小
    $fp2 = @fopen($save_dir . $filename, 'w');
    fwrite($fp2, $img);
    fclose($fp2);
    unset($img, $url);
    return array('file_name' => $filename, 'save_path' => $save_dir . $filename, 'error' => 0);
}

/*
 * 发起curl get方式请求
 * @param url 请注地址
 * @return array
 */
function curlHttpsGet($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}

/*
 * 手机号码隐藏中间数据
 * @param $phone
 * @return string
 */
function hidtel($phone)
{
    $IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i', $phone); //固定电话
    if ($IsWhat == 1) {
        return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i', '$1****$2', $phone);
    } else {
        return preg_replace('/(1[3578]{1}[0-9])[0-9]{4}([0-9]{4})/i', '$1****$2', $phone);
    }
}

/*
 * 隐藏银行卡号中间几位数据
 * @param $bankCard
 * @return string
 */
function hidBankCard($bankCard){
    return substr_replace($bankCard, "**********", 4, 10);
}


/*
 *处理控制器间返回结果
 * @param $msg
 * @param int $code
 * @param string $data
 * @return object
 */
function returnData($msg, $code = 0, $data = ""){
    $res['msg'] = $msg;
    $res['code'] = $code;
    $res['data'] = $data;
    return arrayToObject($res);
}


/*
 * 处理返回数据
 * @param $msg
 * @param int $code
 * @param string $data
 * @return json
 */
function result($msg, $code = 0, $data = "", $count = -1){
    $res['msg'] = $msg;
    $res['code'] = $code;
    $res['data'] = $data;
    if ($count >= 0) {
        $res['count'] = $count;
    }
    return json_encode($res);
}

/*
 * 判断是否是Y-m-d格式
 * @param $date
 * @return bool
 */
function isDate($date)
{
    if ($date == date('Y-m-d', strtotime($date))) {
        return true;
    } else {
        return false;
    }
}

/*
 * 判断是否是Y-m-d H:i:s格式
 * @param $date
 * @return bool
 */
function isDateTime($date){
    if ($date == date('Y-m-d H:i:s', strtotime($date))) {
        return true;
    } else {
        return false;
    }
}

/*
 * 生成静态资源读取地址
 * @param $resource
 * @return url
 */
function asseturl($resource){
    return url("/resource/" . $resource);
}

/*
 * 格式化后台完整地址
 * @param $resource
 * @return url
 */
function adminurl($resource = ""){
    return url(env("BACKSTAGE_PREFIX") . $resource);
}

/*
 * 格式化后台完整地址
 * @param $resource
 * @return url
 */
function apiurl($resource = ""){
    return url('api' . $resource);
}


/*
 * 获取后台菜单列表
 * @param $thisAction 当前操作
 * @return Html
 */
function getAdminMenuList($thisAction = ""){
    $adminInfo = \Illuminate\Support\Facades\Session::get("adminInfo");
    $menu = new \App\Admin\Menu\Models\Menu();
    $role = new \App\Admin\Manage\Models\Role();
    $menuAuthObj = $role->getRoleMenuIdArr($adminInfo->role_id);
    $menuIdArr = array();
    foreach ($menuAuthObj as $data) {
        $menuIdArr[] = $data->menu_id;
    }

    $list = $menu->getFirstMenu(2);
    $str = '<ul class="layui-nav layui-nav-tree"  lay-filter="loadding">';
    foreach ($list as $data) {
        $twoMenuList = $menu->getTwoMenu($data->id, 2);
        if (!count($twoMenuList)) {
            //判断一级菜单是否有权限查看
            if (in_array($data->id, $menuIdArr)) { //判断是否有权限访问此菜单
                if ($data->url == $thisAction) {
                    $str .= '<li class="layui-nav-item layui-this">';
                } else {
                    $str .= '<li class="layui-nav-item">';
                }
                $str .= '<a class="loadHref" href="' . adminurl($data->url) . '"><i class="iconfont">'.htmlspecialchars_decode($data->icon_class).'</i> ' . $data->title . '</a>';
                $str .= '</li>';
            }
        } else {
            $strChild = "";
            $thisChild = false;
            foreach ($twoMenuList as $value) {
                if (in_array($value->id, $menuIdArr)) { //判断是否有权限访问此菜单
                    if ($value->url == $thisAction) {
                        $strChild .= '<dd class="layui-this"><a href="' . adminurl($value->url) . '"><i class="iconfont">'.htmlspecialchars_decode($value->icon_class).'</i> ' . $value->title . '</a></dd>';
                        $thisChild = true;
                    } else {
                        $strChild .= '<dd><a class="loadHref" href="' . adminurl($value->url) . '"><i class="iconfont">'.htmlspecialchars_decode($value->icon_class).'</i> ' . $value->title . '</a></dd>';
                    }
                }
            }
            if ($strChild != "") { //判断此二级菜单下是否有子菜单
                if ($thisChild) {
                    $str .= '<li class="layui-nav-item layui-nav-itemed">';
                } else {
                    $str .= '<li class="layui-nav-item">';
                }
                $str .= '<a class="" href="javascript:;"><i class="iconfont">'.htmlspecialchars_decode($data->icon_class).'</i> ' . $data->title . '</a>';
                $str .= '<dl class="layui-nav-child">';
                $str .= $strChild;
                $str .= '</dl>';
                $str .= '</li>';
            }
        }
    }
    $str .= '</ul>';
    echo $str;
}

/*
 * 获取后台管理员头像
 * @param $adminId 管理员ID
 * @return url
 */
function getAdminAvator($adminId = false){
    if (!$adminId) {
        $adminInfo = \Illuminate\Support\Facades\Session::get("adminInfo");
    } else {
        $admin = new \App\Admin\Manage\Models\Admin();
        $adminInfo = $admin->getAdminInfo($adminId);
    }
    return imageShow($adminInfo->avator,'30x30');
}

/*
 * 获取后台管理员名称
 * @param $adminId 管理员ID
 * @return str name
 */
function getAdminName($adminId = false)
{
    if (!$adminId) {
        $adminInfo = \Illuminate\Support\Facades\Session::get("adminInfo");
    } else {
        $admin = new \App\Admin\Manage\Models\Admin();
        $adminInfo = $admin->getAdminInfo($adminId);
    }
    if ($adminInfo->realname) {
        return $adminInfo->realname;
    } else {
        return "匿名";
    }
}

/*
 * 获取后台面包绡导航
 * @param $thisAction 当前操作
 * @param $thisName 当前操作名称（标题）
 * @return url
 */
function adminNav($thisAction = "",$thisName = "")
{
    $navHtml = '<span class="layui-breadcrumb">';
    if (!$thisAction) {
        $navHtml .= '<a><i class="layui-icon">&#xe68e;</i>后台主页</a>';
    } else {
        $navHtml .= '<a><i class="layui-icon">&#xe68e;</i>后台主页</a>';
        //获取菜单
        $menu = new \App\Admin\Menu\Models\Menu();
        $menuInfo = $menu->getMenuInfo($thisAction, 2);
        if ($menuInfo->parent_id != 0) {
            //获取父级菜单
            $parentMenuInfo = $menu->getMenuInfo($menuInfo->parent_id);
            if (count($parentMenuInfo)) {
                $navHtml .= '<a><i class="layui-icon"></i>' . $parentMenuInfo->title . '</a>';
            }
        }
        $navHtml .= '<a><i class="layui-icon"></i>' . $menuInfo->title . '</a>';
        if($thisName){
            $navHtml .= '<a><i class="layui-icon"></i>' . $thisName . '</a>';
        }
    }
    $navHtml .= '</span>';
    echo $navHtml;
}

function getMenuFromPath($path)
{
    $menuPath = ltrim($path, env("BACKSTAGE_PREFIX"));//过滤链接中存在多个后台关键词
    $menu = new \App\Admin\Menu\Models\Menu();
    //处理多级菜单当前状态问题
    $menuPath = rtrim($menuPath, "/");
    $exp = explode('/',$menuPath);
    $menuInfo = $menu->where("url", "=", '/'.$exp[1])->first();
    if (!count($menuInfo)) {
        $menuInfo['url'] = "/";
        $menuInfo['title'] = "后台主页";
        return arrayToObject($menuInfo);
    } else {
        return $menuInfo;
    }
}

//获取后台配置信息
function adminSetting($key)
{
    $setting = new \App\Admin\System\Models\Setting();
    $settingInfo = $setting->where("key", "=", $key)->first();
    if (!count($settingInfo)) {
        return "";
    } else {
        return $settingInfo->value;
    }
}
//检测远程文件是否存在
function fileExists($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_NOBODY, 1); // 不下载
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch)!==false)
        return true;
    else
        return false;
}

/*
 * php实现下载远程文件保存到本地
 **
 * $url 图片所在地址
 * $path 保存图片的路径
 * $filename 图片自定义命名
 * $type 使用什么方式下载
 * 0:curl方式,1:readfile方式,2file_get_contents方式
 *
 * return 文件名
 */
function getFile($url,$path='',$filename='',$type=0){
    if($url==''){return false;}
    //获取远程文件数据
    if($type===0){
        $ch=curl_init();
        $timeout=5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);//最长执行时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);//最长等待时间

        $img=curl_exec($ch);
        curl_close($ch);
    }
    if($type===1){
        ob_start();
        readfile($url);
        $img=ob_get_contents();
        ob_end_clean();
    }
    if($type===2){
        $img=file_get_contents($url);
    }
    //判断下载的数据 是否为空 下载超时问题
    if(empty($img)){
        throw new \Exception("下载错误,无法获取下载文件！");
    }
    //没有指定路径则默认当前路径
    if($path===''){
        $path="./";
    }
    //如果命名为空
    if($filename===""){
        $filename=md5($img);
    }
    //获取后缀名
    $ext=substr($url, strrpos($url, '.'));
    if($ext && strlen($ext)<5){
        $filename.=$ext;
    }
    //防止"/"没有添加
    $path=rtrim($path,"/")."/";
    $fp2=@fopen($path.$filename,'a');
    fwrite($fp2,$img);
    fclose($fp2);
    return $filename;
}

/*
 * 复制目录
 * @param $src 原目录
 * @param 目标目录
 */
function copyDir($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                copyDir($src . '/' . $file,$dst . '/' . $file);
                continue;
            }else{
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
    return true;
}

//删除目录
function delDir($dir) {
    if (!is_dir($dir)) {
        return false;
    }
    $handle = opendir($dir);
    while (($file = readdir($handle)) !== false) {
        if ($file != "." && $file != "..") {
            is_dir("$dir/$file") ? delDir("$dir/$file") : @unlink("$dir/$file");
        }
    }
    if (readdir($handle) == false) {
        closedir($handle);
        @rmdir($dir);
    }
    return true;
}

//验证操作是否显示
function actionIsView($actionKey){
    $menu = new \App\Admin\Menu\Models\Menu();
    $res = $menu->getMenuActionIsAuth($actionKey);
    if($res){
        return true;
    }else{
        return false;
    }
}