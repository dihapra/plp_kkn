<?php

namespace Imports;

use Exception;
use Throwable;

class ImportProdi
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
            'file_name' => 'import_prodi_' . time(),
        ];
    }

    public function import_prodi(): void
    {
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
                $fakultas = isset($row['B']) ? trim((string) $row['B']) : '';

                if ($nama === '' || $fakultas === '') {
                    continue;
                }

                $rows[] = [
                    'nama' => $nama,
                    'fakultas' => $fakultas,
                ];
            }

            if (empty($rows)) {
                throw new Exception('Data prodi kosong atau format tidak sesuai.');
            }

            $this->db->trans_begin();

            $pairs = [];
            foreach ($rows as $row) {
                $key = strtolower($row['nama']) . '|' . strtolower($row['fakultas']);
                $pairs[$key] = $row;
            }

            $existing = [];
            if (!empty($pairs)) {
                $names = array_unique(array_map(static function ($row) {
                    return $row['nama'];
                }, $pairs));

                $query = $this->db->select('nama, fakultas')
                    ->from('prodi')
                    ->where_in('nama', $names)
                    ->get();

                foreach ($query->result() as $row) {
                    $key = strtolower($row->nama) . '|' . strtolower($row->fakultas);
                    $existing[$key] = true;
                }
            }

            $insertData = [];
            foreach ($pairs as $key => $row) {
                if (isset($existing[$key])) {
                    continue;
                }
                $insertData[] = [
                    'nama' => $row['nama'],
                    'fakultas' => $row['fakultas'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }

            if (!empty($insertData)) {
                $this->db->insert_batch('prodi', $insertData);
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
