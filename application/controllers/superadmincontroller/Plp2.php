<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/superadmincontroller/Modulebase.php');

class Plp2 extends Modulebase
{
    protected $moduleLabel = 'PLP II';
    protected $moduleSlug  = 'plp2';
    protected $pageDescriptions = [
        'activities'           => 'Monitor aktivitas lanjutan PLP II termasuk pengembangan perangkat ajar di sekolah mitra.',
        'report'               => 'Pastikan laporan dampak dan refleksi PLP II tersusun rapi sebelum dikirim ke pimpinan.',
        'absensi'              => 'Tracking presensi harian mahasiswa PLP II agar proses pencairan berjalan lancar.',
        'verifikasi_mahasiswa' => 'Kelola dokumen mahasiswa PLP II sebelum melanjutkan ke tahap lapangan.',
        'verifikasi_guru'      => 'Validasi guru pamong PLP II beserta kelengkapan dokumennya.',
        'verifikasi_kepsek'    => 'Pastikan kepala sekolah mitra PLP II sudah diverifikasi dan siap mendampingi.',
    ];

    public function activities()
    {
        $this->renderModulePage('activities', 'Kegiatan');
    }

    public function report()
    {
        $this->renderModulePage('report', 'Laporan');
    }

    public function absensi()
    {
        $this->renderModulePage('absensi', 'Absensi');
    }

    public function verifikasi_mahasiswa()
    {
        $this->renderModulePage('verifikasi_mahasiswa', 'Verifikasi Mahasiswa');
    }

    public function verifikasi_guru()
    {
        $this->renderModulePage('verifikasi_guru', 'Verifikasi Guru');
    }

    public function verifikasi_kepsek()
    {
        $this->renderModulePage('verifikasi_kepsek', 'Verifikasi Kepala Sekolah');
    }
}
