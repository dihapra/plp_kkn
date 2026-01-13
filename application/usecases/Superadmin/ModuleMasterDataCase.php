<?php

namespace UseCases\Superadmin;

defined('BASEPATH') or exit('No direct script access allowed');

class ModuleMasterDataCase extends BaseCase
{
    private $entityConfig = [
        'sekolah' => [
            'searchable'   => ['school_name', 'alamat'],
            'default_order'=> 'school_name',
        ],
        'dosen' => [
            'searchable'   => ['lecturer_name', 'email', 'phone', 'nama_prodi'],
            'default_order'=> 'lecturer_name',
        ],
        'mahasiswa' => [
            'searchable'   => ['student_name', 'nim', 'email', 'phone', 'program_studi', 'fakultas', 'status'],
            'default_order'=> 'student_name',
        ],
        'guru' => [
            'searchable'   => ['teacher_name', 'school_name', 'email', 'phone'],
            'default_order'=> 'teacher_name',
        ],
        'kepsek' => [
            'searchable'   => ['principal_name', 'school_name', 'email', 'phone', 'status_pembayaran'],
            'default_order'=> 'principal_name',
        ],
    ];

    public function datatableByEntity(string $entity, array $params): array
    {
        if (!array_key_exists($entity, $this->entityConfig)) {
            throw new \InvalidArgumentException('Entity master data tidak dikenali.');
        }

        $programId = (int) ($params['filter_program_id'] ?? 0);
        if ($programId <= 0) {
            return [
                'formatted'      => [],
                'count_total'    => 0,
                'count_filtered' => 0,
            ];
        }

        $rows = $this->collectRows($entity, $programId);
        $count_total = count($rows);

        $search = trim((string) ($params['search'] ?? ''));
        if ($search !== '') {
            $rows = $this->filterRows($rows, $entity, $search);
        }
        $count_filtered = count($rows);

        $orderColumn = $params['order_column'] ?: ($this->entityConfig[$entity]['default_order'] ?? null);
        $orderDir = strtolower($params['order_dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
        if ($orderColumn) {
            $rows = $this->sortRows($rows, $orderColumn, $orderDir);
        }

        $start = max(0, (int) ($params['start'] ?? 0));
        $length = (int) ($params['length'] ?? 10);
        if ($length <= 0) {
            $length = $count_filtered ?: 1;
        }

        $paged = array_slice(array_values($rows), $start, $length);

        return [
            'formatted'      => array_values($paged),
            'count_total'    => $count_total,
            'count_filtered' => $count_filtered,
        ];
    }

    private function filterRows(array $rows, string $entity, string $needle): array
    {
        $keys = $this->entityConfig[$entity]['searchable'] ?? [];
        if (empty($keys)) {
            return $rows;
        }

        $needle = mb_strtolower($needle);

        return array_filter($rows, function ($row) use ($keys, $needle) {
            foreach ($keys as $key) {
                if (!isset($row[$key])) {
                    continue;
                }
                $value = mb_strtolower((string) $row[$key]);
                if (strpos($value, $needle) !== false) {
                    return true;
                }
            }
            return false;
        });
    }

    private function sortRows(array $rows, string $key, string $direction): array
    {
        if (empty($rows) || !array_key_exists($key, $rows[0])) {
            return $rows;
        }

        usort($rows, function ($a, $b) use ($key, $direction) {
            $valueA = $a[$key] ?? null;
            $valueB = $b[$key] ?? null;

            if (is_numeric($valueA) && is_numeric($valueB)) {
                $comparison = $valueA <=> $valueB;
            } else {
                $comparison = strcasecmp((string) $valueA, (string) $valueB);
            }

            return $direction === 'desc' ? -$comparison : $comparison;
        });

        return $rows;
    }

    private function collectRows(string $entity, int $programId): array
    {
        switch ($entity) {
            case 'sekolah':
                return $this->collectSchools($programId);
            case 'dosen':
                return $this->collectLecturers($programId);
            case 'mahasiswa':
                return $this->collectStudents($programId);
            case 'guru':
                return $this->collectTeachers($programId);
            case 'kepsek':
                return $this->collectPrincipals($programId);
            default:
                return [];
        }
    }

    private function collectSchools(int $programId): array
    {
        $rows = $this->CI->db->select('
                sekolah.id,
                sekolah.nama AS school_name,
                sekolah.alamat,
                COUNT(DISTINCT pm.id) AS total_students,
                COUNT(DISTINCT pg.id_guru) AS total_teachers,
                COUNT(DISTINCT pk.id_kepsek) AS total_principals
            ')
            ->from('program_sekolah ps')
            ->join('sekolah', 'sekolah.id = ps.id_sekolah', 'inner')
            ->join('program_mahasiswa pm', 'pm.id_program = ps.id_program AND pm.id_sekolah = sekolah.id', 'left')
            ->join('program_guru pg', 'pg.id_program = ps.id_program AND pg.id_program_sekolah = ps.id', 'left')
            ->join('program_kepsek pk', 'pk.id_program = ps.id_program AND pk.id_program_sekolah = ps.id', 'left')
            ->where('ps.id_program', $programId)
            ->group_by('sekolah.id')
            ->order_by('sekolah.nama', 'ASC')
            ->get()
            ->result_array();

        return array_map(function ($row) {
            $row['total_students']   = (int) ($row['total_students'] ?? 0);
            $row['total_teachers']   = (int) ($row['total_teachers'] ?? 0);
            $row['total_principals'] = (int) ($row['total_principals'] ?? 0);
            return $row;
        }, $rows);
    }

    private function collectLecturers(int $programId): array
    {
        $rows = $this->CI->db->select('
                dosen.id,
                dosen.nama AS lecturer_name,
                dosen.email,
                dosen.no_hp AS phone,
                dosen.fakultas,
                prodi.nama AS nama_prodi,
                COUNT(pm.id) AS total_students
            ')
            ->from('program_dosen pd')
            ->join('dosen', 'dosen.id = pd.id_dosen', 'inner')
            ->join('prodi', 'prodi.id = dosen.id_prodi', 'left')
            ->join('program_mahasiswa pm', 'pm.id_program = pd.id_program AND pm.id_dosen = dosen.id', 'left')
            ->where('pd.id_program', $programId)
            ->group_by('dosen.id')
            ->order_by('dosen.nama', 'ASC')
            ->get()
            ->result_array();

        return array_map(function ($row) {
            $row['total_students'] = (int) ($row['total_students'] ?? 0);
            return $row;
        }, $rows);
    }

    private function collectStudents(int $programId): array
    {
        return $this->CI->db->select('
                mahasiswa.id,
                mahasiswa.nama AS student_name,
                mahasiswa.nim,
                mahasiswa.email,
                mahasiswa.no_hp AS phone,
                pm.status AS status,
                prodi.nama AS program_studi,
                prodi.fakultas
            ')
            ->from('program_mahasiswa pm')
            ->join('mahasiswa', 'mahasiswa.id = pm.id_mahasiswa', 'inner')
            ->join('prodi', 'prodi.id = mahasiswa.id_prodi', 'left')
            ->where('pm.id_program', $programId)
            ->order_by('mahasiswa.nama', 'ASC')
            ->get()
            ->result_array();
    }

    private function collectTeachers(int $programId): array
    {
        $rows = $this->CI->db->select('
                guru.id,
                guru.nama AS teacher_name,
                guru.email,
                guru.no_hp AS phone,
                MIN(sekolah.nama) AS school_name,
                COUNT(pm.id) AS total_students
            ')
            ->from('program_guru pg')
            ->join('guru', 'guru.id = pg.id_guru', 'inner')
            ->join('program_sekolah ps', 'ps.id = pg.id_program_sekolah', 'left')
            ->join('sekolah', 'sekolah.id = ps.id_sekolah', 'left')
            ->join('program_mahasiswa pm', 'pm.id_program = pg.id_program AND pm.id_guru = guru.id', 'left')
            ->where('pg.id_program', $programId)
            ->group_by('guru.id')
            ->order_by('guru.nama', 'ASC')
            ->get()
            ->result_array();

        return array_map(function ($row) {
            $row['total_students'] = (int) ($row['total_students'] ?? 0);
            return $row;
        }, $rows);
    }

    private function collectPrincipals(int $programId): array
    {
        return $this->CI->db->select('
                kepsek.id,
                kepsek.nama AS principal_name,
                kepsek.email,
                kepsek.no_hp AS phone,
                sekolah.nama AS school_name,
                pk.status_pembayaran
            ')
            ->from('program_kepsek pk')
            ->join('kepsek', 'kepsek.id = pk.id_kepsek', 'inner')
            ->join('program_sekolah ps', 'ps.id = pk.id_program_sekolah', 'left')
            ->join('sekolah', 'sekolah.id = ps.id_sekolah', 'left')
            ->where('pk.id_program', $programId)
            ->order_by('kepsek.nama', 'ASC')
            ->get()
            ->result_array();
    }
}
