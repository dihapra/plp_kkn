<?php

namespace UseCases\Principal;

use Traits\DatatableTrait;
use Traits\SearchsTrait;

class StudentCase
{
    use SearchsTrait, DatatableTrait;
    public $CI;
    public $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    public function principal_datatable($param)
    {
        $school_id = $this->CI->session->userdata('school_id');
        $this->db->select('student.*, teachers.name as teacher_name, lecturers.name as lecturer_name');
        $this->db->from('student');
        $this->db->join('teachers', 'student.teacher_id = teachers.id', 'left');
        $this->db->join('lecturers', 'student.lecture_id = lecturers.id', 'left');
        $this->db->where('student.school_id', $school_id);

        $count_total = $this->db->count_all_results('', false);

        if (!empty($param['search'])) {
            $this->db->group_start();
            $this->db->like('student.name', $param['search']);
            $this->db->or_like('student.nim', $param['search']);
            $this->db->or_like('teachers.name', $param['search']);
            $this->db->or_like('lecturers.name', $param['search']);
            $this->db->group_end();
        }

        $count_filtered = $this->db->count_all_results('', false);

        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        $query = $this->db->get();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }

    public function student_formatter($result)
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = array(
                'id' => $r->id,
                'name' => $r->name,
                'nim' => $r->nim,
                'prodi' => $r->prodi,
                'fakultas' => $r->fakultas,
                'phone' => $r->phone,
                'teacher_name' => $r->teacher_name,
                'lecturer_name' => $r->lecturer_name,
            );
        }
        return $formatter;
    }
}
