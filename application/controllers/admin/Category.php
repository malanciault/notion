<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Category extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['class'] = 'category';
			$data['view'] = 'admin/category/category_list';
			$data['cur_tab'] = 'category';
			$this->load->view('layout', $data);
		}

		public function datatable_json(){
			$records = $this->category_model->get_all_jason();
			$records['data'] = $this->datatable->transformThreelDataset('category', $records, array('category_name'), array('edit', 'delete'), true);
	        echo json_encode($records);
		}

		public function add(){
			$data['lang'] = 'default';
			$data['languages'] = $this->language_model->get_all();
			$data['view'] = 'admin/category/category_add';
			$data['page_title'] = 'Ajouter une catégorie';
			$data['form_button'] = 'Ajouter la catégorie';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
			$data['op'] = 'add';
			$data['cur_tab'] = 'category';
			$this->load->view('layout', $data);
		}
		public function do_edit($lang = 'default'){
			$data['record_id'] = $this->uri->segment(4);
			$data['record'] = $this->category_model->get_for_edit($data['record_id'], $lang);
			if ($lang == 'default') {
				$data['op'] = 'edit';
			} else {
				$data['op'] = 'edit_ml';
			}
            $data['categories_array'] = $this->category_model->get_all();

			$data['languages'] = $this->language_model->get_all();
			$data['view'] = 'admin/category/category_add';
			$data['page_title'] = 'Modifier une catégorie';
			$data['form_button'] = 'Modifier la catégorie';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}

			$data['lang'] = $lang;
			$data['cur_tab'] = 'category';
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
                    'category_i18n_language_id' => $this->input->post('category_i18n_language_id'),
					'category_i18n_name' => $this->input->post('category_i18n_name'),
				);
			} else {
				$data = array(
                    'category_i18n_language_id' => $this->input->post('category_i18n_language_id'),
                    'category_i18n_name' => $this->input->post('category_i18n_name'),
				);
			}

			if ($op == 'edit') {
				$result = $this->category_model->update($this->input->post('record_id'), $data, true);

				$feedback = 'La catégorie été modifiée !';
			} elseif ($op == 'edit_ml') {
				$result = $this->category_model->update_lang($this->input->post('record_id'), $lang, $data);
				$feedback = 'La catégorie a été modifiée !';
			} else {

				$result = $this->category_model->insert($data);
				$feedback = 'La catégorie a été ajoutée !';
			}
			$data['op'] = $op;
			if ($result) {
				$this->session->set_flashdata('msg', $feedback);
				redirect(base_url('admin/category'));
			} else {
				show_error('Category save failed');
			}
		}

		public function delete() {
			//$this->category_model->delete($this->uri->segment(4));
			//$this->session->set_flashdata('msg', 'La catégoriea été supprimée.');
			//redirect(base_url('admin/category'));
		}
	}