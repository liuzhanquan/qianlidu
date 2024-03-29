// define(["jquery", "underscore", "webuploader", "jquery.jplayer", "bootstrap"], function(a, b, c) {
Do("jquery", "underscore", "webuploader", "jquery.jplayer", function (a, b, c) {
	var d = {
		defaultoptions: {
			direct: !1,
			global: !1,
			dest_dir: "",
			callback: null,
			type: "image",
			multiple: !0,
			uploader: {}
		},
		uploader: {},
		show: function(a, b) {
			return this.init(a, b)
		},
		init: function(b, c) {
			var d = this;
			return d.options = a.extend({}, d.defaultoptions, c), d.options.callback = b, this.options.global ? this.options.global = "global" : this.options.global = "", document.cookie = "__fileupload_type=" + escape(this.options.type), document.cookie = "__fileupload_dest_dir=" + escape(this.options.dest_dir), document.cookie = "__fileupload_global=" + escape(this.options.global), a("#modal-webuploader").remove(), 0 == a("#modal-webuploader").length && a(document.body).append(d.buildHtml().mainDialog), d.modalobj = a("#modal-webuploader"), d.modalobj.modal("show"), d.modalobj.on("shown.bs.modal", function() {
				a(this).data("init") || ("image" == d.options.type && (d.initRemote(), d.initLocal()), "audio" == d.options.type && d.initLocalAudio(), d["init" + d.options.type.substring(0, 1).toUpperCase() + d.options.type.substring(1) + "Uploader"]())
			}), d.modalobj
		},
		initRemote: function() {
			var b = this;
			b.modalobj.find("#li_network").removeClass("hide"), b.modalobj.find(".modal-body").append(b.buildHtml().remoteDialog), b.modalobj.find(".btn-primary").click(function() {
				var c = b.modalobj.find("#networkurl").val();
				c.length > 0 && a.getJSON("./index.php?c=utility&a=file&do=fetch", {
					url: c
				}, function(a) {
					a.message && alert(a.message), a && (b.finish([a]), a = {})
				})
			})
		},
		initLocal: function() {
			var a = this;
			a.modalobj.find("#li_history_image").removeClass("hide"), a.modalobj.find("#history_image")[0] || a.modalobj.find(".modal-body").append(this.buildHtml().localDialog);
			a.modalobj.find("#history_image");
			a.localPage(1)
		},
		localPage: function(c) {
			var d = this,
				e = d.modalobj.find("#select-year .btn-info").data("id"),
				f = d.modalobj.find("#select-month .btn-info").data("id"),
				g = d.modalobj.find("#history_image");
			return a.getJSON("./index.php?c=utility&a=file&do=local", {
				page: c,
				year: e,
				month: f
			}, function(c) {
				c = c.message, g.find(".history-content").css("text-align", "center").html('<i class="fa fa-spinner fa-pulse fa-5x"></i>'), g.find("#image-list-pager").html(""), b.isEmpty(c.items) ? g.find(".history-content").css("text-align", "center").html('<i class="fa fa-info-circle"></i> 暂无数据') : (g.data("attachment", c.items), g.find(".history-content").empty(), g.find(".history-content").html(b.template(d.buildHtml().localDialogLi)(c)), g.find("#image-list-pager").html(c.page), g.find(".pagination a").click(function() {
					d.localPage(a(this).attr("page"))
				}), g.find(".img-list li").click(function(b) {
					d.selectImage(a(b.target).parents("li"))
				}), g.find(".img-list li .btnClose").unbind().click(function() {
					var b = a(this),
						c = a(this).data("id");
					return c ? (a.post("./index.php?c=utility&a=file&do=delete", {
						id: c
					}, function(a) {
						"success" != a ? alert(a) : (b.parent().remove(), util.message("删除成功", "", "success"))
					}), !1) : !1
				}))
			}), d.modalobj.find(".btn-select").unbind("click").click(function() {
				return a(this).hasClass("btn-info") ? !1 : ("month" == a(this).data("type") && a(this).data("id") > 0 && (d.modalobj.find("#select-year .btn-info").data("id") || (d.modalobj.find("#select-year .btn-select").removeClass("btn-info"), d.modalobj.find("#select-year .btn-select").eq(1).addClass("btn-info"))), a(this).siblings().removeClass("btn-info"), a(this).addClass("btn-info"), void d.localPage(1))
			}), g.find(".btn-primary").unbind("click").click(function() {
				var b = [];
				g.find(".img-item-selected").each(function() {
					b.push(d.modalobj.find("#history_image").data("attachment")[a(this).attr("attachid")]), a(this).removeClass("img-item-selected")
				}), d.finish(b)
			}), !1
		},
		selectImage: function(b) {
			var c = this;
			a(b).toggleClass("img-item-selected"), c.options.multiple || c.modalobj.find("#history_image").find(".btn-primary").trigger("click")
		},
		initLocalAudio: function() {
			var a = this;
			a.modalobj.find("#li_history_audio").removeClass("hide"), a.modalobj.find("#history_audio")[0] || a.modalobj.find(".modal-body").append(this.buildHtml().localAudioDialog);
			a.modalobj.find("#li_history_audio");
			a.localAudioPage(1)
		},
		localAudioPage: function(c) {
			var d = this,
				e = d.modalobj.find("#history_audio");
			return a.getJSON("./index.php?c=utility&a=file&do=local&type=audio&pagesize=5", {
				page: c
			}, function(c) {
				c = c.message, e.find(".history-content").html('<i class="fa fa-spinner fa-pulse"></i>'), b.isEmpty(c.items) || (e.data("attachment", c.items), e.find(".history-content").empty(), e.find(".history-content").html(b.template(d.buildHtml().localAudioDialogLi)(c)), e.find("#image-list-pager").html(c.page), e.find(".pagination a").click(function() {
					d.localAudioPage(a(this).attr("page"))
				}), e.find(".js-btn-select").click(function(b) {
					a(b.target).toggleClass("btn-primary"), d.options.multiple || d.modalobj.find("#history_audio").find(".modal-footer .btn-primary").trigger("click")
				}), d.playAudio())
			}), e.find(".modal-footer .btn-primary").unbind("click").click(function() {
				var b = [];
				e.find(".history-content .btn-primary").each(function() {
					b.push(d.modalobj.find("#history_audio").data("attachment")[a(this).attr("attachid")]), a(this).removeClass("btn-primary")
				}), d.finish(b)
			}), !1
		},
		playAudio: function() {
			var b = this,
				c = b.modalobj.find("#history_audio");
			a(".audio-player-play").click(function() {
				var b = a(this).attr("attach");
				if (b) {
					if (a("#player")[0]) var d = a("#player");
					else {
						var d = a('<div id="player"></div>');
						a(document.body).append(d)
					}
					d.data("control", a(this)), d.jPlayer({
						playing: function() {
							a(this).data("control").find("p").removeClass("fa-play").addClass("fa-stop")
						},
						pause: function(b) {
							a(this).data("control").find("p").removeClass("fa-stop").addClass("fa-play")
						},
						swfPath: "resource/components/jplayer",
						supplied: "mp3,wma,wav,amr",
						solution: "html, flash"
					}), d.jPlayer("setMedia", {
						mp3: a(this).attr("attach")
					}).jPlayer("play"), a(this).find("p").hasClass("fa-stop") ? d.jPlayer("stop") : (c.find(".fa-stop").removeClass("fa-stop").addClass("fa-play"), d.jPlayer("setMedia", {
						mp3: a(this).attr("attach")
					}).jPlayer("play"))
				}
			})
		},
		initImageUploader: function() {
			function b(b) {
				var c = a('<li id="' + b.id + '"><p class="title">' + b.name + '</p><p class="imgWrap"></p></li>'),
					e = a('<div class="file-panel"><span class="cancel">删除</span></div>').appendTo(c),
					f = c.find("p.progress span"),
					g = c.find("p.imgWrap"),
					h = a('<p class="error"></p>'),
					j = function(a) {
						switch (a) {
						case "exceed_size":
							text = "文件大小超出";
							break;
						case "interrupt":
							text = "上传暂停";
							break;
						default:
							text = "上传失败，请重试"
						}
						h.text(text).appendTo(c)
					};
				"invalid" === b.getStatus() ? j(b.statusText) : (g.text("预览中"), d.makeThumb(b, function(b, c) {
					if (b) return void g.text("不能预览");
					var d = a('<img src="' + c + '">');
					g.empty().append(d)
				}, thumbnailWidth, thumbnailHeight), percentages[b.id] = [b.size, 0], b.rotation = 0), b.on("statuschange", function(a, d) {
					"progress" === d ? f.hide().width(0) : "queued" === d && (c.off("mouseenter mouseleave"), e.remove()), "error" === a || "invalid" === a ? (j(b.statusText), percentages[b.id][1] = 1) : "interrupt" === a ? j("interrupt") : "queued" === a ? percentages[b.id][1] = 0 : "progress" === a && (h.remove(), f.css("display", "block")), c.removeClass("state-" + d).addClass("state-" + a)
				}), c.on("mouseenter", function() {
					e.stop().animate({
						height: 30
					})
				}), c.on("mouseleave", function() {
					e.stop().animate({
						height: 0
					})
				}), e.on("click", "span", function() {
					var c, e = a(this).index();
					switch (e) {
					case 0:
						return void d.removeFile(b);
					case 1:
						b.rotation += 90;
						break;
					case 2:
						b.rotation -= 90
					}
					supportTransition ? (c = "rotate(" + b.rotation + "deg)", g.css({
						"-webkit-transform": c,
						"-mos-transform": c,
						"-o-transform": c,
						transform: c
					})) : g.css("filter", "progid:DXImageTransform.Microsoft.BasicImage(rotation=" + ~~ (b.rotation / 90 % 4 + 4) % 4 + ")")
				}), i.options.multiple && k.find(".fileinput-button").show(), c.insertBefore(k.find(".fileinput-button"))
			}
			function e(b) {
				var c = a("#" + b.id);
				delete percentages[b.id], f(), c.off().find(".file-panel").off().end().remove()
			}
			function f() {
				var b, c = 0,
					d = 0,
					e = p.children();
				a.each(percentages, function(a, b) {
					d += b[0], c += b[0] * b[1]
				}), b = d ? c / d : 0, e.eq(0).text(Math.round(100 * b) + "%"), e.eq(1).css("width", Math.round(100 * b) + "%"), g()
			}
			function g() {
				var a, b = "";
				"ready" === state ? b = "选中" + fileCount + "张图片，共" + c.formatSize(fileSize) + "。" : "confirm" === state ? (a = d.getStats(), a.uploadFailNum && (b = "已上传" + a.successNum + "张图片," + a.uploadFailNum + '张图片上传失败，<a class="retry" href="#">重新上传</a>失败图片或<a class="ignore" href="#">忽略</a>')) : (a = d.getStats(), b = "共" + fileCount + "张（" + c.formatSize(fileSize) + "），已上传" + a.successNum + "张", a.uploadFailNum && (b += "，失败" + a.uploadFailNum + "张")), m.html(b)
			}
			function h(a) {
				var b;
				if (a !== state) {
					switch (n.removeClass("state-" + state), n.addClass("state-" + a), state = a, state) {
					case "pedding":
						o.removeClass("element-invisible"), k.hide(), d.refresh();
						break;
					case "ready":
						o.addClass("element-invisible"), k.show(), d.refresh();
						break;
					case "uploading":
						p.show(), n.text("暂停上传");
						break;
					case "paused":
						p.hide(), n.text("确定使用");
						break;
					case "confirm":
						if (p.hide(), n.text("确定使用").addClass("disabled"), b = d.getStats(), b.successNum && !b.uploadFailNum) return void h("finish");
						break;
					case "finish":
						if (n.removeClass("disabled"), b = d.getStats(), b.successNum) {
							if (d.uploadedFiles.length > 0) return i.finish(d.uploadedFiles), void d.resetUploader()
						} else state = "done", location.reload()
					}
					g()
				}
			}
			var i = this;
			i.modalobj.find("#li_upload a").html("上传图片"), i.modalobj.find(".modal-body").append(this.buildHtml().uploaderDialog);
			var j = a("#uploader"),
				k = a('<ul class="filelist"><li class="fileinput-button js-add-image" id="filePicker2" style="display:none;"> <a href="javascript:;" class="fileinput-button-icon">+</a></li></ul>').appendTo(j.find(".queueList")),
				l = j.find(".statusBar"),
				m = l.find(".info"),
				n = j.find(".uploadBtn"),
				o = j.find(".placeholder"),
				p = l.find(".progress").hide();
			j.find(".btn-primary");
			fileCount = 0, fileSize = 0, ratio = window.devicePixelRatio || 1, thumbnailWidth = 110 * ratio, thumbnailHeight = 110 * ratio, state = "pedding", percentages = {}, supportTransition = function() {
				var a = document.createElement("p").style,
					b = "transition" in a || "WebkitTransition" in a || "MozTransition" in a || "msTransition" in a || "OTransition" in a;
				return a = null, b
			}(), d;
			var q = {
				pick: {
					id: "#filePicker",
					label: "点击选择图片",
					multiple: !0
				},
				dnd: "#dndArea",
				paste: "#uploader",
				swf: "./resource/componets/webuploader/Uploader.swf",
				server: "./index.php?c=utility&a=file&do=upload&type=image",
				chunked: !1,
				compress: !1,
				accept: {
					title: "Images",
					extensions: "gif,jpg,jpeg,bmp,png",
					mimeTypes: "image/*"
				},
				fileNumLimit: 30,
				fileSizeLimit: 4194304,
				fileSingleSizeLimit: 125829120,
				auto: !1
			};
			q = a.extend({}, q, i.options.uploader), q.pick.multiple = i.options.multiple, d = c.create(q), d.uploadedFiles = [], d.addButton({
				id: "#filePicker2",
				label: "+",
				multiple: i.options.multiple
			}), accept = 0, d.resetUploader = function() {
				fileCount = 0, fileSize = 0, accept = 0, d.uploadedFiles = [], a.each(d.getFiles(), function(a, b) {
					e(b)
				}), d.refresh(), d.reset(), n.removeClass("disabled"), h("pedding")
			}, d.onUploadProgress = function(b, c) {
				var d = a("#" + b.id),
					e = d.find(".progress span");
				e.css("width", 100 * c + "%"), percentages[b.id][1] = c, fileid = b.id, f()
			}, d.onFileQueued = function(a) {
				fileCount++, fileSize += a.size, 1 === fileCount && (o.addClass("element-invisible"), l.show()), b(a), h("ready"), f()
			}, d.onFileDequeued = function(a) {
				fileCount--, fileSize -= a.size, fileCount || h("pedding"), e(a), f()
			}, d.on("all", function(a) {
				switch (a) {
				case "uploadFinished":
					h("confirm");
					break;
				case "startUpload":
					h("uploading");
					break;
				case "stopUpload":
					h("paused")
				}
			}), d.on("uploadSuccess", function(b, c) {
				return c.message ? (alert(c.message), void d.resetUploader()) : void(c.attachment && (accept++, d.uploadedFiles.push(c), a("#" + b.id).append('<span class="success" style="line-height: 50px;">' + c.width + "x" + c.height + "</span>"), a(".accept").text("成功上传 " + accept + " 张图片")))
			}), d.onError = function(a) {
				return "Q_EXCEED_SIZE_LIMIT" == a ? void alert("错误信息: 图片大于 1M 无法上传.") : "F_DUPLICATE" == a ? void alert("错误信息: 不能重复上传图片.") : void alert("Eroor: " + a)
			}, n.on("click", function() {
				return a(this).hasClass("disabled") ? !1 : void("ready" === state ? d.upload() : "paused" === state ? d.upload() : "uploading" === state && d.stop())
			}), m.on("click", ".retry", function() {
				d.retry()
			}), m.on("click", ".ignore", function() {}), n.addClass("state-" + state), f()
		},
		initAudioUploader: function() {
			function b(b) {
				var e = a('<li id="' + b.id + '"><p class="title" style="top:40px;">' + b.name + '</p><p class="imgWrap" style="top:30px;"></p></li>'),
					f = a('<div class="file-panel"><span class="cancel">删除</span></div>').appendTo(e),
					g = e.find("p.progress span"),
					h = e.find("p.imgWrap"),
					j = a('<p class="error"></p>'),
					l = function(a) {
						switch (a) {
						case "exceed_size":
							text = "文件大小超出";
							break;
						case "interrupt":
							text = "上传暂停";
							break;
						default:
							text = "上传失败，请重试"
						}
						j.text(text).appendTo(e)
					};
				"invalid" === b.getStatus() ? l(b.statusText) : (h.text(c.formatSize(b.size) + " kb"), percentages[b.id] = [b.size, 0], b.rotation = 0), b.on("statuschange", function(a, c) {
					"progress" === c ? g.hide().width(0) : "queued" === c && (e.off("mouseenter mouseleave"), f.remove()), "error" === a || "invalid" === a ? (l(b.statusText), percentages[b.id][1] = 1) : "interrupt" === a ? l("interrupt") : "queued" === a ? percentages[b.id][1] = 0 : "progress" === a && j.remove(), e.removeClass("state-" + c).addClass("state-" + a)
				}), e.on("mouseenter", function() {
					f.stop().animate({
						height: 30
					})
				}), e.on("mouseleave", function() {
					f.stop().animate({
						height: 0
					})
				}), f.on("click", "span", function() {
					var c, e = a(this).index();
					switch (e) {
					case 0:
						return void d.removeFile(b);
					case 1:
						b.rotation += 90;
						break;
					case 2:
						b.rotation -= 90
					}
					supportTransition ? (c = "rotate(" + b.rotation + "deg)", h.css({
						"-webkit-transform": c,
						"-mos-transform": c,
						"-o-transform": c,
						transform: c
					})) : h.css("filter", "progid:DXImageTransform.Microsoft.BasicImage(rotation=" + ~~ (b.rotation / 90 % 4 + 4) % 4 + ")")
				}), i.options.multiple && k.find(".fileinput-button").show(), e.insertBefore(k.find(".fileinput-button"))
			}
			function e(b) {
				var c = a("#" + b.id);
				delete percentages[b.id], f(), c.off().find(".file-panel").off().end().remove()
			}
			function f() {
				var b, c = 0,
					d = 0,
					e = p.children();
				a.each(percentages, function(a, b) {
					d += b[0], c += b[0] * b[1]
				}), b = d ? c / d : 0, e.eq(0).text(Math.round(100 * b) + "%"), e.eq(1).css("width", Math.round(100 * b) + "%"), g()
			}
			function g() {
				var a, b = "";
				"ready" === state ? b = "选中" + fileCount + "个音频，共" + c.formatSize(fileSize) + "。" : "confirm" === state ? (a = d.getStats(), a.uploadFailNum && (b = "已上传" + a.successNum + "个音频," + a.uploadFailNum + '个音频上传失败，<a class="retry" href="#">重新上传</a>失败音频文件或<a class="ignore" href="#">忽略</a>')) : (a = d.getStats(), b = "共" + fileCount + "张（" + c.formatSize(fileSize) + "），已上传" + a.successNum + "个", a.uploadFailNum && (b += "，失败" + a.uploadFailNum + "个")), m.html(b)
			}
			function h(a) {
				var b;
				if (a !== state) {
					switch (n.removeClass("state-" + state), n.addClass("state-" + a), state = a, state) {
					case "pedding":
						o.removeClass("element-invisible"), k.hide(), d.refresh();
						break;
					case "ready":
						o.addClass("element-invisible"), k.show(), d.refresh();
						break;
					case "uploading":
						p.show(), n.text("暂停上传");
						break;
					case "paused":
						p.show(), n.text("继续上传");
						break;
					case "confirm":
						if (p.hide(), n.text("确定使用").addClass("disabled"), b = d.getStats(), b.successNum && !b.uploadFailNum) return void h("finish");
						break;
					case "finish":
						if (n.removeClass("disabled"), b = d.getStats(), b.successNum) {
							if (d.uploadedFiles.length > 0) return i.finish(d.uploadedFiles), void d.resetUploader()
						} else state = "done", location.reload()
					}
					g()
				}
			}
			var i = this;
			i.modalobj.find("#li_upload a").html("上传音频"), i.modalobj.find(".modal-body").append(this.buildHtml().uploaderDialog);
			var j = a("#uploader"),
				k = a('<ul class="filelist"><li class="fileinput-button js-add-image" id="filePicker2" style="display:none;"> <a href="javascript:;" class="fileinput-button-icon">+</a></li></ul>').appendTo(j.find(".queueList")),
				l = j.find(".statusBar"),
				m = l.find(".info"),
				n = j.find(".uploadBtn"),
				o = j.find(".placeholder"),
				p = l.find(".progress").hide();
			j.find(".btn-primary");
			fileCount = 0, fileSize = 0, ratio = window.devicePixelRatio || 1, state = "pedding", percentages = {}, supportTransition = function() {
				var a = document.createElement("p").style,
					b = "transition" in a || "WebkitTransition" in a || "MozTransition" in a || "msTransition" in a || "OTransition" in a;
				return a = null, b
			}(), d;
			var q = {
				pick: {
					id: "#filePicker",
					label: "点击选择音频",
					multiple: !0
				},
				dnd: "#dndArea",
				paste: "#uploader",
				swf: "./resource/componets/webuploader/Uploader.swf",
				server: "./index.php?c=utility&a=file&do=upload&type=audio",
				chunked: !1,
				compress: !1,
				accept: {
					title: "Audios",
					extensions: "mp3,wma,wav,amr",
					mimeTypes: "audio/*"
				},
				fileNumLimit: 30,
				fileSizeLimit: 6291456,
				fileSingleSizeLimit: 125829120,
				auto: !1
			};
			q = a.extend({}, q, i.options.uploader), a("#dndArea p").html("最大支持 " + c.formatSize(q.fileSizeLimit) + " MB 以内的语音 (" + q.accept.extensions + " 格式)"), q.pick.multiple = i.options.multiple, d = c.create(q), d.uploadedFiles = [], d.addButton({
				id: "#filePicker2",
				label: "+",
				multiple: i.options.multiple
			}), accept = 0, d.resetUploader = function() {
				fileCount = 0, fileSize = 0, accept = 0, d.uploadedFiles = [], a.each(d.getFiles(), function(a, b) {
					e(b)
				}), d.refresh(), d.reset(), n.removeClass("disabled"), h("pedding")
			}, d.onUploadProgress = function(b, c) {
				var d = a("#" + b.id),
					e = d.find(".progress span");
				e.css("width", 100 * c + "%"), percentages[b.id][1] = c, fileid = b.id, f()
			}, d.onFileQueued = function(a) {
				fileCount++, fileSize += a.size, 1 === fileCount && (o.addClass("element-invisible"), l.show()), b(a), h("ready"), f()
			}, d.onFileDequeued = function(a) {
				fileCount--, fileSize -= a.size, fileCount || h("pedding"), e(a), f()
			}, d.on("all", function(a) {
				switch (a) {
				case "uploadFinished":
					h("confirm");
					break;
				case "startUpload":
					h("uploading");
					break;
				case "stopUpload":
					h("paused")
				}
			}), d.on("uploadSuccess", function(b, c) {
				return c.error ? (alert(c.message), d.resetUploader(), !1) : void(c.attachment && (accept++, d.uploadedFiles.push(c), a("#" + b.id).append('<span class="success" style="line-height: 50px;">' + c.width + "x" + c.height + "</span>"), a(".accept").text("成功上传 " + accept + " 个音频")))
			}), d.onError = function(a) {
				return "Q_EXCEED_SIZE_LIMIT" == a ? void alert("错误信息: 音频大于 " + c.formatSize(q.fileSizeLimit) + " 无法上传.") : "F_DUPLICATE" == a ? void alert("错误信息: 不能重复上传音频.") : void alert("Eroor: " + a)
			}, n.on("click", function() {
				return a(this).hasClass("disabled") ? !1 : void("ready" === state ? d.upload() : "paused" === state ? d.upload() : "uploading" === state && d.stop())
			}), m.on("click", ".retry", function() {
				d.retry()
			}), m.on("click", ".ignore", function() {}), n.addClass("state-" + state), f()
		},
		finish: function(b) {
			var c = this;
			a.isFunction(c.options.callback) && (0 == c.options.multiple ? c.options.callback(b[0]) : c.options.callback(b), c.modalobj.modal("hide"))
		},
		buildHtml: function() {
			var a = {};
			return a.mainDialog = '<div id="modal-webuploader" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">\n	<div class="modal-dialog" style="width:660px;">\n		<div class="modal-content">\n			<div class="modal-header">\n				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>\n				<ul class="nav nav-pills" role="tablist">\n					<li id="li_upload" class="active" role="presentation"><a href="#upload" aria-controls="upload" role="tab" data-toggle="tab" onclick="$(\'#select\').hide();">上传</a></li>\n					<li id="li_network" class="hide" role="presentation"><a href="#network" aria-controls="network" role="tab" data-toggle="tab" onclick="$(\'#select\').hide();">提取网络图片</a></li>\n					<li id="li_history_image" class="hide" role="presentation"><a href="#history_image" aria-controls="history_image" role="tab" data-toggle="tab" onclick="$(\'#select\').show();">浏览图片</a></li>\n					<li id="li_history_audio" class="hide" role="presentation"><a href="#history_audio" aria-controls="history_audio" role="tab" data-toggle="tab" onclick="$(\'#select\').hide();">浏览音频</a></li>\n				</ul>\n			</div>\n				<div id="select" style="display: none;margin:10px 0 -10px 15px; padding-left:7px;">					<div id="select-year" style="margin-bottom:10px;">						<div class="btn-group">							<a href="javascript:;" data-id="0" data-type="year" class="btn btn-default btn-info btn-select">不限</a>							<a href="javascript:;" data-id="2016" data-type="year" class="btn btn-default btn-select">2016年</a>							<a href="javascript:;" data-id="2015" data-type="year" class="btn btn-default btn-select">2015年</a>							<a href="javascript:;" data-id="2014" data-type="year" class="btn btn-default btn-select">2014年</a>							<a href="javascript:;" data-id="2013" data-type="year" class="btn btn-default btn-select">2013年</a>						</div>					</div>					<div id="select-month">						<div class="btn-group">							<a href="javascript:;" data-id="0" data-type="month" class="btn btn-default btn-info btn-select">不限</a>							<a href="javascript:;" data-id="1" data-type="month" class="btn btn-default btn-select">1</a>							<a href="javascript:;" data-id="2" data-type="month" class="btn btn-default btn-select">2</a>							<a href="javascript:;" data-id="3" data-type="month" class="btn btn-default btn-select">3</a>							<a href="javascript:;" data-id="4" data-type="month" class="btn btn-default btn-select">4</a>							<a href="javascript:;" data-id="5" data-type="month" class="btn btn-default btn-select">5</a>							<a href="javascript:;" data-id="6" data-type="month" class="btn btn-default btn-select">6</a>							<a href="javascript:;" data-id="7" data-type="month" class="btn btn-default btn-select">7</a>							<a href="javascript:;" data-id="8" data-type="month" class="btn btn-default btn-select">8</a>							<a href="javascript:;" data-id="9" data-type="month" class="btn btn-default btn-select">9</a>							<a href="javascript:;" data-id="10" data-type="month" class="btn btn-default btn-select">10</a>							<a href="javascript:;" data-id="11" data-type="month" class="btn btn-default btn-select">11</a>							<a href="javascript:;" data-id="12" data-type="month" class="btn btn-default btn-select">12</a>						</div>					</div>				</div>			<div class="modal-body tab-content"></div>\n		</div>\n	</div>\n</div>', a.remoteDialog = '<div role="tabpanel" class="tab-pane network" id="network">\n	<div style="margin-top: 10px;">\n		<form>\n			<div class="form-group">\n				<input type="url" class="form-control" id="networkurl" placeholder="请输入网络图片地址">\n				<input type="hidden" name="network_attachment" value="" >\n				<div id="network-img" class="network-img" style="background-image:url(\'{php echo tomedia(\'images/global/nopic.jpg\');}\')">\n					<span class="network-img-sizeinfo" id="network-img-sizeinfo"></span>\n				</div>\n			</div>\n		</form>\n	</div>\n	<div class="modal-footer">\n		<button type="button" class="btn btn-primary">确认</button>\n		<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>\n	</div>\n</div>', a.localDialog = '<div role="tabpanel" class="tab-pane history" id="history_image">\n	<div class="history-content" style="height:310px"></div>\n	<div class="modal-footer">\n		<div style="float: left;">\n			<nav id="image-list-pager">\n				<ul class="pager" style="margin: 0;"></ul>\n			</nav>\n		</div>\n		<div style="float: right;">\n		<button ' + (this.options.multiple ? "" : 'style="display:none;"') + ' type="button" class="btn btn-primary">确认</button>\n' + (this.options.multiple ? '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>\n' : "") + "		</div>\n	</div>\n</div>", a.uploaderDialog = '<div role="tabpanel" class="tab-pane upload active" id="upload">\n	<div id="uploader" class="uploader">\n		<div class="queueList">\n			<div id="dndArea" class="placeholder">\n				<div id="filePicker">xx</div>\n' + (this.options.multiple ? '<p id="">或将照片拖到这里，单次最多可选30张</p>\n' : '<p id="">或将照片拖到这里</p>\n') + '			</div>\n		</div>\n		<div class="statusBar">\n			<div class="infowrap">\n				<div class="progress">\n					<span class="text">0%</span>\n					<span class="percentage"></span>\n				</div>\n				<div class="info"></div>\n				<div class="accept"></div>\n			</div>\n			<div class="btns">\n				<div class="uploadBtn btn btn-primary" style="margin-top: 4px;">确定使用</div>\n				<div class="modal-button-upload" style="float: right; margin-left: 5px;">\n					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>\n				</div>\n			</div>\n		</div>\n	</div>\n</div>', a.localDialogLi = '<ul class="img-list clearfix">\n<%var items = _.sortBy(items, function(item) {return -item.id;});%><%_.each(items, function(item) {%> \n<li class="img-item" attachid="<%=item.id%>" title="<%=item.filename%>">\n	<div class="img-container" style="background-image: url(\'<%=item.url%>\');">\n		<div class="select-status"><span></span></div>\n	</div>\n	<div class="btnClose" data-id="<%=item.id%>"><a href=""><i class="fa fa-times"></i></a></div>\n</li>\n<%});%>\n</ul>', a.localAudioDialog = '<div role="tabpanel" class="tab-pane history" id="history_audio">\n	<div style="height:310px">\n		<table class="table table-hover">\n		<thead class="navbar-inner">\n			<tr>\n				<th>标题</th>\n				<th style="width:20%;">创建时间</th>\n				<th style="width:30%;">\n					<div class="input-group input-group-sm">\n						<input type="text" class="form-control">\n						<span class="input-group-btn">\n							<button class="btn btn-default" type="button"><i class="fa fa-search" style="font-size:12px; margin-top:0;"></i></button>\n						</span>\n					</div>\n				</th>\n			</tr>\n		</thead>\n		<tbody class="history-content">\n		</tbody>\n	</table></div>\n	<div class="modal-footer">\n		<div style="float: left;">\n			<nav id="image-list-pager">\n				<ul class="pager" style="margin: 0;"></ul>\n			</nav>\n		</div>\n		<div style="float: right;">\n		<button ' + (this.options.multiple ? "" : 'style="display:none;"') + ' type="button" class="btn btn-primary">确认</button>\n' + (this.options.multiple ? '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>\n' : "") + "		</div>\n	</div>\n</div>", a.localAudioDialogLi = '<%var items = _.sortBy(items, function(item) {return -item.id;});%><%_.each(items, function(item) {%> \n<tr>\n	<td><a href="#" title="<%=item.filename%>"><%=item.filename%></a></td>\n	<td class="text-right"><%=item.createtime%></td>\n	<td class="text-right">\n		<span class="input-group-btn">\n			<button class="btn btn-default audio-player-play" type="button" attach="<%=item.url%>"><p style="margin:0px;" class="fa fa-play"></p></button>\n			<button attachid="<%=item.id%>" class="btn btn-default js-btn-select">选取</button>\n		</span>\n	</td>\n</tr>\n<%});%>\n', a
		}
	};
	return d
});