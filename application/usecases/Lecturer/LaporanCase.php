<?php

namespace UseCases\Lecturer;

use Exception;
use Repositories\LecturerRepository;
use UseCases\AspekPenilaian;

class LaporanCase
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
        try {


            // --------- Ambil input ---------
            $submission_id = $this->CI->input->post('submission_id');
            $data          = $this->CI->input->post('nilai_laporan');    // [aspek_key => [student_id => nilai]]
            $presentasi    = $this->CI->input->post('nilai_presentasi'); // [aspek_key => [student_id => nilai]]
            $feedback      = $this->CI->input->post('feedback');

            if (empty($submission_id))                       throw new Exception('submission_id wajib dikirim.');
            if (!$data || !is_array($data))                  throw new Exception('Data  penilaian laporan tidak valid.');
            if (!$presentasi || !is_array($presentasi))      throw new Exception('Data penilaian presentasi tidak valid.');

            // --------- Source of truth aspek ---------
            $uc = new AspekPenilaian();
            $aspek_penilaian_laporan    = array_column($uc->laporan_aspek, 'key');
            $aspek_penilaian_presentasi = array_column($uc->presentasi_aspek, 'key');

            // --------- Validasi isi (wajib ada semua aspek & nilai 1–4) ---------
            $validateBlock = function (array $payload, array $allowedKeys, string $namaBlock) {
                // pastikan tiap aspek ada dan array
                foreach ($allowedKeys as $aspek) {
                    if (!isset($payload[$aspek]) || !is_array($payload[$aspek])) {
                        throw new Exception("Penilaian untuk aspek '$aspek' ($namaBlock) tidak ditemukan.");
                    }
                    foreach ($payload[$aspek] as $student_id => $nilai) {
                        if (!is_numeric($nilai) || $nilai < 1 || $nilai > 4) {
                            throw new Exception("Nilai untuk '$student_id' pada aspek '$aspek' ($namaBlock) harus 1–4.");
                        }
                    }
                }
            };
            $validateBlock($data,       $aspek_penilaian_laporan,    'laporan');
            $validateBlock($presentasi, $aspek_penilaian_presentasi, 'presentasi');

            // --------- Identitas session ---------
            $user_id = $this->CI->session->userdata('user_id');
            $role    = $this->CI->session->userdata('role');
            $nip     = $this->CI->session->userdata('nip');
            if (empty($role)) throw new Exception('Role must be provided.');
            if (empty($nip))  throw new Exception('NIP tidak ditemukan di session.');

            $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
            if (!$lecturer) throw new Exception('Data dosen (lecturer) tidak ditemukan.');

            // --------- Helper UPSERT ---------
            $upsertScores = function (string $table, array $payload) use ($submission_id, $role, $user_id, $lecturer) {
                foreach ($payload as $aspek => $students) {
                    foreach ($students as $student_id => $nilai) {

                        // cek apakah existing
                        $exists = $this->db->where([
                            'submission_id' => $submission_id,
                            'student_id'    => $student_id,
                            'indicator'     => $aspek,
                            'role'          => $role
                        ])->count_all_results($table);

                        $now = date('Y-m-d H:i:s');

                        if ($exists == 0) {
                            // INSERT
                            $this->db->insert($table, [
                                'submission_id' => $submission_id,
                                'student_id'    => $student_id,
                                'indicator'     => $aspek,
                                'score'         => $nilai,
                                'created_at'    => $now,
                                'created_by'    => (int)$lecturer->id,
                                'role'          => $role,
                                'lecture_id'    => (int)$lecturer->id,
                                // jika tabel punya kolom ini, boleh ikutkan:
                                // 'updated_at' => $now,
                                // 'updated_by' => $user_id,
                            ]);
                        } else {
                            // UPDATE
                            $this->db->where([
                                'submission_id' => $submission_id,
                                'student_id'    => $student_id,
                                'indicator'     => $aspek,
                                'role'          => $role
                            ])->update($table, [
                                'score'      => $nilai,
                                'updated_at' => $now,
                                'updated_by' => $user_id
                            ]);
                        }
                    }
                }
            };

            // --------- TRANSAKSI ---------
            $this->db->trans_begin();

            // upsert skor laporan & presentasi
            $upsertScores('report_score',       $data);
            $upsertScores('presentation_score', $presentasi);

            // update status submission + feedback
            $this->db->where('id', $submission_id)->update('submission', [
                'status'     => 'sudah dinilai',
                'feedback'   => $feedback,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $user_id
            ]);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Gagal menyimpan penilaian (transaksi dibatalkan).');
            }
            $this->db->trans_commit();
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            throw $th;
        }
    }

    public function get_penilaian_by_submisi_id($submission_id)
    {
        $nip = $this->CI->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        // Query untuk mengambil data dari tabel 'report_score'
        $this->db->select('student_id, indicator, score, role, created_at, created_by');
        $this->db->from('report_score');
        $this->db->where('submission_id', $submission_id);
        $this->db->where('lecture_id', $lid);
        $query1 = $this->db->get();
        $report_scores = $query1->result_array();

        // Query untuk mengambil data dari tabel 'presentation_score'
        $this->db->select('student_id, indicator, score, role, created_at, created_by');
        $this->db->from('presentation_score');
        $this->db->where('submission_id', $submission_id);
        $query2 = $this->db->get();
        $presentation_scores = $query2->result_array();

        // Formatkan hasil dalam bentuk array yang diinginkan
        $scores = [
            'nilai_laporan' => $report_scores,
            'nilai_presentasi' => $presentation_scores
        ];

        return $scores;
    }
}
