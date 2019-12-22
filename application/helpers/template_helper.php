<?php


// marcan says: not used. Should be removed. It was replace by MY_Controller::add_on_demand_ressource
function add_assets($pos, $params) {
	$ci = &get_instance();
	if (!is_array($params)) {
		$params = array($params);
	}
	$ci->config->set_item($pos, $params);
}

function header_assets($str = '') {
	$ci = &get_instance();
	return $ci->config->item('header');
}

function footer_assets($str = '') {
	$ci = &get_instance();
	return $ci->config->item('footer');
}