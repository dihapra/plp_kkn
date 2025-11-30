<?php

namespace Services;

defined('BASEPATH') or exit('No direct script access allowed');

class StudentService
{
    protected $CI;

    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
}
