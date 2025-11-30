<?php

namespace UseCases\Lecturer;

use Exception;

class FinalScoreCase
{
    public $CI;
    public $db;

    // Bobot nilai sesuai PRD update
    private const BOBOT = [
        'kehadiran'            => 0.05,
        'analisis_mahasiswa'   => 0.10,
        'laporan_kemajuan'     => 0.05,
        'presentasi_kemajuan'  => 0.05,
        'intrakurikuler_pamong' => 0.10,
        'intrakurikuler_dpl'   => 0.10,
        'ekstrakurikuler'      => 0.10,
        'laporan_akhir'        => 0.10,
        'presentasi_akhir'     => 0.10,
        'modul_ajar'           => 0.10,
        'bahan_ajar'           => 0.10,
        'modul_proyek'         => 0.05,
    ];
    private const BOBOT_SIKAP = [
        'guru'  => 0.60,
        'dosen' => 0.40,
    ];

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    /**
     * Mengambil dan menghitung semua komponen nilai akhir untuk SEMUA mahasiswa
     * yang diampu oleh dosen yang sedang login.
     *
     * @return array
     * @throws Exception
     */
    public function getAllStudentScores()
    {
        // 1. Get Lecturer and their students
        $nip = $this->CI->session->userdata('nip');
        if (empty($nip)) {
            throw new Exception('NIP tidak ditemukan di session.');
        }
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception('Dosen tidak ditemukan.');
        }
        $students = $this->db->where('lecture_id', $lecturer->id)->get('student')->result();

        // Initialize result arrays
        $penilaian_dosen = [];
        $penilaian_guru = [];
        $penilaian_total = [];

