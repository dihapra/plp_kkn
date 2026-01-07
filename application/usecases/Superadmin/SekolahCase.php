<?php

namespace UseCases\Superadmin;

defined('BASEPATH') or exit('No direct script access allowed');

class SekolahCase extends BaseCase
{
    public function create(array $payload): void
    {
        $normalized = $this->normalizeAndValidate($payload);
        $schoolId = $this->SekolahRepository->create($normalized['data']);
        if (!empty($normalized['program_id'])) {
            $this->SekolahRepository->ensure_program_relation($normalized['program_id'], $schoolId);
        }
    }

    public function update(int $id, array $payload): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID sekolah tidak valid.');
        }
        $normalized = $this->normalizeAndValidate($payload);
        $this->SekolahRepository->update($id, $normalized['data']);
        if (!empty($normalized['program_id'])) {
            $this->SekolahRepository->ensure_program_relation($normalized['program_id'], $id);
        }
    }

    public function datatable(array $params): array
    {
        $result = $this->SekolahRepository->datatable($params);

        return [
            'formatted'       => $this->formatter($result),
            'count_total'     => $result['count_total'],
            'count_filtered'  => $result['count_filtered'],
        ];
    }

    public function datatableByProgram(array $params, int $programId): array
    {
        $result = $this->SekolahRepository->datatable_by_program($params, $programId);

        return [
            'formatted'      => $this->formatter($result),
            'count_total'    => $result['count_total'],
            'count_filtered' => $result['count_filtered'],
        ];
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID sekolah tidak valid.');
        }

        $this->SekolahRepository->delete($id);
    }

    public function deleteByProgram(int $sekolahId, int $programId): void
    {
        if ($sekolahId <= 0 || $programId <= 0) {
            throw new \InvalidArgumentException('ID sekolah atau program tidak valid.');
        }

        $this->SekolahRepository->delete_program_relation($programId, $sekolahId);
    }

    private function formatter(array $result): array
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = [
                'id'     => $r->id,
                'nama'   => $r->nama,
                'alamat' => $r->alamat,
                'id_program' => $r->id_program ?? null,
            ];
        }
        return $formatter;
    }

    public function listForFilter(): array
    {
        return $this->SekolahRepository->list_simple();
    }

    private function normalizeAndValidate(array $input): array
    {
        $nama   = isset($input['nama']) ? trim($input['nama']) : '';
        $alamat = isset($input['alamat']) ? trim($input['alamat']) : '';
        $idProgram = isset($input['id_program']) && $input['id_program'] !== '' ? (int) $input['id_program'] : null;

        if ($nama === '') {
            throw new \InvalidArgumentException('Nama sekolah wajib diisi.');
        }

        $data = [
            'nama'       => $nama,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $data['alamat'] = $alamat !== '' ? $alamat : null;

        return [
            'data' => $data,
            'program_id' => !empty($idProgram) ? $idProgram : null,
        ];
    }
}
