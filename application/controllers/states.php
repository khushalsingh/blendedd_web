<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class States extends MY_Controller {

	public $public_methods = array('get_active_states_by_country_id');

	function __construct() {
		parent::__construct();
		$this->load->model('State_model');
	}

	function get_active_states_by_country_id($countries_id) {
		if (is_numeric($countries_id)) {
			echo json_encode($this->State_model->get_active_states_by_country_id($countries_id));
			die;
		}
		echo json_encode(array());
	}

}
