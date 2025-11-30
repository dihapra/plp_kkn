<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property User_model $User_model
 */

class Admin extends MY_Controller
{
    protected $exclude_methods = [];
    protected $DashboardService;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

 
}
