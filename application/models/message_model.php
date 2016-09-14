<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Message_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	function get_messages_by_post_id($post_id) {
		$this->db->select("posts.post_id,post_title,user_login,message_body,DATE_FORMAT(message_created, '%e %b %Y %h:%i %p') AS message_created", FALSE);
		$this->db->join('posts', 'posts.post_id = messages.posts_id');
		$this->db->join('users', 'users.user_id = messages.message_by_users_id');
		$this->db->order_by('message_id', 'desc');
		return $this->db->get_where('messages', array('posts_id' => $post_id))->result_array();
	}

	function create($message_insert_array) {
		return $this->db->insert('messages', $message_insert_array);
	}

	function update_inbox_message_by_post_id($post_ids_array, $message_to_users_id, $message_update_array) {
		$this->db->where_in('posts_id', $post_ids_array);
		$this->db->where('message_to_users_id', $message_to_users_id);
		return $this->db->update('messages', $message_update_array);
	}

	function update_sent_message_by_post_id($post_ids_array, $message_by_users_id, $message_update_array) {
		$this->db->where_in('posts_id', $post_ids_array);
		$this->db->where('message_by_users_id', $message_by_users_id);
		return $this->db->update('messages', $message_update_array);
	}

	function mark_inbox_messages_deleted_by_post_id($post_ids_array, $message_update_array) {
		$this->db->where_in('posts_id', $post_ids_array);
		return $this->db->update('messages', $message_update_array);
	}

	function mark_sent_messages_deleted_by_post_id($post_ids_array, $message_update_array) {
		$this->db->where_in('posts_id', $post_ids_array);
		return $this->db->update('messages', $message_update_array);
	}

}
