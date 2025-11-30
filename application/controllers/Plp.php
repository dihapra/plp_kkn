<?php

use Repositories\Superadmin\Program;
use Services\AuthorizeService;
use UseCases\Admin\GetPrincipalData;
use UseCases\Admin\GetTeacherData;
use UseCases\Admin\GetUnverifiedPrincipalDatatable;
use UseCases\Admin\InsertSchoolCase;
use UseCases\Admin\UpdateSchoolCase;
use UseCases\Admin\UpdateTeacherCase;
use UseCases\Admin\UserCase;
use UseCases\Admin\ImportMainDataCase;
use UseCases\Admin\VerifyPrincipalCase;

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property User_model $User_model
 */

class Plp extends MY_Controller
{
    protected $exclude_methods = [];
    protected $DashboardService;

    public function __construct()
    {
        parent::__construct();
        
        $this->init_check();
    }
    private function init_check()
    {
        try {
            $service = new AuthorizeService();
            $program = new Program();
            $service->check_user_access($program->get_latest_program());
        } catch (\Throwable $th) {
            $role = $this->session->userdata('role');
            $this->session->set_flashdata('error', $th->getMessage());
            redirect(base_url(''));
            exit;
        }
    }


}
