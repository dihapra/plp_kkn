<?php

namespace Repositories;

use SearchTrait;

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property School_model $School_model
 */
require_once(APPPATH . 'traits/SearchTrait.php');
class SchoolRepository
{
    use SearchTrait;
    protected $CI;
    protected $db;

    /** @var School_model */
    protected $model;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
        $this->CI->load->model('School_model',);
        /** @var School_model $this->CI->School_model */
        $this->model = $this->CI->School_model;
    }
    public function surat_tugas_kepsek($school_id)
    {
        $this->db->select("
        school.name AS nama_sekolah,
        principal.name AS kepsek_nama,
        principal.nik,
        principal.account_name,
        CONCAT(principal.account_number, ' (', principal.bank, ')') AS no_rekening,
        principal.status as status_perkawinan
    ");
        $this->db->from('school');
        $this->db->join('principal', 'principal.school_id = school.id', 'left');
        $this->db->where('school.id', $school_id);
        $this->db->where('principal.status_data', 'verified');
        $query = $this->db->get();
        return $query->row(); // atau result() jika lebih dari satu baris
    }

    public function get_school()
    {
        return $this->db->select('id, name')->from('school')->get()->result_array();
    }
    /**
     * Sekolah untuk pendaftaran pamong:
     * hanya sekolah yang masih punya mahasiswa tanpa teacher_id (NULL/0).
     * @return array{id:int,name:string}[]
     */
    public function get_school_for_registration(): array
    {
        $db = $this->db;

        // Sekolah eligible jika masih ada siswa unassigned DAN tidak sedang di-booking di temp_teachers
        $existsSql = "EXISTS (
        SELECT 1
        FROM student st
        WHERE st.school_id = s.id
          AND (st.teacher_id IS NULL OR st.teacher_id = 0)
          AND NOT EXISTS (
              SELECT 1
              FROM temp_teacher tt
              WHERE FIND_IN_SET(st.id, tt.student_ids)
              -- opsional kalau ada kolom status/TTL:
              -- AND tt.status IN ('pending','submitted')
              -- AND (tt.expires_at IS NULL OR tt.expires_at > NOW())
          )
    )";

        return $db->select('s.id, s.name')
            ->from('school s')
            // ->where('s.is_active', 1)
            ->where($existsSql, null, false)
            ->order_by('s.name', 'ASC')
            ->get()->result_array();
    }
}
