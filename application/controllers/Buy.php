<?php

class Buy extends MY_Controller {

    public function index() {
        $data['preset'] = false;
        $data['extra_param'] = false;
        $data['partner'] = false;
        $preset = false;
        $partner_key = false;

        if ($this->input->get('partner')) {
            $partner_key = $this->input->get('partner');
        } elseif($this->session->partner){
            $partner_key = $this->session->partner;
        }

        if ($partner_key) {
            $data['partner']= $this->partner_model->get_by_key($partner_key);
            $data['extra_param'] = '?partner=' . $partner_key;
        }

        $data['totals'] = $this->calcs->calculate_totals();

        if($this->input->post('submit')){

            $this->form_validation->set_rules('first_name', __('Prénom'), 'trim|required');
            $this->form_validation->set_rules('last_name', __('Nom'), 'trim|required');
            $this->form_validation->set_rules('email', __('Courriel'), 'trim|valid_email|required');
            $this->form_validation->set_rules('valid_email', __('Confirmer le courriel'), 'trim|matches[email]');
            $this->form_validation->set_rules('certificate_text', __('Intitulé du certificat'), 'trim|required');

            if ($this->input->post('for_organisation') == 'on') {
                $this->form_validation->set_rules('organisation', __('Nom de l\'organisme'), 'trim|required');
            }

            if ($this->input->post('have_code') == 'on') {
                $this->form_validation->set_rules('code', __('Code promo'), 'trim|required|callback_code_check');
            }

            if ( $this->form_validation->run() == FALSE) {
                $data['intro'] = false;
                $data['calculators'] = $this->calculator_model->get_all();
                if ($this->input->post('preset_id')) {
                    $this->session->set_userdata(array('preset_shown' => true));
                    $data['preset'] = $this->preset_model->get($this->session->preset_id);
                    $template_path = FCPATH . 'application/views/';
                    $template_filename = 'preset/' . $this->i18n->current() . '/' . $data['preset']['preset_key'] . '.php';
                    if (file_exists($template_path  . $template_filename)) {
                        $data['preset']['preset_i18n_html'] = $this->load->view($template_filename, $data, true);
                    }
                }
                $this->load_full_template('buy', $data);
            } else {
                $data['intro'] = false;
                $email = $this->input->post('email');
                if (!$user = $this->user_model->get_by_email($email)) {
                    $user = array(
                        'email' => $this->input->post('email'),
                        'firstname' => $this->input->post('first_name'),
                        'lastname' => $this->input->post('last_name'),
                    );
                    $user_id = $this->user_model->add_user($user);
                    $user = $this->user_model->get_user_by_id($user_id);
                }

                $code = $this->code_model->get_by_key($this->input->post('code'), true);

                $order_data = array(
                    'order_date' => now_str(),
                    'order_status' => 'new',
                    'order_user_id' => $user['user_id'],
                    'order_co2' => $data['totals']['total_co2'],
                    'order_text' => $this->input->post('certificate_text'),
                    'order_code_id' => $code['code_id'],
                );

                if ($partner_key) {
                    if ($partner = $this->partner_model->get_by_key($partner_key)) {
                        $order_data['order_partner_id'] = $partner['partner_id'];
                    }
                }

                if ($preset_id = $this->input->post('preset_id')) {
                    $preset = $this->preset_model->get($preset_id);
                    $order_data['order_preset_id'] = $preset_id;
                }

                $data['order_id'] = $this->order_model->insert($order_data);
                $session_data = array(
                    'order_id' => $data['order_id']
                );
                $this->session->set_userdata($session_data);
                $data['customer_email'] = $this->input->post('email');

                foreach ($this->session->calculations as $calculation) {
                    $calculation_array = array(
                        'calculation_order_id' => $data['order_id'],
                    );
                    $this->calculation_model->update($calculation['calculation_id'], $calculation_array);
                }
                if ($preset && get_array_value($preset['preset_options'], 'no_project_select')) {
                    $this->do_select_project($data['order_id']);
                } else {
                    $data['projects'] = $this->project_model->get_all(false, 'project_order');
                    $this->load_full_template('buy-project', $data);
                }
            }
        } else {
            $data['intro'] = false;
            $project_planetair = $this->project_model->get(1);
            $data['calculators'] = $this->calculator_model->get_all();
            if ($this->session->preset_id && !$this->session->preset_shown) {
                $this->session->set_userdata(array('preset_shown' => true));
                $data['preset'] = $this->preset_model->get($this->session->preset_id);
                $template_path = FCPATH . 'application/views/';
                $template_filename = 'preset/' . $this->i18n->current() . '/' . $data['preset']['preset_key'] . '.php';
                if (file_exists($template_path  . $template_filename)) {
                    $data['preset']['preset_i18n_html'] = $this->load->view($template_filename, $data, true);
                }
            }
            $this->load_full_template('buy', $data);
        }
    }

