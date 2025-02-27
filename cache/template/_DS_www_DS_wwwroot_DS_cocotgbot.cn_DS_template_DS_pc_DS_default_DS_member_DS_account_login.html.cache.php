<?php if ($fn_include = $this->_include("mheader.html")) include($fn_include); ?>


    <div class="row">

        <div class="col-md-12">

            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject"> 登录记录</span>
                    </div>
                </div>
                <div class="portlet-body">


                    <div class="table-scrollable table-scrollable-borderless">
                        <table class="table table-hover table-light">
                            <thead>
                            <tr class="uppercase">
                                <th width="160"> 登录时间 </th>
                                <th width="200"> 登录地点 </th>
                                <th> 客户端详情 </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $list_return = $this->list_tag("action=table table=member_login uid=$member[uid] order=logintime desc"); if ($list_return && is_array($list_return)) extract($list_return, EXTR_OVERWRITE); $count=dr_count($return); if (is_array($return) && $return) { $key=-1; foreach ($return as $t) { $key++; $is_first=$key==0 ? 1 : 0;$is_last=$count==$key+1 ? 1 : 0; ?>
                            <tr>
                                <td> <?php echo dr_date($t['logintime']); ?> </td>
                                <td> <a href="http://www.ip138.com/ips138.asp?ip=<?php echo $t['loginip']; ?>&action=2" target="_blank"><?php echo \Phpcmf\Service::L('Ip')->address($t['loginip']); ?></a> </td>
                                <td> <a class="tooltips" data-container="body" data-placement="top" data-original-title="<?php echo $t['useragent']; ?>"><?php echo dr_strcut($t['useragent'], 80); ?></a> </td>
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