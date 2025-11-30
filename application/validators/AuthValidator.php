<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Form_validation $form_validation
 */
class AuthValidator
{
    protected $CI;

    protected $user;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('form_validation');
        $this->user = $this->CI->session->userdata();
    }

    public function run()
    {
        if ($this->CI->form_validation->run() === FALSE) {
            $errs = $this->CI->form_validation->error_array(); // ['field' => 'msg', ...]
            $msg  = implode(' | ', array_map('trim', array_values($errs)));
            throw new Exception($msg);
        }
    }

    public function register_validator()
    {
        $this->CI->form_validation->set_rules('name', 'Nama', 'required|trim');
        $this->CI->form_validation->set_rules('status', 'status', 'required|trim');
        $this->CI->form_validation->set_rules('nik', 'NIK', 'required|numeric');
        $this->CI->form_validation->set_rules('phone', 'phone', 'required|numeric');
        $this->CI->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->CI->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->CI->form_validation->set_rules('school_id', 'Sekolah', 'required|integer');
        $this->CI->form_validation->set_rules('prodi_id', 'Prodi', 'required|integer');
        $this->CI->form_validation->set_rules('ketua', 'Ketua', 'required|integer');
        $this->CI->form_validation->set_rules('account_name', 'Nama Rekening', 'required');
        $this->CI->form_validation->set_rules('account_number', 'No Rekening', 'required|numeric');
        $this->CI->form_validation->set_rules('bank', 'Bank', 'required');
        $this->CI->form_validation->set_rules('anggotaId[]', 'Anggota', 'required');
        $this->run();
    }

    public function login_validator()
    {
        $this->CI->form_validation->set_rules('identifier', 'Email', 'required', array('required' => 'Field Email/NIP/NIM harus diisi.'));
        $this->CI->form_validation->set_rules('password', 'Password', 'required', array('required' => 'Field Password harus diisi.'));


        if ($this->CI->form_validation->run() == FALSE) {
            $this->CI->load->view('auth/login/index');
        }
    }
    public function validate_update_password()
    {
        $rules = [
            ['field' => 'currentPassword', 'label' => 'Password Lama', 'rules' => 'required'],
            ['field' => 'newPassword', 'label' => 'Password Baru', 'rules' => 'required|min_length[6]'],
            ['field' => 'confirmPassword', 'label' => 'Konfirmasi Password Baru', 'rules' => 'required|matches[newPassword]'],
        ];

        $this->CI->form_validation->set_rules($rules);

        if (!$this->CI->form_validation->run()) {
            throw new Exception(strip_tags(validation_errors()));
        }
    }
}
