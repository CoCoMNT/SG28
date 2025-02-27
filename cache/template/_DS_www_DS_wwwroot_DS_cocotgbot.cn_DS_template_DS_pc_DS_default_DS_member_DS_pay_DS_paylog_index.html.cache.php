<?php if ($fn_include = $this->_include("mheader.html")) include($fn_include); ?>

<div class="portlet light ">
    <div class="portlet-title tabbable-line">
        <ul class="nav nav-tabs" style="float:left;">
            <?php if (isset($type) && is_array($type) && $type) { $key_t=-1;$count_t=dr_count($type);foreach ($type as $i=>$t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0; ?>
            <li class="<?php if ($param['tid']==$i) { ?>active<?php } ?>">
                <a href="<?php echo $t['url']; ?>"> <?php echo $t['name']; ?> </a>
            </li>
            <?php } } ?>
        </ul>
    </div>
    <div class="portlet-body">
        <div class="fc-table-note">
            账户余额：￥<?php echo number_format($member['money'], 2); ?>元
        </div>
        <div class="table-scrollable">
            <table class="table table-striped table-bordered table-hover table-checkable dataTable">
                <thead>
                <tr class="heading">
                    <th style="text-align:center" width="70" class="<?php echo dr_sorting('mid'); ?>" name="mid">类型</th>
                    <th width="150" class="<?php echo dr_sorting('inputtime'); ?>" name="inputtime">时间</th>
                    <th class="<?php echo dr_sorting('title'); ?>" name="title">说明</th>
                    <th width="120" class="<?php echo dr_sorting('value'); ?>" name="value" style="padding-left:15px">金额</th>
                    <th style="text-align:center" width="90" class="<?php echo dr_sorting('type'); ?>" name="type">付款方式</th>
                    <th style="text-align:center" width="80" class="<?php echo dr_sorting('status'); ?>" name="status">状态</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($list) && is_array($list) && $list) { $key_t=-1;$count_t=dr_count($list);foreach ($list as $t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0;?>
                <tr class="odd gradeX" id="dr_row_<?php echo $t['id']; ?>">
                    <td style="text-align:center"><?php echo \Phpcmf\Service::M('Pay','pay')->paytype($t['mid']) ?></td>
                    <td><?php echo dr_date($t['inputtime']); ?></td>
                    <td><a href="<?php echo dr_member_url('pay/paylog/show', ['id'=>$t['id']]); ?>" class="tooltips" data-container="body" data-placement="top" data-original-title="<?php echo str_replace('"', '', $t['title']); ?>"><?php echo dr_strcut($t['title'], 30); ?></a></td>
                    <td><b><?php echo dr_pay_money_html($t['value']); ?></b></td>
                    <td style="text-align:center"><?php echo dr_pay_type_html($t['type']); ?></td>
                    <td style="text-align:center"><?php echo \Phpcmf\Service::M('Pay','pay')->paystatus($t) ?></td>
                    <td>
                        <?php if ($t['status'] == 1) { ?>
                        <a href="<?php echo dr_member_url('pay/paylog/show', ['id'=>$t['id']]); ?>" class="tooltips" data-container="body" data-placement="top" data-original-title="<?php echo str_replace('"', '', $t['result']); ?>"><?php echo dr_strcut($t['result'], 30); ?></a>
                        <?php } else { ?>
                        <a class="label label-danger" target="_blank" href="<?php echo ROOT_URL; ?>index.php?s=api&c=pay&id=<?php echo $t['id']; ?>"> <i class="fa fa-rmb"></i> 立即处理 </a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } } ?>
                </tbody>
            </table>
        </div>

        <?php if ($mypages) { ?>
        <div class="fc-pages text-center"><?php echo $mypages; ?></div>
        <?php } ?>
    </div>
</div>



<?php if ($fn_include = $this->_include("mfooter.html")) include($fn_include); ?>