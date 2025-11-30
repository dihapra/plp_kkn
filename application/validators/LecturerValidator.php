<?php


defined('BASEPATH') or exit('No direct script access allowed');

class LecturerValidator extends CI_Form_validation
{
    protected $CI;

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->library('form_validation');
    }

    public function validate_create_request()
    {
        $this->CI->form_validation->set_rules('name', 'Nama', 'required|trim');
        $this->CI->form_validation->set_rules('nip', 'NIP', 'required|trim|is_unique[lecturers.nip]|is_unique[users.nip]');
        $this->CI->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[lecturers.email]|is_unique[users.email]');
        $this->CI->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->CI->form_validation->set_rules('phone', 'Telepon', 'trim');
        $this->CI->form_validation->set_rules('prodi_id', 'Prodi ID', 'trim|integer');
        $this->CI->form_validation->set_rules('prodi', 'Prodi', 'trim');
        $this->CI->form_validation->set_rules('fakultas', 'Fakultas', 'trim');

        if ($this->CI->form_validation->run() == FALSE) {
            throw new Exception(validation_errors());
        }
    }

    public function validate_update_request()
    {
        $id = $this->CI->uri->segment(4); // Assuming ID is the 4th segment in URL like admin/dosen/update/ID

        $this->CI->form_validation->set_rules('name', 'Nama', 'required|trim');
        // For update, check uniqueness excluding current record
        $this->CI->form_validation->set_rules('nip', 'NIP', 'required|trim');
        $this->CI->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->CI->form_validation->set_rules('password', 'Password', 'trim|min_length[6]'); // Password is optional on update
        $this->CI->form_validation->set_rules('phone', 'Telepon', 'trim');
        $this->CI->form_validation->set_rules('prodi_id', 'Prodi ID', 'trim|integer');
        $this->CI->form_validation->set_rules('prodi', 'Prodi', 'trim');
        $this->CI->form_validation->set_rules('fakultas', 'Fakultas', 'trim');

        if ($this->CI->form_validation->run() == FALSE) {
            throw new Exception(validation_errors());
        }
    }
}
