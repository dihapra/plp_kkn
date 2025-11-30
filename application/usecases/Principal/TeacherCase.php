<?php

namespace UseCases\Principal;

use Traits\DatatableTrait;
use Traits\SearchsTrait;

class TeacherCase
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

        $this->db->select('teachers.*, COUNT(student.id) as total_mahasiswa');
        $this->db->from('teachers');
        $this->db->join("student", "teachers.id = student.teacher_id", "left");
        $this->db->where('teachers.school_id', $school_id);
        $this->db->where('teachers.status_data', 'verified');
        $this->db->group_by('teachers.id'); // <-- penting supaya COUNT benar per guru

        $count_total = $this->db->count_all_results('', false);

        if (!empty($param['search'])) {
            $this->db->group_start();
            $this->db->like('teachers.name', $param['search']);
            $this->db->or_like('teachers.nik', $param['search']);
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

    public function teacher_formatter($result)
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = array(
                'id' => $r->id,
                'name' => $r->name,
                'nik' => $r->nik,
                'total_mahasiswa' => $r->total_mahasiswa,
            );
        }
        return $formatter;
    }
}
