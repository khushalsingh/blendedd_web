<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Post_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function create($post_insert_array) {
        $this->db->insert('posts', $post_insert_array);
        return $this->db->insert_id();
    }

    function create_post_images($image_insert_array) {
        return $this->db->insert_batch('post_images', $image_insert_array);
    }

    function create_post_availabilities($time_available_array) {
        return $this->db->insert_batch('post_availabilities', $time_available_array);
    }

    function update($post_id, $post_update_array) {
        return $this->db->where('post_id', $post_id)->update('posts', $post_update_array);
    }

    function get_post_by_id($posts_id) {
        $this->db->join('categories', 'categories.category_id = posts.categories_id', 'left');
        $this->db->join('cities', 'cities.city_id = posts.cities_id', 'left');
        $this->db->join('states', 'states.state_id = cities.states_id', 'left');
        $this->db->join('countries', 'countries.country_id = states.countries_id', 'left');
        $this->db->join('users', 'users.user_id = posts.users_id', 'left');
        $this->db->join('time_zones', 'time_zones.time_zone_id = posts.time_zones_id', 'left');
        return $this->db->get_where('posts', array('post_id' => $posts_id))->row_array();
    }

    function get_post_row_by_id($posts_id) {
        return $this->db->get_where('posts', array('post_id' => $posts_id))->row_array();
    }

    function get_next_post_in_same_category($post_id, $categories_id) {
        return $this->db->order_by('post_id', 'asc')->get_where('posts', array('post_id >' => $post_id, 'categories_id' => $categories_id), 1, 0)->row_array();
    }

    function get_previous_post_in_same_category($post_id, $categories_id) {
        return $this->db->order_by('post_id', 'desc')->get_where('posts', array('post_id <' => $post_id, 'categories_id' => $categories_id), 1, 0)->row_array();
    }

    function get_time_availability_by_post_id($post_id) {
        return $this->db->get_where('post_availabilities', array('posts_id' => $post_id))->result_array();
    }

    function get_images_by_post_id($post_id) {
        return $this->db->order_by('post_image_display_order', 'asc')->get_where('post_images', array('posts_id' => $post_id))->result_array();
    }

    function get_pricing_options() {
        return $this->db->get('pricing_options')->result_array();
    }

    function get_all_paid_posts_by_user_id($user_id) {
        $this->db->where('invoices.invoice_to_users_id', $user_id);
        $this->db->where('invoices.invoice_status', '1');
        $this->db->join('posts', 'posts.post_id = invoices.posts_id', 'left');
        $this->db->join('categories', 'categories.category_id = posts.categories_id', 'left');
        $this->db->join('post_images', 'post_images.posts_id = posts.post_id', 'left');        
        $this->db->order_by('invoice_id', 'desc');
        $this->db->order_by('post_image_display_order', 'asc');
        return $this->db->get('invoices')->result_array();
    }

    function get_posted_posts_by_user_id($user_id, $category_type) {
        $this->db->where('users_id', $user_id);
        $this->db->where('category_type', $category_type);
        $this->db->join('post_images', 'post_images.posts_id = posts.post_id', 'left');
        $this->db->join('categories', 'categories.category_id = posts.categories_id', 'left');
        $this->db->order_by('post_id', 'desc');
        $this->db->order_by('post_image_display_order', 'asc');
        return $this->db->get('posts')->row_array();
    }

    function get_paid_posts_by_user_id($user_id, $category_type) {
        $this->db->where('invoices.invoice_to_users_id', $user_id);
        $this->db->where('invoices.invoice_status', '1');
        $this->db->where('category_type', $category_type);
        $this->db->join('posts', 'posts.post_id = invoices.posts_id', 'left');
        $this->db->join('post_images', 'post_images.posts_id = posts.post_id', 'left');
        $this->db->join('categories', 'categories.category_id = posts.categories_id', 'left');
        $this->db->order_by('invoice_id', 'desc');
        $this->db->order_by('post_image_display_order', 'asc');
        return $this->db->get('invoices')->row_array();
    }

    function get_posted_pager($category_type, $post_id, $user_id, $pager_type, $post_status = '', $post_within = '') {
        $this->db->where('users_id', $user_id);
        if ($post_status !== '') {
            $this->db->where('post_status', $post_status);
        }
        if ($post_within !== '') {
            $this->db->where("post_created BETWEEN '" . date('Y-m-d H:i:s', strtotime('-' . $post_within . ' days')) . "' AND '" . date('Y-m-d H:i:s') . "'");
        }
        $this->db->where('category_type', $category_type);
        if ($pager_type !== '') {
            $this->db->where('post_id ' . $pager_type, $post_id);
        }
        $this->db->join('post_images', 'post_images.posts_id = posts.post_id', 'left');
        $this->db->join('categories', 'categories.category_id = posts.categories_id', 'left');
        if ($pager_type === '>') {
            $this->db->order_by('post_id', 'asc');
        } else {
            $this->db->order_by('post_id', 'desc');
        }
        $this->db->order_by('post_image_display_order', 'asc');
        return $this->db->get('posts')->row_array();
    }

    function get_purchased_pager($category_type, $post_id, $user_id, $pager_type, $post_within = '') {
        $this->db->where('invoices.invoice_to_users_id', $user_id);
        $this->db->where('invoices.invoice_status', '1');
        $this->db->join('posts', 'posts.post_id = invoices.posts_id', 'left');
        if ($post_within !== '') {
            $this->db->where("post_created BETWEEN '" . date('Y-m-d H:i:s', strtotime('-' . $post_within . ' days')) . "' AND '" . date('Y-m-d H:i:s') . "'");
        }
        $this->db->where('category_type', $category_type);
        if ($pager_type !== '') {
            $this->db->where('post_id ' . $pager_type, $post_id);
        }
        $this->db->join('post_images', 'post_images.posts_id = posts.post_id', 'left');
        $this->db->join('categories', 'categories.category_id = posts.categories_id', 'left');
        if ($pager_type === '>') {
            $this->db->order_by('invoice_id', 'asc');
        } else {
            $this->db->order_by('invoice_id', 'desc');
        }
        $this->db->order_by('post_image_display_order', 'asc');
        return $this->db->get('invoices')->row_array();
    }

    function delete_post_image_by_id($post_id, $images_ids) {
        $this->db->where('post_image_id IN (' . $images_ids . ') AND posts_id =' . $post_id);
        return $this->db->delete('post_images');
    }

    function delete_post_images_by_post_id($post_id) {
        $this->db->where('posts_id', $post_id);
        return $this->db->delete('post_images');
    }

    function delete_post_availabilities_by_post_id($post_id) {
        $this->db->where('posts_id', $post_id);
        return $this->db->delete('post_availabilities');
    }

    function delete_post_image_id($image_id) {
        $this->db->where('post_image_id', $image_id);
        return $this->db->delete('post_images');
    }

    function get_post_image_name_by_post_image_id($post_image_id) {
        $this->db->join('posts', 'posts.post_id = post_images.posts_id', 'left');
        $this->db->select('post_image_name,post_created');
        return $this->db->get_where('post_images', array('post_image_id' => $post_image_id))->row_array();
    }

    function get_all_time_zones() {
        return $this->db->get('time_zones')->result_array();
    }

    function get_time_zone_details_by_id($time_zone_id) {
        return $this->db->get_where('time_zones', array('time_zone_id' => $time_zone_id))->row_array();
    }

    function get_prohibits($post_id) {
        return $this->db->get_where('prohibits', array('posts_id' => $post_id))->result_array();
    }

    function prohibit($prohibit_insert_array) {
        if ($this->db->insert('prohibits', $prohibit_insert_array)) {
            return $this->db->insert_id();
        }
        return 0;
    }

}
