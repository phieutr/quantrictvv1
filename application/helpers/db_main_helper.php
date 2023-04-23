<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('result_select')) :
    function result_select($table, $data, $value)
    {
        $CI = &get_instance();
        $CI->load->model('Main_model');

        return $CI->Main_model->result_select($table, $data, $value);
    }
endif;


if (!function_exists('update')) :
    function update($table, $data, $where)
    {
        $CI = &get_instance();
        $CI->load->model('Main_model');

        return $CI->Main_model->update($table, $data, $where);
    }
endif;


if (!function_exists('select_all')) :
    function select_all($table)
    {
        $CI = &get_instance();
        $CI->load->model('Main_model');

        return $CI->Main_model->select_all($table);
    }
endif;


if (!function_exists('insert')) :
    function insert($table, $data)
    {
        $CI = &get_instance();
        $CI->load->model('Main_model');

        return $CI->Main_model->insert($table, $data);
    }
endif;

if (!function_exists('get_posts')) :
    function get_posts($table, $slug = FALSE)
    {
        $CI = &get_instance();
        $CI->load->model('Main_model');

        return $CI->Main_model->get_posts($table, $slug = FALSE);
    }
endif;

if (!function_exists('result_select_order_by')) :
    function result_select_order_by($table, $order_column, $order_by, $limit)
    {
        $CI = &get_instance();
        $CI->load->model('Main_model');

        return $CI->Main_model->result_select_order_by($table, $order_column, $order_by, $limit);
    }
endif;

if (!function_exists('row_select')) :
    function row_select($table, $data, $value)
    {
        $CI = &get_instance();
        $CI->load->model('Main_model');

        return $CI->Main_model->row_select($table, $data, $value);
    }
endif;

if (!function_exists('delete')) :
    function delete($table, $data)
    {
        $CI = &get_instance();
        $CI->load->model('Main_model');

        return $CI->Main_model->delete($table, $data);
    }
endif;
