<?php

namespace UseCases\Auth;

use Exception;
use Throwable;

class RegisterPrincipalCase
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
            $this->validate_input();

            $nik = $this->CI->input->post('nik', TRUE);
            $folder_path = "./uploads/principals/" . $nik;
            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0777, true);
            }

            $data = [
                'name' => $this->CI->input->post('name', TRUE),
                'nik' => $nik,
                'email' => $this->CI->input->post('email', TRUE),
                'phone' => $this->CI->input->post('phone', TRUE),
                'status' => $this->CI->input->post('status', TRUE), // Assuming 'status' is marital status from the form
                'school_id' => $this->CI->input->post('school_id', TRUE),
                'bank' => $this->CI->input->post('bank', TRUE),
                'account_number' => $this->CI->input->post('account_number', TRUE),
                'account_name' => $this->CI->input->post('account_name', TRUE),
                'status_data' => 'unverified',
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Handle file uploads
            $data['identification_card'] = $this->upload_file('identification_card', $folder_path, 'ktp_' . $nik);
            $data['book'] = $this->upload_file('book', $folder_path, 'rekening_' . $nik);

            $this->db->insert('principal', $data);
        } catch (Throwable $e) {
            // In case of error, re-throw the exception to be caught by the controller
            throw $e;
        }
    }

    private function validate_input()
    {
        $this->CI->load->library('form_validation');
        $rules = [
            ['field' => 'name', 'label' => 'Nama Lengkap', 'rules' => 'required'],
            ['field' => 'nik', 'label' => 'NIK', 'rules' => 'required|numeric'],
            ['field' => 'phone', 'label' => 'Phone', 'rules' => 'required|numeric'],
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'],
            ['field' => 'school_id', 'label' => 'Sekolah', 'rules' => 'required'],
            ['field' => 'bank', 'label' => 'Bank', 'rules' => 'required'],
            ['field' => 'account_number', 'label' => 'Nomor Rekening', 'rules' => 'required|numeric'],
            ['field' => 'account_name', 'label' => 'Nama di Rekening', 'rules' => 'required'],
        ];
        if ($_FILES['identification_card']['size'] > 2 * 1024 * 1024) { // 2MB
            throw new Exception("Ukuran file KTP maksimal 2MB");
        }
        if ($_FILES['book']['size'] > 2 * 1024 * 1024) { // 2MB
            throw new Exception("Ukuran file Buku rekening maksimal 2MB");
        }
        $this->CI->form_validation->set_rules($rules);

        if ($this->CI->form_validation->run() == FALSE) {
            throw new Exception(validation_errors());
        }
    }

    private function upload_file($file_input_name, $upload_path, $file_name)
    {
        if (empty($_FILES[$file_input_name]['name'])) {
            // Allow empty files for now, validation can be stricter if needed
            return null;
        }

        $config = [
            'upload_path' => $upload_path,
            'allowed_types' => 'jpg|jpeg|png|pdf',
            'max_size' => 2048, // 2MB
            'file_name' => $file_name,
            'overwrite' => TRUE
        ];

        $this->CI->load->library('upload', $config);
        $this->CI->upload->initialize($config); // Re-initialize for each upload

        if (!$this->CI->upload->do_upload($file_input_name)) {
            $error = $this->CI->upload->display_errors('', '');
            throw new Exception("Upload file '" . $file_input_name . "' gagal: " . $error);
        }

        $upload_data = $this->CI->upload->data();
        return $upload_path . '/' . $upload_data['file_name'];
    }
}
