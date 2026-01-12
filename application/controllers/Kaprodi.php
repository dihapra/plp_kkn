<?php

use UseCases\Kaprodi\DosenCase as KaprodiDosenCase;
use UseCases\Kaprodi\MahasiswaCase as KaprodiMahasiswaCase;
use UseCases\Kaprodi\PlottingCase;

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
        $activeProgram = $this->db
            ->select('id')
            ->from('program')
            ->where('active', 1)
            ->order_by('updated_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();
        $activeProgramId = $activeProgram ? (int) $activeProgram->id : 0;

        $allowedProdiIds = [];
        $idUser = (int) $this->session->userdata('id_user');
        if ($idUser) {
            $record = $this->db
                ->select('id_prodi')
                ->from('kaprodi')
                ->where('id_user', $idUser)
                ->get()
                ->row();
            if (!empty($record) && !empty($record->id_prodi)) {
                $allowedProdiIds[] = (int) $record->id_prodi;
            }
        }

        $totalMahasiswaQuery = $this->db
            ->from('program_mahasiswa pm')
            ->join('mahasiswa', 'mahasiswa.id = pm.id_mahasiswa', 'inner')
            ->where('pm.status', 'verified');
        if ($activeProgramId > 0) {
            $totalMahasiswaQuery->where('pm.id_program', $activeProgramId);
        }
        if (!empty($allowedProdiIds)) {
            $totalMahasiswaQuery->where_in('mahasiswa.id_prodi', $allowedProdiIds);
        }
        $totalMahasiswa = (int) $totalMahasiswaQuery->count_all_results();

        $totalDosenQuery = $this->db->from('dosen');
        if (!empty($allowedProdiIds)) {
            $totalDosenQuery->where_in('id_prodi', $allowedProdiIds);
        }
        $totalDosen = (int) $totalDosenQuery->count_all_results();

        $viewData = [
            'total_mahasiswa' => $totalMahasiswa,
            'total_dosen'     => $totalDosen,
            'program_aktif'   => $activeProgramId > 0 ? 1 : 0,
        ];

        view_with_layout('kaprodi/dashboard', 'Dashboard Kaprodi', null, $viewData);
    }

    public function mahasiswa()
    {
        $activeProgram = $this->db
            ->select('id, kode, nama, tahun_ajaran, semester')
            ->from('program')
            ->where('active', 1)
            ->order_by('updated_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();
        $data = [
            'activeProgram' => $activeProgram ?: null,
        ];

        view_with_layout(
            'kaprodi/mahasiswa/index',
            'Mahasiswa Per Prodi',
            null,
            $data,
            'css/datatable',
            'script/datatable'
        );
    }

    public function mahasiswa_datatable()
    {
        $req = get_param_datatable();
        $filters = [];

        try {
            $uc = new KaprodiMahasiswaCase();
            $data = $uc->datatable($req, $filters);
            datatable_response_array($req['draw'], $data['count_total'], $data['count_filtered'], $data['formatted']);
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
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

    public function dosen_import()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        $idUser = (int) $this->session->userdata('id_user');
        $prodiId = 0;
        if ($idUser) {
            $record = $this->db
                ->select('id_prodi')
                ->from('kaprodi')
                ->where('id_user', $idUser)
                ->get()
                ->row();
            if (!empty($record) && !empty($record->id_prodi)) {
                $prodiId = (int) $record->id_prodi;
            }
        }

        if ($prodiId <= 0) {
            response_error('Program studi belum ditentukan.', null, 422);
            return;
        }

        $this->load->library('upload');
        $uploadFolder = './uploads/kaprodi/import/';
        if (!is_dir($uploadFolder)) {
            if (!mkdir($uploadFolder, 0777, true)) {
                response_error('Gagal membuat folder upload.', null, 500);
                return;
            }
        }

        $config = [
            'upload_path' => $uploadFolder,
            'allowed_types' => 'xlsx',
            'max_size' => 4096,
            'file_name' => 'import_dosen_' . time(),
        ];
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('importFile')) {
            $error = $this->upload->display_errors();
            response_error('Upload gagal: ' . strip_tags($error), null, 422);
            return;
        }

        $fileData = $this->upload->data();
        $filePath = $uploadFolder . $fileData['file_name'];

        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            if (empty($sheetData) || count($sheetData) < 2) {
                throw new \RuntimeException('Data dosen kosong atau format tidak sesuai.');
            }

            $success = 0;
            $failed = 0;
            $errors = [];
            $uc = new KaprodiDosenCase();

            foreach ($sheetData as $index => $row) {
                if ($index === 1) {
                    continue;
                }

                $nama = isset($row['A']) ? trim((string) $row['A']) : '';
                $nidn = isset($row['B']) ? trim((string) $row['B']) : '';
                $email = isset($row['C']) ? trim((string) $row['C']) : '';
                $noHp = isset($row['D']) ? trim((string) $row['D']) : '';

                if ($nama === '' && $nidn === '' && $email === '' && $noHp === '') {
                    continue;
                }

                try {
                    $uc->create([
                        'nama' => $nama,
                        'nidn' => $nidn,
                        'email' => $email,
                        'no_hp' => $noHp,
                        'id_prodi' => $prodiId,
                    ]);
                    $success++;
                } catch (\Throwable $rowError) {
                    $failed++;
                    $errors[] = [
                        'row' => $index,
                        'message' => $rowError->getMessage(),
                    ];
                }
            }

            if ($success === 0 && $failed === 0) {
                throw new \RuntimeException('Data dosen kosong atau format tidak sesuai.');
            }

            response_json('Import dosen selesai.', [
                'success' => $success,
                'failed' => $failed,
                'errors' => $errors,
            ]);
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        } finally {
            if (is_file($filePath)) {
                unlink($filePath);
            }
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

    public function plotting()
    {
        view_with_layout(
            'kaprodi/plotting/index',
            'Plotting Mahasiswa',
            null,
            [],
            'css/datatable',
            'script/datatable'
        );
    }

    public function plotting_data()
    {
        try {
            $uc = new PlottingCase();
            $data = $uc->getData();
            $data['counts'] = $uc->getUnassignedCounts();
            response_json('OK', $data);
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function plotting_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $dosenId = (int) $this->input->post('dosen_id');
            $schoolId = (int) $this->input->post('school_id');
            $studentIds = $this->input->post('student_ids') ?? [];
            $studentIds = array_values(array_filter((array) $studentIds, function ($id) {
                return $id !== null && $id !== '';
            }));
            if (count($studentIds) < 5) {
                response_error('Minimal 5 mahasiswa wajib dipilih untuk plotting.', null, 422);
                return;
            }
            if (count($studentIds) > 13) {
                response_error('Maksimal 13 mahasiswa dapat dipilih untuk plotting.', null, 422);
                return;
            }
            $currentDosenId = $this->input->post('current_dosen_id');
            $currentDosenId = $currentDosenId !== null && $currentDosenId !== '' ? (int) $currentDosenId : null;

            $uc = new PlottingCase();
            $uc->savePlotting($dosenId, $schoolId, (array) $studentIds, $currentDosenId);
            response_json('Plotting berhasil disimpan.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function plotting_delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $dosenId = (int) $this->input->post('dosen_id');
            $uc = new PlottingCase();
            $uc->deletePlotting($dosenId);
            response_json('Plotting berhasil dihapus.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
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
