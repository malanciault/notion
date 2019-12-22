<?php
class Mixpanel {

    public $token;
    public $identity = false;
    public $people = array();
    public $events = array();
    private $data = array();
    private $api_secret = false;

    private $api_url = 'https://mixpanel.com/api';
    private $version = '2.0';

	public function __construct() {
		$this->CI =& get_instance();
        $this->token = $this->CI->config->item('mixpanel_' . ENVIRONMENT);
        $this->data = $this->CI->session->flashdata('mixpanel');
        $this->api_secret = $this->CI->config->item('mixpanel_api_secret');
	}

    public function identify($id) {
        $this->data['identity'] = $id;
        $this->CI->session->set_flashdata('mixpanel', $this->data);
    }

    public function track($event, $data=false) {
        $this->data['events'][] = array('name' => $event, 'data' => $data);
        $this->CI->session->set_flashdata('mixpanel', $this->data);
    }

    public function people_set($id, $data) {
        $this->data['people'] = $data;
        $this->CI->session->set_flashdata('mixpanel', $this->data);
    }

    public function alias($alias) {
        $this->data['alias'] = $alias;
        $this->CI->session->set_flashdata('mixpanel', $this->data);
    }

    public function request($methods, $params, $format='json') {
        // $end_point is an API end point such as events, properties, funnels, etc.
        // $method is an API method such as general, unique, average, etc.
        // $params is an associative array of parameters.
        // See http://mixpanel.com/api/docs/guides/api/

        $params['format'] = $format;
        
        $param_query = '';
        foreach ($params as $param => &$value) {
            if (is_array($value))
                $value = json_encode($value);
            $param_query .= '&' . urlencode($param) . '=' . urlencode($value);
        }
        
        $uri = '/' . $this->version . '/' . join('/', $methods) . '/';
        $request_url = $uri . '?' . $param_query;
        
        $headers = array("Authorization: Basic " . base64_encode($this->api_secret));
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->api_url . $request_url);
        curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl_handle);
        curl_close($curl_handle);
        
        return json_decode($data);
    }

}