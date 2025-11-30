<?php

namespace UseCases\Teacher;

use Exception;
use UseCases\AspekPenilaian;

class EvaluationCase
{
    public $CI;
    public $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    public function get_score_by_student_id($type, $student_id)
    {
        $table = '';
        switch ($type) {
            case 1:
                $table = 'assist_intracurricular';
                break;
            case 2:
                $table = 'assist_extracurricular';
                break;
            case 3:
                $table = 'student_attitude';
                break;
            default:
                throw new Exception("Invalid evaluation type.");
        }

        $teacher_id = $this->CI->session->userdata('teacher_id');

        $this->db->select('indicator, score');
        $this->db->from($table);
        $this->db->where('student_id', $student_id);
        $this->db->where('teacher_id', $teacher_id); // Ensure teacher_id is correctly used
        $query = $this->db->get();

        return $query->result();
    }

    public function save($type)
    {
        $table = '';
        switch ($type) {
            case 1:
                $table = 'assist_intracurricular';
                break;
            case 2:
                $table = 'assist_extracurricular';
                break;
            case 3:
                $table = 'student_attitude';
                break;
            default:
                throw new Exception("Invalid evaluation type.");
        }

        $student_id = $this->CI->input->post('student_id');
        $scores = $this->CI->input->post('nilai'); // 'nilai' is the name of the input array
        $teacher_id = $this->CI->session->userdata('teacher_id');

        if (empty($student_id) || empty($scores) || !is_array($scores)) {
            throw new Exception("Invalid input data for evaluation.");
        }

        [$allowedKeys, $aspectByKey] = $this->get_aspek_keys_map($type);

        // Check if a record already exists for this student and teacher
        $existing_record = $this->db->where('student_id', $student_id)
            ->where('teacher_id', $teacher_id)
            ->get($table)
            ->row();

        if ($existing_record) {
            // Update existing record
            foreach ($scores as $indicator => $score) {
                if (!in_array($indicator, $allowedKeys, true)) {
                    throw new Exception("Indikator '$indicator' tidak valid untuk type {$type}.");
                }

                $data = [
                    'score' => (int)$score,
                    'aspek' => $aspectByKey[$indicator] ?? null,
                    'role' => 'teacher',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $teacher_id,
                ];
                $this->db->where('student_id', $student_id)
                    ->where('teacher_id', $teacher_id)
                    ->where('indicator', $indicator)
                    ->update($table, $data);
            }
        } else {
            // Insert new records
            foreach ($scores as $indicator => $score) {
                if (!in_array($indicator, $allowedKeys, true)) {
                    throw new Exception("Indikator '$indicator' tidak valid untuk type {$type}.");
                }

                $data = [
                    'student_id' => $student_id,
                    'teacher_id' => $teacher_id,
                    'indicator' => $indicator,
                    'aspek' => $aspectByKey[$indicator] ?? null,
                    'role' => 'teacher',
                    'score' => (int)$score,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $teacher_id,
                ];
                $this->db->insert($table, $data);
            }
        }
    }

    /**
     * Ambil daftar indikator (keys) + map key -> aspek (nama kelompok)
     * supaya kolom 'aspek' bisa diisi otomatis seperti di flow dosen.
     */
    private function get_aspek_keys_map($type)
    {
        $uc = new AspekPenilaian();

        $items  = [];
        $schema = 'flat';

        switch ((int)$type) {
            case 1:
                $items  = $uc->penilaian_asistensi_intrakurikuler ?? [];
                $schema = 'flat';
                break;
            case 2:
                $items  = $uc->penilaian_asistensi_kokurikuler
                    ?? $uc->penilaian_asistensi_ekstrakurikuler
                    ?? [];
                $schema = 'grouped';
                break;
            case 3:
                $items  = $uc->penilaian_sikap ?? [];
                $schema = 'grouped';
                break;
            default:
                throw new Exception('type tidak valid');
        }

        $keys        = [];
        $aspectByKey = [];

        if ($schema === 'flat') {
            foreach ((array)$items as $row) {
                if (empty($row['key'])) {
                    continue;
                }

                $key   = (string)$row['key'];
                $label = (string)($row['label'] ?? '');

                $keys[] = $key;

                $aspek = null;
                if ($label !== '') {
                    // Some labels use weird dash characters (replacement char sequences, en/em dash, ASCII dash).
                    $normalized = preg_replace(
                        ['/�\?"|�\?�|�"/u', '/\s+/'],
                        [' - ', ' '],
                        $label
                    );
                    $parts = preg_split('/\s*[\x{2013}\x{2014}-]\s*/u', $normalized, 2);
                    if (is_array($parts) && count($parts) === 2 && $parts[0] !== '') {
                        $aspek = trim($parts[0]);
                    }
                }
                $aspectByKey[$key] = $aspek;
            }
        } else {
            foreach ((array)$items as $g) {
                $aspek     = (string)($g['aspek'] ?? $g['aspect'] ?? '');
                $indikator = $g['indikator'] ?? $g['indicators'] ?? [];

                if (!is_array($indikator)) {
                    continue;
                }

                foreach ($indikator as $row) {
                    if (empty($row['key'])) {
                        continue;
                    }

                    $key = (string)$row['key'];
                    $keys[] = $key;
                    $aspectByKey[$key] = $aspek !== '' ? $aspek : null;
                }
            }
        }

        if (empty($keys)) {
            throw new Exception('Master indikator untuk type ini belum diset.');
        }

        $keys = array_values(array_unique($keys));

        foreach ($keys as $k) {
            if (!array_key_exists($k, $aspectByKey)) {
                $aspectByKey[$k] = null;
            }
        }

        return [$keys, $aspectByKey];
    }
}
