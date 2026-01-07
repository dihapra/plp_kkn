<?php

namespace Repositories\Kaprodi;

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

    public function datatable(array $params, array $filters = []): array
    {
        $this->buildBaseSelect($filters);

        $count_total = $this->db->count_all_results('', false);

        $search_columns = [
            'mahasiswa.nama',
            'mahasiswa.nim',
            'prodi.nama',
            'sekolah.nama',
            'program.nama',
            'program.tahun_ajaran',
        ];
        $this->applySearch($params['search'], $search_columns);

        $count_filtered = $this->db->count_all_results('', false);

        $orderMap = [
            'nama' => 'mahasiswa.nama',
            'nim' => 'mahasiswa.nim',
            'prodi' => 'prodi.nama',
            'sekolah' => 'sekolah.nama',
            'program_aktif' => 'program.nama',
        ];
        $orderColumn = $orderMap[$params['order_column']] ?? 'mahasiswa.nama';
        $orderDir = strtolower((string) $params['order_dir']) === 'desc' ? 'desc' : 'asc';

        $this->db->order_by($orderColumn, $orderDir);
        $this->db->limit($params['length'], $params['start']);

        $query = $this->db->get();
        // dd($this->db->last_query());
        return [
            'query' => $query->result(),
            'count_total' => $count_total,
            'count_filtered' => $count_filtered,
        ];
    }

    protected function buildBaseSelect(array $filters = []): void
    {
        $this->db->select('
            mahasiswa.id,
            mahasiswa.nama,
            mahasiswa.nim,
            prodi.nama AS nama_prodi,
            sekolah.nama AS nama_sekolah,
            program.nama AS nama_program,
            program.tahun_ajaran
        ');
        $this->db->from('program_mahasiswa pm');
        $this->db->join('mahasiswa', 'mahasiswa.id = pm.id_mahasiswa', 'inner');
        $this->db->join('prodi', 'prodi.id = mahasiswa.id_prodi', 'left');
        $this->db->join('sekolah', 'sekolah.id = pm.id_sekolah', 'left');
        $this->db->join('program', 'program.id = pm.id_program', 'inner');

        $this->db->where('pm.status', 'verified');

        if (!empty($filters['program_id'])) {
            $this->db->where('pm.id_program', (int) $filters['program_id']);
        }

        if (!empty($filters['prodi_ids']) && is_array($filters['prodi_ids'])) {
            $this->db->where_in('mahasiswa.id_prodi', array_unique(array_map('intval', $filters['prodi_ids'])));
        }

    }
}
