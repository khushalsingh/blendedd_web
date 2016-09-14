<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Calendar_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	function get_events_by_user_id($user_id) {
		return $this->db->get_where('calendars', array('users_id' => $user_id, 'event_status' => '1'))->result_array();
	}

	function create($calendar_insert_array) {
		$this->db->insert('calendars', $calendar_insert_array);
		return $this->db->insert_id();
	}

	function update($calender_id, $calendar_update_array) {
		return $this->db->where(array('calendar_id' => $calender_id))->update('calendars', $calendar_update_array);
	}

}
