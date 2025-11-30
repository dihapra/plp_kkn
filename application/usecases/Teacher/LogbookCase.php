<?php

namespace UseCases\Teacher;

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

    public function execute()
    {
        $teacher_id = $this->CI->session->userdata('teacher_id');

        $this->db->select('logbook.*, student.name as student_name, student.nim as student_nim');
        $this->db->from('logbook');
        $this->db->join('student', 'logbook.student_id = student.id');
        $this->db->where('student.teacher_id', $teacher_id);
        $this->db->where_in('logbook.meeting_number', self::EDITABLE_MEETINGS);
        $this->db->order_by('logbook.created_at', 'DESC'); // Order by newest first

        $query = $this->db->get();
        return $query->result();
    }

    public function execute_grouped()
    {
        $teacher_id = $this->CI->session->userdata('teacher_id');

        $this->db->select('student.name as student_name, student.nim as student_nim, logbook.id as logbook_id, logbook.meeting_number');
        $this->db->from('student');
        $this->db->join('logbook', 'student.id = logbook.student_id', 'left');
        $this->db->where('student.teacher_id', $teacher_id);

        // Filter logbook entries by meeting number, but this condition applies to the LEFT JOINED table
        // So, students without logbooks will still be included.
        $this->db->group_start(); // Start a group for OR conditions
        $this->db->where_in('logbook.meeting_number', self::EDITABLE_MEETINGS);
        $this->db->or_where('logbook.id IS NULL'); // Include students with no logbooks
        $this->db->group_end();

        $this->db->order_by('student.nim', 'ASC');
        $this->db->order_by('logbook.meeting_number', 'ASC');

        $query = $this->db->get();
        $raw_data = $query->result();

        $grouped_logbooks = [];
        foreach ($raw_data as $row) {
            $nim = $row->student_nim;
            // log_message("error", $nim);
            if (!isset($grouped_logbooks[$nim])) {
                $grouped_logbooks[$nim] = [
                    'nama' => $row->student_name,
                    'nim'  => $nim,
                    'pertemuan' => []
                ];
            }
            // Only add logbook data if it exists and is within editable meetings
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
        $teacher_id = $this->CI->session->userdata('teacher_id');

        $this->db->select('logbook.*, student.name as student_name, student.nim as student_nim');
        $this->db->from('logbook');
        $this->db->join('student', 'logbook.student_id = student.id');
        $this->db->where('logbook.id', $id);
        $this->db->where('student.teacher_id', $teacher_id); // Ensure teacher can only view their students' logbooks
        $this->db->where_in('logbook.meeting_number', self::EDITABLE_MEETINGS);

        $query = $this->db->get();
        $logbook = $query->row();

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
        $logbook = $this->get_logbook_by_id($logbook_id);
        if (!$logbook) {
            throw new Exception('Logbook tidak ditemukan atau tidak dapat diakses.');
        }

        $this->db->where('id', $logbook_id);
        $this->db->update('logbook', [
            'feedback_teacher' => $feedback,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->CI->session->userdata('teacher_id')
        ]);

        $dbError = $this->db->error();
        if (!empty($dbError['code'])) {
            throw new Exception('Feedback gagal disimpan.');
        }

        return $this->get_logbook_by_id($logbook_id);
    }
}
