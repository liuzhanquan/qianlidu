<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"D:\phpstudy\WWW\lanHu\application/manage\view\agentcard\card_view.html";i:1570671040;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570862656;}*/ ?>
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
	<div class="agent-level"><h3>会员卡信息</h3></div>
	<dl class="add-block-li">
		<dt>卡号：</dt>
		<dd><?php echo $info['card_num']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>密码：</dt>
		<dd><?php echo $info['password']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>卡片类型：</dt>
		<dd><?php echo $info['card_type']==1?'虚拟卡' : '实体卡'; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>是否使用：</dt>
		<dd><?php echo !empty($info['card_state'])?'已使用':'未使用'; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>卡片样式：</dt>
		<dd><?php if(is_array($cardType) || $cardType instanceof \think\Collection || $cardType instanceof \think\Paginator): if( count($cardType)==0 ) : echo "" ;else: foreach($cardType as $key=>$item): if($item['id'] == $info['card_style']): ?>
				<?php echo $item['title']; endif; endforeach; endif; else: echo "" ;endif; ?>
		</dd>
	</dl>
	<?php if($info['card_style'] == 2): ?>
		<dl class="add-block-li">
			<dt>是否印刷：</dt>
			<dd><?php echo !empty($info['print_status'])?'已印刷':'未印刷'; ?></dd>
		</dl>
	<?php endif; ?>
	<dl class="add-block-li">
		<dt>当天试用密码次数：</dt>
		<dd><?php echo $info['up_num']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>使用开始时间：</dt>
		<dd><?php echo date("Y-m-d H:i:s",$info['start_time']); ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>使用结束时间：</dt>
		<dd><?php echo date("Y-m-d H:i:s",$info['stop_time']); ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>激活使用后增加会员时间：</dt>
		<dd><?php echo charge_time(time()+$info['charge_time']); ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>当天试用密码次数：</dt>
		<dd><?php echo $info['up_num']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>最近修改时间：</dt>
		<dd><?php echo $info['update_time']; ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>创建时间：</dt>
		<dd><?php echo date("Y-m-d H:i:s",$info['add_time']); ?></dd>
	</dl>
	<dl class="add-block-li">
		<dt>是否冻结：</dt>
		<dd>
			<?php if($info['state'] == 1): ?>
				已启用
			<?php elseif($info['state'] == 3): ?>
				已冻结
			<?php endif; ?>
		</dd>
	</dl>
	<?php if(!(empty($agent) || (($agent instanceof \think\Collection || $agent instanceof \think\Paginator ) && $agent->isEmpty()))): ?>
	<div class="agent-level"><h3>代理商信息</h3></div>
	<div class="agent-info nobor">
		<dl class="add-block-li">
			<dt>代理商名称：</dt>
			<dd><?php echo $agent['name']; ?></dd>
		</dl>
		<dl class="add-block-li">
			<dt>授权期限时间：</dt>
			<dd>
				<?php if($agent['level'] > 0): ?>
					<em><?php echo date('Y年m月d日',$agent['start_time']); ?></em> <em class="ml-10 mr-10">~</em>	 <em><?php echo date('Y年m月d日',$agent['stop_time']); ?></em>
				<?php else: ?>
					<span class="color_red">尚未授权</span>
				<?php endif; ?>
			</dd>
		</dl>
	</div>
	<?php endif; ?>
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