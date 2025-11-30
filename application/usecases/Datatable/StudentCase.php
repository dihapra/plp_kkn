<?php

namespace Usecases\Datatable;

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
    private function get_search_value()
    {
        return [
            'student.nim',
            'student.name',

        ];
    }

    public function lecture_datatable($param)
    {
        $nip = $this->CI->session->userdata('nip');
        $this->db->select('id');
        $this->db->where('nip', $nip);

        $dosen = $this->db->get('lecturers')->row();
        $this->db->select('student.*,teachers.name as teacher_name, school.name as school_name');
        $this->db->where('lecture_id', $dosen->id);
        $this->db->from('student');
        $this->db->join('teachers', 'student.teacher_id = teachers.id', 'left');
        $this->db->join('school', 'student.school_id = school.id', 'left');
        $count_total = $this->db->count_all_results('', false);
        if (!empty($param['fakultas'])) {
            $this->db->where('student.fakultas', $param['fakultas']);
        }
        if (!empty($param['prodi'])) {
            $this->db->where('student.prodi', $param['prodi']);
        }

        // Menerapkan pencarian jika ada
        if (!empty($param['search'])) {
            $this->db->group_start();
            $this->db->like('student.nim', $param['search']);
            $this->db->or_like('student.name', $param['search']);
            // $this->db->or_like('student.prodi', $param['prodi']);
            // $this->db->or_like('student.fakultas', $param['fakultas']);
            $this->db->group_end();
        }

        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        // var_dump($this->db->last_query(), $param);
        // exit();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }
    public function teacher_datatable($param)
    {
        $teacher_id = $this->CI->session->userdata('teacher_id');
        $this->db->select('student.*,teachers.name as teacher_name, school.name as school_name');
        $this->db->where('teacher_id', $teacher_id);
        $this->db->from('student');
        $this->db->join('teachers', 'student.teacher_id = teachers.id', 'left');
        $this->db->join('school', 'student.school_id = school.id', 'left');
        $count_total = $this->db->count_all_results('', false);
        if (!empty($param['fakultas'])) {
            $this->db->where('student.fakultas', $param['fakultas']);
        }
        if (!empty($param['prodi'])) {
            $this->db->where('student.prodi', $param['prodi']);
        }

        // Menerapkan pencarian jika ada
        if (!empty($param['search'])) {
            $this->db->group_start();
            $this->db->like('student.nim', $param['search']);
            $this->db->or_like('student.name', $param['search']);
            // $this->db->or_like('student.prodi', $param['prodi']);
            // $this->db->or_like('student.fakultas', $param['fakultas']);
            $this->db->group_end();
        }

        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        // var_dump($this->db->last_query(), $param);
        // exit();
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
                'leader' => $r->leader,
                'teacher_name' => $r->teacher_name,
                'school_name' => $r->school_name,
            );
        }
        return $formatter;
    }
}
