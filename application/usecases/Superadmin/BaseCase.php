<?php

namespace UseCases\Superadmin;

use Repositories\Superadmin\Dashboard;
use Repositories\Superadmin\Program;
use Repositories\Superadmin\Sekolah;
use Repositories\Superadmin\Prodi;
use Repositories\Superadmin\Kepsek;
use Repositories\Superadmin\Guru;
use Repositories\Superadmin\Dosen;
use Repositories\Superadmin\Kaprodi;
use Repositories\Superadmin\Mahasiswa;
use Repositories\Superadmin\MahasiswaTrue;

defined('BASEPATH') or exit('No direct script access allowed');
class BaseCase
{
    public $CI;
    public $ProgramRepository;
    public $DashboardRepository;
    public $SekolahRepository;
    public $ProdiRepository;
    public $KepsekRepository;
    public $GuruRepository;
    public $DosenRepository;
    public $KaprodiRepository;
    public $MahasiswaRepository;
    public $MahasiswaTrueRepository;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->ProgramRepository = new Program();
        $this->DashboardRepository = new Dashboard();
        $this->SekolahRepository = new Sekolah();
        $this->ProdiRepository = new Prodi();
        $this->KepsekRepository = new Kepsek();
        $this->GuruRepository = new Guru();
        $this->DosenRepository = new Dosen();
        $this->KaprodiRepository = new Kaprodi();
        $this->MahasiswaRepository = new Mahasiswa();
        $this->MahasiswaTrueRepository = new MahasiswaTrue();
    }

    
}
