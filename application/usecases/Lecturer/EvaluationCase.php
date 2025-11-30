<?php

namespace UseCases\Lecturer;

use Exception;
use Repositories\LecturerRepository;
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
        $nip  = $this->CI->session->userdata('nip');
        $role = $this->CI->session->userdata('role'); // opsional, tidak dipakai di query
        if (empty($nip))  throw new Exception('NIP tidak ditemukan di session.');

        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) throw new Exception('Dosen tidak ditemukan.');
        $lid = (int) $lecturer->id;
        $table = $this->get_tabel($type);
        return $this->db->where('student_id', $student_id)->where('lecture_id', $lid)->get($table)->result();
    }
    public function save($type)
    {
        try {
            // --------- Ambil input (per mahasiswa) ---------
            $data       = $this->CI->input->post('nilai');       // ['indikator_key' => 1..4]
            $student_id = (int) $this->CI->input->post('student_id');

            if (!$student_id) {
                throw new Exception('student_id wajib diisi.');
            }
            if (!$data || !is_array($data)) {
                throw new Exception('Data penilaian tidak valid.');
            }
            $nip  = $this->CI->session->userdata('nip');
            $role = $this->CI->session->userdata('role'); // opsional, tidak dipakai di query
            if (empty($nip))  throw new Exception('NIP tidak ditemukan di session.');

            $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
            if (!$lecturer) throw new Exception('Dosen tidak ditemukan.');
            $lid = (int) $lecturer->id;
            $owned = $this->db->select('1')->from('student')
                ->where(['id' => $student_id, 'lecture_id' => $lid])->get()->row();
            if (!$owned) throw new Exception('Mahasiswa bukan di bawah bimbingan Anda.');

            // --------- Ambil master indikator & tabel target ---------
            [$allowedKeys, $aspectByKey] = $this->get_aspek_keys_map($type);
            $table = $this->get_tabel($type); // 1: assist_cocullicular, 2: assist_extracurricular, 3: student_attitude
            // --------- Validasi nilai ---------
            foreach ($data as $indicatorKey => $score) {
                if (!in_array($indicatorKey, $allowedKeys, true)) {
                    throw new Exception("Indikator '$indicatorKey' tidak dikenal untuk type {$type}.");
                }
                if (!is_numeric($score) || $score < 1 || $score > 4) {
                    throw new Exception("Nilai indikator '$indicatorKey' harus 1–4.");
                }
            }

            // --------- UPSERT per indikator ---------
            $now = date('Y-m-d H:i:s');
            $this->db->trans_begin();

            foreach ($data as $indicatorKey => $score) {
                $aspekLabel = $aspectByKey[$indicatorKey] ?? null;

                // cek eksistensi
                $exists = $this->db->from($table)
                    ->where('student_id', $student_id)
                    ->where('indicator',  $indicatorKey)
                    ->where('lecture_id', $lid)
                    ->count_all_results();
                if ($exists) {
                    // UPDATE
                    $this->db->where([
                        'student_id' => $student_id,
                        'indicator'  => $indicatorKey,
                        'lecture_id' => $lid,
                    ])->update($table, [
                        'score'      => (int)$score,
                        'aspek'      => $aspekLabel, // hapus jika kolom tidak ada
                        'lecture_id' => $lid,        // hapus jika kolom tidak ada
                        'role'       => 'lecturer',
                        'updated_at' => $now,
                    ]);
                } else {
                    // INSERT
                    $this->db->insert($table, [
                        'student_id' => $student_id,
                        'indicator'  => $indicatorKey,
                        'aspek'      => $aspekLabel, // hapus jika kolom tidak ada
                        'score'      => (int)$score,
                        'lecture_id' => $lid,
                        'teacher_id' => null,       // hapus jika kolom tidak ada
                        'role'       => 'lecturer',
                        'created_at' => $now,
                    ]);
                }
            }

            if (!$this->db->trans_status()) {
                $this->db->trans_rollback();
                throw new Exception('Gagal menyimpan penilaian.');
            }
            $this->db->trans_commit();

            response_json('Penilaian tersimpan.');
        } catch (\Throwable $th) {
            if ($this->db->trans_status()) {
                $this->db->trans_rollback();
            }
            throw $th;
        }
    }

    /**
     * Ambil daftar indikator (keys) + map key -> aspek (nama kelompok),
     * supaya kolom 'aspek' bisa diisi otomatis.
     */
    private function get_aspek_keys_map($type)
    {
        $uc = new AspekPenilaian();

        // Tentukan sumber dan schema
        $items  = [];
        $schema = 'flat';

        switch ((int)$type) {
            case 1: // Intrakurikuler -> flat
                $items  = $uc->penilaian_asistensi_intrakurikuler ?? [];
                $schema = 'flat';
                break;

            case 2: // Ekstrakurikuler/Kokurikuler -> grouped
                $items  = $uc->penilaian_asistensi_kokurikuler
                    ?? $uc->penilaian_asistensi_ekstrakurikuler
                    ?? [];
                $schema = 'grouped';
                break;

            case 3: // Sikap -> grouped
                $items  = $uc->penilaian_sikap ?? [];
                $schema = 'grouped';
                break;

            case 4: // Analisis Mahasiswa -> grouped
                $items  = $uc->penilaian_analisis_mahasiswa ?? [];
                $schema = 'grouped';
                break;

            default:
                throw new Exception('type tidak valid');
        }

        $keys        = [];
        $aspectByKey = [];

        if ($schema === 'flat') {
            // Contoh item: ['key'=>'pendahuluan_pembuka_dan_doa', 'label'=>'Pendahuluan – Pembuka & Doa', ...]
            foreach ((array)$items as $row) {
                if (empty($row['key'])) continue;

                $key   = (string)$row['key'];
                $label = (string)($row['label'] ?? '');

                $keys[] = $key;

                // Derive aspek dari prefix label sebelum tanda pisah (–, —, atau -)
                $aspek = null;
                if ($label !== '') {
                    // Pisah sekali saja di tanda dash (en/em/ascii)
                    $parts = preg_split('/\s*[–—-]\s*/u', $label, 2);
                    // Jika benar-benar terbelah (bukan tidak ada dash)
                    if (is_array($parts) && count($parts) === 2 && $parts[0] !== '') {
                        $aspek = trim($parts[0]);
                    }
                }
                $aspectByKey[$key] = $aspek; // bisa null jika tidak ada prefix
            }
        } else {
            // Contoh group: ['aspek'=>'Keterlibatan', 'indikator'=> [ ['key'=>'kehadiran', 'label'=>'Kehadiran', ...], ... ]]
            foreach ((array)$items as $g) {
                $aspek     = (string)($g['aspek'] ?? $g['aspect'] ?? '');
                $indikator = $g['indikator'] ?? $g['indicators'] ?? [];

                if (!is_array($indikator)) continue;

                foreach ($indikator as $row) {
                    if (empty($row['key'])) continue;

                    $key = (string)$row['key'];
                    $keys[] = $key;
                    $aspectByKey[$key] = $aspek !== '' ? $aspek : null;
                }
            }
        }

        if (empty($keys)) {
            throw new Exception('Master indikator untuk type ini belum diset.');
        }

        // Dedup keys dengan mempertahankan urutan pertama kali muncul
        $keys = array_values(array_unique($keys));

        // Pastikan semua key ada entry aspek-nya (minimal null)
        foreach ($keys as $k) {
            if (!array_key_exists($k, $aspectByKey)) {
                $aspectByKey[$k] = null;
            }
        }

        return [$keys, $aspectByKey];
    }


    /**
     * Tabel target berdasarkan jenis penilaian.
     * Perhatikan ejaan tabel: sebelumnya kamu pakai "assist_cocullicular".
     */
    private function get_tabel($type)
    {
        switch ((int)$type) {
            case 1:
                return 'assist_intracurricular';   // Intrakurikuler
            case 2:
                return 'assist_extracurricular'; // Ekstrakurikuler
            case 3:
                return 'student_attitude';      // Sikap
            case 4:
                return 'analisis_score';        // Analisis Mahasiswa
            default:
                throw new Exception('type tidak valid');
        }
    }
}
