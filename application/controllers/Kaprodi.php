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

    public function sekolah()
    {
        $activeProgram = $this->getActiveProgram();
        $programId = $activeProgram ? (int) $activeProgram['id'] : 0;
        $prodiId = $this->getCurrentProdiId();
        $prodiInfo = $prodiId > 0
            ? $this->db->select('nama, fakultas')->from('prodi')->where('id', $prodiId)->get()->row_array()
            : null;

        $programSekolahOptions = [];
        $rows = [];
        $blockedProgramSekolahIds = [];

        if ($programId > 0) {
            $programSekolahOptions = $this->db
                ->select('ps.id, sekolah.nama')
                ->from('program_sekolah ps')
                ->join('sekolah', 'sekolah.id = ps.id_sekolah', 'inner')
                ->where('ps.id_program', $programId)
                ->order_by('sekolah.nama', 'ASC')
                ->get()
                ->result_array();

            if ($prodiId > 0) {
                $groupProdiIds = $this->getProdiGroupIds($prodiInfo ? (string) $prodiInfo['nama'] : '');
                if (!empty($groupProdiIds)) {
                    $groupProdiIds = array_values(array_diff($groupProdiIds, [$prodiId]));
                }
                if (!empty($groupProdiIds)) {
                    $blockedProgramSekolahIds = $this->db
                        ->select('psp.id_program_sekolah')
                        ->from('program_sekolah_prodi psp')
                        ->join('program_sekolah ps', 'ps.id = psp.id_program_sekolah', 'inner')
                        ->where('ps.id_program', $programId)
                        ->where_in('psp.id_prodi', $groupProdiIds)
                        ->get()
                        ->result_array();
                    $blockedProgramSekolahIds = array_map('intval', array_column($blockedProgramSekolahIds, 'id_program_sekolah'));
                }

                $rows = $this->db
                    ->select('psp.id, psp.surat_mou, psp.status, ps.id AS program_sekolah_id, sekolah.nama AS nama_sekolah')
                    ->from('program_sekolah_prodi psp')
                    ->join('program_sekolah ps', 'ps.id = psp.id_program_sekolah', 'inner')
                    ->join('sekolah', 'sekolah.id = ps.id_sekolah', 'inner')
                    ->where('psp.id_prodi', $prodiId)
                    ->where('ps.id_program', $programId)
                    ->order_by('sekolah.nama', 'ASC')
                    ->get()
                    ->result_array();
            }
        }

        $viewData = [
            'activeProgram' => $activeProgram,
            'programSekolahOptions' => $programSekolahOptions,
            'rows' => $rows,
            'prodiInfo' => $prodiInfo,
            'blockedProgramSekolahIds' => $blockedProgramSekolahIds,
        ];

        view_with_layout('kaprodi/sekolah/index', 'Sekolah Kerja Sama', null, $viewData);
    }

    public function sekolah_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        $prodiId = $this->getCurrentProdiId();
        if ($prodiId <= 0) {
            $this->session->set_flashdata('error', 'Program studi tidak ditemukan.');
            redirect('kaprodi/sekolah');
            return;
        }

        $agreement = $this->input->post('agreement', true);
        if ($agreement !== 'yes') {
            $this->session->set_flashdata('error', 'Pernyataan wajib disetujui.');
            redirect('kaprodi/sekolah');
            return;
        }

        $programSekolahIds = $this->input->post('program_sekolah_id');
        $programSekolahIds = array_values(array_filter((array) $programSekolahIds, function ($id) {
            return $id !== null && $id !== '';
        }));
        $programSekolahIds = array_map('intval', $programSekolahIds);
        if (empty($programSekolahIds)) {
            $this->session->set_flashdata('error', 'Sekolah wajib dipilih.');
            redirect('kaprodi/sekolah');
            return;
        }

        $activeProgram = $this->getActiveProgram();
        $programId = $activeProgram ? (int) $activeProgram['id'] : 0;
        if ($programId <= 0) {
            $this->session->set_flashdata('error', 'Program aktif tidak ditemukan.');
            redirect('kaprodi/sekolah');
            return;
        }

        $prodiInfo = $this->db
            ->select('nama')
            ->from('prodi')
            ->where('id', $prodiId)
            ->limit(1)
            ->get()
            ->row_array();

        $now = date('Y-m-d H:i:s');
        $userId = (int) $this->session->userdata('id_user');

        $this->db->trans_begin();

        try {
            $groupProdiIds = [];
            if (!empty($prodiInfo) && !empty($prodiInfo['nama'])) {
                $groupProdiIds = $this->getProdiGroupIds((string) $prodiInfo['nama']);
                if (!empty($groupProdiIds)) {
                    $groupProdiIds = array_values(array_diff($groupProdiIds, [$prodiId]));
                }
            }

            $programSekolahRows = $this->db
                ->select('id')
                ->from('program_sekolah')
                ->where('id_program', $programId)
                ->where_in('id', $programSekolahIds)
                ->get()
                ->result_array();

            if (empty($programSekolahRows)) {
                throw new \RuntimeException('Sekolah tidak terdaftar pada program aktif.');
            }

            $validIds = array_map('intval', array_column($programSekolahRows, 'id'));

            if (!empty($groupProdiIds)) {
                $conflictRow = $this->db
                    ->select('psp.id_program_sekolah')
                    ->from('program_sekolah_prodi psp')
                    ->join('program_sekolah ps', 'ps.id = psp.id_program_sekolah', 'inner')
                    ->where('ps.id_program', $programId)
                    ->where_in('psp.id_program_sekolah', $validIds)
                    ->where_in('psp.id_prodi', $groupProdiIds)
                    ->limit(1)
                    ->get()
                    ->row();

                if ($conflictRow) {
                    throw new \RuntimeException('Sekolah sudah digunakan oleh prodi yang setara. Pilih sekolah lain.');
                }
            }

            $existingRows = $this->db
                ->select('id, id_program_sekolah')
                ->from('program_sekolah_prodi')
                ->where('id_prodi', $prodiId)
                ->where_in('id_program_sekolah', $validIds)
                ->get()
                ->result_array();

            $existingMap = [];
            foreach ($existingRows as $row) {
                $existingMap[(int) $row['id_program_sekolah']] = (int) $row['id'];
            }

            foreach ($validIds as $programSekolahId) {
                if (isset($existingMap[$programSekolahId])) {
                    $this->db
                        ->where('id', $existingMap[$programSekolahId])
                        ->update('program_sekolah_prodi', [
                            'status' => 'verified',
                            'updated_at' => $now,
                            'updated_by' => $userId ?: null,
                        ]);
                } else {
                    $this->db->insert('program_sekolah_prodi', [
                        'id_program_sekolah' => $programSekolahId,
                        'id_prodi' => $prodiId,
                        'surat_mou' => null,
                        'status' => 'verified',
                        'created_at' => $now,
                        'updated_at' => $now,
                        'created_by' => $userId ?: null,
                        'updated_by' => $userId ?: null,
                    ]);
                }
            }

            if ($this->db->trans_status() === false) {
                throw new \RuntimeException('Gagal menyimpan sekolah mitra.');
            }

            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Sekolah mitra berhasil disimpan. Status verifikasi: verified.');
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', $th->getMessage());
        }

        redirect('kaprodi/sekolah');
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
            $prodiRow = $this->db
                ->select('prodi.nama')
                ->from('dosen')
                ->join('prodi', 'prodi.id = dosen.id_prodi', 'left')
                ->where('dosen.id', $dosenId)
                ->limit(1)
                ->get()
                ->row_array();
            $prodiName = $prodiRow && !empty($prodiRow['nama']) ? (string) $prodiRow['nama'] : '';
            $maxStudents = $this->getMaxStudentsForProdiName($prodiName);
            if (count($studentIds) < 5) {
                response_error('Minimal 5 mahasiswa wajib dipilih untuk plotting.', null, 422);
                return;
            }
            if (count($studentIds) > $maxStudents) {
                response_error('Maksimal ' . $maxStudents . ' mahasiswa dapat dipilih untuk plotting.', null, 422);
                return;
            }
            $currentDosenId = $this->input->post('current_dosen_id');
            $currentDosenId = $currentDosenId !== null && $currentDosenId !== '' ? (int) $currentDosenId : null;
            $currentSchoolId = $this->input->post('current_school_id');
            $currentSchoolId = $currentSchoolId !== null && $currentSchoolId !== '' ? (int) $currentSchoolId : null;

            $uc = new PlottingCase();
            $uc->savePlotting($dosenId, $schoolId, (array) $studentIds, $currentDosenId, $currentSchoolId);
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
            $schoolId = (int) $this->input->post('school_id');
            $uc = new PlottingCase();
            $uc->deletePlotting($dosenId, $schoolId);
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

    private function getCurrentProdiId(): int
    {
        $idUser = (int) $this->session->userdata('id_user');
        if (!$idUser) {
            return 0;
        }

        $record = $this->db
            ->select('id_prodi')
            ->from('kaprodi')
            ->where('id_user', $idUser)
            ->limit(1)
            ->get()
            ->row();

        return !empty($record) && !empty($record->id_prodi) ? (int) $record->id_prodi : 0;
    }

    private function getActiveProgram(): ?array
    {
        $row = $this->db
            ->select('id, kode, nama, tahun_ajaran')
            ->from('program')
            ->where('active', 1)
            ->order_by('updated_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        return $row ?: null;
    }

    private function getProdiGroupIds(string $currentProdiName): array
    {
        $current = trim($currentProdiName);
        if ($current === '') {
            return [];
        }

        $groups = [
            ['Pendidikan Jasmani Kesehatan dan Rekreasi', 'Pendidikan Kepelatihan Olahraga'],
            ['Pendidikan Tari', 'Pendidikan Seni Rupa', 'Pendidikan Musik'],
        ];

        $matchedGroup = [];
        foreach ($groups as $group) {
            foreach ($group as $name) {
                if (strcasecmp($name, $current) === 0) {
                    $matchedGroup = $group;
                    break 2;
                }
            }
        }

        if (empty($matchedGroup)) {
            return [];
        }

        $rows = $this->db
            ->select('id')
            ->from('prodi')
            ->where_in('nama', $matchedGroup)
            ->get()
            ->result_array();

        return array_map('intval', array_column($rows, 'id'));
    }

    private function getMaxStudentsForProdiName(string $prodiName): int
    {
        $normalized = strtolower(trim($prodiName));
        if ($normalized == 'pendidikan tari') {
            return 17;
        }
        if ($normalized == 'pendidikan musik') {
            return 15;
        }
        return 13;
    }
}
