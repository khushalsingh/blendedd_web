<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
date_default_timezone_set('America/Los_Angeles');

function pr($data) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	die;
}

class MY_Controller extends CI_Controller {

	public $public_methods = array();

	function __construct() {
		parent::__construct();
		if ($this->router->class !== 'api') {
			session_start();
		}
		if (ENVIRONMENT === 'development' && !$this->input->is_ajax_request() && !$this->input->is_cli_request()) {
//			$this->output->enable_profiler(TRUE);
		}
		if (!in_array($this->router->method, $this->public_methods) && !$this->check_auth() && !in_array($this->router->class, array('pages', 'api', 'cron'))) {
			$this->session->set_userdata('redirect_url', base_url() . $this->uri->uri_string);
			redirect('login', 'refresh');
		}
		if (isset($_SESSION['user']) && isset($_SESSION['user']['force_change_password']) && $_SESSION['user']['force_change_password'] == 1) {
			if ($this->uri->uri_string != 'account/change_password' && $this->uri->uri_string != 'auth/logout') {
				redirect('account/change_password', 'refresh');
			}
		}
	}

	function _create_cache() {
		if (ENVIRONMENT === 'production') {
			$this->output->cache(1440);
		}
	}

	function render_view($data = array(), $template = '', $view = '', $get_string = FALSE) {
		if ($template === '') {
			$template = 'common';
			if (isset($_SESSION['user']['group_slug'])) {
				$template = 'system';
			}
		}
		if ($view === '' && $this->router->directory === '') {
			$view = $this->router->class . '/' . $this->router->method;
		} else {
			if ($view === '') {
				$view = $this->router->class . '/' . $this->router->method;
			}
		}
		if ($get_string) {
			$return = $this->load->view('templates/' . $template . '/header', $data, TRUE);
			$return .= $this->load->view('templates/' . $template . '/menu', $data, TRUE);
			$return .= $this->load->view($view, $data, TRUE);
			$return .= $this->load->view('templates/' . $template . '/footer', $data, TRUE);
			return $return;
		}
		$this->load->view('templates/' . $template . '/header', $data);
		if ($this->router->class . '/' . $this->router->method !== 'pages/index') {
			$this->load->view('templates/common/noscript', $data);
		}
		$this->load->view('templates/' . $template . '/menu', $data);
		$this->load->view($view, $data);
		$this->load->view('templates/' . $template . '/footer', $data);
		return;
	}

