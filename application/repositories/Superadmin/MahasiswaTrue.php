<?php

namespace Repositories\Superadmin;

require_once(APPPATH . 'traits/SearchTrait.php');

defined('BASEPATH') or exit('No direct script access allowed');

class MahasiswaTrue
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
            mahasiswa_true.id,
            mahasiswa_true.nama,
            mahasiswa_true.nim,
            mahasiswa_true.email,
            mahasiswa_true.no_hp,
            mahasiswa_true.id_prodi,
            mahasiswa_true.id_program,
            mahasiswa_true.created_at,
            mahasiswa_true.updated_at,
            prodi.nama AS nama_prodi,
            prodi.fakultas AS fakultas,
            program.nama AS nama_program,
            program.kode AS kode_program,
            program.tahun_ajaran AS tahun_ajaran_program
        ');
        $this->db->from('mahasiswa_true');
        $this->db->join('prodi', 'prodi.id = mahasiswa_true.id_prodi', 'left');
        $this->db->join('program', 'program.id = mahasiswa_true.id_program', 'left');

        if (!empty($params['filter_program'])) {
            $this->db->where('mahasiswa_true.id_program', (int) $params['filter_program']);
        }

        $count_total = $this->db->count_all_results('', false);

        $search_columns = [
            'mahasiswa_true.nama',
            'mahasiswa_true.nim',
            'mahasiswa_true.email',
            'mahasiswa_true.no_hp',
            'program.nama',
            'program.kode',
            'program.tahun_ajaran',
        ];
        $this->applySearch($params['search'], $search_columns);

        $count_filtered = $this->db->count_all_results('', false);

        $this->db->limit($params['length'], $params['start']);
        $orderColumn = $params['order_column'] ?: 'mahasiswa_true.nama';
        $orderDir = $params['order_dir'] ?: 'asc';
        $allowedColumns = [
            'nama'        => 'mahasiswa_true.nama',
            'nim'         => 'mahasiswa_true.nim',
            'email'       => 'mahasiswa_true.email',
            'no_hp'       => 'mahasiswa_true.no_hp',
            'nama_prodi'  => 'prodi.nama',
            'nama_program'=> 'program.nama',
            'created_at'  => 'mahasiswa_true.created_at',
            'updated_at'  => 'mahasiswa_true.updated_at',
        ];
        $orderBy = $allowedColumns[$orderColumn] ?? 'mahasiswa_true.nama';
        $orderDirection = strtolower($orderDir) === 'desc' ? 'desc' : 'asc';
        $this->db->order_by($orderBy, $orderDirection);

        $query = $this->db->get();

        return [
            'query'          => $query->result(),
            'count_total'    => $count_total,
            'count_filtered' => $count_filtered,
        ];
    }

    public function create(array $data): int
    {
        $this->db->insert('mahasiswa_true', $data);
        return (int) $this->db->insert_id();
    }

    public function update(int $id, array $data): void
    {
        $this->db->where('id', $id)->update('mahasiswa_true', $data);
    }

    public function delete(int $id): void
    {
        $this->db->where('id', $id)->delete('mahasiswa_true');
    }

    public function find(int $id)
    {
        return $this->db
            ->from('mahasiswa_true')
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function findByNim(string $nim, ?int $excludeId = null)
    {
        if ($nim === '') {
            return null;
        }

        $this->db
            ->select('
                mahasiswa_true.*,
                prodi.nama AS nama_prodi,
                prodi.fakultas AS fakultas,
                program.nama AS nama_program,
                program.kode AS kode_program,
                program.tahun_ajaran AS tahun_ajaran_program
            ')
            ->from('mahasiswa_true')
            ->join('prodi', 'prodi.id = mahasiswa_true.id_prodi', 'left')
            ->join('program', 'program.id = mahasiswa_true.id_program', 'left')
            ->where('mahasiswa_true.nim', $nim);

        if (!empty($excludeId)) {
            $this->db->where('mahasiswa_true.id !=', $excludeId);
        }

        return $this->db->limit(1)->get()->row();
    }
}
