<?php

namespace Repositories\Superadmin;

require_once(APPPATH . 'traits/SearchTrait.php');

defined('BASEPATH') or exit('No direct script access allowed');

class AdminPic
{
    use \SearchTrait;

    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    public function datatable(array $params): array
    {
        $count_total = (int) $this->db
            ->from('users')
            ->where('users.role', 'admin')
            ->count_all_results();

        $search_columns = [
            'users.username',
            'users.email',
            'users.fakultas',
            'p.nama',
            'p.kode',
            'p.tahun_ajaran',
        ];

        $this->db->select('COUNT(DISTINCT users.id) AS aggregate', false);
        $this->db->from('users');
        $this->db->join('akses_modul_user amu', 'amu.id_user = users.id AND amu.aktif = 1', 'left');
        $this->db->join('program p', 'p.id = amu.id_program', 'left');
        $this->db->where('users.role', 'admin');
        $this->applySearch($params['search'], $search_columns);
        $countRow = $this->db->get()->row();
        $count_filtered = (int) ($countRow->aggregate ?? 0);

        $this->db->select('
            users.id,
            users.username AS nama,
            users.email,
            users.fakultas,
            users.created_at,
            GROUP_CONCAT(DISTINCT CONCAT(
                COALESCE(UPPER(p.kode), ""),
                CASE WHEN p.kode IS NOT NULL AND p.kode <> "" THEN " " ELSE "" END,
                COALESCE(p.nama, ""),
                CASE WHEN p.tahun_ajaran IS NOT NULL AND p.tahun_ajaran <> "" THEN CONCAT(" (", p.tahun_ajaran, ")") ELSE "" END
            ) ORDER BY p.tahun_ajaran DESC SEPARATOR ", ") AS program_list
        ', false);
        $this->db->from('users');
        $this->db->join('akses_modul_user amu', 'amu.id_user = users.id AND amu.aktif = 1', 'left');
        $this->db->join('program p', 'p.id = amu.id_program', 'left');
        $this->db->where('users.role', 'admin');
        $this->applySearch($params['search'], $search_columns);
        $this->db->group_by('users.id');

        $orderMap = [
            'nama' => 'users.username',
            'email' => 'users.email',
            'fakultas' => 'users.fakultas',
            'programs' => 'program_list',
            'created_at' => 'users.created_at',
        ];
        $orderColumn = $orderMap[$params['order_column']] ?? 'users.username';
        $orderDir = strtolower((string) $params['order_dir']) === 'desc' ? 'desc' : 'asc';

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($params['length'], $params['start']);

        $query = $this->db->get();

        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered,
        ];
    }

    public function create(array $data): int
    {
        $this->db->insert('users', $data);
        return (int) $this->db->insert_id();
    }

    public function update(int $id, array $data): void
    {
        $this->db->where('id', $id)->update('users', $data);
    }

    public function delete(int $id): void
    {
        $this->db->where('id', $id)->delete('users');
    }

    public function find(int $id)
    {
        return $this->db
            ->from('users')
            ->where('id', $id)
            ->where('role', 'admin')
            ->get()
            ->row();
    }
}
