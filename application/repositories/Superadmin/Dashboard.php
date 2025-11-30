<?php
namespace Repositories\Superadmin;
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard
{
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->db = $this->CI->db;
    }

    public function get_total_programs(): int
    {
        return (int) $this->db->count_all('program');
    }

    public function get_total_users(): int
    {
        return (int) $this->db->count_all('users');
    }

    public function get_total_students(): int
    {
        return (int) $this->db->count_all('mahasiswa');
    }

    public function get_total_teachers(): int
    {
        return (int) $this->db->count_all('guru');
    }

    public function get_total_lecturers(): int
    {
        return (int) $this->db->count_all('dosen');
    }

    public function get_groups_by_program(): array
    {
        $this->db->select('program.nama AS program, COUNT(kelompok.id) AS total_groups');
        $this->db->from('program');
        $this->db->join('kelompok', 'kelompok.id_program = program.id', 'left');
        $this->db->group_by('program.id');
        return $this->db->get()->result_array();
    }

    public function get_users_by_role(): array
    {
        $this->db->select('role, COUNT(id) AS total');
        $this->db->from('users');
        $this->db->group_by('role');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_users_by_program(): array
    {
        $this->db->select('program.nama AS program, COUNT(users.id) AS total');
        $this->db->from('users');
        $this->db->join('program', 'program.id = users.id_program', 'left');
        $this->db->group_by('program.id');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result_array();
    }
}
