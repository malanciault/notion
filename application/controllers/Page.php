<?php

class Page extends MY_Controller {

	public function view() {
		$data['page'] = $this->page_model->get_by_slug($this->uri->segment(2));
		$data['intro'] = false;
        $data['page_title'] = $data['page']['page_i18n_meta_title'];
        $data['page_description'] = $data['page']['page_i18n_meta_description'];
        $data['page_image'] = $data['page']['page_i18n_meta_image'];
		if (!$data['page'])
			show_404();
		
		$this->load_full_template('page-view', $data);
	}
}