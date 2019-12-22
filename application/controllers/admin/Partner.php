<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Vnn\WpApiClient\Auth\WpBasicAuth;
use Vnn\WpApiClient\Http\GuzzleAdapter;
use Vnn\WpApiClient\WpClient;


class Partner extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['class'] = 'partner';
			$data['view'] = 'admin/partner/partner_list';
			$data['cur_tab'] = 'partner';
			$this->load->view('layout', $data);
		}

        
		public function datatable_json(){
            $records = $this->partner_model->get_all_jason();
			$records['data'] = $this->datatable->transformThreelDataset('partner', $records, array('partner_key'), array(), true);
	        echo json_encode($records);
		}

        public function export() {
			$this->load->library('export');
			$this->export->export_partners();
        }
	}