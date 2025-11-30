<?php

namespace Repositories\Superadmin;

require_once(APPPATH . 'traits/SearchTrait.php');

defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa
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
            mahasiswa.id,
            mahasiswa.id_user,
            mahasiswa.nama,
            mahasiswa.nim,
            mahasiswa.email,
            mahasiswa.no_hp,
            mahasiswa.id_prodi,
            mahasiswa.id_sekolah,
            prodi.nama AS nama_prodi,
            prodi.fakultas AS fakultas,
            sekolah.nama AS nama_sekolah
        ');
        $this->db->from('mahasiswa');
        $this->db->join('prodi', 'prodi.id = mahasiswa.id_prodi', 'left');
        $this->db->join('sekolah', 'sekolah.id = mahasiswa.id_sekolah', 'left');
        $count_total = $this->db->count_all_results('', false);

        $search_columns = ['mahasiswa.nama', 'mahasiswa.nim', 'mahasiswa.email', 'prodi.nama', 'sekolah.nama'];
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
        $this->db->insert('mahasiswa', $data);
        return (int) $this->db->insert_id();
    }

    public function update(int $id, array $data): void
    {
        $this->db->where('id', $id)->update('mahasiswa', $data);
    }

    public function find(int $id)
    {
        return $this->db
            ->from('mahasiswa')
            ->where('id', $id)
            ->get()
            ->row();
    }
}

