<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExampleServices
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('ExampleModel');
    }

    public function getAll()
    {
        return "Hello World";
    }

    // public function create($data)
    // {
    //     return $this->CI->ExampleModel->insert($data);
    // }
}
