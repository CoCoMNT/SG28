<?php namespace Phpcmf\Model\Pay;

// 支付类
// 支付状态 status 0未付款 1付款成功 2转账中 3转账被拒绝
class Pay extends \Phpcmf\Model
{

    protected $myfield;
    protected $payname;

    // 初始化
    public function __construct() {
        parent::__construct();
        $this->myfield = [
            'recharge' => [
                'fieldtype' => 'Pay',
                'fieldname' => 'pay',
                'setting' => [
                    'option' => [
                        'payfile' => 'pay.html', // 模板文件
                        'is_finecms' => 0, // 是否启用余额付款
                    ],
                ]
            ],
            'score' => [
                'fieldtype' => 'Pay',
                'fieldname' => 'pay',
                'setting' => [
                    'option' => [
                        'payfile' => 'score.html', // 模板文件
                        'is_finecms' => 1, // 是否启用余额付款
                    ],
                ]
            ],
            'donation' => [
                'fieldtype' => 'Pay',
                'fieldname' => 'pay',
                'setting' => [
                    'option' => [
                        'payfile' => 'donation.html', // 模板文件
                        'is_finecms' => 1, // 是否启用余额付款
                    ],
                ]
            ],
            'gathering' => [
                'fieldtype' => 'Pay',
                'fieldname' => 'price',
                'setting' => [
                    'option' => [
                        'payfile' => 'gathering.html', // 模板文件
                        'is_finecms' => 1, // 是否启用余额付款
                    ],
                ]
            ],
        ];
    }

    // 获取支付方式
    public function get_pay_type($is_finecms = 0) {

        $pay_type = [];

        $is_finecms && $pay_type['finecms'] = \Phpcmf\Service::R(ROOTPATH.'api/pay/finecms/config.php');

        if (\Phpcmf\Service::C()->member_cache['payapi']) {
            foreach (\Phpcmf\Service::C()->member_cache['payapi'] as $name => $t) {
                if (!is_file(ROOTPATH.'api/pay/'.$name.'/config.php')) {
                    continue; // 排除是否存在配置文件
                }
                $pay_type[$name] = \Phpcmf\Service::R(ROOTPATH.'api/pay/'.$name.'/config.php');
            }
        }

        if ($is_finecms) {
            if (!$this->uid) {
                unset($pay_type['finecms']);
            } elseif ($this->member['money'] < 0.01) {
                unset($pay_type['finecms']);
            }
        }

        if (IS_DEV && !$pay_type) {
            log_message('debug', '系统没有开启支付接口，无法获取到支付方式');
        }

        return $pay_type;
    }

    // 获取打赏模块内容
    public function get_module_row($dir, $id, $siteid) {

        $data = $this->table($siteid.'_'.$dir)->get($id);
        if (!$data) {
            return dr_return_data(0, dr_lang('打赏内容不存在'));
        }

        return dr_return_data(1, 'ok', $data);
    }

    // 获取价格值
    public function get_price_value($data, $field, $sku = '') {

		if ($sku && $sku != 'null' && isset($data[$field.'_sku']) && is_array($data[$field.'_sku'])) {
			return (float)$data[$field.'_sku']['value'][$sku]['price'];
		}
        return (float)$data[$field];
    }

