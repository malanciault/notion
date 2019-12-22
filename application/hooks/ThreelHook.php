<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Malanciault\Threelci\Threelci;

class ThreelfHook
{

    private $ci;

    public function post_controller_constructor()
    {
        $threelci = new Threelci();

        $this->ci =& get_instance();
        $this->ci->load->helper('language');
        $siteLang = $this->ci->i18n->current();

        if ($siteLang) {
            $this->ci->lang->load('main', $siteLang);
        } else {
            $this->ci->lang->load('main', 'french');
        }

        $this->ci->config->set_item('upload_path', FCPATH . 'uploads/');
        $this->ci->config->set_item('upload_url', base_url('uploads/'));
        define('THREEL_HASH', 'sw87d89ydhsdgsdtwdiwo');
        define('THREEL_SESSION_SALT', 's9w8d72wduigsdfgtiwfdghj');
        define('ACCESS_GROUP_ADMIN', 1);
        define('ROLE_USER', 1);
        define('ROLE_ADMIN', 6);
        define('S3URL', getenv('S3URL'));
        define('CURRENT_LANG', $this->ci->i18n->current());

        $this->ci->output->enable_profiler($this->ci->config->item('activate_profiler'));

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
            if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false)) {
                $this->ci->session->set_userdata('damnie', true);
            } else {

            }
        }

        // load models
        $this->ci->load->library('dbupdate');
        $models = $this->ci->config->item('models');

        $current_db_version = $this->ci->dbupdate->current();
        for ($i = 1; $i <= $current_db_version; $i++) {
            if (isset($models[$i])) {
                foreach ($models[$i] as $model)
                    $this->ci->load->model($model);
            }
        }

        if ($result = $this->ci->authentication->remembered()) {
            $user_data = array(
                'firstname' => $result['firstname'],
                'lastname' => $result['lastname'],
                'user_id' => $result['user_id'],
                'email' => $result['email'],
                'is_user_login' => TRUE,
                'user_role' => $result['role'],
                'admin_id' => $result['is_admin'] == 1 ? $result['id'] : 0,
                'is_admin_login' => $result['is_admin'],
                'user_role' => $result['role']
            );
            $this->ci->authentication->login_user($user_data);
        }

        // is site closed ?
        if ($this->ci->config_model->get_value_by_key('site_closed') && !$this->ci->session->is_admin_login) {
            if (
                $this->ci->router->fetch_class() != 'closed' &&
                ($this->ci->router->fetch_class() != 'auth' || $this->ci->router->fetch_method() != 'login')
            ) {
                redirect('closed');
            } else {

            }
        }
    }
}