    public function code_check($str) {
        if ($str == 'test') {
            $this->form_validation->set_message('code_check', __("Le code promo que vous avez entré est invalide."));
            return false;
        } elseif(!$this->code_model->get_by_key($str)) {
            $this->form_validation->set_message('code_check', __("Le code promo que vous avez entré est invalide."));
            return false;
        } else {
            return true;
        }
    }

    public function select() {
        $data['intro'] = false;
        $data['project'] = $this->project_model->get($this->input->post('project_id'));
        $data['order'] = $this->order_model->get($this->input->post('order_id'));
        $data['code'] = $this->code_model->get($data['order']['order_code_id']);

        $order_data = array(
            'order_project_id' => $data['project']['project_id'],
            'order_status' => 'project_chose',
            'order_total' => round($data['order']['order_co2'] * $data['project']['project_price'], 2),
            'order_hash' => hash('sha256', $data['order']['order_id'] . $data['order']['order_date']),
            'order_language_id' => $this->i18n->current()
        );

        if ($data['code']) {
            $order_data['order_subtotal'] = $order_data['order_total'];
            if ($data['code']['code_type'] == 'amount') {
                $order_data['order_total'] = $order_data['order_subtotal'] - $data['code']['code_value'];
                if ($order_data['order_total'] < 0) {
                    $order_data['order_total'] = 0;
                }
            } elseif($data['code']['code_type'] == 'percent') {
                $order_data['order_total'] = round(($order_data['order_subtotal'] - ($order_data['order_subtotal'] * ($data['code']['code_value'] * 0.01))), 2);
                if ($order_data['order_total'] < 0) {
                    $order_data['order_total'] = 0;
                }
            }
        }

        $data['order'] = $this->order_model->update($this->input->post('order_id'), $order_data, true);
        $data['total_co2'] = $data['order']['order_co2'];
        $data['total_cost'] = $data['order']['order_total'];
        $data['extra_param'] = false;
        $data['partner'] = false;
        if ($partner_key = $this->input->get('partner')) {
            $data['partner']= $this->partner_model->get_by_key($partner_key);
            $data['extra_param'] = '?partner=' . $partner_key;
        }
        $this->load_full_template('buy-select', $data);
    }

    private function do_select_project($order_id) {
        $data['intro'] = false;
        $data['project'] = $this->project_model->get_project_planetair();

        $data['order'] = $this->order_model->get($order_id);
        $data['code'] = $this->code_model->get($data['order']['order_code_id']);

        $order_data = array(
            'order_project_id' => $data['project']['project_id'],
            'order_status' => 'project_chose',
            'order_total' => round($data['order']['order_co2'] * $data['project']['project_price'], 2),
            'order_hash' => hash('sha256', $data['order']['order_id'] . $data['order']['order_date']),
            'order_language_id' => $this->i18n->current()
        );

        if ($data['code']) {
            $order_data['order_subtotal'] = $order_data['order_total'];
            if ($data['code']['code_type'] == 'amount') {
                $order_data['order_total'] = $order_data['order_subtotal'] - $data['code']['code_value'];
                if ($order_data['order_total'] < 0) {
                    $order_data['order_total'] = 0;
                }
            } elseif($data['code']['code_type'] == 'percent') {
                $order_data['order_total'] = round(($order_data['order_subtotal'] - ($order_data['order_subtotal'] * ($data['code']['code_value'] * 0.01))), 2);
                if ($order_data['order_total'] < 0) {
                    $order_data['order_total'] = 0;
                }
            }
        }

        $data['order'] = $this->order_model->update($this->input->post('order_id'), $order_data, true);
        $data['total_co2'] = $data['order']['order_co2'];
        $data['total_cost'] = $data['order']['order_total'];
        $data['extra_param'] = false;
        $data['partner'] = false;
        if ($partner_key = $this->input->get('partner')) {
            $data['partner']= $this->partner_model->get_by_key($partner_key);
            $data['extra_param'] = '?partner=' . $partner_key;
        }
        $this->load_full_template('buy-select', $data);
    }


