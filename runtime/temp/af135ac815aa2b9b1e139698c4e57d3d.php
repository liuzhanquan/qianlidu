<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:63:"D:\phpstudy\WWW\lanHu\application/manage\view\agents\count.html";i:1570671294;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570500128;}*/ ?>
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
            <!-- <li <?php if($id == 2): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['id'=>'2']); ?>">待审核</a></li> -->
            <li <?php if($id == 3): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['id'=>'3']); ?>">已冻结</a></li>
        </ul>
    </div>
    <div class="right-content" data-model="table-bind">
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
					<th class="text-center" width="20%">代理商</th>
					<th class="text-center">虚拟卡数量</th>
					<th class="text-center">实体卡数量</th>
					
					<th class="text-center">操作</th>
				</tr>
			</thead>
			<?php if(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty())): ?>
				<tr>
					<td colspan="7" class="text-center">没有更多数据啦</td>
				</tr>
			<?php else: foreach($list as $vo): ?>
				<tr>
					<td style="vertical-align: top;">
						<div class="page-wexin-info">
                            <div class="goods-list">
                                <img id="img_806" class="img_806" src="<?php echo $vo['user_all']['avatar']; ?>" alt="头像" style="width:50px;height:50px;">
                                <input class="imgurl" value="" type="hidden">
                                <p><?php echo $vo['name']; ?></p>
                            </div>
                            <div class="info f-c" style="display: none;">
                                <div class="left f-c">
                                    <img src="<?php echo $vo['user_all']['avatar']; ?>" class="user_img" alt="头像">
                                </div>
                                <div class="right f-c">
                                	<p><?php echo $vo['user_all']['nickname']; ?></p>
                                	<p>身份证：<?php echo !empty($vo['user_all']['idcard'])?$vo['idcard'] : '未设置'; ?></p>
                                	<!-- 查询授权品牌 -->
                                </div>
                            </div>
                        </div>
					</td>
					
					<td class="text-center">
						<a href="<?php echo url('agentcard/index'); ?>?selkey=1&key=<?php echo $vo['user_all']['phone']; ?>&type=1&style=1">虚拟卡(A) <?php echo $vo['A1']; ?>
						</a>
						<br/>
						<a href="<?php echo url('agentcard/index'); ?>?selkey=1&key=<?php echo $vo['user_all']['phone']; ?>&type=1&style=2">虚拟卡(B) <?php echo $vo['B1']; ?>
						</a>
						<br/>
						<a href="<?php echo url('agentcard/index'); ?>?selkey=1&key=<?php echo $vo['user_all']['phone']; ?>&type=1&style=3">虚拟卡(C) <?php echo $vo['C1']; ?>
						</a>
						<br/>
					</td>
					<td class="text-center">
						<a href="<?php echo url('agentcard/index'); ?>?selkey=1&key=<?php echo $vo['user_all']['phone']; ?>&type=2&style=1">实体卡(A) <?php echo $vo['A2']; ?>
						</a>
						<br/>
						<a href="<?php echo url('agentcard/index'); ?>?selkey=1&key=<?php echo $vo['user_all']['phone']; ?>&type=2&style=2">实体卡(B) <?php echo $vo['B2']; ?>
						</a>
						<br/>
						<a href="<?php echo url('agentcard/index'); ?>?selkey=1&key=<?php echo $vo['user_all']['phone']; ?>&type=2&style=3">实体卡(C) <?php echo $vo['C2']; ?>
						</a>
						<br/>
					</td>
					<td class="text-center">
						<a href="<?php echo url('agentcard/index'); ?>?selkey=1&key=<?php echo $vo['user_all']['phone']; ?>">查看</a>

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