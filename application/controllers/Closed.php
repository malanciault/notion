<?php

class Closed extends MY_Controller {

	public function index() {
		$data['intro'] = false;
		$this->load_full_template('site_closed', $data);
	}

}