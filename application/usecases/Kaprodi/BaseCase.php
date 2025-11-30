<?php

namespace UseCases\Kaprodi;

use Repositories\Kaprodi\Dosen as DosenRepository;

defined('BASEPATH') or exit('No direct script access allowed');

class BaseCase
{
    /** @var \CI_Controller */
    protected $CI;
    /** @var DosenRepository */
    protected $DosenRepository;
    protected $kaprodiProfile;
    protected $allowedProdiIds = [];

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->DosenRepository = new DosenRepository();
        $this->kaprodiProfile = $this->loadProfile();
        $this->allowedProdiIds = $this->resolveAllowedProdiIds();
    }

    protected function loadProfile()
    {
        $idUser = (int) $this->CI->session->userdata('id_user');
        if (!$idUser) {
            return null;
        }

        return $this->CI->db
            ->from('kaprodi')
            ->where('id_user', $idUser)
            ->get()
            ->row();
    }

    protected function resolveAllowedProdiIds(): array
    {
        if (!empty($this->kaprodiProfile) && !empty($this->kaprodiProfile->id_prodi)) {
            return [(int) $this->kaprodiProfile->id_prodi];
        }

        return [];
    }

    protected function getAllowedProdiIds(): array
    {
        return $this->allowedProdiIds;
    }

    protected function getDefaultProdiId(): ?int
    {
        return $this->allowedProdiIds[0] ?? null;
    }

    protected function assertProdiAccess(int $idProdi): void
    {
        if (empty($this->allowedProdiIds)) {
            return;
        }

        if (!in_array($idProdi, $this->allowedProdiIds, true)) {
            throw new \InvalidArgumentException('Anda tidak memiliki akses ke program studi ini.');
        }
    }

    protected function findProdi(int $id)
    {
        if ($id <= 0) {
            return null;
        }

        return $this->CI->db
            ->select('id, nama, fakultas')
            ->from('prodi')
            ->where('id', $id)
            ->get()
            ->row();
    }
}
