<?php namespace Phpcmf\Controllers\Member;

class {name} extends \Phpcmf\Common
{

    public function index() {

        $name = 'hello word';

        // 将变量传入模板
        \Phpcmf\Service::V()->assign([
            'testname' => $name,
        ]);
        // 选择输出模板 用户中心位于 /template/pc/defaul/membert/应用目录/{tplname} 这个文件要自己手动创建
        \Phpcmf\Service::V()->display('{tplname}');
    }

}
