<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<div class="note note-danger">
    <p>自定义应用程序目录是：<?php echo APPSPATH; ?></p>
</div>
<div class="right-card-box">
<form class="form-horizontal" role="form" id="myform">
    <?php echo dr_form_hidden(); ?>
    <div class="table-scrollable">
        <table class="table table-striped table-bordered table-hover table-checkable dataTable">
            <thead>
            <tr class="heading">
                <th width="90" style="text-align:center"> <?php echo dr_lang('类别'); ?> </th>
                <th width="330"> <?php echo dr_lang('应用目录'); ?></th>
                <th> <?php echo dr_lang('操作'); ?> </th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1;  if (isset($list) && is_array($list) && $list) { $key_t=-1;$count_t=dr_count($list);foreach ($list as $dir=>$t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0; ?>
            <tr class="odd gradeX">
                <td style="text-align:center"><?php echo $t['type']; ?></td>
                <td><?php echo $t['cname']; ?></td>
                <td>
                    <label><a href="<?php echo dr_url('dever/home/c_index', ['dir'=>$dir]); ?>" class="btn btn-xs blue"> <i class="fa fa-code"></i> <?php echo dr_lang('控制器管理'); ?> </a></label>
                    <label><a href="javascript:dr_iframe_show('插件属性', '<?php echo dr_url('dever/home/cg_index', ['dir'=>$dir]); ?>');" class="btn btn-xs green"> <i class="fa fa-cog"></i> <?php echo dr_lang('属性配置'); ?> </a></label>
                    <?php if ($t['is_app'] && !dr_is_app($dir)) { ?>
                    <label><a href="<?php echo dr_url('cloud/local'); ?>" class="btn btn-xs red"> <i class="fa fa-cog"></i> <?php echo dr_lang('未安装'); ?> </a></label>
                    <?php } ?>
                </td>
            </tr>
            <?php $i++;  } } ?>
            </tbody>
        </table>
    </div>


</form>
</div>


<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>