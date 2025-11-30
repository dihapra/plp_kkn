<?php

namespace Repositories;

use SearchTrait;

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'traits/SearchTrait.php');
class TeacherRepository
{
    use SearchTrait;
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
    public function get_by_id($id)
    {
        $this->db->select('teachers.*,school.name as school_name,school.id as school_id');
        $this->db->where('teachers.id', $id);
        $this->db->from('teachers');
        $this->db->join('school', 'teachers.school_id = school.id', 'left');
        return $this->db->get()->row();
    }
}
