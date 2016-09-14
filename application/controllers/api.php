<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Api extends MY_Controller {

	public $public_methods = array();
	public $weekdays_array = array(
		'1' => 'monday',
		'2' => 'tuesday',
		'3' => 'wednesday',
		'4' => 'thursday',
		'5' => 'friday',
		'6' => 'saturday',
		'7' => 'sunday',
	);
	public $package_deals_array = array();
	public $item_price_array = array();

	function __construct() {
		parent::__construct();
		if (!$this->input->post()) {
			$this->output->set_status_header('401');
			die;
		}
		$this->load->database();
	}

	private function _error() {
		parent::json_output(array('code' => '0', 'message' => 'Error !!!'));
		return;
	}

	private function _get_user() {
		$this->load->library('form_validation');
		$this->form_validation = new MY_Form_validation();
		$this->form_validation->set_rules('user_id', 'User ID', 'trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('user_security_hash', 'User Security Hash', 'trim|required|exact_length[32]');
		if ($this->form_validation->run()) {
			$this->load->model('User_model');
			$user_details_array = $this->User_model->get_active_user_by_id_and_security_hash($this->input->post('user_id'), $this->input->post('user_security_hash'));
			if (count($user_details_array) > 0) {
				if ($user_details_array['user_credit_card_number'] !== '') {
					$this->load->library('encrypt');
					$user_details_array['user_credit_card_number'] = parent::mask_characters($this->encrypt->decode($user_details_array['user_credit_card_number']), 12, 'X');
				}
				return $user_details_array;
			}
		}
		return array();
	}

	function _get_pricing_options() {
		$this->load->model('Post_model');
		$pricing_options_array = $this->Post_model->get_pricing_options();
		foreach ($pricing_options_array as $pricing_option) {
			if ($pricing_option['category_type'] === '1') {
				$this->item_price_array[] = $pricing_option;
			}
			if ($pricing_option['category_type'] === '2') {
				$this->package_deals_array[] = $pricing_option;
			}
		}
	}

	function _trim_html($html) {
		$return = '<!DOCTYPE html><html><head><meta charset="UTF-8" /><link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" /><link rel="stylesheet" href="' . base_url() . 'assets/css/blendedd.css" /></head><body>';
		$html = preg_replace('#<a.*?>(.*?)</a>#i', '$1', (preg_replace('#(' . preg_quote("<!--API_TRIM_START-->") . ')(.*?)(' . preg_quote("<!--API_TRIM_END-->") . ')#si', '$1' . "" . '$3', $html)));
		$return .= $html;
		$return .= '</body></html>';
		return $return;
	}

	function index() {
		parent::json_output(array('code' => '1', 'message' => '', 'data' => '1.0'));
		return;
	}

	function get_api_configuration($api_config_key = '') {
		if (trim($api_config_key) !== '') {
			$this->load->model('Api_model');
			$api_configuration_array = $this->Api_model->get_api_configuration_by_key($api_config_key);
			if (count($api_configuration_array) > 0) {
				parent::json_output(array('code' => '1', 'message' => $api_configuration_array['api_configuration_value']));
				return;
			}
		}
		parent::json_output(array('code' => '0', 'message' => 'Server Error.'));
		return;
	}

	function categories() {
		parent::_create_cache();
		$this->load->model('Category_model');
		parent::json_output(array('code' => '1', 'message' => '', 'data' => $this->Category_model->get_all_active_categories()));
		return;
	}

	function signup_step_one() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('user_last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('user_login', 'User ID', 'trim|required|is_unique[users.user_login]|is_unique[registrations.user_login]');
		$this->form_validation->set_rules('user_login_password', 'Password', 'trim|min_length[8]|callback__validate_password');
		$this->form_validation->set_rules('confirm_user_login_password', 'Confirm Password', 'trim|matches[user_login_password]');
		$this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|is_unique[users.user_email]|is_unique[registrations.user_email]|is_unique[users.user_paypal_email_address]|is_unique[registrations.user_paypal_email_address]');
		$this->form_validation->set_rules('user_primary_contact', 'Primary Contact Number', 'trim|required|is_unique[users.user_primary_contact]|is_unique[registrations.user_primary_contact]');
		$this->form_validation->set_rules('user_dob', 'Date Of Birth', 'trim|required');
		$this->form_validation->set_error_delimiters("", "|");
		if ($this->form_validation->run()) {
			parent::json_output(array('code' => '1', 'message' => '', 'data' => array()));
			return;
		} else {
			parent::json_output(array('code' => '0', 'message' => '', 'data' => explode('|', validation_errors())));
			return;
		}
		$this->_error();
	}

	function signup_step_two() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_address_line_1', 'Street Address 1', 'trim|required');
		$this->form_validation->set_rules('user_address_line_2', 'Street Address 2', 'trim');
		$this->form_validation->set_rules('countries_id', 'Country', 'trim|required');
		$this->form_validation->set_rules('states_id', 'State/Province', 'trim|required');
		$this->form_validation->set_rules('cities_id', 'City', 'trim|required');
		$this->form_validation->set_rules('user_zipcode', 'Zip Code', 'trim|required');
		$this->form_validation->set_error_delimiters("", "|");
		if ($this->form_validation->run()) {
			parent::json_output(array('code' => '1', 'message' => '', 'data' => array()));
			return;
		} else {
			parent::json_output(array('code' => '0', 'message' => '', 'data' => explode('|', validation_errors())));
			return;
		}
		$this->_error();
	}

	function signup_step_three() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_credit_card_name', 'Credit Card Name', 'trim|required');
		$this->form_validation->set_rules('user_credit_card_number', 'Credit Card Number', 'trim|required|callback__validate_unique_credit_card');
		$this->form_validation->set_rules('user_credit_card_expiry_month', 'Expiry Month', 'trim|required');
		$this->form_validation->set_rules('user_credit_card_expiry_year', 'Expiry Year', 'trim|required');
		$this->form_validation->set_rules('user_credit_card_cvv', 'CVV Number', 'trim|required');
		$this->form_validation->set_error_delimiters("", "|");
		if ($this->form_validation->run()) {
			$purchase_response = parent::braintree_cc_purchase('1.00', $this->input->post('user_credit_card_name'), $this->input->post('user_credit_card_expiry_month'), $this->input->post('user_credit_card_expiry_year'), $this->input->post('user_credit_card_number'), $this->input->post('user_credit_card_cvv'));
			if (isset($purchase_response->success) && $purchase_response->success) {
				parent::json_output(array('code' => '1', 'message' => 'Credit Card Validated Succesfully.', 'data' => array()));
				return;
			}
		} else {
			parent::json_output(array('code' => '0', 'message' => '', 'data' => explode('|', validation_errors())));
			return;
		}
		$this->_error();
	}

	function signup_step_four() {
		$this->load->library('form_validation');
		/**
		 * Step 1
		 */
		$this->form_validation->set_rules('user_first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('user_last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('user_login', 'User ID', 'trim|required|is_unique[users.user_login]|is_unique[registrations.user_login]');
		$this->form_validation->set_rules('user_login_password', 'Password', 'trim|min_length[8]|callback__validate_password');
		$this->form_validation->set_rules('confirm_user_login_password', 'Confirm Password', 'trim|matches[user_login_password]');
		$this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|is_unique[users.user_email]|is_unique[registrations.user_email]|is_unique[users.user_paypal_email_address]|is_unique[registrations.user_paypal_email_address]');
		$this->form_validation->set_rules('user_primary_contact', 'Primary Contact Number', 'trim|required|is_unique[users.user_primary_contact]|is_unique[registrations.user_primary_contact]');
		$this->form_validation->set_rules('user_dob', 'Date Of Birth', 'trim|required');
		/**
		 * Step 2
		 */
		$this->form_validation->set_rules('user_address_line_1', 'Street Address 1', 'trim|required');
		$this->form_validation->set_rules('user_address_line_2', 'Street Address 2', 'trim');
		$this->form_validation->set_rules('countries_id', 'Country', 'trim|required');
		$this->form_validation->set_rules('states_id', 'State/Province', 'trim|required');
		$this->form_validation->set_rules('cities_id', 'City', 'trim|required');
		$this->form_validation->set_rules('user_zipcode', 'Zip Code', 'trim|required');
		/**
		 * Step 3
		 */
		$this->form_validation->set_rules('user_credit_card_name', 'Credit Card Name', 'trim|required');
		$this->form_validation->set_rules('user_credit_card_number', 'Credit Card Number', 'trim|required|callback__validate_unique_credit_card');
		$this->form_validation->set_rules('user_credit_card_expiry_month', 'Expiry Month', 'trim|required');
		$this->form_validation->set_rules('user_credit_card_expiry_year', 'Expiry Year', 'trim|required');
		$this->form_validation->set_rules('user_credit_card_cvv', 'CVV Number', 'trim|required');
		/**
		 * Step 4
		 */
		$this->form_validation->set_rules('user_communication_via_phone_call', 'Call Communication', 'trim');
		$this->form_validation->set_rules('user_communication_via_sms', 'SMS Communication', 'trim');
		$this->form_validation->set_rules('user_newsletter_subscription', 'Newsletter Subscription', 'trim');
		$this->form_validation->set_rules('user_agreement', 'Agreement', 'trim');
		$this->form_validation->set_error_delimiters("", "|");
		if ($this->form_validation->run()) {
			$this->load->library('encrypt');
			$time_now = date('Y-m-d H:i:s');
			$registration_insert_array = array(
				'groups_id' => '3',
				'user_type' => '2',
				'user_first_name' => $this->input->post('user_first_name'),
				'user_last_name' => $this->input->post('user_last_name'),
				'user_login' => $this->input->post('user_login'),
				'user_email' => $this->input->post('user_email'),
				'user_login_salt' => md5($time_now),
				'user_login_password' => md5(md5(md5($time_now) . $this->input->post('user_login_password'))),
				'user_password_hash' => $this->encrypt->encode($this->input->post('user_login_password'), md5(md5(md5($time_now) . $this->input->post('user_login_password')))),
				'user_security_hash' => md5($time_now . $this->input->post('user_login_password')),
				'user_primary_contact' => $this->input->post('user_primary_contact'),
				'user_dob' => $this->input->post('user_dob'),
				'user_address_line_1' => $this->input->post('user_address_line_1'),
				'user_address_line_2' => $this->input->post('user_address_line_2'),
				'cities_id' => $this->input->post('cities_id'),
				'user_zipcode' => $this->input->post('user_zipcode'),
				'user_credit_card_name' => $this->encrypt->encode($this->input->post('user_credit_card_name')),
				'user_credit_card_number' => $this->encrypt->encode($this->input->post('user_credit_card_number')),
				'user_credit_card_number_md5' => md5($this->input->post('user_credit_card_number')),
				'user_credit_card_expiry_month' => $this->encrypt->encode($this->input->post('user_credit_card_expiry_month')),
				'user_credit_card_expiry_year' => $this->encrypt->encode($this->input->post('user_credit_card_expiry_year')),
				'user_credit_card_cvv' => $this->encrypt->encode($this->input->post('user_credit_card_cvv')),
				'user_communication_via_email' => '1',
				'user_communication_via_phone_call' => $this->input->post('user_communication_via_phone_call'),
				'user_communication_via_sms' => $this->input->post('user_communication_via_sms'),
				'user_newsletter_subscription' => $this->input->post('user_newsletter_subscription'),
				'user_agreement' => $this->input->post('user_agreement'),
				'user_status' => '0',
				'user_ip' => $this->input->server('REMOTE_ADDR'),
				'user_agent' => $this->input->server('HTTP_USER_AGENT'),
				'user_created' => $time_now
			);
			$this->load->model('Auth_model');
			$registration_id = $this->Auth_model->register($registration_insert_array);
			if ($registration_id > 0) {
				$email_id1 = parent::add_email_to_queue('', '', $registration_insert_array['user_email'], '0', 'Registration Received', $this->render_view($registration_insert_array, 'emails', 'emails/templates/register', TRUE));
				$email_id2 = parent::add_email_to_queue('', '', $this->config->item('admin_email'), '1', 'Registration Received', $this->render_view($registration_insert_array, 'emails', 'emails/templates/admin_register', TRUE));
				if ($email_id1 > 0 && $email_id2 > 0) {
					@file_get_contents(base_url() . 'emails/cron/' . $email_id1);
					@file_get_contents(base_url() . 'emails/cron/' . $email_id2);
				}
				parent::json_output(array('code' => '1', 'message' => 'Thank you. You must click on the the link sent to your email to activate your account before trying to log in.', 'data' => $registration_insert_array));
				return;
			}
		} else {
			parent::json_output(array('code' => '0', 'message' => '', 'data' => explode('|', validation_errors())));
			return;
		}
		$this->_error();
	}

	function social_media_login() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_facebook_id', 'Facebook ID', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('user_first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('user_last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email');
		if ($this->form_validation->run()) {
			$this->load->model('Auth_model');
			$this->load->model('User_model');
			$user_details_array = $this->User_model->get_user_by_social_id($this->input->post('user_facebook_id'), 'facebook');
			if (count($user_details_array) > 0) {
				$this->Auth_model->update_user_login($user_details_array['user_id']);
				$this->Auth_model->add_login_log(array(
					'users_id' => $user_details_array['user_id'],
					'login_log_from' => '1',
					'login_log_mode' => 'facebook',
					'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
					'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
					'login_log_created' => date('Y-m-d H:i:s')
				));
				$_POST['user_id'] = $user_details_array['user_id'];
				$_POST['user_security_hash'] = $user_details_array['user_security_hash'];
				$user_details_array = $this->_get_user();
				parent::json_output(array('code' => '1', 'message' => 'Login Successful.', 'data' => $user_details_array));
				return;
			} else {
				$user_details_array = $this->User_model->get_user_by_email($this->input->post('user_email'));
				if (count($user_details_array) > 0) {
					$user_update_array = array(
						'user_facebook_id' => $this->input->post('user_facebook_id'),
						'user_modified' => date('Y-m-d H:i:s')
					);
					if ($this->Auth_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
						$this->Auth_model->update_user_login($user_details_array['user_id']);
						$this->Auth_model->add_login_log(array(
							'users_id' => $user_details_array['user_id'],
							'login_log_from' => '1',
							'login_log_mode' => 'facebook',
							'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
							'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
							'login_log_created' => date('Y-m-d H:i:s')
						));
						$_POST['user_id'] = $user_details_array['user_id'];
						$_POST['user_security_hash'] = $user_details_array['user_security_hash'];
						$user_details_array = $this->_get_user();
						parent::json_output(array('code' => '1', 'message' => 'Login Successful.', 'data' => $user_details_array));
						return;
					}
				} else {
					$time_now = date('Y-m-d H:i:s');
					$user_login_password = parent::generate_random_string();
					$user_insert_array = array(
						'user_facebook_id' => $this->input->post('user_facebook_id'),
						'groups_id' => '3',
						'user_login' => $this->input->post('user_facebook_id'),
						'user_first_name' => $this->input->post('user_first_name'),
						'user_last_name' => $this->input->post('user_last_name'),
						'user_email' => $this->input->post('user_email'),
						'user_login_salt' => md5($time_now),
						'user_login_password' => md5(md5(md5($time_now) . $user_login_password)),
						'user_password_hash' => $this->encrypt->encode($user_login_password, md5(md5(md5($time_now) . $user_login_password))),
						'user_security_hash' => md5($time_now . $user_login_password),
						'user_communication_via_email' => '1',
						'user_agreement' => '1',
						'user_newsletter_subscription' => '1',
						'user_created' => $time_now,
					);
					$user_id = $this->User_model->insert($user_insert_array);
					if ($user_id > 0) {
						$email_id1 = parent::add_email_to_queue('', '', $user_insert_array['user_email'], '0', 'Registration Received', $this->render_view($user_insert_array, 'emails', 'emails/templates/register_facebook', TRUE));
						$email_id2 = parent::add_email_to_queue('', '', $this->config->item('admin_email'), '1', 'Registration Received', $this->render_view($user_insert_array, 'emails', 'emails/templates/admin_register_facebook', TRUE));
						if ($email_id1 > 0 && $email_id2 > 0) {
							@file_get_contents(base_url() . 'emails/cron/' . $email_id1);
							@file_get_contents(base_url() . 'emails/cron/' . $email_id2);
						}
						$user_details_array = $this->User_model->get_user_by_social_id($this->input->post('user_facebook_id'), 'facebook');
						$this->Auth_model->update_user_login($user_details_array['user_id']);
						$this->Auth_model->add_login_log(array(
							'users_id' => $user_details_array['user_id'],
							'login_log_from' => '1',
							'login_log_mode' => 'facebook',
							'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
							'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
							'login_log_created' => date('Y-m-d H:i:s')
						));
						$_POST['user_id'] = $user_insert_array['user_id'];
						$_POST['user_security_hash'] = $user_insert_array['user_security_hash'];
						$user_details_array = $this->_get_user();
						parent::json_output(array('code' => '1', 'message' => 'Login Successful.', 'data' => $this->_get_user()));
						return;
					}
				}
			}
		}
		$this->_error();
	}

	function session_login() {
		$user_details_array = $this->_get_user();
		if (count($user_details_array) > 0) {
			$this->load->model('Auth_model');
			$this->Auth_model->add_login_log(array(
				'users_id' => $user_details_array['user_id'],
				'login_log_from' => '2',
				'login_log_mode' => 'mobile',
				'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
				'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
				'login_log_created' => date('Y-m-d H:i:s')
			));
			parent::json_output(array('code' => '1', 'message' => 'Logged In Successfully.', 'data' => $user_details_array));
			return;
		}
		$this->_error();
	}

	function login() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_login', 'Username OR Email ID', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('user_login_password', 'Password', 'trim|required|min_length[5]');
		if ($this->form_validation->run()) {
			$this->load->model('Auth_model');
			$user_details_array = $this->Auth_model->login(trim($this->input->post('user_login')));
			if (
					count($user_details_array) > 0 &&
					strtolower(trim($this->input->post('user_login_password'))) === strtolower($this->encrypt->decode($user_details_array['user_password_hash'], $user_details_array['user_login_password']))
			) {
				$this->Auth_model->add_login_log(array(
					'users_id' => $user_details_array['user_id'],
					'login_log_from' => '2',
					'login_log_mode' => 'mobile',
					'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
					'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
					'login_log_created' => date('Y-m-d H:i:s')
				));
				$this->load->model('User_model');
				$_POST['user_id'] = $user_details_array['user_id'];
				$_POST['user_security_hash'] = $user_details_array['user_security_hash'];
				parent::json_output(array('code' => '1', 'message' => 'Logged In Successfully.', 'data' => $this->_get_user()));
				return;
			} else {
				parent::json_output(array('code' => '-1', 'message' => 'Invalid User Credentials.'));
				return;
			}
		} else {
			parent::json_output(array('code' => '0', 'message' => 'Invalid Email ID OR Password.'));
			return;
		}
		$this->_error();
	}

	function recover() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email_address', 'User ID OR Email', 'trim|required');
		if ($this->form_validation->run()) {
			$this->load->model('Auth_model');
			$user_details_array = $this->Auth_model->get_user_by_username_or_email($this->input->post('email_address'));
			if (count($user_details_array) > 0) {
				$new_password = parent::generate_random_string();
				$time_now = date('Y-m-d H:i:s');
				$this->load->library('encrypt');
				$user_update_array = array(
					'user_login_salt' => md5($time_now),
					'user_login_password' => md5(md5(md5($time_now) . $new_password)),
					'user_password_hash' => $this->encrypt->encode($new_password, md5(md5(md5($time_now) . $new_password))),
					'user_security_hash' => md5($time_now . $new_password),
					'user_modified' => $time_now,
					'force_change_password' => '1'
				);
				$this->load->model('User_model');
				if ($this->User_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
					$email_details_array = array(
						'user_first_name' => $user_details_array['user_first_name'],
						'user_last_name' => $user_details_array['user_last_name'],
						'user_email' => $user_details_array['user_email'],
						'user_login_password' => $new_password
					);
					$email_id = parent::add_email_to_queue('', '', $user_details_array['user_email'], $user_details_array['user_id'], 'Your Account Password', $this->render_view($email_details_array, 'emails', 'emails/templates/forgot_password', TRUE));
					if ($email_id > 0) {
						$file_contents = file_get_contents(base_url() . 'emails/cron/' . $email_id);
						if ($file_contents === '1') {
							parent::json_output(array('code' => '1', 'message' => 'We have sent an email with new password.', 'data' => $user_update_array['user_security_hash']));
							return;
						}
					}
				}
			} else {
				parent::json_output(array('code' => '0', 'message' => 'Invalid User !!!'));
				return;
			}
		} else {
			parent::json_output(array('code' => '0', 'message' => 'Invalid Email ID !!!'));
			return;
		}
		$this->_error();
	}

	function change_password() {
		$user_details_array = $this->_get_user();
		if (count($user_details_array) > 0) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('user_login_password', 'Password', 'trim|required|min_length[8]|callback__validate_password');
			$this->form_validation->set_rules('user_confirm_password', 'Confirm Password ', 'trim|required|matches[user_login_password]');
			if ($this->form_validation->run()) {
				$time_now = date('Y-m-d H:i:s');
				if ($this->input->post('user_login_password') !== '') {
					$user_update_array['user_security_hash'] = md5($time_now . $this->input->post('user_login_password'));
					$user_update_array['force_change_password'] = '0';
					$user_update_array['user_modified'] = $time_now;
					$user_update_array['user_login_salt'] = md5($time_now);
					$user_update_array['user_login_password'] = md5(md5(md5($time_now) . $this->input->post('user_login_password')));
					$user_update_array['user_password_hash'] = $this->encrypt->encode($this->input->post('user_login_password'), md5(md5(md5($time_now) . $this->input->post('user_login_password'))));
				}
				$this->load->model('Auth_model');
				if ($this->Auth_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
					$_POST['user_security_hash'] = $user_update_array['user_security_hash'];
					parent::json_output(array('code' => '1', 'message' => 'Password Changed.', 'data' => $this->_get_user()));
					return;
				}
			}
			$this->_error();
		}
	}

	function edit_profile() {
		$user_details_array = $this->_get_user();
		if (count($user_details_array) > 0) {
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
			if ($this->form_validation->run()) {
				$time_now = date('Y-m-d H:i:s');
				$user_update_array = array(
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
					'user_modified' => $time_now
				);

				if ($this->User_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
					parent::json_output(array('code' => '1', 'message' => 'Profile Edited Successfully.', 'data' => $this->_get_user()));
					return;
				}
			}
		}
		$this->_error();
	}

	function dashboard() {
		$user_details_array = $this->_get_user();
		if (count($user_details_array) > 0) {
			$this->load->model('Post_model');
			$data = $this->Post_model->get_all_paid_posts_by_user_id($user_details_array['user_id']);
			if (count($data) > 0) {
				foreach ($data as $key => $post) {
					$data[$key]['post_image_url'] = parent::_prepare_post_image_url($post);
					$data[$key]['post_display_price'] = 'US $' . sprintf('%01.2f', $post['post_min_price']);
				}
				parent::json_output(array('code' => '1', 'message' => 'Success Fetching Posts.', 'data' => $data));
				return;
			} else {
				parent::json_output(array('code' => '0', 'message' => 'No Posts', 'data' => array()));
				return;
			}
		}
		$this->_error();
	}

	function search() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('categories_id', 'Category', 'trim|is_natural_no_zero');
		$this->form_validation->set_rules('search_term', 'Search Term', 'trim');
		$this->form_validation->set_rules('page', 'Page', 'trim|numeric');
		if ($this->form_validation->run()) {
			$this->load->model('Search_model');
			$categories_id = '-';
			if (trim($this->input->post('categories_id')) !== '') {
				$categories_id = $this->input->post('categories_id');
			}
			$search_term = '-';
			if (trim($this->input->post('search_term')) !== '') {
				$search_term = $this->input->post('search_term');
			}
			$page = 0;
			if (trim($this->input->post('page')) !== '') {
				$page = $this->input->post('page');
			}
			$per_page = 12;
			$order_by = array('post_id', 'desc');
			$data = $this->Search_model->search($categories_id, $search_term, $page * $per_page, $per_page, $order_by);
			if (count($data) > 0) {
				foreach ($data as $key => $post) {
					$data[$key]['post_image_url'] = parent::_prepare_post_image_url($post);
					$data[$key]['post_display_price'] = 'US $' . sprintf('%01.2f', $post['post_min_price']);
				}
				parent::json_output(array('code' => '1', 'message' => 'Success Fetching Posts.', 'data' => $data));
				return;
			} else {
				parent::json_output(array('code' => '0', 'message' => 'No More Posts', 'data' => array()));
				return;
			}
		}
		$this->_error();
	}

	function view() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('post_id', 'Post Id', 'trim||required|is_natural_no_zero');
		if ($this->form_validation->run()) {
			$this->load->model('Post_model');
			$post_details_array = $this->Post_model->get_post_by_id($this->input->post('post_id'));
			if (count($post_details_array) > 0) {
				date_default_timezone_set($post_details_array['time_zone_set']);
				$post_time_availability_array = $this->Post_model->get_time_availability_by_post_id($this->input->post('post_id'));
				$post_images_array = $this->Post_model->get_images_by_post_id($this->input->post('post_id'));
				$post_details_array['post_images_array'] = array();
				if (count($post_images_array) > 0) {
					foreach ($post_images_array as $post_images) {
						$post_images['post_created'] = $post_details_array['post_created'];
						$post_details_array['post_images_array'][] = array(
							'post_image_url' => parent::_prepare_post_image_url($post_images)
						);
					}
				} else {
					$post_details_array['post_images_array'][] = array(
						'post_image_url' => parent::_prepare_post_image_url('')
					);
				}
				if ($post_details_array['post_show_actual_email'] === '1') {
					$post_details_array['user_email'] = $post_details_array['user_email'];
				} else {
					$post_details_array['user_email'] = parent::mask_characters($post_details_array['user_email']);
				}
				$post_details_array['user_feedback_percentage'] = parent::get_user_feedback_percentage($post_details_array['users_id']);
				$post_details_array['pricing_buttons'] = array();
				$this->_get_pricing_options();
				if ($post_details_array['category_type'] === '1') {
					foreach ($this->item_price_array as $item_price) {
						if ($post_details_array['post_' . $item_price['pricing_option_type'] . '_price'] > 0) {
							$post_details_array['pricing_buttons'][] = array(
								'pricing_options_id' => $item_price['pricing_option_id'],
								'price_button_text' => ucwords($item_price['pricing_option_type']) . ' Price',
								'price_button_value' => $post_details_array['post_' . $item_price['pricing_option_type'] . '_price'],
								'price_button_display_value' => 'US $' . sprintf('%01.2f', $post_details_array['post_' . $item_price['pricing_option_type'] . '_price'])
							);
						}
					}
				} else {
					foreach ($this->package_deals_array as $package_deal) {
						if ($post_details_array['post_deal_price_' . $package_deal['pricing_option_type']] > 0) {
							$post_details_array['pricing_buttons'][] = array(
								'pricing_options_id' => $package_deal['pricing_option_id'],
								'price_button_text' => $package_deal['pricing_option_name'],
								'price_button_value' => $post_details_array['post_deal_price_' . $package_deal['pricing_option_type']],
								'price_button_display_value' => 'US $' . sprintf('%01.2f', $post_details_array['post_deal_price_' . $package_deal['pricing_option_type']])
							);
						}
					}
				}
				$post_details_array['post_time_availability_array'] = array();
				foreach ($post_time_availability_array as $post_time_availability) {
					$post_details_array['post_time_availability_array'][] = array(
						'post_availability_day' => date('l', strtotime("Sunday + " . $post_time_availability['post_availability_day'] . " Days")),
						'post_availability_from' => date('h:i A', strtotime($post_time_availability['post_availability_from'])),
						'post_availability_to' => date('h:i A', strtotime($post_time_availability['post_availability_to'])),
						'post_availability_current_day' => (date('N') === $post_time_availability['post_availability_day']),
						'post_availabile_current_time' => (date('N') === $post_time_availability['post_availability_day'] && time() < strtotime(date('Y-m-d ') . $post_time_availability['post_availability_to']) && time() > strtotime(date('Y-m-d ') . $post_time_availability['post_availability_from']))
					);
				}
				parent::json_output(array('code' => '1', 'message' => 'Success Fetching Post.', 'data' => $post_details_array));
				return;
			}
		}
		$this->_error();
	}

	function strings() {
		$data = array();
		$data['signup_info'] = "By creating an account, you will be able to rent and/or buy services, you can also rent out items and provide services yourself, pay with your credit card, keep track of your account and transactions, and receive and give feedback on all your transactions.";
		$data['about_us'] = $this->_trim_html($this->load->view('pages/about_us', array(), TRUE));
		$data['how_does_it_work'] = $this->_trim_html($this->load->view('pages/how_does_it_work', array(), TRUE));
		$data['faq'] = $this->_trim_html($this->load->view('pages/faq', array(), TRUE));
		$data['contact_us'] = $this->_trim_html($this->load->view('pages/contact_us', array(), TRUE));
		$data['stories'] = $this->_trim_html($this->load->view('pages/stories', array(), TRUE));
		$data['feedback'] = $this->_trim_html($this->load->view('pages/feedback', array(), TRUE));
		$data['privacy'] = $this->_trim_html($this->load->view('pages/privacy', array(), TRUE));
		$data['terms'] = $this->_trim_html($this->load->view('pages/terms', array(), TRUE));
		parent::json_output(array('code' => '1', 'message' => 'Success Fetching Data.', 'data' => $data));
		return;
	}

	function update_credit_card() {
		$user_details_array = $this->_get_user();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_credit_card_name', 'Credit Card Name', 'trim|required');
		$this->form_validation->set_rules('user_credit_card_number', 'Credit Card Number', 'trim|required|callback__validate_unique_credit_card');
		$this->form_validation->set_rules('user_credit_card_expiry_month', 'Expiry Month', 'trim|required');
		$this->form_validation->set_rules('user_credit_card_expiry_year', 'Expiry Year', 'trim|required');
		$this->form_validation->set_rules('user_credit_card_cvv', 'CVV Number', 'trim|required');
		$this->form_validation->set_error_delimiters("", "|");
		if ($this->form_validation->run()) {
			$this->load->library('encrypt');
			$user_update_array = array(
				'user_credit_card_name' => $this->encrypt->encode($this->input->post('user_credit_card_name')),
				'user_credit_card_number' => $this->encrypt->encode($this->input->post('user_credit_card_number')),
				'user_credit_card_number_md5' => md5($this->input->post('user_credit_card_number')),
				'user_credit_card_expiry_month' => $this->encrypt->encode($this->input->post('user_credit_card_expiry_month')),
				'user_credit_card_expiry_year' => $this->encrypt->encode($this->input->post('user_credit_card_expiry_year')),
				'user_credit_card_cvv' => $this->encrypt->encode($this->input->post('user_credit_card_cvv')),
				'user_credit_card_verified' => '1',
				'user_credit_card_verified_on' => date('Y-m-d H:i:s'),
				'user_modified' => date('Y-m-d H:i:s')
			);
			$purchase_response = parent::braintree_cc_purchase('1.00', $this->input->post('user_credit_card_name'), $this->input->post('user_credit_card_expiry_month'), $this->input->post('user_credit_card_expiry_year'), $this->input->post('user_credit_card_number'), $this->input->post('user_credit_card_cvv'));
			if (isset($purchase_response->success) && $purchase_response->success) {
				if ($this->User_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
					parent::json_output(array('code' => '1', 'message' => 'Credit Card Updated Succesfully.', 'data' => $this->_get_user()));
					return;
				}
			}
		} else {
			parent::json_output(array('code' => '0', 'message' => 'Error while updating credit card.', 'data' => explode('|', validation_errors())));
			return;
		}
		$this->_error();
	}

	function countries() {
		parent::_create_cache();
		$this->load->model('Country_model');
		parent::json_output(array('code' => '1', 'message' => 'Countries Fetched Successfully.', 'data' => $this->Country_model->get_all_active_countries()));
		return;
	}

	function states() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('countries_id', 'State ID', 'trim|required|is_natural_no_zero');
		if ($this->form_validation->run()) {
			$this->load->model('State_model');
			parent::json_output(array('code' => '1', 'message' => 'States Fetched Successfully.', 'data' => $this->State_model->get_active_states_by_country_id($this->input->post('countries_id'))));
			return;
		}
		$this->_error();
	}

	function cities() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('states_id', 'State ID', 'trim|required|is_natural_no_zero');
		if ($this->form_validation->run()) {
			$this->load->model('City_model');
			parent::json_output(array('code' => '1', 'message' => 'Cities Fetched Successfully.', 'data' => $this->City_model->get_active_cities_by_state_id($this->input->post('states_id'))));
			return;
		}
		$this->_error();
	}

	function purchase() {
		$user_details_array = $this->_get_user();
		if (count($user_details_array) > 0) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			$this->form_validation->set_rules('posts_id', 'Post id', 'trim|required');
			$this->form_validation->set_rules('pricing_options_id', 'Pricing Option', 'trim|required|is_natural_no_zero');
			if ($this->form_validation->run()) {
				$this->load->model('Post_model');
				$post_details_array = $this->Post_model->get_post_row_by_id($this->input->post('posts_id'));
				if (count($post_details_array) > 0) {
					$invoice_insert_array = array(
						'invoice_amount' => $this->input->post('amount'),
						'posts_id' => $this->input->post('posts_id'),
						'invoice_by_users_id' => $post_details_array['users_id'],
						'invoice_to_users_id' => $user_details_array['user_id'],
						'pricing_options_id' => $this->input->post('pricing_options_id'),
						'invoice_status' => '0',
						'invoice_currency' => 'USD',
						'invoice_created' => date('Y-m-d H:i:s')
					);
					$this->load->model('Invoice_model');
					$invoice_id = $this->Invoice_model->create($invoice_insert_array);
					if (is_numeric($invoice_id)) {
						$seller_details_array = $this->Invoice_model->get_seller_by_invoice_id($invoice_id);
						$this->load->library('encrypt');
						$user_row_details_array = $this->User_model->get_user_row_by_user_id($user_details_array['user_id']);
						if (isset($user_row_details_array['user_credit_card_number']) && $this->encrypt->decode($user_row_details_array['user_credit_card_number']) !== '') {
							$user_details_array['user_credit_card_number'] = $user_row_details_array['user_credit_card_number'];
							$purchase_response = parent::braintree_cc_sale($invoice_id, $seller_details_array['user_braintree_merchant_id'], $this->input->post('amount'), $this->encrypt->decode($user_details_array['user_credit_card_name']), $this->encrypt->decode($user_details_array['user_credit_card_expiry_month']), $this->encrypt->decode($user_details_array['user_credit_card_expiry_year']), $this->encrypt->decode($user_details_array['user_credit_card_number']), $this->encrypt->decode($user_details_array['user_credit_card_cvv']));
							if (isset($purchase_response->success) && $purchase_response->success) {
								$invoice_update_array = array(
									'invoice_transaction_id' => $purchase_response->transaction->_attributes['id'],
									'invoice_blendedd_amount' => $purchase_response->transaction->_attributes['serviceFeeAmount'],
									'invoice_merchant_amount' => $purchase_response->transaction->_attributes['amount'] - $purchase_response->transaction->_attributes['serviceFeeAmount'],
									'invoice_currency' => $purchase_response->transaction->_attributes['currencyIsoCode'],
									'invoice_status' => '1',
									'invoice_paid_on' => date('Y-m-d H:i:s'),
								);
								if ($this->Invoice_model->update($invoice_id, $invoice_update_array)) {
									if ($seller_details_array['category_type'] === '1') {
										$this->Post_model->update($seller_details_array['post_id'], array('post_status' => '0', 'post_modified' => date('Y-m-d H:i:s')));
									}
									$seller_details_array['post_url'] = base_url() . 'post/view/' . $seller_details_array['post_slug'] . '/' . $seller_details_array['post_id'];
									$buyer_details_array = $user_details_array;
									$email_details_array = array(
										'buyer_full_name' => $buyer_details_array['user_first_name'] . ' ' . $buyer_details_array['user_last_name'],
										'buyer_user_login' => $buyer_details_array['user_login'],
										'seller_full_name' => $seller_details_array['user_first_name'] . ' ' . $seller_details_array['user_last_name'],
										'seller_user_login' => $seller_details_array['user_login'],
										'post_url' => $seller_details_array['post_url'],
										'invoice_amount' => $seller_details_array['invoice_amount']
									);
									if ($seller_details_array['user_communication_via_email'] === '1') {
										$email_id = parent::add_email_to_queue('', '', $seller_details_array['user_email'], $seller_details_array['user_id'], 'Payment from Blendedd', $this->render_view($email_details_array, 'emails', 'emails/templates/seller_invoice_paid', TRUE));
										if ($email_id > 0) {
											file_get_contents(base_url() . 'emails/cron/' . $email_id);
										}
									}
									$buyer_details_array['post_url'] = $seller_details_array['post_url'];
									$buyer_details_array['invoice_amount'] = $seller_details_array['invoice_amount'];
									if ($buyer_details_array['user_communication_via_sms'] === '1') {
										parent::send_sms(str_replace('-', '', $buyer_details_array['user_primary_contact']), "Congratulations. Thank you for your transaction with Blendedd");
									}
									if ($buyer_details_array['user_communication_via_email'] === '1') {
										$email_id = parent::add_email_to_queue('', '', $buyer_details_array['user_email'], $buyer_details_array['user_id'], 'Invoice Payment Successful', $this->render_view($email_details_array, 'emails', 'emails/templates/buyer_invoice_paid', TRUE));
										if ($email_id > 0) {
											file_get_contents(base_url() . 'emails/cron/' . $email_id);
										}
									}
									$email_id = parent::add_email_to_queue('', '', $this->config->item('admin_email'), '1', 'Invoice Paid By User', $this->render_view($email_details_array, 'emails', 'emails/templates/admin_invoice_paid', TRUE));
									if ($email_id > 0) {
										file_get_contents(base_url() . 'emails/cron/' . $email_id);
									}
									// Update User Rating on the basis of the total sales.
									$user_total_sales = $this->Invoice_model->get_paid_invoices_count_by_user_id($user_details_array['user_id']);
									$user_rating = 0;
									if ($user_total_sales > 0) {
										$user_rating++;
									}
									if ($user_total_sales > 99) {
										$user_rating++;
									}
									if ($user_total_sales > 999) {
										$user_rating++;
									}
									if ($user_total_sales > 99999) {
										$user_rating++;
									}
									if ($user_total_sales > 249999) {
										$user_rating++;
									}
									if ($user_total_sales > 499999) {
										$user_rating++;
									}
									if ($user_total_sales > 999999) {
										$user_rating++;
									}
									if ($user_total_sales > 1000000) {
										$user_rating++;
									}
									$this->User_model->edit_user_by_user_id($user_details_array['user_id'], array('user_rating' => $user_rating, 'user_modified' => date('Y-m-d H:i:s')));
									$user_total_sales = $this->Invoice_model->get_paid_invoices_count_by_user_id($seller_details_array['users_id']);
									$user_rating = 0;
									if ($user_total_sales > 0) {
										$user_rating++;
									}
									if ($user_total_sales > 99) {
										$user_rating++;
									}
									if ($user_total_sales > 999) {
										$user_rating++;
									}
									if ($user_total_sales > 99999) {
										$user_rating++;
									}
									if ($user_total_sales > 249999) {
										$user_rating++;
									}
									if ($user_total_sales > 499999) {
										$user_rating++;
									}
									if ($user_total_sales > 999999) {
										$user_rating++;
									}
									if ($user_total_sales > 1000000) {
										$user_rating++;
									}
									$this->User_model->edit_user_by_user_id($seller_details_array['users_id'], array('user_rating' => $user_rating, 'user_modified' => date('Y-m-d H:i:s')));
									parent::json_output(array('code' => '1', 'message' => 'Payment is Successful.', 'data' => $this->_get_user()));
									return;
								} else {
									parent::json_output(array('code' => '0', 'message' => 'Something Went Wrong.', 'data' => array()));
									return;
								}
							} else {
								parent::json_output(array('code' => '0', 'message' => 'Error Making Payment.', 'data' => $purchase_response->_attributes['message']));
								return;
							}
						}
					}
				}
			}
		}
		$this->_error();
	}

}
