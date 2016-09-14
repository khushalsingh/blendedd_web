<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Calendar extends MY_Controller {

	public $public_methods = array();

	function __construct() {
		parent::__construct();
		$this->load->model('Calendar_model');
	}

	function index() {
		parent::allow(array('administrator', 'manager', 'head-office', 'region', 'branch', 'dealer'));
		$data = array();
		$events_array = $this->Calendar_model->get_events_by_user_id($_SESSION['user']['user_id']);
		$new_events_array = array();
		foreach ($events_array as $event) {
			if ($event['event_is_full_day'] == "1") {
				$event_is_full_day = true;
			} else {
				$event_is_full_day = false;
			}
			$new_events_array[] = array(
				'id' => $event['calendar_id'],
				'title' => $event['event_name'],
				'start' => $event['event_start'],
				'end' => $event['event_end'],
				'allDay' => $event_is_full_day,
				'backgroundColor' => $event['event_color']
			);
		}
		$data['events_json'] = json_encode($new_events_array);
		$this->render_view($data);
	}

	function create() {
		parent::allow(array('administrator', 'manager', 'head-office', 'region', 'branch', 'dealer'));
		if ($this->input->post()) {
			$this->load->library('Form_validation');
			$this->form_validation->set_rules('title', 'Event Name', 'trim|required');
			$this->form_validation->set_rules('event_start', 'Start Date', 'trim|required');
			$this->form_validation->set_error_delimiters('', '<br/>');
			if ($this->form_validation->run()) {
				$calendar_insert_array = array(
					'users_id' => $_SESSION['user']['user_id'],
					'event_name' => $this->input->post('title'),
					'event_start' => $this->input->post('event_start'),
					'event_end' => $this->input->post('event_end'),
					'event_color' => $this->input->post('event_color'),
					'event_status' => '1',
					'event_created' => date('Y-m-d H:i:s'),
					'event_is_full_day' => ($this->input->post('event_is_full_day')) ? '1' : '0'
				);
				echo $this->Calendar_model->create($calendar_insert_array);
			} else {
				echo validation_errors();
				die;
			}
		}
	}

	function update() {
		parent::allow(array('administrator', 'manager', 'head-office', 'region', 'branch', 'dealer'));
		if ($this->input->post()) {
			$this->load->library('Form_validation');
			$this->form_validation->set_rules('title', 'Event Name', 'trim|required');
			$this->form_validation->set_rules('event_start', 'Start Date', 'trim|required');
			$this->form_validation->set_rules('id', 'Calendar Id', 'trim|required|integer');
			$this->form_validation->set_error_delimiters('', '<br/>');
			if ($this->form_validation->run()) {
				$calender_id = $this->input->post('id');
				$calendar_update_array = array(
					'event_name' => $this->input->post('title'),
					'event_start' => $this->input->post('event_start'),
					'event_end' => $this->input->post('event_end'),
					'event_color' => $this->input->post('event_color'),
					'event_modified' => date('Y-m-d H:i:s'),
					'event_is_full_day' => ($this->input->post('event_is_full_day')) ? '1' : '0'
				);
				if ($this->Calendar_model->update($calender_id, $calendar_update_array)) {
					die('1');
				}
			} else {
				echo validation_errors();
				die;
			}
		}
	}

	function resize() {
		parent::allow(array('administrator', 'manager', 'head-office', 'region', 'branch', 'dealer'));
		if ($this->input->post()) {
			$this->load->library('Form_validation');
			$this->form_validation->set_rules('event_start', 'Start Date', 'trim|required');
			$this->form_validation->set_rules('id', 'Calendar Id', 'trim|required|integer');
			$this->form_validation->set_error_delimiters('', '<br/>');
			if ($this->form_validation->run()) {
				$calender_id = $this->input->post('id');
				$calendar_update_array = array(
					'event_start' => $this->input->post('event_start'),
					'event_end' => $this->input->post('event_end'),
					'event_modified' => date('Y-m-d H:i:s')
				);
				if ($this->Calendar_model->update($calender_id, $calendar_update_array)) {
					die('1');
				}
			} else {
				echo validation_errors();
				die;
			}
		}
	}

	function delete() {
		parent::allow(array('administrator', 'manager', 'head-office', 'region', 'branch', 'dealer'));
		if ($this->input->post()) {
			$this->load->library('Form_validation');
			$this->form_validation->set_rules('id', 'Calendar Id', 'trim|required|integer');
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run()) {
				$calender_id = $this->input->post('id');
				$calender_update_array = array(
					'event_modified' => date('Y-m-d H:i:s'),
					'event_status' => '-1'
				);
				if ($this->Calendar_model->update($calender_id, $calender_update_array)) {
					die('1');
				}
			} else {
				echo validation_errors();
				die;
			}
		}
	}

}
