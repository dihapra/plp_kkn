<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/superadmincontroller/Modulebase.php');

class Kkn extends Modulebase
{
    protected $moduleLabel = 'KKN';
    protected $moduleSlug  = 'kkn';
    protected $pageDescriptions = [
        'activities' => 'Atur agenda KKN mulai dari pembekalan, penerjunan hingga monitoring lapangan.',
        'report'     => 'Rekap laporan kemajuan dan capaian program desa binaan secara berkala.',
    ];

    public function activities()
    {
        $this->renderModulePage('activities', 'Kegiatan');
    }

    public function report()
    {
        $this->renderModulePage('report', 'Laporan');
    }
}
