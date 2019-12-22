<?php
require('UploadHandler.php');

class BetterselfUploadHandler extends UploadHandler {

	public function __construct() {
        parent::__construct(array(
 		   'user_dirs' => true
		));
    }

    protected function get_user_id() {
        $ci =& get_instance();
        return $ci->get_upload_folder();
	}
}