    // 获取支付价格和内容
    public function get_pay_info($id, $field, $num = 1, $sku = '') {

        list($table, $name, $username, $thumb, $url) = $this->_get_table_name(
            $field['relatedid'],
            $field['relatedname']
        );
        if (!$table) {
            return dr_return_data(0, dr_lang('支付关联表不存在'));
        }

        $rt = $this->table($table)->get($id);
        if (!$rt) {
            return dr_return_data(0, dr_lang('支付关联表[%s]内容(#%s)不存在', $table, $id));
        } elseif (!isset($rt[$field['fieldname']])) {
            return dr_return_data(0, dr_lang('支付关联表的支付字段[%s]不存在于主表', $field['fieldname']));
        } elseif (isset($rt[$thumb]) && $rt[$thumb] && !is_numeric($rt[$thumb])) {
            // 如果是多文件字段取第一个作为缩略图
            $image = dr_string2array($rt[$thumb]);
            isset($image['file'][0]) && $image['file'][0] && $rt[$thumb] = $image['file'][0];
        }

        if (isset($rt[$field['fieldname'].'_sku']) && $rt[$field['fieldname'].'_sku']) {
            $rt[$field['fieldname'].'_sku'] = dr_string2array($rt[$field['fieldname'].'_sku']);
        }

        $obj = \Phpcmf\Service::L('field')->get($field['fieldtype']);
        if (isset($rt[$field['fieldname'].'_sku']) && $rt[$field['fieldname'].'_sku']
            && $obj && method_exists($obj, 'get_pay_info')) {
            $rs = $obj->get_pay_info($rt, $field, $sku);
            if (!$rs['code']) {
                return $rs;
            }
            list($sn, $quantity, $sku_name, $sku_string) = $rs['data'];
        } elseif ($field['fieldtype'] == 'Pays' && isset($rt[$field['fieldname'].'_sku']) && $rt[$field['fieldname'].'_sku']) {
            // 兼容老版本写法
            $rt[$field['fieldname'].'_sku'] = dr_string2array($rt[$field['fieldname'].'_sku']);
            if (!$sku && $rt[$field['fieldname'].'_sku']) {
                return dr_return_data(0, dr_lang('没有选择商品属性'));
            } elseif (!isset($rt[$field['fieldname'].'_sku']['value'][$sku]) || !$rt[$field['fieldname'].'_sku']['value'][$sku]) {
                #print_r($rt[$field['fieldname'].'_sku']['value']);
                return dr_return_data(0, dr_lang('商品(#'.$rt['id'].')属性（#'.$sku.'）无效'));
            }
            $sn = (string)$rt[$field['fieldname'].'_sku']['value'][$sku]['sn'];
            $quantity = (int)$rt[$field['fieldname'].'_sku']['value'][$sku]['quantity'];
            list($sku_name, $sku_string) = dr_sku_name($sku, $rt[$field['fieldname'].'_sku'], 1);
        } else {
            $sn = (string)$rt[$field['fieldname'].'_sn'];
            $quantity = (int)$rt[$field['fieldname'].'_quantity'];
            $sku_name = $sku_string = '';
        }

        // 获取价格
        $price = $this->get_price_value($rt, $field['fieldname'], $sku);

        // buy-表名-主键id-字段id-数量-sku
        $mid = 'buy-'.$table .'-'. $id .'-'. $field['id'] .'-'. max(1, (int)$num) .'-'. ($sku ? $sku : 'null');

        return [
            'sn' => $sn,
            'mid' => $mid,
            'num' => $num,
            'price' => $price,
            'price_sku' => isset($rt[$field['fieldname'].'_sku']) ? $rt[$field['fieldname'].'_sku'] : '',
            'total' => $price * $num,
            'table' => $table,
            'quantity' => $quantity,
            'sku_name' => $sku_name,
            'sku_string' => $sku_string,
            'sku_value' => $sku,
            'touid' => intval($rt['uid']),
            'tousername' => (string)$rt['username'],
            'title' => (string)$rt[$name],
            'thumb' => (string)$rt[$thumb],
            'url' => $url ? $url.$id : '',
            'data' => $rt,
        ];
    }

    // 获取支付信息的表名
    public function _get_table_name($relatedid, $relatedname) {

        $data = \Phpcmf\Service::C()->get_cache('table-pay-'.SITE_ID, $relatedname.'-'.$relatedid);
        if (!$data) {
            return [];
        }

        // 表名 - 标题字段 - 卖家账号 - 缩略图 - 地址
        return [$data['table'], $data['name'], $data['username'], $data['thumb'], $data['url']];
    }

