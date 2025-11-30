<?php

use Repositories\SchoolRepository;
use Repositories\StudentRepository;

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property User_model $User_model
 */

class Utils extends MY_Controller
{

    protected $exclude_methods = [
        'select_school',
        'select_prodi',
        'select_student',
        'get_school_for_registration',
        'get_prodi_for_registration',
        'select_student_for_registration',
        'get_school_for_principal_register',
    ];
    protected $SchoolRepository;
    protected $ProdiRepository;
    public function __construct()
    {
        parent::__construct();
        $this->SchoolRepository = new SchoolRepository();
        $this->ProdiRepository = new ProdiRepository();
    }

    public function select_lecture()
    {
        $this->db->select('id, name,nip')->from('lecturers');
        $lecturers =  $this->db->get()->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($lecturers));
    }
    public function select_teacher()
    {
        $school_id = $_GET['schoolId'];
        $this->db->select('id, name');
        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        $this->db->from('teachers');
        $teachers = $this->db->get()->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($teachers));
    }
    public function select_school()
    {
        $schools = $this->SchoolRepository->get_school();
        $this->output->set_content_type('application/json')->set_output(json_encode($schools));
    }
    public function get_school_for_registration()
    {
        $schools = $this->SchoolRepository->get_school_for_registration();
        $this->output->set_content_type('application/json')->set_output(json_encode($schools));
    }
    public function select_prodi()
    {
        $prodis = $this->db->select('name,fakultas')->get('prodi')->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($prodis));
    }
    public function get_prodi_for_registration()
    {
        $school_id = $this->input->get('school_id', true);
        if (!is_numeric($school_id)) {
            response_json("Sekolah tidak valid", null, 422);
            exit();
        }
        $schools = $this->ProdiRepository->get_prodi_for_registration($school_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($schools));
    }

    public function select_student_for_registration()
    {
        $school_id = $this->input->get('school_id');
        $prodi_id = $this->input->get('prodi_id');
        if ($school_id !== null && !filter_var($school_id, FILTER_VALIDATE_INT) && $prodi_id !== null && !filter_var($prodi_id, FILTER_VALIDATE_INT)) {
            show_error('Invalid school_id & prodi_id', 400);
        }
        $repo = new StudentRepository();
        $student = $repo->get_students_for_registration($school_id, $prodi_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($student));
    }

    public function get_school_for_principal_register()
    {
        $this->db->select('school_id')->where('status_data !=', 'rejected')->from('principal');
        $taken_schools = $this->db->get()->result_array();
        $taken_school_ids = array_column($taken_schools, 'school_id');

        $this->db->select('id, name')->from('school');
        if (!empty($taken_school_ids)) {
            $this->db->where_not_in('id', $taken_school_ids);
        }
        $schools = $this->db->get()->result_array();

        $this->output->set_content_type('application/json')->set_output(json_encode($schools));
    }
}
