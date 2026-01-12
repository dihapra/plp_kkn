<?php

namespace UseCases\Kaprodi;

defined('BASEPATH') or exit('No direct script access allowed');

class PlottingCase extends BaseCase
{
    public function getData(): array
    {
        $program = $this->getActiveProgram();
        $programId = (int) $program['id'];

        $allowedProdiIds = $this->getAllowedProdiIds();

        $dosen = $this->getDosenList($programId, $allowedProdiIds);
        $sekolah = $this->getSekolahList($programId);
        $mahasiswa = $this->getMahasiswaList($programId, $allowedProdiIds);
        $rows = $this->getPlottingRows($programId, $allowedProdiIds);

        return [
            'program' => $program,
            'dosen' => $dosen,
            'sekolah' => $sekolah,
            'mahasiswa' => $mahasiswa,
            'rows' => $rows,
        ];
    }

    public function getUnassignedCounts(): array
    {
        $program = $this->getActiveProgram();
        $programId = (int) $program['id'];
        $allowedProdiIds = $this->getAllowedProdiIds();

        return [
            'unassigned_dosen' => $this->countUnassignedDosen($programId, $allowedProdiIds),
            'unassigned_mahasiswa' => $this->countUnassignedMahasiswa($programId, $allowedProdiIds),
        ];
    }

