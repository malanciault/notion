<?php

class Project extends MY_Controller {

    public function get() {
        $result = $this->project_model->get($this->input->post('project_id'));
        $result['project_price'] = format_dollar($result['project_price']);
        echo json_encode($result);
    }
}