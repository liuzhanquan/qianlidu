<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"D:\phpstudy\WWW\lanHu\application/manage\view\product\product_edit.html";i:1570780042;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570776449;}*/ ?>
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
		  <li><?php if(empty($info) || (($info instanceof \think\Collection || $info instanceof \think\Paginator ) && $info->isEmpty())): ?>添加产品<?php else: ?>编辑产品<?php endif; ?></li>
		</ol>
	</div>
	<div class="sys-content">
		<form data-model="form-submit">
	        <dl>
	            <dt><i>*</i>产品标题：</dt>
	            <dd>
	            	<input type="text" style="width: 260px" name="title" class="form-controls" datatype="*1-10" value="<?php echo $info['title']; ?>">
	            	<i>（最多20个字符）</i>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
	        <dl>
	            <dt><i>*</i>产品副标题：</dt>
	            <dd>
	            	<input type="text" style="width: 260px" name="title_list" class="form-controls" datatype="*1-40" value="<?php echo $info['title_list']; ?>">
	            	<i>（最多60个字符）</i>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
	        <dl>
	            <dt>代理商：</dt>
	            <dd>
	            	
					<select name="agent" class="form-control" style="width: 200px;" id="ddlAgentLevel_<?php echo $vo['id']; ?>">
   34                      <option value="0">无</option>
   35                      <?php if(is_array($agent) || $agent instanceof \think\Collection || $agent instanceof \think\Paginator): if( count($agent)==0 ) : echo "" ;else: foreach($agent as $key=>$item): ?>
   36                      <option value="<?php echo $item['id']; ?>" <?php if($item['id'] == $info['agent']): ?>selected="selected"<?php endif; ?>><?php echo $item['user']['nickname']; ?></option>
   37                      <?php endforeach; endif; else: echo "" ;endif; ?>
   38:                 </select>
	            	<i></i>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
	        <dl>
	            <dt>线路标签：</dt>
	            <dd>
	           	<?php if(is_array($goodsTag) || $goodsTag instanceof \think\Collection || $goodsTag instanceof \think\Paginator): if( count($goodsTag)==0 ) : echo "" ;else: foreach($goodsTag as $key=>$item): ?>
					<label class="checkbox-inline"><input type="checkbox" name="goodsT[]" value="<?php echo $item['id']; ?>" <?php if(is_array($goodsT) || $goodsT instanceof \think\Collection || $goodsT instanceof \think\Paginator): if( count($goodsT)==0 ) : echo "" ;else: foreach($goodsT as $key=>$i): if($i['tid'] == $item['id']): ?>checked="checked"<?php endif; endforeach; endif; else: echo "" ;endif; ?>><?php echo $item['name']; ?></label>
				<?php endforeach; endif; else: echo "" ;endif; ?>
                <!-- <span class="layers_tips_blue" data-model="form-webtips" data-content="勾选之后新代理授权申请以及推荐的代理授权申请时需要上传相关图片资料" data-placement="right">
                    <em class="newtips"></em>
                </span> -->
            </dd>
	        </dl>
	        <dl>
	            <dt>推荐标签：</dt>
	            <dd>
	            	<select name="rid" class="form-control" style="width: 200px;" id="ddlAgentLevel_<?php echo $vo['id']; ?>">
                         <option value="0">无</option>
                         <?php if(is_array($goodsRecommed) || $goodsRecommed instanceof \think\Collection || $goodsRecommed instanceof \think\Paginator): if( count($goodsRecommed)==0 ) : echo "" ;else: foreach($goodsRecommed as $key=>$item): ?>
                         <option value="<?php echo $item['id']; ?>" <?php if($item['id'] == $info['rid']): ?>selected="selected"<?php endif; ?>><?php echo $item['name']; ?></option>
                         <?php endforeach; endif; else: echo "" ;endif; ?>
                   </select>
	            	<!-- <em>元</em>
	            	<i>（支持2位小数点）</i> -->
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
	        <dl>
	            <dt><i>*</i>产品封面图片：</dt>
	            <dd>
	            	<div class="upload-panel">
	            		<a href="javascript:;" data-model="form-upload" data-target="#images" data-preview="#uploadImage_2" class="btn btn-ok"><i class="iconfont">&#xe74a;</i> 上传图片</a>
	            	</div>
	            	<input type="hidden" name="photo" value="<?php echo $info['photo']; ?>" id="images">
		            <div class="help-block">(推荐尺寸为600px*600px，大小不超过200k，支持jpeg、jpg、png、gif、jpeg格式)</div>
	            	<div class="upload-prview">
	            		<img style="width:expression(this.width > 105 ? 105px : this.width)" src="<?php echo $info['photo']; ?>" id="uploadImage_2">
	            	</div>
	            </dd>
	        </dl>
	        <dl>
	            <dt>是否banner展示：</dt>
	            <dd>
	            	<label class="radio-inline"><input type="radio" name="show_banner" <?php if($info['show_banner'] == 1): ?>checked<?php endif; ?> value="1">显示</label>
	            	<label class="radio-inline"><input type="radio" name="show_banner" <?php if($info['show_banner'] == 0): ?>checked<?php endif; ?> value="0">取消</label>
	            	<!-- <em>元</em>
	            	<i>（支持2位小数点）</i> -->
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
	        <dl>
	            <dt><i>*</i>旅途时间：</dt>
	            <dd style="">
	            	<div class="search-item" style="position:relative;height:20px;">
						<input type="text" class="form-control" name="start_time" style="width: 150px;float:left;position:absolute;left:0px;background-color:#fff;" value="<?php if($info['id'] != 0): ?><?php echo date("Y-m-d H:i:s",$info['start_time']); else: ?><?php echo date('Y-m-d H:i:s',time()); endif; ?>" data-model="form-time" readonly=""><em style="float:left;margin-top:5px;position:absolute;left:160px;width:10px;">--</em>
						<input type="text" class="form-control" name="stop_time" style="width: 150px;float:left;position:absolute;left:190px;background-color:#fff;" value="<?php if($info['id'] != 0): ?><?php echo date("Y-m-d H:i:s",$info['stop_time']); else: ?><?php echo date('Y-m-d H:i:s',time()); endif; ?>"" data-model="form-time" readonly="">
					</div>
	            </dd>
	        </dl>
	        <dl>
	            <dt><i>*</i>旅途时间长度：</dt>
	            <dd>
	            	<input type="text" style="width: 260px" name="total_time" class="form-controls" datatype="*1-10" value="<?php echo $info['total_time']; ?>">
	            	<i>（最多20个字符）</i>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
	        <dl>
	            <dt><i>*</i>路线展示时间：</dt>
	            <dd style="">
	            	<div class="search-item" style="position:relative;height:20px;">
						<input type="text" class="form-control" name="show_time" style="width: 150px;float:left;position:absolute;left:0px;background-color:#fff;" value="<?php if($info['id'] != 0): ?><?php echo date("Y-m-d H:i:s",$info['show_time']); else: ?><?php echo date('Y-m-d H:i:s',time()); endif; ?>" data-model="form-time" readonly=""><em style="float:left;margin-top:5px;position:absolute;left:160px;width:10px;">--</em>
						<input type="text" class="form-control" name="hide_time" style="width: 150px;float:left;position:absolute;left:190px;background-color:#fff;" value="<?php if($info['id'] != 0): ?><?php echo date("Y-m-d H:i:s",$info['hide_time']); else: ?><?php echo date('Y-m-d H:i:s',time()); endif; ?>"" data-model="form-time" readonly="">
					</div>
	            </dd>
	        </dl>
	        <dl>
	        	<dt><i>*</i>商品图：</dt>
	            <dd>
					<div class="flex-item-1 form-item-line">
						<div class="input-line input-flex">
							<div class="photo-group">
								<ul class="clearfix" id="images-thumbnails">
									<?php if(!(empty($photo) || (($photo instanceof \think\Collection || $photo instanceof \think\Paginator ) && $photo->isEmpty()))): foreach($photo as $vo): ?>
									<li><a href="<?php echo $vo['photo']; ?>" target="_blank"><img src="<?php echo $vo['photo']; ?>"></a><div class="info"><a class="del">x</a></div><input type="hidden" name="photo_arr[]" value="<?php echo $vo['photo']; ?>"></li>
									<?php endforeach; endif; ?>
									<li>
										<div class="rc-upload">
										<a class="add-goods" href="javascript:;" data-model="upload-photo"
					                        data-img-list='false'
					                        data-img-name="photo_arr"
					                        data-img-warp="#images-thumbnails"
					                        data-id="imageUpload">+ 添加图片</a>
										</div>
										<script type="text/plain" id="imageUpload" style="display:block;" ></script>
									</li>
								</ul>
							</div>
							<div class="help-block">建议尺寸：800*800像素，最多上传10张</div>
						</div>
					</div>
				</dd>
	        </dl>
	        <dl>
	            <dt><i>*</i>详细描述：</dt>
	            <dd>
	            	<textarea name="content" rows="5" id="content" data-model="form-ueditor"><?php echo $info['content']; ?></textarea>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
	        <dl>
	            <dt>排序：</dt>
	            <dd>
	            	<input type="text" style="width: 260px" name="rank_num" class="form-controls" datatype="*1-100" value="<?php echo !empty($info['rank_num'])?$info['rank_num']:0; ?>">
	            	<i>（必须数字，值越大 优先展示）</i>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
	        <dl>
	            <dt>收藏次数：</dt>
	            <dd>
	            	<input type="text" style="width: 260px" name="collect_num" class="form-controls" value="<?php echo !empty($info['collect_num'])?$info['collect_num']:0; ?>">
	            	<i></i>
	            	<div class="tip-alert"></div>
	            </dd>
	        </dl>
	        <dl>
	            <dt>是否展示：</dt>
	            <dd>
	            	<?php $state = isset($info['status']) ? $info['status'] : '0'; ?>
	            	<label class="radio-inline"><input type="radio" name="status" <?php if($state == 1): ?>checked<?php endif; ?> value="1">显示</label>
	            	<label class="radio-inline"><input type="radio" name="status" <?php if($state == 0): ?>checked<?php endif; ?> value="0">隐藏</label>
	            </dd>
	        </dl>
			<div class="submit-btn">
	        	<input type="hidden" value="<?php echo $info['id']; ?>" name="id">
	        	<button class="btn btn-info">保存</button>
	        </div>
		</form>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$("#brandSel").change(function(){
		var brandId = $(this).val();
		$.post("<?php echo url('api/getLevel'); ?>",{brand:brandId},function(res){
			if(res.code == '1'){
				var list = res.data;
				var html = '';
				for(var i in list){
					html +='<dl class="sub-price">';
	            	html +='	<dt>'+list[i].name+'</dt>';
	            	html +='	<dd>';
	            	html +='		<input type="text" style="width: 120px" name="agent['+list[i].id+']" class="form-controls" datatype="*1-10" value="">';
	            	html +='		<em>元</em>';
	            	html +='		<i>（支持2位小数点）</i>';
	            	html +='	</dd>';
	            	html +='</dl>';          	
				}
				html +='<div class="tip-alert"></div>';
				$("#levelPrice").html(html);
			}else{
				$("#levelPrice").html('该品牌当前还没添加任何代理级别，无法设置代理订货价，<a href="<?php echo url('system/dolevel'); ?>">立即添加</a>');
			}
		},'JSON');
	});
	<?php if(empty($level) || (($level instanceof \think\Collection || $level instanceof \think\Paginator ) && $level->isEmpty())): ?>
	$("#brandSel").change();
	<?php endif; ?>
})
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