//定义全局变量
var i=0;
//金额总和
var money=0;

//计算合计价格
var cart_money=new Object();

//全部商品ID
var cart_id = new Object();
//备份商品ID，用于全选后去掉全选又再次全选
var cart_id_copy=new Object();

var noX = 0;  /* 没选中时点击加减计算数量  */
var allThis = $(".commodity_box .selects em"); /*底部全选*/
/* 减  */
function reduceMod(e,totalH,mod,noX){
	var tn = e.siblings().find(".qu_su").text(); /* 当前选中商品  */
	var tn1 = e.siblings().find(".zi").text(); /* 商品数量  */
	if(mod != 2){
		var Total = parseFloat(totalH) - (tn*tn1);	/* 总价格减该商品总数价格  */
			$("#total_price b").text(Total.toFixed(2));
	}else{
		/* 合计加单价-1 */
		var Total = parseFloat(totalH) - parseFloat(tn);	/* 总价格减该商品总数价格  */
			$("#total_price b").text(Total.toFixed(2));
	}
	
};
/* 加  */
function ty_plusMod(e,totalH,mod){
	var tn = e.siblings().find(".qu_su").text(); /* 当前选中商品  */
	var tn1 = e.siblings().find(".zi").text(); /* 商品数量  */
	if(mod != 2){
		var Total = parseFloat(totalH)+(tn*tn1);	/* 总价格加上该商品总数价格  */
			$("#total_price b").text(Total.toFixed(2));
	}else{
		/* 合计加单价+1 */
		// var Total = parseFloat(totalH)+(parseFloat(tn)+(noX-1));	/* 总价格加上该商品总数价格  */
		var Total = parseFloat(totalH)+(parseFloat(tn));	/* 总价格加上该商品总数价格  */
			$("#total_price b").text(Total.toFixed(2));
	}
	
};
/*全选该店商品价格 加*/
function commodityty_plusMod(e,totalH){
	var qu = e.parents(".commodity_list").find(".pitch_on").parent().find(".qu_su");
	var quj = e.parents(".commodity_list").find(".pitch_on").parent().find(".zi");
	var Total = 0;
	var erTotal = true;
	/* 该商品全部金额  */
	for(var i=0; i<qu.length; i++)
	{
		var n = qu.eq(i).text();
		var n1 = quj.eq(i).text();
		/*合计价格*/
		if(erTotal){
			Total = parseFloat(totalH) +(parseFloat(n)*parseFloat(n1));
			if(Total < 0)
				Total=0;
			erTotal = false;
		}else{
			Total = parseFloat(Total) + (parseFloat(n)*parseFloat(n1));
			if(Total < 0)
				Total=0;
		}
	}
	$("#total_price b").text(Total.toFixed(2)); /* 合计金额  */
};
var ty_plus;
/*全选该店商品价格 减*/
function commodityReduceMod(e,totalH){
	var qu = e.parents(".commodity_list").find(".pitch_on").parent().find(".qu_su");
	var quj = e.parents(".commodity_list").find(".pitch_on").parent().find(".zi");
	var Total = 0;

	var erTotal = true;
	/* 该商品全部金额  */
	for(var i=0; i<qu.length; i++)
	{
		var n = qu.eq(i).text();
		var n1 = quj.eq(i).text();
		/*合计价格*/
		if(erTotal){
			Total = parseFloat(totalH) - (parseFloat(n)*parseFloat(n1));
			ty_plus = parseFloat(n)*parseFloat(n1);
			if(Total < 0)
				Total=0;
			erTotal = false;
		}else{
			Total = parseFloat(Total) - (parseFloat(n)*parseFloat(n1));
			ty_plus = parseFloat(n)*parseFloat(n1);
			if(Total < 0)
				Total=0;
		}
		
		
	}
	$("#total_price b").text(Total.toFixed(2)); /* 合计金额  */
	return ty_plus;
};
/*全部商品价格*/
function commodityWhole() {
	/* 合计金额  */
	var je = $(".commodity_box .selects .qu_su"); /* 全部商品单价  */
	var je1 = $(".commodity_box .selects .zi");  /* 全部商品数量  */
	var je2 = $(".commodity_box .selects .pitch_on");  /* 全部商品数量  */
	je2.each(function(){
		var cart_id = $(this).attr('cart_id');
		cart_id_copy[cart_id] = '';
	})
	var TotalJe = 0;
	for(var i=0; i<je.length; i++)
	{
		var n = je.eq(i).text();
		var n1 = je1.eq(i).text();
		TotalJe = TotalJe + (parseFloat(n)*parseFloat(n1));
		
	}
	$("#total_price b").text(TotalJe.toFixed(2)); /* 合计金额  */
};

