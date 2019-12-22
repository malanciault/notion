<?php

class MY_404 extends MY_Controller {
 
	public function __construct() {
		parent::__construct();
	}
 
	public function index() {
		$this->output->set_status_header('404');
		$data = array(
			'intro' => false,
			'page_title' => 'Page introuvable',
		);
		$this->load_full_template('templates/404', $data);
	}
}