        // 2. Loop through each student to calculate their scores
        foreach ($students as $student) {
            $student_id = $student->id;
            $sikap_dosen = $this->_getStudentAttitudeScore($student_id, 'lecturer'); // 0–100
            $sikap_guru  = $this->_getStudentAttitudeScore($student_id, 'teacher');  // 0–100
            $sikap_final = round(
                ($sikap_guru  * self::BOBOT_SIKAP['guru']) +
                    ($sikap_dosen * self::BOBOT_SIKAP['dosen']),
                2
            );
            // --- Get all individual score components ---
            $scores = [
                'kehadiran'             => $this->_getAttendanceScore($student_id),
                'analisis_mahasiswa'    => $this->_getAnalisisScore($student_id),
                'laporan_kemajuan'      => $this->_getSubmissionScore($student_id, 1, 'report_score', $student->group_id),
                'presentasi_kemajuan'   => $this->_getSubmissionScore($student_id, 1, 'presentation_score', $student->group_id),
                'intrakurikuler_dpl'    => $this->_getIntraCurricularScore($student_id, 'dosen'),
                'laporan_akhir'         => $this->_getSubmissionScore($student_id, 2, 'report_score', $student->group_id),
                'presentasi_akhir'      => $this->_getSubmissionScore($student_id, 2, 'presentation_score', $student->group_id),
                'modul_ajar'            => $this->_getSubmissionScore($student_id, 3, 'report_score'),
                'bahan_ajar'            => $this->_getSubmissionScore($student_id, 4, 'report_score'),
                'modul_proyek'          => $this->_getSubmissionScore($student_id, 5, 'report_score'),
                'intrakurikuler_pamong' => $this->_getIntraCurricularScore($student_id, 'guru'),
                'ekstrakurikuler'       => $this->_getExtraCurricularScore($student_id),
            ];

            // --- Calculate final score using the new BOBOT ---
            $nilai_akhir = 0;
            foreach (self::BOBOT as $key => $weight) {
                $nilai_akhir += ($scores[$key] ?? 0) * $weight;
            }
            // Convert score from 1-4 scale to 0-100 scale if necessary, assuming scores are already 0-100
            // For now, we assume scores are already in a 0-100 scale.
            // If they are 1-4, they should be converted, e.g., ($score / 4) * 100

            // --- Populate the arrays for the view ---
            $penilaian_dosen[] = [
                'nama' => $student->name,
                'nim' => $student->nim,
                'analisis_mahasiswa' => $scores['analisis_mahasiswa'],
                'intrakurikuler_dpl' => $scores['intrakurikuler_dpl'],
                'laporan_kemajuan' => $scores['laporan_kemajuan'],
                'presentasi_kemajuan' => $scores['presentasi_kemajuan'],
                'laporan_akhir' => $scores['laporan_akhir'],
                'presentasi_akhir' => $scores['presentasi_akhir'],
                'modul_ajar' => $scores['modul_ajar'],
                'bahan_ajar' => $scores['bahan_ajar'],
                'modul_proyek' => $scores['modul_proyek'],
                'penilaian_sikap'     => $sikap_dosen,
            ];

            $penilaian_guru[] = [
                'nama' => $student->name,
                'nim' => $student->nim,
                'intrakurikuler_pamong' => $scores['intrakurikuler_pamong'],
                'ekstrakurikuler' => $scores['ekstrakurikuler'],
                'penilaian_sikap'     => $sikap_guru,
            ];

            $penilaian_total[] = array_merge(
                [
                    'nama' => $student->name,
                    'nim' => $student->nim,
                    'total_nilai_akhir' => round($nilai_akhir, 2),
                    'penilaian_sikap' => $sikap_final
                ],
                $scores // Add all individual components
            );
        }
        // dd($penilaian_total, $this->_getSubmissionScore($student_id, 1, 'report_score'),);
        return [
            'penilaian_dosen' => $penilaian_dosen,
            'penilaian_guru' => $penilaian_guru,
            'penilaian_total' => $penilaian_total,
        ];
    }

    private function _getSubmissionScore($student_id, $submission_type, $score_table, $group_id = null)
    {
        $this->db->select('AVG(score) as average_score')
            ->from($score_table . ' as sc')
            ->join('submission as sub', 'sub.id = sc.submission_id');

        if ($group_id !== null) {
            // filter berdasarkan group_id
            $this->db->where('sub.group_id', $group_id);
        } else {
            // default: filter per mahasiswa
            $this->db->where('sub.student_id', $student_id);
        }

        $this->db->where('sub.type', $submission_type);

        $result = $this->db->get()->row();
        $score = $result ? (float)$result->average_score : 0;

        // convert 1-4 scale ke 0-100
        return ($score / 4) * 100;
    }

    private function _getStudentAttitudeScore($student_id, $role = null)
    {
        $this->db->select('AVG(score) as average_score')
            ->from('student_attitude')
            ->where('student_id', $student_id);

        // Sesuaikan filter role—pakai kolom yg tersedia di tabel
        if ($role === 'lecturer') {
            $this->db->where('lecture_id IS NOT NULL', null, false);
        } elseif ($role === 'teacher') {
            $this->db->where('teacher_id IS NOT NULL', null, false);
        }

        $result = $this->db->get()->row();
        $score4 = $result ? (float)$result->average_score : 0.0;

        // simpan sebagai 0–100 agar konsisten dengan komponen lain
        return ($score4 / 4) * 100;
    }
    private function _getAnalisisScore($student_id)
    {
        $query = $this->db->select('AVG(score) as average_score')
            ->from('analisis_score')
            ->where('student_id', $student_id)
            ->get();
        $result = $query->row();
        $score = $result ? (float)$result->average_score : 0;
        return ($score / 4) * 100; // Convert 1-4 scale to 0-100
    }

    private function _getIntraCurricularScore($student_id, $role)
    {
        $this->db->select('AVG(score) as average_score')->from('assist_intracurricular');
        $this->db->where('student_id', $student_id);
        if ($role === 'dosen') {
            $this->db->where('lecture_id IS NOT NULL');
        } else {
            $this->db->where('teacher_id IS NOT NULL');
        }
        $query = $this->db->get();
        $result = $query->row();
        $score = $result ? (float)$result->average_score : 0;
        return ($score / 4) * 100; // Convert 1-4 scale to 0-100
    }

    private function _getExtraCurricularScore($student_id)
    {
        $query = $this->db->select('AVG(score) as average_score')
            ->from('assist_extracurricular')
            ->where('student_id', $student_id)
            ->get();
        $result = $query->row();
        $score = $result ? (float)$result->average_score : 0;
        return ($score / 4) * 100; // Convert 1-4 scale to 0-100
    }

    private function _getAttendanceScore($student_id)
    {
        $total_meetings = 16;
        $hadir_count = $this->db->from('attendance')
            ->where('student_id', $student_id)
            ->where('status', 'Hadir')
            ->count_all_results();
        return ($total_meetings > 0) ? ($hadir_count / $total_meetings) * 100 : 0;
    }
}
