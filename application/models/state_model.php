<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class State_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	function get_active_states_by_country_id($countries_id) {
		return $this->db->get_where('states', array('countries_id' => $countries_id, 'state_status !=' => '-1'))->result_array();
	}

	function get_state_by_id($state_id) {
		return $this->db->get_where('states', array('state_id' => $state_id))->row_array();
	}

	function get_all_active_states() {
		return $this->db->get('states')->result_array();
	}

}
