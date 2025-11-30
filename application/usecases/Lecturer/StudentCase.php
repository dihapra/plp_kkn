<?php

namespace UseCases\Lecturer;

use Exception;

class AbsensiCase
{
    public $CI;
    public $db;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
}
