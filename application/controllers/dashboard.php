<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public $public_methods = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function index() {
        $data = array();
        if ($_SESSION['user']['group_slug'] === 'administrator' || $_SESSION['user']['group_slug'] === 'manager') {
            redirect('users', 'refresh');
        } else {
            $this->load->model('Post_model');
            $data['item_posted_array'] = $this->Post_model->get_posted_posts_by_user_id($_SESSION['user']['user_id'], '1');
            if (count($data['item_posted_array']) > 0) {
                $next_item_posted_array = $this->Post_model->get_posted_pager('1', $data['item_posted_array']['post_id'], $_SESSION['user']['user_id'], '<');
                if (count($next_item_posted_array) > 0) {
                    $data['next_item_posted'] = 'true';
                }
                $data['item_posted_array']['post_image_url'] = parent::_prepare_post_image_url($data['item_posted_array']);
            }
            $data['item_rented_array'] = $this->Post_model->get_paid_posts_by_user_id($_SESSION['user']['user_id'], '1');
            if (count($data['item_rented_array']) > 0) {
                $next_item_rented_array = $this->Post_model->get_purchased_pager('1', $data['item_rented_array']['post_id'], $_SESSION['user']['user_id'], '<');
                if (count($next_item_rented_array) > 0) {
                    $data['next_item_rented'] = 'true';
                }
                $data['item_rented_array']['post_image_url'] = parent::_prepare_post_image_url($data['item_rented_array']);
            }
            $data['service_posted_array'] = $this->Post_model->get_posted_posts_by_user_id($_SESSION['user']['user_id'], '2');
            if (count($data['service_posted_array']) > 0) {
                $next_service_posted_array = $this->Post_model->get_posted_pager('2', $data['service_posted_array']['post_id'], $_SESSION['user']['user_id'], '<');
                if (count($next_service_posted_array) > 0) {
                    $data['next_service_posted'] = 'true';
                }
                $data['service_posted_array']['post_image_url'] = parent::_prepare_post_image_url($data['service_posted_array']);
            }
            $data['service_paid_array'] = $this->Post_model->get_paid_posts_by_user_id($_SESSION['user']['user_id'], '2');
            if (count($data['service_paid_array']) > 0) {
                $next_service_paid_array = $this->Post_model->get_purchased_pager('2', $data['service_paid_array']['post_id'], $_SESSION['user']['user_id'], '<');
                if (count($next_service_paid_array) > 0) {
                    $data['next_service_paid'] = 'true';
                }
                $data['service_paid_array']['post_image_url'] = parent::_prepare_post_image_url($data['service_paid_array']);
            }
        }
        $data['error_message'] = $this->session->flashdata('error_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->render_view($data, '', 'dashboard/' . $_SESSION['user']['group_slug']);
    }

    function download_report() {
        $data = array();
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_rules('report_select', 'Report Type', 'trim|required');
            if ($this->input->post('report_select') == 'revenue-report') {
                $this->form_validation->set_rules('report_by', 'Report by', 'trim|required');
                $this->form_validation->set_rules('report_date', 'Report Date', 'trim|required');
            } else if ($this->input->post('report_select') == 'new-user-report') {
                $this->form_validation->set_rules('report_by', 'Report by', 'trim|required');
                $this->form_validation->set_rules('report_date', 'Report Date', 'trim|required');
            } else if ($this->input->post('report_select') == 'user-report-by') {
                $this->form_validation->set_rules('location_by', 'Report By Location', 'trim|required');
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
                if ($this->input->post('location_by') == 'zip') {
                    $this->form_validation->set_rules('user_location_by_zip', 'Report By Location', 'trim|required');
                }
            } else if ($this->input->post('report_select') == 'posting-report') {
                $this->form_validation->set_rules('report_select', 'Report Type', 'trim|required');
            } else if ($this->input->post('report_select') == 'posting-report-by-category') {
                $this->form_validation->set_rules('revenue_by_category', 'Report by Category', 'trim|required');
                $this->form_validation->set_rules('report_by', 'Report by', 'trim|required');
                $this->form_validation->set_rules('report_date', 'Report Date', 'trim|required');
            }
            if ($this->form_validation->run() == FALSE) {
                $this->load->helper('url');
                $this->session->set_flashdata('error_message', 'Please Select All Fields.');
                redirect('reports', 'refresh');
            } else if ($this->form_validation->run()) {
                if ($this->input->post('report_select') == 'revenue-report') {
                    if ($this->input->post('report_by') == 'year') {
                        $where = 'where YEAR(invoice_created)=' . $this->input->post('report_date');
                    } else if ($this->input->post('report_by') == 'month') {
                        $year_month = explode('-', $this->input->post('report_date'));
                        $where = 'where YEAR(invoice_created)=' . $year_month[0] . ' and MONTH(invoice_created)=' . $year_month[1];
                    } else if ($this->input->post('report_by') == 'day') {
                        $where = 'where DATE(invoice_created)="' . $this->input->post('report_date') . '"';
                    }
                    $file_name = 'Revenue_Report_' . date('d-M-Y-h-i-s-a');
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=' . $file_name . '.xls');
                    $revenue_report_array = @mysql_unbuffered_query("SELECT invoice_transaction_id, CONCAT(user_first_name, ' ', user_last_name) AS user_full_name,
CASE user_type
WHEN 1 THEN 'Business'
WHEN 2 THEN 'Individual'
ELSE 'ADMIN' END AS user_type,
user_email,post_title, invoice_amount,invoice_currency,invoice_status,DATE_FORMAT(invoice_created, '%e %b %Y') ,DATE_FORMAT(invoice_paid_on, '%e %b %Y') FROM (`invoices`) LEFT JOIN `users` ON `invoices`.`invoice_to_users_id` = `users`.`user_id` LEFT JOIN `posts` ON `invoices`.`posts_id` = `posts`.`post_id` $where and invoice_status = 1 GROUP BY `invoices`.`invoice_id`");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, array(
                        "Transaction Id",
                        "User Full Name",
                        "User Type",
                        "User Email",
                        "Post Title",
                        "Amount",
                        "Currency",
                        "Invoice Status",
                        "Invoice Date",
                        "Invoice Paid On"
                            ), "\t");
                    while ($row = @mysql_fetch_assoc($revenue_report_array)) {
                        fputcsv($fp, $row, "\t");
                    }
                    fclose($fp);
                    die;
                } else if ($this->input->post('report_select') == 'new-user-report') {
                    if ($this->input->post('report_by') == 'year') {
                        $where = 'where YEAR(user_created)=' . $this->input->post('report_date');
                    } else if ($this->input->post('report_by') == 'month') {
                        $year_month = explode('-', $this->input->post('report_date'));
                        $where = 'where YEAR(user_created) =' . $year_month[0] . ' and MONTH(user_created) =' . $year_month[1];
                    } else if ($this->input->post('report_by') == 'day') {
                        $where = 'where DATE(user_created) ="' . $this->input->post('report_date') . '"';
                    }
                    $file_name = 'New_User_Report_' . date('d-M-Y-h-i-s-a');
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=' . $file_name . '.xls');
                    $new_user_report_array = @mysql_unbuffered_query("select user_id,CONCAT(user_first_name, ' ', user_last_name) AS user_full_name,CASE user_type
WHEN 1 THEN 'Business'
WHEN 2 THEN 'Individual'
ELSE 'ADMIN' END AS user_type, user_email,user_primary_contact,user_address_line_1,user_address_line_2,state_name,city_name,user_zipcode,
CASE user_status
WHEN 1 THEN 'Active'
WHEN 0 THEN 'Inactive'
ELSE 'Suspended' END AS user_status,
DATE_FORMAT(user_created, '%e %b %Y') from users join cities on cities.city_id = users.cities_id join states on states.state_id = cities.states_id $where GROUP BY `users`.`user_id`");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, array(
                        "User ID",
                        "User Full Name",
                        "User Type",
                        "User Email",
                        "User Contact Number",
                        "Address 1",
                        "Address 2",
                        "State",
                        "City",
                        "Zip Code",
                        "User Status",
                        "User Created"
                            ), "\t");
                    while ($row = @mysql_fetch_assoc($new_user_report_array)) {
                        fputcsv($fp, $row, "\t");
                    }
                    fclose($fp);
                    die;
                } else if ($this->input->post('report_select') == 'user-report-by') {
                    if ($this->input->post('location_by') == 'country') {
                        $where = 'where countries.country_id=' . $this->input->post('user_location_by_country');
                    }
                    if ($this->input->post('location_by') == 'state') {
                        $where = 'where states.state_id=' . $this->input->post('user_location_by_state');
                    }
                    if ($this->input->post('location_by') == 'city') {
                        $where = 'where users.cities_id=' . $this->input->post('user_location_by_city');
                    }
                    $file_name = 'Location_Report_' . date('d-M-Y-h-i-s-a');
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=' . $file_name . '.xls');
                    if ($this->input->post('location_by') == 'zip') {
                        $zip = $this->input->post('user_location_by_zip');
                        $new_user_report_array = @mysql_unbuffered_query("select user_id,CONCAT(user_first_name, ' ', user_last_name) AS user_full_name, user_email,user_primary_contact,user_address_line_1,user_address_line_2,state_name,city_name,user_zipcode,CASE user_status
WHEN 1 THEN 'Active'
WHEN 0 THEN 'Inactive'
ELSE 'Suspended' END AS user_status,DATE_FORMAT(user_created, '%e %b %Y') from users LEFT JOIN `cities` ON `users`.`cities_id` = `cities`.`city_id` LEFT JOIN `states` ON `cities`.`states_id` = `states`.`state_id` where user_zipcode = $zip GROUP BY `users`.`user_id`");
                    } else {
                        $new_user_report_array = @mysql_unbuffered_query("select user_id,CONCAT(user_first_name, ' ', user_last_name) AS user_full_name, user_email,user_primary_contact,user_address_line_1,user_address_line_2,state_name,city_name,user_zipcode,CASE user_status
WHEN 1 THEN 'Active'
WHEN 0 THEN 'Inactive'
ELSE 'Suspended' END AS user_status,DATE_FORMAT(user_created, '%e %b %Y')  from users LEFT JOIN `cities` ON `users`.`cities_id` = `cities`.`city_id` LEFT JOIN `states` ON `cities`.`states_id` = `states`.`state_id` LEFT JOIN `countries` ON `states`.`countries_id` = `countries`.`country_id` $where GROUP BY `users`.`user_id`");
                    }
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, array(
                        "User ID",
                        "User Full Name",
                        "User Email",
                        "User Contact Number",
                        "Address 1",
                        "Address 2",
                        "State",
                        "City",
                        "Zip Code",
                        "User Status",
                        "User Created"
                            ), "\t");
                    while ($row = @mysql_fetch_assoc($new_user_report_array)) {
                        fputcsv($fp, $row, "\t");
                    }
                    fclose($fp);
                    die;
                } else if ($this->input->post('report_select') == 'active-user-report') {
                    $file_name = 'Activeusers_Report_' . date('d-M-Y-h-i-s-a');
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=' . $file_name . '.xls');
                    $new_user_report_array = @mysql_unbuffered_query("select user_id,CONCAT(user_first_name, ' ', user_last_name) AS user_full_name,CASE user_type
WHEN 1 THEN 'Business'
WHEN 2 THEN 'Individual'
ELSE 'ADMIN' END AS user_type, user_email,user_primary_contact,user_address_line_1,user_address_line_2,state_name,city_name,user_zipcode,DATE_FORMAT(user_created, '%e %b %Y') from users join cities on cities.city_id = users.cities_id join states on states.state_id = cities.states_id where user_status='1' GROUP BY `users`.`user_id`");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, array(
                        "User ID",
                        "User Full Name",
                        "User Type",
                        "User Email",
                        "User Contact Number",
                        "Address 1",
                        "Address 2",
                        "State",
                        "City",
                        "Zip Code",
                        "User Created"
                            ), "\t");
                    while ($row = @mysql_fetch_assoc($new_user_report_array)) {
                        fputcsv($fp, $row, "\t");
                    }
                    fclose($fp);
                    die;
                } else if ($this->input->post('report_select') == 'posting-report') {
                    $file_name = 'Posting_Report_' . date('d-M-Y-h-i-s-a');
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=' . $file_name . '.xls');
                    $new_user_report_array = @mysql_unbuffered_query("select post_id,CONCAT(user_first_name, ' ', user_last_name) AS user_full_name,category_name,post_title,post_contact_name,post_contact_number,DATE_FORMAT(post_created, '%e %b %Y')  from posts join categories on categories.category_id = posts.categories_id join users on users.user_id=posts.users_id GROUP BY `posts`.`post_id`");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, array(
                        "Post Id",
                        "Post Creatde By",
                        "Category name",
                        "Post Title",
                        "Contact Person Name",
                        "Contact Person Number",
                        "Post Created"
                            ), "\t");
                    while ($row = @mysql_fetch_assoc($new_user_report_array)) {
                        fputcsv($fp, $row, "\t");
                    }
                    fclose($fp);
                    die;
                } else if ($this->input->post('report_select') == 'posting-report-by-category') {
                    if ($this->input->post('report_by') == 'year') {
                        $where = ' AND YEAR(invoice_created)=' . $this->input->post('report_date');
                    } else if ($this->input->post('report_by') == 'month') {
                        $year_month = explode('-', $this->input->post('report_date'));
                        $where = ' AND YEAR(invoice_created) =' . $year_month[0] . ' and MONTH(invoice_created) =' . $year_month[1];
                    } else if ($this->input->post('report_by') == 'day') {
                        $where = ' AND DATE(invoice_created) ="' . $this->input->post('report_date') . '"';
                    }
                    $file_name = 'Posting_Report_' . date('d-M-Y-h-i-s-a');
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=' . $file_name . '.xls');
                    $posting_report_array = @mysql_unbuffered_query("SELECT invoice_id,invoice_transaction_id,post_id,post_title,post_description,category_name from invoices JOIN posts ON invoices.posts_id=posts.post_id JOIN categories ON categories.category_id=posts.categories_id where category_id=" . $this->input->post('revenue_by_category') . $where);
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, array(
                        "Invoice Id",
                        "Paypal Transaction Id",
                        "Post Id",
                        "Post Title",
                        "Post Description",
                        "Category Name"
                            ), "\t");
                    while ($row = @mysql_fetch_assoc($posting_report_array)) {
                        fputcsv($fp, $row, "\t");
                    }
                    fclose($fp);
                    die;
                }
            } else {
                echo validation_errors();
                die;
            }
            die('Error In Creating Report !!!');
        }
        $this->render_view($data);
    }

    function get_all_active_countries() {
        $this->load->model('Country_model');
        parent::json_output($this->Country_model->get_all_active_countries());
    }

    function get_active_states_by_country_id() {
        $this->load->model('State_model');
        if (is_numeric($this->input->post('country_id'))) {
            parent::json_output($this->State_model->get_active_states_by_country_id($this->input->post('country_id')));
        }
    }

    function get_active_cities_by_state_id() {
        $this->load->model('City_model');
        if (is_numeric($this->input->post('state_id'))) {
            parent::json_output($this->City_model->get_active_cities_by_state_id($this->input->post('state_id')));
        }
    }

    function get_all_zip_codes() {
        $this->load->model('City_model');
        parent::json_output($this->City_model->get_all_zip_codes());
    }

}