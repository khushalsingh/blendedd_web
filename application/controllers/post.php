<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Post extends MY_Controller {

    public $public_methods = array('index', 'view', 'login_checkout', 'reply', 'prohibit', 'email_to_friend');
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
        $this->load->model('Post_model');
    }

    function _get_pricing_options() {
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

    function _check_post_price($str) {
        foreach ($this->item_price_array as $item_price) {
            if ($this->input->post('post_' . $item_price['pricing_option_type'] . '_price') !== '') {
                return TRUE;
            }
        }
        $this->form_validation->set_message('_check_post_price', 'Please specify a price.');
        return FALSE;
    }

    function _check_post_deal_price($str) {
        foreach ($this->package_deals_array as $package_deal) {
            if ($this->input->post('post_deal_price_' . $package_deal['pricing_option_type']) !== '') {
                return TRUE;
            }
        }
        $this->form_validation->set_message('_check_post_price', 'Please specify a price.');
        return FALSE;
    }

    function index() {
        parent::allow(array('administrator', 'manager'));
        $data = array();
        $data['error_message'] = $this->session->flashdata('error_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->render_view($data);
    }

    function posts_datatable() {
        parent::allow(array('administrator', 'manager'));
        $this->load->library('Datatables');
        $this->datatables->select("post_id,category_name,post_title,user_email,DATE_FORMAT(post_created, '%e %b %Y') AS post_created_date,post_status,post_slug,category_slug", FALSE)->from('posts');
        $this->datatables->join('users', 'users.user_id = posts.users_id', 'left');
        $this->datatables->join('categories', 'categories.category_id = posts.categories_id', 'left');
        echo $this->datatables->generate();
    }

    function category() {
        if (isset($_SESSION['user']) && isset($_SESSION['user']['user_dob']) && $_SESSION['user']['user_dob'] == '0000-00-00') {
            if ($this->uri->uri_string != 'account/edit' && $this->uri->uri_string != 'auth/logout') {
                $this->session->set_flashdata('error_message', 'Please fill in the form below to proceed.');
                redirect('account/edit', 'refresh');
            }
        }
        if (isset($_SESSION['user']) && isset($_SESSION['user']['user_bank_verified']) && $_SESSION['user']['user_bank_verified'] == '0') {
            $this->session->set_flashdata('error_message', 'Account Not Verified for receiving payments !!!');
            redirect('account/bank_account', 'refresh');
        }
        $data = array();
        $this->load->model('Category_model');
        $data['categories'] = $this->Category_model->get_all_active_categories_alphabetical();
        $this->render_view($data);
    }

    function create($category) {
        $this->load->model('User_model');
        $user_details_array = $this->User_model->get_user_row_by_user_id($_SESSION['user']['user_id']);
        $data['user_actual_email'] = $_SESSION['user']['user_email'];
        if ($user_details_array['user_bank_verified'] === '1') {
            $this->_get_pricing_options();
            $this->load->model('Category_model');
            $data['category_details_array'] = $this->Category_model->get_category_by_slug($category);
            if ($this->input->post()) {
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('', '<br/>');
                $this->form_validation->set_rules('post_show_actual_email', 'Show email', 'trim|required');
                $this->form_validation->set_rules('post_title', 'Post Title', 'trim|required|min_length[10]');
                $this->form_validation->set_rules('post_description', 'Description', 'trim|required|min_length[50]');
                if ($data['category_details_array']['category_type'] == '1') {
                    $this->form_validation->set_rules('post_daily_price', 'Price', 'trim|callback__check_post_price');
                } else if ($data['category_details_array']['category_type'] == '2') {
                    $this->form_validation->set_rules('post_deal_price_1', 'Price', 'trim|callback__check_post_deal_price');
                }
                $this->form_validation->set_rules('countries_id', 'Country', 'trim|required');
                $this->form_validation->set_rules('states_id', 'State', 'trim|required');
                $this->form_validation->set_rules('cities_id', 'City', 'trim|required');
                $this->form_validation->set_rules('post_zipcode', 'Zip Code', 'trim|required|exact_length[5]');
                $this->form_validation->set_rules('time_zones_id', 'Time Zone', 'trim|required');
                $this->form_validation->set_rules('post_image_name', 'At least one image', 'required');
                if ($this->form_validation->run()) {
                    $status = TRUE;
                    $date_time_now = date('Y-m-d H:i:s');
                    $this->load->model('City_model');
                    $city_details_array = $this->City_model->get_city_details_by_id($this->input->post('cities_id'));
                    $post_title_shadow = $this->input->post('post_title') . ' ' . $city_details_array['city_name'] . ' ' . $city_details_array['state_name'] . ' ' . $city_details_array['state_code'] . ' ' . $this->input->post('post_zipcode') . ' ' . $city_details_array['country_name'];
                    $post_insert_array = array(
                        'users_id' => $_SESSION['user']['user_id'],
                        'categories_id' => $data['category_details_array']['category_id'],
                        'post_show_actual_email' => $this->input->post('post_show_actual_email'),
                        'post_communication_by_phone' => $this->input->post('post_communication_by_phone'),
                        'post_communication_by_sms' => $this->input->post('post_communication_by_sms'),
                        'post_contact_name' => $this->input->post('post_contact_name'),
                        'post_contact_number' => $this->input->post('post_contact_number'),
                        'post_title' => $this->input->post('post_title'),
                        'post_title_shadow' => $post_title_shadow,
                        'post_slug' => url_title($post_title_shadow, '-', TRUE),
                        'post_description' => strip_tags(trim($this->input->post('post_description'))),
                        'post_show_on_map' => $this->input->post('post_show_on_map'),
                        'post_street' => $this->input->post('post_street'),
                        'post_cross_street' => $this->input->post('post_cross_street'),
                        'cities_id' => $this->input->post('cities_id'),
                        'post_zipcode' => $this->input->post('post_zipcode'),
                        'time_zones_id' => $this->input->post('time_zones_id'),
                        'post_status' => '1',
                        'post_created' => $date_time_now
                    );
                    $post_insert_array['post_min_price'] = 999999;
                    if ($data['category_details_array']['category_type'] == '1') {
                        foreach ($this->item_price_array as $item_price) {
                            $post_insert_array['post_' . $item_price['pricing_option_type'] . '_price'] = $this->input->post('post_' . $item_price['pricing_option_type'] . '_price');
                            if ($post_insert_array['post_' . $item_price['pricing_option_type'] . '_price'] != 0 && $post_insert_array['post_min_price'] > $post_insert_array['post_' . $item_price['pricing_option_type'] . '_price']) {
                                $post_insert_array['post_min_price'] = $post_insert_array['post_' . $item_price['pricing_option_type'] . '_price'];
                            }
                        }
                    } else if ($data['category_details_array']['category_type'] == '2') {
                        foreach ($this->package_deals_array as $package_deal) {
                            $post_insert_array['post_deal_price_' . $package_deal['pricing_option_type']] = $this->input->post('post_deal_price_' . $package_deal['pricing_option_type']);
                            if ($post_insert_array['post_deal_price_' . $package_deal['pricing_option_type']] != 0 && $post_insert_array['post_min_price'] > $post_insert_array['post_deal_price_' . $package_deal['pricing_option_type']]) {
                                $post_insert_array['post_min_price'] = $post_insert_array['post_deal_price_' . $package_deal['pricing_option_type']];
                            }
                        }
                    }
                    $post_id = $this->Post_model->create($post_insert_array);
                    if ($post_id > 0) {
                        $time_available_array = array();
                        for ($time_available = 1; $time_available < 8; $time_available++) {
                            $column_name = $this->input->post('post_availability_' . $time_available);
                            if (trim($column_name) !== '' && $this->input->post('post_availability_' . $time_available . '_from') !== '' && $this->input->post('post_availability_' . $time_available . '_to') !== '') {
                                $time_available_array[] = array(
                                    'posts_id' => $post_id,
                                    'post_availability_day' => $this->input->post('post_availability_' . $time_available),
                                    'post_availability_from' => date('H:i:s', strtotime($this->input->post('post_availability_' . $time_available . '_from'))),
                                    'post_availability_to' => date('H:i:s', strtotime($this->input->post('post_availability_' . $time_available . '_to'))),
                                    'post_availability_created' => $date_time_now,
                                );
                            }
                        }
                        $upload_directory = FCPATH . 'uploads/posts/' . date('Y/m/d/H/i/s', strtotime($date_time_now));
                        if (!is_dir($upload_directory)) {
                            mkdir($upload_directory, 0755, TRUE);
                        }
                        $image_insert_array = array();
                        $this->load->library('image_lib');
                        $counter = 0;
                        foreach ($this->input->post('post_image_name') as $post_image_name) {
                            if (trim($post_image_name) !== '' && is_file(FCPATH . 'uploads/' . $post_image_name)) {
                                $post_image_name_array = explode('.', trim($post_image_name));
                                $extension = array_pop($post_image_name_array);
                                rename(FCPATH . 'uploads/' . trim($post_image_name), $upload_directory . '/' . trim($post_image_name));
                                $config['source_image'] = $upload_directory . '/' . $post_image_name;
                                $config['new_image'] = $upload_directory . '/' . implode('.', $post_image_name_array) . '_thumb.' . $extension;
                                $config['maintain_ratio'] = TRUE;
                                $config['width'] = 200;
                                $config['height'] = 150;
                                $config['quality'] = 100;
                                $this->image_lib->initialize($config);
                                $this->image_lib->resize();
                                $this->image_lib->clear();
                                $image_insert_array[] = array(
                                    'posts_id' => $post_id,
                                    'post_image_name' => trim($post_image_name),
                                    'post_image_display_order' => $counter,
                                    'post_image_status' => '1',
                                    'post_image_created' => $date_time_now
                                );
                                $counter++;
                            }
                        }
                        if (count($image_insert_array) > 0 && !$this->Post_model->create_post_images($image_insert_array)) {
                            $status = FALSE;
                        }
                        if (count($time_available_array) > 0 && !$this->Post_model->create_post_availabilities($time_available_array)) {
                            $status = FALSE;
                        }
                        if ($status === TRUE) {
                            $email_details_array = array(
                                'post_title' => $post_insert_array['post_title'],
                                'post_link' => base_url() . 'post/view/' . $post_insert_array['post_slug'] . '/' . $post_id,
                                'post_description' => $post_insert_array['post_description'],
                                'user_login' => $_SESSION['user']['user_login'],
                                'user_full_name' => $_SESSION['user']['user_first_name'] . ' ' . $_SESSION['user']['user_last_name'],
                                'user_email' => $_SESSION['user']['user_email'],
                                'user_primary_contact' => $_SESSION['user']['user_primary_contact'],
                            );
                            if ($_SESSION['user']['user_communication_via_sms']) {
                                parent::send_sms(str_replace('-', '', $_SESSION['user']['user_primary_contact']), "Congratulations. Your posting is now published with Blendedd");
                            }
                            if ($_SESSION['user']['user_communication_via_email'] === '1') {
                                $email_id1 = parent::add_email_to_queue('', '', $_SESSION['user']['user_email'], $_SESSION['user']['user_id'], 'Your posting is now published', $this->render_view($email_details_array, 'emails', 'emails/templates/user_post_created', TRUE));
                            } else {
                                $email_id1 = 0;
                            }
                            $email_id2 = parent::add_email_to_queue('', '', $this->config->item('admin_email'), '1', 'Post Published', $this->render_view($email_details_array, 'emails', 'emails/templates/admin_post_created', TRUE));
                            if ($email_id1 > 0) {
                                @file_get_contents(base_url() . 'emails/cron/' . $email_id1);
                            }
                            if ($email_id2 > 0) {
                                @file_get_contents(base_url() . 'emails/cron/' . $email_id2);
                            }
                            echo $post_id;
                            die;
                        }
                    }
                } else {
                    echo validation_errors();
                    die;
                }
                die('0');
            }
            $data['weekdays_array'] = $this->weekdays_array;
            $data['user_email'] = parent::mask_characters($_SESSION['user']['user_email']);
            $data['time_zones_array'] = $this->Post_model->get_all_time_zones();
            $this->load->model('Country_model');
            $data['countries_array'] = $this->Country_model->get_all_active_countries();
            if ($data['category_details_array']['category_type'] === '1') {
                $data['item_price_array'] = $this->item_price_array;
                $this->render_view($data, 'system', 'post/create_rental');
            } else if ($data['category_details_array']['category_type'] === '2') {
                $data['package_deals_array'] = $this->package_deals_array;
                $this->render_view($data, 'system', 'post/create_service');
            }
        }
    }

    function view($post_slug = '', $post_id = 0) {
        $this->_get_pricing_options();
        $this->load->model('Category_model');
        $data['post_details_array'] = $this->Post_model->get_post_by_id($post_id);
        if (count($data['post_details_array']) > 0 && $data['post_details_array']['post_slug'] === $post_slug) {
            $data['title'] = $data['post_details_array']['post_title_shadow'];
            $data['next_post_details_array'] = $this->Post_model->get_next_post_in_same_category($post_id, $data['post_details_array']['categories_id']);
            $data['previous_post_details_array'] = $this->Post_model->get_previous_post_in_same_category($post_id, $data['post_details_array']['categories_id']);
            $data['category_details_array'] = $this->Category_model->get_category_type_by_id($data['post_details_array']['categories_id']);
            $data['post_details_array']['post_time_availability_array'] = $this->Post_model->get_time_availability_by_post_id($post_id);
            $post_images_array = $this->Post_model->get_images_by_post_id($post_id);
            $data['post_details_array']['post_images_array'] = array();
            foreach ($post_images_array as $post_images) {
                $data['post_details_array']['post_images_array'][] = array(
                    'post_image_name' => $post_images['post_image_name'],
                    'post_image_display_order' => $post_images['post_image_display_order']
                );
            }
            if ($data['post_details_array']['post_show_actual_email'] === '1') {
                $data['user_email'] = $data['post_details_array']['user_email'];
            } else {
                $data['user_email'] = parent::mask_characters($data['post_details_array']['user_email']);
            }
            if ($data['category_details_array']['category_type'] === '1') {
                $data['item_price_array'] = $this->item_price_array;
            } else if ($data['category_details_array']['category_type'] === '2') {
                $data['package_deals_array'] = $this->package_deals_array;
            }
            $data['post_details_array']['user_feedback_percentage'] = parent::get_user_feedback_percentage($data['post_details_array']['users_id']);
            $this->render_view($data);
        } else {
            redirect('search/advanced', 'refresh');
        }
    }

    function upload_images() {
        parent::upload_files();
    }

    function preview() {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
            if ($this->input->post('post_id') !== '') {
                $post_details_array = $this->Post_model->get_post_row_by_id($this->input->post('post_id'));
                if (count($post_details_array) > 0 && ($post_details_array['users_id'] === $_SESSION['user']['user_id'] || $_SESSION['user']['group_slug'] === 'administrator')) {

                } else {
                    redirect('dashboard', 'refresh');
                }
            }
            $this->_get_pricing_options();
            $this->load->model('Category_model');
            $category_slug = end(explode('/', $_SERVER['HTTP_REFERER']));
            $data['category_details_array'] = $this->Category_model->get_category_by_slug($category_slug);
            $this->load->model('Country_model');
            $country_details_array = $this->Country_model->get_country_by_id($this->input->post('countries_id'));
            $this->load->model('State_model');
            $state_details_array = $this->State_model->get_state_by_id($this->input->post('states_id'));
            $this->load->model('City_model');
            $city_details_array = $this->City_model->get_city_by_id($this->input->post('cities_id'));
            $data['post_details_array'] = array(
                'users_id' => $_SESSION['user']['user_id'],
                'categories_id' => $data['category_details_array']['category_id'],
                'post_show_actual_email' => $this->input->post('post_show_actual_email'),
                'post_communication_by_phone' => $this->input->post('post_communication_by_phone'),
                'post_communication_by_sms' => $this->input->post('post_communication_by_sms'),
                'post_contact_name' => $this->input->post('post_contact_name'),
                'post_contact_number' => $this->input->post('post_contact_number'),
                'user_feedbacks' => $_SESSION['user']['user_feedbacks'],
                'user_rating' => $_SESSION['user']['user_rating'],
                'post_title' => $this->input->post('post_title'),
                'post_slug' => url_title($this->input->post('post_title'), '-', TRUE),
                'post_description' => $this->input->post('post_description'),
                'post_show_on_map' => $this->input->post('post_show_on_map'),
                'post_street' => $this->input->post('post_street'),
                'post_cross_street' => $this->input->post('post_cross_street'),
                'country_name' => $country_details_array['country_name'],
                'state_name' => $state_details_array['state_name'],
                'city_name' => $city_details_array['city_name'],
                'post_zipcode' => $this->input->post('post_zipcode'),
                'time_zones_id' => $this->input->post('time_zones_id'),
                'post_daily_price' => $this->input->post('post_daily_price'),
                'post_weekly_price' => $this->input->post('post_weekly_price'),
                'post_monthly_price' => $this->input->post('post_monthly_price'),
                'post_custom_price' => $this->input->post('post_custom_price'),
                'post_deal_price_1' => $this->input->post('post_deal_price_1'),
                'post_deal_price_2' => $this->input->post('post_deal_price_2'),
                'post_deal_price_3' => $this->input->post('post_deal_price_3'),
                'post_deal_price_custom' => $this->input->post('post_deal_price_custom'),
                'post_status' => ($this->input->post('post_id')) ? '1' : '0',
                'post_created' => (isset($post_details_array['post_created'])) ? $post_details_array['post_created'] : ''
            );
            $data['post_details_array'] = array_merge($data['post_details_array'], $this->Post_model->get_time_zone_details_by_id($this->input->post('time_zones_id')));
            $data['post_details_array']['post_time_availability_array'] = array();
            for ($time_available = 1; $time_available < 8; $time_available++) {
                $column_name = $this->input->post('post_availability_' . $time_available);
                if (trim($column_name) !== '' && $this->input->post('post_availability_' . $time_available . '_from') !== '' && $this->input->post('post_availability_' . $time_available . '_to') !== '') {
                    $data['post_details_array']['post_time_availability_array'][] = array(
                        'post_availability_day' => $this->input->post('post_availability_' . $time_available),
                        'post_availability_from' => date('H:i:s', strtotime($this->input->post('post_availability_' . $time_available . '_from'))),
                        'post_availability_to' => date('H:i:s', strtotime($this->input->post('post_availability_' . $time_available . '_to')))
                    );
                }
            }
            $data['post_details_array']['post_images_array'] = array();
            if ($this->input->post('post_image_name') && count($this->input->post('post_image_name')) > 0) {
                foreach ($this->input->post('post_image_name') as $key => $post_image_name) {
                    if (trim($post_image_name) !== '') {
                        $data['post_details_array']['post_images_array'][] = array(
                            'post_image_name' => trim($post_image_name),
                            'post_image_type' => $this->input->post('post_image_type')[$key]
                        );
                    }
                }
            }
            $data['weekdays_array'] = $this->weekdays_array;
            if ($data['post_details_array']['post_show_actual_email'] === '1') {
                $data['user_email'] = $_SESSION['user']['user_email'];
            } else {
                $data['user_email'] = parent::mask_characters($_SESSION['user']['user_email']);
            }
            if ($data['category_details_array']['category_type'] == '1') {
                $data['item_price_array'] = $this->item_price_array;
            } else if ($data['category_details_array']['category_type'] == '2') {
                $data['package_deals_array'] = $this->package_deals_array;
            }
            $data['post_details_array']['user_feedback_percentage'] = parent::get_user_feedback_percentage();
            $this->render_view($data, '', 'post/view');
        } else {
            redirect('post', 'refresh');
        }
    }

    function checkout($post_id, $pricing_option_type) {
        if (isset($_SESSION['user']) && isset($_SESSION['user']['user_dob']) && $_SESSION['user']['user_dob'] == '0000-00-00') {
            if ($this->uri->uri_string != 'account/edit' && $this->uri->uri_string != 'auth/logout') {
                $this->session->set_flashdata('error_message', 'Please fill in the form below to proceed.');
                redirect('account/edit', 'refresh');
            }
        }
        $data = array();
        $this->_get_pricing_options();
        $this->load->library('encrypt');
        $this->load->config('paypal');
        $data['paypal_url'] = $this->config->item('paypal_url');
        $data['paypal_business_email'] = $this->config->item('paypal_business_email');
        $this->load->model('User_model');
        $data['user_details_array'] = $this->User_model->get_user_row_by_user_id($_SESSION['user']['user_id']);
        if ($data['user_details_array']['user_credit_card_number'] !== '') {
            $data['user_details_array']['masked_user_credit_card_number'] = parent::mask_characters($this->encrypt->decode($data['user_details_array']['user_credit_card_number']), 12, 'X');
        }
        $data['post_details_array'] = $this->Post_model->get_post_by_id($post_id);
        if (count($data['post_details_array']) > 0 && $data['post_details_array']['post_status'] === '1') {
            if ($data['post_details_array']['category_type'] === '1' && isset($data['post_details_array']['post_' . $pricing_option_type . '_price']) && $data['post_details_array']['post_' . $pricing_option_type . '_price'] > 0) {
                $data['post_payment_price'] = $data['post_details_array']['post_' . $pricing_option_type . '_price'];
                foreach ($this->item_price_array as $item_price) {
                    if ($item_price['pricing_option_type'] === $pricing_option_type) {
                        $data['pricing_options_id'] = $item_price['pricing_option_id'];
                    }
                }
            } else if ($data['post_details_array']['category_type'] === '2' && isset($data['post_details_array']['post_deal_price_' . $pricing_option_type]) && $data['post_details_array']['post_deal_price_' . $pricing_option_type] > 0) {
                $data['post_payment_price'] = $data['post_details_array']['post_deal_price_' . $pricing_option_type];
                foreach ($this->package_deals_array as $package_deal) {
                    if ($package_deal['pricing_option_type'] === $pricing_option_type) {
                        $data['pricing_options_id'] = $package_deal['pricing_option_id'];
                    }
                }
            } else {
                redirect('search/advanced', 'refresh');
            }
        } else {
            redirect('search/advanced', 'refresh');
        }
        $data['error_message'] = $this->session->flashdata('error_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->render_view($data);
    }

    function checkout_success($payment_processor = '', $invoice_id = 0) {
        if ($invoice_id > 0) {
            switch ($payment_processor) {
//				case 'paypal':
//					if ($this->input->get('st') == 'Completed') {
//						$invoice_status = '1';
//					} else {
//						$invoice_status = '0';
//					}
//					$invoice_update_array = array(
//						'invoice_transaction_id' => $this->input->get('tx'),
//						'invoice_paypal_amount' => $this->input->get('amt'),
//						'invoice_currency' => $this->input->get('cc'),
//						'invoice_status' => $invoice_status,
//						'invoice_paid_on' => date('Y-m-d H:i:s'),
//					);
//					break;
//				case 'firstdata':
//					$invoice_update_array = array(
//						'firstdata_transaction_id' => $this->session->flashdata('firstdata_transaction_id'),
//						'invoice_firstdata_amount' => $this->session->flashdata('invoice_firstdata_amount'),
//						'invoice_currency' => $this->session->flashdata('invoice_currency'),
//						'invoice_status' => '1',
//						'invoice_paid_on' => date('Y-m-d H:i:s'),
//					);
//					break;
                case 'braintree':
                    $invoice_update_array = array(
                        'invoice_transaction_id' => $this->session->flashdata('invoice_transaction_id'),
                        'invoice_blendedd_amount' => $this->session->flashdata('invoice_blendedd_amount'),
                        'invoice_merchant_amount' => $this->session->flashdata('invoice_merchant_amount'),
                        'invoice_currency' => $this->session->flashdata('invoice_currency'),
                        'invoice_status' => '1',
                        'invoice_paid_on' => date('Y-m-d H:i:s'),
                    );
                    break;
                default :
                    redirect(base_url() . 'dashboard', 'refresh');
                    break;
            }
            $this->load->model('Invoice_model');
            if ($this->Invoice_model->update($invoice_id, $invoice_update_array)) {
                $seller_details_array = $this->Invoice_model->get_seller_by_invoice_id($invoice_id);
                if ($seller_details_array['category_type'] === '1') {
                    $this->Post_model->update($seller_details_array['post_id'], array('post_status' => '0', 'post_modified' => date('Y-m-d H:i:s')));
                }
                $seller_details_array['post_url'] = base_url() . 'post/view/' . $seller_details_array['post_slug'] . '/' . $seller_details_array['post_id'];
                $buyer_details_array = $_SESSION['user'];
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
                $this->load->model('User_model');
                $user_total_sales = $this->Invoice_model->get_paid_invoices_count_by_user_id($_SESSION['user']['user_id']);
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
                $this->User_model->edit_user_by_user_id($_SESSION['user']['user_id'], array('user_rating' => $user_rating, 'user_modified' => date('Y-m-d H:i:s')));
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
                parent::regenerate_session();
                $this->session->set_flashdata('success_message', 'Payment is Successful.');
            } else {
                $this->session->set_flashdata('error_message', 'Something went wrong. Try again later.');
            }
            redirect('dashboard', 'refresh');
        }
    }

    function edit($post_id = 0) {
        if ($post_id > 0) {
            $data = array();
            $data['post_details_array'] = $this->Post_model->get_post_by_id($post_id);
            if ($data['post_details_array']['users_id'] !== $_SESSION['user']['user_id'] && $_SESSION['user']['group_slug'] !== 'administrator') {
                redirect('dashboard', 'refresh');
                return;
            }
            $this->load->model('Category_model');
            $data['category_details_array'] = $this->Category_model->get_category_type_by_id($data['post_details_array']['categories_id']);
            $this->_get_pricing_options();
            if ($this->input->post()) {
                if ($data['post_details_array']['users_id'] === $_SESSION['user']['user_id'] || $_SESSION['user']['group_slug'] === 'administrator') {
                    $this->load->library('form_validation');
                    $this->form_validation->set_error_delimiters('', '<br/>');
                    $this->form_validation->set_rules('post_show_actual_email', 'Show email', 'trim|required');
                    $this->form_validation->set_rules('post_title', 'Post Title', 'trim|required|min_length[10]');
                    $this->form_validation->set_rules('post_description', 'Description', 'trim|required|min_length[50]');
                    if ($data['category_details_array']['category_type'] == '1') {
                        $this->form_validation->set_rules('post_daily_price', 'Price', 'trim|callback__check_post_price');
                    } else if ($data['category_details_array']['category_type'] == '2') {
                        $this->form_validation->set_rules('post_deal_price_1', 'Price', 'trim|callback__check_post_deal_price');
                    }
                    $this->form_validation->set_rules('countries_id', 'Country', 'trim|required');
                    $this->form_validation->set_rules('states_id', 'State', 'trim|required');
                    $this->form_validation->set_rules('cities_id', 'City', 'trim|required');
                    $this->form_validation->set_rules('post_zipcode', 'Zip Code', 'trim|required|exact_length[5]');
                    $this->form_validation->set_rules('time_zones_id', 'Time Zone', 'trim|required');
                    $this->form_validation->set_rules('post_image_name', 'At least one image', 'required');
                    if ($this->form_validation->run()) {
                        $status = TRUE;
                        $date_time_now = date('Y-m-d H:i:s');
                        $this->load->model('City_model');
                        $city_details_array = $this->City_model->get_city_details_by_id($this->input->post('cities_id'));
                        $post_title_shadow = $this->input->post('post_title') . ' ' . $city_details_array['city_name'] . ' ' . $city_details_array['state_name'] . ' ' . $city_details_array['state_code'] . ' ' . $this->input->post('post_zipcode') . ' ' . $city_details_array['country_name'];
                        $post_update_array = array(
                            'categories_id' => $data['category_details_array']['category_id'],
                            'post_show_actual_email' => $this->input->post('post_show_actual_email'),
                            'post_communication_by_phone' => $this->input->post('post_communication_by_phone'),
                            'post_communication_by_sms' => $this->input->post('post_communication_by_sms'),
                            'post_contact_name' => $this->input->post('post_contact_name'),
                            'post_contact_number' => $this->input->post('post_contact_number'),
                            'post_title' => $this->input->post('post_title'),
                            'post_title_shadow' => $post_title_shadow,
                            'post_slug' => url_title($post_title_shadow, '-', TRUE),
                            'post_description' => strip_tags(trim($this->input->post('post_description'))),
                            'post_show_on_map' => $this->input->post('post_show_on_map'),
                            'post_street' => $this->input->post('post_street'),
                            'post_cross_street' => $this->input->post('post_cross_street'),
                            'cities_id' => $this->input->post('cities_id'),
                            'post_zipcode' => $this->input->post('post_zipcode'),
                            'time_zones_id' => $this->input->post('time_zones_id'),
                            'post_status' => '1',
                            'post_modified' => $date_time_now
                        );
                        $post_update_array['post_min_price'] = 999999;
                        if ($data['category_details_array']['category_type'] == '1') {
                            foreach ($this->item_price_array as $item_price) {
                                $post_update_array['post_' . $item_price['pricing_option_type'] . '_price'] = $this->input->post('post_' . $item_price['pricing_option_type'] . '_price');
                                if ($post_update_array['post_' . $item_price['pricing_option_type'] . '_price'] != 0 && $post_update_array['post_min_price'] > $post_update_array['post_' . $item_price['pricing_option_type'] . '_price']) {
                                    $post_update_array['post_min_price'] = $post_update_array['post_' . $item_price['pricing_option_type'] . '_price'];
                                }
                            }
                        } else if ($data['category_details_array']['category_type'] == '2') {
                            foreach ($this->package_deals_array as $package_deal) {
                                $post_update_array['post_deal_price_' . $package_deal['pricing_option_type']] = $this->input->post('post_deal_price_' . $package_deal['pricing_option_type']);
                                if ($post_update_array['post_deal_price_' . $package_deal['pricing_option_type']] != 0 && $post_update_array['post_min_price'] > $post_update_array['post_deal_price_' . $package_deal['pricing_option_type']]) {
                                    $post_update_array['post_min_price'] = $post_update_array['post_deal_price_' . $package_deal['pricing_option_type']];
                                }
                            }
                        }
                        if ($this->Post_model->update($post_id, $post_update_array)) {
                            $time_available_array = array();
                            for ($time_available = 1; $time_available < 8; $time_available++) {
                                $column_name = $this->input->post('post_availability_' . $time_available);
                                if (trim($column_name) !== '' && $this->input->post('post_availability_' . $time_available . '_from') !== '' && $this->input->post('post_availability_' . $time_available . '_to') !== '') {
                                    $time_available_array[] = array(
                                        'posts_id' => $post_id,
                                        'post_availability_day' => $this->input->post('post_availability_' . $time_available),
                                        'post_availability_from' => date('H:i:s', strtotime($this->input->post('post_availability_' . $time_available . '_from'))),
                                        'post_availability_to' => date('H:i:s', strtotime($this->input->post('post_availability_' . $time_available . '_to'))),
                                        'post_availability_created' => $date_time_now,
                                    );
                                }
                            }

                            $upload_directory = FCPATH . 'uploads/posts/' . date('Y/m/d/H/i/s', strtotime($data['post_details_array']['post_created']));
                            if (!is_dir($upload_directory)) {
                                mkdir($upload_directory, 0755, TRUE);
                            }
                            $image_insert_array = array();
                            $this->load->library('image_lib');
                            $counter = 0;
                            foreach ($this->input->post('post_image_name') as $key => $post_image_name) {
                                if ($this->input->post('post_image_type')[$key] === 'NEW') {
                                    if (trim($post_image_name) !== '' && is_file(FCPATH . 'uploads/' . $post_image_name)) {
                                        $post_image_name_array = explode('.', trim($post_image_name));
                                        $extension = array_pop($post_image_name_array);
                                        rename(FCPATH . 'uploads/' . trim($post_image_name), $upload_directory . '/' . trim($post_image_name));
                                        $config['source_image'] = $upload_directory . '/' . $post_image_name;
                                        $config['new_image'] = $upload_directory . '/' . implode('.', $post_image_name_array) . '_thumb.' . $extension;
                                        $config['maintain_ratio'] = TRUE;
                                        $config['width'] = 200;
                                        $config['height'] = 150;
                                        $config['quality'] = 100;
                                        $this->image_lib->initialize($config);
                                        $this->image_lib->resize();
                                        $this->image_lib->clear();
                                        $image_insert_array[] = array(
                                            'posts_id' => $post_id,
                                            'post_image_name' => trim($post_image_name),
                                            'post_image_display_order' => $counter,
                                            'post_image_status' => '1',
                                            'post_image_created' => $date_time_now
                                        );
                                        $counter++;
                                    }
                                }
                                if ($this->input->post('post_image_type')[$key] === 'OLD') {
                                    if (is_file(FCPATH . 'uploads/posts/' . date('Y/m/d/H/i/s', strtotime($data['post_details_array']['post_created'])) . '/' . $post_image_name)) {
                                        $image_insert_array[] = array(
                                            'posts_id' => $post_id,
                                            'post_image_name' => trim($post_image_name),
                                            'post_image_display_order' => $counter,
                                            'post_image_status' => '1',
                                            'post_image_created' => $date_time_now
                                        );
                                        $counter++;
                                    }
                                }
                            }
                            if (!$this->Post_model->delete_post_images_by_post_id($post_id)) {
                                $status = FALSE;
                            }
                            if (count($image_insert_array) > 0 && !$this->Post_model->create_post_images($image_insert_array)) {
                                $status = FALSE;
                            }
                            if (!$this->Post_model->delete_post_availabilities_by_post_id($post_id)) {
                                $status = FALSE;
                            }
                            if (count($time_available_array) > 0 && !$this->Post_model->create_post_availabilities($time_available_array)) {
                                $status = FALSE;
                            }
                            if ($status === TRUE) {
                                echo $post_id;
                                die;
                            }
                        } else {
                            die('0');
                        }
                    } else {
                        echo validation_errors();
                        die;
                    }
                }
                die('0');
            }
            $data['weekdays_array'] = $this->weekdays_array;
            $data['time_zones_array'] = $this->Post_model->get_all_time_zones();
            $this->load->model('Country_model');
            $this->load->model('State_model');
            $this->load->model('City_model');
            $data['countries_array'] = $this->Country_model->get_all_active_countries();
            $data['states_array'] = $this->State_model->get_active_states_by_country_id($data['post_details_array']['country_id']);
            $data['cities_array'] = $this->City_model->get_active_cities_by_state_id($data['post_details_array']['state_id']);
            if ($_SESSION['user']['group_slug'] == 'administrator') {
                $data['user_email'] = parent::mask_characters($data['post_details_array']['user_email']);
                $data['user_actual_email'] = $data['post_details_array']['user_email'];
            } else {
                $data['user_email'] = parent::mask_characters($_SESSION['user']['user_email']);
                $data['user_actual_email'] = $_SESSION['user']['user_email'];
            }
            $data['post_details_array']['post_time_availability_array'] = $this->Post_model->get_time_availability_by_post_id($post_id);
            $data['post_details_array']['post_images_array'] = $this->Post_model->get_images_by_post_id($post_id);
            if ($data['category_details_array']['category_type'] === '1') {
                $data['item_price_array'] = $this->item_price_array;
                $this->render_view($data, 'system', 'post/create_rental');
            } else if ($data['category_details_array']['category_type'] === '2') {
                $data['package_deals_array'] = $this->package_deals_array;
                $this->render_view($data, 'system', 'post/create_service');
            }
        } else {
            redirect('dashboard', 'refresh');
        }
    }

    /**
     * Update a post via ajax request
     */
    function update() {
        if ($this->input->post('post_id') !== '' && $this->input->is_ajax_request()) {
            $post_details_array = $this->Post_model->get_post_by_id($this->input->post('post_id'));
            if (count($post_details_array) > 0) {
                if ($post_details_array['users_id'] === $_SESSION['user']['user_id']) {
                    $post_update_array = $this->input->post();
                    unset($post_update_array['post_id']);
                    $post_update_array['post_modified'] = date('Y-m-d H:i:s');
                    if ($this->Post_model->update($post_details_array['post_id'], $post_update_array)) {
                        die('1');
                    }
                }
            }
        }
        die('0');
    }

    function get_posted_pager() {
        if ($this->input->post('post_id') &&
                $this->input->post('pager_section')) {
            switch ($this->input->post('pager_section')) {
                case 'item_posted':
                    $category_type = 1;
                    break;
                case 'service_posted':
                    $category_type = 2;
                    break;

                default:
                    break;
            }
            $pager_array = $this->Post_model->get_posted_pager($category_type, $this->input->post('post_id'), $_SESSION['user']['user_id'], $this->input->post('pager_type'), $this->input->post('post_status'), $this->input->post('post_within'));
            if (count($pager_array) > 0) {
                $next_pager_array = $this->Post_model->get_posted_pager($category_type, $pager_array['post_id'], $_SESSION['user']['user_id'], '<', $this->input->post('post_status'), $this->input->post('post_within'));
                $previous_pager_array = $this->Post_model->get_posted_pager($category_type, $pager_array['post_id'], $_SESSION['user']['user_id'], '>', $this->input->post('post_status'), $this->input->post('post_within'));
                $pager_array['pager_next'] = $pager_array['pager_previous'] = 'false';
                if (count($next_pager_array) > 0) {
                    $pager_array['pager_next'] = 'true';
                }
                if (count($previous_pager_array) > 0) {
                    $pager_array['pager_previous'] = 'true';
                }
                $pager_array['post_created_url'] = $pager_array['post_created'];
                $pager_array['post_created'] = date('M d, Y', strtotime($pager_array['post_created']));
                $pager_array['post_min_price'] = sprintf('%01.2f', $pager_array['post_min_price']);
                if ($pager_array['post_image_name'] !== '') {
                    $post_image_name_array = explode('.', $pager_array['post_image_name']);
                    $extension = array_pop($post_image_name_array);
                    if (is_file(FCPATH . 'uploads/posts' . date('/Y/m/d/H/i/s/', strtotime($pager_array['post_created_url'])) . implode('.', $post_image_name_array) . '_thumb.' . $extension)) {
                        $pager_array['post_image_url'] = base_url() . 'uploads/posts' . date('/Y/m/d/H/i/s/', strtotime($pager_array['post_created_url'])) . implode('.', $post_image_name_array) . '_thumb.' . $extension;
                    } else {
                        $pager_array['post_image_url'] = base_url() . 'assets/images/no-image-146x146.gif';
                    }
                } else {
                    $pager_array['post_image_url'] = base_url() . 'assets/images/no-image-146x146.gif';
                }
                parent::json_output($pager_array);
            }
        }
    }

    function get_purchased_pager() {
        if ($this->input->post('post_id') &&
                $this->input->post('pager_section')) {
            switch ($this->input->post('pager_section')) {
                case 'item_rented':
                    $category_type = 1;
                    break;
                case 'service_paid':
                    $category_type = 2;
                    break;

                default:
                    break;
            }
            $pager_array = $this->Post_model->get_purchased_pager($category_type, $this->input->post('post_id'), $_SESSION['user']['user_id'], $this->input->post('pager_type'), $this->input->post('post_within'));
            if (count($pager_array) > 0) {
                $next_pager_array = $this->Post_model->get_purchased_pager($category_type, $pager_array['post_id'], $_SESSION['user']['user_id'], '<', $this->input->post('post_within'));
                $previous_pager_array = $this->Post_model->get_purchased_pager($category_type, $pager_array['post_id'], $_SESSION['user']['user_id'], '>', $this->input->post('post_within'));
                $pager_array['pager_next'] = $pager_array['pager_previous'] = 'false';
                if (count($next_pager_array) > 0) {
                    $pager_array['pager_next'] = 'true';
                }
                if (count($previous_pager_array) > 0) {
                    $pager_array['pager_previous'] = 'true';
                }
                $pager_array['invoice_created'] = date('M d, Y', strtotime($pager_array['invoice_created']));
                $pager_array['invoice_amount'] = sprintf('%01.2f', $pager_array['invoice_amount']);
                $pager_array['post_image_url'] = parent::_prepare_post_image_url($pager_array);
                parent::json_output($pager_array);
            }
        }
    }

    function delete_post_image_by_id() {
        if ($this->input->post()) {
            $post_image = $this->Post_model->get_post_image_name_by_post_image_id($this->input->post('post_image_id'));
            if (count($post_image) > 0) {
                $post_image_name_array = explode('.', $post_image['post_image_name']);
                $extension = array_pop($post_image_name_array);
                if (is_file(FCPATH . 'uploads/posts' . date('/Y/m/d/H/i/s/', strtotime($post_image['post_created'])) . $post_image['post_image_name'])) {
                    unlink(FCPATH . 'uploads/posts' . date('/Y/m/d/H/i/s/', strtotime($post_image['post_created'])) . $post_image['post_image_name']);
                }
                if (is_file(FCPATH . 'uploads/posts' . date('/Y/m/d/H/i/s/', strtotime($post_image['post_created'])) . implode('.', $post_image_name_array) . '_thumb.' . $extension)) {
                    unlink(FCPATH . 'uploads/posts' . date('/Y/m/d/H/i/s/', strtotime($post_image['post_created'])) . implode('.', $post_image_name_array) . '_thumb.' . $extension);
                }
            }
            if ($this->Post_model->delete_post_image_id($this->input->post('post_image_id'))) {
                die('Image Deleted!!');
            } else {
                die('0');
            }
        }
    }

    function reply($post_slug = '', $post_id = 0) {
        if (is_numeric($post_id) && $post_id > 0 && $post_slug !== '') {
            $this->session->set_userdata('redirect_url', base_url() . 'post/view/' . $post_slug . '/' . $post_id . '#create_message_modal');
            redirect('login', 'refresh');
        } else {
            redirect('search/advanced', 'refresh');
        }
    }

//	function checkout_firstdata() {
//		if ($this->input->post()) {
//			$this->load->library('form_validation');
//			$this->form_validation->set_error_delimiters('', '<br/>');
//			$this->form_validation->set_rules('amount', '', 'trim|required');
//			$this->form_validation->set_rules('return', '', 'trim|required');
//			if ($this->form_validation->run()) {
//				$invoice_id = end(explode('/', $this->input->post('return')));
//				if (is_numeric($invoice_id)) {
//					$this->load->model('User_model');
//					$user_details_array = $this->User_model->get_user_row_by_user_id($_SESSION['user']['user_id']);
//					$this->load->library('encrypt');
//					if (isset($user_details_array['user_credit_card_number']) && $this->encrypt->decode($user_details_array['user_credit_card_number']) !== '') {
//						$purchase_response = parent::firstdata_cc_purchase($_SESSION['user']['user_id'], $invoice_id, $this->input->post('amount'), $this->encrypt->decode($user_details_array['user_credit_card_name']), $this->encrypt->decode($user_details_array['user_credit_card_number']), $this->encrypt->decode($user_details_array['user_credit_card_expiry_month']) . substr($this->encrypt->decode($user_details_array['user_credit_card_expiry_year']), 2, 2));
//						if (is_object($purchase_response)) {
//							if (isset($purchase_response->transaction_approved) && $purchase_response->transaction_approved == '1' && isset($purchase_response->bank_message) && $purchase_response->bank_message == 'Approved') {
//								$this->session->set_flashdata('invoice_firstdata_amount', $purchase_response->amount);
//								$this->session->set_flashdata('firstdata_transaction_id', $purchase_response->transaction_tag);
//								$this->session->set_flashdata('invoice_currency', $purchase_response->currency_code);
//								redirect($this->input->post('return'), 'refresh');
//								return;
//							} else {
//								$this->session->set_flashdata('error_message', $purchase_response->bank_message);
//								redirect($this->input->server('HTTP_REFERER'), 'refresh');
//								return;
//							}
//						} else {
//							$this->session->set_flashdata('error_message', $purchase_response);
//							redirect($this->input->server('HTTP_REFERER'), 'refresh');
//							return;
//						}
//					}
//				}
//			}
//		}
//		if ($this->input->server('HTTP_REFERER')) {
//			redirect($this->input->server('HTTP_REFERER'), 'refresh');
//			return;
//		}
//	}

    function checkout_braintree() {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_rules('amount', '', 'trim|required');
            $this->form_validation->set_rules('return', '', 'trim|required');
            if ($this->form_validation->run()) {
                $invoice_id = end(explode('/', $this->input->post('return')));
                if (is_numeric($invoice_id)) {
                    $this->load->model('User_model');
                    $user_details_array = $this->User_model->get_user_row_by_user_id($_SESSION['user']['user_id']);
                    $this->load->model('Invoice_model');
                    $seller_details_array = $this->Invoice_model->get_seller_by_invoice_id($invoice_id);
                    $this->load->library('encrypt');
                    if (isset($user_details_array['user_credit_card_number']) && $this->encrypt->decode($user_details_array['user_credit_card_number']) !== '') {
                        $purchase_response = parent::braintree_cc_sale($invoice_id, $seller_details_array['user_braintree_merchant_id'], $this->input->post('amount'), $this->encrypt->decode($user_details_array['user_credit_card_name']), $this->encrypt->decode($user_details_array['user_credit_card_expiry_month']), $this->encrypt->decode($user_details_array['user_credit_card_expiry_year']), $this->encrypt->decode($user_details_array['user_credit_card_number']), $this->encrypt->decode($user_details_array['user_credit_card_cvv']));
                        if (isset($purchase_response->success) && $purchase_response->success) {
                            $this->session->set_flashdata('invoice_blendedd_amount', $purchase_response->transaction->_attributes['serviceFeeAmount']);
                            $this->session->set_flashdata('invoice_merchant_amount', $purchase_response->transaction->_attributes['amount'] - $purchase_response->transaction->_attributes['serviceFeeAmount']);
                            $this->session->set_flashdata('invoice_transaction_id', $purchase_response->transaction->_attributes['id']);
                            $this->session->set_flashdata('invoice_currency', $purchase_response->transaction->_attributes['currencyIsoCode']);
                            redirect($this->input->post('return'), 'refresh');
                            return;
                        } else {
                            $this->session->set_flashdata('error_message', nl2br($purchase_response->_attributes['message']));
                            redirect($this->input->server('HTTP_REFERER'), 'refresh');
                            return;
                        }
                    }
                }
            }
        }
        if ($this->input->server('HTTP_REFERER')) {
            redirect($this->input->server('HTTP_REFERER'), 'refresh');
            return;
        }
    }

    function prohibit() {
        if ($this->input->post() && $this->input->server('HTTP_REFERER') && isset($_SESSION['user']['user_id'])) {
            $post_meta = str_replace(base_url() . 'post/view/', '', $this->input->server('HTTP_REFERER'));
            $post_meta_array = explode('/', $post_meta);
            if (count($post_meta_array) === 2 && isset($post_meta_array[1]) && is_numeric($post_meta_array[1])) {
                $post_details_array = $this->Post_model->get_post_by_id($post_meta_array[1]);
                if (count($post_details_array) > 0 && isset($post_details_array['post_title_shadow']) && $post_details_array['post_slug'] === $post_meta_array[0]) {
                    $exiting_prohibits_array = $this->Post_model->get_prohibits($post_details_array['post_id']);
                    foreach ($exiting_prohibits_array as $exiting_prohibit) {
                        if ($exiting_prohibit['prohibit_ip'] === $this->input->server('REMOTE_ADDR')) {
                            die('1');
                        }
                        if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] === $exiting_prohibit['users_id']) {
                            die('1');
                        }
                    }
                    $prohibit_insert_array = array(
                        'posts_id' => $post_details_array['post_id'],
                        'users_id' => isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : '0',
                        'prohibit_ip' => $this->input->server('REMOTE_ADDR'),
                        'prohibit_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                        'prohibit_created' => date('Y-m-d H:i:s')
                    );
                    if ($this->Post_model->prohibit($prohibit_insert_array) > 0) {
                        $post_prohibited_array = array(
                            'post_id' => $post_details_array['post_id'],
                            'post_title_shadow' => $post_details_array['post_title_shadow'],
                            'user_login' => $post_details_array['user_login'],
                            'user_full_name' => $post_details_array['user_first_name'] . ' ' . $post_details_array['user_last_name'],
                            'user_id' => $post_details_array['users_id'],
                            'post_url' => base_url() . 'post/view/' . $post_details_array['post_slug'] . '/' . $post_details_array['post_id'],
                        );
                        $email_id1 = parent::add_email_to_queue('', '', $this->config->item('admin_email'), '1', 'Post Prohibited - POST ID ' . $post_details_array['post_id'], $this->render_view($post_prohibited_array, 'emails', 'emails/templates/admin_post_prohibited', TRUE));
                        if ($email_id1 > 0) {
                            @file_get_contents(base_url() . 'emails/cron/' . $email_id1);
                        }
                        $email_id2 = parent::add_email_to_queue('', '', $post_details_array['user_email'], $post_details_array['users_id'], 'Your posting was flagged as prohibited - POST ID ' . $post_details_array['post_id'], $this->render_view($post_prohibited_array, 'emails', 'emails/templates/user_post_prohibited', TRUE));
                        if ($email_id2 > 0) {
                            @file_get_contents(base_url() . 'emails/cron/' . $email_id2);
                        }
                        if ($post_details_array['user_communication_via_sms']) {
                            parent::send_sms(str_replace('-', '', $post_details_array['user_primary_contact']), "One of your posting on Blendedd has been flagged. Please check your account and posting");
                        }
                        if (count($exiting_prohibits_array) === 4) {
                            $this->Post_model->update($post_details_array['post_id'], array('post_status' => '-1'));
                        }
                        die('1');
                    }
                }
            }
        }
        die('0');
    }

    function email_to_friend() {
        if ($this->input->post() && $this->input->server('HTTP_REFERER')) {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('', '<br/>');
            if (!isset($_SESSION['user']['user_id'])) {
                $this->form_validation->set_rules('sender_email_address', 'Your Email Address', 'trim|required|valid_email');
            }
            $this->form_validation->set_rules('destination_email_address', 'Destination Email Address', 'trim|required|valid_email');
            if ($this->form_validation->run()) {
                $post_meta = str_replace(base_url() . 'post/view/', '', $this->input->server('HTTP_REFERER'));
                $post_meta_array = explode('/', $post_meta);
                if (count($post_meta_array) === 2 && isset($post_meta_array[1]) && is_numeric($post_meta_array[1])) {
                    $post_details_array = $this->Post_model->get_post_by_id($post_meta_array[1]);
                    if (count($post_details_array) > 0 && isset($post_details_array['post_title_shadow']) && $post_details_array['post_slug'] === $post_meta_array[0]) {
                        $email_details_array = array(
                            'sender_email_address' => (isset($_SESSION['user']['user_email'])) ? $_SESSION['user']['user_email'] : $this->input->post('sender_email_address'),
                            'destination_email_address' => $this->input->post('destination_email_address'),
                            'post_title' => $post_details_array['post_title'],
                            'post_url' => base_url() . 'post/view/' . $post_details_array['post_slug'] . '/' . $post_details_array['post_id']
                        );
                        $email_id = parent::add_email_to_queue('', '', $email_details_array['destination_email_address'], '0', $post_details_array['post_title'], $this->render_view($email_details_array, 'emails', 'emails/templates/email_to_friend', TRUE));
                        if ($email_id > 0) {
                            @file_get_contents(base_url() . 'emails/cron/' . $email_id);
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

    function post_status() {
        if ($this->input->post('post_id') && $this->input->is_ajax_request()) {
            $post_update_array = array(
                'post_status' => '-1',
                'post_modified' => date('Y-m-d H:i:s')
            );
            if ($this->Post_model->update($this->input->post('post_id'), $post_update_array)) {
                die('1');
            } else {
                die('0');
            }
        }
    }

}
