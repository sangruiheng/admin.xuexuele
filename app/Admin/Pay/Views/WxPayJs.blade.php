
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <title>充值订单</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html,
        body {
            background: #fff;
            width: 100%;
            height: 100%;
            font-size: 10px;
            color: #333;
            overflow: hidden;
        }

        .purse {
            width: 100%;
            height: 100%;
            background: #30A007;
            overflow: hidden;
        }

        .container {
            margin: .5rem;
            width: 97.5%;
            height: 98%;
            background: #A8D535;
            border-radius: 1rem;
        }

        .content {
            position: absolute;
            top: 4.4rem;
            left: 1rem;
            width: 95%;
            height: 89.66%;
            background-color: #FFF7DF;
            border-radius: 1rem;
            overflow: hidden;
        }

        .purseTitle {
            position: absolute;
            top: 1.5rem;
            width: 98%;
            text-align: center;
            font-family: MicrosoftYaHei-Bold;
            font-weight: bold;
            color: #FFFFFF;
            font-size: 1.8rem;
        }

        .recharge {
            position: absolute;
            top: 12rem;
            left: .4rem;
            width: 86%;
            height: 6rem;
            padding: 0 2rem 0 2rem;
            border-top: 1px solid rgba(229, 194, 138, 1);
            border-bottom: 1px solid rgba(229, 194, 138, 1);
            font-size: 1.5rem;
        }

        .order {
            line-height: 6rem;
            float: left;
        }

        .num {
            line-height: 6rem;
            float: right;
        }

        .btn {
            position: absolute;
            top: 30rem;
            left: 7.2rem;
            width: 62%;
            height: 5rem;
            border-radius: .5rem;
            text-align: center;
            line-height: 5rem;
            font-size: 1.5rem;
            background-color: #30A007;
        }
    </style>
    <script type="text/javascript">
    //调用微信JS api 支付
    function jsApiCall()
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo $jsApiParameters; ?>,
            function(res){
                WeixinJSBridge.log(res.err_msg);
                if(res.err_msg == "get_brand_wcpay_request:ok" ){
                   window.location.href="https://m.xuexuele.vip/html/purse/recharge-result.html?ordermoney="+{{$paymoney}}; 
                } 

            }
        );
    }

    function callpay()
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }
    </script>
    
</head>
<body>
    <section class="purse">
        <div class="container">
            <p class="purseTitle">-充值订单-</p>
            <div class="content">
                <div class="recharge">
                    <span class="order">订单支付金额为</span>
                    <span class="num">￥{{$paymoney}}</span>
                </div>
                <div class="btn" onclick="callpay()">立即支付</div>
            </div>
        </div>
    </section>

    
</body>
<script>
    window.onload = function () {
        !(function (doc, win) {
            var docEle = doc.documentElement, //获取html元素
                event = "onorientationchange" in window ? "orientationchange" : "resize", //判断是屏幕旋转还是resize;
                fn = function () {
                    var width = docEle.clientWidth - 50; //获取屏幕宽度并减去状态栏高度
                    width && (docEle.style.fontSize = 10 * (width / 360) + "px"); //设置html的fontSize，随着event的改变而改变。
                };
            win.addEventListener(event, fn, false);
            doc.addEventListener("DOMContentLoaded", fn, false);
        }(document, window));
    }
</script>
</html>