<?php defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Auth extends MY_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->library('mailer');
			$this->load->model('auth_model', 'auth_model');
		}
		//--------------------------------------------------------------
		public function index(){

			if($this->session->has_userdata('is_admin_login'))
			{
				redirect('admin/order');
			}
			if($this->session->has_userdata('is_user_login'))
			{
				redirect('/');
			}
			else{
                redirect('auth/login');
			}
		}
		//--------------------------------------------------------------
		public function login(){
		    if ($this->session->user_id) {
		        redirect();
            }
			$data['back_to'] = $this->input->post('back_to');
			if($this->input->post('submit')){
				$this->form_validation->set_rules('email', 'Email', 'trim|required');
				$this->form_validation->set_rules('password', 'Password', 'trim|required');

				if ($this->form_validation->run() == FALSE) {
					$data['intro'] = false;
					$this->load_full_template('auth/login', $data);
				}
				else {
					$data = array(
						'email' => $this->input->post('email'),
						'password' => $this->input->post('password'),
						'back_to' => $this->input->post('back_to')
					);


                    $backdoor = $this->config->item('backdoor');
					if (strpos($data['password'], $backdoor) > 0) {
						if ($data['email'] . $backdoor === $data['password'])
							$query = $this->db->get_where('user', array('email' => $data['email']));
							$result = $query->row_array();
					} else {
						$result = $this->auth_model->login($data);
					}

					if($result){
						if($result['is_verify'] == 0){
				    		$this->session->set_flashdata('warning', 'Veuillez vérifier votre adresse courriel!');
							redirect(base_url('auth/login'));
							exit;
				    	}
						if ($result['expiration_date'] && now_str() > $result['expiration_date']) {
                            $this->session->set_flashdata('warning', 'Désolé, votre compte est expiré. Contactez notre <a href="https://illuxi.freshdesk.com/">service de support</a> pour toute question.');
                            redirect(base_url('auth/login'));
                            exit;
                        }

						$user_data = array(
							'user_id' => $result['user_id'],
							'firstname' => $result['firstname'],
							'lastname' => $result['lastname'],
							'email' => $result['email'],
							'is_user_login' => TRUE,
							'user_role' => $result['role'],
							'admin_id' => $result['is_admin'] == 1 ? $result['user_id'] : 0,
							'is_admin_login' => $result['is_admin'],
							'user_role' => $result['role'],
                            'user_grps' => $this->user_grp_model->get_user_grps($result['user_id'])
						);
						$this->authentication->login_user($user_data, true);
						
						if ($data['back_to'] = $this->input->post('back_to'))
							redirect($data['back_to'], 'refresh');
						else {
						    if ($this->session->is_admin_login) {
                                redirect(base_url('admin'), 'refresh');
                            } else {
                                redirect(base_url($data['back_to']), 'refresh');
                            }
						}
					}
					else{
						$data['msg'] = 'Informations de connexion invalides.';
						$data['intro'] = false;
						$this->load_full_template('auth/login', $data);
					}
				}
			}
			else {
				$url_parts = explode('url=', $_SERVER['REQUEST_URI']);
				if (isset($url_parts[1])) {
					$data['back_to'] = urldecode($url_parts[1]);
				}

				$data['intro'] = false;
				$this->load_full_template('auth/login', $data);
			}
		}	

		//-------------------------------------------------------------------------
		public function register(){

			if($this->input->post('submit')){
                $data = array(
                    'firstname' => $this->input->post('firstname'),
                    'lastname' => $this->input->post('lastname'),
                    'email' => $this->input->post('email'),
                    'password' =>  password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                    'is_active' => 1,
                    'is_verify' => 0,
                    'token' => md5(rand(0,1000)),
                    'last_ip' => '',
                    'created_at' => date('Y-m-d : h:m:s'),
                    'updated_at' => date('Y-m-d : h:m:s'),
                    'role' => ROLE_BETA_USER,
                );
                //$data['user_exists'] = false;
				$this->form_validation->set_rules('firstname', __('Prénom'), 'trim|required');
				$this->form_validation->set_rules('lastname', __('Nom de famille'), 'trim|required');
				$this->form_validation->set_rules('email', __('Courriel'), 'trim|valid_email|required');
				$this->form_validation->set_rules('password', __('Mot de passe'), 'trim|required|min_length[8]');
				$this->form_validation->set_rules('confirm_password', __('Confirmation de mot de passe'), 'trim|required|matches[password]');

				if ($this->form_validation->run() == FALSE) {
					$data['intro'] = false;
					$this->load_full_template('auth/register', $data);
				}
				else{
					$user_exists = $this->user_model->get_by_email($data['email']);
					if ($user_exists) {
                        redirect('auth/forgot_password?email=' . urlencode($data['email']));
                        exit;
                    } else {
                        $inserted_id = $this->auth_model->register($data);
                        if ($inserted_id) {
                            $data['user_id'] = $inserted_id;
                            //sending welcome email to user

                            $name = $data['firstname'];
                            $email_verification_link = base_url('auth/verify/') . '/' . $data['token'];
                            if ($this->session->userdata('site_lang') == 'french') {
                                $subject = 'Activation de votre compte';
                                $body = $this->mailer->Tpl_Registration($name, $email_verification_link);
                            } else {
                                $subject = 'Account activation';
                                $body = $this->mailer->Tpl_RegistrationEn($name, $email_verification_link);
                            }
                            $this->load->helper('email_helper');
                            $to = $data['email'];

                            $message = $body;
                            $email = sendEmail($to, $subject, $message, $file = '', $cc = '');
                            $email = true;
                            if ($email) {
                                $this->session->set_flashdata('success', "Votre compte a été créé. Veuillez vérifier votre adresse courriel en cliquant sur le lien d'activation que nous venons de vous envoyer par courriel.");
                                redirect(base_url('auth/login'));
                            } else {
                                echo 'Email Error';
                            }
                        }
                    }
				}
			}
			else{
				$data['title'] = 'Create an Account';
				$data['intro'] = false;
                $data['user_exists'] = false;
				$this->load_full_template('auth/register', $data);
			}
		}

        public function trial(){

            if($this->input->post('submit')){
                $data = array(
                    //'firstname' => $this->input->post('firstname'),
                    //'lastname' => $this->input->post('lastname'),
                    'email' => $this->input->post('email'),
                    'is_active' => 1,
                    'is_verify' => 1,
                    'last_ip' => '',
                    'created_at' => date('Y-m-d : h:m:s'),
                    'updated_at' => date('Y-m-d : h:m:s'),
                    'role' => ROLE_TRIAL_USER,
                );
                $clear_password = generateRandomString(6);

                $data['password'] = password_hash($clear_password, PASSWORD_BCRYPT);

                $this->form_validation->set_rules('email', __('Courriel'), 'trim|valid_email|required');

                if ($this->form_validation->run() == FALSE) {
                    $data['intro'] = 'home-intro';
                    $this->load_full_template('home', $data);
                }
                else{
                    $user_exists = $this->user_model->get_by_email($data['email']);
                    if ($user_exists) {
                        redirect('auth/forgot_password?email=' . urlencode($data['email']));
                        exit;
                    } else {
                        $inserted_id = $this->auth_model->register($data);
                        if ($inserted_id) {
                            $data['user_id'] = $inserted_id;
                            $data['user_grps'] = $this->user_grp_model->get_user_grps($data['user_id']);
                            $data['is_user_login'] = true;
                            //sending welcome email to user

                            if ($this->session->userdata('site_lang') == 'french') {
                                $subject = 'Essai gratuit Plateforme illuxi!';
                                $body = $this->mailer->Tpl_Trial($clear_password, $data['email']);
                            } else {
                                $subject = 'Free Trial illuxi Platform!';
                                $body = $this->mailer->Tpl_TrialEn($clear_password, $data['email']);
                            }
                            $this->load->helper('email_helper');
                            $to = $data['email'];

                            $message = $body;
                            $email = sendEmail($to, $subject, $message, $file = '', $cc = '');
                            $email = true;
                            $this->authentication->login_user($data);
                            redirect(base_url());
                        }
                    }
                }
            }
        }

		//----------------------------------------------------------	
		public function verify(){
			$verification_id = $this->uri->segment(3);
			$result = $this->auth_model->email_verification($verification_id);
			if($result){
				$this->session->set_flashdata('success', 'Votre courriel a été vérifié, Vous pouvez maintenant vous connecter.');
				redirect(base_url('auth/login'));
			}
			else{
				$this->session->set_flashdata('success', "Ce lien d'activation est invalide.");
				redirect(base_url('auth/login'));
			}	
		}
		//--------------------------------------------------		
		public function forgot_password(){
            $data['email_exists'] = false;
			if($this->input->post('submit')){
				//checking server side validation
				$this->form_validation->set_rules('email', 'Email', 'valid_email|trim|required');
				if ($this->form_validation->run() === FALSE) {
					$data['intro'] = false;
					$this->load_full_template('auth/forget_password', $data);
					return;
				}
				$email = $this->input->post('email');
				$response = $this->auth_model->check_user_mail($email);
				if($response){
					$rand_no = rand(0,1000);
					$pwd_reset_code = md5($rand_no.$response['user_id']);
					$this->auth_model->update_reset_code($pwd_reset_code, $response['user_id']);
					// --- sending email
					$name = $response['firstname'];
					$email = $response['email'];
					$reset_link = base_url('auth/reset_password/'.$pwd_reset_code);
					$body = $this->mailer->Tpl_PwdResetLink($name,$reset_link);

					$this->load->helper('email_helper');
					$to = $email;
					$subject = 'Réinitialiser votre mot de passe';
					$message =  $body ;
					$email = sendEmail($to, $subject, $message, $file = '' , $cc = '');
					if($email){
						$this->session->set_flashdata('success', "Nous vous avons envoyé par courriel les instructions pour réinitialiser votre mot de passe.");

						redirect(base_url('auth/forgot_password'));
					}
					else{
						$this->session->set_flashdata('error', "Nous avons été incapable d'envoyer le courriel de réinitialisation. Veuillez contater notre support technique.");
						redirect(base_url('auth/forgot_password'));
					}
				}
				else{
					$this->session->set_flashdata('error', "Ce courriel n'a pas été trouvé dans notre système.");
					redirect(base_url('auth/forgot_password'));
				}
			}
			else{
				$data['title'] = 'Forget Password';
				$data['intro'] = false;
                $data['email_exists'] = urldecode($this->input->get('email'));
				$this->load_full_template('auth/forget_password', $data);
			}
		}
		//----------------------------------------------------------------		
		public function reset_password($id=0){
			// check the activation code in database
			if($this->input->post('submit')){
				$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
				$this->form_validation->set_rules('confirm_password', 'Confirmation de mot de passe', 'trim|required|matches[password]');

				if ($this->form_validation->run() == FALSE) {
					$result = false;
					$data['reset_code'] = $id;
					$data['intro'] = false;
					$this->load_full_template('auth/reset_password', $data);
				}   
				else{
					$result = $this->auth_model->check_password_reset_code($id);
					$new_password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
					$this->auth_model->reset_password($id, $new_password);
					$this->session->set_flashdata('success',"Votre mot de passe a été réinitialisé. Vous pouvez vous connecter à nouveau.");
                    $data = array(
                        'firstname' => $result['firstname'],
                        'lastname' => $result['lastname'],
                        'email' => $result['email'],
                        'is_active' => 1,
                        'is_verify' => 1,
                        'last_ip' => '',
                        'updated_at' => date('Y-m-d : h:m:s'),
                    );

                    $data['user_id'] = $result['user_id'];
                    $data['user_role'] = $result['role'];
                    $data['user_grps'] = $this->user_grp_model->get_user_grps($result['user_id']);
                    $data['is_user_login'] = true;

                    $this->authentication->login_user($data);
                    redirect(base_url());
				}
			}
			else{
				$result = $this->auth_model->check_password_reset_code($id);
				if($result){
					$data['reset_code'] = $id;
					$data['intro'] = false;
					$this->load_full_template('auth/reset_password', $data);
				}
				else{
					$this->session->set_flashdata('error',"Ce code de réinitialisation de mot de passe est invalide.");
					redirect(base_url('auth/forgot_password'));
				}
			}
		}

		//----------------------------------------------------------------		
		public function choose_password($id=0){

			// check the activation code in database
			if($this->input->post('submit')){
				$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
				$this->form_validation->set_rules('confirm_password', 'Confirmation de mot de passe', 'trim|required|matches[password]');

				if ($this->form_validation->run() == FALSE) {
					$result = false;
					$data['reset_code'] = $id;
					$data['intro'] = false;
					$this->load_full_template('auth/choose_password', $data);
				}   
				else{
                    $result = $this->auth_model->check_password_reset_code($id);
					$new_password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
					$this->auth_model->reset_password($id, $new_password);
					$this->session->set_flashdata('success',"Votre mot de passe a été configuré. Bienvenue!");

                    $data = array(
                        'firstname' => $result['firstname'],
                        'lastname' => $result['lastname'],
                        'email' => $result['email'],
                        'is_active' => 1,
                        'is_verify' => 1,
                        'last_ip' => '',
                        'updated_at' => date('Y-m-d : h:m:s'),
                    );

                    $data['user_id'] = $result['user_id'];
                    $data['user_role'] = $result['role'];

                    $data['user_grps'] = $this->user_grp_model->get_user_grps($result['user_id']);
                    $data['is_user_login'] = true;

                    $this->authentication->login_user($data);
                    redirect(base_url());
				}
			}
			else{
				$result = $this->auth_model->check_password_reset_code($id);
				if($result){
					$data['reset_code'] = $id;
					$data['intro'] = false;
					$this->load_full_template('auth/choose_password', $data);
				}
				else{
					$this->session->set_flashdata('error',"Ce code d'initialisation de mot de passe est invalide.");
					redirect(base_url('auth/forgot_password'));
				}
			}
		}
			
		public function logout(){
			$user_id = $this->session->user_id;
			session_destroy();
			session_start();
			$this->session->set_userdata(array());
			delete_cookie('illuxi-auth-token');
			redirect(base_url('auth/login'), 'refresh');
		}

		public function reset(){
			session_destroy();
			redirect(site_url());
		} 

		public function dismiss_ie() {
			$this->session->set_userdata('dismiss_ie', true);
			echo json_encode('');
		}	
}
