<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ProdiRepository
{
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    /**
     * Prodi di sekolah tertentu yang masih punya mahasiswa tanpa teacher_id.
     * Return: [ ['id' => ..., 'name' => ...], ... ]
     */
    public function get_prodi_for_registration(int $school_id): array
    {
        if (!$school_id) return [];

        $sql = "SELECT p.id, p.name
            FROM prodi p
            WHERE EXISTS (
                SELECT 1
                FROM student s
                WHERE s.school_id = ?
                  AND s.prodi_id  = p.id
                  AND (s.teacher_id IS NULL OR s.teacher_id = 0)
                  AND NOT EXISTS (
                      SELECT 1
                      FROM temp_teacher tt
                      WHERE FIND_IN_SET(s.id, tt.student_ids)
                      -- opsional:
                      -- AND tt.status IN ('pending','submitted')
                      -- AND (tt.expires_at IS NULL OR tt.expires_at > NOW())
                  )
            )
            GROUP BY p.id, p.name
            ORDER BY p.name ASC";

        return $this->db->query($sql, [$school_id])->result_array();
    }

    private function handle_school_search($school_id)
    {
        $this->db->join('student', 'student.prodi_id = prodi.id', 'left');
        $this->db->where('student.school_id', $school_id);
    }
}
