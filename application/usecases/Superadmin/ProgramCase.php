<?php

namespace UseCases\Superadmin;

use UseCases\Superadmin\BaseCase;

defined('BASEPATH') or exit('No direct script access allowed');
class ProgramCase extends BaseCase
{

    public function create(array $payload)
    {
        $data = $this->normalizeAndValidate($payload);
        $this->ProgramRepository->create($data);
    }

    public function update(int $id, array $payload)
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID program tidak valid.');
        }
        $data = $this->normalizeAndValidate($payload);
        $this->ProgramRepository->update($id, $data);
    }

    public function toggleStatus(int $id): int
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID program tidak valid.');
        }
        return $this->ProgramRepository->toggleActive($id);
    }

    public function datatable($params)
    {
        $result = $this->ProgramRepository->datatable($params);
        return [
            'formatted' => $this->formatter($result),
            'count_total' => $result['count_total'],
            'count_filtered' => $result['count_filtered'],
        ];
    }

    private function formatter($result)
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = [
                'id' => $r->id,
                'nama' => $r->nama,
                'tahun_ajaran' => $r->tahun_ajaran,
                'status' => $r->status? 'Aktif' : 'Tidak Aktif',
            ];
        }
        return $formatter;
    }

    private function normalizeAndValidate(array $input): array
    {
        $nama = isset($input['nama']) ? trim($input['nama']) : '';
        $tahun = isset($input['tahun_ajaran']) ? trim($input['tahun_ajaran']) : '';
        $statusRaw = isset($input['status']) ? (string) $input['status'] : '0';
        $status = $statusRaw === '1' ? 1 : 0; // default tidak aktif

        if ($nama === '' || $tahun === '') {
            throw new \InvalidArgumentException('Nama program dan tahun ajaran wajib diisi.');
        }

        return [
            'nama'         => $nama,
            'tahun_ajaran' => $tahun,
            'active'       => $status,
        ];
    }

    public function listForFilter(): array
    {
        return $this->ProgramRepository->list_simple();
    }

}
