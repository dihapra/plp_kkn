<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/Superadmin.php');

class Modulebase extends Superadmin
{
    protected $moduleLabel = 'Modul';
    protected $moduleSlug  = '';
    protected $pageDescriptions = [];

    protected function renderModulePage(string $pageKey, string $pageTitle): void
    {
        $data = [
            'module_label'    => $this->moduleLabel,
            'module_slug'     => $this->moduleSlug,
            'page_key'        => $pageKey,
            'page_title'      => $pageTitle,
            'description'     => $this->pageDescriptions[$pageKey] ?? 'Halaman modul sedang dipersiapkan.',
            'highlights'      => $this->buildHighlights($pageTitle),
            'checklist'       => $this->buildChecklist($pageKey),
            'supporting_links'=> $this->supportingLinks(),
        ];

        $title = sprintf('Super Admin - %s %s', $this->moduleLabel, $pageTitle);
        view_with_layout('super_admin/modules/placeholder', $title, 'super_admin', $data);
    }

    protected function buildHighlights(string $pageTitle): array
    {
        $lowerTitle = strtolower($pageTitle);
        return [
            [
                'icon'  => 'bi-diagram-3',
                'title' => 'Struktur Data',
                'body'  => 'Kerangka database siap, menunggu sinkronisasi sumber data ' . $lowerTitle . '.',
            ],
            [
                'icon'  => 'bi-lightning-charge',
                'title' => 'Integrasi Modul',
                'body'  => 'Akses menu telah aktif. Endpoint layanan ' . $lowerTitle . ' sedang disiapkan oleh tim dev.',
            ],
            [
                'icon'  => 'bi-people',
                'title' => 'Koordinasi Tim',
                'body'  => 'PIC modul sudah tercatat. Silakan susun kebutuhan tambahan sebelum pengembangan lanjutan.',
            ],
        ];
    }

    protected function buildChecklist(string $pageKey): array
    {
        $default = [
            ['label' => 'Inventarisasi kebutuhan data', 'status' => 'progress'],
            ['label' => 'Susun SOP operasional', 'status' => 'pending'],
            ['label' => 'Mapping akses pengguna', 'status' => 'pending'],
        ];

        $map = [
            'activities' => [
                ['label' => 'Kumpulkan template aktivitas dari tim lapangan', 'status' => 'progress'],
                ['label' => 'Tentukan penanggung jawab update harian', 'status' => 'pending'],
                ['label' => 'Siapkan integrasi jadwal', 'status' => 'pending'],
            ],
            'report' => [
                ['label' => 'Definisikan struktur laporan', 'status' => 'progress'],
                ['label' => 'Mapping timeline pengumpulan', 'status' => 'pending'],
                ['label' => 'Review kebutuhan tanda tangan digital', 'status' => 'pending'],
            ],
            'verifikasi' => [
                ['label' => 'List validator dan wewenang', 'status' => 'progress'],
                ['label' => 'Konfigurasi notifikasi verifikasi', 'status' => 'pending'],
                ['label' => 'Siapkan log audit', 'status' => 'pending'],
            ],
            'absensi' => [
                ['label' => 'Tentukan sumber data presensi', 'status' => 'progress'],
                ['label' => 'Susun aturan toleransi keterlambatan', 'status' => 'pending'],
                ['label' => 'Review format ekspor absensi', 'status' => 'pending'],
            ],
            'verifikasi_mahasiswa' => [
                ['label' => 'Mapping validator mahasiswa', 'status' => 'progress'],
                ['label' => 'Siapkan template notifikasi kelengkapan', 'status' => 'pending'],
                ['label' => 'Susun arsip digital per mahasiswa', 'status' => 'pending'],
            ],
            'verifikasi_guru' => [
                ['label' => 'Daftar kebutuhan dokumen guru pamong', 'status' => 'progress'],
                ['label' => 'Tetapkan alur persetujuan dengan sekolah', 'status' => 'pending'],
                ['label' => 'Rancang monitoring pencairan insentif', 'status' => 'pending'],
            ],
            'verifikasi_kepsek' => [
                ['label' => 'Identifikasi PIC tiap sekolah', 'status' => 'progress'],
                ['label' => 'Konfirmasi kebutuhan tanda tangan digital', 'status' => 'pending'],
                ['label' => 'Siapkan log aktivitas verifikasi', 'status' => 'pending'],
            ],
        ];

        return $map[$pageKey] ?? $default;
    }

    protected function supportingLinks(): array
    {
        return [
            [
                'label'  => 'Dokumen kebutuhan modul',
                'url'    => '#',
                'status' => 'draft',
            ],
            [
                'label'  => 'Template koordinasi lintas peran',
                'url'    => '#',
                'status' => 'draft',
            ],
            [
                'label'  => 'Hubungi PIC pengembangan',
                'url'    => 'mailto:support@plp-kkn.local',
                'status' => 'ready',
            ],
        ];
    }
}
