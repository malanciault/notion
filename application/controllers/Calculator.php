<?php
class Calculator extends MY_Controller {

	public function index() {
	    if ($this->session->partner) {
	        redirect(site_url('partner/' . $this->session->partner));
        }

	    $data['intro'] = false;
        $data['cols_calculators'] = $this->get_cols_to_use(4);

        $where = array(
            'calculator_partner' => 0
        );
        $data['calculators'] = $this->calculator_model->get_all($where, 'calculator_order');


        $data['action'] = 'compensate';
        if ($this->input->get('reset')) {
            unset($_SESSION['calculations']);
        }
		$this->load_full_template('calculator-index', $data);
	}

    public function calculate() {
        if ($this->session->partner) {
            redirect(site_url('partner/' . $this->session->partner));
        }
	    $data['intro'] = false;
        $data['cols_calculators'] = $this->get_cols_to_use(4);

        $where = array(
            'calculator_partner' => 0
        );
        $data['calculators'] = $this->calculator_model->get_all($where, 'calculator_order');

        $data['action'] = 'calculate';
        if ($this->input->get('reset')) {
            unset($_SESSION['calculations']);
        }
        $this->load_full_template('calculator-index', $data);
    }

    public function view() {
        $data['partner'] = false;
        $data['extra_param'] = '';
        if ($partner_key = $this->input->get('partner')) {
            $data['partner']= $this->partner_model->get_by_key($partner_key);
            $data['extra_param'] = '?partner=' . $partner_key;
        }
        if($this->input->post('submit')){

            if (!$calculation = $this->calcs->calculate($this->input->post())) {
                $data['calculator'] = $this->calculator_model->get_by_key($this->uri->segment(2));
                $data['intro'] = false;
                $data['calculation'] = $this->session->flashdata('calculation');

                if (!$data['calculator'])
                    show_not_found();
                $this->add_on_demand_ressource(site_url('assets/mdb/js/addons-pro/stepper.min.js'), 'footer');
                $this->add_on_demand_ressource('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js', 'footer');

                if ($data['calculator']['calculator_custom']) {
                    $view = 'calculators/calculator-view-' . $data['calculator']['calculator_key'];
                } else {
                    $data['factor'] = $this->factor_model->get_factor($data['calculator']['calculator_id']);
                    $view = 'calculators/calculator-view';
                }
                $this->load_full_template($view, $data);
            } else {

                $calculation_array = array(
                    'calculation_calculator_id' => $calculation['calculator_id'],
                    'calculation_factor_id' => $calculation['factor']['factor_id'],
                    'calculation_amount' => isset($calculation['amount']) ? $calculation['amount'] : '',
                    'calculation_co2' => $calculation['co2'],
                    'calculation_date' => now_str(),
                    'calculation_user_id' => 0,
                    'calculation_ip' => $this->input->ip_address(),
                );
                $calculation_id = $this->calculation_model->insert($calculation_array);
                $calculation_array['calculation_id'] = $calculation_id;
                $calculator = $this->calculator_model->get($calculation_array['calculation_calculator_id']);
                $calculation['calculator_i18n_name'] = $calculator['calculator_i18n_name'];
                $calculation['calculator_icon'] = $calculator['calculator_icon'];
                $calculation['calculation_id'] = $calculation_id;

                foreach ($calculation as $k => $v) {
                    if (strpos($k, 'option_') !== false) {

                        $option_array = array(
                            'calculation_option_calculation_id' => $calculation_id,
                            'calculation_option_key' => str_replace('option_', '', $k),
                            'calculation_option_value' => $v,
                        );
                        $this->calculation_option_model->insert($option_array);
                    }
                }

                $calculations = $this->session->calculations;
                $calculations[$calculation_id] = $calculation;

                //$this->session->set_userdata(array('calculations' => $calculations));
                $this->session->set_userdata(array('temp_calculation' => $calculation));

                $this->session->set_flashdata('calculation_id', $calculation_id);
                $this->session->set_flashdata('calculation', $calculation);

                redirect(site_url('calculator/' . $calculator['calculator_key'] . $data['extra_param']));
            }
        } else {
            $data['calculator'] = $this->calculator_model->get_by_key($this->uri->segment(2));
            $data['intro'] = false;
            $data['calculation'] = $this->session->flashdata('calculation');

            if (!$data['calculator'])
                show_not_found();
            $this->add_on_demand_ressource(site_url('assets/mdb/js/addons-pro/stepper.min.js'), 'footer');
            $this->add_on_demand_ressource('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js', 'footer');


            if ($data['calculator']['calculator_custom']) {
                $view = 'calculators/calculator-view-' . $data['calculator']['calculator_key'];
            } else {
                $data['factor'] = $this->factor_model->get_factor($data['calculator']['calculator_id']);
                $view = 'calculators/calculator-view';
            }
            $this->load_full_template($view, $data);
        }
    }

    public function transform() {
        $ret['element_id'] = $this->input->post('element_id');
        $element_value = $this->input->post('element_value');
        $project_planetair = $this->project_model->get(1);

        if ($ret['element_id'] == 'dollars') {
            $ret['element_value'] = number_format(round($element_value / $project_planetair['project_price'], 4 ), 4, $this->i18n->decimal(), '');
        } elseif ($ret['element_id'] == 'amount-show') {
            $ret['element_value'] = number_format(round($element_value * $project_planetair['project_price'], 2), 2, $this->i18n->decimal(), '');
        }

        echo json_encode($ret);
    }

    public function rapide_check($str) {
        $this->form_validation->set_message('rapide_check', __("Vous devez saisir un montant en dollar ou en tonnes."));
        return false;
    }
    public function rapide_amount_check($str) {
        if ($str && !is_numeric(str_replace(',', '.', $str))) {
            $this->form_validation->set_message('rapide_amount_check', __("Le montant en dollar n'est pas valide."));
            return false;
        }

        return true;
    }

    public function rapide_check_empty($str){
        if(!$str){
            $this->form_validation->set_message('rapide_check_empty', __("Tous les champs doivent être rempli"));
            return false;
        }
    } 
    
    public function reset() {
        session_destroy();
        redirect(site_url());
    }
    public function rapide_dollars_check($str) {
        if ($str && !is_numeric(str_replace(',', '.', $str))) {
            $this->form_validation->set_message('rapide_dollars_check', __("Le montant en tonne n'est pas valide."));
            return false;
        }
    }

    public function valid_airport($str) {
        if(!$this->airport_model->get_airport($str)){
            $this->form_validation->set_message('valid_airport', __("Veuillez sélectionner un aéroport de liste."));
            return false;
        }
        
        return true;
    }

}