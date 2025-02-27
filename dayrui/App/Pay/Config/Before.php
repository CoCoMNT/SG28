<?php

if (!dr_is_app('member')) {
	return dr_return_data(0, '需要先安装官方版的<用户系统>插件');
}

return dr_return_data(1, 'ok');