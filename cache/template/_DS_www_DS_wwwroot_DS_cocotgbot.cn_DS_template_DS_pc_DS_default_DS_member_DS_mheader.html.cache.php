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
    <link href="<?php echo THEME_PATH; ?>assets/global/css/cms.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo HOME_THEME_PATH; ?>member/css/layout.css" rel="stylesheet" type="text/css" />
    <!-- 系统关键js(所有自建模板必须引用) -->
    <script type="text/javascript">var is_mobile_cms = '<?php echo IS_MOBILE; ?>';</script>
    <script src="<?php echo LANG_PATH; ?>lang.js" type="text/javascript"></script>
    <script src="<?php echo THEME_PATH; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo THEME_PATH; ?>assets/js/cms.js" type="text/javascript"></script>
    <!-- 系统关键js结束 -->
    <script src="<?php echo THEME_PATH; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo HOME_THEME_PATH; ?>member/scripts/app.min.js" type="text/javascript"></script>
</head>
<body class="page-sidebar-closed-hide-logo page-container-bg-solid page-header-fixed page-sidebar-fixed ">
<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner ">
        <div class="page-logo">
            <a href="<?php echo SITE_URL; ?>" target="_blank">
                <img src="<?php echo THEME_PATH; ?>assets/logo.png" class="logo-default" />
            </a>
            <div class="menu-toggler sidebar-toggler">
            </div>
        </div>
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <div class="page-top">
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <?php if (dr_is_app('notice') && XR_M()->is_table_exists('member_notice')) { ?>
                    <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="fa fa-bell"></i>
                            <span class="badge badge-default dr_notice_num"> 0 </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="external dr_notice_html">
                                <h3>
                                    <span class="bold dr_notice_num"> 0 </span> 条未读提醒</h3>
                                <a href="<?php echo dr_member_url('notice/home/index'); ?>">查看</a>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                                    <?php $list_return = $this->list_tag("action=table table=member_notice isnew=1 uid=$member[uid] order=inputtime cache=0"); if ($list_return && is_array($list_return)) extract($list_return, EXTR_OVERWRITE); $count=dr_count($return); if (is_array($return) && $return) { $key=-1; foreach ($return as $t) { $key++; $is_first=$key==0 ? 1 : 0;$is_last=$count==$key+1 ? 1 : 0; ?>
                                    <li>
                                        <a <?php if ($t['url']) { ?> href="<?php echo $t['url']; ?>" target="_blank" <?php } else { ?>href="<?php echo dr_member_url('notice/home/index'); ?>"<?php } ?>>
                                            <span class="time"><?php echo dr_fdate($t['inputtime']); ?></span>
                                            <span class="details">
                                                <?php echo dr_notice_icon($t['type'], 'label-icon');  echo dr_strcut($t['content'], 20); ?>
                                            </span>
                                        </a>
                                    </li>
                                    <?php } } ?>
                                    <script>
                                        $('.dr_notice_num').html("<?php echo $count; ?>");
                                    </script>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img class="img-circle" src="<?php echo dr_avatar($member['uid']); ?>" />
                            <span class="username username-hide-on-mobile"> <?php echo $member['username']; ?> </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="<?php echo dr_member_url('account/index'); ?>">
                                    <i class="fa fa-user"></i> 我的资料 </a>
                            </li>
                            <li>
                                <a href="<?php echo dr_member_url('account/avatar'); ?>">
                                    <i class="fa fa-smile-o"></i> 我的头像 </a>
                            </li>
                            <li class="divider"> </li>
                            <li>
                                <a href="javascript:;" onclick="dr_loginout()">
                                    <i class="fa fa-sign-out"></i> 我要退出 </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"> </div>
<div class="page-container">
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse">

            <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-compact" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <li class="nav-item start <?php if (!$mymenu) { ?> active open<?php } ?>">
                    <a href="<?php echo MEMBER_URL; ?>" class="nav-link nav-toggle">
                        <i class="fa fa-home"></i>
                        <span class="title">用户中心</span>
                    </a>
                </li>
                <?php if (isset($menu) && is_array($menu) && $menu) { $key_top=-1;$count_top=dr_count($menu);foreach ($menu as $top) { $key_top++; $is_first=$key_top==0 ? 1 : 0;$is_last=$count_top==$key_top+1 ? 1 : 0; if (dr_member_menu_show($top)) { ?>
                <li class="nav-item <?php if (dr_in_array($top['id'], $mymenu)) { ?>active open<?php } ?>">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="<?php echo dr_icon($top['icon']); ?>"></i>
                        <span class="title"><?php echo $top['name']; ?></span>
                        <span class="arrow <?php if (dr_in_array($top['id'], (array)$mymenu)) { ?>open<?php } ?>"></span>
                    </a>
                    <ul class="sub-menu">
                        <?php if (isset($top['link']) && is_array($top['link']) && $top['link']) { $key_t=-1;$count_t=dr_count($top['link']);foreach ($top['link'] as $t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0; if (dr_member_menu_show($t)) { ?>
                        <li class="nav-item <?php if (dr_in_array($t['id'], $mymenu)) { ?>active open<?php } ?>">
                            <a href="<?php if ($t['url']) {  echo $t['url'];  } else {  echo dr_member_url($t['uri']);  } ?>" class="nav-link ">
                                <i class="<?php echo dr_icon($t['icon']); ?>"></i>
                                <span class="title"><?php echo $t['name']; ?></span>
                            </a>
                        </li>
                        <?php }  } } ?>
                    </ul>
                </li>
                <?php }  } } ?>
            </ul>


        </div>
    </div>
    <div class="page-content-wrapper">
        <div class="page-content">
            <?php echo $page_bar; ?>