//选择结算商品

$(".selects em").click(function(){
	var su = $(this).attr("aem");
	var carts_id=$(this).attr("cart_id");
	var totalH = $("#total_price b").text(); /* 合计金额  */
	if(su == 0){
		/* 单选商品  */
		if($(this).hasClass("pitch_on")){
			/*去该店全选*/
			$(this).parents("ul").siblings(".selects").find("em").removeClass("pitch_on");
			/*去底部全选*/
			$("#all_pitch_on").removeClass("pitch_on");
			$(this).removeClass("pitch_on");
			reduceMod($(this),totalH);
			cart_id[carts_id]="";
			delete cart_id[carts_id];
		}else{
			$(this).addClass("pitch_on");
			var n = $(this).parents("ul").children().find(".pitch_on");
			var n1 = $(this).parents("ul").children();
			ty_plusMod($(this),totalH,0,noX);
			cart_id[carts_id]="";
			/*该店商品全选中时*/
			if(n.length == n1.length){
				$(this).parents("ul").siblings(".selects").find("em").addClass("pitch_on");
			}
			/*商品全部选中时*/
			var fot = $(".commodity_list_box .tite_tim .pitch_on");
			var fot1 = $(".commodity_list_box .tite_tim em");
			if(fot.length == fot1.length)
			$("#all_pitch_on").addClass("pitch_on");
		}
	}else{
		/* 全选该店铺  */
		if($(this).hasClass("pitch_on")){
			/*去底部全选*/
			$("#all_pitch_on").removeClass("pitch_on");
			$(this).removeClass("pitch_on");
			$(this).parent().siblings("ul").find("em").removeClass("pitch_on");
			commodityReduceMod($(this),totalH);
			delete cart_id[carts_id];
		}else{
			commodityReduceMod($(this),totalH);

			$(this).addClass("pitch_on");
			
			$(this).parent().siblings("ul").find("em").addClass("pitch_on");
			
			if(ty_plus != NaN && ty_plus != undefined){
				totalH = parseFloat(totalH)-parseFloat(ty_plus);
			}
			
			commodityty_plusMod($(this),totalH);
			cart_id[carts_id]="";
			/*商品全部选中时*/
			var fot = $(".commodity_list_box .tite_tim .pitch_on");
			var fot1 = $(".commodity_list_box .tite_tim em");
			if(fot.length == fot1.length)
			$("#all_pitch_on").addClass("pitch_on");
			
		}
	}
	
	//计算选择数值
	number();
	
});	
/* 底部全选  */
var bot = 0;
$("#all_pitch_on").click(function(){
	if(bot == 0){
		$(this).addClass("pitch_on");
		allThis.removeClass("pitch_on");
		allThis.addClass("pitch_on");
		/*总价格*/
		commodityWhole();
		bot = 1;
		//重新加入属性对象
		for(var key in cart_id_copy){
			cart_id[key]="";
		}
	}else{
		$(this).removeClass("pitch_on");
		allThis.removeClass("pitch_on");
		$("#total_price b").text("0");
		bot = 0;
		//移除全部对象
		for(var key in cart_id){
			delete cart_id[key];
		}
	}
	//计算选择数值
	number();
});

function number() {
	var num=0;
	for(var key in cart_id){
		num++;
	}
	if(num > 0){
		$("#confirm_cart").removeAttr('disabled');
	}else{
		$("#confirm_cart").attr('disabled',true);
	}
	//将选择的放入到计算里面
	$("#confirm_cart").html("确认");
}
	
