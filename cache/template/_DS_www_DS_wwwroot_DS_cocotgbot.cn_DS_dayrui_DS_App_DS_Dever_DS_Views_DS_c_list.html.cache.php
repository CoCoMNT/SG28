<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<div class="note note-danger">
    <p>当前应用程序的控制器目录是：<?php echo APPSPATH;  echo $dir; ?>/Controllers/</p>
</div>
<div class="right-card-box">
    <form class="form-horizontal" role="form" id="myform">
        <?php echo dr_form_hidden(); ?>
        <div class="table-scrollable">
            <table class="table table-striped table-bordered  table-checkable dataTable">
                <thead>
                <tr class="heading">
                    <th width="120" style="text-align:center"> <?php echo dr_lang('权限'); ?> </th>
                    <th width="330"> <?php echo dr_lang('应用目录'); ?></th>
                    <th> <?php echo dr_lang('操作'); ?> </th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($list) && is_array($list) && $list) { $key_t=-1;$count_t=dr_count($list);foreach ($list as $tid=>$t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0; ?>
                <tr class="odd gradeX">
                    <td style="text-align:center"><?php echo $t['type']; ?></td>
                    <td><?php echo $t['path']; ?></td>
                    <td>
                        <?php if ($dir=='Member' && $tid=='member') {  } else { ?>
                        <label><a href="javascript:dr_iframe('创建空白控制器', '<?php echo dr_url('dever/home/c_add', ['dir'=>$dir, 'tid' => $tid]); ?>', '60%','510px');" class="btn btn-xs blue"> <i class="fa fa-plus"></i> <?php echo dr_lang('创建空白控制器'); ?> </a></label>
                        <?php if (in_array($tid, ['admin', 'member'])) { ?>
                        <label><a href="javascript:dr_iframe('创建数据控制器', '<?php echo dr_url('dever/home/db_add', ['dir'=>$dir, 'tid' => $tid]); ?>', '60%','510px');" class="btn btn-xs red"> <i class="fa fa-plus"></i> <?php echo dr_lang('创建数据控制器'); ?> </a></label>
                        <?php }  if (in_array($tid, ['admin'])) { ?>
                        <label><a href="javascript:dr_iframe('创建Table控制器', '<?php echo dr_url('dever/home/table_add', ['dir'=>$dir, 'tid' => $tid]); ?>', '60%','510px');" class="btn btn-xs green"> <i class="fa fa-plus"></i> <?php echo dr_lang('创建Table控制器'); ?> </a></label>
                        <label><a href="javascript:dr_iframe('创建Form控制器', '<?php echo dr_url('dever/home/form_add', ['dir'=>$dir, 'tid' => $tid]); ?>', '60%','510px');" class="btn btn-xs yellow"> <i class="fa fa-plus"></i> <?php echo dr_lang('创建Form控制器'); ?> </a></label>
                        <?php }  } ?>
                    </td>
                </tr>
                <?php if (isset($t['file']) && is_array($t['file']) && $t['file']) { $key_file=-1;$count_file=dr_count($t['file']);foreach ($t['file'] as $file) { $key_file++; $is_first=$key_file==0 ? 1 : 0;$is_last=$count_file==$key_file+1 ? 1 : 0;             $fpath = APPSPATH.$dir.'/Controllers/'.ucfirst($tid).'/'.$file;
$uri = strtolower($dir.'/'.str_replace('.php', '', $file).'/index');
if ($tid == 'admin') {
    $url = dr_url($uri);
} elseif ($tid == 'member') {
    $url = dr_member_url($uri);
} else {
    $url = dr_url($uri, '', 'index.php');
}

?>
                <tr>
                    <td style="text-align:center"></td>
                    <td>
                        <div >
                            <a href="javascript:dr_iframe_show('控制器', '<?php echo dr_url('dever/home/show', ['dir'=>$dir, 'file'=>$file, 'tid' => $tid]); ?>', '80%');"><?php echo $file; ?></a>
                        </div>
                    </td>
                    <td>
                        <label><a href="<?php echo $url; ?>" target="_blank" class="btn btn-xs yellow">  <?php echo dr_lang('访问'); ?> </a></label>
                        <?php if (is_file(str_replace('.php', '.json', $fpath))) { ?>
                        <label><a href="javascript:dr_iframe('字段设置：<?php echo basename($fpath); ?>', '<?php echo dr_url('dever/home/db_field', ['dir'=>$dir, 'file'=>$file, 'tid' => $tid]); ?>', '80%','','nogo');" class="btn btn-xs green">  <?php echo dr_lang('字段设置'); ?> </a></label>
                        <label><a href="javascript:dr_iframe('列表设置：<?php echo basename($fpath); ?>', '<?php echo dr_url('dever/home/db_list', ['dir'=>$dir, 'file'=>$file, 'tid' => $tid]); ?>', '80%','','nogo');" class="btn btn-xs blue">  <?php echo dr_lang('列表设置'); ?> </a></label>
                        <?php }  if (in_array($tid, ['admin'])) { ?>
                        <label><a href="javascript:dr_iframe('菜单设置：<?php echo $uri; ?>', '<?php echo dr_url('dever/home/menu_add', ['uri'=>$uri]); ?>', '500px','','nogo');" class="btn btn-xs red">  <?php echo dr_lang('加入到后台菜单'); ?> </a></label>
                        <?php } ?>
                        <label><a href="javascript:dr_iframe('控制器：<?php echo basename($fpath); ?>', '<?php echo dr_url('dever/home/c_install', ['dir'=>$dir, 'file'=>$file, 'tid' => $tid]); ?>', '500px','300px','nogo');" class="btn btn-xs yellow">  <?php echo dr_lang('导出配置'); ?> </a></label>
                    </td>
                </tr>
                <?php } }  $i++;  } } ?>
                </tbody>
            </table>
        </div>


    </form>
</div>


<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>