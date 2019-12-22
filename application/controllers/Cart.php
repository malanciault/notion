<?php

class Cart extends MY_Controller {

    public function remove() {
        $calculation_id = $this->input->post('calculation_id');
        unset($_SESSION['calculations'][$calculation_id]);
        $data['totals'] = $this->calcs->calculate_totals();
        $result['cart_summary'] = $this->load->view('templates/cart_summary', $data, true);
        $result['calculations_count'] = count($_SESSION['calculations']);
        echo json_encode($result);
    }

    public function add() {
        $calculation_id = $this->input->post('calculation_id');
        $calculations = $this->session->calculations;
        $calculations[$calculation_id] = $this->session->temp_calculation;
        $this->session->set_userdata(array('calculations' => $calculations));
        $result = array(
            'calculation_id' => $calculation_id,
        );

        echo json_encode($result);
    }

    public function count() {
        if ($this->session->calculations && is_array($this->session->calculations))
            $result = count($this->session->calculations);
        else
            $result = 0;
        echo json_encode($result);
    }
}