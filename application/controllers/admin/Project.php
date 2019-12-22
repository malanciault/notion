<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Malanciault\WpApiClient\Auth\WpBasicAuth;
use Malanciault\WpApiClient\Http\GuzzleAdapter;
use Malanciault\WpApiClient\WpClient;


class Project extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['class'] = 'project';
			$data['view'] = 'admin/project/project_list';
			$data['cur_tab'] = 'project';
			$this->load->view('layout', $data);
		}

        public function sync(){
            $data['class'] = 'project';
            $data['view'] = 'admin/project/project_list';
            $data['cur_tab'] = 'project';

            //[vc_
            $this->do_sync('french');
            $this->do_sync('english');

            $this->load->view('layout', $data);
        }

        private function do_sync($lang) {
		    if ($lang == 'french') {
		        $wp_api_url =  'https://planetair.ca';
            } else {
                $wp_api_url =  'https://planetair.ca/en/';
            }
            $client = new WpClient(new GuzzleAdapter(new GuzzleHttp\Client()), $wp_api_url);
            $client->setCredentials(new WpBasicAuth('malanciault', 'Xoops32matrix?'));

            $portfolios = $client->portfolios()->get();


            foreach($portfolios as $portfolio) {
                x('wp_post_id=' . $portfolio['id']);
                $portfolio_data = array(
                    'project_i18n_title' => $portfolio['title']['rendered'],
                    'project_i18n_description' => $portfolio['content']['rendered'],
                );
                $this->project_i18n_model->update_from_wp($portfolio['id'], $portfolio_data);
            }
        }

		public function datatable_json(){
			$records = $this->project_model->get_all_jason();
			$records['data'] = $this->datatable->transformThreelDataset('project', $records, array('project_i18n_title', 'project_price', 'project_order', ), array('edit'), true);
	        echo json_encode($records);
		}

		public function add(){
			$data['lang'] = 'default';
            $data['languages'] = $this->language_model->get_all();
			$data['view'] = 'admin/project/project_add';
			$data['page_title'] = 'Ajouter un projet';
			$data['form_button'] = 'Ajouter le projet';
            $data['calculators'] = $this->calculator_model->get_all();
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
			$data['op'] = 'add';
			$data['cur_tab'] = 'project';
			$this->load->view('layout', $data);
		}
		public function do_edit($lang = 'default'){

			$data['record_id'] = $this->uri->segment(4);
			$data['record'] = $this->project_model->get_for_edit($data['record_id'], $lang);
            if ($lang == 'default') {
                $data['op'] = 'edit';
            } else {
                $data['op'] = 'edit_ml';
            }

            $data['languages'] = $this->language_model->get_all();
            $data['calculators'] = $this->calculator_model->get_all();

			$data['view'] = 'admin/project/project_add';
			$data['page_title'] = 'Modifier un projet';
			$data['form_button'] = 'Modifier le projet';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
            $data['lang'] = $lang;
			$data['cur_tab'] = 'project';
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
                    'project_i18n_language_id' => $this->input->post('project_i18n_language_id'),
                    'project_key' => $this->input->post('project_key'),
                    'project_price' => $this->input->post('project_price'),
                    'project_url' => $this->input->post('project_url'),
                    'project_wp_post_id' => $this->input->post('project_wp_post_id'),
                    'project_order' => $this->input->post('project_order'),
                    'project_i18n_title' => $this->input->post('project_i18n_title'),
                    'project_i18n_subtitle' => $this->input->post('project_i18n_subtitle'),
                    'project_i18n_description' => $this->input->post('project_i18n_description'),
                );
            } else {
                $data = array(
                    'project_i18n_title' => $this->input->post('project_i18n_title'),
                    'project_i18n_subtitle' => $this->input->post('project_i18n_subtitle'),
                    'project_i18n_description' => $this->input->post('project_i18n_description'),
                );
            }

			if ($op == 'edit') {
				$result = $this->project_model->update($this->input->post('record_id'), $data, true);
				$feedback = 'Le projet été modifié !';
            } elseif ($op == 'edit_ml') {
                $result = $this->project_model->update_lang($this->input->post('record_id'), $lang, $data);
                $feedback = 'Le projet a été modifié !';
			} else {
				$result = $this->project_model->insert($data);
				$feedback = 'Le projet a été ajouté !';
			}
			$data['op'] = $op;
			if ($result) {
				$this->session->set_flashdata('msg', $feedback);
				redirect(base_url('admin/project'));
			} else {
				show_error('Project save failed');
			}
		}

		public function delete() {
			//$this->calculator_model->delete($this->uri->segment(4));
			//$this->session->set_flashdata('msg', 'Le projet a été supprimé.');
			//redirect(base_url('admin/project'));
		}

        public function export() {
            $this->load->library('export');
            $this->export->export_projects();

        }
}