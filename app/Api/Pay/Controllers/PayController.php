<?php
/*
 * @name 支付接口
 * @auth lina
 * @time 2018-10-17
 */
namespace App\Api\Pay\Controllers;

use App\Classlib\FormCheck;
use App\Api\Controller;
use Illuminate\Http\Request;
use App\Classlib\AlipayTradeWapPayRequest;
use App\Classlib\AopClient;
use App\Api\Wisdom\Models\Wisdom;
use App\Classlib\JsApiPay;
use App\Classlib\WxPayConfig;
use App\Classlib\lib\WxPayApi;
use App\Classlib\lib\WxPayUnifiedOrder;
use App\Classlib\lib\WxPayNotify;
use App\Classlib\WeChatAppPay;

class PayController extends Controller
{
    public function __construct(){
        $this->Wisdom = new Wisdom();
        $this->FormCheck = new FormCheck();
    }

    //支付宝支付
    public function AliPayWeb(Request $req)
    {   
        $UserId = $this->FormCheck->isEmpty($req->uid, '用户id');
        if (!$UserId->code) {
            return result($UserId->msg);
        }

        $order_no = get_ordernumber();
        $req->order_no = $order_no;

        $str = $req->uid;

        // 添加订单信息
        $req->complaint = 2;

        $res = $this->Wisdom->AddOrderInfo($req);
        if (!$res) {
            return $this->response('订单添加失败', 0, json_decode("{}"));
        }

        $aop = new AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do'; //'https://openapi.alipaydev.com/gateway.do';
        // $aop->appId = '2016092100562508';
        // $aop->rsaPrivateKey ='MIIEpAIBAAKCAQEAvW9VMjgD12NKSYdJ8v6wVWo1U+Vo32/Ttwu6sE34qtXPsMIi7Fi4bdMiOX80NiN2KsmFO1Co2WtGCciLLfEiLww4tevmQzLhBfzRlVY+aYnPzP5KnBSqkDxtHl5m+9pXz0b7HDSWiNeNVStGBcmjUVY/WYr4C7uqw2/J/kQuuHyWWIgqtn2uSAa3H8EY98zln/TRruE4G5z9/0XLvKOOCTxMZSELt7Gh+i7vjO1pM3mhskbofUvTrciNV5ivU18HEAW7TgMg9+jhM9CIdlcnjcBkmIX27TV3lZqhuLCeTvFXT+YXMQNAAtxsHvuJpU+iKBcdezMfaXxgRSUuxl2W7wIDAQABAoIBAQCEKNoNURsM945qQeKLjrhCvvg5ccF6sK/J8nrqaVtlBAoDzU1ECpmQtU4ZQu+QHalhLHqw6RMDrF2OkcTX5nTs0d/2u39HvoVTAhDN2P9t7eF1qKswvRJJ0n5mQ4IhjzDXwTOLd/zVt1w44RnXC9fO9Rb5R4TJ2sHF24XWyIa32a+hhKDcXTo1PkHv7sTrTxQOnF4ajauVu4nFv4zRE0ibrhhJVe8fTMlECab5bbf/+jhhrsaazkFNYsg845l27qiEaAp+HCx8JA9enkmQAfWeD2QKgtL8b2nHMUVCW1GzS+EvYWpkPcyoU3apTS0LGsxNQLkyCsLQGqvz3yHPxzjhAoGBAOvsRPo2lMXX4HUIwgn75m9TDBoTtAZ2WBdDGzaOpbn3nysS9Ymk2AI00GiJPL0XxVWKuLTGdovDr12HDKFgoDqUXLgJjGcp/No229d+c+iy6dD1y/DoYGGWv6nMv4419Y+QeU+X2VrTVPrxEuPwrKNRLiRxiIjTM8eB34oIVunJAoGBAM2OSsS5NcTCHfQpPsscnwKxYElgxLnZ/qiIlJeSkoelwsdE2qqXujXSL5oF0zi/OqOie1qeZ72KmbWoAsZd7tSePKHmJklULE6kGcoj18q91FYlun8kS/x7m40PZx/OGqaYI3pmg3bCqQ2lcDWtmuzJsMc2jHMHOCs1aIcEdNb3AoGAb5HnuPCxALMrug0vPmt7OXBry7U/sMKsvCuHJhDlvQGmq0cnCTYE1V5GpPkp2T7xmpek7BDjJQug1jk0tuisPafGBV62oF8R7dx9sPZjOEvMGX70OHSch+ozr8hdWpKYE5G+XO6x4qsIiDgTt+osqnuMt71dVV+UICiCH5P8DkkCgYEAk4SvwfjNTrSk/InxJ6EVuMAFQwPwixSYfujuAs1M3Y6nd4zBhTY4i9GZGedeS9ZpRfAgqMsP1mY2o7i8rRHSicI571KVXYDL/3Ajlf8dKcIMNfsDmaU55yvqWVSZhKjMVYzPwVL6u+s9ma6GFe/ORGsGtCFAsHiY5eGBb4xSMccCgYBrsb+OQJCJvEkdE6mMfzmOsSGkFIXBDvblXpKzcsGotbc4WsX37xjzqyKOAHnnVwAnVaodkmYb6APZbP4e6bNMFE24jO0OsJnxT+lWK5Hg4x6SwY2AiqEsrLfYkrosyiIMnshb1ox1rzVarEIxc+JmlQrRFENJO8MJB4eiFzaeoA==';
        // $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApKmJ0tn3qsrgVSGNxKBXSEl1sstV42Wbsq2iqzuttAZqTvm5BMFaq+BrikB9iefBjkr/uNFhtcRqnw5Z5/xbQftEEW8KxeQCdFX3RybwgYW6Z0MLepBJ7gynaf4n52XjA9AwH1lKCBlf59H+b6SqBqkZQ/Xx455ZxiRziOctxYf/IfVbveznkF/StE/CDj8jENgmcUXeWEgGPOa1StuvgQvqA8q6J3SNLl3xBjfR/2yHTwXaJCBMEC4uaQ0D/780UKNzfGBjSyiGMyeeaB7iuU4714BQrRTrHai8J+a1o6GIcb9kHms+WMLXZYvBndJQO7gxRqsauQ2vj5gu9THAXQIDAQAB';
        $aop->appId = '2018101661690486';
        $aop->rsaPrivateKey = 'MIIEpAIBAAKCAQEA1PMFjEo8WV0ouUnKrzBHtoxRNyXv2caRvxgEjVuY6fT406qHM6yLHJl4gStObKw2to61ItvEOZqFKS8x4InRpkjFF+OU+V6pxMfMVatj5s5/XZvtGOu3ot77qxnR5DpnHM2FCMiIim/SYh3B1wMPOVeRZGTKtq20XFDN1huHHx7LVpOvwr4LJ7EyDLY59woe/0Ng4tMvWWdgjvRczKeLGKwIo92iRxAm5P+n4zpPefjEOAI6rSF7Tkclc10e9DoHFEeyWKK9y++gnrDGpuw71SwvujYsghDEzKECsAnE4GanGY2ad2eiTfS4SwEynBzZ2MlBpAm1aXveKyegRm+MUwIDAQABAoIBAQDTUkeTLKgu1m/vT/c/Wt4PnbeKzq7yokdIJ4F89gUNRvkWtAdvlC6ItxQKuvschNnW2E+QxB46O8khUaBhfuL57QUJ6+KOhQ5cJRn1ehutlAZ40p+7N1AI5NdCuGC95+cjgiAe00IMf2N70qPlxzd4ZBn4Ur2IA2EUq2tYqgCxM4FKsmGpf5I4Iwfb33ueoBEPGhoQKJHuEjphXjfFlNP4nE1Ih/wrSnnYH9Bidxpi9miOy0myR9JxFCvS8FLi2ZXLQhxtL3EwtbtH5htaIbRHxLcUKU7eaTh8gaJgp0fzuJHRvd5TgspRmaARFpk9z8n0btyrc2HkFcLEpL7heqQBAoGBAPuYdA6/flIkmAHABB/iJaCEueQkGssAxUorEKDvdp8pw5b3H6cnhshmazuDBLagqT00WAeRMJSGyx/avCHhK0EUx/mbPfNus2o03bs5W9Z2yOTN1NusDUj6VZ1LKt0YwPIZvphTVy4yo/mhyLoirWRqiOkFV4RD+/4SVJzflFPTAoGBANitXzzj0Rk7Ub7EC93pQ5iKRQ3sAjZwVDXVtXBN0EmkftWC5v6G6J9mwDlqZgKNsw3h4FxnBgdSJDk2VNqMc2Xi8AhptXmTSt8NkXgZk0ygl5o+bSz4Pj+zJG5oL7kFUaNC2kQPbwsFrowz9MXsSsH7i35J9KGRm17yLAGxSxWBAoGBAJt0sZFDRRwgPEQojuPcaf9qwylyHZP9X+nZ/iZSj+r8dTvcahRwWdmNXwighdMKJkgJBbN3RJshRnTiRIhyhrVpz74s6/R0HCutiqvaXyJ+ttaZT8KD5pmHBEtyXIPMfbuZqKiaJi4HYEYobsyEDLsVEI9am57AGv37+YgMpau9AoGAeZkaaIaFcz6KCp/k9P2SlMmMJVr8huaVK70qXMMekCWDlHko/1Bgvgwg9lfStb4l7TWE/vZGONeNQppOFnRZ0qUABgik8nyJuRHZQGhB9O/vBH3iMSmDbaYwXNGVbqUggpni+e0baTkgEsiZpnWMIr7zMsV4Ry/63NbVgYGo3IECgYBSTmJYm4SLqyHcMpF9E6DmYTrUReAWRiH5/0iFAZ3gvaGm0BV/oEsLm0mpOXttlAR8Rmdxqd0/rpStTeK4L3Cs7qY5g9M8cgvuOcOqGROLqbgXPsC8KnV46/jRlY3NheS9KAgGXlyItWiWsttR7Qmc43ogUTN15aWjdejRZM6GWg==';
        $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiianWlhe9yHx39bHror0PmBfVxbS5ey+Gnfp8Yew35TU7x4LpjjJbcRV/YdayJFOdjnfPqNRtzVxJ8TW5S/tE4bU1j851v54PQ8h9loJZZTgNGn4XtN3uZup03nQVUT/UNmvPywVnQfDj9xneRlgrYRMxEb2JACroqaKfw913qKTzCfi3g9hutILRbSzs/umeBJlC0Wyzbv92uI7EfJPk6NzEpw7X49YBCNT7ZbxjlojzFkCWxYWsdRV2bpvzrB482OYID9fIZ9sD4PheRzEGWr+doh8SEl2yFdgGd0pyzIAi3+xEJpAlLO7TPoDGKKgmT02IyFTuoO/cjpZK4sYxwIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $aop->signType='RSA2';
        $request = new AlipayTradeWapPayRequest ();

        $request->setBizContent("{" .
        "    \"body\":\"智慧豆充值\"," .
        "    \"subject\":\"智慧豆充值\"," .
        "    \"out_trade_no\":\"$order_no\"," .
        //"    \"total_amount\":0.01," .
        "    \"total_amount\":$req->order_money," .
        "    \"product_code\":\"QUICK_WAP_WAY\"" .
        "  }");
        $request->setNotifyUrl('http://manage.xuexuele.vip/api/Pay/AliPayNotify');
        $request->setReturnUrl('http://manage.xuexuele.vip/api/Pay/AliPayReturn');
        $response = $aop->pageExecute ( $request,$req->paymeth); 
        return result('true', 1, $response);
        // return $result;
    }

