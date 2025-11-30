<?php

namespace Repositories\Superadmin;

require_once(APPPATH . 'traits/SearchTrait.php');

defined('BASEPATH') or exit('No direct script access allowed');

class Guru
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
            guru.id,
            guru.id_sekolah,
            guru.nama,
            guru.email,
            guru.no_hp,
            sekolah.nama as nama_sekolah,
            guru.status_pembayaran,
            guru.nik,
            guru.bank,
            guru.nomor_rekening,
            guru.nama_rekening,
            guru.foto_ktp,
            guru.buku
        ');
        $this->db->from('guru');
        $this->db->join('sekolah', 'sekolah.id = guru.id_sekolah', 'left');
        $count_total = $this->db->count_all_results('', false);

        $search_columns = ['guru.nama', 'guru.email', 'sekolah.nama'];
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
        $this->db->insert('guru', $data);
        return (int) $this->db->insert_id();
    }

    public function update(int $id, array $data): void
    {
        $this->db->where('id', $id)->update('guru', $data);
    }

    public function find(int $id)
    {
        return $this->db
            ->from('guru')
            ->where('id', $id)
            ->get()
            ->row();
    }
}

