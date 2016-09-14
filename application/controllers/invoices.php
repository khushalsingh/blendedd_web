<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Invoices extends MY_Controller {

	public $public_methods = array('index');

	function __construct() {
		parent::__construct();
		$this->load->model('Invoice_model');
	}

	function index() {
		$this->render_view();
	}

	function create() {
		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			$this->form_validation->set_rules('posts_id', 'Post id', 'trim|required');
			$this->form_validation->set_rules('pricing_options_id', 'Pricing Option', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('currency_code', 'Currency', 'trim|required');
			$this->form_validation->set_error_delimiters('', '<br/>');
			if ($this->form_validation->run()) {
				$this->load->model('Post_model');
				$post_details_array = $this->Post_model->get_post_row_by_id($this->input->post('posts_id'));
				$invoice_insert_array = array(
					'invoice_amount' => $this->input->post('amount'),
					'posts_id' => $this->input->post('posts_id'),
					'invoice_by_users_id' => $post_details_array['users_id'],
					'invoice_to_users_id' => $_SESSION['user']['user_id'],
					'pricing_options_id' => $this->input->post('pricing_options_id'),
					'invoice_status' => '0',
					'invoice_currency' => $this->input->post('currency_code'),
					'invoice_created' => date('Y-m-d H:i:s')
				);
				$invoice_id = $this->Invoice_model->create($invoice_insert_array);
				if ($invoice_id > 0) {
					echo $invoice_id;
					die;
				} else {
					die('0');
				}
			} else {
				echo validation_errors();
				die;
			}
		}
	}

}
