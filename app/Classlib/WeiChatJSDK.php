<?php
/**
 * @copyRight 易创互联（www.huimor.com）
 * @auth tzchao
 * @time 2018-03-19
 * Class WeiChatJSDK
 * 微信JSDK PHP端配置信息调取包
 */
namespace App\Classlib;

use Illuminate\Support\Facades\Cache;

class WeiChatJSDK {
    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret) {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /*
     * 获取JSDK的配置信息
     * @param $url 获取配置信息的前端url地址
     * @return json 返回json数据
     */
    public function getSignPackage($url) {
        $jsapiTicket = $this->getJsApiTicket();
        if(!$jsapiTicket->code){
            return returnData($jsapiTicket->msg,$jsapiTicket->code,$jsapiTicket->data);
        }else{
            $ticket = $jsapiTicket->data;
        }
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string); //生成签名算法
        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return returnData("配置获取成功",1,$signPackage);
    }

    //获取微信用户的access_token和openid
    public function getUserAccessToken($code) {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appId."&secret=$this->appSecret&code=".$code."&grant_type=authorization_code";
        $res = json_decode($this->httpGet($url));
        return $res;
    }

    //获取授权用户的详细信息
    public function getUserInfo($tokenArray) {
        $tokenArray = arrayToObject($tokenArray);
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$tokenArray->access_token&openid=$tokenArray->openid&lang=zh_CN";
        $res = json_decode($this->httpGet($url));
        return $res;
    }

    //创建签名数据信息
    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    //获取JSAPI票据信息
    private function getJsApiTicket() {
        $ticketCache = (object)array();
        $ticketCache = Cache::get("ticketCache");
        if(empty($ticketCache) || !isset($ticketCache->expire_time) || $ticketCache->expire_time < time()){
            $accessTokenRes = $this->getAccessToken();
            if(!$accessTokenRes->code){
                return returnData($accessTokenRes->msg,$accessTokenRes->code,$accessTokenRes->data);
            }else{
                $accessToken = $accessTokenRes->data;
            }
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            if (isset($res->ticket)) {
                $ticket = $res->ticket;
                $ticketCache->expire_time = time() + 7000;
                $ticketCache->jsapi_ticket = $ticket;
                Cache::put("ticketCache",$ticketCache);
            }else{
                return returnData("ticket获取失败",0,$res);
            }
        }else{
            $ticket = $ticketCache->jsapi_ticket;
        }
        return returnData("ticket获取成功",1,$ticket);
    }

    //获取微信用户token信息
    private function getAccessToken() {
        $tokenCache = (object)array();
        $tokenCache = Cache::get("tokenCache");
        if(empty($tokenCache) || !isset($tokenCache->expire_time) || $tokenCache->expire_time < time()){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url));
            if (isset($res->access_token)) {
                $accessToken = $res->access_token;
                $tokenCache->expire_time = time() + 7000;
                $tokenCache->access_token = $accessToken;
                Cache::put("tokenCache",$tokenCache);
            }else{
                return returnData("AccessToken获取失败",0,$res);
            }
        }else{
            $accessToken = $tokenCache->access_token;
        }
        return returnData("AccessToken获取成功",1,$accessToken);
    }

    //进行httpGET请求
    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
}