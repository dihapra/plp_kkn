<?php

namespace Repositories\Superadmin;

require_once(APPPATH . 'traits/SearchTrait.php');

defined('BASEPATH') or exit('No direct script access allowed');

class Sekolah
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
        $this->db->select('id, nama, alamat');
        $this->db->from('sekolah');
        $count_total = $this->db->count_all_results('', false);

        $search_columns = ['nama', 'alamat'];
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
        $this->db->insert('sekolah', $data);
        return (int) $this->db->insert_id();
    }

    public function update(int $id, array $data): void
    {
        $this->db->where('id', $id)->update('sekolah', $data);
    }

    public function list_simple(): array
    {
        return $this->db
            ->select('id, nama')
            ->from('sekolah')
            ->order_by('nama', 'ASC')
            ->get()
            ->result_array();
    }
}
