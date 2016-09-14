<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Users extends MY_Controller {

	public $public_methods = array();

	function __construct() {
		parent::__construct();
		$this->load->model('User_model');
	}

	function index() {
		parent::allow(array('administrator', 'manager'));
		$data = array();
		$data['error_message'] = $this->session->flashdata('error_message');
		$data['success_message'] = $this->session->flashdata('success_message');
		$this->render_view($data);
	}

	function users_datatable() {
		parent::allow(array('administrator', 'manager'));
		$this->load->library('Datatables');
		$this->datatables->select("user_id,user_first_name,user_last_name,user_email,user_primary_contact,user_status", FALSE)->from('users');
		echo $this->datatables->generate();
	}

	function view($user_id = 0) {
		parent::allow(array('administrator', 'manager'));
		$data = array();
		$data['user_details_array'] = $this->User_model->get_user_details_by_user_id($user_id);
		$this->render_view($data);
	}

	function delete() {
		if ($this->input->post('user_id') && $this->User_model->edit_user_by_user_id($this->input->post('user_id'), array('user_status' => '-1', 'user_modified' => date('Y-m-d H:i:s')))) {
			die('1');
		}
		die('0');
	}

	function update_credit_card() {
		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('user_credit_card_name', 'Credit Card Name', 'trim|required');
			$this->form_validation->set_rules('user_credit_card_number', 'Credit Card Number', 'trim|required|callback__validate_unique_credit_card');
			$this->form_validation->set_rules('user_credit_card_expiry_month', 'Expiry Month', 'trim|required');
			$this->form_validation->set_rules('user_credit_card_expiry_year', 'Expiry Year', 'trim|required');
			$this->form_validation->set_rules('user_credit_card_cvv', 'CVV Number', 'trim|required');
			$this->form_validation->set_error_delimiters('', '<br />');
			if ($this->form_validation->run()) {
				$this->load->library('encrypt');
				$user_update_array = array(
					'user_credit_card_name' => $this->encrypt->encode($this->input->post('user_credit_card_name')),
					'user_credit_card_number' => $this->encrypt->encode($this->input->post('user_credit_card_number')),
					'user_credit_card_number_md5' => md5($this->input->post('user_credit_card_number')),
					'user_credit_card_expiry_month' => $this->encrypt->encode($this->input->post('user_credit_card_expiry_month')),
					'user_credit_card_expiry_year' => $this->encrypt->encode($this->input->post('user_credit_card_expiry_year')),
					'user_credit_card_cvv' => $this->encrypt->encode($this->input->post('user_credit_card_cvv')),
					'user_modified' => date('Y-m-d H:i:s')
				);
				$purchase_response = parent::braintree_cc_purchase('1.00', $this->input->post('user_credit_card_name'), $this->input->post('user_credit_card_expiry_month'), $this->input->post('user_credit_card_expiry_year'), $this->input->post('user_credit_card_number'), $this->input->post('user_credit_card_cvv'));
				if (isset($purchase_response->success) && $purchase_response->success) {
					if ($this->User_model->edit_user_by_user_id($_SESSION['user']['user_id'], $user_update_array)) {
						parent::regenerate_session();
						die('1');
					}
				}
			} else {
				echo validation_errors();
				die;
			}
			die('0');
		}
	}

	function edit($user_id = 0) {
		$data = array();
		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('user_first_name', 'First Name', 'trim|required');
			$this->form_validation->set_rules('user_last_name', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('user_facebook_url', 'Facebook Link', 'trim|valid_url');
			$this->form_validation->set_rules('user_twitter_url', 'Twitter Link', 'trim||valid_url');
			$this->form_validation->set_rules('user_linkedin_url', 'LinkedIn Link', 'trim|valid_url');
			$this->form_validation->set_rules('user_instagram_url', 'Instagram Link', 'trim|valid_url');
			$this->form_validation->set_rules('user_address_line_1', 'Street Address 1', 'trim|required');
			$this->form_validation->set_rules('user_address_line_2', 'Street Address 2', 'trim');
			$this->form_validation->set_rules('countries_id', 'Country', 'trim|required');
			$this->form_validation->set_rules('states_id', 'State/Province', 'trim|required');
			$this->form_validation->set_rules('cities_id', 'City', 'trim|required');
			$this->form_validation->set_rules('user_zipcode', 'Zip Code', 'trim|required');
			$this->form_validation->set_rules('user_communication_via_email', 'Email Communication', 'trim|callback__check_communication_preferences');
			$this->form_validation->set_rules('user_communication_via_phone_call', 'Call Communication', 'trim');
			$this->form_validation->set_rules('user_communication_via_sms', 'SMS Communication', 'trim');
			$this->form_validation->set_error_delimiters("", "<br/>");
			if ($this->form_validation->run()) {
				$user_update_array = array(
					'user_email' => $this->input->post('user_email'),
					'user_primary_contact' => $this->input->post('user_primary_contact'),
					'user_first_name' => $this->input->post('user_first_name'),
					'user_last_name' => $this->input->post('user_last_name'),
					'user_facebook_url' => $this->input->post('user_facebook_url'),
					'user_twitter_url' => $this->input->post('user_twitter_url'),
					'user_linkedin_url' => $this->input->post('user_linkedin_url'),
					'user_instagram_url' => $this->input->post('user_instagram_url'),
					'user_address_line_1' => $this->input->post('user_address_line_1'),
					'user_address_line_2' => $this->input->post('user_address_line_2'),
					'cities_id' => $this->input->post('cities_id'),
					'user_zipcode' => $this->input->post('user_zipcode'),
					'user_communication_via_email' => $this->input->post('user_communication_via_email'),
					'user_communication_via_phone_call' => $this->input->post('user_communication_via_phone_call'),
					'user_communication_via_sms' => $this->input->post('user_communication_via_sms'),
					'user_modified' => date('Y-m-d H:i:s')
				);
				if ($this->User_model->edit_user_by_user_id($user_id, $user_update_array)) {
					parent::regenerate_session();
					die('1');
				}
			} else {
				echo validation_errors();
				die;
			}
			die('0');
		}
		$data['error_message'] = $this->session->flashdata('error_message');
		$data['success_message'] = $this->session->flashdata('success_message');
		$this->load->helper('form');
		$data['user_details_array'] = $this->User_model->get_user_details_by_user_id($user_id);
		$this->load->model('Country_model');
		$data['countries_array'] = $this->Country_model->get_all_active_countries();
		$this->load->model('State_model');
		$data['states_array'] = $this->State_model->get_active_states_by_country_id($data['user_details_array']['country_id']);
		$this->load->model('City_model');
		$data['cities_array'] = $this->City_model->get_active_cities_by_state_id($data['user_details_array']['state_id']);
		$this->render_view($data);
	}

}
