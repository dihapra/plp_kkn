<?php

namespace UseCases\Superadmin;

defined('BASEPATH') or exit('No direct script access allowed');

class ProdiCase extends BaseCase
{
    public function create(array $payload): void
    {
        $data = $this->normalizeAndValidate($payload);
        $this->ProdiRepository->create($data);
    }

    public function update(int $id, array $payload): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID prodi tidak valid.');
        }
        $data = $this->normalizeAndValidate($payload);
        $this->ProdiRepository->update($id, $data);
    }

    public function datatable(array $params): array
    {
        $result = $this->ProdiRepository->datatable($params);

        return [
            'formatted'       => $this->formatter($result),
            'count_total'     => $result['count_total'],
            'count_filtered'  => $result['count_filtered'],
        ];
    }

    private function formatter(array $result): array
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = [
                'id'       => $r->id,
                'nama'     => $r->nama,
                'fakultas' => $r->fakultas,
            ];
        }
        return $formatter;
    }

    public function listForFilter(): array
    {
        return $this->ProdiRepository->list_simple();
    }

    public function listFakultas(): array
    {
        return $this->ProdiRepository->list_fakultas();
    }

    private function normalizeAndValidate(array $input): array
    {
        $nama     = isset($input['nama']) ? trim($input['nama']) : '';
        $fakultas = isset($input['fakultas']) ? trim($input['fakultas']) : '';

        if ($nama === '' || $fakultas === '') {
            throw new \InvalidArgumentException('Nama prodi dan fakultas wajib diisi.');
        }

        return [
            'nama'       => $nama,
            'fakultas'   => $fakultas,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
