<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Controller {

	private $logViewer;

	public function __construct(){
		parent::__construct();
		if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
		include_once(FCPATH . 'assets/seunmatt/codeigniter-log-viewer/src/CILogViewer.php');
    	$this->logViewer = new \CILogViewer\CILogViewer();
	}
	
	public function index() {
	    $data['logs'] = $this->logViewer->showLogs();
		$data['view'] = 'admin/logs/logs_index';
		$data['cur_tab'] = 'tools';
		$this->load->view('layout', $data);
	}
}