<?php

namespace UseCases\Kaprodi;

defined('BASEPATH') or exit('No direct script access allowed');

class DosenCase extends BaseCase
{
    public function datatable(array $params, array $filters = []): array
    {
        $filterParams = $this->buildFilter($filters);
        $result = $this->DosenRepository->datatable($params, $filterParams);

        return [
            'formatted'      => $this->formatter($result),
            'count_total'    => $result['count_total'],
            'count_filtered' => $result['count_filtered'],
        ];
    }

    public function create(array $payload): void
    {
        $db = $this->CI->db;

        try {
            $db->trans_begin();

            $data = $this->normalizeAndValidate($payload);

            $userData = [
                'email'      => $data['email'],
                'username'   => $data['nama'],
                'password'   => password_hash($data['nidn'], PASSWORD_BCRYPT),
                'role'       => 'dosen',
                'fakultas'   => $data['fakultas'] ?? null,
                'has_change' => 0,
                'id_program' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $db->insert('users', $userData);
            $userId = (int) $db->insert_id();

            if ($userId <= 0) {
                throw new \RuntimeException('Gagal membuat user untuk dosen.');
            }

            $data['id_user']    = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');

            $this->DosenRepository->create($data);

            $db->trans_commit();
        } catch (\Throwable $e) {
            $db->trans_rollback();
            throw $e;
        }
    }

    public function update(int $id, array $payload): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID dosen tidak valid.');
        }

        $row = $this->DosenRepository->find($id);
        if (!$row) {
            throw new \InvalidArgumentException('Data dosen tidak ditemukan.');
        }

        $this->assertProdiAccess((int) $row->id_prodi);

        $db = $this->CI->db;

        try {
            $db->trans_begin();

            $data = $this->normalizeAndValidate($payload, (int) $row->id_prodi, (int) $row->id);

            if (!empty($row->id_user)) {
                $userUpdate = [
                    'email'    => $data['email'],
                    'username' => $data['nama'],
                    'fakultas' => $data['fakultas'] ?? null,
                ];

                if (!empty($data['nidn']) && $data['nidn'] !== $row->nidn) {
                    $userUpdate['password'] = password_hash($data['nidn'], PASSWORD_BCRYPT);
                }

                $db->where('id', $row->id_user)->update('users', $userUpdate);
            }

            $this->DosenRepository->update($id, $data);

            $db->trans_commit();
        } catch (\Throwable $e) {
            $db->trans_rollback();
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID dosen tidak valid.');
        }

        $row = $this->DosenRepository->find($id);
        if (!$row) {
            throw new \InvalidArgumentException('Data dosen tidak ditemukan.');
        }

        $this->assertProdiAccess((int) $row->id_prodi);

        $db = $this->CI->db;

        try {
            $db->trans_begin();

            $this->DosenRepository->delete($id);

            if (!empty($row->id_user)) {
                $db->where('id', $row->id_user)->delete('users');
            }

            $db->trans_commit();
        } catch (\Throwable $e) {
            $db->trans_rollback();
            throw $e;
        }
    }

    public function export(array $filters = []): array
    {
        $result = $this->DosenRepository->all($this->buildFilter($filters));
        return $this->formatter(['query' => $result]);
    }

    protected function buildFilter(array $filters): array
    {
        $result = [];
        $filterId = isset($filters['id_prodi']) ? (int) $filters['id_prodi'] : null;

        if (!empty($filterId)) {
            $this->assertProdiAccess($filterId);
            $result['id_prodi'] = $filterId;
            return $result;
        }

        $allowed = $this->getAllowedProdiIds();
        if (!empty($allowed)) {
            $result['prodi_ids'] = $allowed;
        }

        return $result;
    }

    protected function formatter(array $result): array
    {
        $data = [];
        if (empty($result['query'])) {
            return $data;
        }

        $rows = $result['query'];

        foreach ($rows as $row) {
            $data[] = [
                'id'                => (int) $row->id,
                'id_user'           => isset($row->id_user) ? (int) $row->id_user : null,
                'nama'              => $row->nama ?? '',
                'nidn'              => $row->nidn ?? '',
                'email'             => $row->email ?? '',
                'no_hp'             => $row->no_hp ?? '',
                'id_prodi'          => isset($row->id_prodi) ? (int) $row->id_prodi : null,
                'nama_prodi'        => $row->nama_prodi ?? '',
                'fakultas'          => $row->fakultas ?? '',
                'total_mahasiswa'   => isset($row->total_mahasiswa) ? (int) $row->total_mahasiswa : 0,
                'mahasiswa_aktif'   => isset($row->mahasiswa_aktif) ? (int) $row->mahasiswa_aktif : 0,
                'sekolah_binaan'    => $row->sekolah_binaan ?? '',
            ];
        }

        return $data;
    }

    protected function normalizeAndValidate(array $input, ?int $currentProdiId = null, ?int $currentId = null): array
    {
        $nama    = isset($input['nama']) ? trim($input['nama']) : '';
        $nidn    = isset($input['nidn']) ? trim($input['nidn']) : '';
        $email   = isset($input['email']) ? trim($input['email']) : '';
        $noHp    = isset($input['no_hp']) ? trim($input['no_hp']) : null;
        $idProdi = isset($input['id_prodi']) ? (int) $input['id_prodi'] : $currentProdiId;

        if ($nama === '' || $nidn === '') {
            throw new \InvalidArgumentException('Nama dan NIDN wajib diisi.');
        }

        if ($email === '') {
            throw new \InvalidArgumentException('Email wajib diisi.');
        }

        if (empty($idProdi)) {
            $idProdi = $this->getDefaultProdiId();
        }

        if (empty($idProdi)) {
            throw new \InvalidArgumentException('Program studi tidak ditemukan.');
        }

        $this->assertProdiAccess($idProdi);

        $existing = $this->CI->db
            ->select('id')
            ->from('dosen')
            ->where('nidn', $nidn)
            ->get()
            ->row();

        if ($existing && (int) $existing->id !== (int) $currentId) {
            throw new \InvalidArgumentException('NIP/NIDN sudah digunakan oleh dosen lain.');
        }

        $prodi = $this->findProdi($idProdi);
        if (!$prodi) {
            throw new \InvalidArgumentException('Program studi tidak valid.');
        }

        return [
            'nama'       => $nama,
            'nidn'       => $nidn,
            'email'      => $email,
            'no_hp'      => $noHp,
            'id_prodi'   => $idProdi,
            'fakultas'   => $prodi->fakultas ?? null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
