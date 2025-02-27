<!DOCTYPE html>
<!--[if IE 8]> <html lang="zh-cn" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="zh-cn" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="zh-cn">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title><?php echo $meta_title; ?></title>
	<meta content="<?php echo $meta_keywords; ?>" name="keywords" />
	<meta content="<?php echo $meta_description; ?>" name="description" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<link href="<?php echo THEME_PATH; ?>assets/icon/css/icon.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo THEME_PATH; ?>assets/global/css/admin.min.css?v=<?php echo CMF_UPDATE_TIME; ?>" rel="stylesheet" type="text/css" />
	<!-- 系统关键js(所有自建模板必须引用) -->
	<script type="text/javascript">var is_mobile_cms = '<?php echo IS_MOBILE; ?>';</script>
	<script src="<?php echo LANG_PATH; ?>lang.js" type="text/javascript"></script>
	<script src="<?php echo THEME_PATH; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<script src="<?php echo THEME_PATH; ?>assets/js/cms.js" type="text/javascript"></script>
	<!-- 系统关键js结束 -->
	<script src="<?php echo THEME_PATH; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo HOME_THEME_PATH; ?>web/scripts/app.min.js" type="text/javascript"></script>
</head>
<body class="page-container-bg-solid">

<div class="page-404-full-page" style="padding-top:10px">
<div class="row">
	<div class="col-xs-12 page-404">
		<?php if ($mark==1) { ?>
		<div class="admin_msg number font-green-turquoise"> <i class="fa fa-check-circle-o"></i> </div>
		<?php } else if ($mark==2) { ?>
		<div class="admin_msg number font-blue" > <i class="fa fa-info-circle"></i> </div>
		<?php } else { ?>
		<div class="admin_msg number font-red"> <i class="fa fa-times-circle-o"></i> </div>
		<?php } ?>
		<div class="details">
			<h4><?php echo $msg; ?></h4>
			<p class="alert_btnleft">
				<?php if ($url) { ?>
				<a href="<?php echo $url; ?>"><?php echo dr_lang('如果您的浏览器没有自动跳转，请点击这里'); ?></a>
				<meta http-equiv="refresh" content="<?php echo $time; ?>; url=<?php echo $url; ?>">
				<?php } else { ?>
				<a href="<?php echo $backurl; ?>" >[<?php echo dr_lang('点击返回上一页'); ?>]</a>
				<?php } ?>
			</p>

		</div>
	</div>
</div>
</div>

</body>
</html>