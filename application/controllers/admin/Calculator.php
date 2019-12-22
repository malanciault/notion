<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Calculator extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['class'] = 'calculator';
			$data['view'] = 'admin/calculator/calculator_list';
			$data['cur_tab'] = 'calculator';
			$this->load->view('layout', $data);
		}

		public function datatable_json(){
			$records = $this->calculator_model->get_all_jason();
			$records['data'] = $this->datatable->transformThreelDataset('calculator', $records, array('calculator_key', 'category_key'), array('edit'), true);
	        echo json_encode($records);
		}

		public function add(){
			$data['lang'] = 'default';
			$data['languages'] = $this->language_model->get_all();
            $data['categories'] = $this->category_model->get_all();

			$data['view'] = 'admin/calculator/calculator_add';
			$data['page_title'] = 'Ajouter un calculateur';
			$data['form_button'] = 'Ajouter le calculateur';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
			$data['op'] = 'add';
			$data['cur_tab'] = 'calculator';
			$this->load->view('layout', $data);
		}
		public function do_edit($lang = 'default'){
			$data['record_id'] = $this->uri->segment(4);
			$data['record'] = $this->calculator_model->get_for_edit($data['record_id'], $lang);
			if ($lang == 'default') {
				$data['op'] = 'edit';
			} else {
				$data['op'] = 'edit_ml';
			}
            $data['categories'] = $this->category_model->get_all();

			$data['languages'] = $this->language_model->get_all();
			$data['view'] = 'admin/calculator/calculator_add';
			$data['page_title'] = 'Modifier un calculateur';
			$data['form_button'] = 'Modifier le calculateur';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}

			$data['lang'] = $lang;
			$data['cur_tab'] = 'calculator';
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
			$lang = $this->input->post('lang');

			if ($lang == 'default') {

				$data = array(
					'calculator_category_id' => $this->input->post('calculator_category_id'),
                    'calculator_i18n_language_id' => $this->input->post('calculator_i18n_language_id'),
					'calculator_key' => $this->input->post('calculator_key'),
					'calculator_i18n_name' => $this->input->post('calculator_i18n_name'),
					'calculator_i18n_description' => $this->input->post('calculator_i18n_description'),
                    'calculator_image' => $this->input->post('calculator_image'),
                    'calculator_icon' => $this->input->post('calculator_icon'),
                    'calculator_custom' => $this->input->post('calculator_custom'),
                    'calculator_order' => $this->input->post('calculator_order'),
				);

                $config['upload_path']          = './uploads/';
                $config['allowed_types']        = 'gif|jpeg|jpg|png';
                $config['max_size']             = 1000;
                $config['max_width']            = 2100;
                $config['max_height']           = 1024;
                $config['encrypt_name']         = true;

                if ($_FILES['calculator_image']['name']) {
                    $this->load->library('upload', $config);
                    if ( !$this->upload->do_upload('calculator_image')) {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        $form_data = $this->input->post();
                        $form_data['calculator_image'] = $this->input->post('calculator_original_image');
                        $this->session->set_flashdata('form_data', $form_data);
                        if ($op == 'add')
                            redirect(base_url('admin/calculator/add/'));
                        else
                            redirect(base_url('admin/calculator/edit/' . $this->input->post('record_id')));
                    } else {
                        $upload_data = array('upload_data' => $this->upload->data());
                        $data['calculator_image'] = $this->upload->data('file_name');
                    }
                }
			} else {
				$data = array(
                    'calculator_i18n_name' => $this->input->post('calculator_i18n_name'),
                    'calculator_i18n_description' => $this->input->post('calculator_i18n_description'),
				);
			}

			if ($op == 'edit') {
				$result = $this->calculator_model->update($this->input->post('record_id'), $data, true);

				$feedback = 'Le calculateur été modifiée !';
			} elseif ($op == 'edit_ml') {
				$result = $this->calculator_model->update_lang($this->input->post('record_id'), $lang, $data);
				$feedback = 'Le calculateur a été modifié !';
			} else {
				$result = $this->calculator_model->insert($data);
				$feedback = 'Le calculateur a été ajouté !';
			}
			$data['op'] = $op;
			if ($result) {
				$this->session->set_flashdata('msg', $feedback);
				redirect(base_url('admin/calculator'));
			} else {
				show_error('Calculator save failed');
			}
		}

		public function delete() {
			//$this->calculator_model->delete($this->uri->segment(4));
			//$this->session->set_flashdata('msg', 'Le calculateur a été supprimée.');
			//redirect(base_url('admin/calculator'));
		}
	}