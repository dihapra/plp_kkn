<?php

namespace UseCases\Auth;

use Exception;
use Throwable;

class RegisterTeacherCase
{
    public $CI;
    public $db;
    protected $AuthRepository;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
        $this->CI->load->model('User_model');
        $this->AuthRepository = new \AuthRepository();
    }

    public function execute()
    {
        try {
            $this->db->trans_begin();
            $this->validate_input();

            $nik = $this->CI->input->post('nik', TRUE);
            $folder_path = "./uploads/teachers/" . $nik;
            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0777, true);
            }

            $data = $this->get_data_register($folder_path, $nik);

            $teacher_id = $this->AuthRepository->save_teacher($data['data']);
            $data['temp_data']['teacher_id'] = $teacher_id;
            $this->AuthRepository->save_temp_data($data['temp_data']);

            $this->db->trans_commit();
        } catch (Throwable $e) {
            $this->db->trans_rollback();
            throw $e;
        }
    }

    private function validate_input()
    {
        $this->CI->load->library('form_validation');
        $rules = [
            ['field' => 'name', 'label' => 'Nama', 'rules' => 'required|trim'],
            ['field' => 'status', 'label' => 'status', 'rules' => 'required|trim'],
            ['field' => 'nik', 'label' => 'NIK', 'rules' => 'required|numeric'],
            ['field' => 'phone', 'label' => 'phone', 'rules' => 'required|numeric'],
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'],
            ['field' => 'school_id', 'label' => 'Sekolah', 'rules' => 'required|integer'],
            ['field' => 'prodi_id', 'label' => 'Prodi', 'rules' => 'required|integer'],
            ['field' => 'ketua', 'label' => 'Ketua', 'rules' => 'required|integer'],
            ['field' => 'account_name', 'label' => 'Nama Rekening', 'rules' => 'required'],
            ['field' => 'account_number', 'label' => 'No Rekening', 'rules' => 'required|numeric'],
            ['field' => 'bank', 'label' => 'Bank', 'rules' => 'required'],
            ['field' => 'anggotaId[]', 'label' => 'Anggota', 'rules' => 'required'],
        ];
        $this->CI->form_validation->set_rules($rules);

        if ($this->CI->form_validation->run() == FALSE) {
            throw new Exception(validation_errors());
        }

        if (empty($_FILES['book']['name'])) {
            throw new Exception("file Buku rekening tidak ada");
        }
        if (empty($_FILES['identification_card']['name'])) {
            throw new Exception("file KTP tidak ada");
        }
        if ($_FILES['identification_card']['size'] > 2 * 1024 * 1024) { // 2MB
            throw new Exception("Ukuran file KTP maksimal 2MB");
        }
        if ($_FILES['book']['size'] > 2 * 1024 * 1024) { // 2MB
            throw new Exception("Ukuran file Buku rekening maksimal 2MB");
        }
    }

    private function get_data_register($folder_path, $nik)
    {
        $teacher_name =  $this->CI->input->post('name', true);

        $data = $this->asign_teacher_data($teacher_name, $nik, $folder_path);
        $user_data = $this->asign_user_data();

        $anggotaIds = $this->CI->input->post('anggotaId');
        $string_anggota_id = implode(',', $anggotaIds);
        $temp_data = [
            'student_ids' => $string_anggota_id,
            'email' => $user_data['email'],
            'ketua' => $this->CI->input->post('ketua')
        ];
        return [
            'data' => $data,
            'temp_data' => $temp_data,
        ];
    }

    private function upload_file($file_input_name, $upload_path, $file_name)
    {
        $config = [
            'upload_path' => $upload_path,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size' => 2048, // 2MB
            'file_name' => $file_name,
            'overwrite' => TRUE
        ];

        $this->CI->load->library('upload', $config);
        $this->CI->upload->initialize($config);

        if (!$this->CI->upload->do_upload($file_input_name)) {
            $error = $this->CI->upload->display_errors('', '');
            throw new Exception("Upload file '" . $file_input_name . "' gagal: " . $error);
        }

        $upload_data = $this->CI->upload->data();
        return str_replace('./', '', $upload_path) . '/' . $upload_data['file_name'];
    }

    private function asign_teacher_data($teacher_name, $nik, $folder_path)
    {
        return [
            'name'         => $teacher_name,
            'nik'          => $nik,
            'email' => $this->CI->input->post('email', true),
            'phone' => $this->CI->input->post('phone', true),
            'school_id'    => $this->CI->input->post('school_id', true),
            'account_name' => $this->CI->input->post('account_name', true),
            'account_number' => $this->CI->input->post('account_number', true),
            'bank'         => $this->CI->input->post('bank', true),
            'book'    => $this->upload_file('book', $folder_path, 'rekening_' . $nik),
            'identification_card' => $this->upload_file('identification_card', $folder_path, 'ktp_' . $nik),
            'status_data' => 'unverified',
            'status' => $this->CI->input->post('status', true),
            'created_at'          => date('Y-m-d H:i:s'),
        ];
    }

    private function asign_user_data()
    {
        return [
            'email'        => $this->CI->input->post('email', true),
            'role' => 'teacher',
        ];
    }
}
