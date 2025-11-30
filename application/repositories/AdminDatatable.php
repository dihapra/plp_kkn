<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'traits/SearchTrait.php');
class AdminDatatable
{
    use SearchTrait;
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
    public function admin_datatable_student($param)
    {
        $this->db->select('
        student.*,
        teachers.name AS teacher_name,
        lecturers.name AS lecturer_name,
        school.name AS school_name
    ');
        $this->db->from('student');
        $this->db->join('teachers', 'teachers.id = student.teacher_id', 'left');
        $this->db->join('lecturers', 'lecturers.id = student.lecture_id', 'left');
        $this->db->join('school', 'school.id = student.school_id', 'left');
        $count_total = $this->db->count_all_results('', false);

        // Filter berdasarkan fakultas dan prodi jika ada
        if (!empty($param['fakultas'])) {
            $this->db->where('student.fakultas', $param['fakultas']);
        }
        if (!empty($param['prodi'])) {
            $this->db->where('student.prodi', $param['prodi']);
        }

        $search_columns = ['student.nim', 'student.name', 'teachers.name', 'lecturers.name', 'school.name'];
        $this->applySearch($param['search'], $search_columns);

        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        // var_dump($this->db->last_query());
        // exit();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }
    public function admin_datatable_lecture($param)
    {
        $this->db->select('lecturers.*');
        $this->db->from('lecturers');
        $count_total = $this->db->count_all_results('', false);
        if (!empty($param['fakultas'])) {
            $this->db->where('lecturers.fakultas', $param['fakultas']);
        }
        if (!empty($param['prodi'])) {
            $this->db->where('lecturers.prodi', $param['prodi']);
        }


        $search_columns = ['lecturers.nip', 'lecturers.name'];
        $this->applySearch($param['search'], $search_columns);
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
    public function admin_datatable_school($param)
    {
        $this->db->select('school.*, 
            principal.name as principal_name,
            principal.email as principal_email,
            principal.phone as principal_phone,
            principal.bank as principal_bank,
            principal.account_number as principal_account_number,
            principal.account_name as principal_account_name,
            principal.nik as principal_nik,
            principal.status as principal_status,
            principal.book as principal_book
        ');
        $this->db->from('school');
        $this->db->join(
            'principal',
            'principal.school_id = school.id AND principal.status_data = "verified"',
            'left'
        );
        $count_total = $this->db->count_all_results('', false);

        // Menerapkan pencarian jika ada
        $this->applySearch($param['search'], ['school.name', 'principal.name', 'principal.email']);
        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        // var_dump($this->db->last_query());
        // exit();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }
    public function admin_datatable_teacher($param)
    {
        $this->db->select('teachers.*,school.name as school_name,school.id as school_id');
        $this->db->from('teachers');
        $this->db->join('school', 'teachers.school_id = school.id', 'left');
        // $this->db->join('student', 'student.teacher_id = teachers.id', 'left');
        $count_total = $this->db->count_all_results('', false);

        if ($param['school_id']) {
            $this->db->where('school_id', $param['school_id']);
        }
        $this->db->where_not_in('teachers.status_data', ['unverified', 'rejected']);

        // Menerapkan pencarian jika ada
        if (!empty($param['search'])) {
            $this->db->group_start();
            $this->db->like('teachers.name', $param['search']);
            $this->db->group_end();
        }
        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }
    public function datatable_unverified_teacher($param)
    {
        $this->db->select('teachers.*,school.name as school_name');
        $this->db->from('teachers');
        $this->db->join('school', 'teachers.school_id = school.id', 'left');
        $count_total = $this->db->count_all_results('', false);

        if ($param['school_id']) {
            $this->db->where('school_id', $param['school_id']);
        }
        $this->db->where('teachers.status_data', "unverified");
        // Menerapkan pencarian jika ada
        if (!empty($param['search'])) {
            $this->db->group_start();
            $this->db->like('teachers.name', $param['search']);
            $this->db->group_end();
        }
        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }
    public function datatable_unverified_principal($param)
    {
        $this->db->select('principal.*,school.name as school_name');
        $this->db->from('principal');
        $this->db->join('school', 'principal.school_id = school.id', 'left');
        $count_total = $this->db->count_all_results('', false);

        if ($param['school_id']) {
            $this->db->where('school_id', $param['school_id']);
        }
        $this->db->where('principal.status_data', "unverified");
        // Menerapkan pencarian jika ada
        if (!empty($param['search'])) {
            $this->db->group_start();
            $this->db->like('principal.name', $param['search']);
            $this->db->group_end();
        }
        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }
    public function admin_datatable_user($param)
    {
        $this->db->select('users.*,teachers.nik, principal.nik as principal_nik');
        $this->db->from('users');
        $this->db->join('teachers', 'users.teacher_id = teachers.id', 'left');
        $this->db->join('student', 'users.nim = student.nim', 'left');
        $this->db->join('lecturers', 'users.nip = lecturers.nip', 'left');
        $this->db->join('principal', 'users.principal_id = principal.id', 'left');
        $this->db->where_not_in('role', ['admin', 'super_admin']);
        $count_total = $this->db->count_all_results('', false);


        // Menerapkan pencarian jika ada
        if (!empty($param['search'])) {
            $this->db->group_start();
            $this->db->like('users.email', $param['search']);
            $this->db->or_like('student.nim', $param['search']);
            $this->db->or_like('student.name', $param['search']);
            $this->db->or_like('teachers.name', $param['search']);
            $this->db->or_like('lecturers.name', $param['search']);
            $this->db->or_like('users.role', $param['search']);

            $this->db->group_end();
        }
        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        // var_dump($this->db->last_query());
        // exit();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }
    public function admin_datatable_all($param)
    {
        $this->db->select('
        student.id,
        school.name as school_name,
        school.principal,
        school.phone as principal_phone,
        lecturers.name as lecturer_name,
        student.email as student_email,
        student.name as student_name,
        student.nim,
        student.phone as student_phone,
        student.prodi as student_prodi,
        student.fakultas as student_fakultas,
        teachers.name as teacher_name
    ');
        $this->db->from('student');
        // $this->db->join('teachers', 'teachers.id = student.teacher_id', 'left');
        $this->db->join('lecturers', 'lecturers.id = student.lecture_id', 'left');
        $this->db->join('school', 'school.id = student.school_id', 'left');
        $this->db->join('teachers', 'teachers.id = student.teacher_id', 'left');
        $count_total = $this->db->count_all_results('', false);

        // Filter berdasarkan fakultas dan prodi jika ada
        if (!empty($param['fakultas'])) {
            $this->db->where('student.fakultas', $param['fakultas']);
        }
        if (!empty($param['prodi'])) {
            $this->db->where('student.prodi', $param['prodi']);
        }

        // Menerapkan pencarian jika ada
        if (!empty($param['search'])) {
            $this->db->group_start();
            $this->db->like('nim', $param['search']);
            $this->db->or_like('student.name', $param['search']);
            $this->db->or_like('principal', $param['search']);
            // $this->db->or_like('teachers.name', $param['search']);
            $this->db->or_like('lecturers.name', $param['search']);
            $this->db->or_like('school.name', $param['search']);
            $this->db->group_end();
        }
        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        // var_dump($this->db->last_query());
        // exit();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }
    public function datatable_unpaid_teacher($param)
    {
        $this->db->select('teachers.*, school.name as school_name');
        $this->db->from('teachers');
        $this->db->join('school', 'teachers.school_id = school.id', 'left');
        $this->db->where('teachers.status_pembayaran', 'belum dibayar');
        $count_total = $this->db->count_all_results('', false);

        if (!empty($param['school_id'])) {
            $this->db->where('teachers.school_id', $param['school_id']);
        }

        // Menerapkan pencarian jika ada
        if (!empty($param['search'])) {
            $this->db->group_start();
            $this->db->like('teachers.name', $param['search']);
            $this->db->or_like('school.name', $param['search']);
            $this->db->group_end();
        }
        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }

    public function datatable_unpaid_principal($param)
    {
        $this->db->select('principal.*, school.name as school_name');
        $this->db->from('principal');
        $this->db->join('school', 'principal.school_id = school.id', 'left');
        $this->db->where('principal.status_pembayaran', 'belum dibayar');
        $count_total = $this->db->count_all_results('', false);

        if (!empty($param['school_id'])) {
            $this->db->where('principal.school_id', $param['school_id']);
        }

        // Menerapkan pencarian jika ada
        if (!empty($param['search'])) {
            $this->db->group_start();
            $this->db->like('principal.name', $param['search']);
            $this->db->or_like('school.name', $param['search']);
            $this->db->group_end();
        }
        $count_filtered = $this->db->count_all_results('', false);

        // Menambahkan batasan dan pengurutan
        $this->db->limit($param['length'], $param['start']);
        $this->db->order_by($param['order_column'], $param['order_dir']);

        // Melakukan kueri dan mengembalikan hasilnya
        $query = $this->db->get();
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered
        ];
    }

    public function datatable_sekolah_tanpa_kepsek($param)
    {
        $this->db->select('school.*');
        $this->db->from('school');
        $this->db->join('principal', 'principal.school_id = school.id', 'left');
        $this->db->where('principal.id IS NULL');
        $count_total = $this->db->count_all_results('', false);

        $search_columns = ['school.name'];
        $this->applySearch($param['search'], $search_columns);

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

    public function datatable_mahasiswa_tanpa_guru($param)
    {
        $this->db->select('student.*, school.name as school_name, lecturers.name as lecturer_name');
        $this->db->from('student');
        $this->db->join('school', 'school.id = student.school_id', 'left');
        $this->db->join('lecturers', 'lecturers.id = student.lecture_id', 'left');
        $this->db->where('student.teacher_id IS NULL');
        $count_total = $this->db->count_all_results('', false);

        $search_columns = ['student.nim', 'student.name', 'school.name'];
        $this->applySearch($param['search'], $search_columns);

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
}
