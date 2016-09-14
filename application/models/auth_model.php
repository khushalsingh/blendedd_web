<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function login($username) {
        $where = "(users.user_login = '" . $this->db->escape_str($username) . "' OR users.user_email = '" . $this->db->escape_str($username) . "') AND users.user_status = '1'";
        $this->db->join('groups', 'groups.group_id = users.groups_id');
        return $this->db->where($where)->get('users')->row_array();
    }

    function update_user_login($user_id) {
        return $this->db->where('user_id', $user_id)->update('users', array('user_last_logged_in' => date('Y-m-d H:i:s')));
    }

    function add_login_log($login_log_insert_array) {
        return $this->db->insert('login_logs', $login_log_insert_array);
    }

    function get_user_by_username_or_email($user_detail) {
        return $this->db->where('user_login', $user_detail)->or_where('user_email', $user_detail)->get('users')->row_array();
    }

    function get_user_details_by_user_security_hash($user_security_hash) {
        return $this->db->get_where('users', array('user_security_hash' => $user_security_hash))->row_array();
    }

    function get_user_by_id($user_id) {
        $this->db->join('groups', 'groups.group_id = users.groups_id', 'left');
        return $this->db->get_where('users', array('user_id' => $user_id))->row_array();
    }

    function edit_user_by_user_id($user_id, $user_details_array) {
        return $this->db->where('user_id', $user_id)->update('users', $user_details_array);
    }

    function update_user_details_by_user_security_hash($user_security_hash, $user_array) {
        return $this->db->where('user_security_hash', $user_security_hash)->update('users', $user_array);
    }

    function get_registration_details_by_user_security_hash($user_security_hash) {
        return $this->db->get_where('registrations', array('user_security_hash' => $user_security_hash))->row_array();
    }

    function create_account($user_array) {
        return $this->db->insert('users', $user_array);
    }

    function register($registration_insert_array) {
        if ($this->db->insert('registrations', $registration_insert_array)) {
            return $this->db->insert_id();
        }
        return 0;
    }

    function delete_registration_by_id($registration_id) {
        return $this->db->delete('registrations', array('user_id' => $registration_id));
    }

    function get_security_questions() {
        return $this->db->get('questions')->result_array();
    }

    function update_registration_by_id($registration_id, $registration_update_array) {
        return $this->db->where('user_id', $registration_id)->update('registrations', $registration_update_array);
    }

    function get_registration_by_user_braintree_merchant_id($user_braintree_merchant_id) {
        return $this->db->get_where('registrations', array('user_braintree_merchant_id' => $user_braintree_merchant_id))->row_array();
    }

    function get_user_by_user_braintree_merchant_id($user_braintree_merchant_id) {
        return $this->db->get_where('users', array('user_braintree_merchant_id' => $user_braintree_merchant_id))->row_array();
    }

    function get_user_by_credit_card($user_credit_card_number) {
        return $this->db->get_where('users', array('user_credit_card_number_md5' => md5($user_credit_card_number)))->row_array();
    }

    function get_registration_by_credit_card($user_credit_card_number) {
        return $this->db->get_where('registrations', array('user_credit_card_number_md5' => md5($user_credit_card_number)))->row_array();
    }

    function get_user_by_bank_name_and_bank_account_number_and_routing_code($user_bank_name, $user_bank_account_number, $user_bank_route_code) {
        return $this->db->get_where('users', array('user_bank_name' => $user_bank_name, 'user_bank_account_number' => $user_bank_account_number, 'user_bank_route_code' => $user_bank_route_code))->row_array();
    }

    function get_registration_by_bank_name_and_bank_account_number_and_routing_code($user_bank_name, $user_bank_account_number, $user_bank_route_code) {
        return $this->db->get_where('registrations', array('user_bank_name' => $user_bank_name, 'user_bank_account_number' => $user_bank_account_number, 'user_bank_route_code' => $user_bank_route_code))->row_array();
    }

}
