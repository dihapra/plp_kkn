<?php

namespace UseCases\Lecturer;

use Exception;

class LogbookCase
{
    protected $CI;
    protected $db;

    const EDITABLE_MEETINGS = [4, 5, 6, 7, 9, 10, 11, 12, 13, 14];

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    public function execute_grouped()
    {
        $lecturer_id = $this->getLecturerId();

        $this->db->select('student.name as student_name, student.nim as student_nim, logbook.id as logbook_id, logbook.meeting_number');
        $this->db->from('student');
        $this->db->join('logbook', 'student.id = logbook.student_id', 'left');
        $this->db->where('student.lecture_id', $lecturer_id);
        $this->db->group_start();
        $this->db->where_in('logbook.meeting_number', self::EDITABLE_MEETINGS);
        $this->db->or_where('logbook.id IS NULL', null, false);
        $this->db->group_end();
        $this->db->order_by('student.nim', 'ASC');
        $this->db->order_by('logbook.meeting_number', 'ASC');

        $raw_data = $this->db->get()->result();

        $grouped_logbooks = [];
        foreach ($raw_data as $row) {
            $nim = $row->student_nim;
            if (!isset($grouped_logbooks[$nim])) {
                $grouped_logbooks[$nim] = [
                    'nama' => $row->student_name,
                    'nim' => $nim,
                    'pertemuan' => []
                ];
            }

            if ($row->logbook_id !== null && in_array($row->meeting_number, self::EDITABLE_MEETINGS)) {
                $grouped_logbooks[$nim]['pertemuan'][$row->meeting_number] = [
                    'id' => $row->logbook_id,
                    'status' => 'filled'
                ];
            }
        }

        return $grouped_logbooks;
    }

    public function get_logbook_by_id($id)
    {
        $lecturer_id = $this->getLecturerId();

        $this->db->select('logbook.*, student.name as student_name, student.nim as student_nim');
        $this->db->from('logbook');
        $this->db->join('student', 'logbook.student_id = student.id');
        $this->db->where('logbook.id', $id);
        $this->db->where('student.lecture_id', $lecturer_id);
        $this->db->where_in('logbook.meeting_number', self::EDITABLE_MEETINGS);

        $logbook = $this->db->get()->row();

        if ($logbook) {
            $logbook->activities = $this->db
                ->where('logbook_id', $logbook->id)
                ->order_by('created_at', 'ASC')
                ->get('logbook_activity')
                ->result();
        }

        return $logbook;
    }

    public function save_feedback($logbook_id, $feedback)
    {
        $lecturer_id = $this->getLecturerId();
        $logbook = $this->get_logbook_by_id($logbook_id);
        if (!$logbook) {
            throw new Exception('Logbook tidak ditemukan atau tidak dapat diakses.');
        }

        $this->db->where('id', $logbook_id);
        $this->db->update('logbook', [
            'feedback_lecture' => $feedback,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $lecturer_id
        ]);

        $dbError = $this->db->error();
        if (!empty($dbError['code'])) {
            throw new Exception('Feedback gagal disimpan.');
        }

        return $this->get_logbook_by_id($logbook_id);
    }

    protected function getLecturerId()
    {
        $nip = $this->CI->session->userdata('nip');
        if (!$nip) {
            throw new Exception('Data dosen tidak ditemukan pada sesi.');
        }
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception('Dosen tidak ditemukan.');
        }
        return (int) $lecturer->id;
    }
}
