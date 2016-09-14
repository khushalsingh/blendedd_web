<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Cities extends MY_Controller {

	public $public_methods = array('get_active_cities_by_state_id');

	function __construct() {
		parent::__construct();
		$this->load->model('City_model');
	}

	function get_active_cities_by_state_id($states_id) {
		if (is_numeric($states_id)) {
			echo json_encode($this->City_model->get_active_cities_by_state_id($states_id));
			die;
		}
		echo json_encode(array());
	}

}
