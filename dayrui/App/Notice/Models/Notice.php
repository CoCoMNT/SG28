<?php namespace Phpcmf\Model\Notice;

class Notice extends \Phpcmf\Model
{
    /**
     * 添加一条通知
     *
     * @param   string  $uid
     * @param   string  $note
     * @return  null
     */
    public function add_notice($uid, $type, $note, $url = '', $mark = '') {

        if (!$uid || !$note) {
            return '';
        }

        $uids = is_array($uid) ? $uid : explode(',', $uid);
        foreach ($uids as $uid) {
            $this->db->table('member_notice')->insert([
                'uid' => $uid,
                'type' => max(1, (int)$type),
                'isnew' => 1,
                'content' => $note,
                'url' => (string)$url,
                'mark' => (string)$mark,
                'inputtime' => SYS_TIME,
            ]);
        }

        return '';
    }

}