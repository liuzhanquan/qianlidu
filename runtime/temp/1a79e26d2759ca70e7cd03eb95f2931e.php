<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"D:\phpstudy\WWW\lanHu\application/manage\view\system\sysmenuedit.html";i:1570871859;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570862656;}*/ ?>
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
	<div class="sys-content">
		<form data-model="form-submit">
	        <dl>
	            <dt><i>*</i>菜单名称：</dt>
	            <dd>
	            	<input type="text" style="width: 270px" name="name" class="form-controls" datatype="*" value="<?php echo $info['name']; ?>">
	            	<i>最多10个中英文、数字字符</i>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
			<?php if($data['type'] == 3 || $id != 0): ?>
			<dl>
	            <dt>上级菜单：</dt>
	            <dd>
	            	
	                <?php foreach($list as $vo): if($info['parent'] == $vo['id'] || $data['pid'] == $vo['id']): ?>
							<?php echo $vo['name']; ?>
							<input type="hidden" name="parent" class="form-controls" datatype="*" value="<?php echo $data['pid']; ?>">
						<?php endif; endforeach; ?>
	                
	            </dd>
	        </dl>
			<?php endif; if($data['type'] >= 0 && $id == 0 && $data['type'] !=  3): ?>
	        <dl>
	            <dt><i>*</i>上级菜单：</dt>
	            <dd>
	            	<select name="parent" class="select" data-model="form-select" style="width: 262px!important" datatype="*">
	                    <option value="">请选择</option>
	                    <?php foreach($list as $vo): ?>
						<option value="<?php echo $vo['id']; ?>" <?php if($info['parent'] == $vo['id'] || $data['pid'] == $vo['id']): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
						<?php endforeach; ?>
	                </select>
	                <i>角色对应的权限请在【角色权限】中修改</i>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
			
			<?php endif; if($data['type'] == -1): ?>
	        <dl>
	            <dt>图标：</dt>
	            <dd>
					<ul id="sidebar-menu" class="sidebar-menu">
					<?php if(is_array($icon) || $icon instanceof \think\Collection || $icon instanceof \think\Paginator): if( count($icon)==0 ) : echo "" ;else: foreach($icon as $key=>$item): ?>
					<li style="display:inline;" class="iconbox <?php if($item['name'] == $info['icon']): ?>on<?php endif; ?>">
						<label class="radio-inline">
							<a href="javascript:void(0);" class="">
							<input type="radio" name="icon" <?php if($item['name'] == $info['icon']): ?>checked<?php endif; ?> value="<?php echo $item['name']; ?>">
								<i class="icon-<?php echo $item['name']; ?>"></i>
							</a>
						</label>
					</li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					
					</ul>
	            </dd>
	        </dl>
			
			<?php endif; ?>
	        <dl>
	            <dt>模块：</dt>
	            <dd>
	            	<input type="text" style="width: 270px" name="model" class="form-controls" value="<?php echo $info['model']; ?>">
	            </dd>
	        </dl>
			<dl>
	            <dt>方法：</dt>
	            <dd>
	            	<input type="text" style="width: 270px" name="action" class="form-controls" value="<?php echo $info['url']; ?>">
	            </dd>
	        </dl>
			<?php if($data['type'] != 3): ?>
			<dl>
	            <dt>排序：</dt>
	            <dd>
	            	<input type="text" style="width: 270px" name="sort" class="form-controls" value="<?php echo $info['sort']; ?>">
	            </dd>
	        </dl>
			<?php endif; ?>
	        <div class="submit-btn">
	        	<input type="hidden" value="<?php echo $info['id']; ?>" name="id">
	            <button class="btn btn-info">保存设置</button>
	        </div>
		</form>
	</div>
</div>
<script>
	$('.iconbox').each(function(index){
			
		$(this).click(function(){
			$('.iconbox').attr('class','iconbox');
			$(this).attr('class','iconbox on');
			$(this).find('input').prop('checked',true);
		});
		
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