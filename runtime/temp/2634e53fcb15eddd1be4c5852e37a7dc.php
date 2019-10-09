<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:62:"D:\phpstudy\WWW\lanHu\application/manage\view\login\index.html";i:1569833982;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>帐户登录－<?php echo $config['sitename']; ?></title>
<link rel="stylesheet" type="text/css" href="/static/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/static/admin/css/login.css">
<link rel="stylesheet" type="text/css" href="/static/admin/css/jquery.slider.css">
<script type="text/javascript" src="/static/admin/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/admin/js/jquery.slider.min.js"></script>
</head>
<body class="bg_img" style="background: url(https://stati.weixin12315.com/houtai/images/denglu/EdImg1.jpg) no-repeat 50% 0;">
<div class="page-login2">
    <div class="contant">
        <div class="bg"></div>
        <div class="box">
            <div class="title">
                <div class="title_top"><?php echo $config['sitename']; ?></div>
                <span class="title_down">后台管理系统</span>
                <div class="title_line"></div>
            </div>
            <form>
                <div class="inputs">
                    <div class="user">
                        <input type="text" id="uName" name="user" placeholder="管理账号" />
                        <!-- 如果验证正确就是加ture,否则就是加false -->
                        <p class="Error"></p>
                    </div>
                    <div class="pass">
                        <input type="password" id="pwd" name="password" placeholder="商户密码" />
                        <p class="Error"></p>
                    </div>
                    <div class="verification">
                    	<div id="verification"></div>
                    	<p class="Error"></p>
	                    <input type="hidden" id="result2" value="false">
                    </div>
                    <input type="button" value="登录" class="btn" onclick="Checklogin()"  />
                </div>
            </form>
        </div>
    </div>
</div>
<div class="page-footer">
	<p class="md-copyright__info"><?php echo $config['copyright']; ?><span><?php echo $config['copyright_num']; ?></span></p>
	<p class="md-copyright__support"><?php echo $config['copyright_technology']; ?></p>
</div>
<script type="text/javascript">
$("#verification").slider({
	width: 334, // width
	height: 50, // height
	fontSize: 14, // 文字大小
	callback: function(result) { // 回调函数，true(成功),false(失败)
		$("#result2").val(result);
        $(".verification").find(".Error").remove();
	}
});
function Checklogin(){
    var uName = $("#uName").val();
    var pwd = $("#pwd").val(); 
    if (!judgeValue())
    {
        return;
    }
    var option = {
        data: {
            username: uName,
            password: pwd,
            a: "login"
        },
        url:"<?php echo url('index'); ?>",
        type: "post",
        dataType: "json",
        success: function (data) {
            if(data.code == '1'){
                // success
                location.href = "<?php echo url('/index'); ?>";
            }else{
                if (data.code == "-1") {
                    $("#uName").next().html(data.msg).show();
                }else if (data.code == "-2") {
                    $("#pwd").next().html(data.msg).show();
                }else{
                    $(".verification").find(".Error").remove();
                    $(".verification").append("<p class=\"Error \">"+data.msg+"</p>");
                }
            }
        }
    }
    $.ajax(option);
}
function judgeValue(select) { 
    //账号 
    if ($("#uName").val().length == 0) {
        $("#uName").next().html("请输入商户帐号").show();
        return false;
    } else {
        $("#uName").next().hide();
    }
    //密码 
    if ($("#pwd").val().length == 0) {
        $("#pwd").next().html("请输入商户密码").show();
        return false;
    } else {
        $("#pwd").next().hide();
    }
    if ($("#result2").val() == 'false') {
        $(".verification").find(".Error").remove();
        $(".verification").append("<p class=\"Error \">请完成滑块验证</p>");
        return false;
    }
    return true;
}

</script>
</body>
</html>