<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Seeder extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Seeder_model');
    }

    public function index()
    {
        return $this->run();
    }

    public function run()
    {
        try {
            $this->Seeder_model->run();
            echo 'Seeder completed successfully.';
        } catch (Throwable $e) {
            log_message('error', 'Seeder run failed: ' . $e->getMessage());
            echo 'Seeder failed: ' . $e->getMessage();
        }
    }
}
