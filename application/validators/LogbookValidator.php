<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Form_validation $form_validation
 */
class LogbookValidator
{
    protected $CI;

    protected $user;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('form_validation');
        $this->user = $this->CI->session->userdata();
    }


    public static function validate_save_logbook($input_data)
    {

        if (!isset($input_data['logbook']) || !is_array($input_data['logbook']) || empty($input_data['logbook'])) {
            throw new Exception("logbook tidak boleh kosong");
        }

        foreach ($input_data['logbook'] as $entry) {
            if (!isset($entry['kegiatan']) || !isset($entry['hasil']) || empty(trim($entry['kegiatan'])) || empty(trim($entry['hasil']))) {
                throw new Exception("Setiap entri logbook harus memiliki kegiatan dan hasil.");
            }
        }
        // Validasi input
        $CI = &get_instance();
        $CI->form_validation->set_data($input_data);
        $CI->form_validation->set_rules('meeting_number', 'Pertemuan', 'required|integer|greater_than[0]|less_than_equal_to[16]');
        $CI->form_validation->set_rules('permasalahan', 'Permasalahan', 'required');
        $CI->form_validation->set_rules('solusi', 'Solusi yang Diberikan', 'required');
        $CI->form_validation->set_rules('kesimpulan', 'Kesimpulan', 'required');

        if ($CI->form_validation->run() === FALSE) {
            throw new Exception(strip_tags(validation_errors()));
        }
    }
}
