<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Config extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['class'] = 'config';
			$data['view'] = 'admin/config/config_list';
			$data['cur_tab'] = 'tools';
			$this->load->view('layout', $data);
		}

		public function datatable_json(){				   					   
			$records = $this->config_model->get_all_jason();
	        $data = array();
			$records['data'] = $this->datatable->transformThreelDataset('config', $records, array('config_key', 'config_value'), array('edit'));
	        echo json_encode($records);						   
		}

		public function do_edit($lang = 'default'){
			$data['config_id'] = $this->uri->segment(4);
			$data['record'] = $this->config_model->get_for_edit($data['config_id'], $lang);
			$data['op'] = 'edit';
			$data['view'] = 'admin/config/config_add';
			$data['page_title'] = 'Modifier une config';
			$data['form_button'] = 'Modifier la config';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');	
			}
			$data['cur_tab'] = 'tools';
			$this->load->view('layout', $data);
		}

		public function edit(){
			$this->do_edit();
		}

		public function edit_ml() {
			$this->do_edit($this->uri->segment(5));
		}

		public function post() {

			$op = $this->input->post('op');


            $data = array(
                'config_key' => $this->input->post('config_key'),
                'config_value' => $this->input->post('config_value'),
            );
			
			if ($op == 'edit') {
				$result = $this->config_model->update($this->input->post('config_id'), $data, true);	
				$feedback = 'La config a été modifiée !';
			}
			$data['op'] = $op;
			if ($result) {
				$this->session->set_flashdata('msg', $feedback);
				redirect(base_url('admin/config'));
			} else {
				show_error('config save failed');
			}
		}
	}