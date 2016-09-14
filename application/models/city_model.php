<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class City_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	function get_active_cities_by_state_id($state_id) {
		return $this->db->get_where('cities', array('states_id' => $state_id))->result_array();
	}

	function get_city_by_id($city_id) {
		return $this->db->get_where('cities', array('city_id' => $city_id))->row_array();
	}

	function get_all_active_cities() {
		return $this->db->get('cities')->result_array();
	}

	function get_city_details_by_id($city_id) {
		$this->db->join('states', 'states.state_id = cities.states_id');
		$this->db->join('countries', 'countries.country_id = states.countries_id');
		return $this->db->get_where('cities', array('city_id' => $city_id))->row_array();
	}
	
	function get_all_zip_codes(){
		return $this->db->get('zip_codes')->result_array();
	}

}
