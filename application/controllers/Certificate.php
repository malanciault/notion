<?php

class Certificate extends MY_Controller {

    public function view() {
        $data['order'] = $this->order_model->get_by_hash($this->uri->segment(2));
        if (!$data['order'])
            show_error('Désolé, le certificat est introuvable.', 500, 'Une erreur est survenue');

        $data['trigger_conversion'] = false;
        $data['intro'] = false;
        $data['certificate_url'] = urlencode(site_url('certificate/share/' . $data['order']['order_hash'] . '?lang=' . $this->i18n->current()));

        $data['partner'] = false;
        if ($partner_key = $this->input->get('partner')) {
            $data['partner']= $this->partner_model->get_by_key($partner_key);
            $data['extra_param'] = '?partner=' . $partner_key;
        }
        $data['step'] = $this->input->get('step');

        // has the facebook conversion been triggered ?

        if (!$data['order']['order_conversion_triggered'] && $data['order']['order_id'] > 430) {
            $data['trigger_conversion'] = true;
            $update_order = array(
                'order_conversion_triggered' => 1
            );
            unset($_SESSION['calculations']);
            $this->order_model->update($data['order']['order_id'], $update_order);
        }
        $this->load_full_template('certificate', $data);
    }


    public function share() {

        $data['order'] = $this->order_model->get_by_hash($this->uri->segment(3));

        if (!$data['order'])
            show_error('Désolé, le certificat est introuvable.', 500, 'Une erreur est survenue');
        $data['intro'] = false;
        $data['certificate_url'] = urlencode(current_url());
        $data['page_title_no_app_name'] = true;
        $data['page_title'] = $data['order']['firstname'] . " " . $data['order']['lastname'] . " " . __("a compensé") . " " . format_decimal($data['order']['order_co2']) . " " . __("tonnes d'émissions de gaz à effet de serre sur Planetair.ca");
        $this->load_full_template('certificate-share', $data);
    }
}