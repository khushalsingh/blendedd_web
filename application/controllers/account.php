<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account extends MY_Controller {

    public $public_methods = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function index() {
        $data = array();
        $this->load->model('User_model');
        $data['user_details_array'] = $this->User_model->get_user_details_by_user_id($_SESSION['user']['user_id']);
        $data['error_message'] = $this->session->flashdata('error_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->render_view($data);
    }

    function edit() {
        $data = array();
        $this->load->model('User_model');
        if ($this->input->post()) {
            $this->load->library('form_validation');
            if (isset($_SESSION['user']['user_dob']) && $_SESSION['user']['user_dob'] === '0000-00-00') {
                $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');
                if ($this->input->post('user_type') === 'business') {
                    $this->form_validation->set_rules('business_legal_name', 'Business Legal Name', 'trim|required');
                    $this->form_validation->set_rules('business_tax_id', 'Business Tax ID', 'trim|required|is_unique[users.business_tax_id]');
                }
                $this->form_validation->set_rules('user_dob', 'Date Of Birth', 'trim|required');
                $this->form_validation->set_rules('user_login', 'User ID', 'trim|required|is_unique[users.user_login]|is_unique[registrations.user_login]');
                $this->form_validation->set_rules('user_primary_contact', 'Primary Contact Number', 'trim|required|is_unique[users.user_primary_contact]|is_unique[registrations.user_primary_contact]');
            }
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
                if (isset($_SESSION['user']['user_dob']) && $_SESSION['user']['user_dob'] === '0000-00-00') {

                    $user_dob_array = explode('/', $this->input->post('user_dob'));
                    $user_update_array['user_login'] = $this->input->post('user_login');
                    $user_update_array['user_type'] = ($this->input->post('user_type') == 'business') ? '1' : '2';
                    $user_update_array['business_legal_name'] = ($this->input->post('user_type') == 'business') ? $this->input->post('business_legal_name') : '';
                    $user_update_array['business_tax_id'] = ($this->input->post('user_type') == 'business') ? $this->input->post('business_tax_id') : '';
                    $user_update_array['user_ssn'] = $this->input->post('user_ssn');
                    $user_update_array['user_primary_contact'] = $this->input->post('user_primary_contact');
                    $user_update_array['user_dob'] = $user_dob_array[2] . '-' . $user_dob_array[0] . '-' . $user_dob_array[1];
                }
                if ($this->User_model->edit_user_by_user_id($_SESSION['user']['user_id'], $user_update_array)) {
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
        $data['warning_message'] = $this->session->flashdata('warning_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->load->helper('form');
        $data['user_details_array'] = $this->User_model->get_user_details_by_user_id($_SESSION['user']['user_id']);
        $this->load->model('Country_model');
        $data['countries_array'] = $this->Country_model->get_all_active_countries();
        $this->load->model('State_model');
        $data['states_array'] = $this->State_model->get_active_states_by_country_id($data['user_details_array']['country_id']);
        $this->load->model('City_model');
        $data['cities_array'] = $this->City_model->get_active_cities_by_state_id($data['user_details_array']['state_id']);
        $this->render_view($data);
    }

    function credit_card() {
        if (isset($_SESSION['user']) && isset($_SESSION['user']['user_dob']) && $_SESSION['user']['user_dob'] == '0000-00-00') {
            if ($this->uri->uri_string != 'account/edit' && $this->uri->uri_string != 'auth/logout') {
                $this->session->set_flashdata('error_message', 'Please fill in the form below to proceed.');
                redirect('account/edit', 'refresh');
            }
        }
        $data = array();
        $this->load->model('User_model');
        $data['user_details_array'] = $this->User_model->get_user_details_by_user_id($_SESSION['user']['user_id']);
        if ($data['user_details_array']['user_credit_card_number'] !== '') {
            $this->load->library('encrypt');
            $data['user_details_array']['user_credit_card_number'] = parent::mask_characters($this->encrypt->decode($data['user_details_array']['user_credit_card_number']), 12, 'X');
        }
        $this->render_view($data);
    }

    function paypal() {
        $data = array();
        $this->load->model('User_model');
        $data['user_details_array'] = $this->User_model->get_user_details_by_user_id($_SESSION['user']['user_id']);
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters("", "<br/>");
            if ($this->input->post('user_paypal_email_address')) {
                if ($this->input->post('user_paypal_email_address') === $data['user_details_array']['user_paypal_email_address']) {

                } else {
                    $this->form_validation->set_rules('user_paypal_email_address', 'PayPal ID', 'trim|required|valid_email|is_unique[users.user_email]|is_unique[registrations.user_email]|is_unique[users.user_paypal_email_address]|is_unique[registrations.user_paypal_email_address]');
                    if ($this->form_validation->run()) {
                        $user_update_array = array(
                            'user_paypal_verified' => '0',
                            'user_paypal_verified_on' => '0000-00-00 00:00:00',
                            'user_paypal_email_address' => $this->input->post('user_paypal_email_address'),
                            'user_modified' => date('Y-m-d H:i:s')
                        );
                        $user_paypal_verification_code = parent::generate_random_string();
                        $paypal_result = parent::paypal_payout($user_update_array['user_paypal_email_address'], parent::generate_random_string('alnum', 16), 'Paypal Verification Code', 'Paypal Verification Code : ' . $user_paypal_verification_code);
                        if (isset($paypal_result['body']->items[0]->transaction_status) && $paypal_result['body']->items[0]->transaction_status === 'SUCCESS') {
                            $user_update_array['user_paypal_verification_code'] = $user_paypal_verification_code;
                            if ($this->User_model->edit_user_by_user_id($_SESSION['user']['user_id'], $user_update_array)) {
                                parent::regenerate_session();
                                $data['success_message'] = 'We have sent you a payment of 0.01 USD with a Custom Note . Please enter that note in the Box below.';
                            } else {
                                $data['error_message'] = 'Critical Error !!!';
                            }
                        } else {
                            $data['error_message'] = 'Paypal Account Validation Failed !!!';
                        }
                    } else {
                        $data['error_message'] = validation_errors();
                    }
                }
            }
            if ($this->input->post('user_paypal_verification_code')) {
                $this->form_validation->set_rules('user_paypal_verification_code', 'Verification Code', 'trim|required');
                if ($this->form_validation->run()) {
                    if ($data['user_details_array']['user_paypal_verification_code'] === $this->input->post('user_paypal_verification_code')) {
                        $user_update_array = array(
                            'user_paypal_verified' => '1',
                            'user_paypal_verified_on' => date('Y-m-d H:i:s'),
                            'user_modified' => date('Y-m-d H:i:s')
                        );
                        if ($this->User_model->edit_user_by_user_id($_SESSION['user']['user_id'], $user_update_array)) {
                            parent::regenerate_session();
                            $data['success_messsage'] = 'We have sent you a payment of 0.01 USD with a Custom Note . Please enter that note in the Box below.';
                        } else {
                            $data['error_message'] = 'Critical Error !!!';
                        }
                    } else {
                        $data['error_message'] = 'Invalid Verification Code !!!';
                    }
                } else {
                    $data['error_message'] = validation_errors();
                }
            }
        }
        $data['user_details_array'] = $this->User_model->get_user_details_by_user_id($_SESSION['user']['user_id']);
        $this->render_view($data);
    }

    function bank_account() {
        if (isset($_SESSION['user']) && isset($_SESSION['user']['user_dob']) && $_SESSION['user']['user_dob'] == '0000-00-00') {
            if ($this->uri->uri_string != 'account/edit' && $this->uri->uri_string != 'auth/logout') {
                $this->session->set_flashdata('error_message', 'Please fill in the form below to proceed.');
                redirect('account/edit', 'refresh');
            }
        }
        $data = array();
        $this->load->model('User_model');
        $user_details_array = $this->User_model->get_user_details_by_user_id($_SESSION['user']['user_id']);
        if ($this->input->post()) {
            if (
                    $this->input->post('user_bank_account_number') === $user_details_array['user_bank_account_number'] &&
                    $this->input->post('user_bank_name') === $user_details_array['user_bank_name'] &&
                    $this->input->post('user_bank_route_code') === $user_details_array['user_bank_route_code'] &&
                    $this->input->post('user_bank_account_online') === $user_details_array['user_bank_account_online']
            ) {
                die('No Changes to make !!!');
            }
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters("", "<br/>");
            $this->form_validation->set_rules('user_bank_account_number', 'Bank Account Number', 'trim|required|callback__validate_unique_bank_account');
            $this->form_validation->set_rules('user_bank_name', 'Bank Name', 'trim|required');
            $this->form_validation->set_rules('user_bank_route_code', 'Bank Route Code', 'trim|required');
            $this->form_validation->set_rules('user_bank_account_online', 'Bank Account Online', 'trim');
            if ($this->form_validation->run()) {
                $user_update_array = array(
                    'user_bank_verified' => '0',
                    'user_bank_account_verified_on' => '0000-00-00 00:00:00',
                    'user_bank_account_number' => $this->input->post('user_bank_account_number'),
                    'user_bank_name' => $this->input->post('user_bank_name'),
                    'user_bank_route_code' => $this->input->post('user_bank_route_code'),
                    'user_bank_account_online' => $this->input->post('user_bank_account_online'),
                    'user_modified' => date('Y-m-d H:i:s')
                );
                if ($this->User_model->edit_user_by_user_id($_SESSION['user']['user_id'], $user_update_array)) {
                    $user_type = 'individual';
                    if ($user_details_array['user_type'] === '1') {
                        $user_type = 'business';
                    }
                    $this->load->model('City_model');
                    $city_details_array = $this->City_model->get_city_details_by_id($user_details_array['cities_id']);
                    $merchant_response = parent::braintree_add_merchant($user_type, $user_details_array['user_first_name'], $user_details_array['user_last_name'], $user_details_array['user_email'], $user_details_array['user_dob'], $user_details_array['user_ssn'], $user_details_array['user_address_line_1'], $user_details_array['user_address_line_2'], $city_details_array['city_name'], $city_details_array['state_code'], $user_details_array['user_zipcode'], $this->input->post('user_bank_account_number'), $this->input->post('user_bank_route_code'), $user_details_array['business_legal_name'], $user_details_array['business_tax_id']);
                    if (isset($merchant_response->success) && $merchant_response->success == '1' && isset($merchant_response->merchantAccount->_attributes['id'])) {
                        $this->load->model('Auth_model');
                        $this->Auth_model->edit_user_by_user_id($_SESSION['user']['user_id'], array('user_braintree_merchant_id' => $merchant_response->merchantAccount->_attributes['id']));
                    } else {
                        echo '<h4>Error While Validating Bank Account details.</h4>';
                        echo nl2br(@$merchant_response->_attributes['message']);
                        die;
                    }
                    parent::regenerate_session();
                    die('1');
                }
            } else {
                echo validation_errors();
                die;
            }
            die('0');
        }
        $data['user_details_array'] = $this->User_model->get_user_details_by_user_id($_SESSION['user']['user_id']);
        $data['error_message'] = $this->session->flashdata('error_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->render_view($data);
    }

    function payments_made() {
        $data = array();
        $data['error_message'] = $this->session->flashdata('error_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->render_view($data);
    }

    function payments_made_datatable() {
        $this->load->library('Datatables');
        $this->datatables->where('invoices.invoice_to_users_id', $_SESSION['user']['user_id']);
        $this->datatables->where('invoices.invoice_status', '1');
        $this->datatables->select("post_id,post_title,invoice_transaction_id,invoice_amount,DATE_FORMAT(invoice_paid_on, '%e %b %Y') AS invoice_created_date", FALSE)->from('invoices');
        $this->datatables->join('posts', 'posts.post_id = invoices.posts_id', 'left');
        echo $this->datatables->generate();
    }

    function payments_received() {
        $data = array();
        $data['error_message'] = $this->session->flashdata('error_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->render_view($data);
    }

    function payments_received_datatable() {
        $this->load->library('Datatables');
        $this->datatables->where('invoices.invoice_by_users_id', $_SESSION['user']['user_id']);
        $this->datatables->where('invoices.invoice_status', '1');
        $this->datatables->select("post_id,post_title,invoice_transaction_id,invoice_amount,invoice_blendedd_amount,invoice_merchant_amount,DATE_FORMAT(invoice_paid_on, '%e %b %Y') AS invoice_created_date,invoice_settlement_status", FALSE)->from('invoices');
        $this->datatables->join('posts', 'posts.post_id = invoices.posts_id', 'left');
        echo $this->datatables->generate();
    }

    function change_password() {
        $this->load->model('Auth_model');
        $data = array();
        $data['user_details_array'] = $this->Auth_model->get_user_by_id($_SESSION['user']['user_id']);
        if ($data['user_details_array']['user_status'] === '-1') {
            redirect('auth/logout');
        }
        $this->load->library('encrypt');
        $this->load->helper('form');
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('user_login_password', 'Password', 'trim|required|min_length[8]|callback__validate_password');
            $this->form_validation->set_rules('user_confirm_password', 'Confirm Password ', 'trim|required|matches[user_login_password]');
            $this->form_validation->set_error_delimiters('', '<br />');
            if ($this->form_validation->run()) {
                $time_now = date('Y-m-d H:i:s');
                $user_details_array = array(
                    'user_security_hash' => md5($time_now . $this->input->post('user_login_password')),
                    'force_change_password' => '0',
                    'user_modified' => $time_now
                );
                if ($this->input->post('user_login_password') !== '') {
                    $user_details_array['user_login_salt'] = md5($time_now);
                    $user_details_array['user_login_password'] = md5(md5(md5($time_now) . $this->input->post('user_login_password')));
                    $user_details_array['user_password_hash'] = $this->encrypt->encode($this->input->post('user_login_password'), md5(md5(md5($time_now) . $this->input->post('user_login_password'))));
                }
                if ($this->Auth_model->edit_user_by_user_id($_SESSION['user']['user_id'], $user_details_array)) {
                    $_SESSION['user']['force_change_password'] = 0;
                    $data['success_message'] = 'Password Changed !!!';
                    parent::regenerate_session();
                }
            } else {
                $data['error_message'] = validation_errors();
            }
        }
        $this->render_view($data);
    }

}
