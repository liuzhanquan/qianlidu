<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:62:"D:\phpstudy\WWW\lanHu\application/manage\view\index\index.html";i:1569830897;s:57:"D:\phpstudy\WWW\lanHu\application\manage\view\layout.html";i:1570776449;}*/ ?>
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
	<div class="home-block">
		<ul class="flex-row">
			<li class="flex10">
	            <p class="num"><?php echo $money['agent']; ?></p>
	            <p class="txt">
	                代理加盟总数
	                <span class="layers_tips_blue" data-model="form-webtips" data-content="更新时间：<?php echo date('Y-m-d H:i:s'); ?>" data-placement="right">
	                    <em class="newtips white ml-5"></em>
	                </span>
	            </p>
	        </li>
            <li class="flex10">
                <p class="num"><?php echo $money['business']; ?></p>
                <p class="txt">
                    商家加盟总数
                    <span class="layers_tips_blue" data-model="form-webtips" data-content="总部给直属代理充值的货款总额，更新时间：<?php echo date('Y-m-d H:i:s'); ?>" data-placement="right">
                        <em class="newtips white ml-5"></em>
                    </span>
                </p>
            </li>
		</ul>
		<ul class="flex-row tongji2">
			<li class="flex10">
                <p class="num"><?php echo $money['user']; ?></p>
	            <p class="txt">
	                用户总数
	                <span class="layers_tips_blue" data-model="form-webtips" data-content="总部直属代理用货款下单和总部扣除各级代理货款总和，更新时间：<?php echo date('Y-m-d H:i:s'); ?>" data-placement="right">
	                    <em class="newtips white ml-5"></em>
	                </span>
	            </p>
	        </li>
			<li class="flex10">
                <p class="num"><?php echo $money['order']; ?></p>
	            <p class="txt">
	                订单总数
	                <span class="layers_tips_blue" data-model="form-webtips" data-content="各级代理货款余额总和，更新时间：<?php echo date('Y-m-d H:i:s'); ?>" data-placement="right">
	                    <em class="newtips white ml-5"></em>
	                </span>
	            </p>
	        </li>
		</ul>
	</div>
	<div class="home-echart">
		<h3>近七天代理加盟数量 <a href="<?php echo url('agents/index'); ?>">(<?php echo date('Y-m-d',strtotime('-7 days')); ?>至<?php echo date('Y-m-d'); ?>)</a></h3>
		<div class="quanchangjin_quxiantu-page" id="pre1Img" style="height:400px"></div>
	</div>
	<!-- <div class="home-echart">
		<h3>近七天产品出货数量曲线图 <span>(<?php echo date('Y-m-d',strtotime('-7 days')); ?>至<?php echo date('Y-m-d'); ?>)</span></h3>
		<div class="quanchangjin_quxiantu-page" id="pre2Img" style="height:400px"></div>
	</div> -->
	<div class="home-echart">
		<h3>全国代理分布图 <span>(<?php echo date('Y-m-d',strtotime('-7 days')); ?>至<?php echo date('Y-m-d'); ?>)</span></h3>
		<div id="container" style="height:540px;"></div>
	</div>
</div>
<script type="text/javascript" src="https://stati.weixin12315.com/houtai/Component/hcharts/highcharts.js?v=20180517"></script>
<script type="text/javascript" src="https://stati.weixin12315.com/houtai/Component/hcharts/map.js?v=20180517"></script>
<script type="text/javascript" src="https://stati.weixin12315.com/houtai/Component/hcharts/china.js?v=20180517"></script>
<script>
function escapeHtml(str) {
    return str.replace(/-/g,'"');
}
// 随机数据
var saoma = "代理总量："
var data = eval('(' + escapeHtml('[{value:0,name:-河北-},{value:0,name:-山西-},{value:0,name:-内蒙古-},{value:0,name:-黑龙江-},{value:0,name:-吉林-},{value:0,name:-辽宁-},{value:0,name:-陕西-},{value:0,name:-甘肃-},{value:0,name:-青海-},{value:0,name:-新疆-},{value:0,name:-宁夏-},{value:0,name:-山东-},{value:0,name:-河南-},{value:0,name:-江苏-},{value:0,name:-浙江-},{value:0,name:-安徽-},{value:0,name:-江西-},{value:0,name:-福建-},{value:0,name:-台湾-},{value:0,name:-湖北-},{value:0,name:-湖南-},{value:16,name:-广东-},{value:0,name:-广西-},{value:0,name:-海南-},{value:0,name:-四川-},{value:0,name:-云南-},{value:0,name:-贵州-},{value:0,name:-西藏-},{value:0,name:-北京-},{value:0,name:-重庆-},{value:8,name:-上海-},{value:0,name:-香港-},{value:0,name:-澳门-},{value:0,name:-天津-},{value:0,name:-南海诸岛-}]') + ')');


