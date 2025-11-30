<?php

use Repositories\TeacherRepository;

defined('BASEPATH') or exit('No direct script access allowed');

class TeacherService
{
    protected $CI;

    protected $db;
    protected $TeacherRepository;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
        $this->TeacherRepository = new TeacherRepository();
    }


    public function get_by_id($teacher_id)
    {
        return $this->TeacherRepository->get_by_id($teacher_id);
    }
}
