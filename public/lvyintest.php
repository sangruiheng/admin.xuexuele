<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="viewport" content="width=640, user-scalable=no">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-title" content="">
<title>录音test</title>
<style type="text/css">
@charset "utf-8";
*{ margin:0px; padding:0px; box-sizing:border-box; -webkit-tap-highlight-color:rgba(0,0,0,0);}
html{ max-width:640px; margin:0 auto;}
body{ font-family:"PingFangSC-Regular","sans-serif","STHeitiSC-Light","微软雅黑","Microsoft YaHei"; font-size:24px; line-height:1.5em; color:#000;
    -webkit-user-select:none; user-select:none;
    -webkit-touch-callout:none; touch-callout:none;
}
 
.start_btn , .play_btn , .send_btn{ width:250px; height:60px; line-height:60px; margin:20px auto; text-align:center; border:#eee solid 2px; cursor:pointer;}
.start_btn_in , .stop_btn{ color:#f00; border:#f00 solid 2px;}
</style>
</head>
 
<body>
 
<div class="start_btn">按住不放即可录音</div>
 
<div class="play_btn">点我播放</div>
 
<div class="send_btn">点我保存</div>

<button onclick="onclick11()" type="button">111</button>

 

 
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript">
	function onclick11(){
		$.ajax({
	   	url: 'https://xuexuele.huimor.com/api/Pay/WxPayJsApi',
	   	type: 'post',
	   	dataType: 'json',
	   	data: {uid: 90,order_money:6},
	   })
	   .done(function(data) {
	   	console.log(data);
	   	console.log("success");
	   })
	   .fail(function() {
	   	console.log("error");
	   })
	   .always(function() {
	   	console.log("complete");
	   });
	}
   
   
	
$.ajax({
	url: 'http://xuexuele.huimor.com/api/My/WxConfig',
	type: 'get',
	dataType: 'json',
	data: {url: 'http://xuexuele.huimor.com/lvyintest.php'},
})
.done(function(res) {
	console.log(res);

	wx.config({
		debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
		appId: res.data.appId, // 必填，公众号的唯一标识
		timestamp: res.data.timestamp, // 必填，生成签名的时间戳
		nonceStr:res.data.nonceStr, // 必填，生成签名的随机串
		signature:res.data.signature,// 必填，签名，见附录1
		jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','startRecord','stopRecord','onVoiceRecordEnd','playVoice','stopVoice','onVoicePlayEnd','uploadVoice'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	});
	 
	

})
.fail(function() {
	console.log("error");
})
.always(function() {
	console.log("complete");
});

wx.ready(function(){
		//返回音频的本地ID
		var localId;
		//返回音频的服务器端ID
		var serverId;
		//录音计时,小于指定秒数(minTime = 10)则设置用户未录音
		var startTime , endTime , minTime = 2;
		
		
		//***********************************//
		
		
		//开始录音
		$('.start_btn').on('touchstart',function(e){
			e.preventDefault();
			var $this = $(this);
			$this.addClass('start_btn_in');
			startTime = new Date().getTime();
			
			//开始录音
			wx.startRecord();
		});
		//***********************************//
		//停止录音接口
		$('.start_btn').on('touchend', function(){
			var $this = $(this);
			$this.removeClass('start_btn_in');
			
			//停止录音接口
			wx.stopRecord({
				success: function (res) {
					localId = res.localId;
				}
			});
			
			endTime = new Date().getTime();
			alert((endTime - startTime) / 1000);
			if((endTime - startTime) / 1000 < minTime){
				localId = '';
				alert('录音少于' + minTime +  '秒，录音失败，请重新录音');
			}
			
		});
		//监听录音自动停止接口
		// wx.onVoiceRecordEnd({
		// 	//录音时间超过一分钟没有停止的时候会执行 complete 回调
		// 	complete: function (res) {
		// 		localId = res.localId;
				
		// 		$('.start_btn').removeClass('start_btn_in');
		// 	}
		// });
		
		
		//***********************************//
		
		
		$('.play_btn').on('click',function(){
			if(!localId){
				alert('您还未录音，请录音后再点击播放');
				return;
			}
			var $this = $(this);
			if($this.hasClass('stop_btn')){
				$(this).removeClass('stop_btn').text('点我播放');
				
		//		//暂停播放接口
		//		wx.pauseVoice({
		//			//需要暂停的音频的本地ID，由 stopRecord 或 onVoiceRecordEnd 接口获得
		//			localId: localId
		//		});
		
				//停止播放接口
				wx.stopVoice({
					//需要停止的音频的本地ID，由 stopRecord 或 onVoiceRecordEnd 接口获得
					localId: localId
				});
			}else{
				$this.addClass('stop_btn').text('点我停止');
				
				//播放语音接口
				wx.playVoice({
					//需要播放的音频的本地ID，由 stopRecord 或 onVoiceRecordEnd 接口获得
					localId: localId
				});
			}
		});
		//监听语音播放完毕接口
		wx.onVoicePlayEnd({
			//需要下载的音频的服务器端ID，由uploadVoice接口获得
			serverId: localId,
			success: function (res) {
				$('.play_btn').removeClass('stop_btn').text('点我播放');
				
				//返回音频的本地ID
				//localId = res.localId;
			}
		});
		
		
		//***********************************//
		
		
		//上传语音接口
		$('.send_btn').on('click',function(){
			if(!localId){
				alert('您还未录音，请录音后再保存');
				return;
			}
			
			alert('上传语音,测试，并未提交保存');
			return;
			
			//上传语音接口
			wx.uploadVoice({
				//需要上传的音频的本地ID，由 stopRecord 或 onVoiceRecordEnd 接口获得
				localId: localId, 
				//默认为1，显示进度提示
				isShowProgressTips: 1,
				success: function (res) {
					//返回音频的服务器端ID
					serverId = res.serverId;
				}
			});
		});
		
	});
 
</script>
</body>
</html>
