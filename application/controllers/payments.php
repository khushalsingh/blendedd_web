<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Payments extends MY_Controller {

	public $public_methods = array();

	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function index() {
		parent::allow(array('administrator', 'manager'));
		$data = array();
		$data['error_message'] = $this->session->flashdata('error_message');
		$data['success_message'] = $this->session->flashdata('success_message');
		$this->render_view($data);
	}

	function invoices_datatable() {
		parent::allow(array('administrator', 'manager'));
		$this->load->library('Datatables');
		$this->datatables->where('invoices.invoice_status', '1');
		$this->datatables->select("invoice_transaction_id,post_title,user_email,invoice_amount,invoice_merchant_amount,invoice_blendedd_amount,DATE_FORMAT(invoice_paid_on, '%e %b %Y') AS invoice_created_date,invoice_settlement_status", FALSE)->from('invoices');
		$this->datatables->join('posts', 'posts.post_id = invoices.posts_id', 'left');
		$this->datatables->join('users', 'users.user_id = invoices.invoice_to_users_id', 'left');
		echo $this->datatables->generate();
	}

}
