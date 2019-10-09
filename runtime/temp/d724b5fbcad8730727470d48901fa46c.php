<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"D:\phpstudy\WWW\lanHu\application/manage\view\agents\user_view.html";i:1570531918;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570500128;}*/ ?>
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
	<div class="agent-level"><h3>代理信息</h3></div>
	<dl class="add-block-li">
		<dt>头像：</dt>
		<dd><div class="user_img " title="点击查看大图" style="cursor:pointer"><img src="<?php echo $info['user_all']['avatar']; ?>"></div></dd>
	</dl>
	<dl class="add-block-li">
		<dt>授权名称：</dt>
		<dd><?php echo $info['name']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>微信昵称：</dt>
		<dd><?php echo $info['user_all']['nickname']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>OpenId：</dt>
		<dd><?php echo $info['user_all']['openid']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>手机号：</dt>
		<dd><?php echo $info['user_all']['phone']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>身份证号：</dt>
		<dd><?php echo $info['card_num']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>商家：</dt>
		<dd><?php if($info['business'] == 1): ?>
			<span class="color_red">是</span>
			<?php else: ?>
			<span class="color_red">否</span>
			<?php endif; ?>
		</dd>
	</dl>
	<dl class="add-block-li">
		<dt>创建时间：</dt>
		<dd><?php echo date("Y-m-d H:i:s",$info['add_time']); ?></dd>
	</dl>
	<!-- <dl class="add-block-li">
		<dt>资料状态：</dt>
		<dd>
			<?php if($info['status'] == 0): ?>
			<span class="color_red">待审核</span>
			<?php elseif($info['status'] == 1): ?>
			<span class="color_green">正常</span>
			<?php elseif($info['status'] == 2): ?>
			<span class="color_red">已禁用</span>
			<?php endif; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>打款截图：</dt>
		<dd><?php if(empty($info['money_img']) || (($info['money_img'] instanceof \think\Collection || $info['money_img'] instanceof \think\Paginator ) && $info['money_img']->isEmpty())): else: ?> <img src="<?php echo $info['money_img']; ?>"> <?php endif; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>身份证照片：</dt>
		<dd><?php if(empty($info['card_img']) || (($info['card_img'] instanceof \think\Collection || $info['card_img'] instanceof \think\Paginator ) && $info['card_img']->isEmpty())): else: ?> <img src="<?php echo $info['card_img']; ?>"> <?php endif; ?></dd>
	</dl> -->
	<div class="agent-level"><h3>授权信息</h3></div>
	<div class="agent-info nobor">
		<!-- <dl class="add-block-li">
			<dt>代理级别：</dt>
			<dd><?php echo $info['level']; ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>授权编号：</dt>
			<dd><?php echo $config['brand_prefix']; ?><?php echo $info['id']; ?></dd>
		</dl> -->
		<dl class="add-block-li">
			<dt>授权期限时间：</dt>
			<dd>
				<?php if($info['status'] == 1): ?>
				<em><?php echo date('Y年m月d日',$info['start_time']); ?></em> <em class="ml-10 mr-10">~</em>	 <em><?php echo date('Y年m月d日',$info['stop_time']); ?></em>
				<?php else: ?>
				<span class="color_red">尚未授权</span>
				<?php endif; ?>
			</dd>
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