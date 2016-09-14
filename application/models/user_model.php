<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function get_user_row_by_user_id($user_id) {
        return $this->db->get_where('users', array('user_id' => $user_id))->row_array();
    }

    function edit_user_by_user_id($user_id, $user_details_array) {
        return $this->db->where('user_id', $user_id)->update('users', $user_details_array);
    }

    function get_registered_users_by_id($pre_reg_user_id) {
        $this->db->join('states', 'states.state_id = registrations.states_id', 'left');
        return $this->db->get_where('registrations', array('registration_id' => $pre_reg_user_id))->row_array();
    }

    function get_user_details_by_user_id($user_id) {
        $this->db->join('cities', 'cities.city_id = users.cities_id', 'left');
        $this->db->join('states', 'states.state_id = cities.states_id', 'left');
        $this->db->join('countries', 'countries.country_id = states.countries_id', 'left');
        return $this->db->get_where('users', array('user_id' => $user_id))->row_array();
    }

    function get_all_active_users() {
        return $this->db->query("SELECT user_id,CONCAT(user_first_name, ' ', user_last_name) AS user_full_name,
			CASE user_type
				WHEN 1 THEN 'Business'
				WHEN 2 THEN 'Individual'
				ELSE 'ADMIN'
			END AS user_type,
			user_email,user_primary_contact,user_address_line_1,user_address_line_2,state_name,city_name,user_zipcode,DATE_FORMAT(user_created, '%e %b %Y') AS user_created from users join cities on cities.city_id = users.cities_id join states on states.state_id = cities.states_id where user_status='1' GROUP BY `users`.`user_id`")->result_array();
    }

    function get_user_by_social_id($user_social_id, $social_network) {
        $this->db->join('cities', 'cities.city_id = users.cities_id', 'left');
        $this->db->join('states', 'states.state_id = cities.states_id', 'left');
        $this->db->join('countries', 'countries.country_id = states.countries_id', 'left');
        $this->db->join('groups', 'groups.group_id = users.groups_id');
        $this->db->where(array('user_' . $social_network . '_id' => $user_social_id, 'user_status' => '1'));
        return $this->db->get('users')->row_array();
    }

    function get_user_by_email($user_email) {
        $this->db->join('cities', 'cities.city_id = users.cities_id', 'left');
        $this->db->join('states', 'states.state_id = cities.states_id', 'left');
        $this->db->join('countries', 'countries.country_id = states.countries_id', 'left');
        $this->db->join('groups', 'groups.group_id = users.groups_id');
        $this->db->where(array('user_email' => $user_email, 'user_status' => '1'));
        return $this->db->get('users')->row_array();
    }

    function get_active_user_by_id_and_security_hash($user_id, $user_security_hash) {
        $this->db->join('groups', 'groups.group_id = users.groups_id');
        $this->db->join('cities', 'cities.city_id = users.cities_id', 'left');
        $this->db->join('states', 'states.state_id = cities.states_id', 'left');
        $this->db->join('countries', 'countries.country_id = states.countries_id', 'left');
        $this->db->where(array('user_id' => $user_id, 'user_security_hash' => $user_security_hash, 'user_status' => '1'));
        return $this->db->get('users')->row_array();
    }

    function get_registration_by_id_and_security_hash($user_id, $user_security_hash) {
        $this->db->join('groups', 'groups.group_id = registrations.groups_id');
        $this->db->join('cities', 'cities.city_id = registrations.cities_id', 'left');
        $this->db->join('states', 'states.state_id = cities.states_id', 'left');
        $this->db->join('countries', 'countries.country_id = states.countries_id', 'left');
        $this->db->where(array('user_id' => $user_id, 'user_security_hash' => $user_security_hash));
        return $this->db->get('registrations')->row_array();
    }

    function insert($user_insert_array) {
        if ($this->db->insert('users', $user_insert_array)) {
            return $this->db->insert_id();
        }
        return 0;
    }

}
