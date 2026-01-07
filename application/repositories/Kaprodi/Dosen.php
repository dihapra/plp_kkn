<?php

namespace Repositories\Kaprodi;

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

    public function datatable(array $params, array $filters = []): array
    {
        $this->buildBaseSelect($filters);

        $count_total = $this->db->count_all_results('', false);

        $search_columns = ['dosen.nama', 'dosen.email', 'prodi.nama'];
        $this->applySearch($params['search'], $search_columns);

        $count_filtered = $this->db->count_all_results('', false);

        if (!empty($params['order_column'])) {
            $this->db->order_by($params['order_column'], $params['order_dir'] ?: 'asc');
        }

        $this->db->limit($params['length'], $params['start']);

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

    public function delete(int $id): void
    {
        $this->db->where('id', $id)->delete('dosen');
    }

    public function find(int $id)
    {
        return $this->db
            ->select('dosen.*, prodi.nama as nama_prodi')
            ->from('dosen')
            ->join('prodi', 'prodi.id = dosen.id_prodi', 'left')
            ->where('dosen.id', $id)
            ->get()
            ->row();
    }

    public function all(array $filters = []): array
    {
        $this->buildBaseSelect($filters);
        $query = $this->db->get();
        return $query->result();
    }

    protected function buildBaseSelect(array $filters = []): void
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
            prodi.nama AS nama_prodi,
            COUNT(pm.id) AS total_mahasiswa,
            COALESCE(SUM(CASE WHEN pm.id_sekolah IS NOT NULL THEN 1 ELSE 0 END), 0) AS mahasiswa_aktif,
            GROUP_CONCAT(DISTINCT sekolah.nama ORDER BY sekolah.nama SEPARATOR ", ") AS sekolah_binaan
        ');
        $this->db->from('dosen');
        $this->db->join('prodi', 'prodi.id = dosen.id_prodi', 'left');
        $this->db->join('program_mahasiswa pm', 'pm.id_dosen = dosen.id', 'left');
        $this->db->join('sekolah', 'sekolah.id = pm.id_sekolah', 'left');

        if (!empty($filters['id_prodi'])) {
            $this->db->where('dosen.id_prodi', (int) $filters['id_prodi']);
        } elseif (!empty($filters['prodi_ids']) && is_array($filters['prodi_ids'])) {
            $this->db->where_in('dosen.id_prodi', array_unique(array_map('intval', $filters['prodi_ids'])));
        }

        $this->db->group_by('dosen.id');
    }
}
