<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"D:\phpstudy\WWW\lanHu\application/manage\view\user\user_view.html";i:1570787883;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570776449;}*/ ?>
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
	<div class="agent-level"><h3>用户信息</h3></div>
	<dl class="add-block-li">
		<dt>头像：</dt>
		<dd><div class="user_img " title="点击查看大图" style="cursor:pointer"><img src="<?php echo $info['avatar']; ?>"></div></dd>
	</dl>
	<dl class="add-block-li">
		<dt>用户名称：</dt>
		<dd><?php echo $info['nickname']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>openID：</dt>
		<dd><?php echo $info['openid']; ?></dd>
	</dl>
	
	<dl class="add-block-li">
		<dt>注册时间：</dt>
		<dd><?php echo date("Y-m-d H:i:s",$info['reg_time']); ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>是否启用：</dt>
		<dd><?php if($info['status'] == 0): ?>待审核<?php elseif($info['status'] == 1): ?>正常<?php elseif($info['status'] == 2): ?>待二次审核<?php elseif($info['status'] == 3): ?>已冻结<?php endif; ?></dd>
	</dl>
	<div class="agent-level"><h3>会员基本信息</h3></div>
	<div class="agent-info nobor">
		<dl class="add-block-li">
			<dt>手机号：</dt>
			<dd><?php echo $info['phone']; ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>身份证号：</dt>
			<dd><?php echo $info['card_id']; ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>会员到期时间：</dt>
			<dd>
				<?php if($info['charge_time'] > time()): ?>
				<em><?php echo date('Y-m-d H:i:s',$info['charge_time']); ?></em>
				<?php else: ?>
				<span class="color_red">尚未激活</span>
				<?php endif; ?>
			</dd>
		</dl>
		<?php if(!(empty($info['agent']) || (($info['agent'] instanceof \think\Collection || $info['agent'] instanceof \think\Paginator ) && $info['agent']->isEmpty()))): ?>
		<dl class="add-block-li">
			<dt>代理商时间：</dt>
			<dd><?php echo date("Y-m-d H:i:s",$info['agent']['start_time']); ?> - <?php echo date("Y-m-d H:i:s",$info['agent']['stop_time']); ?></dd>
		</dl>
		<?php endif; ?>
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