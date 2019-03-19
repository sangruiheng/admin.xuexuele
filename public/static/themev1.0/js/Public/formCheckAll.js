layui.use(['jquery','layer','form'], function() {
    var form = layui.form;
    $ = layui.jquery;
    //表单验证
    form.verify({
        empty:function(value,item){
            var checkName = $(item).data("vername")!="undefind" ? $(item).data("vername") : "";
            if(value==""){
                return checkName + "不能为空！";
            }
        }
        ,title: function(value, item){ //标题验证
            var checkName = $(item).data("vername");
            if(/^\d+\d+\d$/.test(value)){
                return checkName + '不能全为数字';
            }
        }
        ,username:function(value,item){
            if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                return '用户名不能有特殊字符';
            }
            if(/(^\_)|(\__)|(\_+$)/.test(value)){
                return '用户名首尾不能出现下划线\'_\'';
            }
            if(/^\d+\d+\d$/.test(value)){
                return '用户名不能全为数字';
            }
        }
        ,selected:function(value,item){
            var checkName = $(item).data("vername")!="undefind" ? $(item).data("vername") : "";
            if(value==""){
                return "请选择"+ checkName +"！";
            }
        }
        ,mobile:[
            /0?(13|14|15|18|17)[0-9]{9}/
            ,'手机号码格式不正确!'
        ]
        ,carnum:function(value){ //车牌号验证
            var preg = /^[京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领A-Z]{1}[A-Z]{1}[A-Z0-9]{4}[A-Z0-9挂学警港澳]{1}$/;
            if(value==""){
                return "车牌号码不能为空！";
            }else if(!preg.test(value)){
                return "车牌号码格式不正确！";
            }
        }
        ,price:function(value,item){
            var checkName = $(item).data("vername")!="undefind" ? $(item).data("vername") : "";
            var preg  = /[1-9]\d*|[1-9]\d*.\d{2}|0\.\d*[1-9]\d{2}/;
            if(!preg.test(value)){
                return checkName+"价格格式不正确！";
            }
        }
        ,number:function(value,item){
            var checkName = $(item).data("vername")!="undefind" ? $(item).data("vername") : "";
            if(isNaN(value)){
                return checkName + '必须为数字';
            }
        }
    });
});