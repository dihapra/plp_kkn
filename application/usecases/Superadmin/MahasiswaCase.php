<?php

namespace UseCases\Superadmin;

defined('BASEPATH') or exit('No direct script access allowed');

class MahasiswaCase extends BaseCase
{
    public function create(array $payload): void
    {
        $db = $this->CI->db;

        try {
            $db->trans_begin();

            $data = $this->normalizeAndValidate($payload);

            $nim = $data['nim'];
            $email = $data['email'];

            $userData = [
                'email'      => $email,
                'username'   => $data['nama'],
                'password'   => password_hash($nim, PASSWORD_BCRYPT),
                'role'       => 'mahasiswa',
                'fakultas'   => null,
                'has_change' => 0,
                'id_program' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $db->insert('users', $userData);
            $userId = (int) $db->insert_id();

            if ($userId <= 0) {
                throw new \RuntimeException('Gagal membuat user untuk mahasiswa.');
            }

            $data['id_user']    = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['status']     = 'verified';

            $this->MahasiswaRepository->create($data);

            $db->trans_commit();
        } catch (\Throwable $e) {
            $db->trans_rollback();
            throw $e;
        }
    }

    public function update(int $id, array $payload): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID mahasiswa tidak valid.');
        }

        $db = $this->CI->db;

        try {
            $row = $this->MahasiswaRepository->find($id);
            if (!$row) {
                throw new \InvalidArgumentException('Data mahasiswa tidak ditemukan.');
            }

            $db->trans_begin();

            $data = $this->normalizeAndValidate($payload);

            if (!empty($row->id_user)) {
                $userUpdate = [
                    'email'    => $data['email'],
                    'username' => $data['nama'],
                ];

                if (!empty($data['nim']) && $data['nim'] !== $row->nim) {
                    $userUpdate['password'] = password_hash($data['nim'], PASSWORD_BCRYPT);
                }

                $db->where('id', $row->id_user)->update('users', $userUpdate);
            }

            $this->MahasiswaRepository->update($id, $data);

            $db->trans_commit();
        } catch (\Throwable $e) {
            $db->trans_rollback();
            throw $e;
        }
    }

    public function datatable(array $params): array
    {
        $result = $this->MahasiswaRepository->datatable($params);

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
                'id'            => $r->id,
                'id_user'       => $r->id_user,
                'nama'          => $r->nama,
                'nim'           => $r->nim,
                'email'         => $r->email,
                'no_hp'         => $r->no_hp,
                'id_prodi'      => $r->id_prodi,
                'fakultas'      => $r->fakultas,
                'nama_prodi'    => $r->nama_prodi,
                'id_sekolah'    => $r->id_sekolah,
                'nama_sekolah'  => $r->nama_sekolah,
                'status'        => $r->status,
            ];
        }

        return $formatter;
    }

    private function normalizeAndValidate(array $input): array
    {
        $nama      = isset($input['nama']) ? trim($input['nama']) : '';
        $nim       = isset($input['nim']) ? trim($input['nim']) : '';
        $email     = isset($input['email']) ? trim($input['email']) : '';
        $noHp      = isset($input['no_hp']) ? trim($input['no_hp']) : null;
        $idProdi   = isset($input['id_prodi']) ? (int) $input['id_prodi'] : 0;
        $idSekolah = isset($input['id_sekolah']) && $input['id_sekolah'] !== '' ? (int) $input['id_sekolah'] : null;

        if ($nama === '' || $nim === '' || $email === '' || $idProdi <= 0) {
            throw new \InvalidArgumentException('Nama, NIM, email, dan prodi wajib diisi.');
        }

        $data = [
            'nama'       => $nama,
            'nim'        => $nim,
            'email'      => $email,
            'no_hp'      => $noHp,
            'id_prodi'   => $idProdi,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($idSekolah) && $idSekolah > 0) {
            $data['id_sekolah'] = $idSekolah;
        }

        return $data;
    }
}
