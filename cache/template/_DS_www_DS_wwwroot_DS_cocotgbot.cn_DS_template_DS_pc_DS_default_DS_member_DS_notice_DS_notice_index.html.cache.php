<?php if ($fn_include = $this->_include("mheader.html")) include($fn_include); ?>

<div class="portlet light ">
    <div class="portlet-title tabbable-line">
        <ul class="nav nav-tabs" style="float:left;">
            <?php if (isset($type) && is_array($type) && $type) { $key_t=-1;$count_t=dr_count($type);foreach ($type as $i=>$t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0; ?>
            <li class="<?php if ($param['tid']==$i) { ?>active<?php } ?>">
                <a href="<?php echo $t['url']; ?>"> <?php echo $t['icon'];  echo $t['name']; ?> </a>
            </li>
            <?php } } ?>
        </ul>
    </div>
    <div class="portlet-body">

        <ul class="feeds fc-checkable">
            <?php if (isset($list) && is_array($list) && $list) { $key_t=-1;$count_t=dr_count($list);foreach ($list as $t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0;?>
            <li>
                <div class="col1">
                    <div class="cont">
                        <div class="cont-col1">
                            <span class="label label-sm label-success">
                                <?php echo dr_notice_icon($t['type']); ?>
                            </span>
                        </div>
                        <div class="cont-col2">
                            <div class="desc">

                                <?php if ($t['url']) { ?>
                                <a href="<?php echo $t['url']; ?>" target="_blank">
                                    <?php echo $t['content']; ?>
                                    <i class="fa fa-link"></i>
                                </a>
                                <?php } else {  echo $t['content'];  } ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col2">
                    <div class="date"> <?php echo dr_fdate($t['inputtime']); ?> </div>
                </div>
            </li>
            <?php } } ?>
        </ul>

        <?php if ($mypages) { ?>
        <div class="fc-pages text-center"><?php echo $mypages; ?></div>
        <?php } ?>
    </div>
</div>

<?php if ($fn_include = $this->_include("mfooter.html")) include($fn_include); ?>