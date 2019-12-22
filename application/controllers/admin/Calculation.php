<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Calculation extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['class'] = 'calculation';
			$data['view'] = 'admin/calculation/calculation_list';
			$data['cur_tab'] = 'calculation';
			$this->load->view('layout', $data);
		}

		public function datatable_json(){
			$records = $this->calculation_model->get_all_jason();
			$records['data'] = $this->datatable->transformThreelDataset('calculation', $records, array('calculation_date', 'calculator_key', 'factor_key', 'calculation_co2'), array('view'));
	        echo json_encode($records);
		}

        public function order_datatable_json(){
		    $where = array(
		        'calculation_order_id' => $this->uri->segment(4),
            );
            $records = $this->calculation_model->get_all_jason($where);
            $records['data'] = $this->datatable->transformThreelDataset('calculation', $records, array('calculation_date', 'calculator_key', 'factor_key', 'calculation_co2'), array('view'));
            echo json_encode($records);
        }

        public function view(){
            $data['page_title'] = 'Calcul';
            $data['record'] = $this->calculation_model->get($this->uri->segment(4));
            $data['order_id'] = $data['record']['calculation_order_id'] ? $data['record']['calculation_order_id'] : false;
            $data['view'] = 'admin/calculation/calculation_view';
            $data['cur_tab'] = 'calculation';
            $data['calculation_options'] = $this->calculation_option_model->get_by_calculation($data['record']['calculation_id']);
            $this->load->view('layout', $data);
        }

		public function add(){
			$data['lang'] = 'default';
			$data['languages'] = $this->language_model->get_all();
            $data['categories'] = $this->category_model->get_all();

			$data['view'] = 'admin/calculation/calculation_add';
			$data['page_title'] = 'Ajouter un calcul';
			$data['form_button'] = 'Ajouter le calcul';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
			$data['op'] = 'add';
			$data['cur_tab'] = 'calculation';
			$this->load->view('layout', $data);
		}
		public function do_edit($lang = 'default'){
			$data['record_id'] = $this->uri->segment(4);
			$data['record'] = $this->calculation_model->get_for_edit($data['record_id'], $lang);
			if ($lang == 'default') {
				$data['op'] = 'edit';
			} else {
				$data['op'] = 'edit_ml';
			}
            $data['categories'] = $this->category_model->get_all();

			$data['languages'] = $this->language_model->get_all();
			$data['view'] = 'admin/calculation/calculation_add';
			$data['page_title'] = 'Modifier un calcul';
			$data['form_button'] = 'Modifier le calcul';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}

			$data['lang'] = $lang;
			$data['cur_tab'] = 'calculation';
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
					'calculation_category_id' => $this->input->post('calculation_category_id'),
                    'calculation_i18n_language_id' => $this->input->post('calculation_i18n_language_id'),
					'calculation_key' => $this->input->post('calculation_key'),
					'calculation_i18n_name' => $this->input->post('calculation_i18n_name'),
					'calculation_i18n_description' => $this->input->post('calculation_i18n_description'),
                    'calculation_image' => $this->input->post('calculation_image'),
                    'calculation_icon' => $this->input->post('calculation_icon'),
                    'calculation_custom' => $this->input->post('calculation_custom'),
                    'calculation_order' => $this->input->post('calculation_order'),
				);

                $config['upload_path']          = './uploads/';
                $config['allowed_types']        = 'gif|jpeg|jpg|png';
                $config['max_size']             = 1000;
                $config['max_width']            = 2100;
                $config['max_height']           = 1024;
                $config['encrypt_name']         = true;

                if ($_FILES['calculation_image']['name']) {
                    $this->load->library('upload', $config);
                    if ( !$this->upload->do_upload('calculation_image')) {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        $form_data = $this->input->post();
                        $form_data['calculation_image'] = $this->input->post('calculation_original_image');
                        $this->session->set_flashdata('form_data', $form_data);
                        if ($op == 'add')
                            redirect(base_url('admin/calculation/add/'));
                        else
                            redirect(base_url('admin/calculation/edit/' . $this->input->post('record_id')));
                    } else {
                        $upload_data = array('upload_data' => $this->upload->data());
                        $data['calculation_image'] = $this->upload->data('file_name');
                    }
                }
			} else {
				$data = array(
                    'calculation_i18n_name' => $this->input->post('calculation_i18n_name'),
                    'calculation_i18n_description' => $this->input->post('calculation_i18n_description'),
				);
			}

			if ($op == 'edit') {
				$result = $this->calculation_model->update($this->input->post('record_id'), $data, true);

				$feedback = 'Le calcul été modifiée !';
			} elseif ($op == 'edit_ml') {
				$result = $this->calculation_model->update_lang($this->input->post('record_id'), $lang, $data);
				$feedback = 'Le calcul a été modifié !';
			} else {
				$result = $this->calculation_model->insert($data);
				$feedback = 'Le calcul a été ajouté !';
			}
			$data['op'] = $op;
			if ($result) {
				$this->session->set_flashdata('msg', $feedback);
				redirect(base_url('admin/calculation'));
			} else {
				show_error('Calculation save failed');
			}
		}

		public function delete() {
			//$this->calculation_model->delete($this->uri->segment(4));
			//$this->session->set_flashdata('msg', 'Le calcul a été supprimée.');
			//redirect(base_url('admin/calculation'));
		}

		public function export() {
			$this->load->library('export');
			$this->export->export_calculations();
        }
	}