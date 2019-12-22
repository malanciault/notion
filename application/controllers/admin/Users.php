<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Users extends MY_Controller {

		public function __construct(){
			parent::__construct();
			if (!$this->session->is_admin_login)
				show_error('Désolé, vous ne possédez pas les droits nécéssaires pour accéder cette page.', 500, 'Une erreur est survenue');
			$this->load->model('user_model', 'user_model');
			$this->load->library('datatable'); // loaded my custom serverside datatable library
		}

		public function index(){
			$data['view'] = 'admin/users/user_list';
			$data['cur_tab'] = 'people';
			$this->load->view('layout', $data);
		}

		public function datatable_json(){				   					   
			
			$records = $this->user_model->get_all_users();
	        $data = array();
	        foreach ($records['data']  as $row) {  
	        	$groups_records = $this->user_grp_model->get_groups($row['user_id']);
	        	$groups = '';
	        	foreach ($groups_records as $group) {
	        		$groups .= '<span style="margin-right: 3px" class="btn btn-success btn-flat btn-xs" title="status">'.$group['grp_name'].'</span>';
	        	}
				$status = ($row['is_active'] == 0)? 'Deactive': 'Active'.'<span>';
				$disabled = ($row['is_admin'] == 1)? 'disabled': ''.'<span>';
				$data[]= array(
                    '<a href="' . site_url('admin/users/view/' . $row['user_id']) . '">' . $row['created_at'] . '</a>',
					$row['last_login'],
					$row['email'],
					$row['source'],
					'<span style="" class="btn btn-info btn-flat btn-xs" title="status">'.getGroupyName($row['role']).'<span>',	
					$groups,
					'<span class="btn btn-success btn-flat btn-xs" title="status">'.$status.'<span>',
					'<span href="" class="btn btn-success btn-flat btn-xs" title="status"><a target="_blank" href="https://mixpanel.com/report/1891781/explore#user?distinct_id=' . $row['user_id'] . '">Go</a><span>',
					$this->datatable->get_actions('users', $row['user_id'], array('edit', 'view')),
				);
	        }
			$records['data']=$data;
	        echo json_encode($records);						   
		}

		public function add(){
			if($this->input->post('submit')){
				$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
				$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
				$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
				$this->form_validation->set_rules('password', 'Password', 'trim|required');
				$this->form_validation->set_rules('group', 'Group', 'trim|required');

				if ($this->form_validation->run() == FALSE) {
					$data['view'] = 'admin/users/user_add';
                    $data['cur_tab'] = 'people';
					$this->load->view('layout', $data);
				}
				else{
					$data = array(
						'firstname' => $this->input->post('firstname'),
						'lastname' => $this->input->post('lastname'),
						'email' => $this->input->post('email'),
						'password' =>  password_hash($this->input->post('password'), PASSWORD_BCRYPT),
						'role' => $this->input->post('group'),
						'created_at' => date('Y-m-d : h:m:s'),
						'updated_at' => date('Y-m-d : h:m:s'),
						'is_verify' => 1,
					);
					$data['expiration_date'] = $this->input->post('expiration_date') ? $this->input->post('expiration_date') : NULL;
					$result = $this->user_model->add_user($data);
					if($result){
						$this->session->set_flashdata('msg', 'User has been added successfully!');
						redirect(base_url('admin/users'));
					}
				}
			}
			else{
				$data['user_groups'] = $this->user_model->get_user_groups();
				$data['view'] = 'admin/users/user_add';
				$this->load->view('layout', $data);
			}
		}

		public function edit($id = 0){

			if($this->input->post('submit')){
				$this->form_validation->set_rules('firstname', 'Username', 'trim|required');
				$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
				$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
				$this->form_validation->set_rules('status', 'Status', 'trim|required');
				$this->form_validation->set_rules('group', 'Group', 'trim|required');

				if ($this->form_validation->run() == FALSE) {
					$data['user'] = $this->user_model->get_user_by_id($id);
					$data['view'] = 'admin/users/user_edit';
                    $data['user_groups'] = $this->user_model->get_user_groups();
                    $data['cur_tab'] = 'people';
					$this->load->view('layout', $data);
				}
				else{
					
					if ($this->input->post('password') && ($this->input->post('password') != $this->input->post('password2'))) {
						$data['user'] = $this->user_model->get_user_by_id($id);
						$data['user_groups'] = $this->user_model->get_user_groups();
						$data['view'] = 'admin/users/user_edit';
						$this->load->view('layout', $data);
					} else {
                        $data['firstname'] = $this->input->post('firstname');
						$data['lastname'] = $this->input->post('lastname');
						$data['email'] = $this->input->post('email');
						if ($this->input->post('password'))
							$data['password'] =  password_hash($this->input->post('password'), PASSWORD_BCRYPT);
						$data['role'] = $this->input->post('group');
						$data['is_active'] = $this->input->post('status');
                        $data['expiration_date'] = $this->input->post('expiration_date') ? $this->input->post('expiration_date') : NULL;
                        if ($this->input->post('expiration_clear'))
                            $data['expiration_date'] = null;
						$data['is_verify'] = $this->input->post('is_verify');
						$data['updated_at'] = date('Y-m-d : h:m:s');
						$result = $this->user_model->edit_user($data, $id);
						if($result){
							$this->session->set_flashdata('msg', 'User has been updated successfully!');
							redirect(base_url('admin/users'));
						}
					}
				}
			}
			else {
				$data['user'] = $this->user_model->get_user_by_id($id);
				$data['user_groups'] = $this->user_model->get_user_groups();
				$data['view'] = 'admin/users/user_edit';
				$this->load->view('layout', $data);
			}
		}

		public function view(){
			$data['view'] = 'admin/users/user_view';
			$data['user_id'] = $this->uri->segment(4);
			$data['record'] = $this->user_model->get_user_by_id($data['user_id']);
			$data['cur_tab'] = 'people';
			$this->load->view('layout', $data);
		}

		public function del($id = 0){
			$this->db->delete('user', array('id' => $id));
			$this->session->set_flashdata('msg', 'Use has been deleted successfully!');
			redirect(base_url('admin/users'));
		}

        public function password_reset() {
            $user_id = $this->uri->segment(4);
            $data['password'] = password_hash('illuxi1234', PASSWORD_BCRYPT);
            $data['is_verify'] = 1;
            $data['is_active'] = 1;
            $this->user_model->change_pwd($data, $user_id);

            redirect(site_url() . 'admin/users/view/' . $user_id);
        }

        public function export() {
            $this->load->library('export');
            $this->export->export_users();
        }

	}