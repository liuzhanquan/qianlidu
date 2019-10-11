<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"D:\phpstudy\WWW\lanHu\application/manage\view\order\order_edit.html";i:1570783483;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570776449;}*/ ?>
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
  		  <li>修改</li>
		</ol>
	</div>
	<div class="sys-content">
		<form data-model="form-submit">
	        <dl>
	            <dt><i>*</i>订单编号：</dt>
	            <dd>
	            	<input type="text" class="form-controls" name="order_num" disabled="disabled" value="<?php echo $info['order_num']; ?>">
	            	<i>最多8个汉字或16个英文字符</i>
	            </dd>
	        </dl>
			<dl>
	            <dt><i>*</i>成人人数：</dt>
	            <dd>
	            	<input type="text" class="form-controls" name="aduly" value="<?php echo $info['aduly']; ?>">
	            </dd>
	        </dl>
			<dl>
	            <dt>小孩人数：</dt>
	            <dd>
	            	<input type="text" class="form-controls" name="baby" value="<?php echo $info['baby']; ?>">
	            </dd>
	        </dl>
	        <dl>
	            <dt><i>*</i>详细地址：</dt>
	            <dd>
	            	<input type="text" class="form-controls" name="addr_list" value="<?php echo $info['addr_list']; ?>">
	            </dd>
	        </dl>
			<dl>
				<dt>机票：</dt>
				<dd>
					<input type="text" class="form-controls" name="plane"  value="<?php echo $info['plane']; ?>">
				</dd>
			</dl>
			<dl>
				<dt>接机：</dt>
				<dd>
					<input type="text" class="form-controls" name="car" value="<?php echo $info['car']; ?>">
				</dd>
			</dl>
			<dl>
				<dt>管家：</dt>
				<dd>
					<input type="text" class="form-controls" name="butlers" value="<?php echo $info['butlers']; ?>">
				</dd>
			</dl>
	        <dl>
	            <dt><i>*</i>出行时间：</dt>
				
	            <dd style="">
	            	<div class="search-item" style="position:relative;height:20px;">
						<input type="text" class="form-control" name="start_time" style="width: 150px;float:left;position:absolute;left:0px;background:#fff;" value="<?php if($info['id'] != 0): ?><?php echo date("Y-m-d H:i:s",$info['start_time']); else: ?><?php echo date('Y-m-d H:i:s',time()); endif; ?>" data-model="form-time" readonly="">
					</div>
	            </dd>
	        </dl>
			
			<dl>
	            <dt><i>*</i>下单时间：</dt>
				
	            <dd style="">
	            	<div class="search-item" style="position:relative;height:20px;">
						<input type="text" class="form-control" name="add_time" style="width: 150px;float:left;position:absolute;left:0px;background:#fff;" value="<?php if($info['id'] != 0): ?><?php echo date("Y-m-d H:i:s",$info['add_time']); else: ?><?php echo date('Y-m-d H:i:s',time()); endif; ?>" data-model="form-time" readonly="">
					</div>
	            </dd>
	        </dl>
			<dl>
	            <dt>订单状态：</dt>
	            <dd>
	            	<select name="status" class="form-control" style="width: 200px;" >
						<option value="0" <?php if($info['status'] == 0): ?>selected="selected"<?php endif; ?>>未审核</option>
						<option value="1" <?php if($info['status'] == 1): ?>selected="selected"<?php endif; ?>>待出行</option>
						<option value="2" <?php if($info['status'] == 2): ?>selected="selected"<?php endif; ?>>已出行</option>
						<option value="3" <?php if($info['status'] == 3): ?>selected="selected"<?php endif; ?>>已完成</option>
						<option value="4" <?php if($info['status'] == 4): ?>selected="selected"<?php endif; ?>>未评价</option>
						<option value="5" <?php if($info['status'] == 5): ?>selected="selected"<?php endif; ?>>已评价</option>
						<option value="100" <?php if($info['status'] == 100): ?>selected="selected"<?php endif; ?>>取消</option>
					</select>
	            	<!-- <em>元</em>
	            	<i>（支持2位小数点）</i> -->
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
			
	        <div class="submit-btn">
	        	<input type="hidden" value="<?php echo $info['id']; ?>" name="id">
	        	<input type="hidden" value="<?php echo $info['uid']; ?>" name="uid">
	            <button class="btn btn-info">保存</button>
	        </div>
		</form>
	</div>
</div>
<script type="text/javascript" src="/static/admin/js/admin.js"></script>
<script type="text/javascript">
function call_back(data){
	console.log(data)
}
</script>
<script type="text/javascript">
$(function () {
	<?php  $cityId = isset($info['city']) ? $info['city'] : '-1';  $areaId = isset($info['area']) ? $info['area'] : '-1'; ?>
	GetComboboxTwo("ddlProvince", "ddlCity", "<?php echo url('getregion'); ?>", "id", "name", { levelId: 0 }, "id", "-1", "城市", "<?php echo $cityId; ?>", "-1");
	setTimeout(function () {
        GetComboboxTwo("ddlCity", "ddlArea", "<?php echo url('getregion'); ?>", "id", "name", { levelId: 0 }, "id", "-1", "加载中", "<?php echo $areaId; ?>", "-1");
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