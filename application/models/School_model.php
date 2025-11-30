<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_DB_query_builder $db
 */


class School_model extends CI_Model
{

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
