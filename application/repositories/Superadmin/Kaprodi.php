<?php

namespace Repositories\Superadmin;

require_once(APPPATH . 'traits/SearchTrait.php');

defined('BASEPATH') or exit('No direct script access allowed');

class Kaprodi
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
            kaprodi.id,
            kaprodi.id_user,
            kaprodi.id_prodi,
            kaprodi.nama,
            kaprodi.no_hp,
            kaprodi.email,
            prodi.nama AS nama_prodi,
            prodi.fakultas AS fakultas
        ');
        $this->db->from('kaprodi');
        $this->db->join('prodi', 'prodi.id = kaprodi.id_prodi', 'left');
        $count_total = $this->db->count_all_results('', false);

        $search_columns = ['kaprodi.nama', 'kaprodi.email', 'prodi.nama'];
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
        $this->db->insert('kaprodi', $data);
        return (int) $this->db->insert_id();
    }

    public function update(int $id, array $data): void
    {
        $this->db->where('id', $id)->update('kaprodi', $data);
    }

    public function find(int $id)
    {
        return $this->db
            ->from('kaprodi')
            ->where('id', $id)
            ->get()
            ->row();
    }
}
