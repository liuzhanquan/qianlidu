<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"D:\phpstudy\WWW\lanHu\application/manage\view\product\create.html";i:1570500010;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570500128;}*/ ?>
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
			<div class="fixed-content" id="page">
	<div class="panel-form" style="padding: 0;">
		<form data-model="form-submit">
			<div class="layout-panel">
				<div class="panel-header-once">
					<h3>基本信息</h3>
				</div>
				<div class="panel-form">
					<div class="flex">
						<div class="flex-item-1 form-item-line">
							<label class="must">商品名：</label>
							<div class="input-line input-xxlarge">
								<input type="text" class="input" name="title" datatype="*2-100" placeholder="长度为1-100位" value="<?php echo $goods['title']; ?>">
								<div class="help-block">商品名称必须填写，最多100个字</div>
							</div>
						</div>
					</div>
					<div class="flex">
						<div class="flex-item-1 form-item-line">
							<label>商品副标题：</label>
							<div class="input-line input-xxlarge">
								<textarea name="title_list" class="input" style="padding: 10px;height: 60px;" rows="3"><?php echo $goods['title_list']; ?></textarea>
								<div class="help-block">副标题的长度请控制在100字以内</div>
							</div>
						</div>
					</div>
					<div class="flex">
						<div class="flex-item-1 form-item-line">
							<label class="must">商品首图：</label>
							<div class="input-line input-xxlarge">
								<div class="upload-panel">
				            		<a href="javascript:;" data-model="form-upload" data-target="#images" data-preview="#uploadImage_2" class="wbtn"><i class="iconfont">&#xe74a;</i> 上传图片</a>
				            	</div>
				            	<input type="hidden" name="photo" value="<?php echo $goods['photo']; ?>" id="images" datatype="*">
				            	<div class="upload-prview">
				            		<img style="width:expression(this.width > 105 ? 105px : this.width)" src="<?php echo $goods['photo']; ?>" id="uploadImage_2">
				            	</div>

					            <div class="help-block">(推荐尺寸为600px*600px，大小不超过200k，支持jpeg、jpg、png、gif、jpeg格式)</div>
							</div>
						</div>
					</div>
					<div class="flex">
						<div class="flex-item-1 form-item-line">
							<label>商品图：</label>
							<div class="input-line input-flex">
								<div class="photo-group">
									<ul class="clearfix" id="images-thumbnails">
										<?php if(!(empty($photo) || (($photo instanceof \think\Collection || $photo instanceof \think\Paginator ) && $photo->isEmpty()))): foreach($photo as $vo): ?>
										<li><a href="<?php echo $vo; ?>" target="_blank"><img src="<?php echo $vo; ?>"></a><div class="info"><a class="del">x</a></div><input type="hidden" name="photo[]" value="<?php echo $vo; ?>"></li>
										<?php endforeach; endif; ?>
										<li>
											<div class="rc-upload">
											<a class="add-goods" href="javascript:;" data-model="upload-photo"
						                        data-img-list='false'
						                        data-img-name="photo"
						                        data-img-warp="#images-thumbnails"
						                        data-id="imageUpload">+ 添加图片</a>
											</div>
											<script type="text/plain" id="imageUpload" style="display:none;" ></script>
										</li>
									</ul>
								</div>
								<div class="help-block">建议尺寸：800*800像素，最多上传10张</div>
							</div>
						</div>
					</div>
					
					<div class="flex">
						<?php $state = isset($goods['status']) ? $goods['status'] : "1"; ?>
						<div class="flex-item-3 form-item-line">
							<label class="must">上架：</label>
							<div class="input-line input-flex">
								<label class="custom-checkbox"><input value="0" class="no-icon use-attr" type="radio" name="status" <?php if($state == 0): ?>checked<?php endif; ?> />否</label>
							</div>
							<div class="input-line input-flex">
								<label class="custom-checkbox"><input value="1" class="no-icon use-attr" type="radio" name="status" <?php if($state == 1): ?>checked<?php endif; ?> />上架</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="navbtn">
				<button class="wbtn">保存</button>
				<input type="hidden" name="id" value="<?php echo $goods['id']; ?>">
				<button type="reset" class="wbtn wbtn-border-dislabel">重置</button>
			</div>
		</form>
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