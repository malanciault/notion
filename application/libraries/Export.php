<?php
class Export {

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('excel');
    }

    public function export_all(){

        $data = array(
            'Users' => $this->export_users(false),
        );

        $this->CI->excel->multipages_export($data);
    }

    public function export_users($create=true){

        $columns = array(
            'user_id',
            'firstname',
            'lastname',
            'email',
            'password',
            'address',
            'role',
            'is_active',
            'is_verify',
            'is_admin',
            'token',
            'password_reset_code',
            'last_ip',
            'created_at',
            'updated_at',
            'org_id',
            'source',
            'last_login',
            'expiration_date',
            'import_id',
            'language_id',
        );

        $records = $this->CI->user_model->get_for_export($columns);
        $filename = 'users_export_' . date("YmdHms") . '.xlsx';

        $data = array(
            'name'  => 'Utilisateurs',
            'filename' => $filename,
            'columns'   => $columns,
            'content'   => $records,
        );

        if($create){
            $this->CI->excel->export($data['columns'], $data['content'], $data['filename']);
        }

        return $data;
    }
}