<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Factor extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['class'] = 'factor';
			$data['view'] = 'admin/factor/factor_list';
			$data['cur_tab'] = 'factor';
			$this->load->view('layout', $data);
		}

		public function datatable_json(){
			$records = $this->factor_model->get_all_jason();
			$records['data'] = $this->datatable->transformThreelDataset('factor', $records, array('calculator_key', 'factor_key', 'factor_value'), array('edit'), true);
	        echo json_encode($records);
		}

		public function add(){
			$data['lang'] = 'default';
            $data['languages'] = $this->language_model->get_all();
			$data['view'] = 'admin/factor/factor_add';
			$data['page_title'] = 'Ajouter un facteur';
			$data['form_button'] = 'Ajouter le facteur';
            $data['calculators'] = $this->calculator_model->get_all();
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
			$data['op'] = 'add';
			$data['cur_tab'] = 'factor';
			$this->load->view('layout', $data);
		}
		public function do_edit($lang = 'default'){

			$data['record_id'] = $this->uri->segment(4);
			$data['record'] = $this->factor_model->get_for_edit($data['record_id'], $lang);
            if ($lang == 'default') {
                $data['op'] = 'edit';
            } else {
                $data['op'] = 'edit_ml';
            }

            $data['languages'] = $this->language_model->get_all();
            $data['calculators'] = $this->calculator_model->get_all();

			$data['view'] = 'admin/factor/factor_add';
			$data['page_title'] = 'Modifier un facteur';
			$data['form_button'] = 'Modifier le facteur';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
            $data['lang'] = $lang;
			$data['cur_tab'] = 'factor';
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
                    'factor_i18n_language_id' => $this->input->post('factor_i18n_language_id'),
                    'factor_calculator_id' => $this->input->post('factor_calculator_id'),
                    'factor_key' => $this->input->post('factor_key'),
                    'factor_value' => $this->input->post('factor_value'),
                    'factor_i18n_caption' => $this->input->post('factor_i18n_caption'),
                );
            } else {
                $data = array(
                    'factor_i18n_language_id' => $this->input->post('factor_i18n_language_id'),
                    'factor_i18n_caption' => $this->input->post('factor_i18n_caption'),
                );
            }

			if ($op == 'edit') {
				$result = $this->factor_model->update($this->input->post('record_id'), $data, true);
				$feedback = 'Le facteur été modifié !';
            } elseif ($op == 'edit_ml') {
                $result = $this->factor_model->update_lang($this->input->post('record_id'), $lang, $data);
                $feedback = 'Le facteur a été modifié !';
			} else {
				$result = $this->factor_model->insert($data);
				$feedback = 'Le facteur a été ajouté !';
			}
			$data['op'] = $op;
			if ($result) {
				$this->session->set_flashdata('msg', $feedback);
				redirect(base_url('admin/factor'));
			} else {
				show_error('Factor save failed');
			}
		}

		public function delete() {
			//$this->calculator_model->delete($this->uri->segment(4));
			//$this->session->set_flashdata('msg', 'Le facteur a été supprimé.');
			//redirect(base_url('admin/factor'));
		}

    public function export() {
		$this->load->library('export');
		$this->export->export_factors();
    }
}