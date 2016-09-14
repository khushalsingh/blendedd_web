<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Search extends MY_Controller {

    public $public_methods = array('index', 'advanced', 'results');

    function __construct() {
        parent::__construct();
        $this->load->model('Search_model');
    }

    function index() {
        redirect('search/advanced', 'refresh');
    }

    function advanced() {
        parent::_create_cache();
        $data = array();
        $this->load->model('Category_model');
        $data['categories_array'] = $this->Category_model->get_all_active_categories();
        $this->render_view($data);
    }

    function results($categories_id = '', $search_term = '-', $order = '-', $per_page = 24, $page = 0, $not_search_keywords = '', $search_type = '') {
        if ($per_page != 24) {
            $per_page = 24;
        }
        $data = array();
        if ($this->input->post()) {
            if (trim($this->input->post('search_term')) !== '') {
                $search_term = preg_replace('/[^a-zA-Z0-9-]+/', ' ', trim($this->input->post('search_term')));
                redirect('search/results/-/' . $search_term . '/' . $order . '/' . $per_page . '/' . $page);
            } else if (trim($this->input->post('search_keywords')) !== '') {
                $categories_id = $not_search_keywords = $search_type = '-';
                if (trim($this->input->post('categories_id')) !== '') {
                    $categories_id = trim($this->input->post('categories_id'));
                }
                if (trim($this->input->post('not_search_keywords')) !== '') {
                    $not_search_keywords = trim($this->input->post('not_search_keywords'));
                }
                if (trim($this->input->post('search_type')) !== '') {
                    $search_type = trim($this->input->post('search_type'));
                }
                redirect('search/results/' . $categories_id . '/' . trim($this->input->post('search_keywords')) . '/' . $order . '/' . $per_page . '/' . $page . '/' . $not_search_keywords . '/' . $search_type);
            } else {
                redirect('search/advanced', 'refresh');
            }
            return;
        } else {
            $data['posts_array'] = array();
            $data['search_term'] = $search_term = trim(urldecode($search_term));
            if ($search_term === '') {
                redirect('search/advanced', 'refresh');
            }
            switch ($order) {
                case 'price_asc':
                    $order_by = array('post_min_price', 'asc');
                    break;
                case 'price_desc':
                    $order_by = array('post_min_price', 'desc');
                    break;
                case '-':
                default:
                    $order_by = array('post_id', 'desc');
                    break;
            }
            if ($not_search_keywords !== '' && $categories_id !== '' && $search_type !== '') {
                /**
                 * Advanced Search Starts
                 */
                $data['posts_array'] = $this->Search_model->advance_search($categories_id, $search_term, $page, $per_page, $not_search_keywords, $search_type, $order_by);
                $data['results_count'] = $this->Search_model->count_advance_search($categories_id, $search_term, $page, $per_page, $not_search_keywords, $search_type);
                /**
                 * Advanced Search Ends
                 */
            } else {
                /**
                 * Simple Search
                 */
                $data['posts_array'] = $this->Search_model->search($categories_id, $search_term, $page, $per_page, $order_by);
                $data['results_count'] = $this->Search_model->count_search($categories_id, $search_term, $page, $per_page);
                /**
                 * Simple Search Ends
                 */
            }
            $data['pagination'] = parent::get_pagination(base_url() . 'search/results/' . $categories_id . '/' . $data['search_term'] . '/' . $order . '/' . $per_page, 7, $data['results_count'], $per_page, '', '/' . $not_search_keywords . '/' . $search_type);
            foreach ($data['posts_array'] as $key => $post) {
                $data['posts_array'][$key]['post_image_url'] = parent::_prepare_post_image_url($post);
            }
        }
        if ($categories_id !== '-') {
            $this->load->model('Category_model');
            $data['category_details_array'] = $this->Category_model->get_category_type_by_id($categories_id);
        }
        $this->render_view($data);
    }

}
