layui.define(['jquery', 'form','laypage','table'], function(exports){
    /*
     * 封装常用js方法
     * @auth weishuo
     * @2018-08-03
     */
    form = layui.form;
    var proving = {
        name:function (res) {
            //名称 是中文名称
            reg = /^[\u4E00-\u9FA5]{2,4}$/;
            if(!reg.test(res)){
                return false;
            }
                return true;
        },
        sex:function (res) {
            if(!res == 3){
                return true;
            }
                return false;
        },
        phone:function (res) {
            reg = /(^1[3|4|5|7|8]\d{9}$)|(^09\d{8}$)/;
            if(!reg.test(res)){
                return false;
            }
            return true;

        },
        email:function (res) {
            reg = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
            if(!reg.test(res)){
                return false;
            }
            return true;
        },


    };
    exports('proving',proving);
});