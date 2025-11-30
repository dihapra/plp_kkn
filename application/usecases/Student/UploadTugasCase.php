<?php

namespace UseCases\Student;

use Exception;
use Repositories\StudentRepository;
use Repositories\SubmissionRepository;
use Throwable;

class UploadTugasCase
{

    public $CI;
    public $student_repo;
    public $submission_repo;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->student_repo = new StudentRepository();
        $this->submission_repo = new SubmissionRepository();
    }
    public function upload_laporan($type)
    {
        if (empty($_FILES['file']['name'])) {
            throw new Exception('File tidak ada');
        }
        $nim = $this->CI->session->userdata('nim');
        $student = $this->student_repo->get_by_key("nim", $nim);
        $data = [];
        $submission_id = $this->CI->input->post('submission_id'); // Ambil jika ada

        if (!$student) {
            throw new Exception('Mahasiswa tidak ditemukan.');
        }

        // Tentukan nama file berdasarkan NIM dan type
        $result = $this->get_file_type($type, $student);
        $file_type = $result['file_type'];
        $data = $result['data'];
        $file_path = $this->initiate_config($student, $file_type);
        try {
            // Cek apakah ada file dengan nama yang sama sebelumnya, jika ada hapus
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            if (!$this->CI->upload->do_upload('file')) {
                throw new Exception($this->CI->upload->display_errors());
            }

            $uploadData = $this->CI->upload->data();

            // Simpan data ke database

            // Jika terjadi error dalam penyimpanan database, file harus dihapus
            if ($submission_id) {
                // Jika revisi, update submission lama
                $data['status'] = 'sudah perbaikan';
                $data['updated_at'] = date('Y-m-d H:i:s');
                $data['file'] = 'uploads/laporan/' . $uploadData['file_name'];
                $this->submission_repo->update_laporan($data, $submission_id);
            } else {
                $data = array_merge($data, [
                    'file' => 'uploads/laporan/' . $uploadData['file_name'],
                    'type' => $type,
                    'status' => 'sedang dinilai',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->CI->session->userdata('user_id'),
                ]);
                // Insert baru
                $this->submission_repo->upload_laporan($data);
            }
        } catch (Throwable $e) {
            // **Hapus file jika sudah terupload tetapi terjadi error dalam proses lainnya**
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            log_message('error', $e->getLine());
            throw new Exception($e->getMessage()); // Lempar error kembali
        }
    }


    private function get_file_type($type, $student)
    {
        $file_type = '';
        $data = [];

        switch ((int) $type) {
            case 1: // Laporan Kemajuan (Kelompok)
                $file_type = 'laporan_kemajuan';
                $data['group_id'] = $student->group_id;
                break;

            case 2: // Laporan Akhir (Kelompok)
                $file_type = 'laporan_akhir';
                $data['group_id'] = $student->group_id;
                break;

            case 3: // Modul Ajar (Individu)
                $file_type = 'modul_ajar';
                $data['student_id'] = $student->id;
                break;

            case 4: // Bahan Ajar (Individu)
                $file_type = 'bahan_ajar';
                $data['student_id'] = $student->id;
                break;

            case 5: // Modul Projek (Individu)
                $file_type = 'modul_projek';
                $data['student_id'] = $student->id;
                break;

            default:
                throw new Exception('Tipe laporan tidak valid.');
        }

        return [
            'data' => $data,
            'file_type' => $file_type
        ];
    }
    private function initiate_config($student, $fileType)
    {
        $newFileName = $student->nim . '_' . $fileType . '.pdf';
        $folder_path = './uploads/laporan/';
        $file_path = $folder_path . $newFileName; // Path lengkap file yang akan di-upload

        if (!is_dir($folder_path)) {
            mkdir($folder_path, 0777, true);
        }

        // Konfigurasi upload
        $config['upload_path'] = $folder_path;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 11048;
        $config['file_name'] = $newFileName; // Set nama file baru

        $this->CI->load->library('upload', $config);
        return $file_path;
    }
}
