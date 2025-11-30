<?php

namespace UseCases\Lecturer;

use Exception;
use Throwable;
use Traits\DatatableTrait;
use Traits\SearchsTrait;

class AbsensiCase
{
    use SearchsTrait, DatatableTrait;
    public $CI;
    public $db;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
    public function datatable($params)
    {
        $nip = $this->CI->session->userdata('nip');
        $this->db->select('id');
        $this->db->where('nip', $nip);

        $dosen = $this->db->get('lecturers')->row();
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
        $this->db->where('student.lecture_id', $dosen->id);
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
    public function absensi_formatter($result)
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = [
                'student_id'   => $r->student_id,
                'student_name' => $r->student_name,
                'pertemuan_1'  => $r->pertemuan_1 ?? '-',
                'pertemuan_2'  => $r->pertemuan_2 ?? '-',
                'pertemuan_3'  => $r->pertemuan_3 ?? '-',
                'pertemuan_4'  => $r->pertemuan_4 ?? '-',
                'pertemuan_5'  => $r->pertemuan_5 ?? '-',
                'pertemuan_6'  => $r->pertemuan_6 ?? '-',
                'pertemuan_7'  => $r->pertemuan_7 ?? '-',
                'pertemuan_8'  => $r->pertemuan_8 ?? '-',
                'pertemuan_9'  => $r->pertemuan_9 ?? '-',
                'pertemuan_10' => $r->pertemuan_10 ?? '-',
                'pertemuan_11' => $r->pertemuan_11 ?? '-',
                'pertemuan_12' => $r->pertemuan_12 ?? '-',
                'pertemuan_13' => $r->pertemuan_13 ?? '-',
                'pertemuan_14' => $r->pertemuan_14 ?? '-',
                'pertemuan_15' => $r->pertemuan_15 ?? '-',
                'pertemuan_16' => $r->pertemuan_16 ?? '-',
            ];
        }
        return $formatter;
    }
    public function verif_by_lecture()
    {
        try {
            $meeting_number = $_POST['meeting_number'];
            $status = $_POST['status'];
            $student_id = $_POST['student_id'];
            if (empty($meeting_number)) {
                throw new Exception('Pertemuan tidak valid');
            }
            if (empty($status)) {
                throw new Exception('status tidak valid');
            }
            if (empty($student_id)) {
                throw new Exception('ID siswa tidak valid');
            }
            $nip = $this->CI->session->userdata('nip');
            $this->db->select('id');
            $this->db->where('nip', $nip);

            $dosen = $this->db->get('lecturers')->row();
            $data_to_input = [
                'meeting_number' => $meeting_number,
                'student_id' => $student_id,
                'verify_by_lecture' => $dosen->id,
                'status' => $status,
                'date' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $dosen->id,
            ];
            $data = $this->db->where('meeting_number', $meeting_number)->where('student_id', $student_id)->get('attendance')->row();
            if ($data) {
                $this->db->where('id', $data->id)->update('attendance', $data_to_input);
            } else {
                $this->db->insert('attendance', $data_to_input);
            }
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
