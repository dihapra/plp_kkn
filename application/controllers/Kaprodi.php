<?php

use UseCases\Kaprodi\DosenCase as KaprodiDosenCase;

defined('BASEPATH') or exit('No direct script access allowed');

class Kaprodi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_role(['kaprodi']);
    }

    public function index()
    {
        $dummyStats = [
            'total_mahasiswa' => 0,
            'total_dosen'     => 0,
            'program_aktif'   => 0,
        ];
        view_with_layout('kaprodi/dashboard', 'Dashboard Kaprodi', null, $dummyStats);
    }

    public function mahasiswa()
    {
        view_with_layout(
            'kaprodi/mahasiswa/index',
            'Mahasiswa Per Prodi',
            null,
            [],
            'css/datatable',
            'script/datatable'
        );
    }

    public function dosen()
    {
        $data = [
            'prodiOptions' => $this->getAccessibleProdiOptions(),
        ];

        view_with_layout(
            'kaprodi/dosen/index',
            'Dosen Pembimbing',
            null,
            $data,
            'css/datatable',
            'script/datatable'
        );
    }

    public function dosen_datatable()
    {
        $req = get_param_datatable();
        $filters = [];
        $prodiFilter = (int) $this->input->post('filter_prodi');
        if ($prodiFilter > 0) {
            $filters['id_prodi'] = $prodiFilter;
        }

        $uc = new KaprodiDosenCase();
        $data = $uc->datatable($req, $filters);

        datatable_response_array($req['draw'], $data['count_total'], $data['count_filtered'], $data['formatted']);
    }

    public function dosen_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new KaprodiDosenCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->create($payload);
            response_json('Dosen berhasil dibuat');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function dosen_update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new KaprodiDosenCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->update((int) $id, $payload);
            response_json('Dosen berhasil diperbarui');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function dosen_delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new KaprodiDosenCase();
            $uc->delete((int) $id);
            response_json('Dosen berhasil dihapus');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function dosen_export()
    {
        $filter = (int) $this->input->get('filter_prodi');
        $filters = [];
        if ($filter > 0) {
            $filters['id_prodi'] = $filter;
        }

        try {
            $uc = new KaprodiDosenCase();
            $rows = $uc->export($filters);

            $filename = 'dosen_pembimbing_' . date('Ymd_His') . '.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Nama Dosen', 'Prodi', 'Total Mahasiswa', 'Mahasiswa Aktif', 'Sekolah Binaan']);

            foreach ($rows as $row) {
                fputcsv($output, [
                    $row['nama'],
                    $row['nama_prodi'],
                    $row['total_mahasiswa'],
                    $row['mahasiswa_aktif'],
                    $row['sekolah_binaan'],
                ]);
            }

            fclose($output);
            exit;
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function laporan()
    {
        view_with_layout('kaprodi/laporan', 'Laporan Kaprodi');
    }

    private function getAccessibleProdiOptions(): array
    {
        $idUser = (int) $this->session->userdata('id_user');
        $allowed = [];

        if ($idUser) {
            $record = $this->db
                ->select('id_prodi')
                ->from('kaprodi')
                ->where('id_user', $idUser)
                ->get()
                ->row();

            if (!empty($record) && !empty($record->id_prodi)) {
                $allowed[] = (int) $record->id_prodi;
            }
        }

        $this->db->select('id, nama, fakultas');
        $this->db->from('prodi');
        if (!empty($allowed)) {
            $this->db->where_in('id', $allowed);
        }
        $this->db->order_by('nama', 'ASC');

        return $this->db->get()->result();
    }
}
