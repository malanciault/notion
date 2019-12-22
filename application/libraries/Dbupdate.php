<?php
class Dbupdate {

    private $db_current_version = 2;

    private $db_latest_version = 11;

    public function __construct() {
        $this->CI =& get_instance();

        if ($this->config_exists()) {
            $this->CI->load->model('config_model');
            $this->db_current_version = $this->CI->config_model->get_value_by_key('db_version');
        }
    }

    public function current() {
        return $this->db_current_version;
    }

    public function latest() {
        return $this->db_latest_version;
    }

    public function config_exists() {
        $query = $this->CI->db->query("
            SELECT * 
            FROM information_schema.tables
            WHERE table_schema = '" . $this->CI->db->database . "' 
                AND table_name = 'config'
            LIMIT 1;
            "
        );
        return $query->result_array();
    }

    public function upgrade() {
        $version = 2;
        if ($this->db_current_version < $version) {
            $query = $this->CI->db->query("
                CREATE TABLE `" . $this->CI->db->database . "`.`calculator` ( `calculator_id` INT NOT NULL AUTO_INCREMENT , `calculator_key` VARCHAR(255) NOT NULL , `calculator_category_id` INT(11) NOT NULL , PRIMARY KEY (`calculator_id`)) ENGINE = InnoDB;
               "
            );

            $query = $this->CI->db->query("
                CREATE TABLE `" . $this->CI->db->database . "`.`calculator_i18n` ( `calculator_i18n_id` INT NOT NULL AUTO_INCREMENT , `calculator_i18n_calculator_id` VARCHAR(255) NOT NULL , `calculator_category_id` INT(11) NOT NULL , PRIMARY KEY (`calculator_i18n_id`)) ENGINE = InnoDB;
               "
            );
            $this->CI->config_model->update_value('db_version', $version);
        }

        //------------------------------------------------------------------------------------//
        $version = 3;
        if ($this->db_current_version < $version) {
            $query = $this->CI->db->query("
                ALTER TABLE `order` ADD `order_language_id` VARCHAR(20) NOT NULL;
               "
            );
            $this->CI->config_model->update_value('db_version', $version);
        }
        //------------------------------------------------------------------------------------//

        $version = 4;
        if ($this->db_current_version < $version) {
            $query = $this->CI->db->query("
                ALTER TABLE `order` ADD `order_partner_id` INT(11) NOT NULL;
               "
            );
            $this->CI->config_model->update_value('db_version', $version);
        }
        //------------------------------------------------------------------------------------//

        $version = 5;
        if ($this->db_current_version < $version) {
            $query = $this->CI->db->query("
                ALTER TABLE `code` ADD `code_status` VARCHAR(50) NOT NULL DEFAULT 'active';
               "
            );
            $this->CI->config_model->update_value('db_version', $version);
        }
        //------------------------------------------------------------------------------------//

        $version = 6;
        if ($this->db_current_version < $version) {
            $query = $this->CI->db->query("
                ALTER TABLE `order` ADD `order_preset_id` INT(11) NOT NULL;
               "
            );

            $query = $this->CI->db->query("
                CREATE TABLE `" . $this->CI->db->database . "`.`preset` ( `preset_id` INT NOT NULL AUTO_INCREMENT , `preset_key` VARCHAR(255) NOT NULL , `preset_amount` FLOAT NOT NULL , `preset_co2` FLOAT NOT NULL , PRIMARY KEY (`preset_id`)) ENGINE = InnoDB;
               "
            );

            $query = $this->CI->db->query("
                CREATE TABLE `" . $this->CI->db->database . "`.`preset_i18n` ( `preset_i18n_id` INT NOT NULL AUTO_INCREMENT , `preset_i18n_preset_id` INT(11) NOT NULL , `preset_i18n_language_id` VARCHAR(20) NOT NULL , `preset_i18n_description` TEXT NULL , `preset_i18n_html` TEXT NULL , PRIMARY KEY (`preset_i18n_id`)) ENGINE = InnoDB;
               "
            );
            $this->CI->config_model->update_value('db_version', $version);
        }
        //------------------------------------------------------------------------------------//

        $version = 7;
        if ($this->db_current_version < $version) {
            $query = $this->CI->db->query("
                ALTER TABLE `preset` ADD `preset_status` VARCHAR(50) NOT NULL DEFAULT 'active';
               "
            );
            $this->CI->config_model->update_value('db_version', $version);
        }
        //------------------------------------------------------------------------------------//
        $version = 8;
        if ($this->db_current_version < $version) {
            $query = $this->CI->db->query("
                ALTER TABLE `calculation` ADD `calculation_preset_id` INT(11) NOT NULL;
               "
            );
            $this->CI->config_model->update_value('db_version', $version);
        }
        //------------------------------------------------------------------------------------//
        $version = 10;
        if ($this->db_current_version < $version) {
            $query = $this->CI->db->query("
                ALTER TABLE `preset` ADD `preset_options` TEXT NOT NULL;
               "
            );
            $this->CI->config_model->update_value('db_version', $version);
        }

        //------------------------------------------------------------------------------------//
        $version = 11;
        if ($this->db_current_version < $version) {
            $query = $this->CI->db->query("
                ALTER TABLE `order` ADD `order_conversion_triggered` INT(1) NOT NULL;
               "
            );
            $this->CI->config_model->update_value('db_version', $version);
        }

    }
}