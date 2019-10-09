<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:66:"D:\phpstudy\WWW\lanHu\application/manage\view\agentcard\index.html";i:1570532662;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570500128;}*/ ?>
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
			<script type="text/javascript" src="/static/admin/js/admin.js"></script>
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

    <div class="tab-title" style="margin-top: 28px;">
        <ul class="tab-title_con">
            <li <?php if($id == 1): ?>class="on"<?php endif; ?>><a href="<?php echo url('index'); ?>">全部代理</a></li>
            <li <?php if($id == 2): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['id'=>'2']); ?>">待审核</a></li>
            <li <?php if($id == 3): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['id'=>'3']); ?>">已冻结</a></li>
        </ul>
    </div>
    <div class="right-content" data-model="table-bind">
		<div class="option-btn">
			<a href="<?php echo url('addagent'); ?>" class="btn btn-success btn-green"><i class="iconfont">&#xe6c0;</i>添加代理申请链接</a>
		</div>
    	<div class="option-search clearfix">
    		<form method="get">
				<div class="search-item">
					<select class="form-control" name="selkey" data-model="form-select" style="width: 150px;">
						<option value="1" <?php if($data['selkey'] == 1): ?>selected<?php endif; ?>>手机号</option>
						<option value="2" <?php if($data['selkey'] == 2): ?>selected<?php endif; ?>>授权名称</option>
						<option value="3" <?php if($data['selkey'] == 3): ?>selected<?php endif; ?>>身份证</option>
					</select>
					<input type="text" class="form-control" name="key" value="<?php echo $data['key']; ?>" style="width: 200px;">
				</div>
				<div class="search-item">
					<button class="btn btn-info btn-ok">搜索</button>
					<button class="btn btn-default btn-cancle" name="exp" value="1">导出</button>
				</div>
    		</form>
    	</div>
    	<!--  -->
    	<table class="table table-hover" data-table>
			<thead>
				<tr>
					<th class="text-center" width="20%">卡号</th>
					<th class="text-center">类型</th>
					<!-- <th class="text-center">代理级别</th> -->
					<th class="text-center">实体卡/虚拟卡</th>
					<th class="text-center">是否绑定代理商</th>
					<th class="text-center"> 期限时间 </th>
					<th class="text-center">状态</th>
					<th class="text-center">操作</th>
				</tr>
			</thead>
			<?php if(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty())): ?>
				<tr>
					<td colspan="7" class="text-center">没有更多数据啦</td>
				</tr>
			<?php else: foreach($list as $vo): ?>
				<tr>
					<td class="text-center"><?php echo $vo['card_num']; ?></td>
					<td class="text-center"><?php echo $vo['card_type']; ?></td>
					<td class="text-center"><?php echo $vo['card_state']; ?></td>
					<td class="text-center"><?php echo !empty($vo['user_all']['phone'])?$vo['user_all']['phone'] : '未设置'; ?></td>
					<td class="text-center"><?php echo !empty($vo['business'])?'是' : '否'; ?></td>
					<td class="text-center"><?php echo !empty($vo['start_time'])?date('Y-m-d H:i:s',$vo['start_time']):''; ?>
						<br>
					<?php echo !empty($vo['stop_time'])?date('Y-m-d H:i:s',$vo['stop_time']) : '未设置'; ?></td>
					<td class="text-center"><?php if($vo['status'] == 0): ?>待审核<?php elseif($vo['status'] == 1): ?>正常<?php elseif($vo['status'] == 2): ?>待二次审核<?php elseif($vo['status'] == 3): ?>已冻结<?php endif; ?></td>
					<td class="text-center">
						<a href="<?php echo url('douser',['id'=>$vo['id'],'type'=>'view']); ?>">查看</a><em>-</em>
						<?php if($vo['status'] == 0): ?>
						<a href="javascript:void(0)" data-model="dialogs-open" data-width="600px" data-height="560px" data-url="<?php echo url('auditing',['id'=>$vo['id']]); ?>">审核</a><br>
						<?php elseif($vo['status'] == 2): ?>
						<a href="javascript:void(0)" data-model="dialogs-open" data-width="600px" data-height="560px" data-url="<?php echo url('auditing',['id'=>$vo['id']]); ?>">审核</a><br>
						<?php elseif($vo['status'] == 1): ?>
						<a href="javascript:void(0)" data-confirm data-tips="您确认要冻结该代理吗？" data-url="<?php echo url('status'); ?>" data-val="3" data-id="<?php echo $vo['id']; ?>">冻结</a><br>
						<?php elseif($vo['status'] == 3): ?>
						<a href="javascript:void(0)" data-confirm data-tips="您确认要启用吗？" data-url="<?php echo url('status'); ?>" data-val="1" data-id="<?php echo $vo['id']; ?>">启用</a><br>
						<?php endif; ?>
						<!-- <a href="javascript:void(0)" data-model="dialogs-open" data-width="600px" data-height="560px" data-url="<?php echo url('option',['id'=>$vo['id']]); ?>" data-title="增加代理款项">充值</a><em>-</em>
						<a href="javascript:void(0)" data-model="dialogs-open" data-width="600px" data-height="560px" data-url="<?php echo url('option_s',['id'=>$vo['id']]); ?>">扣款</a><br> -->

						<a href="<?php echo url('douser',['id'=>$vo['id'],'type'=>'edit']); ?>">修改</a><em>-</em>
						<a href="javascript:void(0)" data-del data-table="user" data-id="<?php echo $vo['id']; ?>" >删除</a><br>
					</td>
				</tr>
				<?php endforeach; endif; ?>
			<tbody>
    	</table>
		<div class="page"><?php echo $page; ?></div>
    </div>
    <!--  -->
</div>
<script type="text/javascript">
$(function () {
	<?php  $cityId = isset($data['city']) ? $data['city'] : '-1';  $areaId = isset($data['area']) ? $data['area'] : '-1'; ?>
	GetComboboxTwo("ddlProvince", "ddlCity", "<?php echo url('getregion'); ?>", "id", "name", { levelId: 0 }, "id", "-1", "城市", "<?php echo $cityId; ?>", "-1");
	setTimeout(function () {
        GetComboboxTwo("ddlCity", "ddlArea", "<?php echo url('getregion'); ?>", "id", "name", { levelId: 0 }, "id", "-1", "区域", "<?php echo $areaId; ?>", "-1");
    },1000);
	
});
</script>
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