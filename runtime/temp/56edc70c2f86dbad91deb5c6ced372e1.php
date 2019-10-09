<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:63:"D:\phpstudy\WWW\lanHu\application/manage\view\system\index.html";i:1557097926;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570500128;}*/ ?>
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

    <div class="tab-title" style="margin-top: 28px;">
        <ul class="tab-title_con">
            <li <?php if($id == 1): ?>class="on"<?php endif; ?>><a href="<?php echo url('index'); ?>">基础设置</a></li>
            <!-- <li <?php if($id == 2): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['id'=>'2']); ?>">代理设置</a></li> -->
            <!-- <li <?php if($id == 3): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['id'=>'3']); ?>">订单设置</a></li> -->
            <!-- <li <?php if($id == 4): ?>class="on"<?php endif; ?>><a href="<?php echo url('index',['id'=>'4']); ?>">收款账号</a></li> -->
            <li><a href="<?php echo url('sethome'); ?>">代理后台首页设置</a></li>
        </ul>
    </div>
	<div class="sys-content">
		<?php if($id == 1): ?>
        <form data-model="form-submit">
        <dl>
            <dt><i>*</i>品牌名称：</dt>
            <dd>
                <input type="text" style="width: 260px" value="<?php echo $config['brand_name']; ?>" name="brand_name" class="form-controls" datatype="*">
            </dd>
        </dl>
        <dl>
            <dt><i>*</i>授权代码前缀：</dt>
            <dd>
                <input type="text" style="width: 260px" value="<?php echo $config['brand_prefix']; ?>" name="brand_prefix" class="form-controls" datatype="*1-5">
                <i>最多5个英文字符</i>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i>流水号开始值：</dt>
            <dd>
                <input type="text" style="width: 260px" value="<?php echo $config['brand_number']; ?>" name="brand_number" class="form-controls" datatype="n1-7">
                <i>最多7位数，空位可以补0，使用后不可修改</i>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i>品牌授权时长：</dt>
            <dd>
                <input type="text" style="width: 260px" value="<?php echo $config['brand_long']; ?>" name="brand_long" class="form-controls" datatype="n1-7">
                <i>月</i>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i>品牌Logo：</dt>
            <dd>
                <div class="upload-panel">
                    <a href="javascript:;" data-model="form-upload" data-target="#images" data-preview="#uploadImage_2" class="btn btn-ok"><i class="iconfont">&#xe74a;</i> 上传图片</a>
                </div>
                <input type="hidden" name="brand_logo" value="<?php echo $config['brand_logo']; ?>" id="images">
                <span class="layers_tips_blue" data-model="form-webtips" data-content="1、此处授权书的优先级高于【系统管理>授权品牌管理】<br> 2、若未设置则以【系统管理>授权品牌管理】中的授权书为准" data-placement="right">
                    <em class="newtips"></em>
                </span>
                <div class="help-block">(推荐尺寸为200px*200px，大小不超过500k，支持jpg、png、gif、jpeg格式)</div>
                <div class="upload-prview">
                    <img style="width:expression(this.width > 105 ? 105px : this.width)" src="<?php echo $config['brand_logo']; ?>" id="uploadImage_2">
                </div>
            </dd>
        </dl>
        <div class="submit-btn">
            <button class="btn btn-info">保存设置</button>
        </div>
        </form>
		<?php elseif($id == 2): ?>
        <form data-model="form-submit">
		<dl>
			<dt><i>*</i>代理资料标识：</dt>
			<dd>
				<label class="checkbox-inline"><input type="checkbox" name="WxNo" value="1">微信号</label>
				<label class="checkbox-inline"><input type="checkbox" name="TBID" value="1">淘宝ID</label>
                <span class="layers_tips_blue" data-model="form-webtips" data-content="必选一个，可多选；此处选择决定代理申请时必填的资料" data-placement="right">
                    <em class="newtips"></em>
                </span>
            </dd>
		</dl>
		<dl>
			<dt>代理申请附加资料：</dt>
			<dd>
				<label class="checkbox-inline"><input type="checkbox" name="cheIDCardImg" value="1">身份证图片（正反面）</label>
				<label class="checkbox-inline"><input type="checkbox" name="chePortraitImg" value="1">手持身份证的上半身照</label>
				<label class="checkbox-inline"><input type="checkbox" name="cheRemittanceImg" value="1">打款截图</label>
                <span class="layers_tips_blue" data-model="form-webtips" data-content="勾选之后新代理授权申请以及推荐的代理授权申请时需要上传相关图片资料" data-placement="right">
                    <em class="newtips"></em>
                </span>
            </dd>
		</dl>
		<dl>
			<dt><i>*</i>品牌授权禁用后：</dt>
			<dd>
				<label class="radio-inline"><input type="radio" name="IsVirtual2" value="1" checked="">不允许查询和参与活动</label>
				<label class="radio-inline"><input type="radio" name="IsVirtual2" value="2">允许查询和参与活动</label>
				<label class="radio-inline"><input type="radio" name="IsVirtual2" value="3">只允许查询，不允许参与活动</label>
                <span class="layers_tips_blue" data-model="form-webtips" data-content="针对代理的品牌授权被禁用后，其手里的对应品牌产品">
                    <em class="newtips"></em>
                </span>
            </dd>
		</dl>
		<dl class="add-block-li">
            <dt><i>*</i>邀请链接有效期：</dt>
            <dd>
                <input type="text" id="txtInvitationTime" name="txtInvitationTime" style="width:100px" value="60">
                <em>分钟</em>
                <span class="shuoming2 ml-10">
                    设置范围：5-1440，默认60分钟
                </span>
            </dd>
        </dl>
        <dl class="add-block-li">
            <dt>邀请链接标题：</dt>
            <dd>
                <input type="text" style="width:100px" maxlength="10" placeholder="微商代理" value="微商代理" id="txtInvitationTitle" name="txtInvitationTitle">
                <span class="shuoming2 ml-10">
                    1-10个字符
                </span>
                <a class="fc-cur hand ml-10" href="javascript:void(0)" data-model="form-showImg" data-val="https://stati.weixin12315.com/houtai/images/kongjiasystem/tips/t1.png" data-area="320px" style="margin-right: 20px;">示例</a>
            </dd>
        </dl>
		<dl>
			<dt><i>*</i>代理查询关键词：</dt>
			<dd>
				<label class="checkbox-inline"><input type="checkbox" name="chkBrandAuthCode_Key" value="1">代理编号</label>
				<label class="checkbox-inline"><input type="checkbox" name="chkMobile_Key" value="1">手机号</label>
				<label class="checkbox-inline"><input type="checkbox" name="chkWxNo_Key" value="1">微信号</label>
				<label class="checkbox-inline"><input type="checkbox" name="cheTBID_Key" value="1">淘宝ID</label>
				<label class="checkbox-inline"><input type="checkbox" name="cheIDCard_Key" value="1">身份证号码</label>
                <span class="layers_tips_blue" data-model="form-webtips" data-content="代理查询关键词是指前端代理查询页面中用于搜索代理商的关键词，至少勾选一项" data-placement="right">
                    <em class="newtips"></em>
                </span>
            </dd>
		</dl>
		<dl>
            <dt>代理查询页提示：</dt>
            <dd>
                <textarea name="txtAgentCheckRemark" id="txtAgentCheckRemark" maxlength="200" class="validate-right">请输入代理商官方授权编号（或手机号、微信号、淘宝ID。）即可查看官方授权内容。所有授权编号都是唯一对应代理商资料，请仔细核对官方所出具的授权证书，以便为您提供安全保障和优质的服务。对于查询无结果或查询结果与实际不相符，请谨慎甄别，以免上当受骗。</textarea>
            </dd>
            <dd style="padding-left: 5px;">
                <a class="fc-cur hand ml-10" href="javascript:void(0)" data-model="form-showImg" data-val="https://stati.weixin12315.com/houtai/images/kongjiasystem/shili.jpg" data-area="320px" style="margin-right: 20px;">示例</a>
                <a class="fc-cur hand" href="javascript:void(0)" id="recoveryData">恢复默认提示</a>
                <p class="shuoming2" style="vertical-align:bottom;padding-top: 96px;">（限200个汉字以内)</p>
            </dd>
        </dl>
        <script type="text/javascript">
        $("#recoveryData").click(function () {
	        var data = "请输入代理商官方授权编号（或手机号、微信号、淘宝ID。）即可查看官方授权内容。所有授权编号都是唯一对应代理商资料，请仔细核对官方所出具的授权证书，以便为您提供安全保障和优质的服务。对于查询无结果或查询结果与实际不相符，请谨慎甄别，以免上当受骗。";
	        $("#txtAgentCheckRemark").val(data);
	    })
        </script>
        <div class="submit-btn">
            <button class="btn btn-info">保存设置</button>
        </div>
        </form>
		<?php elseif($id == 3): ?>
        <form data-model="form-submit">
		<dl>
			<dt><i>*</i>订单系统：</dt>
			<dd>
				<label class="radio-inline"><input type="radio" name="IsOpenOrder" value="1" checked="">开启</label>
				<label class="radio-inline"><input type="radio" name="IsOpenOrder" value="0">关闭</label>
                <span class="layers_tips_blue" data-model="form-webtips" data-content="1、订单系统开启后将实现代理在线下单，上级按单出货，系统将根据订单计算平级返利<br>2、订单系统开启之前需要完成所有产品的代理订货价设置" data-placement="right">
                    <em class="newtips"></em>
                </span>
            </dd>
		</dl>
		<dl>
			<dt>订单模式：</dt>
			<dd>
				<label class="radio-inline"><input type="radio" name="OrderModel" onclick="fn_SelectOrderModel(1)" checked="" value="1">模式一（默认）</label>
				<label class="radio-inline"><input type="radio" name="OrderModel" onclick="fn_SelectOrderModel(2)" value="2">模式二</label>
            </dd>
		</dl>
		<dl class="IsOpenOrderIsChange">
            <div id="Div_OrderModel1" style="display: block; padding: 0px 20px 20px 15px; border-radius: 5px; background: rgb(238, 238, 238); margin: 0px 20px 0px 140px; max-width: 480px;">
                <h2 style="color:black; padding:10px 0px;">模式说明</h2>
                <div>
                    代理下单之后系统会默认提交给直属上级审核，若直属上级为平级，则订单会跳过平级继续往上提交，一直提交至比下单代理高一级的上级进行审核
                </div>
            </div>
            <div id="Div_OrderModel2" style="display: none; padding: 0px 20px 20px 15px; border-radius: 5px; background: rgb(238, 238, 238); margin: 0px 20px 0px 140px; max-width: 480px;">
                <h2 style="color:black; padding:10px 0px;">模式说明</h2>
                <div>
                    代理下单之后系统会默认提交给直属上级审核，若直属上级为平级，则订单仍然会提交给直属平级审核，但当直属平级有多个时，则订单只会提交给最上面的那个平级进行审核
                </div>
            </div>
        </dl>
		<dl>
			<dt>平级订单：</dt>
			<dd>
				<label class="radio-inline"><input type="radio" name="IsSeeSameLevelOrder" value="1" checked="">允许查看</label>
				<label class="radio-inline"><input type="radio" name="IsSeeSameLevelOrder" value="0" >不允许查看</label>
                <span class="layers_tips_blue" data-model="form-webtips" data-content="选中“允许查看”时，则代理可以查看平级下的订单，反之则不可以" data-placement="right">
                    <em class="newtips"></em>
                </span>
            </dd>
		</dl>
		<dl>
			<dt>平级返利结算时间：</dt>
			<dd>
				<label class="radio-inline"><input type="radio" name="AgentOrderRebateTime" value="1" checked="">订单过了退货有效期后结算</label>
				<label class="radio-inline"><input type="radio" name="AgentOrderRebateTime" value="2" >订单审核通过后结算</label>
                <span class="layers_tips_blue" data-model="form-webtips" data-content="订单过了退货有效期后结算：该选项的平级返利结算时间为代理确认收货后，过了退货有效期的第一天；<br>订单审核通过后结算：该选项的平级返利结算时间为总部或代理审核符合返利的订单后立刻结算，如审核后取消订单返利不会退还，可在【代理级别管理】中设置各代理级别的订单审核权限。" data-placement="right">
                    <em class="newtips"></em>
                </span>
            </dd>
		</dl>
		<dl>
			<dt><i>*</i>已发货订单：</dt>
			<dd>
				<em>发货成功后第</em>
				<input type="text" name="txtSettlementCycle" value="7" style="width: 80px;">
				<em>天系统自动确认收货</em>
            </dd>
		</dl>
		<dl>
			<dt><i>*</i>支付凭证：</dt>
			<dd>
				<label class="radio-inline"><input type="radio" name="IsCashVoucher" value="1" checked="">需要上传</label>
				<label class="radio-inline"><input type="radio" name="IsCashVoucher" value="0" >不需要上传</label>
            </dd>
		</dl>
		<dl>
			<dt><i>*</i>充值功能：</dt>
			<dd>
				<label class="radio-inline"><input type="radio" name="" checked="">是</label>
				<label class="radio-inline"><input type="radio" name="" >否</label>
            </dd>
		</dl>
		<dl>
			<dt><i>*</i>订单支付模式：</dt>
			<dd>
				<label class="radio-inline"><input type="radio" name="IsOpenRecharge" value="1" checked="">余额支付</label>
				<label class="radio-inline"><input type="radio" name="IsOpenRecharge" value="2">线下支付</label>
                <span class="layers_tips_blue" data-model="form-webtips" data-content="货款余额支付：线上进行货款充值，下单时，可使用货款余额支付；<br />线下支付：下单时，线下进行转账支付；" data-placement="right">
                    <em class="newtips"></em>
                </span>
            </dd>
		</dl>
		<dl>
			<dt><i>*</i>返利余额是否可以用来支付：</dt>
			<dd>
				<label class="radio-inline"><input type="radio" name="RebateBalanceIsPay" value="1" checked="">是</label>
				<label class="radio-inline"><input type="radio" name="RebateBalanceIsPay" value="0">否</label>
            </dd>
		</dl>
        <script type="text/javascript">
        //订单模式选择
		function fn_SelectOrderModel(orderModel) {
		    $("#Div_OrderModel1").css('display', 'none');
		    $("#Div_OrderModel2").css('display', 'none');
		    if (orderModel == 1) {
		        $("#Div_OrderModel1").css('display', 'inline-block');
		    }
		    else {
		        $("#Div_OrderModel2").css('display', 'inline-block');
		    }
		}
        </script>
        <div class="submit-btn">
            <button class="btn btn-info">保存设置</button>
        </div>
        </form>
		<?php elseif($id == 4): ?>
        <form data-model="form-submit">
        <dl>
            <dt><i>*</i>开户银行：</dt>
            <dd>
                <input type="text" name="bank[name]" value="<?php echo $bank['name']; ?>" datatype="*" style="width: 200px;">
                <i>如中国工商银行</i>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i>开户名：</dt>
            <dd>
                <input type="text" name="bank[user]" value="<?php echo $bank['user']; ?>" datatype="*" style="width: 200px;">
                <i>开户姓名</i>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i>银行卡号：</dt>
            <dd>
                <input type="text" name="bank[num]" value="<?php echo $bank['num']; ?>" datatype="*" style="width: 200px;">
                <i>银行卡账号</i>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i>开户支行：</dt>
            <dd>
                <input type="text" name="bank[address]" value="<?php echo $bank['address']; ?>" datatype="*" style="width: 200px;">
                <i>银行卡开户网点</i>
            </dd>
        </dl>
        <div class="submit-btn">
            <button class="btn btn-info">保存设置</button>
        </div>
        </form>
		<?php endif; ?>
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