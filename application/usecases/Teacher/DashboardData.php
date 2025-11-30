<?php

namespace UseCases\Teacher;

class DashboardData
{
    public $CI;
    public $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
        $this->CI->load->model('User_model'); // Assuming User_model handles student data
    }

    public function execute()
    {
        $teacher_id = $this->CI->session->userdata('teacher_id');
        $this->db->where('teacher_id', $teacher_id);
        $this->db->from('student');
        $total_student =  $this->db->count_all_results();

        return [
            'total_student' => $total_student
        ];
    }
}