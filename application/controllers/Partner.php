<?php

class Partner extends MY_Controller {


    public function view() {

        $data['partner'] = $this->partner_model->get_by_key($this->uri->segment(2));

        $data['intro'] = false;

        $data['calculators'] = $this->calculator_model->get_by_patrner($data['partner']['partner_id']);

        $session_data = array(
            'partner' => $this->uri->segment(2)
        );
        $this->session->set_userdata($session_data);

        $this->load_full_template('partner', $data);

    }
}