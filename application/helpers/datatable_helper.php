<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('get_param_datatable')) {
    function get_param_datatable()
    {
        $CI = &get_instance(); // Mengakses instance CI
        $params = $CI->input->post();

        return [
            'draw' => isset($params['draw']) ? intval($params['draw']) : 0,
            'start' => isset($params['start']) ? intval($params['start']) : 0,
            'length' => isset($params['length']) ? intval($params['length']) : 10,
            'order_column' => isset($params['order'][0]['column']) ? $params['columns'][$params['order'][0]['column']]['data'] : '',
            'order_dir' => isset($params['order'][0]['dir']) ? $params['order'][0]['dir'] : '',
            'search' => isset($params['search']['value']) ? $params['search']['value'] : '',
        ];
    }
}
if (!function_exists('result_formatter')) {
    function result_formatter($query, $count_total, $count_filtered)
    {
        return [
            'query' => is_object($query) ? $query->result() : [],
            'count_total' => intval($count_total),
            'count_filtered' => intval($count_filtered)
        ];
    }
}

if (!function_exists('datatable_response')) {
    function datatable_response($draw, $result, $data_array)
    {
        $CI = &get_instance();

        $output = [
            "draw" => intval($draw),
            "recordsTotal" => isset($result['count_total']) ? intval($result['count_total']) : 0,
            "recordsFiltered" => isset($result['count_filtered']) ? intval($result['count_filtered']) : 0,
            "data" => is_array($data_array) ? $data_array : [],
        ];

        // Menggunakan CodeIgniter output JSON
        $CI->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output, JSON_PRETTY_PRINT));

        return;
    }
}
if (!function_exists('datatable_response_array')) {
    function datatable_response_array($draw, $count_total,$count_filtered, $data_array)
    {
        $CI = &get_instance();

        $output = [
            "draw" => intval($draw),
            "recordsTotal" => isset($count_total) ? intval($count_total) : 0,
            "recordsFiltered" => isset($count_filtered) ? intval($count_filtered) : 0,
            "data" => is_array($data_array) ? $data_array : [],
        ];

        // Menggunakan CodeIgniter output JSON
        $CI->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output, JSON_PRETTY_PRINT));

        return;
    }
}
