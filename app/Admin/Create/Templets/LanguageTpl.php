<?php
/**
 * @CopyRight 易创互联 www.huimor.com
 * @name 语言文件生成模板
 * @auth tzchao
 * @time 2018-06-012
 */

namespace App\Admin\Create\Templets;

use App\Admin\Controller;

class LanguageTpl extends Controller
{
    /**
     * @return string  字符串
     */
    public function tplCont()
    {
        $createDate = date('Y-m-d');
        $str=<<<startData
<?php
/**
 * @CopyRight 易创互联 www.huimor.com
 * @name 控制器生成模板
 * @auth 
 * @time $createDate
 */
return [
    
];
?>
startData;

        return $str;
    }
}
