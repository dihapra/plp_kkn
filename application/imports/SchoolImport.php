<?php

namespace Imports;

use Exception;
use Throwable;

class SchoolImport
{
    protected $CI;
    protected $db;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
    protected function get_upload_config()
    {
        return [
            'upload_path' => './uploads/admin/import',
            'allowed_types' => 'csv',
            'max_size' => 2048, // Maksimal 2 MB
            'file_name' => 'data_import_' . time(),
        ];
    }
    public function import_school()
    {
        $this->CI->load->library('upload');
        $config = $this->get_upload_config();
        $this->CI->upload->initialize($config);

        // Validasi file upload
        $uploadFolder = $this->create_folder();

        // Validasi file upload
        if (!$this->CI->upload->do_upload('importFile')) {
            $error = $this->CI->upload->display_errors();
            throw new Exception('Upload gagal: ' . strip_tags($error));
        }

        $fileData = $this->CI->upload->data();
        $filePath = $uploadFolder . $fileData['file_name'];
        try {
            // Load file menggunakan PhpSpreadsheet
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');
            $spreadsheet = $reader->load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $school_data = [];
            // $user_data = [];

            foreach ($sheetData as $index => $row) {
                // Skip header (baris pertama)
                if ($index === 1) {
                    continue;
                }

                // Data untuk tabel lecturer
                $school_data[] = [
                    'name' => $row['A'],
                    // 'nip' => $row['C'],
                    // 'email' => $row['D'],
                    // 'phone' => $row['E'],
                    // 'prodi' => $row['F'],
                    // 'fakultas' => $row['G'],
                ];
            }
            $this->db->trans_begin();

            // Kumpulkan daftar nama sekolah dari data import
            $school_names = array_column($school_data, 'name');

            // Query untuk mendapatkan sekolah yang sudah ada berdasarkan nama
            $existing_names = [];
            if (!empty($school_names)) {
                $this->db->select('name');
                $this->db->from('school');
                $this->db->where_in('name', $school_names);
                $query = $this->db->get();
                foreach ($query->result() as $row) {
                    $existing_names[] = $row->name;
                }
            }

            // Filter data baru, hanya masukkan data yang nama sekolahnya belum ada
            $filtered_school_data = [];
            foreach ($school_data as $data) {
                if (!in_array($data['name'], $existing_names)) {
                    $filtered_school_data[] = $data;
                }
            }

            // Lakukan insert batch hanya jika terdapat data baru
            if (!empty($filtered_school_data)) {
                $this->db->insert_batch('school', $filtered_school_data);
            }

            // Cek status transaksi
            if ($this->db->trans_status() === FALSE) {
                // Rollback transaksi jika terjadi error
                $this->db->trans_rollback();
                throw new Exception('Gagal menyimpan data ke database.');
            } else {
                // Commit transaksi jika berhasil
                $this->db->trans_commit();
            }
            unlink($filePath);
        } catch (Throwable $e) {
            $this->db->trans_rollback();
            // Hapus file jika ada error
            unlink($filePath);
            throw $e;
        }
    }
    private function create_folder()
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
