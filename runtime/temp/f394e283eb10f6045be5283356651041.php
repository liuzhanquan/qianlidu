<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"D:\phpstudy\WWW\lanHu\application/manage\view\order\order_view.html";i:1570789751;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570862656;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $config['sitename']; ?></title>
<link rel="stylesheet" type="text/css" href="/static/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/static/admin/css/rest.css">
<link rel="stylesheet" type="text/css" href="/static/admin/css/style.css">
<link rel="stylesheet" type="text/css" href="/static/admin/css/common.css">

<script type="text/javascript" src="/static/js/jquery.js"></script>
<script type="text/javascript" src="/static/js/do.js"></script>
<script type="text/javascript" src="/static/js/package.js" data-path="/static/js/" data-root="/<?php echo \think\Request::instance()->controller(); ?>/"  data-src="/static/admin/js/common"></script>
</head>
<body>
<div class="toper">
	<div class="container clearfix">
		<div class="logo fl">
			<a href="https://www.gz-guangmai.com/" target="_blank"><?php echo $config['sitename']; ?></a><em>|</em><a href="<?php echo url('/'); ?>">后台管理系统</a>
		</div>
		<div class="top-right fr">
			<p class="pinpai"><span class="icon-pinpai"></span><?php echo $config['sitename']; ?></p>
            <p class="zhanghao">
                <span class="icon-per"></span><?php echo $admin['username']; ?>
            </p>
            <p class="tuichu" data-model="form-confirm" data-url="<?php echo url('login/out'); ?>" data-id target="main">退出</p>
		</div>
	</div>
</div>
<div class="container">
	<div class="flex-row ifarme-panel">
		<div class="leftBar flex-grow-1" id="sidebar">
			<ul id="sidebar-menu" class="sidebar-menu">
                <?php foreach($topNav as $vo): if($vo['model'] == 'Index'): ?>
				<li class="sub-menu on" id="<?php echo $vo['model']; ?>">
                    <a href="<?php echo $vo['url']; ?>" class="">
                        <i class="icon-gakuang">
                        </i><span><?php echo $vo['name']; ?></span> <span class="arrow "></span>
                    </a>
                </li>
                <?php else: ?>
                <li class="sub-menu <?php if(\think\Request::instance()->controller() == $vo['model']): ?>on<?php endif; ?>" id="<?php echo $vo['model']; ?>">
                	<?php if(empty($vo['children']) || (($vo['children'] instanceof \think\Collection || $vo['children'] instanceof \think\Paginator ) && $vo['children']->isEmpty())): ?>
                    <a href="<?php echo $vo['url']; ?>" class=""><i class="icon-<?php echo $vo['icon']; ?>"></i><span><?php echo $vo['name']; ?></span> <span class="arrow"></span></a>
                	<?php else: ?>
                    <a href="javascript:void(0);" class=""><i class="icon-<?php echo $vo['icon']; ?>"></i><span><?php echo $vo['name']; ?></span> <span class="arrow"></span></a>
                	<ul class="sub">
                		<?php foreach($vo['children'] as $son): $action = request()->action(); ?>
                    	<li <?php if(in_array($action,$son['extend'])){ echo 'class="active"';} ?>><a href="<?php echo $son['url']; ?>"><?php echo $son['name']; ?></a></li>
                		<?php endforeach; ?>
                	</ul>
                	<?php endif; ?>
                </li>
                <?php endif; endforeach; ?>
			</ul>
		</div>
		<div class="rightContent flex-grow-1">
			<div class="right-panel">
	<div class="bar-title">
		<ol class="breadcrumb">
		  <li><a href="<?php echo url('/'); ?>">首页</a></li>
		  <?php foreach($url_path as $vo): if(empty($vo['url']) || (($vo['url'] instanceof \think\Collection || $vo['url'] instanceof \think\Paginator ) && $vo['url']->isEmpty())): ?>
		  <li><a href="javascript:;"><?php echo $vo['name']; ?></a></li>
		  <?php else: ?>
		  <li><a href="<?php echo url($vo['model'].'/'.$vo['url']); ?>"><?php echo $vo['name']; ?></a></li>
		  <?php endif; endforeach; ?>
  		  <li>查看</li>
		</ol>
	</div>
	<div class="agent-level"><h3>订单信息</h3></div>
	<dl class="add-block-li">
		<dt>订单号：</dt>
		<dd><?php echo $info['order_num']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>成人人数：</dt>
		<dd><?php echo $info['aduly']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>小孩人数：</dt>
		<dd><?php echo $info['baby']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>详细地址：</dt>
		<dd><?php echo $info['addr_list']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>机票：</dt>
		<dd><?php echo $info['plane']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>接机：</dt>
		<dd><?php echo $info['car']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>管家：</dt>
		<dd><?php echo $info['butlers']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>出行时间：</dt>
		<dd><?php echo date("Y-m-d H:i:s",$info['start_time']); ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>下单时间：</dt>
		<dd><?php echo date("Y-m-d H:i:s",$info['add_time']); ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>订单状态：</dt>
		<dd><?php if($info['status'] == 0): ?>
			<font color="blue">未审核</font>
			<?php elseif($info['status'] == 1): ?>
			待出行
			<?php elseif($info['status'] == 2): ?>
			已出行
			<?php elseif($info['status'] == 3): ?>
			已完成
			<?php elseif($info['status'] == 4): ?>
			未评论
			<?php elseif($info['status'] == 5): ?>
			已评论
			<?php elseif($info['status'] == 100): ?>
			<font color="red">取消</font>
			<?php endif; ?>
		</dd>
		</dl>
		<div class="agent-level"><h3>用户信息</h3></div>
		<dl class="add-block-li">
			<dt>头像：</dt>
			<dd><div class="user_img " title="点击查看大图" style="cursor:pointer"><img src="<?php echo $info['user']['avatar']; ?>"></div></dd>
		</dl>
		<dl class="add-block-li">
			<dt>微信名称：</dt>
			<dd><?php echo $info['user']['nickname']; ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>手机号：</dt>
			<dd><?php echo $info['user']['phone']; ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>身份证号：</dt>
			<dd><?php echo $info['user']['card_id']; ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>上级代理：</dt>
			<dd><?php echo get_user_parent($info['user']['parent_id'])['nickname']; ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>会员到期时间：</dt>
			<dd><?php echo date("Y-m-d H:i:s",$info['user']['charge_time']); ?></dd>
		</dl>
	<div class="agent-level"><h3>旅游路线信息</h3></div>
	<div class="agent-info nobor">
		
		<dl class="add-block-li">
			<dt>标题：</dt>
			<dd><?php echo $info['goods']['title']; ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>副标题：</dt>
			<dd><?php echo $info['goods']['title_list']; ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>出行时间区间：</dt>
			<dd><?php echo date("Y-m-d H:i:s",$info['goods']['start_time']); ?> - <?php echo date("Y-m-d H:i:s",$info['goods']['stop_time']); ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>路线总花费时间：</dt>
			<dd><?php echo $info['goods']['total_time']; ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>代理商：</dt>
			<dd><?php echo get_agent($info['goods']['agent'])['name']; ?></dd>
		</dl>
	</div>
</div>
		</div>
	</div>
</div>
<div class="page-footer">
	<p class="md-copyright__info"><?php echo $config['copyright']; ?><span><?php echo $config['copyright_num']; ?></span></p>
	<p class="md-copyright__support"><?php echo $config['copyright_technology']; ?></p>
</div>
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
</body>
</html>