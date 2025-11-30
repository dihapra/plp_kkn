<?php

namespace UseCases\Teacher;

class EvaluationPageCase
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
        $teacher_id = $this->CI->session->userdata('teacher_id');

        $this->db->select('
            s.id as student_id,
            s.name as student_name,
            s.nim as student_nim,
            (SELECT COUNT(*) FROM assist_intracurricular ai WHERE ai.student_id = s.id AND ai.teacher_id = ' . $this->db->escape($teacher_id) . ') > 0 AS has_intracurricular,
            (SELECT COUNT(*) FROM assist_extracurricular ae WHERE ae.student_id = s.id AND ae.teacher_id = ' . $this->db->escape($teacher_id) . ') > 0 AS has_extracurricular,
            (SELECT COUNT(*) FROM student_attitude sm WHERE sm.student_id = s.id AND sm.teacher_id = ' . $this->db->escape($teacher_id) . ') > 0 AS has_attitude
        ');
        $this->db->from('student s');
        $this->db->where('s.teacher_id', $teacher_id);
        $query = $this->db->get();

        return $query->result();
    }
}
