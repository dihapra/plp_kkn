<?php

namespace UseCases\Superadmin;

defined('BASEPATH') or exit('No direct script access allowed');

class MahasiswaTrueCase extends BaseCase
{
    public function datatable(array $params): array
    {
        $result = $this->MahasiswaTrueRepository->datatable($params);

        return [
            'formatted'      => $this->formatter($result),
            'count_total'    => $result['count_total'],
            'count_filtered' => $result['count_filtered'],
        ];
    }

    public function create(array $payload): void
    {
        $data = $this->normalizeAndValidate($payload);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->getCurrentUserId();

        $this->MahasiswaTrueRepository->create($data);
    }

    public function update(int $id, array $payload): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID mahasiswa tidak valid.');
        }

        if (!$this->MahasiswaTrueRepository->find($id)) {
            throw new \InvalidArgumentException('Data mahasiswa tidak ditemukan.');
        }

        $data = $this->normalizeAndValidate($payload, $id);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $this->getCurrentUserId();

        $this->MahasiswaTrueRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID mahasiswa tidak valid.');
        }

        $this->MahasiswaTrueRepository->delete($id);
    }

    private function formatter(array $result): array
    {
        $rows = [];
        foreach ($result['query'] as $item) {
            $rows[] = [
                'id'         => $item->id,
                'nama'       => $item->nama,
                'nim'        => $item->nim,
                'email'      => $item->email,
                'no_hp'      => $item->no_hp,
                'id_prodi'   => $item->id_prodi,
                'nama_prodi' => $item->nama_prodi,
                'fakultas'   => $item->fakultas,
                'id_program' => $item->id_program,
                'nama_program' => $item->nama_program,
                'kode_program' => $item->kode_program,
                'tahun_ajaran_program' => $item->tahun_ajaran_program,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        }

        return $rows;
    }

    private function normalizeAndValidate(array $input, ?int $currentId = null): array
    {
        $nama      = isset($input['nama']) ? trim($input['nama']) : '';
        $nim       = isset($input['nim']) ? trim($input['nim']) : '';
        $email     = isset($input['email']) ? trim($input['email']) : '';
        $noHp      = isset($input['no_hp']) ? trim($input['no_hp']) : null;
        $idProdi   = isset($input['id_prodi']) && $input['id_prodi'] !== '' ? (int) $input['id_prodi'] : null;
        $idProgram = isset($input['id_program']) && $input['id_program'] !== '' ? (int) $input['id_program'] : null;

        if ($nama === '' || $nim === '') {
            throw new \InvalidArgumentException('Nama dan NIM wajib diisi.');
        }

        if ($this->MahasiswaTrueRepository->findByNim($nim, $currentId)) {
            throw new \InvalidArgumentException('NIM sudah terdaftar.');
        }

        $data = [
            'nama' => $nama,
            'nim'  => $nim,
        ];

        if ($email !== '') {
            $data['email'] = $email;
        }

        if (!empty($noHp)) {
            $data['no_hp'] = $noHp;
        }

        $data['id_prodi'] = !empty($idProdi) ? $idProdi : null;

        if (empty($idProgram) || $idProgram <= 0) {
            throw new \InvalidArgumentException('Program wajib dipilih.');
        }

        $data['id_program'] = $idProgram;

        return $data;
    }

    private function getCurrentUserId(): ?int
    {
        $userId = $this->CI->session->userdata('id_user');
        return $userId ? (int) $userId : null;
    }
}