	function json_output($data) {
		$this->output->enable_profiler(FALSE);
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	function check_auth() {
		if (isset($_SESSION['user'])) {
			return TRUE;
		} else {
			if (strpos($this->uri->uri_string, '/auth') !== FALSE) {
				$this->session->set_userdata('redirect_uri', $this->uri->uri_string);
				redirect('login', 'refresh');
			}
		}
		return FALSE;
	}

	function regenerate_session() {
		if (isset($_SESSION['user']['user_id'])) {
			$this->load->model('Auth_model');
			$_SESSION['user'] = $this->Auth_model->get_user_by_id($_SESSION['user']['user_id']);
			return TRUE;
		}
		return FALSE;
	}

	function generate_dropdown_array($array, $key, $value) {
		$return = array();
		$return[''] = '';
		foreach ($array as $result) {
			$return[$result[$key]] = $result[$value];
		}
		return $return;
	}

	/**
	 * Function used to send email via cron jobs.
	 */
	function send_email($email_from, $email_from_name, $email_to, $email_subject, $email_message, $email_cc_array = array(), $email_bcc_array = array(), $attachments_array = array()) {
		$this->load->config('email');
		if ($this->config->item('email_smtp') === TRUE) {
			include_once(FCPATH . 'application/libraries/Mandrill.php');
			$mandrill = new Mandrill($this->config->item('smtp_pass'));
			$message = array(
				'html' => $email_message,
				'subject' => $email_subject,
				'from_email' => $email_from,
				'from_name' => $email_from_name,
				'to' => array(
					array('email' => $email_to, 'type' => 'to')
				),
				'headers' => array('Reply-To' => $email_from)
			);
			$result = $mandrill->messages->send($message, TRUE);
			if (isset($result[0]['_id']) && $result[0]['_id']) {
				return $result[0]['_id'];
			}
		} else {
			$this->load->library('email');
			$config['mailtype'] = $this->config->item('mailtype');
			$config['crlf'] = $this->config->item('crlf');
			$config['newline'] = $this->config->item('newline');
			$config['wordwrap'] = $this->config->item('wordwrap');
			$this->email->initialize($config);
			$this->email->from($this->config->item('email_from'), $this->config->item('email_from_name'));
			$this->email->to($email_to);
			$this->email->subject($email_subject);
			$this->email->message($email_message);
			foreach ($attachments_array as $attachment) {
				$this->email->attach($attachment);
			}
			if ($this->email->send()) {
				return 'sendmail';
			}
		}
		return '';
	}

	/**
	 * Adds Email to queue in database in emails table
	 */
	function add_email_to_queue($email_from, $email_from_name, $email_to, $users_id, $email_subject, $email_message, $email_cc_array = array(), $email_bcc_array = array(), $attachments_array = array()) {
		$this->config->load('email');
		$this->load->library('email');
		$email_insert_array = array(
			'email_hash' => md5($email_from . microtime() . $email_to),
			'email_mandrill_id' => '',
			'email_from' => ($email_from !== '') ? $email_from : $this->config->item('email_from'),
			'email_from_name' => ($email_from_name !== '') ? $email_from_name : $this->config->item('email_from_name'),
			'users_id' => $users_id,
			'email_to' => $email_to,
			'email_cc' => json_encode($email_cc_array),
			'email_bcc' => json_encode(array($this->config->item('email_bcc'))),
			'email_subject' => $email_subject,
			'email_body' => $email_message,
			'email_status' => '0',
			'email_created' => date('Y-m-d H:i:s')
		);
		$this->load->model('Email_model');
		return $this->Email_model->add_email_to_queue($email_insert_array);
	}

	/**
	 * Function to send SMS via Twilio API
	 * @param string $sms_to
	 * @param string $sms_body
	 * @return boolean
	 */
	function send_sms($sms_to, $sms_body) {
		if (in_array(ENVIRONMENT, array('testing', 'production'))) {
			$this->load->config('twilio');
			include_once(FCPATH . 'application/libraries/Twilio.php');
			$http = new Services_Twilio_TinyHttp(
					'https://api.twilio.com', array('curlopts' => array(
					CURLOPT_SSL_VERIFYHOST => 0,
					CURLOPT_SSL_VERIFYPEER => 0,
				))
			);
			$twilio = new Services_Twilio($this->config->item('twilio_sid'), $this->config->item('twilio_token'), "2010-04-01", $http);
			try {
				@$twilio->account->messages->sendMessage($this->config->item('twilio_from'), '+1' . $sms_to, $sms_body);
				return $twilio;
			} catch (Exception $e) {
				return FALSE;
			}
		}
	}

	function upload_files() {
		/**
		 * upload.php
		 *
		 * Copyright 2013, Moxiecode Systems AB
		 * Released under GPL License.
		 *
		 * License: http://www.plupload.com/license
		 * Contributing: http://www.plupload.com/contributing
		 */
// Make sure file is not cached (as it happens for example on iOS devices)
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

// 5 minutes execution time
		@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);
// Settings
		$targetDir = FCPATH . "uploads/";
//$targetDir = 'uploads';
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 1800; // Temp file age in seconds
// Create target dir
		if (!file_exists($targetDir)) {
			@mkdir($targetDir);
		}
		// Clean Up Old Files
		$current_dir = @opendir($targetDir);
		while ($filename = @readdir($current_dir)) {
			if ($filename != "." and $filename != ".." and $filename != "index.html" and $filename != ".htaccess") {
				if (is_file($targetDir . $filename) && filemtime($targetDir . $filename) < time() - $maxFileAge) {
					@unlink($targetDir . $filename);
				}
			}
		}
		@closedir($current_dir);
		// Clean Up Old Files End
		// Get a file name
		if (isset($_REQUEST["name"])) {
			$fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
			$fileName = $_FILES["file"]["name"];
		} else {
			$fileName = uniqid("file_");
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;


// Remove old temp files
		if ($cleanupTargetDir) {
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}

			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}.part") {
					continue;
				}

				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}


