<?php

namespace Repositories\Superadmin;

require_once(APPPATH . 'traits/SearchTrait.php');

defined('BASEPATH') or exit('No direct script access allowed');

class Prodi
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
        $this->db->select('id, nama, fakultas');
        $this->db->from('prodi');
        $count_total = $this->db->count_all_results('', false);

        $search_columns = ['nama', 'fakultas'];
        $this->applySearch($params['search'], $search_columns);

        $count_filtered = $this->db->count_all_results('', false);

        $this->db->limit($params['length'], $params['start']);
        $this->db->order_by($params['order_column'], $params['order_dir']);

        $query = $this->db->get();

        return [
            'query'          => $query->result(),
            'count_total'    => $count_total,
            'count_filtered' => $count_filtered,
        ];
    }

    public function create(array $data): int
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        $this->db->insert('prodi', $data);
        return (int) $this->db->insert_id();
    }

    public function update(int $id, array $data): void
    {
        $this->db->where('id', $id)->update('prodi', $data);
    }

    public function list_simple(): array
    {
        return $this->db
            ->select('id, nama, fakultas')
            ->from('prodi')
            ->order_by('nama', 'ASC')
            ->get()
            ->result_array();
    }

    public function list_fakultas(): array
    {
        $rows = $this->db
            ->select('DISTINCT fakultas', false)
            ->from('prodi')
            ->where('fakultas IS NOT NULL', null, false)
            ->where('fakultas !=', '')
            ->order_by('fakultas', 'ASC')
            ->get()
            ->result_array();

        return array_map(static function ($row) {
            return $row['fakultas'];
        }, $rows);
    }
}