    public function AliPayReturn(Request $req)
    {   
        // $arr=$_GET;
        // $alipaySevice = new AlipayTradeService($config); 
        // $result = $alipaySevice->check($arr);

        $aop = new AopClient;
        $aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiianWlhe9yHx39bHror0PmBfVxbS5ey+Gnfp8Yew35TU7x4LpjjJbcRV/YdayJFOdjnfPqNRtzVxJ8TW5S/tE4bU1j851v54PQ8h9loJZZTgNGn4XtN3uZup03nQVUT/UNmvPywVnQfDj9xneRlgrYRMxEb2JACroqaKfw913qKTzCfi3g9hutILRbSzs/umeBJlC0Wyzbv92uI7EfJPk6NzEpw7X49YBCNT7ZbxjlojzFkCWxYWsdRV2bpvzrB482OYID9fIZ9sD4PheRzEGWr+doh8SEl2yFdgGd0pyzIAi3+xEJpAlLO7TPoDGKKgmT02IyFTuoO/cjpZK4sYxwIDAQAB';
        //'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApKmJ0tn3qsrgVSGNxKBXSEl1sstV42Wbsq2iqzuttAZqTvm5BMFaq+BrikB9iefBjkr/uNFhtcRqnw5Z5/xbQftEEW8KxeQCdFX3RybwgYW6Z0MLepBJ7gynaf4n52XjA9AwH1lKCBlf59H+b6SqBqkZQ/Xx455ZxiRziOctxYf/IfVbveznkF/StE/CDj8jENgmcUXeWEgGPOa1StuvgQvqA8q6J3SNLl3xBjfR/2yHTwXaJCBMEC4uaQ0D/780UKNzfGBjSyiGMyeeaB7iuU4714BQrRTrHai8J+a1o6GIcb9kHms+WMLXZYvBndJQO7gxRqsauQ2vj5gu9THAXQIDAQAB';
        $flag = $aop->rsaCheckV1($_GET, NULL, "RSA2");
        
        if(!$flag){
            // return 'fail';
            header('Location: http://m.xuexuele.vip/html/purse/purse.html');
        }

        

        $orderno  =$req->out_trade_no;
        $alipayno = $req->trade_no;

        $res = $this->Wisdom->UpdateAccountInfo($req);
        if($res){
            header('Location: http://m.xuexuele.vip/html/purse/purse.html');
            // return 'success';
        }else{
            header('Location: http://m.xuexuele.vip/html/purse/purse.html');
            // return 'fail';
        }
        
    }
    /**
     * 支付宝支付充值回调 AliPayNotify.
     * @author   < php@163.com>
     * @param Request $request
     */
    public function AliPayNotify(Request $req)
    {   


        $aop = new AopClient;
        $aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiianWlhe9yHx39bHror0PmBfVxbS5ey+Gnfp8Yew35TU7x4LpjjJbcRV/YdayJFOdjnfPqNRtzVxJ8TW5S/tE4bU1j851v54PQ8h9loJZZTgNGn4XtN3uZup03nQVUT/UNmvPywVnQfDj9xneRlgrYRMxEb2JACroqaKfw913qKTzCfi3g9hutILRbSzs/umeBJlC0Wyzbv92uI7EfJPk6NzEpw7X49YBCNT7ZbxjlojzFkCWxYWsdRV2bpvzrB482OYID9fIZ9sD4PheRzEGWr+doh8SEl2yFdgGd0pyzIAi3+xEJpAlLO7TPoDGKKgmT02IyFTuoO/cjpZK4sYxwIDAQAB';
        //'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApKmJ0tn3qsrgVSGNxKBXSEl1sstV42Wbsq2iqzuttAZqTvm5BMFaq+BrikB9iefBjkr/uNFhtcRqnw5Z5/xbQftEEW8KxeQCdFX3RybwgYW6Z0MLepBJ7gynaf4n52XjA9AwH1lKCBlf59H+b6SqBqkZQ/Xx455ZxiRziOctxYf/IfVbveznkF/StE/CDj8jENgmcUXeWEgGPOa1StuvgQvqA8q6J3SNLl3xBjfR/2yHTwXaJCBMEC4uaQ0D/780UKNzfGBjSyiGMyeeaB7iuU4714BQrRTrHai8J+a1o6GIcb9kHms+WMLXZYvBndJQO7gxRqsauQ2vj5gu9THAXQIDAQAB';
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
        
        if(!$flag){
            return 'fail';
        }

        

        $orderno  =$req->out_trade_no;
        $alipayno = $req->trade_no;

        $res = $this->Wisdom->UpdateAccountInfo($req);
        if($res){
            return 'success';
        }else{
            return 'fail';
        }
    }

