<?php
defined('BASEPATH') or exit('No direct script access allowed');

use UseCases\Admin\SeederCase;

class Seeder_model extends CI_Model
{
    private $seederCase;

    public function __construct()
    {
        parent::__construct();
        $this->seederCase = new SeederCase();
    }

    public function init_dept_data()
    {
        return $this->seederCase->init_dept_data();
    }

    public function admin_seeder()
    {
        return $this->seederCase->admin_seeder();
    }

    public function initialize_data()
    {
        return $this->seederCase->initialize_data();
    }

    public function wd_seeder()
    {
        return $this->seederCase->wd_seeder();
    }

    public function run()
    {
        return $this->seederCase->run();
    }

    public function mahasiswa_seeder()
    {
        return $this->seederCase->mahasiswa_seeder();
    }

    public function user_seeder()
    {
        return $this->seederCase->user_seeder();
    }

    public function super_admin_seeder()
    {
        return $this->seederCase->super_admin_seeder();
    }

    public function kaprodi_seeder()
    {
        return $this->seederCase->kaprodi_seeder();
    }

    public function plotting_seeder()
    {
        return $this->seederCase->plotting_seeder();
    }
}
