<?php

namespace UseCases\Superadmin;

defined('BASEPATH') or exit('No direct script access allowed');

class KepsekCase extends BaseCase
{
    public function create(array $payload, array $files = []): void
    {
        $db = $this->CI->db;

        try {
            $db->trans_begin();

            $data = $this->normalizeAndValidate($payload);

            $email = $payload['email'] ?? null;
            if (empty($email)) {
                throw new \InvalidArgumentException('Email wajib diisi untuk akun kepala sekolah.');
            }

            $plainPassword = !empty($payload['nik']) ? $payload['nik'] : $email;

            $userData = [
                'email'        => $email,
                'username'     => $payload['nama'] ?? $email,
                'password'     => password_hash($plainPassword, PASSWORD_BCRYPT),
                'role'         => 'kepsek',
                'fakultas'     => null,
                'has_change'   => 0,
                'id_program'   => $data['id_program'] ?? null,
                'created_at'   => date('Y-m-d H:i:s'),
            ];

            $db->insert('users', $userData);
            $userId = (int) $db->insert_id();

            if ($userId <= 0) {
                throw new \RuntimeException('Gagal membuat user untuk kepala sekolah.');
            }

            $data['id_user']   = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');

            $id   = $this->KepsekRepository->create($data);

            $paths = $this->handleUploads($id, $files);
            if (!empty($paths)) {
                $this->KepsekRepository->update($id, $paths);
            }

            $db->trans_commit();
        } catch (\Throwable $e) {
            $db->trans_rollback();
            throw $e;
        }
    }

    public function update(int $id, array $payload, array $files = []): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID kepala sekolah tidak valid.');
        }
        $db = $this->CI->db;

        try {
            $row = $this->KepsekRepository->find($id);
            if (!$row) {
                throw new \InvalidArgumentException('Data kepala sekolah tidak ditemukan.');
            }

            $db->trans_begin();

            $data  = $this->normalizeAndValidate($payload);
            $paths = $this->handleUploads($id, $files, $row);
            $updateData = array_merge($data, $paths);

            // Update user terkait (email, username, id_program)
            if (!empty($row->id_user)) {
                $userUpdate = [
                    'email'      => $updateData['email'] ?? $row->email,
                    'username'   => $updateData['nama'] ?? $row->nama,
                    'id_program' => $updateData['id_program'] ?? $row->id_program,
                ];
                $db->where('id', $row->id_user)->update('users', $userUpdate);
            }

            $this->KepsekRepository->update($id, $updateData);

            $db->trans_commit();
        } catch (\Throwable $e) {
            $db->trans_rollback();
            throw $e;
        }
    }

    public function datatable(array $params): array
    {
        $result = $this->KepsekRepository->datatable($params);

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
                'id'                => $r->id,
                'id_sekolah'        => $r->id_sekolah,
                'id_program'        => $r->id_program,
                'nama'              => $r->nama,
                'email'             => $r->email,
                'no_hp'             => $r->no_hp,
                'nama_sekolah'      => $r->nama_sekolah,
                'status_pembayaran' => $r->status_pembayaran,
                'status_perkawinan' => $r->status_perkawinan,
                'nik'               => $r->nik,
                'bank'              => $r->bank,
                'nomor_rekening'    => $r->nomor_rekening,
                'nama_rekening'     => $r->nama_rekening,
                'foto_ktp'          => $r->foto_ktp,
                'buku'              => $r->buku,
            ];
        }
        return $formatter;
    }

    private function normalizeAndValidate(array $input): array
    {
        $nama   = isset($input['nama']) ? trim($input['nama']) : '';
        $idSek  = isset($input['id_sekolah']) ? (int) $input['id_sekolah'] : 0;
        $idProg = isset($input['id_program']) ? (int) $input['id_program'] : null;

        if ($nama === '' || $idSek <= 0) {
            throw new \InvalidArgumentException('Nama dan sekolah wajib diisi.');
        }

        $statusBayar = isset($input['status_pembayaran']) && $input['status_pembayaran'] === 'dibayar'
            ? 'dibayar'
            : 'belum dibayar';

        return [
            'nama'              => $nama,
            'email'             => $input['email'] ?? null,
            'no_hp'             => $input['no_hp'] ?? null,
            'id_sekolah'        => $idSek,
            'id_program'        => $idProg,
            'status_perkawinan' => $input['status_perkawinan'] ?? null,
            'nik'               => $input['nik'] ?? null,
            'bank'              => $input['bank'] ?? null,
            'nomor_rekening'    => $input['nomor_rekening'] ?? null,
            'nama_rekening'     => $input['nama_rekening'] ?? null,
            'status_pembayaran' => $statusBayar,
            'updated_at'        => date('Y-m-d H:i:s'),
        ];
    }

    private function handleUploads(int $id, array $files, $existing = null): array
    {
        $result = [];
        if (empty($files)) {
            return $result;
        }

        $encryptedId = md5($id);
        $basePath    = FCPATH . 'uploads/kepsek/' . $encryptedId . '/';

        if (!is_dir($basePath)) {
            mkdir($basePath, 0775, true);
        }

        // foto_ktp
        if (isset($files['foto_ktp']) && is_uploaded_file($files['foto_ktp']['tmp_name'])) {
            if ($existing && !empty($existing->foto_ktp)) {
                $old = FCPATH . ltrim($existing->foto_ktp, '/');
                if (is_file($old)) {
                    @unlink($old);
                }
            }
            $ext = strtolower(pathinfo($files['foto_ktp']['name'], PATHINFO_EXTENSION));
            $filename = 'ktp.' . $ext;
            $target   = $basePath . $filename;
            if (move_uploaded_file($files['foto_ktp']['tmp_name'], $target)) {
                $result['foto_ktp'] = 'uploads/kepsek/' . $encryptedId . '/' . $filename;
            }
        }

        // buku rekening
        if (isset($files['buku']) && is_uploaded_file($files['buku']['tmp_name'])) {
            if ($existing && !empty($existing->buku)) {
                $old = FCPATH . ltrim($existing->buku, '/');
                if (is_file($old)) {
                    @unlink($old);
                }
            }
            $ext = strtolower(pathinfo($files['buku']['name'], PATHINFO_EXTENSION));
            $filename = 'buku.' . $ext;
            $target   = $basePath . $filename;
            if (move_uploaded_file($files['buku']['tmp_name'], $target)) {
                $result['buku'] = 'uploads/kepsek/' . $encryptedId . '/' . $filename;
            }
        }

        return $result;
    }
}
