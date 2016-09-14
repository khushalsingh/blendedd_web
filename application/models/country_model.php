<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Country_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	function get_all_active_countries() {
		return $this->db->get_where('countries', array('country_status' => '1'))->result_array();
	}

	function get_country_by_id($country_id) {
		return $this->db->get_where('countries', array('country_id' => $country_id))->row_array();
	}

}
