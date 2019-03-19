<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//支付
Route::group(['namespace' =>'Pay\Controllers'],function(){
    Route::get('WxPayJs','PayController@WxPayJs')->name("WxPayJs");
    Route::any('WxPayNotify','PayController@WxPayNotify')->name("WxPayNotify");
});
Route::group(['namespace' =>'Manage\Controllers'],function(){
    Route::get('login','LoginViewController@login')->name("login");
    Route::post('login','LoginController@login')->name("login");
    Route::post('logout','LoginController@logout')->name("logout");
    Route::get('unauthorized','LoginViewController@unauthorized')->name("unauthorized");
});
Route::group(['middleware' =>'auth.admin'], function () {
    Route::group(['namespace' =>'Publics'],function(){
        Route::put('uploadImg','FileUploadController@imageUpload')->name("uploadImg");
    });
});
Route::group(['middleware' =>['auth.admin','permissions.admin']], function () {
    Route::group(['namespace' =>'Index\Controllers'],function(){
        Route::get('/','IndexController@index')->name("index");
        Route::post('/axis', 'IndexController@axis');

    });
    //用户管理
    Route::group(['prefix'=>'users','namespace' =>'User\Controllers'],function(){
        Route::get('/','UserController@index')->name("index");
        Route::get('lists','UserController@getLists')->name("lists");
        Route::post('userstatus','UserController@userStatus')->name("userstatus");
        Route::get('view/{id}','UserController@view')->name("view");
        Route::get('albumview/{id}','UserController@albumview')->name("albumview");
        Route::get('albumlists/{id}','UserController@getalbumLists')->name("albumlists");
        Route::get('albumcourselists/{id}','UserController@getalbumcourseLists')->name("albumcourselists");
        Route::get('albumcoursecommentlists/{id}','UserController@getalbumcoursecommentLists')->name("albumcoursecommentlists");
        Route::get('albumviewdetail/{id}','UserController@albumviewdetail')->name("albumviewdetail");
        Route::get('albumcourseviewdetail/{id}','UserController@albumcourseviewdetail')->name("albumcourseviewdetail");
        Route::get('albumcourseindex/{id}','UserController@albumcourseindex')->name("albumcourseindex");
        Route::delete('delete','UserController@delete')->name("delete");
        Route::delete('coursedelete','UserController@coursedelete')->name("coursedelete");
        Route::delete('coursecommentdelete','UserController@coursecommentdelete')->name("coursecommentdelete");
    });

    //实名认证管理
    Route::group(['prefix'=>'certification','namespace' =>'Certification\Controllers'],function(){
        Route::get('/','CertificationController@index')->name("index");
        Route::get('lists','CertificationController@getLists')->name("lists");
        Route::post('upstatus','CertificationController@upStatus')->name("upstatus");
        Route::get('view/{id}','CertificationController@view')->name("view");
    });


    //系统设置
    Route::group(['namespace' =>'System\Controllers'],function(){
        Route::get('bases','SettingController@base')->name("bases");
        Route::get('bases/setregion','SettingController@baseSetRegion');
        Route::put('bases','SettingController@saveBase')->name("bases");
        Route::put('bases/upload','SettingController@upload');
    });
    //菜单管理
    Route::group(['prefix'=>'menus','namespace' =>'Menu\Controllers'],function(){
        Route::get('/','MenuViewController@index')->name("index");
        Route::get('add','MenuController@add')->name("add");
        Route::post('add','MenuController@store')->name("add");
        Route::put('status','MenuController@menuStatus')->name("status");
        Route::put('edit','MenuController@menuUpdate')->name("edit");
        Route::put('sort','MenuController@sortUpdate')->name("sort");
        Route::delete('delete','MenuController@menuDelete')->name("delete");
        //菜单下操作管理
        Route::get('action/{menuid}','MenuViewController@action')->name("action");
        Route::post('actionAdd','MenuController@addAction')->name("addaction");
        Route::delete('actionDel','MenuController@deleteAction')->name("deleteaction");
    });
    //后台管理员管理
    Route::group(['prefix'=>'admins','namespace'=>'Manage\Controllers'],function(){
        Route::get('/','AdminViewController@index')->name("index");
        Route::get('add','AdminViewController@add')->name("add");
        Route::get('edit/{id}','AdminViewController@edit')->name("edit");
        Route::get('repass','AdminViewController@repass')->name("repass");
        Route::get('profile','AdminViewController@profile')->name("profile");
        Route::post('repass','AdminController@setpass')->name("repasss");
        Route::post('/','AdminController@store')->name("index");
        Route::delete('del','AdminController@adminDelete')->name("delete");
        Route::put('edit/status','AdminController@adminStatus')->name("status");
        Route::put('edit','AdminController@update')->name("update");
        Route::put('upload','AdminController@uploadPortrait')->name("upload");
    });
    //角色管理
    Route::group(['prefix'=>'roles','namespace' =>'Manage\Controllers'],function(){
        Route::get('/','RoleViewController@index')->name("index");
        Route::get('auth/{id}','RoleViewController@roleAuth')->name("auth");
        Route::post('add','RoleController@store')->name("index");
        Route::put('edit','RoleController@roleUpdate')->name("edit");
        Route::delete('delete','RoleController@roleDelete')->name("delete");
        Route::put('editauth','RoleController@editRoleAuth')->name("editauth");
    });
    //单面管理
    Route::group(['prefix'=>'pages','namespace' =>'Page\Controllers'],function(){
        Route::get('/','PageViewController@index')->name("index");
        Route::get('edit/{id}','PageViewController@edit')->name("edit");
        Route::get('view/{id}','PageViewController@view')->name("view");
        Route::put('update','PageController@update')->name("update");
        Route::get('lists','PageController@lists')->name("lists");
    });
    //系统日志
    Route::group(['prefix'=>'logs','namespace' =>'Manage\Controllers'],function(){
        Route::get('/','LogViewController@index')->name("index");
        Route::get('lists','LogController@lists')->name("lists");
    });
    //插件管理
    Route::group(['prefix'=>'plugs','namespace' =>'Plug\Controllers'],function(){
        Route::get('/','PlugViewController@index')->name("index");
        Route::get('install','PlugController@install')->name("install");
        Route::get('installedLists','PlugController@installedLists')->name("installedLists");
        Route::get('details/{id}','PlugViewController@details')->name("details");
        Route::get('view/{id}','PlugViewController@view')->name("view");
    });
    //api请求日志管理
    Route::group(['prefix'=>'apilogs','namespace' =>'ApiLog\Controllers'],function(){
        Route::get('/','ApiLogViewController@index')->name("index");
        Route::get('install','PlugController@install')->name("install");
        Route::get('lists','ApiLogController@lists')->name("lists");
    });
    //异常监控
    Route::group(['prefix'=>'abnormal','namespace'=>'Abnormal\Controllers'],function(){
        Route::get('/','AbnormalController@index')->name("index");
        Route::get('lists','AbnormalController@getLists')->name("lists");
        Route::get('call','AbnormalController@call')->name("call");
    });
    //模块创建
    Route::group(['prefix'=>'create','namespace'=>'Create\Controllers'],function(){
        Route::get('/','CreateController@index')->name("index");
        Route::post('/','CreateController@create')->name("index");
    });

    //智慧塔管理
    Route::group(['prefix'=>'gate','namespace' =>'Gate\Controllers'],function(){
        Route::get('/','GateController@index')->name("index");
        Route::get('lists','GateController@getLists')->name("lists");
        Route::get('gateview/{id}','GateController@gateview')->name("gateview");
        Route::post('update','GateController@update')->name("update");
        Route::get('addgate','GateController@addgate')->name("addgate");
        Route::post('add','GateController@add')->name("add");
        Route::get('courselist','GateController@courselist')->name("courselist");
        Route::get('getcourselist','GateController@getcourselist')->name("getcourselist");
        Route::get('rewordlist','GateController@rewordlist')->name("rewordlist");
        Route::get('getrewordlist','GateController@getrewordlist')->name("getrewordlist");

        Route::post('uploadspic','GateController@uploadspic')->name("uploadspic");
        Route::post('uploadsvideo','GateController@uploadsvideo')->name("uploadsvideo");

        Route::get('iscreatesubject','GateController@isCreateSubject')->name("iscreatesubject"); //获取关卡中是否有题目

    });


    //关卡弹窗管理
    Route::group(['prefix'=>'gatealert','namespace' =>'Gatealert\Controllers'],function(){
        Route::get('/','GatealertController@index')->name("index");
        Route::get('lists','GatealertController@getLists')->name("lists");
        Route::get('editgatealert/{id}','GatealertController@editGateAlert')->name("editgatealert");
        Route::post('updategatealert','GatealertController@updateGateAlert')->name("updategatealert");
        Route::get('addgatealert','GatealertController@addGateAlert')->name("addgatealert");
        Route::post('addcontent','GatealertController@addContent')->name("addcontent");
        Route::delete('deletegatealert','GatealertController@deleteGateAlert')->name("deletegatealert");
    });


    //关卡题目管理
    Route::group(['prefix'=>'subject','namespace' =>'Subject\Controllers'],function(){
        Route::get('showsubject/{id}','SubjectController@index')->name("showsubject");
        Route::get('lists','SubjectController@getLists')->name("lists");
        Route::get('editsubjectview/{id}','SubjectController@editSubjectView')->name("editsubjectview");
        Route::post('editgatesubject','SubjectController@editGateSubject')->name("editgatesubject");
        Route::get('addsubject/{id}','SubjectController@addSubject')->name("addsubject");
        Route::post('addgatesubject','SubjectController@addGateSubject')->name("addgatesubject");
        Route::delete('deletesubject','SubjectController@deleteSubject')->name("deletesubject");
    });


    //动态广告弹窗
    Route::group(['prefix'=>'advert','namespace' =>'Advert\Controllers'],function(){
        Route::get('/','AdvertController@index')->name("index");
        Route::get('getadvert','AdvertController@getAdvert')->name("getadvert");
        Route::get('editadvert/{id}','AdvertController@editAdvert')->name("editadvert");
        Route::post('updateadvert','AdvertController@updateAdvert')->name("updateadvert");
        Route::get('disableadvert','AdvertController@disableAdvert')->name("disableadvert");
    });


    //学学乐启动页
    Route::group(['prefix'=>'startup','namespace' =>'Startup\Controllers'],function(){
        Route::get('/','StartupController@index')->name("index");
        Route::post('updatestartup','StartupController@updateStartup')->name("updatestartup");
        Route::get('disablestartup','StartupController@disableStartup')->name("disablestartup");
    });


    //平台内容
    Route::group(['prefix'=>'album','namespace' =>'Album\Controllers'],function(){
        //专辑
        Route::get('/','AlbumController@albumview')->name("albumview");//专辑主页列表
        Route::get('albumlists','AlbumController@getalbumLists')->name("albumlists");
        Route::get('albumviewdetail/{id}','AlbumController@albumviewdetail')->name("albumviewdetail");//专辑详情
        Route::delete('delete','AlbumController@delete')->name("delete");//专辑删除
        Route::post('update','AlbumController@update')->name("update");//专辑修改
        Route::get('addalbum','AlbumController@addalbum')->name("addalbum");//专辑添加
        Route::post('add','AlbumController@add')->name("add");
        //课程
        Route::get('albumcourselists/{id}','AlbumController@getalbumcourseLists')->name("albumcourselists");
        Route::get('albumcoursecommentlists/{id}','AlbumController@getalbumcoursecommentLists')->name("albumcoursecommentlists");
        Route::get('albumcourseviewdetail/{id}','AlbumController@albumcourseviewdetail')->name("albumcourseviewdetail");//课程详情
        Route::get('albumcourseindex/{id}','AlbumController@albumcourseindex')->name("albumcourseindex");//课程主页列表
        Route::delete('coursedelete','AlbumController@coursedelete')->name("coursedelete");//课程删除
        Route::delete('coursecommentdelete','AlbumController@coursecommentdelete')->name("coursecommentdelete");//评论删除
        Route::get('addalbumcourse/{id}','AlbumController@addalbumcourse')->name("addalbumcourse");//专辑添加
        Route::post('addcourse','AlbumController@addcourse')->name("addcourse");

        Route::post('uploadspic','AlbumController@uploadspic')->name("uploadspic");
    });

    //举报仲裁管理
    Route::group(['prefix'=>'reporting','namespace' =>'Reporting\Controllers'],function(){
        Route::get('/','ReportingController@index')->name("index");
        Route::get('lists','ReportingController@getLists')->name("lists");
        Route::post('upstatus','ReportingController@upStatus')->name("upstatus");
        //Route::delete('delete','ReportingController@delete')->name("delete");
        Route::get('checkview/{id}','ReportingController@checkview')->name("checkview");
        Route::get('recoveryview/{id}','ReportingController@recoveryview')->name("recoveryview");
    });

    //奖励内容管理
     Route::group(['prefix'=>'rewardcontent','namespace' =>'Rewardcontent\Controllers'],function(){
        Route::get('/','RewardcontentController@index')->name("index");
        Route::get('lists','RewardcontentController@getLists')->name("lists");
        Route::delete('delete','RewardcontentController@delete')->name("delete");
        //更新
        Route::get('contentview/{id}','RewardcontentController@contentview')->name("contentview");
        Route::get('voiceview/{id}','RewardcontentController@voiceview')->name("voiceview");
        Route::post('updatecontent','RewardcontentController@updateContent')->name("updatecontent");
        Route::post('updatevoice','RewardcontentController@updateVoice')->name("updatevoice");
        //新增
        Route::get('addcontentview','RewardcontentController@addcontentview')->name("addcontentview");
        Route::get('addvoiceview','RewardcontentController@addvoiceview')->name("addvoiceview");
        Route::post('addcontent','RewardcontentController@addcontent')->name("addcontent");
        Route::post('addvoice','RewardcontentController@addvoice')->name("addvoice");
        Route::put('upload','RewardcontentController@upload')->name("upload");
    });

    //财务中心
    Route::group(['namespace' =>'Financial\Controllers'],function(){
        Route::get('smartbean','FinancialController@smartbean')->name("smartbean");//智慧豆管理
        Route::get('putforward','FinancialController@putforward')->name("putforward");//提现管理
    });
    Route::group(['prefix'=>'recharge','namespace' =>'Financial\Controllers'],function(){
        Route::get('/','FinancialController@recharge')->name("recharge");//充值管理
        Route::get('rechargelists','FinancialController@getRechargeLists')->name("rechargelists");
        Route::get('rechargeexport','FinancialController@rechargeexport')->name("rechargeexport");
        //Route::get('excel','FinancialController@export')->name("excel");
    });
    Route::group(['prefix'=>'orders','namespace' =>'Financial\Controllers'],function(){
        Route::get('/','FinancialController@orders')->name("orders");//订单管理
        Route::get('orderslists','FinancialController@getOrdersLists')->name("orderslists");
        Route::get('orderexport','FinancialController@orderexport')->name("orderexport");
    });
    //广告中心
    Route::group(['prefix'=>'notice','namespace' =>'Advertisingcenter\Controllers'],function(){
        Route::get('/','AdvertisingcenterController@notice')->name("notice");//消息通知
        Route::get('noticelists','AdvertisingcenterController@getNoticeLists')->name("noticelists");
        Route::get('noticeview/{id}','AdvertisingcenterController@noticeview')->name("noticeview");
        Route::get('noticeaddview','AdvertisingcenterController@noticeaddview')->name("noticeaddview");
        Route::post('addnotice','AdvertisingcenterController@addnotice')->name("addnotice");
    });
    Route::group(['prefix'=>'advertisement','namespace' =>'Advertisingcenter\Controllers'],function(){
        Route::get('/','AdvertisingcenterController@advertisement')->name("advertisement");//广告管理
        Route::get('advertisementlists','AdvertisingcenterController@getAdvertisementLists')->name("advertisementlists");
        Route::get('advertisementview/{id}','AdvertisingcenterController@advertisementview')->name("advertisementview");
        Route::post('upstatus','AdvertisingcenterController@upStatus')->name("upstatus");
        Route::get('advertisementaddview','AdvertisingcenterController@advertisementaddview')->name("advertisementaddview");
        Route::post('addadvertisement','AdvertisingcenterController@addadvertisement')->name("addadvertisement");
        Route::put('upload','AdvertisingcenterController@upload')->name("upload");
        
    });
    //分析中心->订阅排行
    Route::group(['prefix'=>'subscribe','namespace' =>'Subscribe\Controllers'],function(){
        Route::get('/','SubscribeController@subscribe')->name("subscribe");
        Route::get('lists','SubscribeController@getLists')->name("lists");
    });
    //系统中心->签到奖励
    Route::group(['prefix'=>'signreward','namespace' =>'Signreward\Controllers'],function(){
        Route::get('/','SignrewardController@signreward')->name("signreward");
        Route::get('lists','SignrewardController@getLists')->name("lists");
        Route::delete('delete','SignrewardController@delete')->name("delete");
        Route::get('signrewardview/{id}','SignrewardController@signrewardview')->name("signrewardview");
        Route::post('update','SignrewardController@update')->name("update");
        Route::get('addsignreward','SignrewardController@addsignreward')->name("addsignreward");
        Route::post('add','SignrewardController@add')->name("add");
    });
    //系统中心->用户协议
    Route::group(['prefix'=>'userprotocol','namespace' =>'Userprotocol\Controllers'],function(){
        Route::get('/','UserprotocolController@userprotocol')->name("userprotocol");
        Route::post('update','UserprotocolController@update')->name("update");
    });
    //系统中心->国外区域
    Route::group(['prefix'=>'foreignregion','namespace' =>'Foreignregion\Controllers'],function(){
        Route::get('/','ForeignregionController@foreignregion')->name("foreignregion");
        Route::get('lists','ForeignregionController@getLists')->name("lists");
        Route::delete('delete','ForeignregionController@delete')->name("delete");
        Route::get('foreignregionview/{id}','ForeignregionController@foreignregionview')->name("foreignregionview");
        Route::post('update','ForeignregionController@update')->name("update");
        Route::get('addforeignregion','ForeignregionController@addforeignregion')->name("addforeignregion");
        Route::post('add','ForeignregionController@add')->name("add");
    });
    //系统中心->平台规则管理
    Route::group(['prefix'=>'rules','namespace' =>'Rules\Controllers'],function(){
        Route::get('/','RulesController@ruleindex')->name("ruleindex");
        Route::get('lists','RulesController@getLists')->name("lists");
        //分享奖励
        Route::get('sharingreward/{id}','RulesController@sharingreward')->name("sharingreward");
        Route::post('upsharingreward','RulesController@upsharingreward')->name("upsharingreward");
        //智慧豆打赏
        Route::get('wisdombeanreward/{id}','RulesController@wisdombeanreward')->name("wisdombeanreward");
        Route::post('upwisdombeanreward','RulesController@upwisdombeanreward')->name("upwisdombeanreward");
        //充值金额
        Route::get('rechargeamount/{id}','RulesController@rechargeamount')->name("rechargeamount");
        Route::post('uprechargeamount','RulesController@uprechargeamount')->name("uprechargeamount");
        //平台信息
        Route::get('platform','RulesController@platform')->name("platform");
        //平台信息更新
        Route::post('updateplatform','RulesController@updateplatform')->name("updateplatform");
    });
});