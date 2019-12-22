<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Page extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['class'] = 'page';
			$data['view'] = 'admin/page/page_list';
			$data['cur_tab'] = 'page';
			$this->load->view('layout', $data);
		}
		
		public function datatable_json(){				   					   
			$records = $this->page_model->get_all_jason();
	        $data = array();
			$records['data'] = $this->datatable->transformThreelDataset('page', $records, array('page_i18n_title', 'page_status'), array('edit', 'delete'), true);
	        echo json_encode($records);						   
		}

		public function add(){
			$data['lang'] = 'default';
			$data['languages'] = $this->language_model->get_all();
            $data['status_array'] = $this->page_model->status_array;
			$data['view'] = 'admin/page/page_add';
			$data['page_title'] = 'Ajouter une page';
			$data['form_button'] = 'Ajouter la page';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');	
			}
			$data['op'] = 'add';
			$data['cur_tab'] = 'page';
			$this->load->view('layout', $data);
		}
		public function do_edit($lang = 'default'){
			$data['page_id'] = $this->uri->segment(4);
			$data['record'] = $this->page_model->get_for_edit($data['page_id'], $lang);
			if ($lang == 'default') {
                $data['status_array'] = $this->page_model->status_array;
				$data['op'] = 'edit';
			} else {
				$data['op'] = 'edit_ml';
			}
			
			$data['languages'] = $this->language_model->get_all();
			$data['view'] = 'admin/page/page_add';
			$data['page_title'] = 'Modifier une page';
			$data['form_button'] = 'Modifier la page';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');	
			}
			
			$data['lang'] = $lang;
			$data['cur_tab'] = 'page';
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
					'product_i18n_title' => $this->input->post('product_i18n_title'),
					'product_i18n_slug' => $this->input->post('product_i18n_slug'),
					'product_i18n_content' => $this->input->post('product_i18n_content'),
					'product_status' => $this->input->post('product_status'),
                    'product_i18n_meta_title' => $this->input->post('product_i18n_meta_title'),
                    'product_i18n_meta_description' => $this->input->post('product_i18n_meta_description'),
                    'product_i18n_meta_image' => $this->input->post('product_i18n_meta_image'),
				);

				$config['upload_path']          = './uploads/';
	            $config['allowed_types']        = 'gif|jpeg|jpg|png';
	            $config['max_size']             = 1000;
	            $config['max_width']            = 2100;
	            $config['max_height']           = 1024;
	            $config['encrypt_name']         = true;

	            if ($_FILES['product_i18n_meta_image']['name']) {
	            	$this->load->library('upload', $config);
	                if ( !$this->upload->do_upload('product_i18n_meta_image')) {
	                    $this->session->set_flashdata('error', $this->upload->display_errors());
	                    $form_data = $this->input->post();
	                    $form_data['product_i18n_meta_image'] = $this->input->post('page_original_image');
	                    $this->session->set_flashdata('form_data', $form_data);
	                    if ($op == 'add') 
							redirect(base_url('admin/page/add/'));
						else
							redirect(base_url('admin/page/edit/' . $this->input->post('page_id')));
	                } else {
			            $upload_data = array('upload_data' => $this->upload->data());
			            $data['product_i18n_meta_image'] = $this->upload->data('file_name');
	                }
	            }
			} else {
				$data = array(
                    'product_i18n_title' => $this->input->post('product_i18n_title'),
                    'product_i18n_slug' => $this->input->post('product_i18n_slug'),
                    'product_i18n_content' => $this->input->post('product_i18n_content'),
                    'product_i18n_meta_title' => $this->input->post('product_i18n_meta_title'),
                    'product_i18n_meta_description' => $this->input->post('product_i18n_meta_description'),
                    'product_i18n_meta_image' => $this->input->post('product_i18n_meta_image'),
				);

                $config['upload_path']          = './uploads/';
                $config['allowed_types']        = 'gif|jpeg|jpg|png';
                $config['max_size']             = 1000;
                $config['max_width']            = 2100;
                $config['max_height']           = 1024;
                $config['encrypt_name']         = true;

                if ($_FILES['product_i18n_meta_image']['name']) {
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('product_i18n_meta_image')) {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        $form_data = $this->input->post();
                        $form_data['product_i18n_meta_image'] = $this->input->post('page_original_image');
                        $this->session->set_flashdata('form_data', $form_data);
                        if ($op == 'add')
                            redirect(base_url('admin/page/add/'));
                        else
                            redirect(base_url('admin/page/edit/' . $this->input->post('page_id')));
                    } else {
                        $upload_data = array('upload_data' => $this->upload->data());
                        $data['product_i18n_meta_image'] = $this->upload->data('file_name');
                    }
                }
			}
            
			
			if ($op == 'edit') {
				$result = $this->page_model->update($this->input->post('page_id'), $data, true);
				$feedback = 'La page a été modifiée !';
			} elseif ($op == 'edit_ml') {
				$result = $this->page_model->update_lang($this->input->post('page_id'), $lang, $data);
				$feedback = 'La page a été modifiée !';
			} else {
				$result = $this->page_model->insert($data);
				$feedback = 'La page a été ajoutée !';
			}
			$data['op'] = $op;
			if ($result) {
				$this->session->set_flashdata('msg', $feedback);
				redirect(base_url('admin/page'));
			} else {
				show_error('Page save failed');
			}
		}

		public function delete() {
			$this->page_model->delete($this->uri->segment(4));
			$this->session->set_flashdata('msg', 'La page a été supprimée.');
			redirect(base_url('admin/page'));
		}

	}