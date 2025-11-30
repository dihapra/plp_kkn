<?php

namespace Repositories;

use Exception;

class StudentRepository
{
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
    public function get_student_with_teacher($school_id)
    {
        $this->db->select("
        teachers.name AS guru_pamong,
        teachers.nik,
        teachers.account_name,
        CONCAT(teachers.account_number, ' (', teachers.bank, ')') AS no_rekening,
        teachers.status AS status_perkawinan,
        student.name AS nama_mahasiswa,
        student.nim,
        student.prodi
    ");
        $this->db->from('teachers');
        $this->db->join('student', 'student.teacher_id = teachers.id', 'left');
        $this->db->where('teachers.school_id', $school_id);
        $this->db->where('teachers.status_data', 'verified');
        $this->db->order_by('teachers.name', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }
    /**
     * Siswa untuk registrasi: hanya yang belum punya teacher_id (NULL/0).
     * Bisa diberi term pencarian & include_ids (untuk kasus edit).
     *
     * @param int      $school_id   wajib
     * @param int      $prodi_id    wajib
     * @param string|null $term     optional (search by name)
     * @param array<int>|null $include_ids  optional (tetap tampil walau sudah punya teacher_id)
     * @param int      $limit
     * @param int      $offset
     * @return array[] {id, name}
     */
    public function get_students_for_registration(
        int $school_id,
        int $prodi_id,
        ?string $term = null,
        ?array $include_ids = null,
        int $limit = 200,
        int $offset = 0
    ): array {
        $db = $this->db;

        $db->select('s.id, s.name')
            ->from('student s')
            ->where('s.school_id', $school_id)
            ->where('s.prodi_id',  $prodi_id);

        if ($term) {
            $db->like('s.name', $term);
        }

        // ((teacher_id kosong) DAN TIDAK sedang di-booking)  ATAU  (s.id termasuk include_ids saat edit)
        $db->group_start();

        // blok "available"
        $db->group_start()
            ->group_start()
            ->where('s.teacher_id IS NULL', null, false)
            ->or_where('s.teacher_id', 0)
            ->group_end()
            ->where("NOT EXISTS (
                          SELECT 1
                          FROM temp_teacher tt
                          WHERE FIND_IN_SET(s.id, tt.student_ids)
                          -- opsional:
                          -- AND tt.status IN ('pending','submitted')
                          -- AND (tt.expires_at IS NULL OR tt.expires_at > NOW())
                      )", null, false);
        $db->group_end();

        // pengecualian: saat edit, tetap tampilkan siswa yang sudah dipilih
        if (!empty($include_ids)) {
            $include_ids = array_map('intval', $include_ids);
            $db->or_where_in('s.id', $include_ids);
        }

        $db->group_end();

        $rows = $db->order_by('s.name', 'ASC')
            ->limit($limit, $offset)
            ->get()->result_array();

        return $rows;
    }


    public function get_by_key($key, $value)
    {
        $this->db->where($key, $value);
        return $this->db->get('student')->row();
    }
    public function get_student_relation($nim)
    {
        try {
            $this->db->select('school.name as school_name, school.id as school_id,teachers.name as teacher_name,teachers.id as teacher_id,lecturers.name as lecture_name, lecturers.id as lecture_id');
            $this->db->where('student.nim', $nim);
            $this->db->from('student');
            $this->db->join('school', 'student.school_id = school.id', 'left');
            $this->db->join('teachers', 'student.teacher_id = teachers.id', 'left');
            $this->db->join('lecturers', 'student.lecture_id = lecturers.id', 'left');
            return $this->db->get()->row();
        } catch (Exception $th) {
            throw $th;
        }
    }
}
