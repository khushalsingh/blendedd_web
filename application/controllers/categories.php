<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Categories extends MY_Controller {

	public $public_methods = array('index');

	function __construct() {
		parent::__construct();
		$this->load->model('Category_model');
	}

	function index() {
        parent::_create_cache();
		$data = array();
		$data['categories'] = $this->Category_model->get_all_active_categories();
		$this->render_view($data);
	}

	function datatable() {
		parent::allow(array('administrator', 'manager'));
		$this->load->library('Datatables');
		$this->datatables->join('brands', 'brands.brand_id = categories.brands_id');
		$this->datatables->select('category_id,category_name,category_alias,brand_name,category_status', FALSE)->from('categories');
		echo $this->datatables->generate();
	}

	function add() {
		parent::allow(array('administrator', 'manager'));
		$data = array();
		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', '<br/>');
			$this->form_validation->set_rules('brands_id', 'Brand', 'trim|required');
			$this->form_validation->set_rules('category_name[]', 'Category Name', 'required|callback__check_unique_category_name');
			$this->form_validation->set_rules('category_alias[]', 'Category Alias', 'trim|callback__check_unique_category_alias');
			if ($this->form_validation->run()) {
				$category_batch_insert_array = array();
				$category_alias_array = $this->input->post('category_alias');
				foreach ($this->input->post('category_name') as $key => $category_name) {
					$category_batch_insert_array[] = array(
						'brands_id' => $this->input->post('brands_id'),
						'category_name' => $category_name,
						'category_slug' => url_title($category_name, '-', TRUE),
						'category_alias' => $category_alias_array[$key],
						'category_status' => '1',
						'category_created' => date('Y-m-d H:i:s')
					);
				}
				if ($this->Category_model->batch_add_categories($category_batch_insert_array)) {
					die('1');
				}
			} else {
				echo validation_errors();
				die;
			}
			die('Error Creating Categories !!!');
		}
		$this->load->model('Brand_model');
		$data['brands_array'] = $this->Brand_model->get_all_active_brands();
		$this->render_view($data);
	}

	function _check_unique_category_name($category_name) {
		if ($this->Category_model->check_unique_category_name($this->input->post('brands_id'), $category_name) > 0) {
			$this->form_validation->set_message('_check_unique_category_name', 'The Category : "' . $category_name . '" already exists !!!');
			return FALSE;
		}
		return TRUE;
	}

	function _check_unique_category_alias($category_alias) {
		if ($this->Category_model->check_unique_category_alias($this->input->post('brands_id'), $category_alias) > 0) {
			$this->form_validation->set_message('_check_unique_category_alias', 'The Category Alias : "' . $category_alias . '" already exists !!!');
			return FALSE;
		}
		return TRUE;
	}

	function block_unblock_category() {
		parent::allow(array('administrator', 'manager'));
		if (
				$this->input->post('category_id') &&
				is_numeric($this->input->post('category_id')) &&
				$this->input->post('category_id') > 0
		) {
			$category_details_array = $this->Category_model->get_category_row_by_category_id($this->input->post('category_id'));
			switch ($category_details_array['category_status']) {
				case '-1':
					die('Error Updating Category Status !!!');
					break;
				case '0':
					$current_status = '1';
					break;
				case '1':
					$current_status = '0';
					break;
				default:
					break;
			}
			if ($this->Category_model->edit_category_by_category_id($this->input->post('category_id'), array('category_status' => $current_status, 'category_modified' => date('Y-m-d H:i:s')))) {
				echo $current_status;
				die;
			}
		}
		die('Error Updating Category Status !!!');
	}

}
