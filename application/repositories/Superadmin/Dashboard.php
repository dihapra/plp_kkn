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
        $this->db->select('program.nama AS program, COUNT(program_kelompok.id) AS total_groups');
        $this->db->from('program');
        $this->db->join('program_kelompok', 'program_kelompok.id_program = program.id', 'left');
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

    public function get_registrants_by_prodi(array $filters = []): array
    {
        $this->db->select('prodi.nama AS prodi, COUNT(mahasiswa.id) AS total');
        $this->db->from('mahasiswa');
        $this->db->join('prodi', 'prodi.id = mahasiswa.id_prodi', 'left');
        $this->db->join(
            'program_mahasiswa pm',
            'pm.id = (SELECT MAX(pm2.id) FROM program_mahasiswa pm2 WHERE pm2.id_mahasiswa = mahasiswa.id)',
            'left',
            false
        );
        $this->db->join('program', 'program.id = pm.id_program', 'left');
        if (!empty($filters['program_code'])) {
            $this->db->where('program.kode', $filters['program_code']);
        }
        if (!empty($filters['tahun_ajaran'])) {
            $this->db->where('program.tahun_ajaran', $filters['tahun_ajaran']);
        }
        $this->db->group_by('prodi.id');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_program_codes(): array
    {
        return $this->db
            ->select('program.kode, MIN(program.nama) AS nama')
            ->from('program')
            ->where('program.kode IS NOT NULL', null, false)
            ->where('program.kode <>', '')
            ->group_by('program.kode')
            ->order_by('program.kode', 'ASC')
            ->get()
            ->result_array();
    }

    public function get_program_years(): array
    {
        return $this->db
            ->select('program.tahun_ajaran')
            ->from('program')
            ->where('program.tahun_ajaran IS NOT NULL', null, false)
            ->where('program.tahun_ajaran <>', '')
            ->group_by('program.tahun_ajaran')
            ->order_by('program.tahun_ajaran', 'DESC')
            ->get()
            ->result_array();
    }
}
