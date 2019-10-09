<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"D:\phpstudy\WWW\lanHu\application/manage\view\system\account.html";i:1557097922;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570500128;}*/ ?>
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
		</ol>
	</div>
	<div class="tab-title">
        <ul class="tab-title_con">
            <li class="on"><a class="tab_2_ul_li_a">子账号管理 </a></li>
            <li><a href="<?php echo url('role'); ?>" class="tab_2_ul_li_a">角色权限管理</a></li>
            <li><a href="<?php echo url('wechat_admin'); ?>" class="tab_2_ul_li_a">微信管理员管理</a></li>
        </ul>
	</div>
	<div class="right-content" data-model="table-bind">
		<div class="option-btn">
			<a href="<?php echo url('doaccount'); ?>" class="btn btn-success btn-green"><i class="iconfont">&#xe6c0;</i>添加子账号</a>
			<!-- <span>子账号登录地址：<?php echo urlDiy('/member/login'); ?></span>
			<a href="javascript:;" data-model="form-copy" data-txt="<?php echo urlDiy('/member/login'); ?>">复制</a> -->
		</div>
		<div class="option-search clearfix">
			<form method="get">
				<div class="search-item">
					<label>角色权限：</label>
					<select class="form-control" name="level" data-model="form-select" style="width: 200px;">
						<option value="">请选择</option>
						<?php foreach($search as $vo): ?>
						<option value="<?php echo $vo['gid']; ?>"><?php echo $vo['name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="search-item">
					<label>关键字：</label>
					<input type="text" class="form-control" name="key" placeholder="登陆账号、姓名、手机">
				</div>
				<div class="search-item">
					<button class="btn btn-info btn-ok">搜索</button>
				</div>
			</form>
		</div>
		<table class="table table-hover" data-table>
			<thead>
				<tr>
					<th class="text-center">ID</th>
					<th class="text-center">登录账号</th>
					<th class="text-center">角色权限</th>
					<th class="text-center">姓名</th>
					<th class="text-center">手机</th>
					<th class="text-center">备注</th>
					<th class="text-center">最后登录</th>
					<th class="text-center">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty())): ?>
				<tr>
					<td colspan="7">没有数据啦~~</td>
				</tr>
				<?php else: foreach($list as $vo): ?>
				<tr>
					<td><?php echo $vo['id']; ?></td>
					<td><?php echo $vo['username']; ?></td>
					<td><?php echo $vo['level_name']; ?></td>
					<td><?php echo $vo['name']; ?></td>
					<td><?php echo $vo['phone']; ?></td>
					<td><?php echo $vo['remark']; ?></td>
					<td><?php echo date("Y-m-d H:i:s",$vo['login_time']); ?></td>
					<td>
						<a href="<?php echo url('doaccount',['id'=>$vo['id']]); ?>">修改</a><em>-</em>
						<a href="javascript:void(0)" data-del data-id="<?php echo $vo['id']; ?>" data-table="admin">删除</a>
					</td>
				</tr>
				<?php endforeach; endif; ?>
			</tbody>
		</table>
		<div class="page"></div>
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