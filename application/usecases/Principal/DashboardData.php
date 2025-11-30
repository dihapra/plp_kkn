<?php

namespace UseCases\Principal;

class DashboardData
{
    public $CI;
    public $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    public function execute()
    {
        $school_id = $this->CI->session->userdata('school_id');

        $this->db->where('school_id', $school_id);
        $this->db->from('student');
        $total_student =  $this->db->count_all_results();




        $this->db->where('school_id', $school_id);
        $this->db->where('status_data', 'verified');
        $this->db->from('teachers');
        $total_teacher = $this->db->count_all_results();
        // var_dump($this->db->last_query());
        // exit();
        return [
            'total_student' => $total_student,
            'total_teacher' => $total_teacher,

        ];
    }
}
