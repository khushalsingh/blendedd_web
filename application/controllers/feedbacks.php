<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feedbacks extends MY_Controller {

    public $public_methods = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Feedback_model');
    }

    function index() {
        $data = array();
        $data['error_message'] = $this->session->flashdata('error_message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $received_feedbacks_array = $this->Feedback_model->get_received_feedbacks_by_user_id($_SESSION['user']['user_id']);
        $data['positive_counts_array'] = $data['neutral_counts_array'] = $data['negative_counts_array'] = array(
            '1' => 0,
            '6' => 0,
            '12' => 0,
            '0' => 0,
        );
        foreach ($received_feedbacks_array as $received_feedback) {
            switch ($received_feedback['feedback_rating']) {
                case '1':
                    $data['positive_counts_array']['0']++;
                    if (strtotime('- 1 Month') < strtotime($received_feedback['feedback_created'])) {
                        $data['positive_counts_array']['1']++;
                    }
                    if (strtotime('- 6 Month') < strtotime($received_feedback['feedback_created'])) {
                        $data['positive_counts_array']['6']++;
                    }
                    if (strtotime('- 1 Year') < strtotime($received_feedback['feedback_created'])) {
                        $data['positive_counts_array']['12']++;
                    }
                    break;
                case '0':
                    $data['neutral_counts_array']['0']++;
                    if (strtotime('- 1 Month') < strtotime($received_feedback['feedback_created'])) {
                        $data['neutral_counts_array']['1']++;
                    }
                    if (strtotime('- 6 Month') < strtotime($received_feedback['feedback_created'])) {
                        $data['neutral_counts_array']['6']++;
                    }
                    if (strtotime('- 1 Year') < strtotime($received_feedback['feedback_created'])) {
                        $data['neutral_counts_array']['12']++;
                    }
                    break;
                case '-1':
                    $data['negative_counts_array']['0']++;
                    if (strtotime('- 1 Month') < strtotime($received_feedback['feedback_created'])) {
                        $data['negative_counts_array']['1']++;
                    }
                    if (strtotime('- 6 Month') < strtotime($received_feedback['feedback_created'])) {
                        $data['negative_counts_array']['6']++;
                    }
                    if (strtotime('- 1 Year') < strtotime($received_feedback['feedback_created'])) {
                        $data['negative_counts_array']['12']++;
                    }
                    break;
            }
        }
        $data['awaiting_feedback_array'] = $this->Feedback_model->get_awaiting_feedback_posts($_SESSION['user']['user_id']);
        $this->render_view($data);
    }

    function feedback_seller_datatable() {
        $this->load->library('Datatables');
        $this->datatables->select("feedbacks.feedback_rating,posts.post_title,users.user_login,feedbacks.feedback_description,DATE_FORMAT(feedbacks.feedback_created, '%e %b %Y') AS feedback_created", FALSE)->from('feedbacks');
        $this->datatables->join('invoices', 'invoices.invoice_id = feedbacks.invoices_id', 'left');
        $this->datatables->join('posts', 'posts.post_id = invoices.posts_id', 'left');
        $this->datatables->join('users', 'users.user_id = invoices.invoice_to_users_id', 'left');
        $this->datatables->where('feedbacks.feedback_to_users_id', $_SESSION['user']['user_id']);
        $this->datatables->where('posts.users_id', $_SESSION['user']['user_id']);
        echo $this->datatables->generate();
    }

    function feedback_buyer_datatable() {
        $this->load->library('Datatables');
        $this->datatables->select("feedbacks.feedback_rating,posts.post_title,users.user_login,feedbacks.feedback_description,DATE_FORMAT(feedbacks.feedback_created, '%e %b %Y') AS feedback_created", FALSE)->from('feedbacks');
        $this->datatables->join('invoices', 'invoices.invoice_id = feedbacks.invoices_id', 'left');
        $this->datatables->join('posts', 'posts.post_id = invoices.posts_id', 'left');
        $this->datatables->join('users', 'users.user_id = posts.users_id', 'left');
        $this->datatables->where('feedbacks.feedback_to_users_id', $_SESSION['user']['user_id']);
        $this->datatables->where('invoices.invoice_to_users_id', $_SESSION['user']['user_id']);
        echo $this->datatables->generate();
    }

    function leave($invoice_id) {
        if (is_numeric($invoice_id) && $invoice_id > 0) {
            $data = array();
            $data['invoice_details_array'] = $this->Feedback_model->get_awaiting_feedback_by_user_id_and_invoice_id($_SESSION['user']['user_id'], $invoice_id);
            if (isset($data['invoice_details_array'][0]['post_id'])) {
                $this->load->model('Post_model');
                $data['post_images_array'] = $this->Post_model->get_images_by_post_id($data['invoice_details_array'][0]['post_id']);
                if ($this->input->post()) {
                    $this->load->library('form_validation');
                    $this->form_validation->set_rules('feedback_rating', 'Feedback Rating', 'trim|required');
                    $this->form_validation->set_rules('feedback_description', 'Feedback Description', 'trim|required');
                    $this->form_validation->set_error_delimiters("", "<br/>");
                    if ($this->form_validation->run()) {
                        $feedback_insert_array = array(
                            'invoices_id' => $invoice_id,
                            'feedback_by_users_id' => $_SESSION['user']['user_id'],
                            'feedback_to_users_id' => ($data['invoice_details_array'][0]['invoice_to_users_id'] === $_SESSION['user']['user_id']) ? $data['invoice_details_array'][0]['invoice_by_users_id'] : $data['invoice_details_array'][0]['invoice_to_users_id'],
                            'feedback_rating' => $this->input->post('feedback_rating'),
                            'feedback_description' => $this->input->post('feedback_description'),
                            'feedback_status' => '1',
                            'feedback_created' => date('Y-m-d H:i:s')
                        );
                        if ($this->Feedback_model->create($feedback_insert_array)) {
                            $sum_of_feedbacks = $this->Feedback_model->get_feedback_ratings_by_user_id($feedback_insert_array['feedback_to_users_id']);
                            $this->load->model('User_model');
                            $user_update_array = array(
                                'user_feedbacks' => $sum_of_feedbacks['feedback_rating'],
                                'user_feedback_percentage' => parent::get_user_feedback_percentage($feedback_insert_array['feedback_to_users_id']),
                                'user_modified' => date('Y-m-d H:i:s')
                            );
                            $this->User_model->edit_user_by_user_id($feedback_insert_array['feedback_to_users_id'], $user_update_array);
                            parent::regenerate_session();
                            die('1');
                        }
                    } else {
                        echo validation_errors();
                        die;
                    }
                    die('0');
                }
                $this->render_view($data);
                return;
            }
        }
        $this->session->set_flashdata('error_message', 'You Can not leave feedback on this post.');
        redirect('feedbacks', 'refresh');
    }

}
