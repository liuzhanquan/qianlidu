<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:64:"D:\phpstudy\WWW\lanHu\application/manage\view\agentcard\add.html";i:1570611202;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570500128;}*/ ?>
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
	            <dt>创建数量：</dt>
	            <dd>
	            	<input type="text" class="form-controls" name="num"  value="">
	            </dd>
	        </dl>
	        <dl>
	            <dt>卡片类型：</dt>
	            <dd>
	            	<label class="radio-inline"><input type="radio" class="card_type" name="card_type" checked value="1">虚拟卡</label>
	            	<label class="radio-inline"><input type="radio" class="card_type" name="card_type" value="2">实体卡</label>
	            	<!-- <em>元</em>
	            	<i>（支持2位小数点）</i> -->
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
			<!-- <dl class="print_box" style="display:none">
	            <dt>是否印刷：</dt>
	            <dd>
	            	<label class="radio-inline"><input type="radio" name="print_status"  value="1">已印刷</label>
	            	<label class="radio-inline"><input type="radio" name="print_status"  value="0">未印刷</label>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl> -->
	       <dl>
	            <dt>卡片样式：</dt>
	            <dd>
	            	
					<select name="card_style" class="form-control" style="width: 200px;" id="ddlAgentLevel_<?php echo $vo['id']; ?>">
   34                      <option value="0">无</option>
   35                      <?php if(is_array($cardType) || $cardType instanceof \think\Collection || $cardType instanceof \think\Paginator): if( count($cardType)==0 ) : echo "" ;else: foreach($cardType as $key=>$item): ?>
   36                      <option value="<?php echo $item['id']; ?>" ><?php echo $item['name']; ?></option>
   37                      <?php endforeach; endif; else: echo "" ;endif; ?>
   38:                 </select>
	            	<i></i>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
			
			<dl>
	            <dt>代理商：</dt>
	            <dd>
	            	
					<select name="gid" class="form-control" style="width: 200px;" id="ddlAgentLevel_<?php echo $vo['id']; ?>">
   34                      <option value="0">无</option>
   35                      <?php if(is_array($agent) || $agent instanceof \think\Collection || $agent instanceof \think\Paginator): if( count($agent)==0 ) : echo "" ;else: foreach($agent as $key=>$item): ?>
   36                      <option value="<?php echo $item['id']; ?>" ><?php echo $item['name']; ?></option>
   37                      <?php endforeach; endif; else: echo "" ;endif; ?>
   38:                 </select>
	            	<i></i>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
			<dl>
	            <dt><i>*</i>使用时间：</dt>
	            <dd style="">
	            	<div class="search-item" style="position:relative;height:20px;">
						<input type="text" class="form-control" name="start_time" style="width: 150px;float:left;position:absolute;left:0px;background-color:#fff;" value="" data-model="form-time" readonly=""><em style="float:left;margin-top:5px;position:absolute;left:160px;width:10px;">--</em>
						<input type="text" class="form-control" name="stop_time" style="width: 150px;float:left;position:absolute;left:190px;background-color:#fff;" value="" data-model="form-time" readonly="">
					</div>
	            </dd>
	        </dl>
			<dl>
	            <dt>激活使用后增加会员时间：</dt>
	            <dd style="position:relative;">
	            	<input type="text" class="form-controls" name="charge_time" value="">
					<select name="charge_type" class="form-control" style="width: 150px;float:left;position:absolute;left:200px;height:33px;background-color:#fff;top:0px;" id="">
						<option value="31104000" >年</option>
						<option value="2592000" >月</option>
						<option value="86400" >天</option>
						<option value="3600" >小时</option>
					</select>
	            </dd>
	        </dl>
			<dl>
	            <dt>是否启用：</dt>
	            <dd>
	            	<label class="radio-inline"><input type="radio" name="state" checked value="1">启用</label>
	            	<label class="radio-inline"><input type="radio" name="state"  value="0">冻结</label>
	            </dd>
	        </dl>
	        <div class="submit-btn">
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

	$('.card_type').click(function(){
		if( $(this).val() == 1 ){
			$('.print_box').css('display','none');
		}else if(  $(this).val() == 2 ){
			$('.print_box').css('display','block');
		}
	
	})


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