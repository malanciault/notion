<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Dashboard extends MY_Controller {
		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');

			$this->load->model('admin/dashboard_model', 'dashboard_model');
			$this->load->model('dashboard_model');
		}

		public function index(){
			$data['all_users'] = $this->dashboard_model->get_all_users();
			$data['active_users'] = $this->dashboard_model->get_active_users();
			$data['deactive_users'] = $this->dashboard_model->get_deactive_users();
			$data['title'] = 'Dashboard';
            $data['cur_tab'] = 'dashboard';
			$data['view'] = 'admin/dashboard/dashboard1';
			$this->load->view('layout', $data);
		}
		
	}