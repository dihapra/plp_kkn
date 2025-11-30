<?php

namespace UseCases\Superadmin;

defined('BASEPATH') or exit('No direct script access allowed');

class KaprodiCase extends BaseCase
{
    public function create(array $payload): void
    {
        $db = $this->CI->db;

        try {
            $db->trans_begin();

            $data = $this->normalizeAndValidate($payload);

            $email = $data['email'];
            $plainPassword = $email;

            $userData = [
                'email'      => $email,
                'username'   => $data['nama'],
                'password'   => password_hash($plainPassword, PASSWORD_BCRYPT),
                'role'       => 'kaprodi',
                'fakultas'   => null,
                'has_change' => 0,
                'id_program' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $db->insert('users', $userData);
            $userId = (int) $db->insert_id();

            if ($userId <= 0) {
                throw new \RuntimeException('Gagal membuat user untuk kaprodi.');
            }

            $data['id_user']    = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');

            $this->KaprodiRepository->create($data);

            $db->trans_commit();
        } catch (\Throwable $e) {
            $db->trans_rollback();
            throw $e;
        }
    }

    public function update(int $id, array $payload): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID kaprodi tidak valid.');
        }

        $db = $this->CI->db;

        try {
            $row = $this->KaprodiRepository->find($id);
            if (!$row) {
                throw new \InvalidArgumentException('Data kaprodi tidak ditemukan.');
            }

            $db->trans_begin();

            $data = $this->normalizeAndValidate($payload);

            if (!empty($row->id_user)) {
                $userUpdate = [
                    'email'    => $data['email'],
                    'username' => $data['nama'],
                ];
                $db->where('id', $row->id_user)->update('users', $userUpdate);
            }

            $this->KaprodiRepository->update($id, $data);

            $db->trans_commit();
        } catch (\Throwable $e) {
            $db->trans_rollback();
            throw $e;
        }
    }

    public function datatable(array $params): array
    {
        $result = $this->KaprodiRepository->datatable($params);

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
                'id_prodi'   => $r->id_prodi,
                'nama'       => $r->nama,
                'no_hp'      => $r->no_hp,
                'email'      => $r->email,
                'nama_prodi' => $r->nama_prodi,
                'fakultas'   => $r->fakultas,
            ];
        }

        return $formatter;
    }

    private function normalizeAndValidate(array $input): array
    {
        $nama    = isset($input['nama']) ? trim($input['nama']) : '';
        $email   = isset($input['email']) ? trim($input['email']) : '';
        $noHp    = isset($input['no_hp']) ? trim($input['no_hp']) : null;
        $idProdi = isset($input['id_prodi']) ? (int) $input['id_prodi'] : 0;

        if ($nama === '' || $email === '' || $idProdi <= 0) {
            throw new \InvalidArgumentException('Nama, email, dan prodi wajib diisi.');
        }

        return [
            'nama'       => $nama,
            'email'      => $email,
            'no_hp'      => $noHp,
            'id_prodi'   => $idProdi,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
