<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Group_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	function get_all_active_groups() {
		return $this->db->get_where('groups', array('group_status' => '1'))->result_array();
	}

}
