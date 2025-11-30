<?php

use UseCases\Principal\DashboardData;
use UseCases\Principal\StudentCase;
use UseCases\Principal\TeacherCase;

defined('BASEPATH') or exit('No direct script access allowed');

class Kepsek extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_role(['principal', 'kepsek']);
    }

    public function index()
    {
        $uc = new DashboardData();
        $data = $uc->execute();
        view_with_layout('kepsek/index', 'Dashboard Kepala Sekolah', $data);
    }

    public function mahasiswa()
    {
        view_with_layout('kepsek/mahasiswa', 'Data Mahasiswa', [], 'css/datatable', 'script/datatable');
    }

    public function guru()
    {
        view_with_layout('kepsek/guru', 'Data Guru', [], 'css/datatable', 'script/datatable');
    }

    public function datatable_student()
    {
        $req = get_param_datatable();
        $uc = new StudentCase();
        $result = $uc->principal_datatable($req);
        $formatter = $uc->student_formatter($result);
        datatable_response($req['draw'], $result, $formatter);
    }

    public function datatable_teacher()
    {
        $req = get_param_datatable();
        $uc = new TeacherCase();
        $result = $uc->principal_datatable($req);
        $formatter = $uc->teacher_formatter($result);
        datatable_response($req['draw'], $result, $formatter);
    }
}