// Open temp file
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}

		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}

			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		} else {
			if (!$in = @fopen("php://input", "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		}

		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}

		@fclose($out);
		@fclose($in);

// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off
			rename("{$filePath}.part", $filePath);
		}

// Return Success JSON-RPC response
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	}

	function get_pagination($base_url, $counter_position, $total_rows, $per_page = NULL, $prefix = '', $suffix = '', $is_ajax = FALSE, $div_id = '', $show_count = FALSE, $additional_param = '') {
		if ($is_ajax === TRUE) {
			$this->load->library('Jquery_pagination');
			$config['div'] = '#' . $div_id;
			$config['show_count'] = $show_count;
			$config['additional_param'] = $additional_param;
		} else {
			$this->load->library('pagination');
		}
		$config['base_url'] = $base_url;
		$config['prefix'] = $prefix;
		$config['suffix'] = $suffix;
		$config['uri_segment'] = $counter_position;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_link'] = '&laquo; First';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a class="number current" href="javascript:;">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next &raquo;';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '&laquo; Previous';
		$config['prev_tag_close'] = '</li>';
		$config['total_rows'] = $total_rows;
		$config['anchor_class'] = 'class="" ';
		if ($per_page != NULL) {
			$config['per_page'] = $per_page;
		} else {
			$config['per_page'] = '10';
		}
		if ($is_ajax === TRUE) {
			$this->jquery_pagination->initialize($config);
			if ($this->jquery_pagination->create_links() != '') {
				return $this->jquery_pagination->create_links();
			}
		} else {
			$this->pagination->initialize($config);
			if ($this->pagination->create_links() != '') {
				return $this->pagination->create_links();
			}
		}
		return;
	}

	function mask_characters($string, $length = 6, $character = '*') {
		return substr_replace($string, str_repeat($character, $length), 0, $length);
	}

	function create_captcha() {
		$this->load->helper('captcha');
		$this->load->helper('string');
		$random_string = random_string('numeric', 6);
		$captcha_array = array(
			'word' => $random_string,
			'img_path' => FCPATH . 'captcha/',
			'img_url' => base_url() . 'captcha/',
			'font_path' => BASEPATH . 'fonts/texb.ttf',
			'img_width' => '150',
			'img_height' => '30',
			'expiration' => 300
		);
		$captcha = create_captcha($captcha_array);
		$_SESSION['captcha_image'] = $random_string;
		return $captcha['image'];
	}

	function validate_captcha($captcha_image) {
		if (isset($_SESSION['captcha_image']) && $captcha_image === $_SESSION['captcha_image']) {
			unset($_SESSION['captcha_image']);
			return TRUE;
		}
		$this->form_validation->set_message('validate_captcha', 'The %s is not correct.');
		return FALSE;
	}

	function allow($allowed_groups) {
		if (!in_array($_SESSION['user']['group_slug'], $allowed_groups)) {
			redirect('dashboard');
		}
	}

	function generate_random_string($type = 'alnum', $length = 6) {
		switch ($type) {
			case 'alpha' : $pool = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
				break;
			case 'alnum' : $pool = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
				break;
			case 'numeric' : $pool = '23456789';
				break;
		}
		$str = '';
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
		}
		return $str;
	}

	function _prepare_post_image_url($post_array) {
		$post_image_url = base_url() . 'assets/images/no-image-146x146.gif';
		if (isset($post_array['post_image_name']) && $post_array['post_image_name'] !== '') {
			$post_image_name_array = explode('.', $post_array['post_image_name']);
			$extension = array_pop($post_image_name_array);
			if (is_file(FCPATH . 'uploads/posts' . date('/Y/m/d/H/i/s/', strtotime($post_array['post_created'])) . implode('.', $post_image_name_array) . '_thumb.' . $extension)) {
				$post_image_url = base_url() . 'uploads/posts' . date('/Y/m/d/H/i/s/', strtotime($post_array['post_created'])) . implode('.', $post_image_name_array) . '_thumb.' . $extension;
			}
		}
		return $post_image_url;
	}

	function _validate_password($password) {
		if (strpos($password, ' ') !== FALSE) {
			$this->form_validation->set_message('_validate_password', 'The %s must not contain spaces.');
			return FALSE;
		}
		$success_count = 0;
		$special_characters = '~!@#$%^&*()_+`=[]\\{}|;\':";,./<>?';
		$check_special_characters = FALSE;
		for ($i = 0; $i < strlen($special_characters); $i++) {
			if (strpos($password, $special_characters[$i]) !== FALSE) {
				$check_special_characters = TRUE;
				$success_count++;
				break;
			}
		}
		$special_numeric = '0123456789';
		$check_numeric = FALSE;
		for ($i = 0; $i < strlen($special_numeric); $i++) {
			if (strpos($password, $special_numeric[$i]) !== FALSE) {
				$check_numeric = TRUE;
				$success_count++;
				break;
			}
		}
		$upper_case_characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$check_upper_case_character = FALSE;
		for ($i = 0; $i < strlen($upper_case_characters); $i++) {
			if (strpos($password, $upper_case_characters[$i]) !== FALSE) {
				$check_upper_case_character = TRUE;
				$success_count++;
				break;
			}
		}
		$lower_case_characters = 'abcdefghijklmnopqrstuvwxyz';
		$check_lower_case_character = FALSE;
		for ($i = 0; $i < strlen($lower_case_characters); $i++) {
			if (strpos($password, $lower_case_characters[$i]) !== FALSE) {
				$check_lower_case_character = TRUE;
				$success_count++;
				break;
			}
		}
		if ($success_count < 2) {
			$this->form_validation->set_message('_validate_password', 'Password must have at least 8 characters and contain at least two of the following: uppercase letters, lower case letters, number, and symbols.');
			return FALSE;
		}
		return TRUE;
	}

	function paypal_payout($receiver_email, $sender_batch_id, $email_subject, $custom_note) {
		$this->load->config('paypal');
		$config = array(
			'paypal_api_url' => $this->config->item('paypal_api_url'),
			'paypal_api_client_id' => $this->config->item('paypal_api_client_id'),
			'paypal_api_client_secret' => $this->config->item('paypal_api_client_secret'),
		);
		include_once(FCPATH . 'application/libraries/PayPal.php');
		$paypal = new PayPal($config);
		return $paypal->payout($receiver_email, $sender_batch_id, $email_subject, $custom_note);
	}

	function firstdata_cc_purchase($customer_ref, $invoice_id, $amount, $cardholder_name, $cc_number, $cc_expiry) {
		$this->load->config('firstdata');
		$config = array(
			'host' => $this->config->item('firstdata_host'),
			'protocol' => $this->config->item('firstdata_protocol'),
			'uri' => $this->config->item('firstdata_uri'),
			'hmackey' => $this->config->item('firstdata_hmackey'),
			'keyid' => $this->config->item('firstdata_keyid'),
			'gatewayid' => $this->config->item('firstdata_gatewayid'),
			'password' => $this->config->item('firstdata_password')
		);
		include_once(FCPATH . 'application/libraries/FirstData.php');
		$firstdata = new FirstData($config);
		return $firstdata->cc_purchase($customer_ref, $invoice_id, $amount, $cardholder_name, $cc_number, $cc_expiry);
	}

	function braintree_add_merchant($user_type = 'individual', $user_first_name, $user_last_name, $user_email, $user_dob, $user_ssn, $user_address_line_1, $user_address_line_2, $city_name, $state_code, $user_zipcode, $user_bank_account_number, $user_bank_route_code, $business_legal_name = '', $business_tax_id = '') {
		$this->load->config('braintree');
		include_once (FCPATH . 'application/libraries/Braintree.php');
		Braintree_Configuration::environment($this->config->item('environment'));
		Braintree_Configuration::merchantId($this->config->item('merchantId'));
		Braintree_Configuration::publicKey($this->config->item('publicKey'));
		Braintree_Configuration::privateKey($this->config->item('privateKey'));
		$user_details_array = array(
			'individual' => array(
				'firstName' => $user_first_name,
				'lastName' => $user_last_name,
				'email' => $user_email,
				'dateOfBirth' => $user_dob,
				'ssn' => $user_ssn
			),
			'business' => array(
				'legalName' => $business_legal_name,
				'taxId' => $business_tax_id
			),
			'funding' => array(
				'destination' => Braintree_MerchantAccount::FUNDING_DESTINATION_BANK,
				'email' => $user_email,
				'accountNumber' => $user_bank_account_number,
				'routingNumber' => $user_bank_route_code
			),
			'tosAccepted' => true,
			'masterMerchantAccountId' => $this->config->item('masterMerchantAccountId')
		);
		$user_details_array[$user_type]['address'] = array(
			'streetAddress' => $user_address_line_1 . ' ' . $user_address_line_2,
			'locality' => $city_name,
			'region' => $state_code,
			'postalCode' => $user_zipcode
		);
		if ($user_type === 'business') {
			$user_details_array['individual']['address'] = $user_details_array[$user_type]['address'];
		}
		return Braintree_MerchantAccount::create($user_details_array);
	}

	function braintree_cc_purchase($amount, $cardholder_name, $cc_expiry_month, $cc_expiry_year, $cc_number, $cc_cvv) {
		$this->load->config('braintree');
		include_once (FCPATH . 'application/libraries/Braintree.php');
		Braintree_Configuration::environment($this->config->item('environment'));
		Braintree_Configuration::merchantId($this->config->item('merchantId'));
		Braintree_Configuration::publicKey($this->config->item('publicKey'));
		Braintree_Configuration::privateKey($this->config->item('privateKey'));
		return Braintree_Transaction::sale(array(
					'amount' => $amount,
					'options' => array(
						'submitForSettlement' => TRUE
					),
					'creditCard' => array(
						'cardholderName' => $cardholder_name,
						'cvv' => $cc_cvv,
						'expirationMonth' => $cc_expiry_month,
						'expirationYear' => $cc_expiry_year,
						'number' => $cc_number
					)
		));
	}

	function braintree_cc_sale($invoice_id, $merchant_id, $amount, $cardholder_name, $cc_expiry_month, $cc_expiry_year, $cc_number, $cc_cvv) {
		$this->load->config('braintree');
		include_once (FCPATH . 'application/libraries/Braintree.php');
		Braintree_Configuration::environment($this->config->item('environment'));
		Braintree_Configuration::merchantId($this->config->item('merchantId'));
		Braintree_Configuration::publicKey($this->config->item('publicKey'));
		Braintree_Configuration::privateKey($this->config->item('privateKey'));
		return Braintree_Transaction::sale(array(
					'amount' => $amount,
					'serviceFeeAmount' => $amount / 10,
					'merchantAccountId' => $merchant_id,
					'orderId' => $invoice_id,
					'options' => array(
						'submitForSettlement' => TRUE,
						'holdInEscrow' => TRUE,
					),
					'creditCard' => array(
						'cardholderName' => $cardholder_name,
						'cvv' => $cc_cvv,
						'expirationMonth' => $cc_expiry_month,
						'expirationYear' => $cc_expiry_year,
						'number' => $cc_number
					)
		));
	}

	function braintree_release_from_escrow($transaction_id) {
		$this->load->config('braintree');
		include_once (FCPATH . 'application/libraries/Braintree.php');
		Braintree_Configuration::environment($this->config->item('environment'));
		Braintree_Configuration::merchantId($this->config->item('merchantId'));
		Braintree_Configuration::publicKey($this->config->item('publicKey'));
		Braintree_Configuration::privateKey($this->config->item('privateKey'));
		return Braintree_Transaction::releaseFromEscrow($transaction_id);
	}

	function _validate_unique_credit_card($str) {
		$this->load->model('Auth_model');
		$user_details_array = $this->Auth_model->get_user_by_credit_card($str);
		if (count($user_details_array) > 0) {
			$this->form_validation->set_message('_validate_unique_credit_card', 'Credit Card Already Used.');
			return FALSE;
		}
		$registration_details_array = $this->Auth_model->get_registration_by_credit_card($str);
		if (count($registration_details_array) > 0) {
			$this->form_validation->set_message('_validate_unique_credit_card', 'Credit Card Already Used.');
			return FALSE;
		}
		return TRUE;
	}

	function _validate_unique_bank_account($str) {
		$this->load->model('Auth_model');
		$user_details_array = $this->Auth_model->get_user_by_bank_name_and_bank_account_number_and_routing_code($this->input->post('user_bank_name'), $this->input->post('user_bank_account_number'), $this->input->post('user_bank_route_code'));
		if (count($user_details_array) > 0) {
			$this->form_validation->set_message('_validate_unique_bank_account', 'Bank account already used.');
			return FALSE;
		}
		$registration_details_array = $this->Auth_model->get_registration_by_bank_name_and_bank_account_number_and_routing_code($this->input->post('user_bank_name'), $this->input->post('user_bank_account_number'), $this->input->post('user_bank_route_code'));
		if (count($registration_details_array) > 0) {
			$this->form_validation->set_message('_validate_unique_bank_account', 'Bank account already used.');
			return FALSE;
		}
		return TRUE;
	}

	function get_user_feedback_percentage($user_id = 0) {
		if ($user_id === 0) {
			$user_id = $_SESSION['user']['user_id'];
		}
		$user_feedback_percentage = 0;
		$this->load->model('Feedback_model');
		$negative_feedbacks = $this->Feedback_model->get_negative_feedback_counts_by_user_id($user_id);
		$positive_feedbacks = $this->Feedback_model->get_positive_feedback_counts_by_user_id($user_id);
		if ($negative_feedbacks == 0 && $positive_feedbacks > 0) {
			$user_feedback_percentage = 100;
		} else if ($negative_feedbacks > 0 && $positive_feedbacks == 0) {
			$user_feedback_percentage = 0;
		} else if ($negative_feedbacks > $positive_feedbacks) {
			$user_feedback_percentage = 0;
		} else if ($positive_feedbacks + $negative_feedbacks > 0) {
			$user_feedback_percentage = ($positive_feedbacks / ($positive_feedbacks + $negative_feedbacks)) * 100;
		} else {
			$user_feedback_percentage = 0;
		}
		return $user_feedback_percentage;
	}

}
