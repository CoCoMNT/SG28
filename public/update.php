<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>迅睿CMS系统升级程序</title>
    <style>
        .container {
            width: 60%;
            margin: 10% auto 0;
            background-color: #f0f0f0;
            padding: 2% 5%;
            border-radius: 10px;
            text-align: center;
        }

        a {
            color: #20a53a
        }
    </style>
</head>
<body>
<div class="container">
    <h2>数据无价，一定要先备份文件和数据库</h2>
    <?php
    $vs = 0;
    $sn = md5(__FILE__).time();
    $webpath = dirname(__FILE__);
    $rootpath = dirname(dirname(__FILE__));
    if (is_file( $webpath. '/dayrui/My/Config/Install.sql')) {
        $my = $webpath. '/dayrui/My/';
    } elseif (is_file( $rootpath. '/dayrui/My/Config/Install.sql')) {
        $vs = 1;
        $my = $rootpath. '/dayrui/My/';
    } else {
        ?>
        <h3>由于你系统目录做过变更，当前无法找到系统版本信息，请登录CMS后台-服务-版本升级，下载离线包</h3>
        <?php
    }
    if (!is_file($my.'Config/Version.php')) {
        $url = 'https://www.xunruicms.com/down.php?is_dev_update=1';
    } else {
        $ver = require $my.'Config/Version.php';
        $url = 'https://www.xunruicms.com/member.php?sn='.$sn.'&action=down&cid='.$ver['id'].'&is_update=v'.$ver['version'].'&vs='.$vs.'&php='.PHP_VERSION;
    }
    ?>
    <h3>迅睿CMS系统升级程序：
        <a class="callusbtn" href="<?php echo $url;?>">单击下载升级包</a>
    </h3>
    <p>将下载后的离线包上传到网站根目录</p>
    <p>在根目录解压zip文件</p>
    <p>登录cms后台</p>
    <p>系统更新，点击最后一项，更新升级脚本</p>
</div>

</body>
</html>