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
        $this->db->select('
            users.id,
            users.username AS nama,
            users.email,
            users.fakultas,
            users.created_at
        ');
        $this->db->from('users');
        $this->db->where('users.role', 'admin');

        $count_total = $this->db->count_all_results('', false);

        $search_columns = [
            'users.username',
            'users.email',
            'users.fakultas',
        ];
        $this->applySearch($params['search'], $search_columns);

        $count_filtered = $this->db->count_all_results('', false);

        $orderMap = [
            'nama' => 'users.username',
            'email' => 'users.email',
            'fakultas' => 'users.fakultas',
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