/* 编辑商品  */
var topb = 0;
$("#header-photo").click(function(){
	if(topb == 0){
		$(this).text("完成");
		$(".total_amount").hide(); /* 合计  */
		$("#confirm_cart").hide(); /* 结算  */
		$("#confirm_cart1").show(); /* 删除 */
		topb = 1;
	}else{
		topb = 0;
		$(this).text("编辑");
		$(".total_amount").show(); /* 合计  */
		$("#confirm_cart").show(); /* 结算  */
		$("#confirm_cart1").hide(); /* 删除 */
		allThis.removeClass("pitch_on"); /* 取消所有选择  */
		$("#all_pitch_on").removeClass("pitch_on"); /* 取消所有选择  */
	}
	
});
/* 加减  */

function reducew(obj){
	//减
	var $this = $(obj);
	var totalH = $("#total_price b").text(); /* 合计金额  */
	var ise = $this.siblings("span").text();
	var gc_id = $this.siblings("input").val();
	if(noX <= 0){
		noX = 0;
	}else{
		noX--;
	};
	var n =parseInt(ise)-1;
	var state = goods_count_adjust(gc_id,n);
	if(state == 1){
		if(parseInt(ise) <= 1){
			$this.siblings("span").text("1");
		}else{
			var n =parseInt(ise)-1;
			$this.siblings("span").text(n);
			$this.parent().parent().children("em").attr('num',n);
			if($this.parent().parent().children("em").hasClass("pitch_on")){
				var mo = $this.parent().parent().children("em");
				reduceMod(mo,totalH,2,noX);
				noX=0;
			}
			
		}
	}
};

function ty_plusw(obj){
	//加
	var $this = $(obj);
	var totalH = $("#total_price b").text(); /* 合计金额  */
	var ise = $this.siblings("span").text();
	var gc_id = $this.siblings("input").val();
	var n =parseInt(ise)+1;
	noX++;
	
	var state = goods_count_adjust(gc_id,n);
	if(state == 1){
		$this.siblings("span").text(n);
		$this.parent().parent().children("em").attr('num',n);
		if($this.parent().parent().children("em").hasClass("pitch_on")){
			var mo = $this.parent().parent().children("em");
			ty_plusMod(mo,totalH,2,noX);
			noX=0;
		}
	}
}
// function goods_count_adjust(id,num,type){
function goods_count_adjust(id,num){
	var state = true;
	// $.ajax({
	// 	url:webRoot + "/line/cart/update_to_cart.html",
	// 	type:'POST',
	// 	async : false,
	// 	data:{id:id,num:num},
	// 	success:function(res){
	// 		if(res.code){
	// 			state = '1';
	// 		}else{
	// 			state = '0';
	// 			// plus.nativeUI.toast(res.msg);
	// 			hui.toast(res.msg);
	// 		}
	// 	}
	// });
	return state;
}
// 提交结算
$("#confirm_cart").click(function(){
	var state = $(this).attr('disabled');
	if(state){
		return ;
	}
	var ids = new Object();
	$(".pitch_on").each(function(){
		var cart_num = $(this).attr('num');
		var cart_price = $(this).attr('price');
		var cart_id = $(this).attr('cart_id');
		if(cart_id != 0 && cart_id != null){
			ids[cart_id] = cart_id +','+cart_num+','+cart_price;
		}
	});
	dialogs.loading({
	    content:'数据提交中',
	    time:30000
	});
	$.post(cartUrl,ids,function(res){
		dialogs.hide();
		// var res = JSON.parse(result);
		if(res.code){
			window.location.href = res.url;
		}else{
			dialogs.alert('',{
				content:res.msg
			})
		}
	},'JSON');
});
 //删除 
function big_cart_remove(){
	var ids = new Object();
	$(".pitch_on").each(function(){
		var cart_id = $(this).attr('cart_id');
		var cart_price = $(this).attr('price');
		var cart_num = $(this).attr('num');
		if(cart_id != 0 && cart_id != null){
			ids[cart_id] = cart_id+','+cart_num+','+cart_price;
		}
	});
	$.post(webRoot + "/line/cart/del_cart.html",ids,function(res){
		// var res = JSON.parse(result);
		if(res.code){
			$(".commodity_list_term .pitch_on").parent().remove();
			$(".commodity_list .tite_tim > em.pitch_on").parents(".commodity_box").remove();	
		}else{
			hui.toast(res.msg);
			// plus.nativeUI.toast(res.msg);
		}
	},'JSON');
} 
