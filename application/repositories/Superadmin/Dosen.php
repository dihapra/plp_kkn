<?php

namespace Repositories\Superadmin;

require_once(APPPATH . 'traits/SearchTrait.php');

defined('BASEPATH') or exit('No direct script access allowed');

class Dosen
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
            dosen.id,
            dosen.id_user,
            dosen.nama,
            dosen.nidn,
            dosen.email,
            dosen.no_hp,
            dosen.id_prodi,
            dosen.fakultas,
            prodi.nama as nama_prodi
        ');
        $this->db->from('dosen');
        $this->db->join('prodi', 'prodi.id = dosen.id_prodi', 'left');
        $count_total = $this->db->count_all_results('', false);

        $search_columns = ['dosen.nama', 'dosen.nidn', 'dosen.email', 'prodi.nama'];
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
        $this->db->insert('dosen', $data);
        return (int) $this->db->insert_id();
    }

    public function update(int $id, array $data): void
    {
        $this->db->where('id', $id)->update('dosen', $data);
    }

    public function find(int $id)
    {
        return $this->db
            ->from('dosen')
            ->where('id', $id)
            ->get()
            ->row();
    }
}

