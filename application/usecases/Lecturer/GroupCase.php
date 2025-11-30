<?php

namespace UseCases\Lecturer;

use Exception;

class GroupCase
{
    protected $CI;
    protected $db;


    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    public function get_groups()
    {
        $nip = $this->CI->session->userdata('nip');
        $lecture = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecture) {
            throw new Exception('Lecturer not found');
        }

        $this->db->select('student_group.*');
        // daftar anggota
        $this->db->select("GROUP_CONCAT(student.name ORDER BY student.name SEPARATOR ', ') AS members", false);
        // ketua (ambil satu yg leader=1)
        $this->db->select("MAX(CASE WHEN student.leader = 1 THEN student.name END) AS leader_name", false);
        $this->db->select("MAX(CASE WHEN student.leader = 1 THEN student.id   END) AS leader_id", false);

        $this->db->from('student_group');
        $this->db->join('student', 'student.group_id = student_group.id', 'left');

        // hanya kelompok yg dibimbing oleh dosen ini
        $this->db->where('student.lecture_id', $lecture->id);

        $this->db->group_by('student_group.id');

        // Jika ONLY_FULL_GROUP_BY aktif dan kamu butuh aman:
        // $this->db->group_by(['student_group.id','student_group.name','student_group.created_at','student_group.updated_at','student_group.created_by','student_group.updated_by']);

        return $this->db->get()->result();
    }

    public function members_of($group_id)
    {
        return $this->db->select('student.id, student.name, student.leader')
            ->from('student')
            ->where('student.group_id', $group_id)    // cukup ini kalau kolom ada
            ->order_by('student.name', 'asc')
            ->get()->result_array();
    }
    public function update_leader()
    {
        $group_id  = $this->CI->input->post('group_id', true);
        $leader_id = $this->CI->input->post('leader_id', true);

        if (!$group_id || !$leader_id) {
            throw new Exception('Data tidak lengkap');
        }

        $this->db->trans_start();
        // reset semua anggota group jadi 0
        $this->db->where('group_id', $group_id)->update('student', ['leader' => 0]);
        // set yang dipilih jadi 1 (pastikan dia memang anggota group tsb)
        $this->db->where('id', $leader_id)->where('group_id', $group_id)->update('student', ['leader' => 1]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            throw new Exception('Gagal menyimpan perubahan');
        }

        // kirim balik nama ketua baru (opsional)
        return $this->db->select('name')->from('student')->where('id', $leader_id)->get()->row();
    }
}
