<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"D:\phpstudy\WWW\lanHu\application/manage\view\agents\user_edit.html";i:1570614562;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570500128;}*/ ?>
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
	            <dt><i>*</i>授权名称：</dt>
	            <dd>
	            	<input type="text" class="form-controls" name="name" datatype="*1-16" value="<?php echo $info['name']; ?>">
	            	<i>最多8个汉字或16个英文字符</i>
	            </dd>
	        </dl>
	        <dl>
	            <dt><i>*</i>手机号：</dt>
	            <dd>
	            	<input type="text" class="form-controls" name="phone" datatype="m" value="<?php echo $info['user_all']['phone']; ?>">
	            </dd>
	        </dl>
	        <dl>
	            <dt>代理商等级：</dt>
	            <dd>
	            	<input type="text" class="form-controls" name="level" value="<?php echo $info['level']; ?>">
	            </dd>
	            <i>（0 为未激活）</i>
	        </dl>
	        <dl>
	            <dt>是否商家：</dt>
	            <dd>
	            	<label class="radio-inline"><input type="radio" name="business" <?php if($info['business'] == 1): ?>checked<?php endif; ?> value="1">是</label>
	            	<label class="radio-inline"><input type="radio" name="business" <?php if($info['business'] == 0): ?>checked<?php endif; ?> value="0">否</label>
	            	<!-- <em>元</em>
	            	<i>（支持2位小数点）</i> -->
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
	        <dl>
	            <dt><i>*</i>代理期限时间：</dt>
	            <dd style="">
	            	<div class="search-item" style="position:relative;height:20px;">
						<input type="text" class="form-control" name="start_time" style="width: 150px;float:left;position:absolute;left:0px;background:#fff;" value="<?php if($info['id'] != 0): ?><?php echo date("Y-m-d H:i:s",$info['start_time']); else: ?><?php echo date('Y-m-d H:i:s',time()); endif; ?>" data-model="form-time" readonly=""><em style="float:left;margin-top:5px;position:absolute;left:160px;width:10px;">--</em>
						<input type="text" class="form-control" name="stop_time" style="width: 150px;float:left;position:absolute;left:190px;background:#fff;" value="<?php if($info['id'] != 0): ?><?php echo date("Y-m-d H:i:s",$info['stop_time']); else: ?><?php echo date('Y-m-d H:i:s',time()); endif; ?>"" data-model="form-time" readonly="">
					</div>
	            </dd>
	        </dl>
	        <dl>
	            <dt>身份证号码：</dt>
	            <dd>
	            	<input type="text" class="form-controls" name="card_num" value="<?php echo $info['card_num']; ?>">
	            </dd>
	        </dl>
	        <!-- <dl>
	            <dt><i>*</i>登录密码：</dt>
	            <dd>
	            	<?php if(empty($info['password']) || (($info['password'] instanceof \think\Collection || $info['password'] instanceof \think\Paginator ) && $info['password']->isEmpty())): ?>
	            	未设置 <a id="PassWordEdit" class="word ml-20 ml-10" style="cursor:pointer" data-model="dialogs-open" data-width="420px" data-height="280px" data-url="<?php echo url('api/setpasw',['id'=>$info['id']]); ?>">设置密码</a>
	            	<?php else: ?>
	            	已设置	<a id="PassWordEdit" class="word ml-20 ml-10" style="cursor:pointer" data-model="dialogs-open" data-width="420px" data-height="280px" data-url="<?php echo url('api/setpasw',['id'=>$info['id']]); ?>">修改密码</a>
	            	<?php endif; ?>
	            	<span class="layers_tips_blue" data-model="form-webtips" data-content="此密码设置后，代理可使用手机号码和该密码作为账号登录代理后台，请谨慎操作！" data-placement="top">
	                    <em class="newtips"></em>
	                </span>
	            </dd>
	        </dl> -->
	        <!-- <dl>
	            <dt>地区：</dt>
	            <dd>
	            	<select class="form-control" name="province" id="ddlProvince" data-model="form-select" style="width: 110px;">
						<option value="-1">省份</option>
						<?php foreach($region as $vo): ?>
						<option value="<?php echo $vo['id']; ?>" <?php if($info['province'] == $vo['id']): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
						<?php endforeach; ?>
					</select>
					<select class="form-control" name="city" id="ddlCity" data-model="form-select" style="width: 110px;">
						<option value="-1">城市</option>
					</select>
					<select class="form-control" name="area" id="ddlArea" data-model="form-select" style="width: 110px;">
						<option value="-1">区域</option>
					</select>
	            </dd>
	        </dl> -->
	        <!-- <dl>
	            <dt><i>*</i>收货地址：</dt>
	            <dd>
	            	<textarea class="form-controls" rows="5" name="address"><?php echo $info['address']; ?></textarea><i>最多50个字</i>
	            </dd>
	        </dl> -->
	        <!-- <dl>
	            <dt>身份证照片：</dt>
	            <dd>
	            	<div class="upload-panel">
	            		<a href="javascript:;" data-model="form-upload" data-target="#card_img" data-preview="#card_img_2" class="btn btn-ok"><i class="iconfont">&#xe74a;</i> 上传图片</a>
	            	</div>
	            	<input type="hidden" name="card_img" value="<?php echo $info['card_img']; ?>" id="card_img">
	            	<div class="upload-prview">
	            		<img style="width:expression(this.width > 105 ? 105px : this.width)" src="<?php echo $info['card_img']; ?>" id="card_img_2">
	            	</div>
	            </dd>
	        </dl> -->
	        <!-- <dl style="margin-top: 25px;">
	    		<dt>代理级别：</dt>
	    		<dd><?php echo $info['level']; ?> <a id="PassWordEdit" class="word ml-20 ml-10" style="cursor:pointer" data-model="dialogs-open" data-width="40%" data-height="580px" data-url="<?php echo url('api/setlevel',['id'=>$info['uid']]); ?>">修改级别</a></dd>
	    	</dl> -->
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