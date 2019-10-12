<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"D:\phpstudy\WWW\lanHu\application/manage\view\system\sysmenu.html";i:1570872076;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570862656;}*/ ?>
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
	<div class="right-content" data-model="table-bind">
		<div class="option-btn">
			<a href="<?php echo url('sysmenuedit',[0]); ?>" class="btn btn-success btn-green"><i class="iconfont">&#xe6c0;</i>添加主栏目</a>
		</div>
		<br/>
		<form data-model="form-submit" class="form form-horizontal form-diy">
			
			<?php foreach($topNav as $vo): ?>
			<table class="table table-diy table-rol">
				<thead>
					<tr>
						<th>
							<label class="checkbox-block" style="margin-left: 10px;"><?php echo $vo['name']; ?>
							</label>
							<a href="<?php echo url('sysmenuedit',[$vo['id']]); ?>" class="btn" style="padding:3px 6px;">[修改]</a>
							<a href="<?php echo url('sysmenuedit',[0,0,$vo['id']]); ?>" class="btn" style="padding:3px 6px;">[添加模块]</a>
							<a href="javascript:void(0)" onclick="sysmenudel(<?php echo $vo['id']; ?>,1)" class="btn" style="padding:3px 6px;">[删除]</a>
						</th>
					</tr>
				</thead>
				<tbody>
					
					<?php if(is_array($vo['children']) || $vo['children'] instanceof \think\Collection || $vo['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?>
					<tr>
						<td width="120" class="text-left">
							<div class="checkbox-inline selectHeadBox" style="width:30%;">
							<label>
								<?php echo $sub['name']; ?> 
								
							</label>
							<a href="<?php echo url('sysmenuedit',[$sub['id'],0,$vo['id']]); ?>" class="btn" style="padding:3px 6px;">[修改]</a>
							<a href="<?php echo url('sysmenuedit',[0,3,$sub['id']]); ?>" class="btn" style="padding:3px 6px;">[添加模块]</a>
							<a href="javascript:void(0)" onclick="sysmenudel(<?php echo $sub['id']; ?>,2)" class="btn" style="padding:3px 6px;">[删除]</a>
							</div>
							<div class="checkbox-inline selectHeadBox" style="width:60%;">
							<?php if(is_array($sub['extend']) || $sub['extend'] instanceof \think\Collection || $sub['extend'] instanceof \think\Paginator): $key = 0; $__LIST__ = $sub['extend'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ext): $mod = ($key % 2 );++$key;if($key > 1): ?>
								<div class="checkbox-inline" style="color:#666;">
									<label><?php echo get_power_parent($sub['id'],$sub['model'],$ext); ?> </label>
									<?php  $subId = get_power_parent($sub['id'],$sub['model'],$ext,'id');  ?>
									<a href="<?php echo url('sysmenuedit',[$subId,3,$sub['id']]); ?>" class="btn" style="padding:3px 3px;">[修改]</a>
									<a href="javascript:void(0)" onclick="sysmenudel(<?php echo $subId; ?>,3)" class="btn" style="padding:3px 3px;">[删除]</a>
								</div>
								
								
								
								<?php endif; endforeach; endif; else: echo "" ;endif; ?>
							</div>
						</td>
					</tr>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody>
			</table>
			<?php endforeach; ?>
			<div class="submit-btn">
	        	<input type="hidden" value="<?php echo $info['gid']; ?>" name="gid">
	        	<button class="btn btn-info">保存</button>
	        </div>
		</form>
	</div>
</div>

<script>
	$('.selectHeadBox').each(function(index){
		$(this).click(function(){
			//var a = $(this).children('input').eq(0).prop('checked');
			var a = $(this).find('input').eq(0).prop('checked');
			$(this).find('input').eq(1).prop('checked',a);
		
		});
	
	});
	
	function sysmenudel(id,type){
		var status = confirm('确定要删除吗');
		if( status ){
			var result = {};
			result['id'] = id;
			result['type'] = type;
			$.post('<?php echo url("sysmenuedel"); ?>',result,function(data){
				alert(data.msg);
				if(data.code == 1){
					window.location.reload();
				}
			},'json');
		}
	}
	
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