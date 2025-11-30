<?php

namespace UseCases\Student;

use Exception;
use LogbookValidator;
use Repositories\StudentRepository;
use Repositories\SubmissionRepository;
use Throwable;

class SaveLogbookCase
{

    public $CI;
    public $db;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
    public function save_logbook()
    {
        try {
            $input_data = json_decode(trim(file_get_contents("php://input")), true);
            LogbookValidator::validate_save_logbook($input_data);

            $nim = $this->CI->session->userdata('nim');
            $repo = new StudentRepository();
            $student = $repo->get_by_key("nim", $nim);
            if (!$student) {
                echo json_encode(['status' => 'error', 'message' => 'Mahasiswa tidak terautentikasi.']);
                return;
            }

            $logbook_data = [
                'student_id'     => $student->id,
                'meeting_number' => $input_data['meeting_number'],
                'problem'        => $input_data['permasalahan'],
                'solution'       => $input_data['solusi'],
                'summary'        => $input_data['kesimpulan'],
                'created_at'     => date('Y-m-d H:i:s'),
                'created_by'     => $student->id,
            ];

            $logbook_entries = $input_data['logbook'];


            // Simpan logbook utama
            $this->insert_logbook($logbook_data, $logbook_entries);
        } catch (Throwable $e) {
            // Tanggapi jika terjadi kesalahan
            throw $e;
        }
    }
    private function insert_logbook($data, $logbook_entries)
    {
        $this->db->trans_start(); // Memulai transaksi

        try {
            // Insert data logbook utama
            $this->db->insert('logbook', $data);
            $logbook_id = $this->db->insert_id();

            if (!$logbook_id) {
                throw new Exception('Gagal menyimpan data logbook.');
            }

            // Insert logbook activities
            foreach ($logbook_entries as $entry) {
                $data_activity = [
                    'logbook_id' => $logbook_id,
                    'activity' => $entry['kegiatan'], // Pastikan menggunakan $entry
                    'observation' => $entry['hasil'], // Pastikan menggunakan $entry
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $data['student_id']
                ];

                $this->db->insert('logbook_activity', $data_activity);

                if ($this->db->affected_rows() == 0) {
                    throw new Exception('Gagal menyimpan aktivitas logbook.');
                }
            }

            $this->db->trans_complete(); // Selesaikan transaksi

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Terjadi kesalahan dalam transaksi database.');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback(); // Batalkan transaksi jika terjadi error
            throw $e; // Lempar error ke controller
        }
    }
    public function get_by_student_id($student_id)
    {
        $this->db->where('student_id', $student_id);
        $logbooks = $this->db->get('logbook')->result_array();

        if (!$logbooks) {
            return null;
        }

        // Ambil semua logbook_id yang dimiliki student ini
        $logbook_ids = array_column($logbooks, 'id');

        // Ambil semua aktivitas yang berhubungan dengan logbook-logbook ini
        $this->db->where_in('logbook_id', $logbook_ids);
        $logbook_activities = $this->db->get('logbook_activity')->result_array();

        // Buat array untuk mengelompokkan aktivitas berdasarkan logbook_id
        $logbook_activity_map = [];
        foreach ($logbook_activities as $activity) {
            $logbook_activity_map[$activity['logbook_id']][] = $activity;
        }

        // Gabungkan data logbook dengan aktivitasnya
        $result = [];
        foreach ($logbooks as $logbook) {
            $logbook_id = $logbook['id'];
            $result[] = [
                'logbook' => $logbook,
                'logbook_activity' => isset($logbook_activity_map[$logbook_id]) ? $logbook_activity_map[$logbook_id] : []
            ];
        }

        return $result;
    }
    public function get_by_student_and_meeting($student_id, $meeting_number)
    {
        // Ambil data logbook utama berdasarkan student_id dan meeting_number
        $this->db->where('student_id', $student_id);
        $this->db->where('meeting_number', $meeting_number);
        $logbook = $this->db->get('logbook')->row_array();

        if (!$logbook) {
            return null; // Jika tidak ada logbook, kembalikan null
        }

        // Ambil semua aktivitas yang terkait dengan logbook ini
        $this->db->where('logbook_id', $logbook['id']);
        $logbook_activity = $this->db->get('logbook_activity')->result_array();

        return [
            'logbook' => $logbook,
            'logbook_activity' => $logbook_activity
        ];
    }
    public function update_logbook()
    {
        // Ambil data JSON dari body request
        $input_data = json_decode(trim(file_get_contents("php://input")), true);

        // Validasi dasar
        if (!isset($input_data['logbook']) || !is_array($input_data['logbook']) || empty($input_data['logbook'])) {
            throw new Exception("Logbook tidak boleh kosong.");
        }
        foreach ($input_data['logbook'] as $entry) {
            if (!isset($entry['activity']) || !isset($entry['observation']) || empty(trim($entry['activity'])) || empty(trim($entry['observation']))) {
                throw new Exception("Setiap entri logbook harus memiliki kegiatan dan hasil.");
            }
        }
        // Validasi input lainnya
        $this->CI->form_validation->set_data($input_data);
        $this->CI->form_validation->set_rules('meeting_number', 'Pertemuan', 'required|integer|greater_than[0]|less_than_equal_to[16]');
        $this->CI->form_validation->set_rules('problem', 'Permasalahan', 'required');
        $this->CI->form_validation->set_rules('solution', 'Solusi yang Diberikan', 'required');
        $this->CI->form_validation->set_rules('summary', 'Kesimpulan', 'required');

        if ($this->CI->form_validation->run() == FALSE) {
            $errs = $this->CI->form_validation->error_array(); // ['field' => 'msg', ...]
            $msg  = implode(' | ', array_map('trim', array_values($errs)));
            throw new Exception($msg);
        }

        // Ambil nilai input
        $meeting_number = $input_data['meeting_number'];
        $logbook_entries = $input_data['logbook']; // Array entri logbook
        $problem = $input_data['problem'];
        $solution = $input_data['solution'];
        $summary = $input_data['summary'];

        // Ambil mahasiswa dari session
        $nim = $this->CI->session->userdata('nim');
        $student = $this->db->where('nim', $nim)->get('student')->row();
        if (!$student) {
            echo json_encode(['status' => 'error', 'message' => 'Mahasiswa tidak terautentikasi.']);
            return;
        }

        // Persiapkan data untuk update logbook utama
        $logbook_data = [
            'student_id' => $student->id,
            'meeting_number' => $meeting_number,
            'problem' => $problem,
            'solution' => $solution,
            'summary' => $summary,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $student->id
        ];

        $this->db->where('student_id', $student->id);
        $this->db->where('meeting_number', $meeting_number);
        $logbook = $this->db->get('logbook')->row();
        $logbook_id = $logbook->id;
        if (!$logbook) {
            return null; // Jika tidak ada logbook, kembalikan null
        }

        // Panggil model untuk update logbook
        $this->db->trans_start();
        $this->db->where('id', $logbook_id);
        $this->db->update('logbook', $logbook_data);

        // Hapus seluruh aktivitas logbook lama untuk logbook ini
        $this->db->where('logbook_id', $logbook_id);
        $this->db->delete('logbook_activity');

        // Insert aktivitas logbook baru
        foreach ($logbook_entries as $entry) {
            $data_activity = [
                'logbook_id' => $logbook_id,
                'activity' => $entry['activity'],
                'observation' => $entry['observation'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $logbook_data['student_id']
            ];
            $this->db->insert('logbook_activity', $data_activity);

            if ($this->db->affected_rows() == 0) {
                throw new Exception('Gagal menyimpan aktivitas logbook.');
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Terjadi kesalahan dalam transaksi database.');
        }
    }
}
