<?php

namespace UseCases\Lecturer;

use Exception;
use Repositories\LecturerRepository;
use UseCases\AspekPenilaian;

class EvaluationPageCase
{

    public $CI;
    public $db;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
    public function execute()
    {
        $nip = $this->CI->session->userdata('nip');
        if (empty($nip)) {
            throw new Exception('Session tidak valid: NIP tidak ditemukan.');
        }

        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception('Dosen tidak ditemukan.');
        }
        $lid = (int) $lecturer->id;

        // --- Ambil list mahasiswa + flag "sudah dinilai" per jenis penilaian
        //     Pakai subquery COUNT(*) agar tidak duplikat walau skor per indikator banyak baris.
        return $this->db->select("
                s.id   AS student_id,
                s.name AS student_name,

                COALESCE(ex.cnt, 0) AS has_extracurricular,  -- assist_extracurricular
                COALESCE(co.cnt, 0) AS has_cocurricular,     
                COALESCE(sa.cnt, 0) AS has_attitude,         -- student_attitude
                COALESCE(an.cnt, 0) AS has_analisis          -- analisis_score
            ", false)
            ->from('student s')

            // Ekstrakurikuler
            ->join("
                (
                    SELECT student_id, COUNT(*) AS cnt
                    FROM assist_extracurricular
                    WHERE lecture_id = " . $this->db->escape($lid) . "
                    GROUP BY student_id
                ) ex
            ", 'ex.student_id = s.id', 'left')

            // Co/Intrakurikuler
            ->join("
                (
                    SELECT student_id, COUNT(*) AS cnt
                    FROM assist_intracurricular
                    WHERE lecture_id = " . $this->db->escape($lid) . "
                    GROUP BY student_id
                ) co
            ", 'co.student_id = s.id', 'left')

            // Sikap
            ->join("
                (
                    SELECT student_id, COUNT(*) AS cnt
                    FROM student_attitude
                    WHERE lecture_id = " . $this->db->escape($lid) . "
                    GROUP BY student_id
                ) sa
            ", 'sa.student_id = s.id', 'left')

            // Analisis
            ->join("(
                    SELECT student_id, COUNT(*) AS cnt
                    FROM analisis_score
                    WHERE lecture_id = " . $this->db->escape($lid) . "
                    GROUP BY student_id
                ) an", 'an.student_id = s.id', 'left')

            // Hanya mahasiswa bimbingan dosen ini
            ->where('s.lecture_id', $lid)
            ->order_by('s.name', 'asc')
            ->get()
            ->result();
    }
}
