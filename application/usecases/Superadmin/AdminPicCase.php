<?php

namespace UseCases\Superadmin;

use Repositories\Superadmin\AdminPic as AdminPicRepository;

defined('BASEPATH') or exit('No direct script access allowed');

class AdminPicCase extends BaseCase
{
    /** @var AdminPicRepository */
    protected $AdminPicRepository;

    public function __construct()
    {
        parent::__construct();
        $this->AdminPicRepository = new AdminPicRepository();
    }

    public function datatable(array $params): array
    {
        $result = $this->AdminPicRepository->datatable($params);

        return [
            'formatted' => $this->formatter($result),
            'count_total' => $result['count_total'],
            'count_filtered' => $result['count_filtered'],
        ];
    }

    public function create(array $payload): void
    {
        $data = $this->normalizeAndValidate($payload);

        $userData = [
            'email' => $data['email'],
            'username' => $data['nama'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => 'admin',
            'fakultas' => $data['fakultas'],
            'has_change' => 0,
            'id_program' => null,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->AdminPicRepository->create($userData);
    }

    public function update(int $id, array $payload): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID admin PIC tidak valid.');
        }

        $existing = $this->AdminPicRepository->find($id);
        if (!$existing) {
            throw new \InvalidArgumentException('Data admin PIC tidak ditemukan.');
        }

        $data = $this->normalizeAndValidate($payload, $id);

        $update = [
            'email' => $data['email'],
            'username' => $data['nama'],
            'fakultas' => $data['fakultas'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($data['password'])) {
            $update['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $this->AdminPicRepository->update($id, $update);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID admin PIC tidak valid.');
        }

        $existing = $this->AdminPicRepository->find($id);
        if (!$existing) {
            throw new \InvalidArgumentException('Data admin PIC tidak ditemukan.');
        }

        $this->AdminPicRepository->delete($id);
    }

    private function normalizeAndValidate(array $input, ?int $currentId = null): array
    {
        $nama = isset($input['nama']) ? trim((string) $input['nama']) : '';
        $email = isset($input['email']) ? trim((string) $input['email']) : '';
        $password = isset($input['password']) ? (string) $input['password'] : '';
        $fakultas = isset($input['fakultas']) ? trim((string) $input['fakultas']) : null;

        if ($nama === '' || $email === '') {
            throw new \InvalidArgumentException('Nama dan email wajib diisi.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Format email tidak valid.');
        }

        if ($currentId === null && $password === '') {
            throw new \InvalidArgumentException('Password wajib diisi.');
        }

        $existing = $this->CI->db
            ->select('id')
            ->from('users')
            ->where('email', $email)
            ->get()
            ->row();

        if ($existing && (int) $existing->id !== (int) $currentId) {
            throw new \InvalidArgumentException('Email sudah digunakan oleh akun lain.');
        }

        return [
            'nama' => $nama,
            'email' => $email,
            'password' => $password,
            'fakultas' => $fakultas ?: null,
        ];
    }

    private function formatter(array $result): array
    {
        $data = [];
        foreach ($result['query'] as $row) {
            $data[] = [
                'id' => (int) $row->id,
                'nama' => $row->nama ?? '',
                'email' => $row->email ?? '',
                'fakultas' => $row->fakultas ?? '',
                'created_at' => $row->created_at ?? null,
            ];
        }

        return $data;
    }
}
