<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ValidateTeacherService
{
    protected $CI;

    protected $AuthRepository;
    protected $AuthService;
    protected $TeacherService;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
        $this->AuthRepository = new AuthRepository();
        $this->AuthService = new AuthService();
        $this->TeacherService = new TeacherService();
    }
    public function validate_teacher($teacher_id)
    {
        $temp_data = $this->AuthRepository->get_teacher_temp($teacher_id);
        $teacher = $this->TeacherService->get_by_id($teacher_id);
        $students_id = explode(',', $temp_data->student_ids);
        $user_data = [
            'email' => $temp_data->email,
            'username' => $teacher->name,
            'password' => $temp_data->password,
            'role' => 'teacher',
            'teacher_id' => $teacher->id,
            'has_change' => 1,
        ];
        $kelompok = ['name' => "kelompok $teacher_id"];
        try {
            $this->db->trans_begin();
            $this->AuthRepository->save_group($kelompok, $students_id, $teacher_id, $temp_data->ketua);
            $this->AuthRepository->verified_teacher($teacher_id);
            $this->AuthRepository->save_user($user_data);
            $this->db->trans_commit();
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            throw $th;
        }
    }
}
