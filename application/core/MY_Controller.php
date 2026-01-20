<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_URI $uri
 * @property CI_Pagination $pagination
 * @property CI_Output $output
 * @property CI_Router $router
 * 
 */

class MY_Controller extends CI_Controller
{
    protected $user;
    protected $role;
    protected $currentProgramId;
    protected $programSessionKey = 'program_id';
    // protected $programRedirectRoute = 'module/select';
    protected $exclude_methods = []; // Method yang dikecualikan dari login

    public function __construct()
    {
        parent::__construct();
        
        $this->check_method();
    }

    public static function get_exclude_methods_static()
    {
        return isset(static::$exclude_methods) && is_array(static::$exclude_methods)
            ? static::$exclude_methods
            : [];
    }

    protected function get_exclude_methods()
    {
        return $this->exclude_methods;
    }

    private function check_method()
    {
        $current_method = $this->router->method;
        $parent_class = get_parent_class($this);
        // Gabungkan metode dari child dan parent
        $exclude_methods = $this->get_exclude_methods();
        if (method_exists($parent_class, 'get_exclude_methods_static')) {
            $exclude_methods = array_merge($exclude_methods, $parent_class::get_exclude_methods_static());
        }
        if (!in_array($current_method, $exclude_methods)) {
            $this->check_login();
            $this->ensureProgramSelection();
        }
    }


    private function method_boolean_check()
    {
        $current_method = $this->router->method;
        $parent_class = get_parent_class($this);
        // Gabungkan metode dari child dan parent
        $exclude_methods = $this->get_exclude_methods();
        if (method_exists($parent_class, 'get_exclude_methods_static')) {
            $exclude_methods = array_merge($exclude_methods, $parent_class::get_exclude_methods_static());
        }
        if (!in_array($current_method, $exclude_methods)) {
            return false;
        }
        return true;
    }

    private function check_login()
    {
        $this->user['logged_in'] = $this->session->userdata('logged_in');
        $this->user['role'] = $this->session->userdata('role');
        $uri = $this->uri->uri_string();
    
        if (empty($this->user['role']) && !str_contains($uri, 'super-admin')) {
            redirect('login');
            exit;
        }else if(empty($this->user['role']) &&  str_contains($uri,'super-admin')){
            redirect('super-admin/login');
            exit;
        }
    }

    protected function ensureProgramSelection($programId = null)
    {
        if($this->session->userdata('role') == 'super_admin'){
            return;
        }
        if (empty($this->user['logged_in'])) {
            return;
        }

        if($this->session->userdata('role') == 'kaprodi'){
            return;
        }
        $programId = $this->session->userdata($this->programSessionKey)
            ?? $this->session->userdata('id_program');

        // if (empty($programId)) {
        //     redirect($this->programRedirectRoute);
        //     exit;
        // }
        if(!empty($programId) &&$this->session->userdata($this->programSessionKey)  != $programId){
            redirect("/dashboard");
        }
        $this->currentProgramId = $programId;
    }

    protected function check_role($allowed_roles = [])
    {
        
        if (!empty($allowed_roles)  && !$this->method_boolean_check() ) {
            // var_dump($this->user, $allowed_roles,$this->session->userdata());
            if (!in_array($this->user['role'], $allowed_roles)) {
                $this->output->set_status_header(403); // Set response code 403
                $this->load->view('unauthorize');
                $this->output->_display(); // Paksa output agar ditampilkan sebelum exit
                exit();
            }
        }
    }
}
