<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Form_validation $form_validation
 */
class SchoolValidator
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
            throw new Exception(validation_errors());
        }
    }
    public function validate_create()
    {
        $rule = [
            ['field' => 'name', 'label' => 'name', 'rules' => 'required'],
            // ['field' => 'principal', 'label' => 'Kepala Sekolah', 'rules' => 'required'],
            // ['field' => 'email', 'label' => 'Email', 'rules' => 'required'],
            // ['field' => 'phone', 'label' => 'phone', 'rules' => 'required'],
            // ['field' => 'bank', 'label' => 'bank', 'rules' => 'required'],
            // ['field' => 'accountNumber', 'label' => 'No Rekening', 'rules' => 'required|integer'],
            // ['field' => 'accountName', 'label' => 'Nama di Rekening', 'rules' => 'required'],
        ];
        // Set validasi form
        foreach ($rule as $key => $rule) {
            $this->CI->form_validation->set_rules($rule['field'], $rule['label'], $rule['rules']);
        }
        if (!$this->CI->form_validation->run()) {
            throw new Exception(validation_errors());
        }
    }
}
