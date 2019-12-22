<?php

class Home extends CI_Controller {

	public function index() {
		if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
		load_admin_full_template('home');
	}
}