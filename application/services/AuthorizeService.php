<?php
namespace Services;
defined('BASEPATH') or exit('No direct script access allowed');
class AuthorizeService
{
    protected $CI;
    protected $db;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    public function check_user_access($id_program)
    {
        $id_user = $this->CI->session->userdata('user_id');

        $exists = $this->db
            ->where('id_user', $id_user)
            ->where('id_program', $id_program)
            ->where('aktif', 1)
            ->limit(1)
            ->count_all_results('akses_modul_user');

        if ($exists > 0) {
            return true;
        }

        throw new \Exception("User tidak memiliki akses spesial apapun");
    }

}
