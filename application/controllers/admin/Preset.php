<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Malanciault\WpApiClient\Auth\WpBasicAuth;
use Malanciault\WpApiClient\Http\GuzzleAdapter;
use Malanciault\WpApiClient\WpClient;


class Preset extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['class'] = 'preset';
			$data['view'] = 'admin/preset/preset_list';
			$data['cur_tab'] = 'preset';
			$this->load->view('layout', $data);
		}

		public function datatable_json(){
			$records = $this->preset_model->get_all_jason();
			$records['data'] = $this->datatable->transformThreelDataset('preset', $records, array('preset_id', 'preset_key', 'preset_status'), array('edit'), true, false);
	        echo json_encode($records);
		}

		public function add(){
			$data['lang'] = 'default';
            $data['languages'] = $this->language_model->get_all();
			$data['view'] = 'admin/preset/preset_add';
			$data['page_title'] = 'Ajouter un preset';
			$data['form_button'] = 'Ajouter le preset';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
			$data['op'] = 'add';
			$data['cur_tab'] = 'preset';
			$this->load->view('layout', $data);
		}
		public function do_edit($lang = 'default'){

			$data['record_id'] = $this->uri->segment(4);
			$data['record'] = $this->preset_model->get_for_edit($data['record_id'], $lang);
            if ($lang == 'default') {
                $data['op'] = 'edit';
            } else {
                $data['op'] = 'edit_ml';
            }

            $data['languages'] = $this->language_model->get_all();
            $data['calculators'] = $this->calculator_model->get_all();

			$data['view'] = 'admin/preset/preset_add';
			$data['page_title'] = 'Modifier un preset';
			$data['form_button'] = 'Modifier le preset';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
            $data['lang'] = $lang;
			$data['cur_tab'] = 'preset';
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
                    'preset_i18n_language_id' => $this->input->post('preset_i18n_language_id'),
                    'preset_key' => $this->input->post('preset_key'),
                    'preset_amount' => $this->input->post('preset_amount'),
                    'preset_status' => $this->input->post('preset_status'),
                    'preset_co2' => $this->input->post('preset_co2'),
                    'preset_options' => $this->input->post('preset_options'),
                    'preset_i18n_html' => $this->input->post('preset_i18n_html'),
                    'preset_i18n_description' => $this->input->post('preset_i18n_description'),
                );
            } else {
                $data = array(
                    'preset_i18n_html' => $this->input->post('preset_i18n_html'),
                    'preset_i18n_description' => $this->input->post('preset_i18n_description'),
                );
            }

			if ($op == 'edit') {
				$result = $this->preset_model->update($this->input->post('record_id'), $data, true);
				$feedback = 'Le preset été modifié !';
            } elseif ($op == 'edit_ml') {
                $result = $this->preset_model->update_lang($this->input->post('record_id'), $lang, $data);
                $feedback = 'Le preset a été modifié !';
			} else {
				$result = $this->preset_model->insert($data);
				$feedback = 'Le preset a été ajouté !';
			}
			$data['op'] = $op;
			if ($result) {
				$this->session->set_flashdata('msg', $feedback);
				redirect(base_url('admin/preset'));
			} else {
				show_error('Preset save failed');
			}
		}

		public function delete() {
			//$this->calculator_model->delete($this->uri->segment(4));
			//$this->session->set_flashdata('msg', 'Le preset a été supprimé.');
			//redirect(base_url('admin/preset'));
		}

        public function export() {
            $this->load->library('export');
            $this->export->export_presets();

        }
}