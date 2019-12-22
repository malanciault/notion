<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends MY_Controller {


	public function __construct(){
		parent::__construct();
		if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
	}
	
	public function export_all() {
        $this->load->library('export');
        $this->export->export_all();
	}
}