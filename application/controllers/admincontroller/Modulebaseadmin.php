<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/Admin.php');

class Modulebaseadmin extends Admin
{
   public function __construct()
    {
        parent::__construct();
    }
}
