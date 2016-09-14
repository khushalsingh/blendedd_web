<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Messages extends MY_Controller {

    public $public_methods = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Message_model');
    }

    function index() {
        redirect('messages/inbox', 'refresh');
    }

    function inbox() {
        $data = array();
        $data['error_message'] = $this->session->flashdata('error_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->render_view($data);
    }

    function inbox_datatable() {
        $this->load->library('Datatables');
        $this->datatables->select("posts_id,user_login,post_title,DATE_FORMAT(MAX(message_created), '%e %b %Y') AS message_created_date,message_by_users_id,MIN(message_read_status)", FALSE)->from('messages');
        $this->datatables->join('posts', 'posts.post_id = posts_id', 'left');
        $this->datatables->join('users', 'users.user_id = message_by_users_id', 'left');
        $this->datatables->where('message_to_users_id', $_SESSION['user']['user_id']);
        $this->datatables->where('trash_message_by_users_id !=', $_SESSION['user']['user_id']);
        $this->datatables->where('trash_message_to_users_id !=', $_SESSION['user']['user_id']);
        $this->datatables->group_by('posts_id');
        echo $this->datatables->generate();
    }

    function get_messages_by_post_id() {
        $message_details_array = array();
        if ($this->input->post('post_id') && is_numeric($this->input->post('post_id'))) {
            $message_details_array = $this->Message_model->get_messages_by_post_id($this->input->post('post_id'));
        }
        parent::json_output($message_details_array);
    }

    function create() {
        if ($this->input->post('posts_id') && $this->input->post('message_body') && strip_tags(trim($this->input->post('message_body'))) !== '') {
            $this->load->model('Post_model');
            $post_details_array = $this->Post_model->get_post_row_by_id($this->input->post('posts_id'));
            if ($this->input->post('message_to_users_id')) {
                $message_to_users_id = $this->input->post('message_to_users_id');
            } else {
                $message_to_users_id = $post_details_array['users_id'];
            }
            $message_insert_array = array(
                'message_by_users_id' => $_SESSION['user']['user_id'],
                'message_to_users_id' => $message_to_users_id,
                'posts_id' => $this->input->post('posts_id'),
                'message_body' => strip_tags(trim($this->input->post('message_body'))),
                'message_read_status' => '0',
                'message_created' => date('Y-m-d H:i:s')
            );
            if ($this->Message_model->create($message_insert_array)) {
                $this->load->model('User_model');
                $user_details_array = $this->User_model->get_user_row_by_user_id($message_to_users_id);
                if ($user_details_array['user_communication_via_email'] === '1') {
                    $email_details_array = array(
                        'user_from_name' => $_SESSION['user']['user_login'],
                        'user_from_full_name' => $_SESSION['user']['user_first_name'] . ' ' . $_SESSION['user']['user_last_name'],
                        'user_first_name' => $user_details_array['user_first_name'],
                        'user_last_name' => $user_details_array['user_last_name'],
                        'message_body' => $message_insert_array['message_body'],
                        'post_title' => $post_details_array['post_title'],
                        'post_link' => base_url() . 'post/view/' . $post_details_array['post_slug'] . '/' . $post_details_array['post_id']
                    );
                    $email_id = parent::add_email_to_queue('', '', $user_details_array['user_email'], $message_to_users_id, 'Message regarding ' . $post_details_array['post_title'] . ', Blendedd posting', $this->render_view($email_details_array, 'emails', 'emails/templates/message_sent', TRUE));
                    if ($email_id > 0) {
                        $file_contents = file_get_contents(base_url() . 'emails/cron/' . $email_id);
                        if ($file_contents === '1') {
                            die('1');
                        }
                    }
                } else {
                    die('1');
                }
            }
            die('0');
        }
    }

    function mark_inbox_messages_deleted_by_post_id() {
        if ($this->input->post('posts_id')) {
            if ($this->Message_model->mark_inbox_messages_deleted_by_post_id($this->input->post('posts_id'), array('trash_message_to_users_id' => $_SESSION['user']['user_id'], 'message_modified' => date('Y-m-d H:i:s')))) {
                die('1');
            }
        }
        die('0');
    }

    function mark_sent_messages_deleted_by_post_id() {
        if ($this->input->post('posts_id')) {
            if ($this->Message_model->mark_sent_messages_deleted_by_post_id($this->input->post('posts_id'), array('trash_message_by_users_id' => $_SESSION['user']['user_id'], 'message_modified' => date('Y-m-d H:i:s')))) {
                die('1');
            }
        }
        die('0');
    }

//	function mark_trash_messages_deleted_by_post_id() {
//		if ($this->input->post('posts_id')) {
//			if ($this->Message_model->update_trash_message_by_post_id($this->input->post('posts_id'), $_SESSION['user']['user_id'], array('message_read_status' => '-2', 'message_modified' => date('Y-m-d H:i:s')))) {
//				die('1');
//			}
//		}
//		die('0');
//	}

    function mark_inbox_messages_read_by_post_id() {
        if ($this->input->post('posts_id')) {
            if ($this->Message_model->update_inbox_message_by_post_id($this->input->post('posts_id'), $_SESSION['user']['user_id'], array('message_read_status' => '1', 'message_modified' => date('Y-m-d H:i:s')))) {
                die('1');
            }
        }
        die('0');
    }

    function mark_inbox_messages_unread_by_post_id() {
        if ($this->input->post('posts_id')) {
            if ($this->Message_model->update_inbox_message_by_post_id($this->input->post('posts_id'), $_SESSION['user']['user_id'], array('message_read_status' => '0', 'message_modified' => date('Y-m-d H:i:s')))) {
                die('1');
            }
        }
        die('0');
    }

    function sent() {
        $data = array();
        $data['error_message'] = $this->session->flashdata('error_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->render_view($data);
    }

    function sent_datatable() {
        $this->load->library('Datatables');
        $this->datatables->select("posts_id,user_login,post_title,DATE_FORMAT(MAX(message_created), '%e %b %Y') AS message_created_date,message_to_users_id,MIN(message_read_status)", FALSE)->from('messages');
        $this->datatables->join('posts', 'posts.post_id = posts_id', 'left');
        $this->datatables->join('users', 'users.user_id = message_to_users_id', 'left');
        $this->datatables->where('message_by_users_id', $_SESSION['user']['user_id']);
        $this->datatables->where('trash_message_by_users_id !=', $_SESSION['user']['user_id']);
        $this->datatables->where('trash_message_to_users_id !=', $_SESSION['user']['user_id']);
        $this->datatables->group_by('posts_id');
        echo $this->datatables->generate();
    }

    function trash() {
        $data = array();
        $data['error_message'] = $this->session->flashdata('error_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->render_view($data);
    }

    function trash_datatable() {
        $this->load->library('Datatables');
        $this->datatables->select("posts_id,post_title,DATE_FORMAT(MAX(message_created), '%e %b %Y') AS message_created_date", FALSE)->from('messages');
        $this->datatables->join('posts', 'posts.post_id = messages.posts_id', 'left');
        $this->datatables->where('trash_message_by_users_id', $_SESSION['user']['user_id']);
        $this->datatables->or_where('trash_message_to_users_id', $_SESSION['user']['user_id']);
        $this->datatables->group_by('posts_id');
        echo $this->datatables->generate();
    }

}