    public function savePlotting(int $dosenId, int $sekolahId, array $studentIds, ?int $currentDosenId = null): void
    {
        if ($dosenId <= 0 || $sekolahId <= 0 || empty($studentIds)) {
            throw new \InvalidArgumentException('Dosen, sekolah, dan mahasiswa wajib dipilih.');
        }

        $program = $this->getActiveProgram();
        $programId = (int) $program['id'];

        $allowedProdiIds = $this->getAllowedProdiIds();

        if (!$this->isDosenInProgram($programId, $dosenId)) {
            throw new \InvalidArgumentException('Dosen tidak terdaftar pada program aktif.');
        }

        if (!$this->isSekolahInProgram($programId, $sekolahId)) {
            throw new \InvalidArgumentException('Sekolah tidak terdaftar pada program aktif.');
        }

        $studentIds = array_values(array_unique(array_map('intval', $studentIds)));
        $studentRows = $this->CI->db
            ->select('pm.id_mahasiswa, mahasiswa.id_prodi, pm.id_dosen')
            ->from('program_mahasiswa pm')
            ->join('mahasiswa', 'mahasiswa.id = pm.id_mahasiswa', 'inner')
            ->where('pm.id_program', $programId)
            ->where_in('pm.id_mahasiswa', $studentIds)
            ->get()
            ->result();

        if (count($studentRows) !== count($studentIds)) {
            throw new \InvalidArgumentException('Sebagian mahasiswa tidak ditemukan pada program aktif.');
        }

        if (!empty($allowedProdiIds)) {
            foreach ($studentRows as $row) {
                if (!in_array((int) $row->id_prodi, $allowedProdiIds, true)) {
                    throw new \InvalidArgumentException('Mahasiswa di luar prodi Anda tidak dapat dipilih.');
                }
            }
        }

        $currentDosenId = $currentDosenId && $currentDosenId > 0 ? $currentDosenId : null;

        $alreadyAssigned = $this->CI->db
            ->select('id_mahasiswa, id_dosen')
            ->from('program_mahasiswa')
            ->where('id_program', $programId)
            ->where_in('id_mahasiswa', $studentIds)
            ->where('id_dosen IS NOT NULL', null, false)
            ->get()
            ->result();

        foreach ($alreadyAssigned as $row) {
            $existingDosen = (int) $row->id_dosen;
            if ($currentDosenId && $existingDosen === $currentDosenId) {
                continue;
            }
            if ($existingDosen !== $dosenId) {
                throw new \InvalidArgumentException('Mahasiswa yang dipilih sudah terplotting.');
            }
        }

        if (!$currentDosenId || $currentDosenId !== $dosenId) {
            $existingForDosen = $this->CI->db
                ->select('id')
                ->from('program_mahasiswa')
                ->where('id_program', $programId)
                ->where('id_dosen', $dosenId)
                ->limit(1)
                ->get()
                ->row();
            if ($existingForDosen) {
                throw new \InvalidArgumentException('Dosen sudah terplotting. Pilih dosen lain.');
            }
        }

        $this->CI->db->trans_begin();

        try {
            if ($currentDosenId) {
                $this->CI->db
                    ->where('id_program', $programId)
                    ->where('id_dosen', $currentDosenId)
                    ->update('program_mahasiswa', [
                        'id_dosen' => null,
                        'id_sekolah' => null,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                $this->removeKelompokForDosen($programId, $currentDosenId);
            }

            $this->CI->db
                ->where('id_program', $programId)
                ->where_in('id_mahasiswa', $studentIds)
                ->update('program_mahasiswa', [
                    'id_dosen' => $dosenId,
                    'id_sekolah' => $sekolahId,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            $this->syncKelompokForPlotting($programId, $dosenId, $sekolahId, $studentIds);

            if ($this->CI->db->trans_status() === false) {
                throw new \RuntimeException('Gagal menyimpan plotting.');
            }

            $this->CI->db->trans_commit();
        } catch (\Throwable $e) {
            $this->CI->db->trans_rollback();
            throw $e;
        }
    }

    public function deletePlotting(int $dosenId): void
    {
        if ($dosenId <= 0) {
            throw new \InvalidArgumentException('ID dosen tidak valid.');
        }

        $program = $this->getActiveProgram();
        $programId = (int) $program['id'];

        $this->CI->db
            ->where('id_program', $programId)
            ->where('id_dosen', $dosenId)
            ->update('program_mahasiswa', [
                'id_dosen' => null,
                'id_sekolah' => null,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        $this->removeKelompokForDosen($programId, $dosenId);
    }

    private function syncKelompokForPlotting(int $programId, int $dosenId, int $sekolahId, array $studentIds): void
    {
        $programDosen = $this->CI->db
            ->select('id')
            ->from('program_dosen')
            ->where('id_program', $programId)
            ->where('id_dosen', $dosenId)
            ->limit(1)
            ->get()
            ->row();

        $programSekolah = $this->CI->db
            ->select('id')
            ->from('program_sekolah')
            ->where('id_program', $programId)
            ->where('id_sekolah', $sekolahId)
            ->limit(1)
            ->get()
            ->row();

        if (!$programDosen || !$programSekolah) {
            return;
        }

        $this->removeKelompokByProgramDosenId($programId, (int) $programDosen->id);

        $existingCount = (int) $this->CI->db
            ->from('program_kelompok')
            ->where('id_program', $programId)
            ->where('id_program_sekolah', (int) $programSekolah->id)
            ->count_all_results();

        $schoolRow = $this->CI->db
            ->select('nama')
            ->from('sekolah')
            ->where('id', $sekolahId)
            ->limit(1)
            ->get()
            ->row();

        $schoolName = $schoolRow ? trim((string) $schoolRow->nama) : 'Kelompok';
        $groupIndex = $existingCount + 1;
        $groupName = trim($schoolName . ' ' . $groupIndex);
        if ($groupName === '') {
            $groupName = 'Kelompok ' . $groupIndex;
        }

        $now = date('Y-m-d H:i:s');
        $userId = $this->CI->session->userdata('id_user');

        $this->CI->db->insert('program_kelompok', [
            'id_program' => $programId,
            'nama_kelompok' => $groupName,
            'id_program_dosen' => (int) $programDosen->id,
            'id_program_sekolah' => (int) $programSekolah->id,
            'created_at' => $now,
            'updated_at' => $now,
            'created_by' => $userId ? (int) $userId : null,
            'updated_by' => $userId ? (int) $userId : null,
        ]);

        $kelompokId = (int) $this->CI->db->insert_id();
        if ($kelompokId <= 0) {
            return;
        }

        if (empty($studentIds)) {
            return;
        }

        $pmRows = $this->CI->db
            ->select('id')
            ->from('program_mahasiswa')
            ->where('id_program', $programId)
            ->where_in('id_mahasiswa', $studentIds)
            ->get()
            ->result();

        if (empty($pmRows)) {
            return;
        }

        $anggotaRows = [];
        foreach ($pmRows as $pmRow) {
            $anggotaRows[] = [
                'id_program_kelompok' => $kelompokId,
                'id_program_mahasiswa' => (int) $pmRow->id,
                'created_at' => $now,
            ];
        }

        $this->CI->db->insert_batch('program_kelompok_anggota', $anggotaRows);
    }

    private function removeKelompokForDosen(int $programId, int $dosenId): void
    {
        $programDosen = $this->CI->db
            ->select('id')
            ->from('program_dosen')
            ->where('id_program', $programId)
            ->where('id_dosen', $dosenId)
            ->limit(1)
            ->get()
            ->row();

        if (!$programDosen) {
            return;
        }

        $this->removeKelompokByProgramDosenId($programId, (int) $programDosen->id);
    }

    private function removeKelompokByProgramDosenId(int $programId, int $programDosenId): void
    {
        $kelompokRows = $this->CI->db
            ->select('id')
            ->from('program_kelompok')
            ->where('id_program', $programId)
            ->where('id_program_dosen', $programDosenId)
            ->get()
            ->result();

        if (empty($kelompokRows)) {
            return;
        }

        $kelompokIds = array_map(function ($row) {
            return (int) $row->id;
        }, $kelompokRows);

        $this->CI->db
            ->where_in('id_program_kelompok', $kelompokIds)
            ->delete('program_kelompok_anggota');

        $this->CI->db
            ->where_in('id', $kelompokIds)
            ->delete('program_kelompok');
    }

    private function getActiveProgram(): array
    {
        $row = $this->CI->db
            ->select('id, kode, nama, tahun_ajaran')
            ->from('program')
            ->where('active', 1)
            ->order_by('updated_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        if (!$row) {
            throw new \RuntimeException('Belum ada program aktif.');
        }

        return $row;
    }

    private function isDosenInProgram(int $programId, int $dosenId): bool
    {
        $row = $this->CI->db
            ->select('id')
            ->from('program_dosen')
            ->where('id_program', $programId)
            ->where('id_dosen', $dosenId)
            ->limit(1)
            ->get()
            ->row();

        return (bool) $row;
    }

    private function isSekolahInProgram(int $programId, int $sekolahId): bool
    {
        $row = $this->CI->db
            ->select('id')
            ->from('program_sekolah')
            ->where('id_program', $programId)
            ->where('id_sekolah', $sekolahId)
            ->limit(1)
            ->get()
            ->row();

        return (bool) $row;
    }

    private function countUnassignedDosen(int $programId, array $allowedProdiIds): int
    {
        $builder = $this->CI->db
            ->select('dosen.id')
            ->from('program_dosen pd')
            ->join('dosen', 'dosen.id = pd.id_dosen', 'inner')
            ->join(
                'program_mahasiswa pm',
                'pm.id_program = pd.id_program AND pm.id_dosen = dosen.id AND pm.id_sekolah IS NOT NULL',
                'left',
                false
            )
            ->where('pd.id_program', $programId)
            ->group_by('dosen.id')
            ->having('COUNT(pm.id) = 0', null, false);

        if (!empty($allowedProdiIds)) {
            $builder->where_in('dosen.id_prodi', $allowedProdiIds);
        }

        return (int) $builder->get()->num_rows();
    }

    private function countUnassignedMahasiswa(int $programId, array $allowedProdiIds): int
    {
        $builder = $this->CI->db
            ->from('program_mahasiswa pm')
            ->join('mahasiswa', 'mahasiswa.id = pm.id_mahasiswa', 'inner')
            ->where('pm.id_program', $programId)
            ->where('(pm.id_dosen IS NULL OR pm.id_sekolah IS NULL)', null, false);

        if (!empty($allowedProdiIds)) {
            $builder->where_in('mahasiswa.id_prodi', $allowedProdiIds);
        }

        return (int) $builder->count_all_results();
    }

    private function getDosenList(int $programId, array $allowedProdiIds): array
    {
        $builder = $this->CI->db
            ->select('
                dosen.id,
                dosen.nama,
                prodi.nama AS prodi,
                COUNT(DISTINCT pm.id) AS terisi
            ')
            ->from('program_dosen pd')
            ->join('dosen', 'dosen.id = pd.id_dosen', 'inner')
            ->join('prodi', 'prodi.id = dosen.id_prodi', 'left')
            ->join('program_mahasiswa pm', 'pm.id_program = pd.id_program AND pm.id_dosen = dosen.id', 'left')
            ->where('pd.id_program', $programId)
            ->group_by('dosen.id')
            ->order_by('dosen.nama', 'ASC');

        if (!empty($allowedProdiIds)) {
            $builder->where_in('dosen.id_prodi', $allowedProdiIds);
        }

        $rows = $builder->get()->result_array();

        return array_map(function ($row) {
            $terisi = (int) ($row['terisi'] ?? 0);
            return [
                'id' => (int) $row['id'],
                'nama' => $row['nama'] ?? '',
                'prodi' => $row['prodi'] ?? '',
                'kuota' => [
                    'terisi' => $terisi,
                    'total' => $terisi,
                ],
            ];
        }, $rows);
    }

    private function getSekolahList(int $programId): array
    {
        $rows = $this->CI->db
            ->select('
                sekolah.id,
                sekolah.nama,
                COUNT(DISTINCT pm.id) AS terisi
            ')
            ->from('program_sekolah ps')
            ->join('sekolah', 'sekolah.id = ps.id_sekolah', 'inner')
            ->join('program_mahasiswa pm', 'pm.id_program = ps.id_program AND pm.id_sekolah = sekolah.id', 'left')
            ->where('ps.id_program', $programId)
            ->group_by('sekolah.id')
            ->order_by('sekolah.nama', 'ASC')
            ->get()
            ->result_array();

        return array_map(function ($row) {
            $terisi = (int) ($row['terisi'] ?? 0);
            return [
                'id' => (int) $row['id'],
                'nama' => $row['nama'] ?? '',
                'kuota' => [
                    'terisi' => $terisi,
                    'total' => $terisi,
                ],
            ];
        }, $rows);
    }

    private function getMahasiswaList(int $programId, array $allowedProdiIds): array
    {
        $builder = $this->CI->db
            ->select('
                mahasiswa.id,
                mahasiswa.nama,
                mahasiswa.id_prodi,
                prodi.nama AS prodi,
                pm.id_dosen,
                pm.id_sekolah
            ')
            ->from('program_mahasiswa pm')
            ->join('mahasiswa', 'mahasiswa.id = pm.id_mahasiswa', 'inner')
            ->join('prodi', 'prodi.id = mahasiswa.id_prodi', 'left')
            ->where('pm.id_program', $programId)
            ->order_by('mahasiswa.nama', 'ASC');

        if (!empty($allowedProdiIds)) {
            $builder->where_in('mahasiswa.id_prodi', $allowedProdiIds);
        }

        $rows = $builder->get()->result_array();

        return array_map(function ($row) {
            return [
                'id' => (int) $row['id'],
                'nama' => $row['nama'] ?? '',
                'prodi' => $row['prodi'] ?? '',
                'id_prodi' => isset($row['id_prodi']) ? (int) $row['id_prodi'] : null,
                'id_dosen' => isset($row['id_dosen']) ? (int) $row['id_dosen'] : null,
                'id_sekolah' => isset($row['id_sekolah']) ? (int) $row['id_sekolah'] : null,
            ];
        }, $rows);
    }

    private function getPlottingRows(int $programId, array $allowedProdiIds): array
    {
        $builder = $this->CI->db
            ->select('
                pm.id_dosen,
                pm.id_sekolah,
                dosen.nama AS dosen_nama,
                prodi_dosen.nama AS dosen_prodi,
                sekolah.nama AS sekolah_nama,
                mahasiswa.id AS mahasiswa_id,
                mahasiswa.nama AS mahasiswa_nama,
                prodi_mhs.nama AS mahasiswa_prodi
            ')
            ->from('program_mahasiswa pm')
            ->join('mahasiswa', 'mahasiswa.id = pm.id_mahasiswa', 'inner')
            ->join('prodi prodi_mhs', 'prodi_mhs.id = mahasiswa.id_prodi', 'left')
            ->join('dosen', 'dosen.id = pm.id_dosen', 'inner')
            ->join('prodi prodi_dosen', 'prodi_dosen.id = dosen.id_prodi', 'left')
            ->join('sekolah', 'sekolah.id = pm.id_sekolah', 'inner')
            ->where('pm.id_program', $programId)
            ->where('pm.id_dosen IS NOT NULL', null, false)
            ->where('pm.id_sekolah IS NOT NULL', null, false)
            ->order_by('dosen.nama', 'ASC')
            ->order_by('mahasiswa.nama', 'ASC');

        if (!empty($allowedProdiIds)) {
            $builder->where_in('mahasiswa.id_prodi', $allowedProdiIds);
        }

        $rows = $builder->get()->result_array();

        $grouped = [];
        foreach ($rows as $row) {
            $dosenId = (int) $row['id_dosen'];
            $schoolId = (int) $row['id_sekolah'];
            $key = $dosenId . '-' . $schoolId;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'dosen_id' => $dosenId,
                    'dosen_nama' => $row['dosen_nama'] ?? '',
                    'dosen_prodi' => $row['dosen_prodi'] ?? '',
                    'school_id' => $schoolId,
                    'school_nama' => $row['sekolah_nama'] ?? '',
                    'student_ids' => [],
                    'students' => [],
                ];
            }

            $studentId = (int) $row['mahasiswa_id'];
            $grouped[$key]['student_ids'][] = $studentId;
            $grouped[$key]['students'][] = [
                'id' => $studentId,
                'nama' => $row['mahasiswa_nama'] ?? '',
                'prodi' => $row['mahasiswa_prodi'] ?? '',
            ];
        }

        return array_values($grouped);
    }
}
