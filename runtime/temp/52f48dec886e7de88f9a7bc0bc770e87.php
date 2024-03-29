<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:52:"D:\phpstudy\WWW\lanHu\themes\default\user\index.html";i:1570686618;}*/ ?>
<!--{#include 'header.html'}-->
<script type="text/javascript" src="/static/js/package.js" data-path="/static/js/" data-root="/<?php echo \think\Request::instance()->controller(); ?>/"  data-src="/static/admin/js/common"></script>
<style type="text/css">
body{background: #efefef;}
</style>

<script type="text/javascript">
var webRoot = "<?php echo \think\Request::instance()->root(true); ?>/";
var webControl = "<?php echo \think\Request::instance()->controller(); ?>";
Do.ready('common',function(){ base.frame(); });
var Script = function () {
    jQuery('#sidebar .sub-menu > a').click(function () {
        var last = jQuery('.sub-menu.on', $('#sidebar'));
        last.removeClass("on");
        jQuery('.arrow', last).removeClass("open");
        jQuery('.sub', last).slideUp(200);
        var sub = jQuery(this).next();
        if (sub.is(":visible")) {
            jQuery('.arrow', jQuery(this)).removeClass("open");
            jQuery(this).parent().removeClass("on");
            sub.slideUp(200);
        } else {
            jQuery('.arrow', jQuery(this)).addClass("open");
            jQuery(this).parent().addClass("on");
            sub.slideDown(200);
        }
    });

}();
</script>

<button id="login">登录</button> 
<button id="loginOut">退出</button>
<br/>
<br/>
<br/>
<button id="index">首页数据</button> 
<button id="goodlist">单页详细信息数据</button> 
<button id="goodComment">全部评论</button>
<br/>
<br/>
<br/>
<button id="order">下单</button>
<br/>
<br/>
<br/>
订单状态：<span id="orderStatus"></span>
订单信息：<span id="orderStatus1"></span>
<br/>
<button id="orderAll">我的行程</button>
<button id="orderCustomer">客服确认</button> 
<button id="orderGo">已出发</button>
<button id="orderComfirm">已完成</button>
<button id="orderCancel">取消订单</button>
<button id="orderReset">恢复订单</button>
<br/>
<br/>
<br/>
<br/>
<button id="User">个人中心</button>
<br/>
<br/>
<input type="text" id="phone" placeholder="手机号" value="15118921185">
<input type="text" id="code" placeholder="验证码">
<input type="text" id="card_num" placeholder="激活卡号">
<input type="text" id="card_pass" placeholder="密码">
<br/>
<button id="userActivate">会员激活</button>
<button id="sms">发送验证码</button>
<button id="PhoneCmsFind">查看验证码</button>
<button id="getCard">查看激活卡</button>
<br/>
<br/>
<button id="agent">代理商中心</button>
<button id="teamList">我的客户</button>
<button id="cardA1">A类实体卡</button>
<button id="cardA2">A类虚拟卡</button>
<br>
<br>
<button id="cardA3">A类实体卡未激活</button>
<button id="cardA4">A类实体卡已激活</button>
<br/>
<dl>

	<dt><i>*</i>评论图片：</dt>
	<dd>
		<div class="flex-item-1 form-item-line">
			<div class="input-line input-flex">
				<div class="photo-group">
					<ul class="clearfix" id="images-thumbnails">
						<li>
							<div class="rc-upload">
							<a class="add-goods" href="javascript:;" data-model="upload-photo"
								data-img-list='false'
								data-img-name="photo_arr"
								data-img-warp="#images-thumbnails"
								data-id="imageUpload">+ 添加图片</a>
							</div>
							<script type="text/plain" id="imageUpload" style="display:block;" ></script>
						</li>
					</ul>
				</div>
				<div class="help-block">建议尺寸：800*800像素，最多上传10张</div>
			</div>
		</div>
	</dd>
</dl>
<dl>
	<dt><i>*</i>评论描述：</dt>
	<dd>
		<textarea name="content1" rows="5" id="content1" ></textarea>
		<div class="tip-alert"></div>
	</dd>
</dl>

<button id="commit">提交评论</button>
<br/>
<br/>
<br/>
<br/>
<br/>


<script type="text/javascript">
    var result = {};
    var num = 1;
    var http = "http://ceshi.pm.gzwehe.cn";
    //var http = "http://www.lh1.com";
    var orderId = 1;
    result['userInfo'] =  $.cookie('userInfo');
    /**
     * 登录接口
     * @param  openid
     * @return userInfo
     */
    $('#login').click(function(){
        result['openid'] = 'otKSH1D8jjZEzVNveDrlYfFo45PI';
        $.post(http+'<?php echo url("api/Login/login"); ?>',result,function(data){
            console.log(data);
            $.cookie('userInfo',data.data);
            window.location.reload();
        },'json');

    });

    /**
     * 退出登录 清除cookie
     */
    $('#loginOut').click(function(){
        $.cookie('userInfo','Null');
        window.location.reload();
    });


    /**
     * 商品-所有商品接口
     * @param  userInfo         cookie('userInfo')
     * @param  page             页码ID
     * @param  limit            每页条数ID
     * @return array
     */
    $('#index').click(function(){
        result['page'] = num;
        result['limit'] = 5;
        $.post(http+'<?php echo url("api/good/good"); ?>',result,function(data){
            console.log(data);
            num++;
        },'json');

    });
    /**
     * 商品-详细信息接口
     * @param  userInfo         cookie('userInfo')
     * @param  id               商品ID
     * @return array
     */
    $('#goodlist').click(function(){
        result['id'] = 1;
        $.post(http+'<?php echo url("api/good/goodsList"); ?>',result,function(data){

        },'json');

    });

    /**
     * 商品-所有评论接口
     * @param  userInfo         cookie('userInfo')
     * @param  id               商品ID
     * @param  page             页码ID
     * @param  limit            每页条数ID
     * @return array
     */
    $('#goodComment').click(function(){
        result['id'] = 1;
        result['page'] = num;
        result['limit'] = 5;
        $.post(http+'<?php echo url("api/goodsComment/index"); ?>',result,function(data){
            console.log(data);
            num++;
        },'json');

    });

    /**
     * 下单接口
     * @param  userInfo         cookie('userInfo')
     * @param  goods_id         商品ID
     * @param  aduly            成人数量ID
     * @param  baby             小孩数量ID
     * @param  addrList         详细地址ID
     * @param  start_time       预约出发时间ID
     * @return array
     */
    $('#order').click(function(){
        result['goods_id']  = 1;
        result['aduly']     = 2;
        result['baby']      = 2;
        result['addrList']  = '天河区天河村天河镇';
        result['start_time']= '2019-09-27';
        $.post(http+'<?php echo url("api/order/orderAdd"); ?>',result,function(data){
            console.log(data);

        },'json');

    });

    /**
     * 订单-获取所有订单
     * @param  userInfo         cookie('userInfo')
     * @param  page             页码ID
     * @param  limit            每页条数ID
     * @return array
     */
    $('#orderAll').click(function(){
        result['page'] = num;
        result['limit'] = 5;
        $.post(http+'<?php echo url("api/order/orderAll"); ?>',result,function(data){
            console.log(data);
            num++;
        },'json');

    });

    /**
     * 订单-客服确认
     * @param  userInfo      cookie('userInfo')
     * @param  orderId       订单ID
     * @return array
     */
    $('#orderCustomer').click(function(){
        result['orderId'] = orderId;
        $.post(http+'<?php echo url("api/Order/orderCustomer"); ?>',result,function(data){
            console.log(data);
        },'json');

    });

    /**
     * 订单-出发
     * @param  userInfo      cookie('userInfo')
     * @param  orderId       订单ID
     * @return array
     */
    $('#orderGo').click(function(){
        result['orderId'] = orderId;
        $.post(http+'<?php echo url("api/Order/orderGo"); ?>',result,function(data){
            console.log(data);
        },'json');

    });

    /**
     * 订单-完成
     * @param  userInfo      cookie('userInfo')
     * @param  orderId       订单ID
     * @return array
     */
    $('#orderComfirm').click(function(){
        result['orderId'] = orderId;
        $.post(http+'<?php echo url("api/Order/orderComfirm"); ?>',result,function(data){
            console.log(data);
        },'json');

    });

    /**
     * 订单-删除
     * @param  userInfo      cookie('userInfo')
     * @param  orderId       订单ID
     * @return array
     */
    $('#orderCancel').click(function(){
        result['orderId'] = orderId;
        $.post(http+'<?php echo url("api/Order/orderCancel"); ?>',result,function(data){
            console.log(data);
        },'json');

    });

    /**
     * 订单-恢复
     * @param  userInfo      cookie('userInfo')
     * @param  orderId       订单ID
     * @return array
     */
    $('#orderReset').click(function(){
        result['orderId'] = orderId;
        $.post(http+'<?php echo url("api/Order/orderReset"); ?>',result,function(data){
            console.log(data);
        },'json');

    });


    var statusStr = new Array('待确认', '待出行', '已出发 ','已完成','已取消');
    /**
     * 订单-详细信息接口
     * @param  userInfo      cookie('userInfo')
     * @param  orderId       订单ID
     * @return array
     */
    function orderStatus(){
        result['orderId'] = orderId;
        $.post(http+'<?php echo url("api/Order/orderGet"); ?>',result,function(data){
            if( data.data!=100 ){
                $('#orderStatus').html(statusStr[data.data.order.status]);
            }else{
                $('#orderStatus').html(statusStr[4]); 
            }

        },'json');
    }
    //获取订单信息
    orderStatus();



    /**
     * 个人中心接口
     * @param  userInfo      cookie('userInfo')
     * @return array
     */
    $('#User').click(function(){
        $.post(http+'<?php echo url("api/User/Index"); ?>',result,function(data){
            console.log(data);
        },'json');

    });

    /**
     * 用户激活接口
     * @param  userInfo      cookie('userInfo')
     * @return array
     */
    $('#userActivate').click(function(){
        result['phone']     = $('#phone').val();
        result['code']      = $('#code').val();
        result['card_num']  = $('#card_num').val();
        result['card_pass'] = $('#card_pass').val();

        $.post(http+'<?php echo url("api/User/userActivate"); ?>',result,function(data){
            console.log(data);
        },'json');

    });

    /**
     * 短信发送接口
     * @param  userInfo      cookie('userInfo')
     * @return array
     */
    $('#sms').click(function(){
        result['phone']     = $('#phone').val();
        $.post(http+'<?php echo url("api/User/usersms"); ?>',result,function(data){
            console.log(data);
        },'json');

    });

    /**
     * 短信查看接口
     * @param  userInfo      cookie('userInfo')
     * @return array
     */
    $('#PhoneCmsFind').click(function(){
        result['phone']     = $('#phone').val();

        $.post(http+'<?php echo url("api/User/PhoneCmsFind"); ?>',result,function(data){
            console.log(data);
        },'json');

    });


    /**
     * 激活卡查看接口
     * @param  userInfo      cookie('userInfo')
     * @return array
     */
    $('#getCard').click(function(){
        result['phone']     = $('#phone').val();

        $.post(http+'<?php echo url("api/User/getCard"); ?>',result,function(data){
            console.log(data);
        },'json');

    });

    /**
     * 我的代理信息接口
     * @param  userInfo      cookie('userInfo')
     * @return array
     */
    $('#agent').click(function(){
        result['limit'] = 5;
        $.post(http+'<?php echo url("api/Agent/Index"); ?>',result,function(data){
            console.log(data);
        },'json');

    });

    /**
     * 我的客户接口
     * @param userid      cookie('userInfo')
     * @return array
     */
    $('#teamList').click(function(){
        result['page'] = num;
        result['limit'] = 5;
        $.post(http+'<?php echo url("api/Agent/teamList"); ?>',result,function(data){
            console.log(data);
            num++;
        },'json');

    });

    /**
     * A类实体卡
     * @param type       卡片类型  1--实体卡  2--虚拟卡
     * @param style      卡片样式  1--A   2--B   3--C
     * @return array
     */
    $('#cardA1').click(function(){
        result['type'] = 1;
        result['style'] = 1;
        result['page'] = num;
        result['limit'] = 5;
        $.post(http+'<?php echo url("api/Agent/cardList"); ?>',result,function(data){
            console.log(data);
            num++;
        },'json');

    });
    /**
     * A类虚拟卡
     * @param type       卡片类型  1--实体卡  2--虚拟卡
     * @param style      卡片样式  1--A   2--B   3--C
     * @return array
     */
    $('#cardA2').click(function(){
        result['type'] = 2;
        result['style'] = 1;
        result['state'] = 0;
        result['page'] = num;
        result['limit'] = 5;
        $.post(http+'<?php echo url("api/Agent/cardList"); ?>',result,function(data){
            console.log(data);
            num++;
        },'json');

    });

    /**
     * A类虚拟卡 已激活
     * @param type       卡片类型  1--实体卡  2--虚拟卡
     * @param style      卡片样式  1--A   2--B   3--C
     * @param state      卡片状况  1已激活  0未激活
     * @return array
     */
    $('#cardA3').click(function(){
        result['type'] = 1;
        result['style'] = 1;
        result['state'] = 0;
        result['page'] = num;
        result['limit'] = 5;
        $.post(http+'<?php echo url("api/Agent/cardList"); ?>',result,function(data){
            console.log(data);
            num++;
        },'json');

    });

    /**
     * A类虚拟卡 已激活
     * @param type       卡片类型  1--实体卡  2--虚拟卡
     * @param style      卡片样式  1--A   2--B   3--C
     * @param state      卡片状况  1已激活  0未激活
     * @return array
     */
    $('#cardA4').click(function(){
        result['type'] = 1;
        result['style'] = 1;
        result['state'] = 1;
        result['page'] = num;
        result['limit'] = 5;
        $.post(http+'<?php echo url("api/Agent/cardList"); ?>',result,function(data){
            console.log(data);
            num++;
        },'json');

    });
	var photo_arr = [];
	/**
     * 评论提交
     * @param goods_id   路线id
     * @param photo      图片路径数组
     * @param content    评论内容
     * @param score    	 用户评分
     * @return array
     */
    $('#commit').click(function(){
		photo();
        result['goods_id']  = 1;
        result['photo'] = photo_arr;
        result['content'] = $('#content1').val();
        result['score'] = 0;
        $.post(http+'<?php echo url("api/goodsComment/add"); ?>',result,function(data){
            console.log(data);
            num++;
        },'json');

    });
	function photo(){
		photo_arr = [];
		$('.clearfix img').each(function(index){
			photo_arr.push($(this).attr('src'));
		});
		
	}

</script>
<!--{#include 'footer.html'}-->