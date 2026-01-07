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
            pm.status AS status,
            prodi.nama AS nama_prodi,
            prodi.fakultas AS fakultas,
            sekolah.nama AS nama_sekolah,
            pm.id AS id_program_mahasiswa,
            pm.id_program,
            program.nama AS nama_program,
            program.kode AS kode_program,
            program.tahun_ajaran
        ');
        $this->db->from('mahasiswa');
        $this->db->join('prodi', 'prodi.id = mahasiswa.id_prodi', 'left');
        $this->db->join(
            'program_mahasiswa pm',
            'pm.id = (SELECT MAX(pm2.id) FROM program_mahasiswa pm2 WHERE pm2.id_mahasiswa = mahasiswa.id)',
            'left',
            false
        );
        $this->db->join('sekolah', 'sekolah.id = pm.id_sekolah', 'left');
        $this->db->join('program', 'program.id = pm.id_program', 'left');

        if (!empty($params['filter_program_type'])) {
            $this->db->where('program.kode', $params['filter_program_type']);
        }

        if (!empty($params['filter_program'])) {
            $this->db->where('pm.id_program', (int) $params['filter_program']);
        }

        if (!empty($params['filter_status']) && $params['filter_status'] !== 'all') {
            $status = $params['filter_status'];
            if ($status === 'unverified') {
                $this->db->where(
                    '(pm.status IS NULL OR pm.status = ' . $this->db->escape($status) . ')',
                    null,
                    false
                );
            } else {
                $this->db->where('pm.status', $status);
            }
        }

        $count_total = $this->db->count_all_results('', false);

        $search_columns = [
            'mahasiswa.nama',
            'mahasiswa.nim',
            'mahasiswa.email',
            'prodi.nama',
            'sekolah.nama',
            'program.nama',
            'program.kode',
            'program.tahun_ajaran',
        ];
        $this->applySearch($params['search'], $search_columns);

        $count_filtered = $this->db->count_all_results('', false);

        $orderMap = [
            'nama' => 'mahasiswa.nama',
            'nim' => 'mahasiswa.nim',
            'email' => 'mahasiswa.email',
            'no_hp' => 'mahasiswa.no_hp',
            'nama_prodi' => 'prodi.nama',
            'nama_sekolah' => 'sekolah.nama',
            'nama_program' => 'program.nama',
            'kode_program' => 'program.kode',
            'tahun_ajaran' => 'program.tahun_ajaran',
            'status' => 'pm.status',
        ];
        $orderColumn = $orderMap[$params['order_column']] ?? 'mahasiswa.nama';
        $orderDir = strtolower((string) $params['order_dir']) === 'desc' ? 'desc' : 'asc';
        $this->db->limit($params['length'], $params['start']);
        $this->db->order_by($orderColumn, $orderDir);

        $query = $this->db->get();

        return [
            'query'          => $query->result(),
            'count_total'    => $count_total,
            'count_filtered' => $count_filtered,
        ];
    }

    public function datatableMasterData(array $params): array
    {
        $this->db->select('
            mahasiswa.id,
            mahasiswa.nama AS nama_mahasiswa,
            mahasiswa.nim,
            mahasiswa.email,
            mahasiswa.no_hp,
            pm.status AS status,
            prodi.nama AS nama_prodi,
            prodi.fakultas AS fakultas,
            sekolah.nama AS nama_sekolah,
            guru.nama AS nama_guru,
            dosen.nama AS nama_dosen,
            program.kode AS kode_program,
            program.nama AS nama_program,
            program.tahun_ajaran
        ');
        $this->db->from('mahasiswa');
        $this->db->join('prodi', 'prodi.id = mahasiswa.id_prodi', 'left');
        $this->db->join(
            'program_mahasiswa pm',
            'pm.id = (SELECT MAX(pm2.id) FROM program_mahasiswa pm2 WHERE pm2.id_mahasiswa = mahasiswa.id)',
            'left',
            false
        );
        $this->db->join('program', 'program.id = pm.id_program', 'left');
        $this->db->join('sekolah', 'sekolah.id = pm.id_sekolah', 'left');
        $this->db->join('guru', 'guru.id = pm.id_guru', 'left');
        $this->db->join('dosen', 'dosen.id = pm.id_dosen', 'left');

        if (!empty($params['filter_program_code'])) {
            $this->db->where('program.kode', $params['filter_program_code']);
        }

        if (!empty($params['filter_program_id'])) {
            $this->db->where('pm.id_program', (int) $params['filter_program_id']);
        }

        $count_total = $this->db->count_all_results('', false);

        $search_columns = [
            'mahasiswa.nama',
            'mahasiswa.nim',
            'mahasiswa.email',
            'sekolah.nama',
            'guru.nama',
            'dosen.nama',
            'prodi.nama',
            'prodi.fakultas',
        ];
        $this->applySearch($params['search'], $search_columns);

        $count_filtered = $this->db->count_all_results('', false);

        $orderMap = [
            'school_name'   => 'sekolah.nama',
            'teacher_name'  => 'guru.nama',
            'lecturer_name' => 'dosen.nama',
            'student_name'  => 'mahasiswa.nama',
            'nim'           => 'mahasiswa.nim',
            'program_studi' => 'prodi.nama',
            'fakultas'      => 'prodi.fakultas',
            'email'         => 'mahasiswa.email',
            'phone'         => 'mahasiswa.no_hp',
            'status'        => 'pm.status',
        ];

        $orderColumn = $orderMap[$params['order_column']] ?? 'mahasiswa.nama';
        $orderDir = strtolower((string) $params['order_dir']) === 'desc' ? 'desc' : 'asc';

        $this->db->order_by($orderColumn, $orderDir);
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

    public function findDetail(int $id)
    {
        return $this->db
            ->select('
                mahasiswa.*,
                prodi.nama AS nama_prodi,
                prodi.fakultas AS fakultas,
                pm.id AS id_program_mahasiswa,
                pm.id_program AS id_program,
                pm.status AS status,
                program.nama AS nama_program,
                program.kode AS kode_program,
                program.tahun_ajaran
            ')
            ->from('mahasiswa')
            ->join('prodi', 'prodi.id = mahasiswa.id_prodi', 'left')
            ->join(
                'program_mahasiswa pm',
                'pm.id = (SELECT MAX(pm2.id) FROM program_mahasiswa pm2 WHERE pm2.id_mahasiswa = mahasiswa.id)',
                'left',
                false
            )
            ->join('program', 'program.id = pm.id_program', 'left')
            ->where('mahasiswa.id', $id)
            ->limit(1)
            ->get()
            ->row();
    }

    public function getSyaratMapelByMahasiswa(int $mahasiswaId): ?array
    {
        $row = $this->db
            ->select('syarat_mapel.*')
            ->from('program_mahasiswa pm')
            ->join('syarat_mapel', 'syarat_mapel.id_program_mahasiswa = pm.id', 'inner')
            ->where('pm.id_mahasiswa', $mahasiswaId)
            ->order_by('pm.valid_from', 'DESC')
            ->order_by('syarat_mapel.updated_at', 'DESC')
            ->order_by('syarat_mapel.id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        return $row ?: null;
    }
}
