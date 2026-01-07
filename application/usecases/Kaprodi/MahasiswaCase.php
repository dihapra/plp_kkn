<?php

namespace UseCases\Kaprodi;

use Repositories\Kaprodi\Mahasiswa as MahasiswaRepository;

defined('BASEPATH') or exit('No direct script access allowed');

class MahasiswaCase extends BaseCase
{
    /** @var MahasiswaRepository */
    protected $MahasiswaRepository;

    public function __construct()
    {
        parent::__construct();
        $this->MahasiswaRepository = new MahasiswaRepository();
    }

    public function datatable(array $params, array $filters = []): array
    {
        $filterParams = $this->buildFilter($filters);
        $result = $this->MahasiswaRepository->datatable($params, $filterParams);

        return [
            'formatted' => $this->formatter($result),
            'count_total' => $result['count_total'],
            'count_filtered' => $result['count_filtered'],
        ];
    }

    protected function buildFilter(array $filters): array
    {
        $programId = $this->getActiveProgramId();
        $allowedProdiIds = $this->getAllowedProdiIds();

        $result = [
            'program_id' => $programId,
        ];

        if (!empty($allowedProdiIds)) {
            $result['prodi_ids'] = $allowedProdiIds;
        }

        return $result;
    }

    protected function formatter(array $result): array
    {
        $data = [];
        if (empty($result['query'])) {
            return $data;
        }

        foreach ($result['query'] as $row) {
            $data[] = [
                'id' => (int) $row->id,
                'nama' => $row->nama ?? '',
                'nim' => $row->nim ?? '',
                'prodi' => $row->nama_prodi ?? '',
                'sekolah' => $row->nama_sekolah ?? '-',
                'program_aktif' => trim(($row->nama_program ?? '') . ' ' . ($row->tahun_ajaran ?? '')),
            ];
        }

        return $data;
    }

    private function getActiveProgramId(): int
    {
        $row = $this->CI->db
            ->select('id')
            ->from('program')
            ->where('active', 1)
            ->order_by('updated_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if (!$row) {
            throw new \RuntimeException('Belum ada program aktif.');
        }

        return (int) $row->id;
    }
}
