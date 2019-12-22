<?php

class Airport extends MY_Controller {

    public function country() {
        $result = $this->airport_model->get_for_autocomplete($this->input->post('query'));

        echo json_encode($result);
    }
}