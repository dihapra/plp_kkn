<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Form_validation $form_validation
 */
class DesindValidator
{
    protected $CI;

    protected $user;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('form_validation');
        $this->user = $this->CI->session->userdata();
    }

    public function validate_pengusulan_user()
    {
        if ($this->CI->session->userdata('role') === 'umum') {
            $this->CI->form_validation->set_rules('pekerjaan_pengusul', 'Pekerjaan Pengusul', 'required');
        }
        // Set validation rules for specific fields
        $this->CI->form_validation->set_rules('judul', 'Judul', 'required');
        $this->CI->form_validation->set_rules('uraian', 'Uraian', 'required');
        $this->CI->form_validation->set_rules('kegunaan', 'Kegunaan', 'required');
        $this->CI->form_validation->set_rules('klaim', 'Klaim', 'required');
        $this->CI->form_validation->set_rules('set_penuh', '1 Set Desain', 'required');
        $this->CI->form_validation->set_rules(
            'dokumen',
            'Dokumen',
            'required|callback_valid_google_drive_url',
            ['required' => 'Field %s is required.']
        );
        $this->CI->form_validation->set_rules('handphone', 'Handphone', 'required|regex_match[/^[0-9]{9,14}$/]', [
            'required' => 'Field {field} harus diisi.',
            'regex_match' => 'Field {field} harus berisi nomor telepon yang valid dengan 9 hingga 14 digit.'
        ]);
        $this->CI->form_validation->set_rules('wa', 'WhatsApp', 'required|regex_match[/^[0-9]{9,14}$/]', [
            'required' => 'Field {field} harus diisi.',
            'regex_match' => 'Field {field} harus berisi nomor WhatsApp yang valid dengan 9 hingga 14 digit.'
        ]);

        // Run validation
        if ($this->CI->form_validation->run() == FALSE) {
            $errors = $this->CI->form_validation->error_array();
            $errorString = implode(', ', array_values($errors));
            response_error($errorString, 400);
            return;
        }
    }
}
