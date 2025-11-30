<?php

namespace UseCases\Superadmin;

defined('BASEPATH') or exit('No direct script access allowed');

class DosenCase extends BaseCase
{
    public function create(array $payload): void
    {
        $db = $this->CI->db;

        try {
            $db->trans_begin();

            $data = $this->normalizeAndValidate($payload);

            $email = $data['email'];
            $passwordSource = $data['nidn'];

            $userData = [
                'email'      => $email,
                'username'   => $data['nama'],
                'password'   => password_hash($passwordSource, PASSWORD_BCRYPT),
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

        $db = $this->CI->db;

        try {
            $row = $this->DosenRepository->find($id);
            if (!$row) {
                throw new \InvalidArgumentException('Data dosen tidak ditemukan.');
            }

            $db->trans_begin();

            $data = $this->normalizeAndValidate($payload);

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

    public function datatable(array $params): array
    {
        $result = $this->DosenRepository->datatable($params);

        return [
            'formatted'      => $this->formatter($result),
            'count_total'    => $result['count_total'],
            'count_filtered' => $result['count_filtered'],
        ];
    }

    private function formatter(array $result): array
    {
        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = [
                'id'         => $r->id,
                'id_user'    => $r->id_user,
                'nama'       => $r->nama,
                'nidn'       => $r->nidn,
                'email'      => $r->email,
                'no_hp'      => $r->no_hp,
                'id_prodi'   => $r->id_prodi,
                'fakultas'   => $r->fakultas,
                'nama_prodi' => $r->nama_prodi,
            ];
        }

        return $formatter;
    }

    private function normalizeAndValidate(array $input): array
    {
        $nama    = isset($input['nama']) ? trim($input['nama']) : '';
        $nidn    = isset($input['nidn']) ? trim($input['nidn']) : (isset($input['nip']) ? trim($input['nip']) : '');
        $email   = isset($input['email']) ? trim($input['email']) : '';
        $noHp    = isset($input['no_hp']) ? trim($input['no_hp']) : null;
        $idProdi = isset($input['id_prodi']) ? (int) $input['id_prodi'] : 0;
        $fakultas = isset($input['fakultas']) ? trim($input['fakultas']) : null;

        if ($nama === '' || $nidn === '' || $idProdi <= 0) {
            throw new \InvalidArgumentException('Nama, NIP/NIDN, dan prodi wajib diisi.');
        }

        if ($email === '') {
            throw new \InvalidArgumentException('Email wajib diisi.');
        }

        return [
            'nama'       => $nama,
            'nidn'       => $nidn,
            'email'      => $email,
            'no_hp'      => $noHp,
            'id_prodi'   => $idProdi,
            'fakultas'   => $fakultas,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}

