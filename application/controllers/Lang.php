<?php

class Lang extends MY_Controller {

	public function switch() {
       $this->i18n->switch($this->uri->segment(2));
   }	
}