    // 支付状态
    public function paystatus($data) {

        switch ($data['status']) {

            case 0:
                return '<a href="javascript:dr_ajaxp_url(\''.PAY_URL.'index.php?s=pay&m=ajax&id='.$data['id'].'\');" class="label label-danger"> '.dr_lang('未付款').' </a>';
                break;

            case 1:
                return '<span class="label label-success"> '.dr_lang('已付款').' </span>';
                break;

            case 2:
                return '<span class="label label-warning"> '.dr_lang('汇款中').' </span>';
                break;

            case 5:
                return '<span class="label label-warning"> '.dr_lang('上门付').' </span>';
                break;

            case 3:
                return '<span class="label label-danger"> '.dr_lang('被拒绝').' </span>';
                break;

        }

    }

    // 用途类型
    public function paytype($mark) {

        switch ($mark) {

            // 在线充值
            case 'recharge':
                return '<span class="label label-default"> '.dr_lang('充值').' </span>';
                break;

            // 在线充值
            case 'score':
                return '<span class="label label-success"> '.dr_lang(SITE_SCORE).' </span>';
                break;

            // 系统
            case 'system':
                return '<span class="label label-danger"> '.dr_lang('系统').' </span>';
                break;

            // 订单
            case 'order':
            case 'orders':
                return '<span class="label label-success"> '.dr_lang('订单').' </span>';
                break;

            // 提现
            case 'cash':
                return '<span class="label label-success"> '.dr_lang('提现').' </span>';
                break;

            // 其他来自自定义字段
            default:
                list($rname, $rid, $fid) = explode('-', $mark);
                switch ($rname) {

                    // 系统
                    case 'system':
                        return '<span class="label label-danger"> '.dr_lang('系统').' </span>';
                        break;

                    // 用户组
                    case 'group':
                        return '<span class="label label-default"> '.dr_lang('会员').' </span>';
                        break;
                    // 用户组
                    case 'level':
                        return '<span class="label label-default"> '.dr_lang('等级').' </span>';
                        break;

                    // 订单
                    case 'order':
                    case 'orders':
                        return '<span class="label label-success"> '.dr_lang('订单').' </span>';
                        break;

                    case 'gathering':
                        // 收款插件
                        return '<span class="label label-danger"> '.dr_lang('收款').' </span>';
                        break;

                    case 'buy':
                        // 快速下单
                        return '<span class="label label-danger"> '.dr_lang('下单').' </span>';
                        break;

                    case 'my':
                        // 二次开发
                        $obj = $this->my_pay_obj($rid);
                        if (!$obj) {
                            return '<span class="label label-danger"> '.dr_lang('未知').' </span>';
                        } elseif (method_exists($obj, 'paytype')) {
                            return $obj->paytype();
                        } else {
                            return '<span class="label label-warning"> '.dr_lang('应用').' </span>';
                        }
                        break;

                    case 'donation':
                        // 打赏作者
                        return '<span class="label label-danger"> '.dr_lang('打赏').' </span>';
                        break;

                    default:
                        // 来自自定义字段
                        /*
                        $field = \Phpcmf\Service::C()->get_cache('table-field', $fid);
                        if ($field['relatedname'] == 'module') {
                            // 模块
                            return '<span class="label label-success"> '.dr_lang('模块').' </span>';
                        } elseif (function_exists('dr_paytype_'.$mark)) {
                            return call_user_func('dr_paytype_'.$mark);
                        }*/
                        return '<span class="label label-warning"> '.dr_lang('其他').' </span>';
                        break;
                }
                break;
        }
    }

    // 付款名称
    public function payname($name) {

        switch ($name) {

            case 'meet';
                return '上门';
                break;

            case 'remit';
                return '汇款';
                break;

            case 'system';
                return '系统';
                break;

            case 'admin';
                return '后台';
                break;

            case 'finecms';
                return '余额';
                break;

            case 'weixin';
                return '微信';
                break;

            case 'alipay';
                return '支付宝';
                break;

            default;

                if (!$this->payname[$name]) {
                    if (is_file(WEBPATH.'api/pay/'.$name.'/config.php')) {
                        $this->payname[$name] = require WEBPATH.'api/pay/'.$name.'/config.php';
                    } else {
                        return $name;
                    }

                }

                if (isset($this->payname[$name]['name']) && $this->payname[$name]['name']) {
                    return $this->payname[$name]['name'];
                }

                return $name;
                break;
        }
    }

