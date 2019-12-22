<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['class'] = 'order';
			$data['view'] = 'admin/order/order_list';
			$data['cur_tab'] = 'order';
			$this->load->view('layout', $data);
		}

    public function view(){
        $data['page_title'] = 'Commande';
        $data['record'] = $this->order_model->get_for_edit($this->uri->segment(4));
        $data['order_id'] = $this->uri->segment(4);
        $data['view'] = 'admin/order/order_view';
        $data['cur_tab'] = 'order';
        $this->load->view('layout', $data);
    }

		public function datatable_json(){
			$records = $this->order_model->get_all_jason();
			$records['data'] = $this->datatable->transformThreelDataset('order', $records, array('order_id', 'order_date', 'email', 'order_co2', 'order_total', 'project_key', 'order_status' ), array('view', 'edit'));
	        echo json_encode($records);
		}
        public function user_datatable_json(){
            $where = array(
                'order_user_id' => $this->uri->segment(4),
            );
            $records = $this->order_model->get_all_jason($where);
            $data = array();
            $records['data'] = $this->datatable->transformThreelDataset('order', $records, array('order_id', 'order_date', 'order_co2', 'order_total', 'project_key', 'order_status' ), array('view'));
            echo json_encode($records);
        }

		public function add(){
			$data['lang'] = 'default';
            $data['languages'] = $this->language_model->get_all();
			$data['view'] = 'admin/order/order_add';
			$data['page_title'] = 'Ajouter un order';
			$data['form_button'] = 'Ajouter le order';
            $data['partners'] = $this->partner_model->get_all();
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
			$data['op'] = 'add';
			$data['cur_tab'] = 'order';
			$this->load->view('layout', $data);
		}
		public function do_edit($lang = 'default'){

			$data['record_id'] = $this->uri->segment(4);
			$data['record'] = $this->order_model->get_for_edit($data['record_id'], $lang);
            if ($lang == 'default') {
                $data['op'] = 'edit';
            } else {
                $data['op'] = 'edit_ml';
            }
			$data['view'] = 'admin/order/order_add';
			$data['page_title'] = 'Modifier une commande';
			$data['form_button'] = 'Modifier la commande';
			if ($this->session->flashdata('form_data')) {
				$data['record'] = $this->session->flashdata('form_data');
			}
            $data['lang'] = $lang;
			$data['cur_tab'] = 'order';
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

            $data = array(
                'order_text' => $this->input->post('order_text'),
            );

			if ($op == 'edit') {
				$result = $this->order_model->update($this->input->post('record_id'), $data, true);
				$feedback = 'La commande été modifiée !';
            } elseif ($op == 'edit_ml') {
                $result = $this->order_model->update_lang($this->input->post('record_id'), $lang, $data);
                $feedback = 'La commande été modifiée !';
			} else {
				$result = $this->order_model->insert($data);
				$feedback = 'La commande a été ajoutée !';
			}
			$data['op'] = $op;
			if ($result) {
				$this->session->set_flashdata('msg', $feedback);
				redirect(base_url('admin/order'));
			} else {
				show_error('Order save failed');
			}
		}

		public function delete() {
			//$this->calculator_model->delete($this->uri->segment(4));
			//$this->session->set_flashdata('msg', 'Le order a été supprimé.');
			//redirect(base_url('admin/order'));
		}

		public function export() {
		   $this->load->library('export');
		   $this->export->export_orders();
        }
	}