layui.use(['element','jquery','layer','form','laypage'], function() {
    var element = layui.element;
    var form    = layui.form;
    $ = layui.jquery;
    var layer = layui.layer;
    laypage = layui.laypage;

    //点击按钮
    $('body').on('click','.handle', function(){
        var othis = $(this), method = othis.data('method');
        active[method] ? active[method].call(this, othis) : '';
    });
    var active = {
        user_info:function(){
            var _id = $(this).data("id");
            innerData = '<iframe src="'+adminurl+'/agent/userInfo/'+_id+'?urltype=1" scrolling="no" frameborder="0" width="100%" onload="this.height=0;var fdh=(this.Document?this.Document.body.scrollHeight:this.contentDocument.body.offsetHeight);this.height=(fdh>700?fdh:700)"></iframe>';
            $('#admin-action').html(innerData);
            form.render();
        },
        agent_info:function(){
            var _id = $(this).data("id");
            innerData = '<iframe src="'+adminurl+'/agent/agentInfo/'+_id+'?urltype=1" scrolling="no" frameborder="0" width="100%" onload="this.height=0;var fdh=(this.Document?this.Document.body.scrollHeight:this.contentDocument.body.offsetHeight);this.height=(fdh>700?fdh:700)"></iframe>';
            $('#admin-action').html(innerData);
            form.render();
        },
        agent_lists:function(){
            var _id = $(this).data("id");
            innerData = '<iframe src="'+adminurl+'/agent/agentLists/'+_id+'?urltype=1" scrolling="no" frameborder="0" width="100%" onload="this.height=0;var fdh=(this.Document?this.Document.body.scrollHeight:this.contentDocument.body.offsetHeight);this.height=(fdh>700?fdh:700)"></iframe>';
            $('#admin-action').html(innerData);
            form.render();
        },
        user_lists:function(){
            var _id = $(this).data("id");
            innerData = '<iframe src="'+adminurl+'/agent/userLists/'+_id+'?urltype=1" scrolling="no" frameborder="0" width="100%" onload="this.height=0;var fdh=(this.Document?this.Document.body.scrollHeight:this.contentDocument.body.offsetHeight);this.height=(fdh>700?fdh:700)"></iframe>';
            $('#admin-action').html(innerData);
            form.render();
        },
        user_profit:function(){
            var _id = $(this).data("id");
            innerData = '<iframe src="'+adminurl+'/agent/userProfit/'+_id+'?urltype=1" scrolling="no" frameborder="0" width="100%" onload="this.height=0;var fdh=(this.Document?this.Document.body.scrollHeight:this.contentDocument.body.offsetHeight);this.height=(fdh>700?fdh:700)"></iframe>';
            $('#admin-action').html(innerData);
            form.render();
        },
    }
    getDataLists(_id); //初始化表格数据
    function getDataLists(_id){
        innerData = '<iframe src="'+adminurl+'/agent/userInfo/'+_id+'?urltype=1" scrolling="no" frameborder="0"  width="100%" onload="this.height=0;var fdh=(this.Document?this.Document.body.scrollHeight:this.contentDocument.body.offsetHeight);this.height=(fdh>700?fdh:700)"></iframe>';
        $('#admin-action').html(innerData);
        form.render();
    }
});