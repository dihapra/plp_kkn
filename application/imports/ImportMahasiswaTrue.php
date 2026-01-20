<?php

namespace Imports;

use Exception;
use UseCases\Superadmin\MahasiswaCase;
use Throwable;

class ImportMahasiswaTrue
{
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    protected function get_upload_config(): array
    {
        return [
            'upload_path' => './uploads/admin/import',
            'allowed_types' => 'xlsx',
            'max_size' => 4096, // Maksimal 4 MB
            'file_name' => 'import_mahasiswa_true_' . time(),
        ];
    }

    public function import_mahasiswa_true(string $programKode, bool $sendVerificationEmail = false): void
    {
        $programKode = trim(strtolower($programKode));
        if ($programKode === '') {
            throw new Exception('Kode program wajib diisi.');
        }

        $this->CI->load->library('upload');
        $config = $this->get_upload_config();
        $this->CI->upload->initialize($config);

        $uploadFolder = $this->create_folder();

        if (!$this->CI->upload->do_upload('importFile')) {
            $error = $this->CI->upload->display_errors();
            throw new Exception('Upload gagal: ' . strip_tags($error));
        }

        $fileData = $this->CI->upload->data();
        $filePath = $uploadFolder . $fileData['file_name'];

        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $rows = [];

            foreach ($sheetData as $index => $row) {
                if ($index === 1) {
                    continue;
                }
                // dd($row);
                $nama = isset($row['A']) ? trim((string) $row['A']) : '';
                $nim = isset($row['B']) ? trim((string) $row['B']) : '';
                $prodiName = isset($row['C']) ? trim((string) $row['C']) : '';
                $fakultas = isset($row['D']) ? trim((string) $row['D']) : '';

                if ($nama === '' || $nim === '') {
                    continue;
                }

                $rows[] = [
                    'nama' => $nama,
                    'nim' => $nim,
                    'kode_program' => $programKode,
                    'prodi_name' => $prodiName,
                    'fakultas' => $fakultas,
                ];
            }

            if (empty($rows)) {
                throw new Exception('Data mahasiswa kosong atau format tidak sesuai.');
            }

            $this->db->trans_begin();

            $programRow = $this->db->select('id, kode, active, tahun_ajaran')
                ->from('program')
                ->where('kode', $programKode)
                ->where('active', 1)
                ->order_by('tahun_ajaran', 'DESC')
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get()
                ->row();

            if (!$programRow) {
                throw new Exception('Program aktif untuk kode tersebut tidak ditemukan.');
            }

            $programId = (int) $programRow->id;

            $prodiNames = array_unique(array_filter(array_map(static function ($row) {
                return $row['prodi_name'];
            }, $rows)));
            // dd($prodiNames, $rows);

            $prodiMap = [];
            if (!empty($prodiNames)) {
                $prodiQuery = $this->db->select('id, nama, fakultas')
                    ->from('prodi')
                    ->get();

                foreach ($prodiQuery->result() as $row) {
                    $nameKey = $this->normalizeKey($row->nama);
                    if (!isset($prodiMap[$nameKey])) {
                        $prodiMap[$nameKey] = (int) $row->id;
                    }
                }
            }

            $nims = array_unique(array_map(static function ($row) {
                return $row['nim'];
            }, $rows));

            $existingNims = [];
            if (!empty($nims)) {
                foreach (array_chunk($nims, 10) as $nimChunk) {
                    // dd(array_chunk($nims, 200));
                    $nimQuery = $this->db->select('nim')
                        ->from('mahasiswa_true')
                        ->where_in('nim', $nimChunk)
                        ->get();
                    foreach ($nimQuery->result() as $row) {
                        $existingNims[$row->nim] = true;
                    }
                }
            }

            $now = date('Y-m-d H:i:s');
            $userId = $this->CI->session->userdata('id_user');
            $insertData = [];
            $verifyTargets = [];
            $updateData = [];
            $missingProdi = [];
            foreach ($rows as $row) {
                $prodiId = null;
                // dd($prodiMap);
                if ($row['prodi_name'] !== '') {
                    $nameKey = $this->normalizeKey($row['prodi_name']);
                    $prodiId = $prodiMap[$nameKey] ?? null;
                }

                if (!isset($verifyTargets[$row['nim']])) {
                    $verifyTargets[$row['nim']] = $prodiId;
                }

                $basePayload = [
                    'nim' => $row['nim'],
                    'nama' => $row['nama'],
                    'id_prodi' => $prodiId,
                    'id_program' => $programId,
                    'updated_at' => $now,
                ];

                if (isset($existingNims[$row['nim']])) {
                    $updateData[] = $basePayload;
                    continue;
                }

                $insertData[] = $basePayload + [
                    'created_at' => $now,
                    'created_by' => $userId ? (int) $userId : null,
                ];

                if ($prodiId === null) {
                    $missingProdi[$row['nim']] = $row['prodi_name'];
                }
            }

            if (empty($insertData) && empty($updateData) && empty($verifyTargets)) {
                throw new Exception('Tidak ada data valid untuk diimpor.');
            }

            if (!empty($missingProdi)) {
                dd($missingProdi);
            }
            if (!empty($insertData)) {
                $this->db->insert_batch('mahasiswa_true', $insertData);
            }
            if (!empty($updateData)) {
                $this->db->update_batch('mahasiswa_true', $updateData, 'nim');
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Gagal menyimpan data ke database.');
            }

            $this->db->trans_commit();
            $this->autoVerifyMahasiswaByNimAndProdi($verifyTargets, $sendVerificationEmail);
            unlink($filePath);
        } catch (Throwable $e) {
            $this->db->trans_rollback();
            if (is_file($filePath)) {
                unlink($filePath);
            }
            throw $e;
        }
    }

    private function create_folder(): string
    {
        $uploadFolder = './uploads/admin/import/';
        if (!is_dir($uploadFolder)) {
            if (!mkdir($uploadFolder, 0777, true)) {
                throw new Exception('Gagal membuat folder upload. Periksa permission server.');
            }
        }
        return $uploadFolder;
    }

    private function normalizeKey($value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }
        $value = preg_replace('/\s+/', ' ', $value);
        return strtolower($value);
    }

    private function autoVerifyMahasiswaByNimAndProdi(array $targets, bool $sendVerificationEmail): void
    {
        $nims = array_keys(array_filter($targets, static function ($nim) {
            return trim((string) $nim) !== '';
        }, ARRAY_FILTER_USE_KEY));
        if (empty($nims)) {
            return;
        }

        $mahasiswaCase = new MahasiswaCase();
        $chunkSize = 50;

        foreach (array_chunk($nims, $chunkSize) as $nimChunk) {
            $query = $this->db->select('
                    mahasiswa.id,
                    mahasiswa.nim,
                    mahasiswa.email,
                    mahasiswa.id_prodi,
                    pm.status
                ')
                ->from('mahasiswa')
                ->join(
                    'program_mahasiswa pm',
                    'pm.id = (SELECT MAX(pm2.id) FROM program_mahasiswa pm2 WHERE pm2.id_mahasiswa = mahasiswa.id)',
                    'left',
                    false
                )
                ->where_in('mahasiswa.nim', $nimChunk)
                ->get();

            if (!$query) {
                continue;
            }

            foreach ($query->result() as $row) {
                $status = strtolower((string) ($row->status ?? ''));
                if ($status === 'verified') {
                    continue;
                }
                if ($status !== '' && $status !== 'unverified') {
                    continue;
                }
                $expectedProdiId = $targets[$row->nim] ?? null;
                if ($expectedProdiId === null) {
                    log_message('error', 'Auto verifikasi mahasiswa dilewati: prodi tidak ditemukan untuk NIM ' . $row->nim);
                    continue;
                }
                if ((int) $row->id_prodi !== (int) $expectedProdiId) {
                    log_message(
                        'error',
                        'Auto verifikasi mahasiswa dilewati: prodi tidak cocok untuk NIM ' . $row->nim
                    );
                    continue;
                }
                if (empty($row->email)) {
                    log_message('error', 'Auto verifikasi mahasiswa gagal: email kosong untuk NIM ' . $row->nim);
                    continue;
                }

                try {
                    $mahasiswaCase->updateVerificationStatus((int) $row->id, 'verified', $sendVerificationEmail);
                } catch (Throwable $e) {
                    log_message(
                        'error',
                        'Auto verifikasi mahasiswa gagal untuk NIM ' . $row->nim . ': ' . $e->getMessage()
                    );
                }
            }
        }
    }
}
