<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
set_time_limit(0);

class Emails extends MY_Controller {

	public $public_methods = array('cron', 'hook');

	function __construct() {
		parent::__construct();
		$this->load->model('Email_model');
	}

	function cron($email_id = 0) {
		$email_details_array = $this->Email_model->get_queued_email($email_id);
		$success_flag = TRUE;
		if ($email_id === 0 && count($email_details_array) > 0) {
			foreach ($email_details_array as $email_details) {
				if ($success_flag === TRUE) {
					$email_mandrill_id = parent::send_email($email_details['email_from'], $email_details['email_from_name'], $email_details['email_to'], $email_details['email_subject'], $email_details['email_body'], json_decode($email_details['email_cc']), json_decode($email_details['email_bcc']));
					if ($email_mandrill_id !== '') {
						$email_update_array = array(
							'email_mandrill_id' => $email_mandrill_id,
							'email_status' => '1',
							'email_modified' => date('Y-m-d H:i:s')
						);
						if ($this->Email_model->update_email_status($email_details['email_id'], $email_update_array)) {
							$success_flag = TRUE;
						} else {
							$success_flag = FALSE;
						}
					}
				}
			}
			if ($success_flag === TRUE) {
				die('1');
			}
			die('0');
		}
		if ($email_id !== 0 && count($email_details_array) > 0) {
			$email_mandrill_id = parent::send_email($email_details_array['email_from'], $email_details_array['email_from_name'], $email_details_array['email_to'], $email_details_array['email_subject'], $email_details_array['email_body'], json_decode($email_details_array['email_cc']), json_decode($email_details_array['email_bcc']));
			if ($email_mandrill_id !== '') {
				$email_update_array = array(
					'email_mandrill_id' => $email_mandrill_id,
					'email_status' => '1',
					'email_modified' => date('Y-m-d H:i:s')
				);
				if ($this->Email_model->update_email_status($email_details_array['email_id'], $email_update_array)) {
					die('1');
				}
			}
		}
		die('0');
	}

	function hook() {
		$event_data = str_replace('mandrill_events=', '', urldecode(file_get_contents('php://input')));
		if ($event_data !== '') {
			$event_object_array = json_decode($event_data);
			$email_update_array = array();
			$event_object = $event_object_array[0];
			unset($event_object_array);
			$email_mandrill_id = $event_object->_id;
			switch ($event_object->event) {
				case 'send':
					$email_update_array['email_sent_on'] = @date('Y-m-d H:i:s', $event_object->ts);
					$email_update_array['email_status'] = '2';
					$email_update_array['email_modified'] = date('Y-m-d H:i:s');
					break;
				case 'open':
					$email_update_array['email_open_ip'] = @$event_object->ip;
					$email_update_array['email_open_country'] = @$event_object->location->country;
					$email_update_array['email_open_city'] = @$event_object->location->city;
					$email_update_array['email_open_count'] = @count($event_object->msg->opens);
					$email_update_array['email_opened_on'] = @date('Y-m-d H:i:s', $event_object->ts);
					$email_update_array['email_click_count'] = @count($event_object->msg->clicks);
					$email_update_array['email_status'] = ((int) $email_update_array['email_click_count'] > 0) ? '4' : '3';
					$email_update_array['email_modified'] = date('Y-m-d H:i:s');
					break;
				case 'click':
					$email_update_array['email_click_ip'] = @$event_object->ip;
					$email_update_array['email_click_country'] = @$event_object->location->country;
					$email_update_array['email_click_city'] = @$event_object->location->city;
					$email_update_array['email_click_count'] = @count($event_object->msg->clicks);
					$email_update_array['email_clicked_on'] = @date('Y-m-d H:i:s', $event_object->ts);
					$email_update_array['email_open_count'] = @count($event_object->msg->opens);
					$email_update_array['email_status'] = '4';
					$email_update_array['email_modified'] = date('Y-m-d H:i:s');
					break;
				case 'hard_bounce':
				case 'soft_bounce':
				case 'reject':
					$email_update_array['email_status'] = '-1';
					$email_update_array['email_modified'] = date('Y-m-d H:i:s');
					break;
				default:
					break;
			}
			if (count($email_update_array) > 0) {
				if ($this->Email_model->update_email_status($email_mandrill_id, $email_update_array)) {
					die('1');
				}
			}
		}
	}

