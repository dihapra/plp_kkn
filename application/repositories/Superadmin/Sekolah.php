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

    public function datatable_by_program(array $params, int $programId): array
    {
        $this->db->select('sekolah.id, sekolah.nama, sekolah.alamat, program_sekolah.id_program');
        $this->db->from('program_sekolah');
        $this->db->join('sekolah', 'sekolah.id = program_sekolah.id_sekolah', 'inner');
        $this->db->where('program_sekolah.id_program', $programId);
        $count_total = $this->db->count_all_results('', false);

        $search_columns = ['sekolah.nama', 'sekolah.alamat'];
        $this->applySearch($params['search'], $search_columns);

        $count_filtered = $this->db->count_all_results('', false);

        $this->db->limit($params['length'], $params['start']);
        $orderMap = [
            'nama' => 'sekolah.nama',
            'alamat' => 'sekolah.alamat',
        ];
        $orderColumn = $orderMap[$params['order_column']] ?? 'sekolah.nama';
        $this->db->order_by($orderColumn, $params['order_dir']);

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

    public function ensure_program_relation(int $programId, int $sekolahId): void
    {
        $exists = $this->db->select('id')
            ->from('program_sekolah')
            ->where('id_program', $programId)
            ->where('id_sekolah', $sekolahId)
            ->limit(1)
            ->get()
            ->row();

        if ($exists) {
            return;
        }

        $this->db->insert('program_sekolah', [
            'id_program' => $programId,
            'id_sekolah' => $sekolahId,
            'valid_from' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function update(int $id, array $data): void
    {
        $this->db->where('id', $id)->update('sekolah', $data);
    }

    public function delete(int $id): void
    {
        $this->db->where('id', $id)->delete('sekolah');
    }

    public function delete_program_relation(int $programId, int $sekolahId): void
    {
        $this->db->where('id_program', $programId)
            ->where('id_sekolah', $sekolahId)
            ->delete('program_sekolah');
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
