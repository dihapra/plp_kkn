<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardService
{
    protected $CI;
    protected $DashboardRepository;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->DashboardRepository =  new DashboardRepository;
    }

    public function get_admin_dashboard()
    {
        $total_mahasiswa = $this->DashboardRepository->get_total_mahasiswa();
        $total_dosen = $this->DashboardRepository->get_total_dosen();
        $total_guru = $this->DashboardRepository->get_total_guru();
        $total_sekolah = $this->DashboardRepository->get_total_sekolah();
        $total_kepsek = $this->DashboardRepository->get_total_kepsek();
        $chart_mahasiswa = $this->DashboardRepository->get_mahasiswa_per_fakultas();
        $chart_dosen = $this->DashboardRepository->get_dosen_per_fakultas();
        $total_sekolah_kosong = $this->DashboardRepository->get_total_sekolah_empty();
        return  [
            'total_mahasiswa' => $total_mahasiswa,
            'total_dosen' => $total_dosen,
            'total_guru' => $total_guru,
            'total_sekolah' => $total_sekolah,
            'total_kepsek' => $total_kepsek,
            'chart_mahasiswa' => $chart_mahasiswa,
            'chart_dosen' => $chart_dosen,
            'total_sekolah_kosong' => $total_sekolah_kosong,
        ];
    }

    public function getAll()
    {
        return "Hello World";
    }

    // public function create($data)
    // {
    //     return $this->CI->ExampleModel->insert($data);
    // }
}
