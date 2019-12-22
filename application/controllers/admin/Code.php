<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Code extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['class'] = 'code';
			$data['view'] = 'admin/code/code_list';
			$data['cur_tab'] = 'code';
			$this->load->view('layout', $data);
		}

		public function datatable_json(){
			$records = $this->code_model->get_all_jason();
			$records['data'] = $this->datatable->transformThreelDataset('code', $records, array('code_key', 'code_type', 'code_status', 'code_value', ), array('edit'));
	        echo json_encode($records);
		}

		public function add(){
			$data['lang'] = 'default';
            $data['languages'] = $this->language_model->get_all();
			$data['view'] = 'admin/code/code_add';
			$data['page_title'] = 'Ajouter un code';
			$data['form_button'] = 'Ajouter le code';
            $data['partners'] = $this->partner_model->get_all();
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
			$data['op'] = 'add';
			$data['cur_tab'] = 'code';
			$this->load->view('layout', $data);
		}
		public function do_edit($lang = 'default'){

			$data['record_id'] = $this->uri->segment(4);
			$data['record'] = $this->code_model->get_for_edit($data['record_id'], $lang);
            if ($lang == 'default') {
                $data['op'] = 'edit';
            } else {
                $data['op'] = 'edit_ml';
            }

            $data['partners'] = $this->partner_model->get_all();

			$data['view'] = 'admin/code/code_add';
			$data['page_title'] = 'Modifier un code';
			$data['form_button'] = 'Modifier le code';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
            $data['lang'] = $lang;
			$data['cur_tab'] = 'code';
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
                    'code_key' => $this->input->post('code_key'),
                    'code_partner_id' => $this->input->post('code_partner_id'),
                    'code_value' => $this->input->post('code_value'),
                    'code_type' => $this->input->post('code_type'),
                    'code_status' => $this->input->post('code_status'),
                );
            } else {
                $data = array(
                    'code_i18n_title' => $this->input->post('code_i18n_title'),
                    'code_i18n_subtitle' => $this->input->post('code_i18n_subtitle'),
                    'code_i18n_description' => $this->input->post('code_i18n_description'),
                );
            }

			if ($op == 'edit') {
				$result = $this->code_model->update($this->input->post('record_id'), $data, true);
				$feedback = 'Le code été modifié !';
            } elseif ($op == 'edit_ml') {
                $result = $this->code_model->update_lang($this->input->post('record_id'), $lang, $data);
                $feedback = 'Le code a été modifié !';
			} else {
				$result = $this->code_model->insert($data);
				$feedback = 'Le code a été ajouté !';
			}
			$data['op'] = $op;
			if ($result) {
				$this->session->set_flashdata('msg', $feedback);
				redirect(base_url('admin/code'));
			} else {
				show_error('Code save failed');
			}
		}

		public function delete() {
			//$this->calculator_model->delete($this->uri->segment(4));
			//$this->session->set_flashdata('msg', 'Le code a été supprimé.');
			//redirect(base_url('admin/code'));
		}

        public function export() {

			$this->load->library('export');
			$this->export->export_codes();
        }
	}