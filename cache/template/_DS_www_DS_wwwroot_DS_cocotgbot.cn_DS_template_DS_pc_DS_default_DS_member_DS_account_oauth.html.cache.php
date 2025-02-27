<?php if ($fn_include = $this->_include("mheader.html")) include($fn_include); ?>


    <div class="row">

        <div class="col-md-12">

            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject"> 账号绑定</span>
                    </div>
                </div>
                <div class="portlet-body">


                    <div class="table-scrollable table-scrollable-borderless">
                        <table class="table table-hover table-light">
                            <thead>
                            <tr class="uppercase">
                                <th width="60" style="text-align:center"> 接入商 </th>
                                <th width="150"> 昵称 </th>
                                <th width="160"> 时间 </th>
                                <th> 操作 </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($oauth) && is_array($oauth) && $oauth) { $key_name=-1;$count_name=dr_count($oauth);foreach ($oauth as $name) { $key_name++; $is_first=$key_name==0 ? 1 : 0;$is_last=$count_name==$key_name+1 ? 1 : 0;?>
                            <tr>
                                <td style="text-align:center"> <img src="<?php echo THEME_PATH; ?>assets/oauth/<?php echo $name; ?>.png"> </td>
                                <td> <?php echo dr_html2emoji($list[$name]['nickname']); ?> </td>
                                <td> <?php echo dr_date($list[$name]['expire_at']); ?>  </td>
                                <td>
                                    <?php if ($list[$name]) { ?>
                                    <a href="<?php echo \Phpcmf\Service::L('Router')->oauth_url($name, 'bang'); ?>" class="btn btn-xs blue"> <i class="fa fa-user-plus"></i> 更新</a>
                                    <a href="javascript:dr_ajax_url('<?php echo dr_member_url('account/oauth_delete', ['name' => $name]); ?>');" class="btn btn-xs red"> <i class="fa fa-user-times"></i> 解绑</a>
                                    <?php } else { ?>
                                    <a href="<?php echo \Phpcmf\Service::L('Router')->oauth_url($name, 'bang'); ?>" class="btn btn-xs green"> <i class="fa fa-user-plus"></i> 绑定</a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>

    </div>


<?php if ($fn_include = $this->_include("mfooter.html")) include($fn_include); ?>