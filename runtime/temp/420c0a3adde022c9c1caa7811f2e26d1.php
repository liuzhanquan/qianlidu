<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:66:"D:\phpstudy\WWW\lanHu\application/manage\view\product\recycle.html";i:1570524134;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570500128;}*/ ?>
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
		  <li>回收站</li>
		</ol>
	</div>
	<!--  -->
	<div class="right-content" data-model="table-bind">
		<div class="option-search clearfix">
			<form method="get">
				<div class="search-item">
					<label>关键词：</label>
					<input type="text" class="form-control" name="key" value="" placeholder="产品编号、产品名称">
				</div>
				<div class="search-item">
					<button class="btn btn-info btn-ok">搜索</button>
					<button class="btn btn-default btn-cancle" name="exp" value="1">导出</button>
				</div>
			</form>
		</div>
		<table class="table table-hover" data-table>
			<thead>
				<tr>
					<th class="text-center">产品标题</th>
					<th class="text-center">产品名称</th>
					<th class="text-center" width="130px">产品图片</th>
					<th class="text-center">旅游时间</th>
					<th class="text-center">活动推广时间</th>
					<th class="text-center">产品状态</th>
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
					<td><?php echo $vo['title']; ?></td>
					<td><?php echo $vo['title_list']; ?></td>
					<td><img src="<?php echo $vo['photo']; ?>" height="30"></td>
					<td><?php echo date('Y-m-d H:i:s',$vo['start_time']); ?> - <?php echo date('Y-m-d H:i:s',$vo['stop_time']); ?></td>
					<td><?php echo date('Y-m-d H:i:s',$vo['show_time']); ?> - <?php echo date('Y-m-d H:i:s',$vo['hide_time']); ?></td>
					<td>
						<?php if($vo['status'] == 0): ?>
						已下架
						<?php else: ?>
						已上架
						<?php endif; ?>
					</td>
					<td>
						<a href="javascript:void(0)" data-del data-id="<?php echo $vo['id']; ?>" data-table="reback" data-tips="还原之后，产品将进入已下架列表，确定要还原吗？">还原</a><em>-</em>
						<a href="javascript:void(0)" data-del data-id="<?php echo $vo['id']; ?>" data-table="goods" data-tips="确定要彻底删除该产品吗？">彻底删除</a>
					</td>
				</tr>
				<?php endforeach; endif; ?>
			</tbody>
		</table>
		<div class="page"><?php echo $page; ?></div>
		<!--  -->
	</div>
	<!--  -->
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