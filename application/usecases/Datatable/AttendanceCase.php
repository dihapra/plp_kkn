<?php

namespace Usecases\Datatable;

use Traits\DatatableTrait;
use Traits\SearchsTrait;

class AttendanceCase
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

    public function admin($params)
    {
        // Ambil nama siswa + status absensi per pertemuan
        $this->db->select("
        student.id as student_id,
        student.name as student_name,
        MAX(CASE WHEN attendance.meeting_number = 1  THEN attendance.status END) AS pertemuan_1,
        MAX(CASE WHEN attendance.meeting_number = 2  THEN attendance.status END) AS pertemuan_2,
        MAX(CASE WHEN attendance.meeting_number = 3  THEN attendance.status END) AS pertemuan_3,
        MAX(CASE WHEN attendance.meeting_number = 4  THEN attendance.status END) AS pertemuan_4,
        MAX(CASE WHEN attendance.meeting_number = 5  THEN attendance.status END) AS pertemuan_5,
        MAX(CASE WHEN attendance.meeting_number = 6  THEN attendance.status END) AS pertemuan_6,
        MAX(CASE WHEN attendance.meeting_number = 7  THEN attendance.status END) AS pertemuan_7,
        MAX(CASE WHEN attendance.meeting_number = 8  THEN attendance.status END) AS pertemuan_8,
        MAX(CASE WHEN attendance.meeting_number = 9  THEN attendance.status END) AS pertemuan_9,
        MAX(CASE WHEN attendance.meeting_number = 10 THEN attendance.status END) AS pertemuan_10,
        MAX(CASE WHEN attendance.meeting_number = 11 THEN attendance.status END) AS pertemuan_11,
        MAX(CASE WHEN attendance.meeting_number = 12 THEN attendance.status END) AS pertemuan_12,
        MAX(CASE WHEN attendance.meeting_number = 13 THEN attendance.status END) AS pertemuan_13,
        MAX(CASE WHEN attendance.meeting_number = 14 THEN attendance.status END) AS pertemuan_14,
        MAX(CASE WHEN attendance.meeting_number = 15 THEN attendance.status END) AS pertemuan_15,
        MAX(CASE WHEN attendance.meeting_number = 16 THEN attendance.status END) AS pertemuan_16
    ");
        $this->db->from("student");
        $this->db->join("attendance", "attendance.student_id = student.id", "left");

        if (!empty($params['school_id'])) {
            $this->db->where('student.school_id', $params['school_id']);
        }

        $this->db->group_by("student.id");

        // Total
        $count_total = $this->db->count_all_results('', false);

        // Search (optional sesuai fungsi Anda)
        $this->applySearch($params['search'], ['student.name']);

        // Filtered
        $count_filtered = $this->db->count_all_results('', false);

        // Limit & order untuk DataTables
        $this->applyDatatable($params);

        $query = $this->db->get();

        return result_formatter($query, $count_total, $count_filtered);
    }
}
