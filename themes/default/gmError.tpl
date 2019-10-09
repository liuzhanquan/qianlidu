<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{$config['sitename']}</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" >
<link rel="stylesheet" type="text/css" href="/static/default/css/home.css">
<script type="text/javascript" src="/static/js/jquery.js"></script>
<script type="text/javascript" src="/static/js/do.js"></script>
<script type="text/javascript" src="/static/js/package.js" data-path="/static/js/" data-root="/{$Request.controller}/"  data-src="/static/admin/js/common"></script>
</head>
<body>
<style type="text/css">
body{background: #fff;}
</style>
<div class="user-empty agent-list-panel">
	<i class="iconfont">&#xe60f;</i>
	<h3><?php echo(strip_tags($msg));?></h3>
	<p style="margin-top: 15px;"><?php echo(strip_tags($submsg));?></p>
</div>