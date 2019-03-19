<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' =>['auth.api']], function () {
    Route::group(['namespace' =>'Auth\Controllers'],function(){
        Route::get('login','LoginController@index')->name("index");
    });
//    echo 11;die;
    Route::group(['prefix' => 'User','namespace' =>'User\Controllers'],function(){
        // 用户登录
        Route::post('Login','UserController@Login')->name("Login");
        // 用户注册
        Route::post('UserRegisterInfo','UserController@UserRegisterInfo')->name("UserRegisterInfo");
        //忘记密码
        Route::post('UpdataUserPasswdInfo','UserController@UpdataUserPasswdInfo')->name("UpdataUserPasswdInfo");
        // 检验手机号码是否已注册
        Route::post('UserPhone','UserController@UserPhone')->name("UserPhone");
        // 发送短信
        Route::post('SendSmsInfo','UserController@SendSmsInfo')->name("SendSmsInfo");
        // 用户签到
        Route::post('UserSignin','UserController@UserSignin')->name("UserSignin");
        // 获取用户当月签到天数和获取的智慧豆
        Route::get('UserSigninTotal','UserController@UserSigninTotal')->name("UserSigninTotal");
        // 站内信列表
        Route::get('UserMessage','UserController@UserMessage')->name("UserMessage");
        // 站内信详情
        Route::get('UserMessageInfo','UserController@UserMessageInfo')->name("UserMessageInfo");
        // 站内信更新为已读
        Route::get('UserMessageIsread','UserController@UserMessageIsread')->name("UserMessageIsread");
        // 站内信删除
        Route::delete('UserMessageDelete','UserController@UserMessageDelete')->name("UserMessageDelete");
        // 闯关列表
        Route::get('UserGate','UserController@UserGate')->name("UserGate");
        // 关卡详情
        Route::get('UserGateDetail','UserController@UserGateDetail')->name("UserGateDetail");
        // 挑战关卡
        Route::get('UserGateChallenge','UserController@UserGateChallenge')->name("UserGateChallenge");
        // 闯关全网排名
        Route::get('UserGateSort','UserController@UserGateSort')->name("UserGateSort");
        // 百宝箱
        Route::get('UserBaobox','UserController@UserBaobox')->name("UserBaobox");
        // 百宝箱详情
        Route::get('UserBaoboxDetail','UserController@UserBaoboxDetail')->name("UserBaoboxDetail");
        // 原住民
        Route::get('UserResident','UserController@UserResident')->name("UserResident");
        // 城市列表
        Route::get('CityList','UserController@CityList')->name("CityList");
        // 所有城市列表
        Route::get('CityListAll','UserController@CityListAll')->name("CityListAll");

        // 百宝箱推荐课程
        Route::get('UserBaoboxCourse','UserController@UserBaoboxCourse')->name("UserBaoboxCourse");
        // 百宝箱推荐课程详情
        Route::get('UserBaoboxCourseDetail','UserController@UserBaoboxCourseDetail')->name("UserBaoboxCourseDetail");

        // 用户协议
        Route::get('UserRegular','UserController@UserRegular')->name("UserRegular");

         // 验证码验证
        Route::post('VerifySmsInfo','UserController@VerifySmsInfo')->name("VerifySmsInfo");

        //启动页
        Route::get('getStartupPage','UserController@getStartupPage')->name("getStartupPage");
        //首页广告弹窗
        Route::get('getAdvertisementAlert','UserController@getAdvertisementAlert')->name("getAdvertisementAlert");

        //关卡详情
        Route::get('UserGateDetail1','UserController@UserGateDetail1')->name("UserGateDetail1");



    });

    Route::group(['prefix' => 'Wisdom','namespace' =>'Wisdom\Controllers'],function(){
        // 智慧社列表
        Route::get('WisdomList','WisdomController@WisdomList')->name("WisdomList");
        // 智慧社详情
        Route::get('WisdomDetail','WisdomController@WisdomDetail')->name("WisdomDetail");
        // 获取智慧社解锁专辑课程列表详情
        Route::get('WisdomDetailList','WisdomController@WisdomDetailList')->name("WisdomDetailList");
        // 智慧社解锁
        Route::post('WisdomUnlock','WisdomController@WisdomUnlock')->name("WisdomUnlock");
        // 广告列表
        Route::get('BannerList','WisdomController@BannerList')->name("BannerList");
        // 判断是否关注
        Route::get('Follow','WisdomController@Follow')->name("Follow");
        // 点击关注
        Route::post('FollowSpot','WisdomController@FollowSpot')->name("FollowSpot");
        // 取消关注
        Route::post('FollowNot','WisdomController@FollowNot')->name("FollowNot");
        // 判断是否可评分
        Route::get('Score','WisdomController@Score')->name("Score");
        // 判断是否可评分
        Route::post('ScoreDo','WisdomController@ScoreDo')->name("ScoreDo");
        // 课程总打赏数量
        Route::get('ScoreWisdombeanSum','WisdomController@ScoreWisdombeanSum')->name("ScoreWisdombeanSum");
        // 各级评分数量列表
        Route::get('ScoreList','WisdomController@ScoreList')->name("ScoreList");

        // 获取账户余额信息
        Route::get('GetAccountBalanceInfo','WisdomController@GetAccountBalanceInfo')->name("GetAccountBalanceInfo");
        // 钱包充值记录
        Route::get('WalletRecord','WisdomController@WalletRecord')->name("WalletRecord");
        // 钱包充值
        Route::post('WalletOrder','WisdomController@WalletOrder')->name("WalletOrder");
        // 微信支付 /api/Wisdom/WalletOrderWxPay
        Route::post('WalletOrderWxPay','WisdomController@WalletOrderWxPay')->name("WalletOrderWxPay");
        // 验证订单并修改订单状态
        Route::any('WxPayNotify','WisdomController@WxPayNotify')->name("WxPayNotify");
        // 支付宝支付
        Route::post('AliPay','WisdomController@AliPay')->name("AliPay");
        // 支付宝支付充值回调
        Route::post('AliPayNotify','MyController@AliPayNotify')->name("AliPayNotify");
        // 智慧豆使用记录
        Route::get('WalletWisdombeanUse','WisdomController@WalletWisdombeanUse')->name("WalletWisdombeanUse");
        // 课程留言列表
        Route::get('CommentList','WisdomController@CommentList')->name("CommentList");
        // 发布留言
        Route::post('CommentRelease','WisdomController@CommentRelease')->name("CommentRelease");
        // 回复留言
        Route::post('CommentReply','WisdomController@CommentReply')->name("CommentReply");
        // 专辑列表
        Route::get('AlbumCourseList','WisdomController@AlbumCourseList')->name("AlbumCourseList");
        // 充值金额列表
        Route::get('MoneyRuleList','WisdomController@MoneyRuleList')->name("MoneyRuleList");
        // 导师详情
        Route::get('TutorDetail','WisdomController@TutorDetail')->name("TutorDetail");
        // 举报
        Route::post('Regular','WisdomController@Regular')->name("Regular");
        // 解锁全部课程
        Route::post('WisdomUnlockAll','WisdomController@WisdomUnlockAll')->name("WisdomUnlockAll");
        // 解锁单一课程详情
        Route::get('UnlockCourseDetail','WisdomController@UnlockCourseDetail')->name("UnlockCourseDetail");
        // 解锁所有课程详情
        Route::get('UnlockAllCourseDetail','WisdomController@UnlockAllCourseDetail')->name("UnlockAllCourseDetail");

        // 智慧塔首页
        Route::get('WisdomIndex','WisdomController@WisdomIndex')->name("WisdomIndex");
        // 查看答案
        Route::get('GateAnswer','WisdomController@GateAnswer')->name("GateAnswer");
        // 打赏比例
        Route::get('PlatreWardbeans','WisdomController@PlatreWardbeans')->name("PlatreWardbeans");
        // 播放课程
        Route::get('CourseStudy','WisdomController@CourseStudy')->name("CourseStudy");

        // 分享智慧社详情
        Route::get('WisdomDetailShare','WisdomController@WisdomDetailShare')->name("WisdomDetailShare");

         // 分享量增加
        Route::get('WisdomShareAdd','WisdomController@WisdomShareAdd')->name("WisdomShareAdd");


    });

 Route::group(['prefix' => 'My','namespace' =>'My\Controllers'],function(){
        //个人中心-设置
        Route::get('Setup','MyController@Setup')->name("Setup");
        //个人中心-头像上传
        Route::post('HeadPortrait','MyController@HeadPortrait')->name("HeadPortrait");
        //保存用户信息
        Route::post('Preservation','MyController@Preservation')->name("Preservation");
        //实名认证
        Route::post('Identity','MyController@Identity')->name("Identity");
        //用户关注列表
        Route::get('FollowList','MyController@FollowList')->name("FollowList");

        //用户关注详情
        Route::get('FollowDetail','MyController@FollowDetail')->name("FollowDetail");
        //我的课程列表
        Route::get('AlbumList','MyController@AlbumList')->name("AlbumList");
        //课程管理列表
        Route::get('AlbumManageList','MyController@AlbumManageList')->name("AlbumManageList");
        //新增专辑
        Route::post('AlbumAdd','MyController@AlbumAdd')->name("AlbumAdd");
        //删除专辑 
        Route::delete('AlbumDelete','MyController@AlbumDelete')->name("AlbumDelete");
        //编辑专辑 
        Route::put('AlbumEdit','MyController@AlbumEdit')->name("AlbumEdit");
        //个人中心用户信息 
        Route::get('UserInfo','MyController@UserInfo')->name("UserInfo");
        //专辑详情 
        Route::get('AlbumDetail','MyController@AlbumDetail')->name("AlbumDetail");
        //上传图片 
        Route::post('ImgUpload','MyController@ImgUpload')->name("ImgUpload");
        //上传音频 
        Route::post('VoiceUpload','MyController@VoiceUpload')->name("VoiceUpload");
        //新增课程 
        Route::post('CourseAdd','MyController@CourseAdd')->name("CourseAdd");
        //课程详情 
        Route::get('CourseDetail','MyController@CourseDetail')->name("CourseDetail");
        //修改课程 
        Route::post('CourseEdit','MyController@CourseEdit')->name("CourseEdit");
        //删除课程 
        Route::delete('CourseDelete','MyController@CourseDelete')->name("CourseDelete");
        //微信JSSDK 
        Route::any('WxConfig','MyController@WxConfig')->name("WxConfig");
        //测试 
        // Route::any('uploadspic','MyController@uploadspic')->name("uploadspic");

    });


   Route::group(['prefix' => 'Pay','namespace' =>'Pay\Controllers'],function(){
       // 支付宝异步回调 
       Route::any('AliPayNotify','PayController@AliPayNotify')->name("AliPayNotify");
       // 支付宝同步回调
       Route::any('AliPayReturn','PayController@AliPayReturn')->name("AliPayReturn");
       // 支付宝下单
       Route::any('AliPayWeb','PayController@AliPayWeb')->name("AliPayWeb");
       // 微信下单
       Route::any('WxPayJsApi','PayController@WxPayJsApi')->name("WxPayJsApi");
       // 微信异步回调 
       Route::any('WxPayNotify','PayController@WxPayNotify')->name("WxPayNotify");


   });



});

