<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 */

class Example extends MY_Controller
{
    public $ExampleServices;
    public $importServices;

    protected $exclude_methods = [
        'login',
        'register',
        'db_seed',
        'hello'
    ];
    public function __construct()
    {
        parent::__construct();
        $this->ExampleServices = new ExampleServices(); // tanpa require_once!
        $this->importServices = new ExcelImportService();
    }

    public function hello()
    {
        return   var_dump($this->ExampleServices->getAll());
    }
    public function import()
    {
        $filePath = $_FILES['file']['tmp_name'];

        $rules = [
            'nama' => 'required',
            'email' => 'required|valid_email',
            'usia' => 'required'
        ];

        try {
            $result = ExcelImportService::importWithValidation($filePath, $rules);

            echo "<h3>Data Valid</h3>";
            print_r($result['valid']);

            echo "<h3>Data Tidak Valid</h3>";
            print_r($result['invalid']);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
