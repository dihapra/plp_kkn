<?php

namespace UseCases\Admin;

use UseCases\Superadmin\SekolahCase as SuperadminSekolahCase;

defined('BASEPATH') or exit('No direct script access allowed');

class SekolahCase extends SuperadminSekolahCase
{
    public function delete(int $id): void
    {
        throw new \RuntimeException('Akses hapus sekolah tidak diizinkan untuk admin.');
    }

    public function deleteByProgram(int $sekolahId, int $programId): void
    {
        throw new \RuntimeException('Akses hapus relasi sekolah tidak diizinkan untuk admin.');
    }
}
