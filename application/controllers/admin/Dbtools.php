<?php

class Dbtools extends MY_Controller {

	private $path;
	private $backup_path;
	private $clean_path;
	private $restore_path;
	private $prefs;
	private $files;
	private $db_current_version = 0;
	private $db_latest_version = 1;

	public function __construct(){
    	parent::__construct();
    	if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');

		$this->load->library('dbupdate');
	}

	public function index(){
		$this->display();
	}

	public function backup_clean() {
		$backup = $this->dbutil->backup($this->prefs);
		$clean_filename = 'clean.zip';
		write_file($this->clean_path  . $clean_filename, $backup);
		$clean_backup_filename = 'clean_' . date("Y-m-d-H-i-s") .'.zip';
		write_file($this->clean_path . $clean_backup_filename, $backup);
		$this->session->set_flashdata('msg', 'The backup as clean database has been completed, file name is ' . $clean_filename . '.');
		redirect('admin/dbtools');
	}

	public function backup() {
		$backup = $this->dbutil->backup($this->prefs);
		$this->load->helper('file');
		$filename = date("Y-m-d-H-i-s") .'.zip';
		write_file($this->backup_path . $filename, $backup);
		$this->session->set_flashdata('msg', 'The backup has been completed, file name is ' . $filename . '.');
		redirect('admin/dbtools');
	}

	public function restore_clean() {
		$this->do_restore($this->clean_path . 'clean.zip');
		redirect('admin/dbtools');
	}

	public function restore() {
		$this->output->enable_profiler(FALSE);
		$this->do_restore($this->backup_path . $this->input->post('file') . '.zip');
		echo json_encode(site_url('dbtools'));
	}

	private function do_restore($zip_filename) {
		$this->load->helper('file');
		$zip = new ZipArchive;
		$extract_path = $this->restore_path;
		if ($zip->open($zip_filename) === TRUE) {
		    $zip->extractTo($extract_path);
		    $zip->close();
			$filename = $extract_path . 'backup.sql';
			if (read_file($filename)) {
			    $thefile = file($filename);
				foreach ($thefile as $line)	{
					$startWith = substr(trim($line), 0 ,2);
					$endWith = substr(trim($line), -1 ,1);
					if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
						continue;
					}
					$query = (isset($query) ? $query : '') . $line;
					if ($endWith == ';') {
						$this->db->query($query);
						$query = '';
					}
				}
				$this->session->set_flashdata('msg', 'The backup "' . $zip_filename . '" has been restored');
		    } else {
		    	$this->session->set_flashdata('error', 'The backup file "backup.sql" was not found');
		    }
		} else {
		    $this->session->set_flashdata('error', 'Could not open the zip file "' . $zip_filename . '"');
		}
		delete_files($extract_path);
	}

	private function display() {
		$data['view'] = 'admin/dbtools';
		$data['files'] = $this->files;
		$data['db_current_version'] = $this->dbupdate->current();
		$data['db_latest_version'] = $this->dbupdate->latest();
		$data['cur_tab'] = 'tools';
		$this->load->view('layout', $data);
	}

	public function delete_user_data() {
		$this->db->trans_start();
		$this->db->query('SET GLOBAL FOREIGN_KEY_CHECKS = 0;');
		$this->db->trans_complete();
		$this->db->trans_start();
		$this->db->query('SET GLOBAL FOREIGN_KEY_CHECKS = 0;');
		$this->db->query('TRUNCATE user_activity;');
		$this->db->query('TRUNCATE user_concept_sorting;');
		$this->db->query('TRUNCATE user_output;');
		$this->db->query('TRUNCATE user_question;');
		//$this->db->query('TRUNCATE objective;');
		$this->db->query('SET GLOBAL FOREIGN_KEY_CHECKS = 1;');
		$this->db->trans_complete();
		$this->session->set_flashdata('msg', 'User data gone !');
		redirect('admin/dbtools');
	}

	public function take_upgrade() {
	    $takes = $this->take_model->get_no_product_access();
	    xd($takes);
    }

	public function upgrade() {
		$this->dbupdate->upgrade();
		redirect('admin/dbtools');
	}
}