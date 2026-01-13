<?php

namespace UseCases\Admin;

use UseCases\Superadmin\MahasiswaTrueCase as SuperadminMahasiswaTrueCase;

defined('BASEPATH') or exit('No direct script access allowed');

class MahasiswaTrueCase extends SuperadminMahasiswaTrueCase
{
    public function delete(int $id): void
    {
        throw new \RuntimeException('Akses hapus data mahasiswa tidak diizinkan untuk admin.');
    }
}
