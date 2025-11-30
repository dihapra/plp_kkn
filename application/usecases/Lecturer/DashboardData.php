<?php

namespace UseCases\Lecturer;

use Exception;
use LogbookValidator;
use Repositories\LecturerRepository;
use Repositories\StudentRepository;
use Repositories\SubmissionRepository;
use Throwable;

class DashboardData
{

    public $CI;
    public $db;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    public function process()
    {
        $nip = $this->CI->session->userdata('nip');
        $repo = new LecturerRepository();
        $lecture = $repo->get_by_key('nip', $nip);
        // dd($nip, $lecture);
        $this->db->where('lecture_id', $lecture->id);
        $this->db->from('student');
        $counted =  $this->db->count_all_results();
        view_with_layout('dosen/index', 'SIMPLP UNIMED', ['total_student' => $counted]);
    }
}