var mapArray = Highcharts.maps['cn-with-city'];
// 初始化图表
var map = new Highcharts.Map('container', {
    title: {
        text: null
    },
    credits: {
        enabled: false
    },
    colors: function (color) {
        return {
            radialGradient: {
                cx: 0.5,
                cy: 0.3,
                r: 0.7
            },
            stops: [
            [0, color],
            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')]
            ]
        };
    } (),
    mapNavigation: {
        enabled: true,
        enableMouseWheelZoom: false,
        buttonOptions: {
            verticalAlign: 'bottom'
        }
    },
    //颜色渐变
    colorAxis: {
        min: 0,
        labels:{
            step:2
        },
        stops: [
        [0, '#f1f1f1'],
        [0.1, Highcharts.getOptions().colors[0]],
        [1, '#169ae3']
        ]
    },
    tooltip: {
        formatter: function () {
            return this.point.name + "<br>" + "<b>" + saoma + " </b>\t" + this.point.value;
        }
    },
    series: [{
        name: '数据',
        data: data,
        joinBy: 'name',
        // Basic China map
        showInLegend: false,
        mapData: mapArray,
        events: {

        },
        states: {
            hover: {
                color: '#BADA55'  //鼠标经过地图背景颜色
            }
        },
        dataLabels: {
            enabled: true,
            format: '{point.name}',
            style: {
                fontSize: '8px',
                fontWeight:"100",
                textShadow:'none',
                color: '#41484f' //字体颜色
            }
        }
    }]
});
</script>
<script type="text/javascript">
    function GetDateStr(AddDayCount) {
        var dd = new Date('2018/6/12 15:45:47');
        dd.setDate(dd.getDate() + AddDayCount); //获取AddDayCount天后的日期
        var y = dd.getFullYear();
        var m = (dd.getMonth() + 1) < 10 ? "0" + (dd.getMonth() + 1) : (dd.getMonth() + 1); //获取当前月份的日期，不足10补0
        var d = dd.getDate() < 10 ? "0" + dd.getDate() : dd.getDate(); //获取当前几号，不足10补0
        return y + "-" + m + "-" + d;
    }
    var chart = null;
    $(function () {
        var pre1 = '<a style="font-size:12px;color: rgb(0,0,238);" href="/Agent/InfoManage?StartTime='+GetDateStr(-7)+'&EndTime='+GetDateStr(-1)+' 23:59:59">（' + GetDateStr(-7) + '至' + GetDateStr(-1) + '）</a>';
        var pre2 = "（" + GetDateStr(-7) + "至" + GetDateStr(-1) + "）";
        $("#pre1").html('').append(pre1);
        $("#pre2").html(pre2);

        $("#pre1Img").highcharts({
            credits: {
                enabled: false
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: [GetDateStr(-7), GetDateStr(-6), GetDateStr(-5), GetDateStr(-4), GetDateStr(-3), GetDateStr(-2), GetDateStr(-1)]
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: ''
                }
            },
            tooltip: {
                enabled: true,
                formatter: function () {
                    var s = '<b>' + this.x + '</b>';
                    $.each(this.points, function () {
                        s += '<br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 0);
                    });
                    return s;
                },
                shared: true
            },
            plotOptions: {
                spline: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                },
                series: {
                    point: {
                        events: {
                            mouseOver: function () {
                                //chart.xAxis[0].removePlotLine("plot-line");
                                //chart.xAxis[0].addPlotLine({
                                //    value: this.x,
                                //    width: 2,
                                //    dashStyle: 'ShortDashDot',
                                //    color: '#5694e7',
                                //    id: 'plot-line'
                                //});
                            }
                        }
                    },
                    events: {
                        click: function (event) {
                            var dtime = new Date(event.point.category);

                            var y = dtime.getFullYear();
                            var m = (dtime.getMonth() + 1) < 10 ? "0" + (dtime.getMonth() + 1) : (dtime.getMonth() + 1); //获取当前月份的日期，不足10补0
                            var d = dtime.getDate() < 10 ? "0" + dtime.getDate() : dtime.getDate(); //获取当前几号，不足10补0
                            window.location.href="/Agent/InfoManage?StartTime="+event.point.category+"&EndTime="+y + "-" + m + "-" + d+" 23:59:59";
                        },
                        mouseOut: function () {
                            chart.xAxis[0].removePlotLine("plot-line");
                        }
                    }
                }
            },
            series: [{
                color: '#5694e7',
                name: '代理加盟数',
                data: [0,0,0,0,0,0,0],
                marker: {
                    symbol: "circle"
                }
            }]
        }, function (c) {
            chart = c;
        });

        $('#pre2Img').highcharts({
            credits: {
                enabled: false
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: [GetDateStr(-7), GetDateStr(-6), GetDateStr(-5), GetDateStr(-4), GetDateStr(-3), GetDateStr(-2), GetDateStr(-1)]
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: ''
                }
            },
            tooltip: {
                enabled: true,
                formatter: function () {
                    var s = '<b>' + this.x + '</b>';
                    $.each(this.points, function () {
                        s += '<br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 0);
                    });
                    return s;
                },
                shared: true

            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                },
                series: {
                    point: {
                        events: {
                            mouseOver: function () {
                                //chart.xAxis[0].removePlotLine("plot-line");
                                //chart.xAxis[0].addPlotLine({
                                //    value: this.x,
                                //    width: 2,
                                //    dashStyle: 'ShortDashDot',
                                //    color: '#5694e7',
                                //    id: 'plot-line'
                                //});
                            }
                        }
                    },
                    events: {
                        mouseOut: function () {
                            chart.xAxis[0].removePlotLine("plot-line");
                        }
                    }
                }
            },
            series: [{
                color: '#5694e7',
                name: '产品出货量',
                data: [0,0,0,0,0,0,0],
                marker: {
                    symbol: "circle"
                }
            }]
        }, function (c) {
            chart = c;
        });
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