    /**
     * 支付表单调用
     * mark     表名-主键id-字段id
     * value    支付金额
     * title    支付说明
     * */
    public function payform($mark, $value = 0, $title = '', $url = '', $remove_div = 0) {

        switch ($mark) {

            // 在线充值
            case 'recharge':
                $field = $this->myfield[$mark];
                break;

            // 金币充值
            case 'score':
                $field = $this->myfield[$mark];
                break;

            // 其他来自自定义字段
            default:
                list($rname, $rid, $fid, $num, $sku) = explode('-', $mark);
                switch ($rname) {

                    case 'gathering':
                        // 来自付款
                        $field = $this->myfield[$rname];
                        $value = ceil($value);
                        break;

                    case 'my':
                        // 来自二次开发
                        $obj = $this->my_pay_obj($rid);
                        if (!$obj) {
                            return dr_lang('自定义支付对象类文件不存在');
                        }
                        if (method_exists($obj, 'get_myfield')) {
                            $field = $obj->get_myfield();
                        } else {
                            return dr_lang('自定义类方法get_myfield未定义');
                        }
                        if (method_exists($obj, 'pay_before')) {
                            $rt = $obj->pay_before($fid, $num, $sku, SITE_ID);
                            if ($rt) {
                                return $rt;
                            }
                        }
                        if (method_exists($obj, 'get_price')) {
                            $value = $obj->get_price($fid, $num, $sku, SITE_ID);
                        } else {
                            return dr_lang('自定义类方法get_price未定义');
                        }
                        break;

                    case 'buy':
                        // 快速下单
                        $field = \Phpcmf\Service::C()->get_cache('table-field', $num);
                        if (!$field) {
                            return dr_lang('支付字段[%s]不存在', $num);
                        }
                        break;

                    default:
                        return dr_return_data(0, dr_lang('未定义的支付方式[%s]', $rname));
                        break;
                }
                break;
        }

        $f = \Phpcmf\Service::L('Field')->get($field['fieldtype']);
        $f->remove_div = $remove_div;
        $html = $f->input($field, $value, [
            'mark' => $mark,
            'title' => $title,
            'url' => $url,
        ]);
        return $html;
    }

    // 增加流水记录
    public function add_paylog($data) {
        $data = [
            'site' => SITE_ID,
            'mid' => $data['mid'],
            'uid' => $data['uid'],
            'touid' => (int)$data['touid'],
            'title' => $data['title'],
            'value' => $data['value'],
            'type' => dr_safe_replace($data['type']),
            'url' => !$data['url'] ? '' : dr_url_prefix($data['url']),
            'status' => (int)$data['status'],
            'result' => $data['result'] ? $data['result'] : '',
            'paytime' => $data['paytime'] ? $data['paytime'] : 0,
            'money' => 0,
            'inputtime' => SYS_TIME,
        ];
        if ($data['uid']) {
            $user = $this->table('member')->get($data['uid']);
            $user && $data['money'] = $user['money'];
        }
        $rt = $this->table('member_paylog')->insert($data);
        $data['id'] = $rt['code'];
        $rt['data'] = $data;
        return $rt;
    }

    // 给账户充值money
    public function add_money($uid, $value) {
        return \Phpcmf\Service::M('member')->add_money($uid, $value);
    }

    // 通过支付流水号获取id号
    public function get_pay_id($mark) {
        if (strpos($mark, 'fc-') === 0) {
            return intval(substr($mark, 3));
        }
        return (int)substr($mark, strlen(\Phpcmf\Service::C()->member_cache['pay']['prefix'].date('YmdHis', SYS_TIME))+2);
    }

    // 生成支付流水id号
    public function get_pay_sn($data) {
        return trim((string)\Phpcmf\Service::C()->member_cache['pay']['prefix']).date('YmdHis', SYS_TIME).'00'.$data['id'];
    }

