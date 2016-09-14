<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Search_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function search($categories_id, $search_term, $page, $per_page, $order_by) {
        $query = "SELECT * FROM posts ";
        $query .= "LEFT JOIN cities ON cities.city_id = posts.cities_id ";
        $query .= "LEFT JOIN states ON states.state_id = cities.states_id ";
        $query .= "LEFT JOIN categories ON categories.category_id = posts.categories_id ";
        $query .= "LEFT JOIN post_images ON post_images.posts_id = posts.post_id WHERE ";
        if ($categories_id !== '-') {
            $query .= "categories_id = " . $categories_id . " AND ";
        }
        if ($search_term !== '-') {
            $search_term_array = explode(' ', $search_term);
            foreach ($search_term_array as $search) {
                $query .= "post_title_shadow LIKE '%" . $search . "%' AND ";
            }
        }
        $query .= "post_status >= 0 ";
        $query .= "GROUP BY post_id ";
        $query .= "ORDER BY " . $order_by[0] . " " . $order_by[1] . " ";
        $query .= "LIMIT " . $page . ", " . $per_page;
        return $this->db->query($query)->result_array();
    }

    function count_search($categories_id, $search_term, $page, $per_page) {
        $query = "SELECT * FROM posts WHERE ";
        if ($categories_id !== '-') {
            $query .= "categories_id = " . $categories_id . " AND ";
        }
        if ($search_term !== '-') {
            $search_term_array = explode(' ', $search_term);
            foreach ($search_term_array as $search) {
                $query .= "post_title_shadow LIKE '%" . $search . "%' AND ";
            }
        }
        $query .= "post_status >= 0 ";
        $query .= "GROUP BY post_id";
        $posts_array = $this->db->query($query)->result_array();
        return count($posts_array);
    }

    function advance_search($categories_id, $search_term, $page, $per_page, $not_search_keywords, $search_type, $order_by) {
        $query = "SELECT * FROM posts ";
        $query .= "LEFT JOIN cities ON cities.city_id = posts.cities_id ";
        $query .= "LEFT JOIN states ON states.state_id = cities.states_id ";
        $query .= "LEFT JOIN categories ON categories.category_id = posts.categories_id ";
        $query .= "LEFT JOIN post_images ON post_images.posts_id = posts.post_id ";
        $query .= "WHERE ";
        if ($categories_id !== '-') {
            $query .= "categories_id = " . $categories_id . " AND ";
        }
        $search_term_array = explode(' ', $search_term);
        switch ($search_type) {
            case 'all-any':
                foreach ($search_term_array as $search) {
                    $query .= "post_title_shadow LIKE '%" . $search . "%' AND ";
                }
                break;
            case 'any-any':
                $query .= "(";
                foreach ($search_term_array as $search) {
                    $query .= "post_title_shadow LIKE '%" . $search . "%' OR ";
                }
                $query = substr($query, 0, -4);
                $query .= ") AND ";
                break;
            case 'exact-exact':
                $query .= "post_title_shadow LIKE '%" . $search_term . "%' AND ";
                break;
            case 'exact-any':
                /**
                 * Same AS Case 1
                 */
                foreach ($search_term_array as $search) {
                    $query .= "post_title_shadow LIKE '%" . $search . "%' AND ";
                }
                break;
            default:
                /**
                 * Same AS Case 1
                 */
                foreach ($search_term_array as $search) {
                    $query .= "post_title_shadow LIKE '%" . $search . "%' AND ";
                }
                break;
        }
        if ($not_search_keywords !== '-') {
            $not_search_keywords_array = explode(' ', $not_search_keywords);
            foreach ($not_search_keywords_array as $not_search_keyword) {
                $query .= "post_title_shadow NOT LIKE '%" . $not_search_keyword . "%' AND ";
            }
        }
        $query .= "post_status >= 0 ";
        $query .= "GROUP BY post_id ";
        $query .= "ORDER BY " . $order_by[0] . " " . $order_by[1] . " ";
        $query .= "LIMIT " . $page . ", " . $per_page;
        return $this->db->query($query)->result_array();
    }

    function count_advance_search($categories_id, $search_term, $page, $per_page, $not_search_keywords, $search_type) {
        $query = "SELECT * FROM posts ";
        $query .= "WHERE ";
        if ($categories_id !== '-') {
            $query .= "categories_id = " . $categories_id . " AND ";
        }
        $search_term_array = explode(' ', $search_term);
        switch ($search_type) {
            case 'all-any':
                foreach ($search_term_array as $search) {
                    $query .= "post_title_shadow LIKE '%" . $search . "%' AND ";
                }
                break;
            case 'any-any':
                $query .= "(";
                foreach ($search_term_array as $search) {
                    $query .= "post_title_shadow LIKE '%" . $search . "%' OR ";
                }
                $query = substr($query, 0, -4);
                $query .= ") AND ";
                break;
            case 'exact-exact':
                $query .= "post_title_shadow LIKE '%" . $search_term . "%' AND ";
                break;
            case 'exact-any':
                /**
                 * Same AS Case 1
                 */
                foreach ($search_term_array as $search) {
                    $query .= "post_title_shadow LIKE '%" . $search . "%' AND ";
                }
                break;
            default:
                /**
                 * Same AS Case 1
                 */
                foreach ($search_term_array as $search) {
                    $query .= "post_title_shadow LIKE '%" . $search . "%' AND ";
                }
                break;
        }
        if ($not_search_keywords !== '-') {
            $not_search_keywords_array = explode(' ', $not_search_keywords);
            foreach ($not_search_keywords_array as $not_search_keyword) {
                $query .= "post_title_shadow NOT LIKE '%" . $not_search_keyword . "%' AND ";
            }
        }
        $query .= "post_status >= 0 ";
        $query .= "GROUP BY post_id";
        $posts_array = $this->db->query($query)->result_array();
        return count($posts_array);
    }

}
