<?php
/*
 * @name 单页管理
 * @auth tzchao
 * @time 2017-11-15
 */

namespace App\Admin\Pay\Controllers;


use App\Classlib\FormCheck;
use Illuminate\Http\Request;
use App\Admin\Controller;
use App\Api\Wisdom\Models\Wisdom;
use App\Classlib\JsApiPay;
use App\Classlib\WxPayConfig;
use App\Classlib\lib\WxPayApi;
use App\Classlib\lib\WxPayUnifiedOrder;
use App\Classlib\WeChatAppPay;
use Illuminate\Support\Facades\DB;

class PayController extends Controller
{
    public function __construct(){
        $this->FormCheck = new FormCheck();
        $this->Wisdom = new Wisdom();
    }

    //微信支付
    public function WxPayJs(Request $req){
        $UserId = $this->FormCheck->isEmpty($req->uid, '用户id');
        if (!$UserId->code) {
            return result('参数错误！');
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
            $input->SetTotal_fee($req->order_money*100);//$req->order_money*100
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            // $input->SetGoods_tag("test");
            $input->SetNotify_url('http://manage.xuexuele.vip/admin/WxPayNotify');
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openId);
            $config = new WxPayConfig();
            $order = WxPayApi::unifiedOrder($config, $input);
            // echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
            // printf_info($order);
            $jsApiParameters = $tools->GetJsApiParameters($order);
            $paymoney=$req->order_money;

            return view("Admin.Pay.Views.WxPayJs")
                    ->with("jsApiParameters", $jsApiParameters)
                    ->with("paymoney", $paymoney);
        }else{
            return result('参数错误！');
        }
    }



    public function WxPayNotify()
    {

        
        $wxappid           = 'wxa4c2d2fa1bbfe161';//应用ID 字符串
        $mch_id            = '1513556381';//商户号 字符串
        $notify_url        = 'http://manage.xuexuele.vip/admin/WxPayNotify';//接收微信支付异步通知回调地址 字符串
        $wxkey             = 'xuexueleapianquanmima01234567890';//这个是在商户中心设置的那个值用来生成签名时保证安全的 字符串

        $this->wechatAppPay = new WeChatAppPay($wxappid, $mch_id, $notify_url, $wxkey);
        
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

                $res = $this->Wisdom->UpdateAccountInfoWx($data['out_trade_no'],$data['transaction_id']);
                $msg=$this->wechatAppPay->replyNotify();
                return true;
            }
            else{
                $msg='FAIL';
                return false;
            }

        }
        else{
            $msg='签名验证失败！';
            return false;
            
        }


        // return $msg;
    

    }

}