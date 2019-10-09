<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:62:"D:\phpstudy\WWW\lanHu\application/manage\view\system\role.html";i:1557097926;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570500128;}*/ ?>
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
  		  <li><a href="javascript:;">系统设置</a></li>
  		  <li><a href="<?php echo url('account'); ?>">子账号管理</a></li>
  		  <li>角色权限管理</li>
  		</ol>
	</div>
	<div class="tab-title">
        <ul class="tab-title_con">
            <li><a href="<?php echo url('account'); ?>" class="tab_2_ul_li_a">子账号管理 </a></li>
            <li class="on"><a class="tab_2_ul_li_a">角色权限管理</a></li>
        </ul>
	</div>
	<div class="right-content" data-model="table-bind">
		<div class="option-btn">
			<a href="<?php echo url('dorole'); ?>" class="btn btn-success btn-green"><i class="iconfont">&#xe6c0;</i>添加角色</a>
		</div>
		<div class="option-search clearfix"></div>
		<table class="table table-hover" data-table>
			<thead>
				<tr>
					<th class="text-center">ID</th>
					<th class="text-center">角色名称</th>
					<th class="text-center">关联子账号用户</th>
					<th class="text-center">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty())): else: foreach($list as $vo): ?>
				<tr>
					<td><?php echo $vo['gid']; ?></td>
					<td><?php echo $vo['name']; ?></td>
					<td>
						<?php $alist = db('admin')->where('role_id',$vo['gid'])->select(); foreach($alist as $vv): ?>
						<code><?php echo $vv['username']; ?></code>
						<?php endforeach; ?>
					</td>
					<td class="text-center">
						<?php if($vo['is_sys'] == 1): ?>
						<a>不可修改</a>
						<?php else: ?>
						<a href="<?php echo url('dorole',['id'=>$vo['gid']]); ?>">编辑</a><em>-</em>
						<a href="javascript:;" data-del data-id="<?php echo $vo['gid']; ?>" data-table="group">删除</a>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; endif; ?>
			</tbody>
		</table>
		<div class="pages"><?php echo $page; ?></div>
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