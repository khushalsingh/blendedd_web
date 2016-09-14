<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth extends MY_Controller {

    public $public_methods = array('signup', 'login', 'logout', 'recover', 'validate_email', 'validate_login', 'verify', 'change_password');

    function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    function index() {
        redirect('login', 'refresh');
    }

    function login($social_network = '') {
        $userdata = $this->session->all_userdata();
        if ((
                (isset($userdata['user_remember'])) &&
                ($userdata['user_remember'] === '1') &&
                (isset($userdata['user_login'])) &&
                ($userdata['user_login'] !== '')) ||
                (isset($_SESSION['user']) && count($_SESSION['user']) > 0)
        ) {
            if (isset($_SESSION['user']) && count($_SESSION['user']) > 0) {
                $user_details_array = $_SESSION['user'];
            } else {
                $user_details_array = $this->Auth_model->login($userdata['user_login']);
                $_SESSION['user'] = $user_details_array;
            }
            $this->Auth_model->update_user_login($user_details_array['user_id']);
            $this->Auth_model->add_login_log(array(
                'users_id' => $user_details_array['user_id'],
                'login_log_from' => '1',
                'login_log_mode' => 'session',
                'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                'login_log_created' => date('Y-m-d H:i:s')
            ));
            parent::regenerate_session();
            if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                $this->session->unset_userdata('redirect_url');
                redirect($userdata['redirect_url'], 'refresh');
            } else {
                redirect('dashboard', 'refresh');
            }
        }
        if (in_array($social_network, array('facebook', 'google'))) {
            $this->load->file(APPPATH . 'third_party/oauth_api/http.php');
            $this->load->file(APPPATH . 'third_party/oauth_api/oauth_client.php');
            $this->load->config('oauth');
            $client = new oauth_client_class;
            $client->debug = FALSE;
            $client->debug_http = FALSE;
            $client->redirect_uri = base_url() . 'login/' . $social_network;
            switch ($social_network) {
                case 'facebook':
                    if ($this->input->get('error')) {
                        $data['auth_error'] = '1';
                        break;
                    }
                    $client->server = 'Facebook';
                    $client->client_id = $this->config->item('facebook_app_id');
                    $client->client_secret = $this->config->item('facebook_app_secret');
                    $client->scope = 'email';
                    if (($success = $client->Initialize())) {
                        if (($success = $client->Process())) {
                            if (strlen($client->access_token)) {
                                $success = $client->CallAPI(
                                        'https://graph.facebook.com/me', 'GET', array(), array('FailOnAccessError' => true), $user);
                            }
                        }
                        $success = $client->Finalize($success);
                    }
                    if ($success) {
                        if (isset($user)) {
                            $this->load->model('User_model');
                            $user_details_array = $this->User_model->get_user_by_social_id($user->id, $social_network);
                            if (count($user_details_array) > 0) {
                                $_SESSION['user'] = $user_details_array;
                                $this->Auth_model->update_user_login($user_details_array['user_id']);
                                $this->Auth_model->add_login_log(array(
                                    'users_id' => $user_details_array['user_id'],
                                    'login_log_from' => '1',
                                    'login_log_mode' => $social_network,
                                    'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                                    'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                                    'login_log_created' => date('Y-m-d H:i:s')
                                ));
                                if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                                    $this->session->unset_userdata('redirect_url');
                                    redirect($userdata['redirect_url'], 'refresh');
                                }
                                redirect('dashboard', 'refresh');
                            } else {
                                $user_details_array = $this->User_model->get_user_by_email($user->email);
                                if (count($user_details_array) > 0) {
                                    $user_update_array = array(
                                        'user_' . $social_network . '_id' => $user->id,
                                        'user_modified' => date('Y-m-d H:i:s')
                                    );
                                    if ($this->Auth_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
                                        $_SESSION['user'] = $user_details_array;
                                        $this->Auth_model->update_user_login($user_details_array['user_id']);
                                        $this->Auth_model->add_login_log(array(
                                            'users_id' => $user_details_array['user_id'],
                                            'login_log_from' => '1',
                                            'login_log_mode' => $social_network,
                                            'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                                            'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                                            'login_log_created' => date('Y-m-d H:i:s')
                                        ));
                                        if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                                            $this->session->unset_userdata('redirect_url');
                                            redirect($userdata['redirect_url'], 'refresh');
                                        }
                                        redirect('dashboard', 'refresh');
                                    }
                                } else {
                                    redirect('signup/' . $social_network, 'refresh');
                                }
                            }
                        }
                    } else {
                        redirect('login', 'refresh');
                    }
                    break;
                case 'google':
                    if ($this->input->get('error')) {
                        $data['auth_error'] = '1';
                        break;
                    }
                    $client->server = 'Google';
                    $client->client_id = $this->config->item('google_app_id');
                    $client->client_secret = $this->config->item('google_app_secret');
                    $client->scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';
                    if (($success = $client->Initialize())) {
                        if (($success = $client->Process())) {
                            if (strlen($client->authorization_error)) {
                                $client->error = $client->authorization_error;
                                $success = false;
                            } elseif (strlen($client->access_token)) {
                                $success = $client->CallAPI(
                                        'https://www.googleapis.com/oauth2/v1/userinfo', 'GET', array(), array('FailOnAccessError' => true), $user);
                            }
                        }
                        $success = $client->Finalize($success);
                    }
                    if ($success) {
                        if (isset($user)) {
                            $this->load->model('User_model');
                            $user_details_array = $this->User_model->get_user_by_social_id($user->id, $social_network);
                            if (count($user_details_array) > 0) {
                                $_SESSION['user'] = $user_details_array;
                                $this->Auth_model->update_user_login($user_details_array['user_id']);
                                $this->Auth_model->add_login_log(array(
                                    'users_id' => $user_details_array['user_id'],
                                    'login_log_from' => '1',
                                    'login_log_mode' => $social_network,
                                    'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                                    'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                                    'login_log_created' => date('Y-m-d H:i:s')
                                ));
                                if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                                    $this->session->unset_userdata('redirect_url');
                                    redirect($userdata['redirect_url'], 'refresh');
                                }
                                redirect('dashboard', 'refresh');
                            } else {
                                $user_details_array = $this->User_model->get_user_by_email($user->email);
                                if (count($user_details_array) > 0) {
                                    $user_update_array = array(
                                        'user_' . $social_network . '_id' => $user->id,
                                        'user_modified' => date('Y-m-d H:i:s')
                                    );
                                    if ($this->Auth_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
                                        $_SESSION['user'] = $user_details_array;
                                        $this->Auth_model->update_user_login($user_details_array['user_id']);
                                        $this->Auth_model->add_login_log(array(
                                            'users_id' => $user_details_array['user_id'],
                                            'login_log_from' => '1',
                                            'login_log_mode' => $social_network,
                                            'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                                            'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                                            'login_log_created' => date('Y-m-d H:i:s')
                                        ));
                                        if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                                            $this->session->unset_userdata('redirect_url');
                                            redirect($userdata['redirect_url'], 'refresh');
                                        }
                                        redirect('dashboard', 'refresh');
                                    }
                                } else {
                                    redirect('signup/' . $social_network, 'refresh');
                                }
                            }
                        }
                    } else {
                        redirect('login', 'refresh');
                    }
                    break;
                default:
                    break;
            }
        }
        $data = array();
        $this->load->helper('form');
        if ($this->input->post()) {
            if (!isset($_SESSION['login_failed_count'])) {
                $_SESSION['login_failed_count'] = 0;
            }
            $this->load->library('form_validation');
            $this->form_validation->set_rules('user_login', 'Email', 'trim|required');
            $this->form_validation->set_rules('user_login_password', 'Password', 'trim|required');
            if ($_SESSION['login_failed_count'] > 2) {
                $this->form_validation->set_rules('captcha_image', 'Secure Image', 'trim|required|exact_length[6]|numeric|callback_validate_captcha');
            }
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            if ($this->form_validation->run()) {
                $user_details_array = $this->Auth_model->login(base64_decode(base64_decode(trim($this->input->post('user_login')))));
                $this->load->library('encrypt');
                if (
                        count($user_details_array) > 0 &&
                        strtolower(base64_decode(base64_decode($this->input->post('user_login_password')))) === md5(md5(strtolower($this->encrypt->decode($user_details_array['user_password_hash'], $user_details_array['user_login_password']))))
                ) {
                    $_SESSION['user'] = $user_details_array;
                    if ($this->input->post('user_remember') && $this->input->post('user_remember') === '1') {
                        $this->session->set_userdata(array('user_id' => $user_details_array['user_id'], 'user_login' => $user_details_array['user_login'], 'user_remember' => '1'));
                    }
                    $this->Auth_model->update_user_login($user_details_array['user_id']);
                    $this->Auth_model->add_login_log(array(
                        'users_id' => $user_details_array['user_id'],
                        'login_log_from' => '1',
                        'login_log_mode' => 'email',
                        'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                        'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                        'login_log_created' => date('Y-m-d H:i:s')
                    ));
                    unset($_SESSION['login_failed_count']);
                    if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                        $this->session->unset_userdata('redirect_url');
                        die($userdata['redirect_url']);
                    }
                    die('1');
                }
            }
            $_SESSION['login_failed_count']++;
            if ($_SESSION['login_failed_count'] > 2) {
                die('-1');
            }
            die('0');
        }
        if (isset($_SESSION['login_failed_count']) && $_SESSION['login_failed_count'] > 2) {
            $data['captcha_image'] = parent::create_captcha();
        }
        $this->render_view($data, 'auth');
    }

    function logout() {
        session_destroy();
        $this->session->sess_destroy();
        redirect('login', 'refresh');
    }

    function signup($social_network = '') {
        $userdata = $this->session->all_userdata();
        if (in_array($social_network, array('facebook', 'google'))) {
            $this->load->file(APPPATH . 'third_party/oauth_api/http.php');
            $this->load->file(APPPATH . 'third_party/oauth_api/oauth_client.php');
            $this->load->config('oauth');
            $client = new oauth_client_class;
            $client->debug = FALSE;
            $client->debug_http = FALSE;
            $client->redirect_uri = base_url() . 'signup/' . $social_network;
            switch ($social_network) {
                case 'facebook':
                    if ($this->input->get('error')) {
                        $data['auth_error'] = '1';
                        break;
                    }
                    $client->server = 'Facebook';
                    $client->client_id = $this->config->item('facebook_app_id');
                    $client->client_secret = $this->config->item('facebook_app_secret');
                    $client->scope = 'email';
                    if (($success = $client->Initialize())) {
                        if (($success = $client->Process())) {
                            if (strlen($client->access_token)) {
                                $success = $client->CallAPI(
                                        'https://graph.facebook.com/me', 'GET', array(), array('FailOnAccessError' => true), $user);
                            }
                        }
                        $success = $client->Finalize($success);
                    }
                    if ($success) {
                        if (isset($user)) {
                            $this->load->model('User_model');
                            $user_details_array = $this->User_model->get_user_by_social_id($user->id, $social_network);
                            if (count($user_details_array) > 0) {
                                $_SESSION['user'] = $user_details_array;
                                $this->Auth_model->update_user_login($user_details_array['user_id']);
                                $this->Auth_model->add_login_log(array(
                                    'users_id' => $user_details_array['user_id'],
                                    'login_log_from' => '1',
                                    'login_log_mode' => $social_network,
                                    'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                                    'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                                    'login_log_created' => date('Y-m-d H:i:s')
                                ));
                                if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                                    $this->session->unset_userdata('redirect_url');
                                    redirect($userdata['redirect_url'], 'refresh');
                                }
                                redirect('dashboard', 'refresh');
                            } else {
                                $user_details_array = $this->User_model->get_user_by_email($user->email);
                                if (count($user_details_array) > 0) {
                                    $user_update_array = array(
                                        'user_' . $social_network . '_id' => $user->id,
                                        'user_modified' => date('Y-m-d H:i:s')
                                    );
                                    if ($this->Auth_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
                                        $_SESSION['user'] = $user_details_array;
                                        $this->Auth_model->update_user_login($user_details_array['user_id']);
                                        $this->Auth_model->add_login_log(array(
                                            'users_id' => $user_details_array['user_id'],
                                            'login_log_from' => '1',
                                            'login_log_mode' => $social_network,
                                            'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                                            'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                                            'login_log_created' => date('Y-m-d H:i:s')
                                        ));
                                        if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                                            $this->session->unset_userdata('redirect_url');
                                            redirect($userdata['redirect_url'], 'refresh');
                                        }
                                        redirect('dashboard', 'refresh');
                                    }
                                } else {
                                    $time_now = date('Y-m-d H:i:s');
                                    $user_login_password = parent::generate_random_string();
                                    $user_insert_array = array(
                                        'user_' . $social_network . '_id' => $user->id,
                                        'groups_id' => '3',
                                        'user_login' => $user->id,
                                        'user_first_name' => $user->first_name,
                                        'user_last_name' => $user->last_name,
                                        'user_email' => $user->email,
                                        'user_login_salt' => md5($time_now),
                                        'user_login_password' => md5(md5(md5($time_now) . $user_login_password)),
                                        'user_password_hash' => $this->encrypt->encode($user_login_password, md5(md5(md5($time_now) . $user_login_password))),
                                        'user_security_hash' => md5($time_now . $user_login_password),
                                        'user_communication_via_email' => '1',
                                        'user_agreement' => '1',
                                        'user_newsletter_subscription' => '1',
                                        'user_created' => $time_now,
                                    );
                                    $user_id = $this->User_model->insert($user_insert_array);
                                    if ($user_id > 0) {
                                        $email_id1 = parent::add_email_to_queue('', '', $user_insert_array['user_email'], '0', 'Registration Received', $this->render_view($user_insert_array, 'emails', 'emails/templates/register_' . $social_network, TRUE));
                                        $email_id2 = parent::add_email_to_queue('', '', $this->config->item('admin_email'), '1', 'Registration Received', $this->render_view($user_insert_array, 'emails', 'emails/templates/admin_register_' . $social_network, TRUE));
                                        if ($email_id1 > 0 && $email_id2 > 0) {
                                            @file_get_contents(base_url() . 'emails/cron/' . $email_id1);
                                            @file_get_contents(base_url() . 'emails/cron/' . $email_id2);
                                        }
                                        $user_details_array = $this->User_model->get_user_by_social_id($user->id, $social_network);
                                        $_SESSION['user'] = $user_details_array;
                                        $this->Auth_model->update_user_login($user_details_array['user_id']);
                                        $this->Auth_model->add_login_log(array(
                                            'users_id' => $user_details_array['user_id'],
                                            'login_log_from' => '1',
                                            'login_log_mode' => $social_network,
                                            'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                                            'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                                            'login_log_created' => date('Y-m-d H:i:s')
                                        ));
                                        if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                                            $this->session->unset_userdata('redirect_url');
                                            redirect($userdata['redirect_url'], 'refresh');
                                        }
                                        $this->session->set_flashdata('warning_message', 'Since you have logged in using social media account, it is mandatory to fill the following details to help us serve you better.');
                                        redirect('account/edit', 'refresh');
                                    } else {
                                        redirect('signup', 'refresh');
                                    }
                                }
                            }
                        }
                    } else {
                        redirect('signup', 'refresh');
                    }
                    break;
                case 'google':
                    if ($this->input->get('error')) {
                        $data['auth_error'] = '1';
                        break;
                    }
                    $client->server = 'Google';
                    $client->client_id = $this->config->item('google_app_id');
                    $client->client_secret = $this->config->item('google_app_secret');
                    $client->scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';
                    if (($success = $client->Initialize())) {
                        if (($success = $client->Process())) {
                            if (strlen($client->authorization_error)) {
                                $client->error = $client->authorization_error;
                                $success = false;
                            } elseif (strlen($client->access_token)) {
                                $success = $client->CallAPI(
                                        'https://www.googleapis.com/oauth2/v1/userinfo', 'GET', array(), array('FailOnAccessError' => true), $user);
                            }
                        }
                        $success = $client->Finalize($success);
                    }
                    if ($success) {
                        if (isset($user)) {
                            $this->load->model('User_model');
                            $user_details_array = $this->User_model->get_user_by_social_id($user->id, $social_network);
                            if (count($user_details_array) > 0) {
                                $_SESSION['user'] = $user_details_array;
                                $this->Auth_model->update_user_login($user_details_array['user_id']);
                                $this->Auth_model->add_login_log(array(
                                    'users_id' => $user_details_array['user_id'],
                                    'login_log_from' => '1',
                                    'login_log_mode' => $social_network,
                                    'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                                    'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                                    'login_log_created' => date('Y-m-d H:i:s')
                                ));
                                if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                                    $this->session->unset_userdata('redirect_url');
                                    redirect($userdata['redirect_url'], 'refresh');
                                }
                                redirect('dashboard', 'refresh');
                            } else {
                                $user_details_array = $this->User_model->get_user_by_email($user->email);
                                if (count($user_details_array) > 0) {
                                    $user_update_array = array(
                                        'user_' . $social_network . '_id' => $user->id,
                                        'user_modified' => date('Y-m-d H:i:s')
                                    );
                                    if ($this->Auth_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
                                        $_SESSION['user'] = $user_details_array;
                                        $this->Auth_model->update_user_login($user_details_array['user_id']);
                                        $this->Auth_model->add_login_log(array(
                                            'users_id' => $user_details_array['user_id'],
                                            'login_log_from' => '1',
                                            'login_log_mode' => $social_network,
                                            'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                                            'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                                            'login_log_created' => date('Y-m-d H:i:s')
                                        ));
                                        if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                                            $this->session->unset_userdata('redirect_url');
                                            redirect($userdata['redirect_url'], 'refresh');
                                        }
                                        redirect('dashboard', 'refresh');
                                    }
                                } else {
                                    $time_now = date('Y-m-d H:i:s');
                                    $user_login_password = parent::generate_random_string();
                                    $user_full_name_array = explode(' ', $user->name);
                                    $user_insert_array = array(
                                        'user_' . $social_network . '_id' => $user->id,
                                        'groups_id' => '3',
                                        'user_login' => $user->id,
                                        'user_first_name' => isset($user_full_name_array[0]) ? $user_full_name_array[0] : '',
                                        'user_last_name' => isset($user_full_name_array[1]) ? $user_full_name_array[1] : '',
                                        'user_email' => $user->email,
                                        'user_login_salt' => md5($time_now),
                                        'user_login_password' => md5(md5(md5($time_now) . $user_login_password)),
                                        'user_password_hash' => $this->encrypt->encode($user_login_password, md5(md5(md5($time_now) . $user_login_password))),
                                        'user_security_hash' => md5($time_now . $user_login_password),
                                        'user_communication_via_email' => '1',
                                        'user_agreement' => '1',
                                        'user_newsletter_subscription' => '1',
                                        'user_created' => $time_now,
                                    );
                                    $user_id = $this->User_model->insert($user_insert_array);
                                    if ($user_id > 0) {
                                        $email_id1 = parent::add_email_to_queue('', '', $user_insert_array['user_email'], '0', 'Registration Received', $this->render_view($user_insert_array, 'emails', 'emails/templates/register_' . $social_network, TRUE));
                                        $email_id2 = parent::add_email_to_queue('', '', $this->config->item('admin_email'), '1', 'Registration Received', $this->render_view($user_insert_array, 'emails', 'emails/templates/admin_register_' . $social_network, TRUE));
                                        if ($email_id1 > 0 && $email_id2 > 0) {
                                            @file_get_contents(base_url() . 'emails/cron/' . $email_id1);
                                            @file_get_contents(base_url() . 'emails/cron/' . $email_id2);
                                        }
                                        $user_details_array = $this->User_model->get_user_by_social_id($user->id, $social_network);
                                        $_SESSION['user'] = $user_details_array;
                                        $this->Auth_model->update_user_login($user_details_array['user_id']);
                                        $this->Auth_model->add_login_log(array(
                                            'users_id' => $user_details_array['user_id'],
                                            'login_log_from' => '1',
                                            'login_log_mode' => $social_network,
                                            'login_log_ip_address' => $this->input->server('REMOTE_ADDR'),
                                            'login_log_user_agent' => $this->input->server('HTTP_USER_AGENT'),
                                            'login_log_created' => date('Y-m-d H:i:s')
                                        ));
                                        if (isset($userdata['redirect_url']) && $userdata['redirect_url'] !== '') {
                                            $this->session->unset_userdata('redirect_url');
                                            redirect($userdata['redirect_url'], 'refresh');
                                        }
                                        $this->session->set_flashdata('warning_message', 'Since you have logged in using social media account, it is mandatory to fill the following details to help us serve you better.');
                                        redirect('account/edit', 'refresh');
                                    } else {
                                        redirect('signup', 'refresh');
                                    }
                                }
                            }
                        }
                    } else {
                        redirect('signup', 'refresh');
                    }
                    break;
                default:
                    break;
            }
        }
        $data = array();
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->load->library('encrypt');
            $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');
            if ($this->input->post('user_type') === 'business') {
                $this->form_validation->set_rules('business_legal_name', 'Business Legal Name', 'trim|required');
                $this->form_validation->set_rules('business_tax_id', 'Business Tax ID', 'trim|required|is_unique[users.business_tax_id]');
            }
            $this->form_validation->set_rules('user_first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('user_last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('user_dob', 'Date Of Birth', 'trim|required');
            $this->form_validation->set_rules('user_login', 'User ID', 'trim|required|is_unique[users.user_login]|is_unique[registrations.user_login]');
            $this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|is_unique[users.user_email]|is_unique[registrations.user_email]|is_unique[users.user_paypal_email_address]|is_unique[registrations.user_paypal_email_address]');
            $this->form_validation->set_rules('confirm_user_email', 'Confirm Email', 'trim|required|matches[user_email]');
            $this->form_validation->set_rules('user_login_password', 'Password', 'trim|required|min_length[8]|callback__validate_password');
            $this->form_validation->set_rules('confirm_user_login_password', 'Confirm Password', 'trim|required|matches[user_login_password]');
            $this->form_validation->set_rules('user_primary_contact', 'Primary Contact Number', 'trim|required|is_unique[users.user_primary_contact]|is_unique[registrations.user_primary_contact]');
            $this->form_validation->set_rules('user_facebook_url', 'Facebook Link', 'trim|valid_url');
            $this->form_validation->set_rules('user_twitter_url', 'Twitter Link', 'trim||valid_url');
            $this->form_validation->set_rules('user_linkedin_url', 'LinkedIn Link', 'trim|valid_url');
            $this->form_validation->set_rules('user_instagram_url', 'Instagram Link', 'trim|valid_url');
//			$this->form_validation->set_rules('user_security_question_1', 'Security Question 1', 'trim|required');
//			$this->form_validation->set_rules('user_security_answer_1', 'Security Question Answer', 'trim|required');
//			$this->form_validation->set_rules('user_security_question_2', 'Security Question 2', 'trim|required');
//			$this->form_validation->set_rules('user_security_answer_2', 'Security Question Answer', 'trim|required');
//			$this->form_validation->set_rules('user_security_question_3', 'Security Question 3', 'trim|required');
//			$this->form_validation->set_rules('user_security_answer_3', 'Security Question Answer', 'trim|required');
            $this->form_validation->set_rules('user_address_line_1', 'Street Address 1', 'trim|required');
            $this->form_validation->set_rules('user_address_line_2', 'Street Address 2', 'trim');
            $this->form_validation->set_rules('countries_id', 'Country', 'trim|required');
            $this->form_validation->set_rules('states_id', 'State/Province', 'trim|required');
            $this->form_validation->set_rules('cities_id', 'City', 'trim|required');
            $this->form_validation->set_rules('user_zipcode', 'Zip Code', 'trim|required');
            $this->form_validation->set_rules('registration_for', 'Registration Purpose', 'trim|required');
//			if (in_array($this->input->post('user_financial_info'), array(
//						'buy_paypal_account_info',
//						'sell_paypal_account_info',
//						'both_paypal_bank_account_info'
//					))) {
//				$this->form_validation->set_rules('user_paypal_email_address', 'PayPal ID', 'trim|valid_email|is_unique[users.user_email]|is_unique[registrations.user_email]|is_unique[users.user_paypal_email_address]|is_unique[registrations.user_paypal_email_address]');
//			}
            if (in_array($this->input->post('user_financial_info'), array(
                        'buy_credit_card_info',
                        'both_credit_card_bank_account_info'
                    ))) {
                $this->form_validation->set_rules('user_credit_card_name', 'Credit Card Name', 'trim|required');
                $this->form_validation->set_rules('user_credit_card_number', 'Credit Card Number', 'trim|required|callback__validate_unique_credit_card');
                $this->form_validation->set_rules('user_credit_card_expiry_month', 'Expiry Month', 'trim|required');
                $this->form_validation->set_rules('user_credit_card_expiry_year', 'Expiry Year', 'trim|required');
                $this->form_validation->set_rules('user_credit_card_cvv', 'CVV Number', 'trim|required');
            }
            if (in_array($this->input->post('user_financial_info'), array(
                        'sell_bank_account_info',
                        'both_paypal_bank_account_info',
                        'both_credit_card_bank_account_info'
                    ))) {
                $this->form_validation->set_rules('user_bank_account_number', 'Bank Account Number', 'trim|required|callback__validate_unique_bank_account');
                $this->form_validation->set_rules('user_bank_name', 'Bank Name', 'trim|required');
                $this->form_validation->set_rules('user_bank_route_code', 'Bank Route Code', 'trim|required');
                $this->form_validation->set_rules('user_bank_account_online', 'Bank Account Online', 'trim');
            }
//			$this->form_validation->set_rules('user_communication_via_email', 'Email Communication', 'trim|callback__check_communication_preferences');
            $this->form_validation->set_rules('user_communication_via_phone_call', 'Call Communication', 'trim');
            $this->form_validation->set_rules('user_communication_via_sms', 'SMS Communication', 'trim');
            $this->form_validation->set_rules('user_agreement', 'Agreement', 'trim|required');
            $this->form_validation->set_error_delimiters("", "<br/>");
            if ($this->form_validation->run()) {
                $time_now = date('Y-m-d H:i:s');
                $user_dob_array = explode('/', $this->input->post('user_dob'));
                $registration_insert_array = array(
                    'groups_id' => '3',
                    'user_type' => ($this->input->post('user_type') == 'business') ? '1' : '2',
                    'business_legal_name' => ($this->input->post('user_type') == 'business') ? $this->input->post('business_legal_name') : '',
                    'business_tax_id' => ($this->input->post('user_type') == 'business') ? $this->input->post('business_tax_id') : '',
                    'user_first_name' => $this->input->post('user_first_name'),
                    'user_last_name' => $this->input->post('user_last_name'),
                    'user_dob' => $user_dob_array[2] . '-' . $user_dob_array[0] . '-' . $user_dob_array[1],
                    'user_ssn' => $this->input->post('user_ssn'),
                    'user_login' => $this->input->post('user_login'),
                    'user_email' => $this->input->post('user_email'),
                    'user_login_salt' => md5($time_now),
                    'user_login_password' => md5(md5(md5($time_now) . $this->input->post('user_login_password'))),
                    'user_password_hash' => $this->encrypt->encode($this->input->post('user_login_password'), md5(md5(md5($time_now) . $this->input->post('user_login_password')))),
                    'user_security_hash' => md5($time_now . $this->input->post('user_login_password')),
                    'user_primary_contact' => $this->input->post('user_primary_contact'),
                    'user_facebook_url' => $this->input->post('user_facebook_url'),
                    'user_twitter_url' => $this->input->post('user_twitter_url'),
                    'user_linkedin_url' => $this->input->post('user_linkedin_url'),
                    'user_instagram_url' => $this->input->post('user_instagram_url'),
//					'user_security_question_1' => $this->input->post('user_security_question_1'),
//					'user_security_answer_1' => $this->encrypt->encode($this->input->post('user_security_answer_1')),
//					'user_security_question_2' => $this->input->post('user_security_question_2'),
//					'user_security_answer_2' => $this->encrypt->encode($this->input->post('user_security_answer_2')),
//					'user_security_question_3' => $this->input->post('user_security_question_3'),
//					'user_security_answer_3' => $this->encrypt->encode($this->input->post('user_security_answer_3')),
                    'user_address_line_1' => $this->input->post('user_address_line_1'),
                    'user_address_line_2' => $this->input->post('user_address_line_2'),
                    'cities_id' => $this->input->post('cities_id'),
                    'user_zipcode' => $this->input->post('user_zipcode'),
                    'user_communication_via_email' => '1',
                    'user_communication_via_phone_call' => $this->input->post('user_communication_via_phone_call'),
                    'user_communication_via_sms' => $this->input->post('user_communication_via_sms'),
                    'user_agreement' => $this->input->post('user_agreement'),
                    'user_newsletter_subscription' => $this->input->post('user_newsletter_subscription'),
                    'user_status' => '0',
                    'user_ip' => $this->input->server('REMOTE_ADDR'),
                    'user_agent' => $this->input->server('HTTP_USER_AGENT'),
                    'user_created' => $time_now
                );
//				if (in_array($this->input->post('user_financial_info'), array(
//							'buy_paypal_account_info',
//							'sell_paypal_account_info',
//							'both_paypal_bank_account_info'
//						))) {
//					$registration_insert_array['user_paypal_email_address'] = $this->input->post('user_paypal_email_address');
//				}
                if (in_array($this->input->post('user_financial_info'), array(
                            'buy_credit_card_info',
                            'both_credit_card_bank_account_info'
                        ))) {
                    $registration_insert_array['user_credit_card_name'] = $this->encrypt->encode($this->input->post('user_credit_card_name'));
                    $registration_insert_array['user_credit_card_number'] = $this->encrypt->encode($this->input->post('user_credit_card_number'));
                    $registration_insert_array['user_credit_card_number_md5'] = md5($this->input->post('user_credit_card_number'));
                    $registration_insert_array['user_credit_card_expiry_month'] = $this->encrypt->encode($this->input->post('user_credit_card_expiry_month'));
                    $registration_insert_array['user_credit_card_expiry_year'] = $this->encrypt->encode($this->input->post('user_credit_card_expiry_year'));
                    $registration_insert_array['user_credit_card_cvv'] = $this->encrypt->encode($this->input->post('user_credit_card_cvv'));
                }
                if (in_array($this->input->post('user_financial_info'), array(
                            'sell_bank_account_info',
                            'both_paypal_bank_account_info',
                            'both_credit_card_bank_account_info'
                        ))) {
                    $registration_insert_array['user_bank_account_number'] = $this->input->post('user_bank_account_number');
                    $registration_insert_array['user_bank_name'] = $this->input->post('user_bank_name');
                    $registration_insert_array['user_bank_route_code'] = $this->input->post('user_bank_route_code');
                    $registration_insert_array['user_bank_account_online'] = $this->input->post('user_bank_account_online');
                }
                $registration_id = 0;
                $registration_id = $this->Auth_model->register($registration_insert_array);
                if ($registration_id > 0) {
//					if (isset($registration_insert_array['user_paypal_email_address']) && $registration_insert_array['user_paypal_email_address'] !== '') {
//						$user_paypal_verification_code = parent::generate_random_string();
//						$paypal_result = parent::paypal_payout($registration_insert_array['user_paypal_email_address'], parent::generate_random_string('alnum', 16), 'Paypal Verification Code', 'Paypal Verification Code : ' . $user_paypal_verification_code);
//						if (isset($paypal_result['body']->items[0]->transaction_status) && $paypal_result['body']->items[0]->transaction_status === 'SUCCESS') {
//							$this->Auth_model->update_registration_by_id($registration_id, array('user_paypal_verification_code' => $user_paypal_verification_code));
//						}
//					}
                    if (isset($registration_insert_array['user_credit_card_number']) && $registration_insert_array['user_credit_card_number'] !== '') {
//						$purchase_response = parent::firstdata_cc_purchase('0', 'CC_VALIDATION', '1.00', $this->encrypt->decode($registration_insert_array['user_credit_card_name']), $this->encrypt->decode($registration_insert_array['user_credit_card_number']), $this->encrypt->decode($registration_insert_array['user_credit_card_expiry_month']) . substr($this->encrypt->decode($registration_insert_array['user_credit_card_expiry_year']), 2, 2));
//						if (isset($purchase_response->transaction_approved) && trim($purchase_response->transaction_approved) == '1' && isset($purchase_response->bank_message) && trim($purchase_response->bank_message) == 'Approved') {
//							$this->Auth_model->update_registration_by_id($registration_id, array('user_credit_card_verified' => '1', 'user_credit_card_verified_on' => date('Y-m-d H:i:s')));
//						}
                        $purchase_response = parent::braintree_cc_purchase('1.00', $this->encrypt->decode($registration_insert_array['user_credit_card_name']), $this->encrypt->decode($registration_insert_array['user_credit_card_expiry_month']), $this->encrypt->decode($registration_insert_array['user_credit_card_expiry_year']), $this->encrypt->decode($registration_insert_array['user_credit_card_number']), $this->encrypt->decode($registration_insert_array['user_credit_card_cvv']));
                        if (isset($purchase_response->success) && $purchase_response->success) {
                            $this->Auth_model->update_registration_by_id($registration_id, array('user_credit_card_verified' => '1', 'user_credit_card_verified_on' => date('Y-m-d H:i:s')));
                        }
                    }
                    if (isset($registration_insert_array['user_bank_account_number']) && $registration_insert_array['user_bank_account_number'] !== '' && $registration_insert_array['user_bank_account_online'] === '1') {
                        $this->load->model('City_model');
                        $city_details_array = $this->City_model->get_city_details_by_id($registration_insert_array['cities_id']);
                        $merchant_response = parent::braintree_add_merchant($this->input->post('user_type'), $registration_insert_array['user_first_name'], $registration_insert_array['user_last_name'], $registration_insert_array['user_email'], $registration_insert_array['user_dob'], $registration_insert_array['user_ssn'], $registration_insert_array['user_address_line_1'], $registration_insert_array['user_address_line_2'], $city_details_array['city_name'], $city_details_array['state_code'], $registration_insert_array['user_zipcode'], $registration_insert_array['user_bank_account_number'], $registration_insert_array['user_bank_route_code'], $registration_insert_array['business_legal_name'], $registration_insert_array['business_tax_id']);
                        if (isset($merchant_response->success) && $merchant_response->success == '1' && isset($merchant_response->merchantAccount->_attributes['id'])) {
                            $this->Auth_model->update_registration_by_id($registration_id, array('user_braintree_merchant_id' => $this->encrypt->encode($merchant_response->merchantAccount->_attributes['id'])));
                        } else {
                            $this->Auth_model->delete_registration_by_id($registration_id);
                            echo '<h4>Error While Validating Bank Account details.</h4>';
                            echo nl2br(@$merchant_response->_attributes['message']);
                            die;
                        }
                    }
                    $email_id1 = parent::add_email_to_queue('', '', $registration_insert_array['user_email'], '0', 'Registration Received', $this->render_view($registration_insert_array, 'emails', 'emails/templates/register', TRUE));
                    $email_id2 = parent::add_email_to_queue('', '', $this->config->item('admin_email'), '1', 'Registration Received', $this->render_view($registration_insert_array, 'emails', 'emails/templates/admin_register', TRUE));
                    if ($email_id1 > 0 && $email_id2 > 0) {
                        @file_get_contents(base_url() . 'emails/cron/' . $email_id1);
                        @file_get_contents(base_url() . 'emails/cron/' . $email_id2);
                        die('1');
                    }
                }
            } else {
                echo validation_errors();
                die;
            }
            die('0');
        }
        $this->load->helper('form');
        $this->load->model('Country_model');
        $data['countries_array'] = $this->Country_model->get_all_active_countries();
        $data['security_questions_array'] = $this->Auth_model->get_security_questions();
        $this->render_view($data, 'auth');
    }

    function _check_communication_preferences($str) {
        if ($this->input->post('user_communication_via_email') || $this->input->post('user_communication_via_phone_call') || $this->input->post('user_communication_via_sms')) {
            return TRUE;
        }
        $this->form_validation->set_message('_check_communication_preferences', 'Please Select At Least One Mode.');
        return FALSE;
    }

    function validate_email() {
        $this->load->library('form_validation');
        if ($this->input->post('user_email') !== '' || $this->input->post('user_paypal_email_address')) {
            if ($this->input->post('user_email')) {
                $this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|is_unique[users.user_email]|is_unique[registrations.user_email]|is_unique[users.user_paypal_email_address]|is_unique[registrations.user_paypal_email_address]');
            }
            if ($this->input->post('user_paypal_email_address')) {
                $this->form_validation->set_rules('user_paypal_email_address', 'PayPal ID', 'trim|required|valid_email|is_unique[users.user_email]|is_unique[registrations.user_email]|is_unique[users.user_paypal_email_address]|is_unique[registrations.user_paypal_email_address]');
            }
            if ($this->form_validation->run()) {
                die('true');
            }
        }
        die('false');
    }

    function validate_login() {
        if ($this->input->post('user_login')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('user_login', 'User ID', 'trim|required|is_unique[users.user_login]|is_unique[registrations.user_login]');
            if ($this->form_validation->run()) {
                die('true');
            }
        }
        die('false');
    }

    function verify($user_security_hash = '') {
        $data = array();
        if ($user_security_hash != '') {
            $this->load->model('Auth_model');
            $user_details_array = $this->Auth_model->get_registration_details_by_user_security_hash($user_security_hash);
            if (count($user_details_array) > 0) {
                $this->load->library('encrypt');
                $time_now = date('Y-m-d H:i:s');
                $registration_id = $user_details_array['user_id'];
                unset($user_details_array['user_id']);
                $user_details_array['user_security_hash'] = md5($time_now . $this->encrypt->decode($user_details_array['user_password_hash'], $user_details_array['user_login_password']));
                $user_details_array['user_ip'] = $this->input->server('REMOTE_ADDR');
                $user_details_array['user_agent'] = $this->input->server('HTTP_USER_AGENT');
                $user_details_array['user_status'] = '1';
                $user_details_array['user_modified'] = $time_now;
                if ($this->Auth_model->create_account($user_details_array) && $this->Auth_model->delete_registration_by_id($registration_id)) {
                    if ($user_details_array['user_communication_via_sms'] === '1') {
                        parent::send_sms(str_replace('-', '', $user_details_array['user_primary_contact']), "Congratulations.Your registration is completed with Blendedd");
                    }
                    $data['success'] = 'Account Verified Successfully.';
                    session_destroy();
                    $this->session->sess_destroy();
                } else {
                    $data['error'] = 'Invalid Link !!!';
                }
            } else {
                $data['error'] = 'Invalid Link !!!';
            }
        } else {
            $data['error'] = 'Invalid Link !!!';
        }
        $this->render_view($data);
    }

    function recover() {
        $data = array();
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email_address', 'User ID OR Email', 'trim|required');
            $this->form_validation->set_rules('captcha_image', 'Secure Image', 'trim|required|exact_length[6]|numeric|callback_validate_captcha');
            $this->form_validation->set_error_delimiters("", "<br/>");
            if ($this->form_validation->run()) {
                $user_details_array = $this->Auth_model->get_user_by_username_or_email($this->input->post('email_address'));
                if (count($user_details_array) > 0) {
                    $new_password = parent::generate_random_string();
                    $time_now = date('Y-m-d H:i:s');
                    $this->load->library('encrypt');
                    $user_update_array = array(
                        'user_login_salt' => md5($time_now),
                        'user_login_password' => md5(md5(md5($time_now) . $new_password)),
                        'user_password_hash' => $this->encrypt->encode($new_password, md5(md5(md5($time_now) . $new_password))),
                        'user_security_hash' => md5($time_now . $new_password),
                        'user_modified' => $time_now,
                        'force_change_password' => '1'
                    );
                    $this->load->model('User_model');
                    if ($this->User_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
                        $email_details_array = array(
                            'user_first_name' => $user_details_array['user_first_name'],
                            'user_last_name' => $user_details_array['user_last_name'],
                            'user_email' => $user_details_array['user_email'],
                            'user_login_password' => $new_password
                        );
                        $email_id = parent::add_email_to_queue('', '', $user_details_array['user_email'], $user_details_array['user_id'], 'Your Account Password', $this->render_view($email_details_array, 'emails', 'emails/templates/forgot_password', TRUE));
                        if ($email_id > 0) {
                            $file_contents = file_get_contents(base_url() . 'emails/cron/' . $email_id);
                            if ($file_contents === '1') {
                                $data['success'] = 'We have sent an email with new password.';
                            }
                        }
                    }
                } else {
                    $data['error'] = 'Invalid Email ID !!!';
                }
            } else {
                $data['error'] = validation_errors();
            }
        }
        $data['captcha_image'] = parent::create_captcha();
        $this->render_view($data, 'auth');
    }

    function credentials() {
//		die;
        $this->load->library('encrypt');
        $this->load->database();
        $user_details_array = $this->db->get('users')->result_array();
        $print_array = array();
        foreach ($user_details_array as $key => $user_detail) {
            $print_array[$key] = $user_detail;
            $print_array[$key]['password'] = $this->encrypt->decode($user_detail['user_password_hash'], $user_detail['user_login_password']);
        }
        ?>
        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
            </tr>
        <?php foreach ($print_array as $value) { ?>
                <tr>
                    <td><?php echo $value['user_login']; ?></td>
                    <td><?php echo $value['user_email']; ?></td>
                    <td><?php echo $value['password']; ?></td>
                </tr>
            <?php }
            ?>
        </table>
        <?php
        die;
    }

}
