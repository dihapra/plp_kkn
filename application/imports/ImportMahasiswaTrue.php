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

    public function import_mahasiswa_true(string $programKode): void
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

                $nama = isset($row['A']) ? trim((string) $row['A']) : '';
                $nim = isset($row['B']) ? trim((string) $row['B']) : '';
                $email = isset($row['C']) ? trim((string) $row['C']) : '';
                $noHp = isset($row['D']) ? trim((string) $row['D']) : '';
                $prodiName = isset($row['E']) ? trim((string) $row['E']) : '';
                $fakultas = isset($row['F']) ? trim((string) $row['F']) : '';

                if ($nama === '' || $nim === '') {
                    continue;
                }

                $rows[] = [
                    'nama' => $nama,
                    'nim' => $nim,
                    'email' => $email,
                    'no_hp' => $noHp,
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

            $prodiMap = [];
            if (!empty($prodiNames)) {
                $prodiQuery = $this->db->select('id, nama, fakultas')
                    ->from('prodi')
                    ->where_in('nama', $prodiNames)
                    ->get();

                foreach ($prodiQuery->result() as $row) {
                    $nameKey = strtolower($row->nama);
                    $facultyKey = strtolower((string) $row->fakultas);
                    $prodiMap[$nameKey . '|' . $facultyKey] = (int) $row->id;
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
                $nimQuery = $this->db->select('nim')
                    ->from('mahasiswa_true')
                    ->where_in('nim', $nims)
                    ->get();
                foreach ($nimQuery->result() as $row) {
                    $existingNims[$row->nim] = true;
                }
            }

            $now = date('Y-m-d H:i:s');
            $userId = $this->CI->session->userdata('id_user');
            $insertData = [];
            $verifyNims = [];

            foreach ($rows as $row) {
                $verifyNims[$row['nim']] = true;

                if (isset($existingNims[$row['nim']])) {
                    continue;
                }

                $prodiId = null;
                if ($row['prodi_name'] !== '') {
                    $key = strtolower($row['prodi_name']) . '|' . strtolower($row['fakultas']);
                    $prodiId = $prodiMap[$key] ?? $prodiMap[strtolower($row['prodi_name'])] ?? null;
                }

                $insertData[] = [
                    'nama' => $row['nama'],
                    'nim' => $row['nim'],
                    'email' => $row['email'] !== '' ? $row['email'] : null,
                    'no_hp' => $row['no_hp'] !== '' ? $row['no_hp'] : null,
                    'id_prodi' => $prodiId,
                    'id_program' => $programId,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'created_by' => $userId ? (int) $userId : null,
                ];
            }

            if (empty($insertData) && empty($verifyNims)) {
                throw new Exception('Tidak ada data valid untuk diimpor.');
            }

            if (!empty($insertData)) {
                $this->db->insert_batch('mahasiswa_true', $insertData);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Gagal menyimpan data ke database.');
            }

            $this->db->trans_commit();
            $this->autoVerifyMahasiswaByNim(array_keys($verifyNims));
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

    private function autoVerifyMahasiswaByNim(array $nims): void
    {
        $nims = array_values(array_unique(array_filter(array_map('trim', $nims))));
        if (empty($nims)) {
            return;
        }

        $query = $this->db->select('
                mahasiswa.id,
                mahasiswa.nim,
                mahasiswa.email,
                pm.status
            ')
            ->from('mahasiswa')
            ->join(
                'program_mahasiswa pm',
                'pm.id = (SELECT MAX(pm2.id) FROM program_mahasiswa pm2 WHERE pm2.id_mahasiswa = mahasiswa.id)',
                'left',
                false
            )
            ->where_in('mahasiswa.nim', $nims)
            ->get();

        if (!$query) {
            return;
        }

        $mahasiswaCase = new MahasiswaCase();

        foreach ($query->result() as $row) {
            $status = strtolower((string) ($row->status ?? ''));
            if ($status === 'verified') {
                continue;
            }
            if ($status !== '' && $status !== 'unverified') {
                continue;
            }
            if (empty($row->email)) {
                log_message('error', 'Auto verifikasi mahasiswa gagal: email kosong untuk NIM ' . $row->nim);
                continue;
            }

            try {
                $mahasiswaCase->updateVerificationStatus((int) $row->id, 'verified');
            } catch (Throwable $e) {
                log_message(
                    'error',
                    'Auto verifikasi mahasiswa gagal untuk NIM ' . $row->nim . ': ' . $e->getMessage()
                );
            }
        }
    }
}
