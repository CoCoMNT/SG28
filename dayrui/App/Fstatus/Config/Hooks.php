<?php

\Phpcmf\Hooks::app_on('fstatus', 'module_content_before', function($data) {
    if (IS_ADMIN) {
        $config = \Phpcmf\Service::M('app')->get_config('fstatus');
        if (isset($config[MOD_DIR])) {
            $table = \Phpcmf\Service::M()->prefix.SITE_ID.'_'.MOD_DIR;
            if (\Phpcmf\Service::M()->db->fieldExists('fstatus', $table)) {
                if (!isset($data[1]['fstatus'])) {
                    $data[1]['fstatus'] = 0;
                    return dr_return_data(1, '', $data);
                }

            }
        }
    }
});