    //微信支付
    public function WxPayJsApi(Request $req)
    {   
        $UserId = $this->FormCheck->isEmpty($req->uid, '用户id');
        if (!$UserId->code) {
            return result($UserId->msg);
        }

        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();
        if($openId){
            $order_no = get_ordernumber();
            $req->order_no = $order_no;

            $str = $req->uid;

            // 添加订单信息
            $req->complaint = 1;

            $res = $this->Wisdom->AddOrderInfo($req);
            if (!$res) {
                return $this->response('订单添加失败', 0, json_decode("{}"));
            }

            //②、统一下单
            $input = new WxPayUnifiedOrder();
            $input->SetBody("智慧豆充值");
            // $input->SetAttach("test");
            $input->SetOut_trade_no($order_no);
            $input->SetTotal_fee($req->order_money*100);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            // $input->SetGoods_tag("test");
            $input->SetNotify_url('https://xuexuele.huimor.com/api/Pay/WxPayNotify');
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openId);
            $config = new WxPayConfig();
            $order = WxPayApi::unifiedOrder($config, $input);
            // echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
            // printf_info($order);
            $jsApiParameters = $tools->GetJsApiParameters($order);

            return result('true', 1, $jsApiParameters);
        }else{
            return result('false',0);
        }

        
        // return $result;
    }

    public function WxPayNotify(Request $req)
    {

        
        $wxappid           = 'wxa4c2d2fa1bbfe161';//应用ID 字符串
        $mch_id            = '1513556381';//商户号 字符串
        $notify_url        = 'https://xuexuele.huimor.com/api/Pay/WxPayNotify';//接收微信支付异步通知回调地址 字符串
        $wxkey             = 'xuexueleapianquanmima01234567890';//这个是在商户中心设置的那个值用来生成签名时保证安全的 字符串

        $this->wechatAppPay = new wechatAppPay($wxappid, $mch_id, $notify_url, $wxkey);
        
        $data = $this->wechatAppPay->getNotifyData();//获取数据 用wechatAppPay类里的getNotifyData()方法，这里数据也被getNotifyData()由xml转化成了数组。

        // Log::notice('WechatPay notify post data verification.', [
        //     'data' => $data
        // ]);

        //假如$data里有如下参数
        $w_sign = array();           //参加验签签名的参数数组                     
        $w_sign['appid']             = $data['appid'];
        $w_sign['bank_type']         = $data['bank_type'];
        $w_sign['cash_fee']          = $data['cash_fee'];
        $w_sign['fee_type']          = $data['fee_type'];
        $w_sign['is_subscribe']      = $data['is_subscribe'];
        $w_sign['mch_id']            = $data['mch_id'];
        $w_sign['nonce_str']         = $data['nonce_str'];
        $w_sign['openid']            = $data['openid'];
        $w_sign['out_trade_no']      = $data['out_trade_no'];
        $w_sign['result_code']       = $data['result_code'];
        $w_sign['return_code']       = $data['return_code'];
        $w_sign['time_end']          = $data['time_end'];
        $w_sign['total_fee']         = $data['total_fee'];
        $w_sign['trade_type']        = $data['trade_type'];
        $w_sign['transaction_id']    = $data['transaction_id'];

        $verify_sign = $this->wechatAppPay->MakeSign($w_sign);//生成验签签名

        if ($verify_sign==$data['sign']){
            if($data['result_code']=='SUCCESS' && $data['return_code']=='SUCCESS'){

                
                $req->out_trade_no=$data['out_trade_no'];
                $req->transaction_id=$data['transaction_id'];
                $res = $this->Wisdom->UpdateAccountInfo($req);
                $msg=$this->wechatAppPay->replyNotify();
                return $msg;
            }
            else{
                $msg='FAIL';
                return $msg;
            }

        }
        else{
            $msg='签名验证失败！';
            return $msg;
        }
        
    

    }

}