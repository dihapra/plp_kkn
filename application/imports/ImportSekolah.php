<?php

namespace Imports;

use Exception;
use Throwable;

class ImportSekolah
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
            'file_name' => 'import_sekolah_' . time(),
        ];
    }

    public function import_sekolah(string $programKode): void
    {
        $programKode = trim(strtolower($programKode));
        if ($programKode === '') {
            throw new Exception('Kode program wajib diisi.');
        }

        $program = $this->db->select('id')
            ->from('program')
            ->where('kode', $programKode)
            ->where('active', 1)
            ->order_by('tahun_ajaran', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if (!$program) {
            throw new Exception('Program aktif untuk kode tersebut tidak ditemukan.');
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
                $alamat = isset($row['B']) ? trim((string) $row['B']) : '';

                if ($nama === '') {
                    continue;
                }

                $rows[] = [
                    'nama' => $nama,
                    'alamat' => $alamat !== '' ? $alamat : null,
                ];
            }

            if (empty($rows)) {
                throw new Exception('Data sekolah kosong atau format tidak sesuai.');
            }

            $this->db->trans_begin();

            $names = array_unique(array_map(static function ($row) {
                return $row['nama'];
            }, $rows));

            $existing = [];
            if (!empty($names)) {
                $query = $this->db->select('id, nama')
                    ->from('sekolah')
                    ->where_in('nama', $names)
                    ->get();
                foreach ($query->result() as $row) {
                    $existing[$row->nama] = (int) $row->id;
                }
            }

            $insertData = [];
            foreach ($rows as $row) {
                if (isset($existing[$row['nama']])) {
                    continue;
                }
                $insertData[$row['nama']] = [
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }

            if (!empty($insertData)) {
                $this->db->insert_batch('sekolah', array_values($insertData));
            }

            $allSchools = [];
            if (!empty($names)) {
                $query = $this->db->select('id, nama')
                    ->from('sekolah')
                    ->where_in('nama', $names)
                    ->get();
                foreach ($query->result() as $row) {
                    $allSchools[] = (int) $row->id;
                }
            }

            if (!empty($allSchools)) {
                $existingRelations = [];
                $relQuery = $this->db->select('id_sekolah')
                    ->from('program_sekolah')
                    ->where('id_program', (int) $program->id)
                    ->where_in('id_sekolah', $allSchools)
                    ->get();
                foreach ($relQuery->result() as $row) {
                    $existingRelations[(int) $row->id_sekolah] = true;
                }

                $relationData = [];
                foreach ($allSchools as $schoolId) {
                    if (isset($existingRelations[$schoolId])) {
                        continue;
                    }
                    $relationData[] = [
                        'id_program' => (int) $program->id,
                        'id_sekolah' => (int) $schoolId,
                        'valid_from' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                }

                if (!empty($relationData)) {
                    $this->db->insert_batch('program_sekolah', $relationData);
                }
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Gagal menyimpan data ke database.');
            }

            $this->db->trans_commit();
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
}
