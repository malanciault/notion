<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function sendEmail($to = '', $subject  = '', $body = '', $attachment = '', $cc = '', $bcc = '') {
    $controller =& get_instance();

    $controller->load->helper('path');

    // Configure email library
    $controller->load->library('email');
    $controller->email->initialize(array(
          'protocol'  => 'smtp',
          'mailtype'  => 'html',
          'charset'   => 'utf-8',
          'wordwrap'  => TRUE,
          'smtp_host' => 'smtp.sendgrid.net',
          'smtp_user' => $controller->config->item('sendgrid_username'),
          'smtp_pass' => $controller->config->item('sendgrid_password'),
          'smtp_port' => 587,
          'crlf' => "\r\n",
          'newline' => "\r\n"
        ));

    $controller->email->from( 'info@planetair.ca' , 'Planetair' );

    $controller->email->to($to);

    $controller->email->subject($subject);

    $controller->email->message($body);

    if($cc != '')
    {
        $controller->email->cc($cc);
    }

    if($bcc != '')
    {
        $controller->email->bcc($bcc);
    }

    if($attachment != '')
    {
        $controller->email->attach(base_url()."uploads/invoices/" .$attachment);

    }

    if($controller->email->send()){
        return "success";
    }
    else
    {
        return "error";
    }
}