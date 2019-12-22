<?php

use Malanciault\Threelci\Core\Threel_model;

class Auth_model extends CI_Model
{

    public function login($data)
    {
        $query = $this->db->get_where('user', array('email' => $data['email']));
        if ($query->num_rows() == 0) {
            return false;
        } else {
            //Compare the password attempt with the password we have stored.
            $result = $query->row_array();
            $validPassword = password_verify($data['password'], $result['password']);
            if ($validPassword) {
                return $result = $query->row_array();
            }

        }
    }

    //--------------------------------------------------------------------
    public function register($data)
    {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    //--------------------------------------------------------------------
    public function email_verification($code)
    {
        $this->db->select('id', 'email, token, is_active');
        $this->db->from('user');
        $this->db->where('token', $code);
        $query = $this->db->get();
        $result = $query->row_array();
        if ($result > 0) {
            $this->db->where('token', $code);
            $this->db->update('user', array('is_verify' => 1, 'token' => ''));
            return $result;
        } else {
            return false;
        }
    }

    //============ Check User Email ============
    function check_user_mail($email)
    {
        $result = $this->db->get_where('user', array('email' => $email));

        if ($result->num_rows() > 0) {
            $result = $result->row_array();
            return $result;
        } else {
            return false;
        }
    }

    //============ Update Reset Code Function ===================
    public function update_reset_code($reset_code, $user_id)
    {
        $data = array('password_reset_code' => $reset_code);
        $this->db->where('id', $user_id);
        $this->db->update('user', $data);
    }

    //============ Activation code for Password Reset Function ===================
    public function check_password_reset_code($code)
    {

        $result = $this->db->get_where('user', array('password_reset_code' => $code));
        return $result->row_array();
    }

    //============ Reset Password ===================
    public function reset_password($id, $new_password)
    {
        $data = array(
            'password_reset_code' => '',
            'password' => $new_password
        );
        $this->db->where('password_reset_code', $id);
        $this->db->update('user', $data);
        return true;
    }

    //--------------------------------------------------------------------
    public function get_admin_detail()
    {
        $id = $this->session->userdata('admin_id');
        $query = $this->db->get_where('user', array('id' => $id));
        return $result = $query->row_array();
    }

    //--------------------------------------------------------------------
    public function update_admin($data)
    {
        $id = $this->session->userdata('admin_id');
        $this->db->where('id', $id);
        $this->db->update('user', $data);
        return true;
    }

    //--------------------------------------------------------------------
    public function change_pwd($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('user', $data);
        return true;
    }

}

?>