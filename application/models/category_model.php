<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Category_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	function get_all_active_categories() {
		return $this->db->order_by('category_order', 'asc')->get_where('categories', array('category_status' => '1'))->result_array();
	}

	function get_all_active_categories_alphabetical() {
		return $this->db->order_by('category_name', 'asc')->get_where('categories', array('category_status' => '1'))->result_array();
	}

	function get_category_by_slug($category_slug) {
		return $this->db->get_where('categories', array('category_slug' => $category_slug))->row_array();
	}

	function get_category_type_by_id($category_id) {
		return $this->db->get_where('categories', array('category_id' => $category_id))->row_array();
	}

}