	function download() {
		parent::allow(array('administrator', 'manager'));
		$file_name = 'Email_Report_' . date('d-M-Y-h-i-s-a');
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=' . $file_name . '.xls');
		$result_set = @mysql_unbuffered_query("SELECT email_id,user_id,user_firm_name,CONCAT(user_salutation,' ',user_first_name,' ',user_last_name) AS user_full_name,GROUP_CONCAT(offer_name),user_mobile,email_to,email_subject,DATE_FORMAT(email_sent_on, '%e %b %Y'),(CASE WHEN email_sent_on = '0000-00-00 00:00:00' THEN '' ELSE DATE_FORMAT(email_sent_on, '%h:%i:%s %p') END),email_open_ip,email_open_country,email_open_city,email_open_count,DATE_FORMAT(email_opened_on, '%e %b %Y'),(CASE WHEN email_opened_on = '0000-00-00 00:00:00' THEN '' ELSE DATE_FORMAT(email_opened_on, '%h:%i:%s %p') END),email_click_ip,email_click_country,email_click_city,email_click_count,DATE_FORMAT(email_clicked_on, '%e %b %Y'),(CASE WHEN email_clicked_on = '0000-00-00 00:00:00' THEN '' ELSE DATE_FORMAT(email_clicked_on, '%h:%i:%s %p') END),DATE_FORMAT(email_created, '%e %b %Y'),(CASE WHEN email_created = '0000-00-00 00:00:00' THEN '' ELSE DATE_FORMAT(email_created, '%h:%i:%s %p') END),(CASE WHEN email_status = -1 THEN 'Bounced' WHEN email_status = 0 THEN 'Queued' WHEN email_status = 1 THEN 'Processed' WHEN email_status = 2 THEN 'Sent' WHEN email_status = 3 THEN 'Opened' WHEN email_status = 4 THEN 'Clicked' ELSE '' END) AS email_status FROM (`emails`) LEFT JOIN users ON users.user_id = emails.users_id LEFT JOIN user_offers ON user_offers.users_id = users.user_id LEFT JOIN offers ON offers.offer_id = user_offers.offers_id GROUP BY email_id ORDER BY email_id DESC");
		$fp = fopen('php://output', 'w');
		fputcsv($fp, array(
			"Record_ID",
			"User_ID",
			"Firm_Name",
			"Partner_Name",
			"Participation",
			"Mobile",
			"Recepient",
			"Subject",
			"Sent_Date",
			"Sent_Time",
			"Open_IP",
			"Open_Country",
			"Open_City",
			"Open_Count",
			"Opened_Date",
			"Opened_Time",
			"Click_IP",
			"Click_Country",
			"Click_City",
			"Click_Count",
			"Clicked_Date",
			"Clicked_Time",
			"Created_Date",
			"Created_Time",
			"Status"
			), "\t");
		while ($row = @mysql_fetch_assoc($result_set)) {
			fputcsv($fp, $row, "\t");
		}
		fclose($fp);
	}

	function logs() {
		parent::allow(array('administrator', 'manager'));
		$data = array();
		$this->render_view($data);
	}

	function datatable() {
		parent::allow(array('administrator', 'manager'));
		$this->load->library('Datatables');
		$this->datatables->select('email_id,email_to,email_subject,email_open_count,email_click_count,DATE_FORMAT(email_created, "%e %b %Y %h:%i:%s %p") AS email_created, email_status', FALSE)->from('emails');
		echo $this->datatables->generate();
	}

	function get_email_details_by_id() {
		parent::allow(array('administrator', 'manager'));
		if ($this->input->post('email_id') && is_numeric($this->input->post('email_id'))) {
			parent::json_output($this->Email_model->get_email_by_id($this->input->post('email_id')));
		}
	}

	function resend_email() {
		parent::allow(array('administrator', 'manager'));
		if ($this->input->post('email_id') && is_numeric($this->input->post('email_id'))) {
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', '<br/>');
			$this->form_validation->set_rules('email_cc', 'Email CC', 'trim|valid_emails');
			if ($this->form_validation->run()) {
				$email_details_array = $this->Email_model->get_email_by_id($this->input->post('email_id'));
				if (count($email_details_array) > 0) {
					$email_id = parent::add_email_to_queue('', '', $email_details_array['email_to'], $email_details_array['users_id'], $email_details_array['email_subject'], $email_details_array['email_body'], $email_cc_array = explode(',', $this->input->post('email_cc')));
					if ($email_id > 0) {
						$file_contents = file_get_contents(base_url() . 'emails/cron/' . $email_id);
						if ($file_contents === '1') {
							die('1');
						}
					}
				}
			} else {
				echo validation_errors();
				die;
			}
		}
		die('0');
	}

}
