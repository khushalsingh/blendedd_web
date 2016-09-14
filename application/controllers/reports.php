<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Reports extends MY_Controller {

	public $public_methods = array();

	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function index() {
		parent::allow(array('administrator', 'manager'));
		$data = array();
		$this->load->model('Category_model');
		$data['category_array'] = $this->Category_model->get_all_active_categories();
		$data['error_message'] = $this->session->flashdata('error_message');
		$data['success_message'] = $this->session->flashdata('success_message');
		$this->render_view($data);
	}

	function test() {
		parent::allow(array('administrator', 'manager'));
		$file_name = 'Report_' . date('d-M-Y-h-i-s-a');
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=' . $file_name . '.xls');
		$result_set = @mysql_unbuffered_query("SELECT user_firm_name,user_first_name,user_mobile,user_email,state_name,city_name,region_name,distributor_full_name,purchase_date,purchase_invoice_number,brand_name,sku_name,purchase_quantity,purchase_created FROM (`purchases`) 
				LEFT JOIN `users` ON `users`.`user_id` = `purchases`.`users_id` 
				LEFT JOIN `distributors` ON `distributors`.`distributor_id`=`purchases`.`distributors_id`
				LEFT JOIN `cities` ON `cities`.`city_id` = `users`.`cities_id` 
				LEFT JOIN `states` ON `states`.`state_id` = `cities`.`states_id` 
                LEFT JOIN `regions` ON `regions`.`region_id` = `states`.`regions_id`
				LEFT JOIN `skus` ON `skus`.`sku_id` = `purchases`.`skus_id`
				LEFT JOIN `products` ON `products`.`product_id` = `skus`.`products_id`
				LEFT JOIN `sub_categories` ON `sub_categories`.`sub_category_id` = `products`.`sub_categories_id`
				LEFT JOIN `categories` ON `categories`.`category_id` = `sub_categories`.`categories_id`
				LEFT JOIN `brands` ON `brands`.`brand_id` = `categories`.`brands_id`
				ORDER BY `purchases`.`purchase_id`");
		$fp = fopen('php://output', 'w');
		fputcsv($fp, array(
			"Firm name",
			"Owner name",
			"Mobile",
			"E-mail ",
			"State",
			"City",
			"Region",
			"Distributor",
			"Purchase date",
			"Invoice number",
			"Brand",
			"Product",
			"Quantity",
			"Created"
				), "\t");
		while ($row = @mysql_fetch_assoc($result_set)) {
			fputcsv($fp, $row, "\t");
		}
		fclose($fp);
	}

	function active_users_datatable() {
		$this->load->model('User_model');
		$data['active_users_array'] = $this->User_model->get_all_active_users();
		$this->render_view($data, 'blank');
	}

	function revenue_report_datatable() {
		$this->load->model('invoice_model');
		$data['revenue_report_array'] = $this->invoice_model->get_revenue_report();
		$this->render_view($data, 'blank');
	}
    function new_user_datatable() {
		$this->load->model('invoice_model');
		$data['new_user_array'] = $this->invoice_model->get_new_user_report();
		$this->render_view($data, 'blank');
	}
	  function report_by_location_datatable() {
		$this->load->model('invoice_model');
		$data['report_by_location_array'] = $this->invoice_model->get_user_report_by();
		$this->render_view($data, 'blank');
	}
	 function posting_report_datatable() {
		$this->load->model('invoice_model');
		$data['posting_report_array'] = $this->invoice_model->posting_report();
		$this->render_view($data, 'blank');
	}
	 function posting_report_by_category_datatable() {
		$this->load->model('invoice_model');
		$data['posting_report_by_category_array'] = $this->invoice_model->get_user_report_by_category();
		$this->render_view($data, 'blank');
	}
}
