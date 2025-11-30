<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_DB_mysqli_driver|CI_DB_query_builder $db
 */
class AuthRepository
{
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
    public function save_user($data)
    {
        return $this->db->insert('users', $data);
    }
    public function save_teacher($data)
    {
        $this->db->insert('teachers', $data);
        return $this->db->insert_id();
    }
    public function save_temp_data($data)
    {
        return $this->db->insert('temp_teacher', $data);
    }

    public function verified_teacher($id)
    {
        return $this->db->where('id', $id)->update(
            'teachers',
            [
                'status_data' => 'verified',
                'verified_by' => $this->CI->session->userdata('user_id'),
                'updated_at'  => date('Y-m-d H:i:s') // tambahkan manual
            ]
        );
    }

    public function save_group($data, $students_id, $teacher_id, int $ketua_id)
    {
        try {
            $this->db->insert('student_group', $data);
            $group_id = $this->db->insert_id();
            foreach ($students_id as $student_id) {
                $value = [
                    'group_id' => $group_id,
                    'teacher_id' => $teacher_id,
                    'leader' => $ketua_id == $student_id ? 1 : 0
                ];
                $this->db->where('id', $student_id);
                $this->db->update('student', $value);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function get_teacher_temp($id)
    {
        return $this->db->where('teacher_id', $id)->get('temp_teacher')->row();
    }


    public function get_password($id)
    {
        try {

            $this->db->select('id,password,role');
            $this->db->where('users.id', $id);
            $this->db->from('users');
            $data = $this->db->get()->row();
            if ($data->role == 'admin' || $data->role == 'super_admin') {
                throw new Exception('Tidak Boleh Diambil');
            }
            return $data;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