    // 充值成功的返回
    public function paysuccess($mark, $payid) {

        // 找流水id
        $id = $this->get_pay_id($mark);
        if (!$id) {
            return dr_return_data(0, dr_lang('没有获取到支付ID号'));
        }

        $data = $this->table('member_paylog')->get($id);
        if (!$data) {
            return dr_return_data(0, dr_lang('支付记录不存在'));
        } elseif ($data['status'] == 1) {
            return dr_return_data($id, dr_lang('此账单已经支付'));
        } else {
            // 分析订单来源
            $user = \Phpcmf\Service::M()->table('member')->get($data['uid']);
            list($a, $b, $c, $d, $e, $f) = explode('-', $data['mid']);
            // 付款到账号余额中 // touid为空表示系统收款直接扣
            if ($data['touid']) {
                if ($data['touid'] == $data['uid']) {
                    // 表示为自己充值
                    if ($data['type'] != 'finecms') {
                        // 充值必须判断type不是余额付款模式
                        $this->add_money($data['uid'], $data['value']);
                        $user['money']+= $data['value'];
                    }
                } else {
                    // 扣除自己的钱
                    if ($data['type'] == 'finecms') {
                        // 判断type是余额付款模式,才进行扣款
                        $this->add_money($data['uid'], -abs($data['value']));
                        $user['money']-= abs($data['value']);
                    } else {
                        // 记录消费
                        if ($user) {
                            $this->table('member')->update($data['uid'], [
                                'spend' => max(0, (float)$user['spend'] + abs($data['value'])),
                            ]);
                        }
                    }
                }
            } else {
                // 扣除自己的钱 touid为空表示系统收款直接扣
                if ($data['type'] == 'finecms') {
                    // 判断type是余额付款模式,才进行扣款
                    $this->add_money($data['uid'], -abs($data['value']));
                    $user['money']-= abs($data['value']);
                } else {
                    // 记录消费
                    if ($user) {
                        $this->table('member')->update($data['uid'], [
                            'spend' => max(0, (float)$user['spend'] + abs($data['value'])),
                        ]);
                    }
                }
            }

            // 备份使用
            $data['result_data'] = $result = $data['result'];

            // 支付成功
            $save = [
                'status' => 1,
                'result' => $payid,
                'paytime' => SYS_TIME,
            ];
            if ($payid == 0 && $payid == (int)$data['value']) {
                // 表示0元支付设置为余额方式
                $save['type'] = 'finecms';
            }

            if ($user) {
                 $save['money'] = $user['money'];
            }

            $this->table('member_paylog')->update($id, $save);

            $data['status'] = 1;
            $data['result'] = $payid;
            $data['paytime'] = SYS_TIME;
            $data['url'] = \Phpcmf\Service::L('Router')->member_url('pay/paylog/show', ['id' => $data['id']]);

            if ($user) {
                $data['phone'] = $user['phone'];
                $data['email'] = $user['email'];
                // 通知
                \Phpcmf\Service::L('Notice')->send_notice('pay_success', $data);
            }

            // 挂钩
            \Phpcmf\Hooks::trigger('pay_success', $data);

            switch ($a) {

                case 'recharge':
                    // 在线充值
                    break;

                case 'group':
                    // 用户组
                    \Phpcmf\Service::M('member')->notice(
                        $data['uid'],
                        2,
                        $data['title'],
                        \Phpcmf\Service::L('Router')->member_url('pay/paylog/show', ['id'=>$data['id']])
                    );
                    $result = dr_string2array($result);
                    \Phpcmf\Service::M('member')->apply_group($d, $user, $b, $c, abs($data['value']), $result);
                    break;

                case 'level':
                    // 用户组等级升级
                    // 提醒通知
                    \Phpcmf\Service::M('member')->notice(
                        $data['uid'],
                        2,
                        $data['title'],
                        \Phpcmf\Service::L('Router')->member_url('pay/paylog/show', ['id'=>$data['id']])
                    );
                    \Phpcmf\Service::M('member')->update_level($data['uid'], $b, $c);
                    break;

                case 'score':
                    // 积分充值
                    $value = abs($data['value']) * \Phpcmf\Service::C()->member_cache['pay']['convert'];
                    \Phpcmf\Service::M('member')->add_score($data['uid'], $value, dr_lang('在线充值'), $data['url']);
                    break;

                case 'buy':
                    // 快速下单
                    \Phpcmf\Hooks::trigger('member_buy_after', $data);
                    break;

                case 'order':
                    // 订单交易
                    \Phpcmf\Service::M('order', 'order')->pay($c, $id);
                    break;

                case 'orders':
                    // 订单交易
                    $oids = explode(',', $c);
                    foreach ($oids as $oi) {
                        \Phpcmf\Service::M('order', 'order')->pay($oi, $id);
                    }
                    break;

                case 'gathering':
                    // 来自收款
                    $row = $this->table($c)->get($b);
                    if (!$row) {
                        log_message('error', '收款(#'.$id.')回调失败：主题#'.$c.'不存在');
                    } else {
                        // 更新表
                        $row['nums'] = (int)$row['nums'] + 1;
                        $row['total'] = $row['total'] + abs($data['value']);
                        $this->table($c)->update($b, [
                            'total' => $row['total'],
                            'nums' => $row['nums'],
                            'updatetime' => SYS_TIME
                        ]);
                        // 收款开发钩子
                        \Phpcmf\Hooks::trigger('gathering_'.$c.'_success', $row, $data);
                    }
                    break;
                case 'my':
                    // 来自二次开发
                    $obj = $this->my_pay_obj($b);
                    if (method_exists($obj, 'success')) {
                        $obj->success($c, $data, $d, $e);
                    }
                    break;
            }

            return dr_return_data($id, dr_lang('支付成功'));
        }
    }

