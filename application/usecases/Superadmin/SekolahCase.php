<?php

namespace UseCases\Superadmin;

defined('BASEPATH') or exit('No direct script access allowed');

class SekolahCase extends BaseCase
{
    public function create(array $payload): void
    {
        $data = $this->normalizeAndValidate($payload);
        $this->SekolahRepository->create($data);
    }

    public function update(int $id, array $payload): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID sekolah tidak valid.');
        }
        $data = $this->normalizeAndValidate($payload);
        $this->SekolahRepository->update($id, $data);
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

    private function formatter(array $result): array
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = [
                'id'     => $r->id,
                'nama'   => $r->nama,
                'alamat' => $r->alamat,
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

        if ($nama === '' || $alamat === '') {
            throw new \InvalidArgumentException('Nama sekolah dan alamat wajib diisi.');
        }

        return [
            'nama'       => $nama,
            'alamat'     => $alamat,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
