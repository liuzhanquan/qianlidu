<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:62:"D:\phpstudy\WWW\lanHu\application/manage\view\order\index.html";i:1570789636;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570776449;}*/ ?>
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
		<div class="option-search clearfix">
			<form method="get">
				<table>
					<tr>
						<td>
							<div class="search-item">
								<label>订单状态：</label>
								<select class="form-control" name="type" data-model="form-select" style="width:200px;">
									<option value="0" <?php if($data['status'] == '0'): ?>selected<?php endif; ?>>全部</option>
									<option value="1" <?php if($data['status'] == '1'): ?>selected<?php endif; ?>>未审核</option>
									<option value="2" <?php if($data['status'] == '2'): ?>selected<?php endif; ?>>待出行</option>
									<option value="3" <?php if($data['status'] == '3'): ?>selected<?php endif; ?>>已出行</option>
									<option value="4" <?php if($data['status'] == '4'): ?>selected<?php endif; ?>>未评价</option>
									<option value="5" <?php if($data['status'] == '5'): ?>selected<?php endif; ?>>未评价</option>
									<option value="6" <?php if($data['status'] == '6'): ?>selected<?php endif; ?>>已评价</option>
									<option value="100" <?php if($data['status'] == '100'): ?>selected<?php endif; ?>>取消</option>
								</select>
							</div>
						</td>
						<td>
							<div class="search-item">
								<label>出行时间：</label>
								<input type="text" class="form-control" name="start" style="width: 100px;" value="<?php echo !empty($data['start'])?$data['start'] : ''; ?>" data-model="form-time" readonly=""><em>--</em>
								<input type="text" class="form-control" name="end" style="width: 100px;" value="<?php echo !empty($data['end'])?$data['end'] : ''; ?>" data-model="form-time" readonly="">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="search-item">
								<select class="form-control" name="selkey" data-model="form-select" style="width: 150px;">
									<option value="1" <?php if($data['selkey'] == 1): ?>selected<?php endif; ?>>订单编号</option>
									<option value="2" <?php if($data['selkey'] == 2): ?>selected<?php endif; ?>>用户名称</option>
									<option value="3" <?php if($data['selkey'] == 3): ?>selected<?php endif; ?>>用户手机</option>
									<option value="4" <?php if($data['selkey'] == 4): ?>selected<?php endif; ?>>旅游路线名称</option>
								</select>
								<input type="text" class="form-control" name="key" value="<?php echo $data['key']; ?>" style="width: 200px;">
							</div>
						</td>
						<td>
							<div class="search-item">
								<button class="btn btn-info btn-ok">搜索</button>
								<button class="btn btn-default btn-cancle" name="exp" value="1">导出</button>
							</div>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div class="tab-title" style="margin-top: 28px;">
	        <ul class="tab-title_con">
	            <li <?php if($data['status'] == 0): ?>class="on"<?php endif; ?>><a href="<?php echo url('index'); ?>">全部</a></li>
	            <li <?php if($data['status'] == 1): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['type'=>'1']); ?>">未审核</a></li>
	            <li <?php if($data['status'] == 2): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['type'=>'2']); ?>">待出行</a></li>
	            <li <?php if($data['status'] == 3): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['type'=>'3']); ?>">已出行</a></li>
	            <li <?php if($data['status'] == 4): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['type'=>'4']); ?>">已完成</a></li>
	            <li <?php if($data['status'] == 5): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['type'=>'5']); ?>">未评价</a></li>
	            <li <?php if($data['status'] == 6): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['type'=>'6']); ?>">已评价</a></li>
	            <li <?php if($data['status'] == 100): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['type'=>'100']); ?>">取消</a></li>
	        </ul>
	    </div>
	    <div class="goods_order-list">
	    	<table class="table table-hover">
	    		<thead>
	    			<tr>
	    				<th>
		    				<div class="clearfix order-header">
		    					<span class="order-h" style="width: 10%;">用户头像</span>
			    				<span class="order-h text-center" style="width: 20%;">客户信息</span>
			    				<span class="order-h text-center" style="width: 20%;">路线信息</span>
			    				<span class="order-h text-center" style="width: 25%;">时 间</span>
			    				<span class="order-h text-center" style="width: 10%;">订单状态</span>
			    				<span class="order-h text-right" style="width: 10%;">操作</span>
		    				</div>
	    				</th>
	    			</tr>
	    		</thead>
	    	</table>
	    	<?php if(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty())): ?>
	    	<div class="empty-txt">
				<i class="iconfont">&#xe60f;</i>
				<p>没有相关记录订单~</p>
			</div>
	    	<?php else: foreach($list as $vo): ?>
		    <table class="table table-hover" style="table-layout: inherit;" data-table>
		    	<thead>
		    		<tr>
		    			<th colspan="6">
		    				<div class="clearfix order-header">
		    					<div class="fl">订单号：<a href="<?php echo url('info',['id'=>$vo['order_num']]); ?>"><?php echo $vo['order_num']; ?></a></div>
								<div class="fr" style="margin-right:10px;"><a href="javascript:void(0)" data-confirm data-tips="您订单确认修改为取消？" data-url="<?php echo url('status'); ?>" data-val="100" data-id="<?php echo $vo['id']; ?>">取消</a></div>
								<div class="fr" style="margin-right:10px;"><a href="javascript:void(0)" data-confirm data-tips="您订单确认修改为已评论？" data-url="<?php echo url('status'); ?>" data-val="5" data-id="<?php echo $vo['id']; ?>">已评论</a></div>
								<div class="fr" style="margin-right:10px;"><a href="javascript:void(0)" data-confirm data-tips="您订单确认修改为未评论？" data-url="<?php echo url('status'); ?>" data-val="4" data-id="<?php echo $vo['id']; ?>">未评论</a></div>
								<div class="fr"  style="margin-right:10px;"><a href="javascript:void(0)" data-confirm data-tips="您订单确认修改为已完成？" data-url="<?php echo url('status'); ?>" data-val="3" data-id="<?php echo $vo['id']; ?>">已完成</a></div>
								<div class="fr"  style="margin-right:10px;"><a href="javascript:void(0)" data-confirm data-tips="您订单确认修改为已出行？" data-url="<?php echo url('status'); ?>" data-val="2" data-id="<?php echo $vo['id']; ?>">已出行</a></div>
								<div class="fr"  style="margin-right:10px;"><a href="javascript:void(0)" data-confirm data-tips="您订单确认修改为待出行？" data-url="<?php echo url('status'); ?>" data-val="1" data-id="<?php echo $vo['id']; ?>">待出行</a></div>
								<div class="fr"  style="margin-right:10px;"><a href="javascript:void(0)" data-confirm data-tips="您订单确认修改为未审核？" data-url="<?php echo url('status'); ?>" data-val="0" data-id="<?php echo $vo['id']; ?>">未审核</a></div>
		    				</div>
							
		    			</th>
						
		    		</tr>
					
		    	</thead>
		    	<tbody>
		    		<tr>
		    			<td width="55%" colspan="2" style="padding: 0;">
			    			<table class="table table-hover" style="border:0;margin-bottom: 0;">
			    				<tbody>
			    					<tr>
						    			<td style="vertical-align: top;border:0;border-top: 1px solid #dfe2e5;">
											<div class="page-wexin-info">
					                            <div class="goods-list">
					                                <img id="img_806" class="img_806" src="<?php echo $vo['user']['avatar']; ?>" alt="头像" style="width:50px;height:50px;">
					                                
					                                
					                            </div>
					                        </div>
										</td>
										<td width="25%">
											<p>用户名称：<?php echo $vo['user']['nickname']; ?></p>
											<p>手机号码：<?php echo $vo['user']['phone']; ?></p>
										</td>
										<td width="25%">
											<p>路线标题：<?php echo $vo['goods']['title']; ?></p>
											<p>代 理 商：<?php echo get_agent($vo['goods']['agent'])['name']; ?></p>
										</td>
										<td width="30%">
											<p>出行时间：<?php echo date("Y-m-d H:i:s",$vo['start_time']); ?></p>
											<p>提交时间：<?php echo date("Y-m-d H:i:s",$vo['add_time']); ?></p>
										</td>
			    					</tr>
			    				</tbody>
			    			</table>
		    			</td>
		    			
		    			<td width="10%">
		    				<?php if($vo['status'] == 0): ?>
							<font color="blue">未审核</font>
							<?php elseif($vo['status'] == 1): ?>
							待出行
							<?php elseif($vo['status'] == 2): ?>
							已出行
							<?php elseif($vo['status'] == 3): ?>
							已完成
							<?php elseif($vo['status'] == 4): ?>
							未评论
							<?php elseif($vo['status'] == 5): ?>
							已评论
							<?php elseif($vo['status'] == 100): ?>
							<font color="red">取消</font>
							<?php endif; ?>
		    			</td>
		    			<td width="10%">
							<a href="<?php echo url('douser',['id'=>$vo['id'],'type'=>'view']); ?>">查看</a><em>-</em>
							<a href="<?php echo url('douser',['id'=>$vo['id'],'type'=>'edit']); ?>">订单修改</a>
							<br/>
							<a href="<?php echo url('user/douser',['id'=>$vo['user']['id'],'type'=>'edit']); ?>">客户信息修改</a>
							
							
						</td>
						
		    		</tr>
		    	</tbody>
		    </table>
		    <?php endforeach; endif; ?>
		    <div class=""></div>
	    </div>
	    <div class="option-bottom clearfix">
			<div class="fl"></div>
			<div class="pages fr"><?php echo $page; ?></div>
		</div>
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