    // 支付成功的回调url
    public function paycall_url($data) {

        // 默认地址
        $url = dr_url_prefix('index.php?s=member&app=pay&c=paylog&m=show&id='.$data['id'], '', $data['site']);

        list($rname, $rid, $fid, $num, $sku) = explode('-', $data['mid']);

        switch ($rname) {

            case 'my':
                // 来自二次开发
                $obj = $this->my_pay_obj($rid);
                if (method_exists($obj, 'call_url')) {
                    $row = $obj->call_url($fid, $data);
                    if ($row) {
                        $url = $row;
                    }
                }

                break;

            case 'order':
                // 来自单用户商城订单系统
                $url = dr_url_prefix('index.php?s=member&app=order&c=home&m=show&id='.$fid, '', $data['site']);
                break;

            case 'orders':
                // 来自组合订单商城订单系统
                $url = dr_url_prefix('index.php?s=member&app=order&c=home&m=index', '', $data['site']);
                break;

            case 'gathering':
                // 来自收款
                $row = $this->table($fid)->get($rid);
                if ($row && $row['url']) {
                    $url = $row['url'];
                }

                break;
        }

        return $url;
    }

    /**
     * 支付提交
     * */
    public function post($post) {

        if (strlen($post['money']) > 8) {
            return dr_return_data(0, dr_lang('付款金额[%s]不规范', $post['money']));
        }

        $post['uid'] = intval($post['uid']);
        $post['username'] = (string)$post['username'];
        $post['money'] = floatval(number_format($post['money'], 2, '.', ''));
        $post['mark'] = dr_safe_filename($post['mark']);
        $post['type'] = dr_safe_filename($post['type']);
        if ($post['money'] <= 0) {
            return dr_return_data(0, dr_lang('付款金额[%s]不规范', $post['money']));
        } elseif ((string)$post['money'] == 'INF') {
            return dr_return_data(0, dr_lang('付款金额[%s]不规范', $post['money']));
        } elseif (!$post['type']) {
            return dr_return_data(0, dr_lang('未知支付接口'));
        }

        // 初始化数据
        $touid = $money = $fid = 0;
        $tousername = $title = '';

        // 挂钩点 提交之前
        \Phpcmf\Hooks::trigger('pay_post_before', $post);

        switch ($post['mark']) {

            // 在线充值
            case 'recharge':
                if (!$post['uid']) {
                    return dr_return_data(0, dr_lang('付款账号不存在'));
                }
                $title = dr_lang('用户（%s）充值', $post['username']);
                $money = $post['money'];
                if (\Phpcmf\Service::C()->member_cache['pay']['min'] && $money < \Phpcmf\Service::C()->member_cache['pay']['min']) {
                    return dr_return_data(0, dr_lang('系统最小充值金额为%s元', \Phpcmf\Service::C()->member_cache['pay']['min']));
                }
                $touid = $post['uid']; // 收款方为自己
                $tousername = $post['username']; // 收款方为自己
                if ($post['type'] == 'finecms') {
                    return dr_return_data(0, dr_lang('充值不能使用余额支付'));
                } elseif (floatval($post['money']) <= 0) {
                    return dr_return_data(0, dr_lang('金额不规范'));
                }
                break;

            // 金币充值
            case 'score':
                if (!$post['uid']) {
                    return dr_return_data(0, dr_lang('付款账号不存在'));
                }
                $money = (int)$post['money'];
                if (\Phpcmf\Service::C()->member_cache['pay']['score_min'] && $money < \Phpcmf\Service::C()->member_cache['pay']['score_min']) {
                    return dr_return_data(0, dr_lang('系统最小充值%s为%s', SITE_SCORE, \Phpcmf\Service::C()->member_cache['pay']['score_min']));
                } elseif ($money == 0) {
                    return dr_return_data(0, dr_lang('金额不规范'));
                }
                $title = dr_lang('用户（%s）充值%s：%s', $post['username'], SITE_SCORE, $money);
                $touid = 0; // 属于消费，收款方为系统
                $tousername = '';
                $money = - $money / \Phpcmf\Service::C()->member_cache['pay']['convert'];
                break;

            // 其他来自自定义字段
            default:
                list($rname, $rid, $fid, $num, $sku, $ff) = explode('-', $post['mark']);
                switch ($rname) {

                    case 'group':
                        // 来自用户组
                        $money = (float)$post['money'];
                        if ($money <= 0) {
                            return dr_return_data(0, dr_lang('金额[%s]不规范', $money));
                        }
                        $title = $post['title'];
                        $money = -$money;
                        $touid = 0; // 收款方为统系
                        $tousername = ''; // 收款方为统系
                        break;

                    case 'level':
                        // 来自用户组等级
                        $money = (float)$post['money'];
                        if ($money <= 0) {
                            return dr_return_data(0, dr_lang('金额[%s]不规范', $money));
                        }
                        $title = $post['title'];
                        $money = -$money;
                        $touid = 0; // 收款方为统系
                        $tousername = ''; // 收款方为统系
                        break;

                    case 'gathering':
                        // 来自收款
                        $row = $this->table($fid)->get($rid);
                        if (!$row) {
                            return dr_return_data(0, dr_lang('收款主题不存在'));
                        }
                        $money = floatval($row['price'] > 0 ? $row['price'] : $post['money']);
                        if ($money <= 0) {
                            return dr_return_data(0, dr_lang('金额[%s]不规范', $money));
                        }
                        $title = $row['title'];
                        $money = -$money;
                        $touid = 0; // 收款方为统系
                        $tousername = ''; // 收款方为统系
                        break;

                    case 'my':
                        // 来自二次开发
                        $obj = $this->my_pay_obj($rid);
						if (method_exists($obj, 'pay_after')) {
                            $rt = $obj->pay_after($fid, $num, $sku, SITE_ID);
                            if ($rt) {
                                return dr_return_data(0, $rt);
                            }
                        }
                        if (method_exists($obj, 'get_row')) {
                            $row = $obj->get_row($fid, $num, $sku, SITE_ID);
                            if (!$row) {
                                return dr_return_data(0, dr_lang('主题不存在'));
                            }
                        } else {
                            return dr_return_data(0, dr_lang('类方法[get_row]未定义'));
                        }
                        if (method_exists($obj, 'save_result')) {
                            $rt = $obj->save_result($fid, $num, $sku, SITE_ID);
                            if ($rt) {
                                $post['result'] = $rt;
                            }
                        }
                        $money = floatval($row['price']);
                        if ($money <= 0) {
                            return dr_return_data(0, dr_lang('金额[%s]不规范', $money));
                        }
                        $title = $row['title'];
                        $money = -$money;
                        $touid = (int)$row['sell_uid']; // 收款方
                        $tousername = (string)$row['sell_username']; // 收款方
                        break;

                    case 'order':
                        // 来自单用户商城订单系统
                        $title = dr_lang('订单编号：%s', $post['sn']);
                        $money = -(float)$post['money'];
                        $touid = 0; // 收款方为统系
                        $tousername = ''; // 收款方为统系
                        break;

                    case 'orders':
                        // 来自多用户商城订单系统
                        $title = dr_lang('订单编号：%s', $post['sn']);
                        $money = -(float)$post['money'];
                        $touid = 0; // 收款方为统系
                        $tousername = ''; // 收款方为统系
                        break;

                    case 'buy':
                        // 快速下单 buy-1_book-992-185-1-null" $rname-$rid-$fid- $num-$sku- $ff
                        $field = \Phpcmf\Service::C()->get_cache('table-field', $num);
                        if (!$field) {
                            return dr_return_data(0, dr_lang('支付字段[%s]不存在', $num));
                        }
                        // 获取付款价格
                        $rt = $this->get_pay_info($fid, $field, $sku, $ff);
                        if ($rt['total'] <= 0) {
                            return dr_return_data(0, dr_lang('金额[%s]不规范', $rt['total']));
                        } elseif ($rt['mid'] != $post['mark']) {
                            return dr_return_data(0, dr_lang('支付信息验证失败'));
                        }
                        $touid = $rt['touid'];
                        $tousername = $rt['tousername'];
                        $title = $rt['title'];
                        $money = -(float)$rt['total'];
                        if ($this->uid == $touid) {
                            return dr_return_data(0, dr_lang('不能对自己付款'));
                        }
                        break;

                    default:
                        return dr_return_data(0, dr_lang('未定义的支付方式[%s]', $rname));
                        break;
                }

                break;
        }

        // 判断接口
        $apifile = ROOTPATH.'api/pay/'.$post['type'].'/pay.php';
        if (!is_file($apifile)) {
            return dr_return_data(0, IS_DEV ? dr_lang('支付接口文件（%s）不存在', $post['type']) : dr_lang('支付接口文件不存在'));
        }

        // 增加流水记录
        return $this->add_paylog([
            'uid' => $post['uid'],
            'username' => $post['username'],
            'touid' => $touid,
            'tousername' => $tousername,
            'mid' => $post['mark'],
            'title' => $title,
            'value' => $money,
            'type' => $post['type'],
            'status' => 0,
            'url' => $post['url'],
            'result' => (string)$post['result'],
            'paytime' => 0,
            'inputtime' => SYS_TIME,
        ]);
    }

    // 调用支付接口
    public function dopay($apifile, $data) {

        $id = $data['id']; // 支付记录的id

        // 生成唯一支付id 接口使用
        $sn = $pid = $this->get_pay_sn($data);

        // 接口配置参数
        $config = \Phpcmf\Service::C()->member_cache['payapi'][$data['type']];

        $return = [];
        $data['value'] = abs($data['value']);
        require $apifile;

        return $return;
    }

    // 缓存可用的支付字段
    public function cache() {

        $cache = [];
        \Phpcmf\Service::L('cache')->set_file('pay', $cache);
        return;
    }


    // 获取二次开发对象
    public function my_pay_obj($name) {
        list($app, $class) = explode('_', $name);
        $classFile = dr_get_app_dir($app).'Models/'.ucfirst($class).'.php';
        if (!is_file($classFile)) {
            CI_DEBUG && log_message('debug', '支付对象('.$name.')类文件（'.$classFile.'）不存在');
            return;
        }
        return \Phpcmf\Service::M($class, $app);
    }

    // 清除3天未付款的流水
    public function clear_paylog() {
        $this->db->table('member_paylog')->where('status', 0)->where('inputtime <'.(SYS_TIME - 3600*24*3))->delete();
    }

}