    public function test() {
        $this->load->library('mailer');
        $subject = 'Votre certificat Planetair';
        $name = "Marc-André";
        $link = "https://planetair-app.amplionumerique.com/certificate/960ee3c2df3c6317bb0dbcfb86416b1c4246493a8c27eef07443de8c08440044";
        $body = $this->mailer->Tpl_Certificate($name, $link);
        $this->load->helper('email_helper');
        $to = 'malanciault@amplionumerique.com';

        $message = $body;
        $email = sendEmail($to, $subject, $message, $file = '', $cc = '');
        xd('done');
    }

    public function endpoint() {

        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey('sk_test_2O4QREFdeZZ4gNTtsLF2yaTS');

        $this->event_model->log('entering endpoint', '');

        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = 'whsec_zvdbGYJsns6Zh8f3tR4xHvex6MH2NkU7';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        $this->event_model->log('trying', '');
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            $this->event_model->log('UnexpectedValueException', '');
            http_response_code(400); // PHP 5.4 or greater
            exit();
        } catch(\Stripe\Error\SignatureVerification $e) {
            // Invalid signature
            $this->event_model->log('SignatureVerification', '');
            http_response_code(400); // PHP 5.4 or greater
            exit();
        }

        // Handle the checkout.session.completed event
        if ($event->type == 'checkout.session.completed') {
            $this->event_model->log('checkout.session.completed', '');
            $session = $event->data->object;
            $name = $session->display_items[0]->custom->name;
            x("name = " . $name);
            $amount = $session->display_items[0]->amount;
            x("amount = " . $amount);
            $payment_intent_id = $session->payment_intent;
            x("payment_intent_id = " . $payment_intent_id);
            $customer_id = $session->customer;
            x("customer_id = " . $customer_id);
            //$customer = $this->retrieve_customer($customer_id);
            $name_array = explode("#", $name);
            $order_id = $name_array[1];
            x("order_id = " . $order_id);
            $order = $this->order_model->get($order_id);
            $order_data = array(
                'order_status' => 'paid',
                'order_payment_intent_id' => $payment_intent_id,
                'order_customer_id' => $customer_id,
            );
            $order = $this->order_model->update($order_id, $order_data, true);


            $log = var_export($session, true);

            $this->event_model->log('info session', $log);
            $this->event_model->log('info customer', $log2);
        }

        http_response_code(200); // PHP 5.4 or greater
    }

    public function retrieve_customer($string) {
        return \Stripe\Customer::retrieve($string);
    }

    public function validate() {
        $code_text = $this->input->post('code_text');
        if ($this->code_model->get_by_key($code_text, true))
            echo json_encode('valid');
        else
            echo json_encode('invalid');

    }

    public function preset($preset_key) {
        $preset = $this->preset_model->get_by_key($preset_key);
        if ($preset['preset_amount']) {
            $params = array(
                'dollars' => '20',
                'amount' => '0,8889',
                'calculator_id' => '12'
            );
            $calculator_id = 12;
            $params['factor'] = $this->factor_model->find($params['calculator_id'], 'Rapide');
            $params['co2'] = str_replace(',', '.', $params['amount']);
            $params['amount'] = $params['dollars'];
            $calculation_array = array(
                'calculation_calculator_id' => $params['calculator_id'],
                'calculation_factor_id' => $params['factor']['factor_id'],
                'calculation_amount' => isset($params['amount']) ? $params['amount'] : '',
                'calculation_co2' => $params['co2'],
                'calculation_date' => now_str(),
                'calculation_user_id' => 0,
                'calculation_preset_id' => $preset['preset_id'],
                'calculation_ip' => $this->input->ip_address(),
            );
            $calculation_id = $this->calculation_model->insert($calculation_array);
            $calculation_array = $this->calculation_model->get($calculation_id);
            $calculation_array['co2'] = $calculation_array['calculation_co2'];

            unset($_SESSION['calculations']);
            $calculations[$calculation_id] = $calculation_array;
            $this->session->set_userdata(array('calculations' => $calculations));
            $this->session->set_userdata(array('preset_id' => $preset['preset_id']));
            $this->session->set_userdata(array('preset_shown' => false));

            redirect('buy');

        } elseif($preset['preset_co2']) {

        }
    }
}