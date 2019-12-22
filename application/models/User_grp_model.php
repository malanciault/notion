<?php

use Malanciault\Threelci\Core\Threel_model;

class User_grp_model extends Threel_model
{

    public function get_groups($user_id)
    {
        $query = $this->db->query('
            SELECT *
            FROM user_grp
            LEFT JOIN grp ON user_grp_grp_id=grp_id
            WHERE user_grp_user_id=' . $user_id . ' ORDER BY grp_name ASC'
        );
        return $query->result_array();
    }

    public function get_user_grps($user_id)
    {
        $where = array(
            'user_grp_user_id' => $user_id,
        );
        $result = $this->get_all($where, 'user_grp_user_id', 'user_grp_grp_id');
        $ret = false;
        if ($result) {
            foreach ($result as $k => $v) {
                $ret[$k] = $k;
            }
        }

        return $ret;
    }

}