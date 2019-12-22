<?php

//namespace Listener;

class Paypal extends MY_Controller {
    
    private $post_data = false; 

    public function index() {
        $this->post_data = var_export($_POST, true);
        $this->event_model->log('ipn call', $post_data);
        // Use the sandbox endpoint during testing.
        $this->load->library('paypalipn');

        //$this->paypalipn->useSandbox();
        $this->event_model->log('ipn_call', '');
        $verified = $this->paypalipn->verifyIPN();

        $this->event_model->log('verifyIPN', '');
        if ($verified) {
            $this->event_model->log('verified', '');
            /*
             * Process IPN
             * A list of variables is available here:
             * https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
             */

            if (strtolower($this->input->post('payment_status')) == 'completed')  {
                $item_name_array = explode('#', $this->input->post('item_name'));
                $this->order_completed($item_name_array[1]);            
            } else {
                $this->event_model->log('payment_status <> completed', $post_data);
            }

            //stripe
            /*
            $this->event_model->log('checkout.session.completed', '');

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
            */
        } else {
            $this->event_model->log('not verified', '');
            $this->event_model->log('POST', var_export($_POST, true));
        }
        // Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
        header("HTTP/1.1 200 OK");

    }

    public function order_completed ($order_id = false) {
        
        if($order_id OR ($this->session->is_admin_login && $order_id = $this->uri->segment(3))){
        
            $order = $this->order_model->get($order_id);
            
            $order_data = array(
                'order_status' => 'paid',
                'order_txn_id' => $this->input->post('txn_id'),
                'order_payer_id' => $this->input->post('payer_id'),
                'order_data' => $this->post_data,
            );
            $order = $this->order_model->update($order['order_id'], $order_data, true);
            
            $this->load->library('mailer');
            $name = $order['firstname'];
            $to = $order['email'];
            $link['certificate'] = site_url('certificate/' . $order['order_hash'] . '?lang=' . $order['order_language_id']);
            $link['share'] = site_url('certificate/share/' . $order['order_hash'] . '?lang=' . $order['order_language_id']);
            if ($order['order_language_id'] === "french"){
                $subject = 'Votre certificat Planetair';
                $body = $this->mailer->Tpl_Certificate($name, $link);
            }
            else if($order['order_language_id'] === "english"){
                $subject = 'Your Planetair certificate';
                $body = $this->mailer->Tpl_Certificate_en($name, $link);    
            }
            $this->load->helper('email_helper');

            $message = $body;
            $email = sendEmail($to, $subject, $message, $file = '', $cc = '');
        }
    }


}


/**
array (
'mc_gross' => '63.45',
'protection_eligibility' => 'Eligible',
'address_status' => 'confirmed',
'payer_id' => '37FCHHP2Y5UAU',
'address_street' => 'jh
kjh',
'payment_date' => '13:54:53 Sep 04, 2019 PDT',
'payment_status' => 'Completed',
'charset' => 'windows-1252',
'address_zip' => 'h1h 1h1',
'first_name' => 'Marc',
'mc_fee' => '2.14',
'address_country_code' => 'CA',
'address_name' => 'Marc Marc',
'notify_version' => '3.9',
'custom' => '',
'payer_status' => 'unverified',
'business' => 'sb-5b2cg115562@business.example.com',
'address_country' => 'Canada',
'address_city' => 'kjh',
'quantity' => '1',
'verify_sign' => 'AD.45wUUDZnv8rLO4SIWwCFEqJjmAUsTdoVry6bysa7Xk-t5Q8eUNpE9',
'payer_email' => 'malanciault@amplionumerique.com',
'txn_id' => '6GV73173HV308340X',
'payment_type' => 'instant',
'last_name' => 'Marc',
'address_state' => 'AB',
'receiver_email' => 'sb-5b2cg115562@business.example.com',
'payment_fee' => '',
'shipping_discount' => '0.00',
'insurance_amount' => '0.00',
'receiver_id' => 'G233SX4K4LN3Y',
'txn_type' => 'express_checkout',
'item_name' => 'Compensation CO2 Planetair #60',
'discount' => '0.00',
'mc_currency' => 'CAD',
'item_number' => '',
'residence_country' => 'CA',
'test_ipn' => '1',
'receipt_id' => '0755-6288-7146-4983',
'shipping_method' => 'Default',
'transaction_subject' => 'Compensation CO2 Planetair #60',
'payment_gross' => '',
'ipn_track_id' => '4686e51029414',
)
 *
 *
 */