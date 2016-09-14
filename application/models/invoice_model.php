<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoice_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function create($invoice_details_array) {
        if ($this->db->insert('invoices', $invoice_details_array)) {
            return $this->db->insert_id();
        }
        return 0;
    }

    function update($invoice_id, $invoice_update_array) {
        $this->db->where('invoice_id', $invoice_id);
        return $this->db->update('invoices', $invoice_update_array);
    }

    function get_seller_by_invoice_id($invoice_id) {
        $this->db->join('posts', 'posts.post_id = invoices.posts_id');
        $this->db->join('categories', 'categories.category_id = posts.categories_id');
        $this->db->join('users', 'users.user_id = posts.users_id');
        return $this->db->get_where('invoices', array('invoice_id' => $invoice_id))->row_array();
    }

    function get_paid_invoices_count_by_user_id($user_id) {
        $this->db->where('invoice_status', '1');
        $this->db->where('(invoice_by_users_id = ' . $user_id . ' OR invoice_to_users_id = ' . $user_id . ')');
        $this->db->from('invoices');
        return $this->db->count_all_results();
    }

    function get_transactions_awaiting_settlement($days_ago_timestamp) {
        return $this->db->get_where('invoices', array('invoice_transaction_id !=' => '', 'invoice_status' => '1', 'invoice_settlement_status' => '0', 'invoice_paid_on <' => $days_ago_timestamp))->result_array();
    }

    function mark_invoice_as_settled($invoice_transaction_id) {
        return $this->db->where('invoice_transaction_id', $invoice_transaction_id)->update('invoices', array('invoice_settlement_status' => '1', 'invoice_settled_on' => date('Y-m-d H:i:s')));
    }

    function get_revenue_report() {
        $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_rules('report_select', 'Report Type', 'trim|required');
        if ($this->input->post('report_select') == 'revenue-report') {
            $this->form_validation->set_rules('report_by', 'Report by', 'trim|required');
            $this->form_validation->set_rules('report_date', 'Report Date', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            die('1');
        } else if ($this->input->post('report_by') == 'year') {
            $where = 'where YEAR(invoice_created)=' . $this->input->post('report_date');
        } else if ($this->input->post('report_by') == 'month') {
            $year_month = explode('-', $this->input->post('report_date'));
            $where = 'where YEAR(invoice_created)=' . $year_month[0] . ' and MONTH(invoice_created)=' . $year_month[1];
        } else if ($this->input->post('report_by') == 'day') {
            $where = 'where DATE(invoice_created)="' . $this->input->post('report_date') . '"';
        }

        return $this->db->query("SELECT invoice_transaction_id, CONCAT(user_first_name, ' ', user_last_name) AS user_full_name,
CASE user_type
WHEN 1 THEN 'Business'
WHEN 2 THEN 'Individual'
ELSE 'ADMIN' END AS user_type,
user_email,post_title, invoice_amount,invoice_currency,invoice_status,DATE_FORMAT(invoice_created, '%e %b %Y') as invoice_created ,DATE_FORMAT(invoice_paid_on, '%e %b %Y') as invoice_paid_on FROM (`invoices`) LEFT JOIN `users` ON `invoices`.`invoice_to_users_id` = `users`.`user_id` LEFT JOIN `posts` ON `invoices`.`posts_id` = `posts`.`post_id` $where and  invoice_status = 1 GROUP BY `invoices`.`invoice_id`")->result_array();
    }

    function get_new_user_report() {
        $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_rules('report_select', 'Report Type', 'trim|required');
        if ($this->input->post('report_select') == 'new-user-report') {
            $this->form_validation->set_rules('report_by', 'Report by', 'trim|required');
            $this->form_validation->set_rules('report_date', 'Report Date', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            die('1');
        } else if ($this->input->post('report_by') == 'year') {
            $where = 'where YEAR(user_created)=' . $this->input->post('report_date');
        } else if ($this->input->post('report_by') == 'month') {
            $year_month = explode('-', $this->input->post('report_date'));
            $where = 'where YEAR(user_created) =' . $year_month[0] . ' and MONTH(user_created) =' . $year_month[1];
        } else if ($this->input->post('report_by') == 'day') {
            $where = 'where DATE(user_created) ="' . $this->input->post('report_date') . '"';
        }

        return $this->db->query("select user_id,CONCAT(user_first_name, ' ', user_last_name) AS user_full_name,CASE user_type
WHEN 1 THEN 'Business'
WHEN 2 THEN 'Individual'
ELSE 'ADMIN' END AS user_type, user_email,user_primary_contact,user_address_line_1,user_address_line_2,state_name,city_name,user_zipcode,
CASE user_status
WHEN 1 THEN 'Active'
WHEN 0 THEN 'Inactive'
ELSE 'Suspended' END AS user_status,
DATE_FORMAT(user_created, '%e %b %Y') as user_created from users join cities on cities.city_id = users.cities_id join states on states.state_id = cities.states_id $where GROUP BY `users`.`user_id`")->result_array();
    }

    function get_user_report_by() {
        $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '<br/>');
        if ($this->input->post('report_select') == 'user-report-by') {

            if ($this->input->post('location_by') == 'country') {
                $this->form_validation->set_rules('user_location_by_country', 'Report By Location', 'trim|required');
            }
            if ($this->input->post('location_by') == 'state') {
                $this->form_validation->set_rules('user_location_by_country', 'Report By Location', 'trim|required');
                $this->form_validation->set_rules('user_location_by_state', 'Report By Location', 'trim|required');
            }
            if ($this->input->post('location_by') == 'city') {
                $this->form_validation->set_rules('user_location_by_country', 'Report By Location', 'trim|required');
                $this->form_validation->set_rules('user_location_by_state', 'Report By Location', 'trim|required');
                $this->form_validation->set_rules('user_location_by_city', 'Report By Location', 'trim|required');
            }
        }

        if ($this->form_validation->run() == FALSE) {
            die('1');
        }

        if ($this->input->post('location_by') == 'country') {
            $where = 'where countries.country_id=' . $this->input->post('user_location_by_country');
        }
        if ($this->input->post('location_by') == 'state') {
            $where = 'where states.state_id=' . $this->input->post('user_location_by_state');
        }
        if ($this->input->post('location_by') == 'city') {
            $where = 'where users.cities_id=' . $this->input->post('user_location_by_city');
        }

        return $this->db->query("select user_id,CONCAT(user_first_name, ' ', user_last_name) AS user_full_name, user_email,user_primary_contact,user_address_line_1,user_address_line_2,state_name,city_name,user_zipcode,CASE user_status
WHEN 1 THEN 'Active'
WHEN 0 THEN 'Inactive'
ELSE 'Suspended' END AS user_status,DATE_FORMAT(user_created, '%e %b %Y') as user_created from users LEFT JOIN `cities` ON `users`.`cities_id` = `cities`.`city_id` LEFT JOIN `states` ON `cities`.`states_id` = `states`.`state_id` LEFT JOIN `countries` ON `states`.`countries_id` = `countries`.`country_id` $where GROUP BY `users`.`user_id`")->result_array();
    }

    function posting_report() {
        return $this->db->query("select post_id,CONCAT(user_first_name, ' ', user_last_name) AS user_full_name,category_name,post_title,post_contact_name,post_contact_number,DATE_FORMAT(post_created, '%e %b %Y') as post_created  from posts join categories on categories.category_id = posts.categories_id join users on users.user_id=posts.users_id GROUP BY `posts`.`post_id`")->result_array();
    }

    function get_user_report_by_category() {
        $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_rules('report_select', 'Report Type', 'trim|required');
        if ($this->input->post('report_select') == 'posting-report-by-category') {
            $this->form_validation->set_rules('report_by', 'Report by', 'trim|required');
            $this->form_validation->set_rules('revenue_by_category', 'Report by Category', 'trim|required');

            $this->form_validation->set_rules('report_date', 'Report Date', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            die('1');
        } else if ($this->input->post('report_by') == 'year') {
            $where = ' AND YEAR(invoice_created)=' . $this->input->post('report_date');
        } else if ($this->input->post('report_by') == 'month') {
            $year_month = explode('-', $this->input->post('report_date'));
            $where = ' AND YEAR(invoice_created) =' . $year_month[0] . ' and MONTH(invoice_created) =' . $year_month[1];
        } else if ($this->input->post('report_by') == 'day') {
            $where = ' AND DATE(invoice_created) ="' . $this->input->post('report_date') . '"';
        }

        return $this->db->query("SELECT invoice_id,invoice_transaction_id,post_id,post_title,post_description,category_name from invoices JOIN posts ON invoices.posts_id=posts.post_id JOIN categories ON categories.category_id=posts.categories_id where category_id=" . $this->input->post('revenue_by_category') . $where)->result_array();
    }

}
