<?php

use Malanciault\Threelci\Core\Threel_model;

class Config_model extends Threel_model {
    
    public function get_value_by_key($key) {
        $result = $this->get_by_key($key);
        if ($result) {
            return $result['config_value'];
        } else 
            return false;
    }

    public function get_by_key($key) {
         $where = array(
            'config_key' => $key,
        );
        return $this->row_array($where, false);
    }

    public function update_value($key, $value) {
        $config = $this->get_by_key($key);
        if ($config) {
            $data = array(
                'config_value' => $value
            );
            $this->config_model->update($config['config_id'], $data);  
        }
    }
}