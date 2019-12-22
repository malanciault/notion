<?php

use Malanciault\Threelci\Core\Threel_model;

class User_model extends Threel_model
{

    //--------------------------------------------------------------------
    public function get_user_detail()
    {
        $id = $this->session->userdata('user_id');
        $query = $this->db->get_where('user', array('user_id' => $id));
        return $result = $query->row_array();
    }

    //--------------------------------------------------------------------
    public function update_user($data)
    {
        $id = $this->session->userdata('user_id');
        $this->db->where('user_id', $id);
        $this->db->update('user', $data);
        return true;
    }

    //--------------------------------------------------------------------
    public function change_pwd($data, $id)
    {
        $this->db->where('user_id', $id);
        $this->db->update('user', $data);
        return true;
    }

    public function get_by_email($email)
    {
        $query = $this->db->get_where('user', array('email' => $email));
        return $result = $query->row_array();
    }

    public function get_by_import_id($import_id)
    {
        $query = $this->db->get_where('user', array('import_id' => $import_id));
        return $result = $query->result_array();
    }

    public function add_user($data)
    {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    //---------------------------------------------------
    // get all users for server-side datatable processing (ajax based)
    public function get_all_users()
    {
        $wh = array();
        $SQL = 'SELECT * FROM user';
        if (count($wh) > 0) {
            $WHERE = implode(' and ', $wh);
            return $this->datatable->LoadJson($SQL, $WHERE);
        } else {
            return $this->datatable->LoadJson($SQL);
        }
    }

    public function get_all_users_for_org($org_id)
    {
        $wh[] = ' org_id = ' . $org_id;
        $SQL = 'SELECT * FROM user';
        if (count($wh) > 0) {
            $WHERE = implode(' and ', $wh);
            return $this->datatable->LoadJson($SQL, $WHERE);
        } else {
            return $this->datatable->LoadJson($SQL);
        }
    }

    //---------------------------------------------------
    // get all user records
    public function get_all_simple_users()
    {
        $this->db->where('is_admin', 0);
        $query = $this->db->get('user');
        return $result = $query->result_array();
    }

    //---------------------------------------------------
    // Count total user for pagination
    public function count_all_users()
    {
        return $this->db->count_all('user');
    }

    //---------------------------------------------------
    // Get all users for pagination
    public function get_all_users_for_pagination($limit, $offset)
    {
        $wh = array();
        $this->db->order_by('created_at', 'desc');
        $this->db->limit($limit, $offset);

        if (count($wh) > 0) {
            $WHERE = implode(' and ', $wh);
            $query = $this->db->get_where('user', $WHERE);
        } else {
            $query = $this->db->get('user');
        }
        return $query->result_array();
        //echo $this->db->last_query();
    }


    //---------------------------------------------------
    // get all users for server-side datatable with advanced search
    public function get_all_users_by_advance_search()
    {
        $wh = array();
        $SQL = 'SELECT * FROM user';
        if ($this->session->userdata('user_search_type') != '')
            $wh[] = "is_active = '" . $this->session->userdata('user_search_type') . "'";
        if ($this->session->userdata('user_search_from') != '')
            $wh[] = " `created_at` >= '" . date('Y-m-d', strtotime($this->session->userdata('user_search_from'))) . "'";
        if ($this->session->userdata('user_search_to') != '')
            $wh[] = " `created_at` <= '" . date('Y-m-d', strtotime($this->session->userdata('user_search_to'))) . "'";

        if (count($wh) > 0) {
            $WHERE = implode(' and ', $wh);
            return $this->datatable->LoadJson($SQL, $WHERE);
        } else {
            return $this->datatable->LoadJson($SQL);
        }
    }
    //---------------------------------------------------
    // Get user detial by ID
    public function get_user_by_id($id)
    {
        $query = $this->db->get_where('user', array('user_id' => $id));
        return $result = $query->row_array();
    }

    //---------------------------------------------------
    // Edit user Record
    public function edit_user($data, $id)
    {
        $this->db->where('user_id', $id);
        $this->db->update('user', $data);
        return true;
    }

    //---------------------------------------------------
    // Get User Role/Group
    public function get_user_groups()
    {
        $query = $this->db->get('user_role');
        return $result = $query->result_array();
    }

    public function get_roles()
    {
        $ret = array();
        $roles = $this->get_user_groups();
        foreach ($roles as $role) {
            $ret[$role['user_id']] = $role['group_name'];
        }
        return $ret;
    }
}