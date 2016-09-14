<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feedback_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function get_awaiting_feedback_posts($user_id) {
        return $this->db->query('SELECT `posts`.*, `invoices`.*, `u1`.`user_login` AS invoice_by_user_login, `u2`.`user_login` AS invoice_to_user_login FROM (`invoices`) JOIN `posts` ON `posts`.`post_id` = `invoices`.`posts_id` LEFT JOIN `feedbacks` ON `feedbacks`.`invoices_id` = `invoices`.`invoice_id` INNER JOIN `users` u1 ON `u1`.`user_id` = `invoices`.`invoice_by_users_id` INNER JOIN `users` u2 ON `u2`.`user_id` = `invoices`.`invoice_to_users_id` WHERE (`invoices`.`invoice_to_users_id` = ' . $user_id . ' OR `invoices`.`invoice_by_users_id` = ' . $user_id . ') AND `invoices`.`invoice_status` = 1 AND `invoices`.`invoice_id` NOT IN (SELECT `invoices_id` FROM `feedbacks` WHERE `feedbacks`.`feedback_by_users_id` = ' . $user_id . ')')->result_array();
    }

    function get_awaiting_feedback_by_user_id_and_invoice_id($user_id, $invoice_id) {
        return $this->db->query('SELECT `posts`.*, `invoices`.*, `u1`.`user_login` AS invoice_by_user_login, `u2`.`user_login` AS invoice_to_user_login FROM (`invoices`) JOIN `posts` ON `posts`.`post_id` = `invoices`.`posts_id` LEFT JOIN `feedbacks` ON `feedbacks`.`invoices_id` = `invoices`.`invoice_id` INNER JOIN `users` u1 ON `u1`.`user_id` = `invoices`.`invoice_by_users_id` INNER JOIN `users` u2 ON `u2`.`user_id` = `invoices`.`invoice_to_users_id` WHERE (`invoices`.`invoice_to_users_id` = ' . $user_id . ' OR `invoices`.`invoice_by_users_id` = ' . $user_id . ') AND `invoices`.`invoice_id` = ' . $invoice_id . ' AND `invoices`.`invoice_status` = 1 AND `invoices`.`invoice_id` NOT IN (SELECT `invoices_id` FROM `feedbacks` WHERE `feedbacks`.`feedback_by_users_id` = ' . $user_id . ')')->result_array();
    }

    function create($feedback_insert_array) {
        return $this->db->insert('feedbacks', $feedback_insert_array);
    }

    function get_feedback_ratings_by_user_id($user_id) {
        $this->db->select_sum('feedback_rating');
        return $this->db->get_where('feedbacks', array('feedback_to_users_id' => $user_id, 'feedback_status' => '1'))->row_array();
    }

    function get_received_feedbacks_by_user_id($user_id) {
        return $this->db->get_where('feedbacks', array('feedback_to_users_id' => $user_id, 'feedback_status' => '1'))->result_array();
    }

    function get_negative_feedback_counts_by_user_id($user_id) {
        $this->db->select('feedback_id')->from('feedbacks')->where(array('feedback_to_users_id' => $user_id, 'feedback_status' => '1', 'feedback_rating' => '-1'));
        return $this->db->count_all_results();
    }

    function get_positive_feedback_counts_by_user_id($user_id) {
        $this->db->select('feedback_id')->from('feedbacks')->where(array('feedback_to_users_id' => $user_id, 'feedback_status' => '1', 'feedback_rating' => '1'));
        return $this->db->count_all_results();
    }

}
