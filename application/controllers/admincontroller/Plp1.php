<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/admincontroller/Modulebaseadmin.php');
use Imports\ImportMahasiswaTrue;
use Imports\ImportSekolah;
use UseCases\Admin\MahasiswaCase;
use UseCases\Admin\MahasiswaTrueCase;
use UseCases\Admin\ModuleMasterDataCase;
use UseCases\Admin\ProgramCase;
use UseCases\Admin\ProdiCase;
use UseCases\Admin\SekolahCase;

class Plp1 extends Modulebaseadmin
{
    protected $moduleLabel = 'PLP I';
    protected $moduleSlug  = 'plp';
    protected $exclude_methods = ['verifikasi_mahasiswa_export'];
    protected $pageDescriptions = [
        'master_data'         => 'Master data PLP I berisi relasi mahasiswa dengan dosen pembimbing, guru pamong, dan sekolah mitra aktif.',
        'activities'          => 'Pantau seluruh aktivitas mahasiswa PLP I dari tahap briefing hingga monitoring lapangan.',
        'report'              => 'Konsolidasi laporan mingguan maupun akhir agar dapat segera disahkan.',
        'absensi'             => 'Review data kehadiran mahasiswa dan guru pamong secara terpusat.',
        'verifikasi_mahasiswa'=> 'Kelola proses pemeriksaan dokumen mahasiswa sebelum diteruskan ke dosen pembimbing.',
        'verifikasi_guru'     => 'Pastikan data guru pamong terverifikasi sebelum diberi akses akun.',
        'verifikasi_kepsek'   => 'Pantau verifikasi akun kepala sekolah dan kelengkapan berkasnya.',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // dd(true);
        $data = $this->buildDashboardData('activities', 'Kegiatan');
        view_with_layout('admin/plp1/index', 'Admin PLP I - Dashboard', 'admin_plp1', $data);
    }

    public function activities()
    {
        $data = $this->buildDashboardData('activities', 'Kegiatan');
        view_with_layout('admin/plp1/modules/under_development', 'Admin PLP I - Kegiatan', 'admin_plp1', $data);
    }

    public function report()
    {
        $this->renderModulePage('report', 'Laporan');
    }

    public function absensi()
    {
        $this->renderModulePage('absensi', 'Absensi');
    }

    public function master_data()
    {
        redirect('admin/plp1/master-data/mahasiswa');
        return;
    }

    public function master_data_sekolah()
    {
        $activeProgram = $this->getActivePlpProgram();
        $viewData = [
            'activeProgram' => $activeProgram,
        ];

        view_with_layout(
            'admin/plp1/master-data/sekolah/index',
            'Admin PLP I - Master Data Sekolah',
            'admin_plp1',
            $viewData,
            null,
            'script/datatable'
        );
    }

    public function master_data_dosen()
    {
        $this->renderMasterDataEntity('dosen');
    }

    public function master_data_mahasiswa()
    {
        $this->renderMasterDataEntity('mahasiswa');
    }

    public function master_data_mahasiswa_true()
    {
        $programCase = new ProgramCase();
        $programOptions = $programCase->listActive();
        $viewData = [
            'programOptions'   => $programOptions,
            'defaultProgramId' => $programOptions[0]['id'] ?? null,
        ];

        view_with_layout(
            'admin/plp1/master-data/mahasiswa_true/index',
            'Admin PLP I - Data Mahasiswa Admin',
            'admin_plp1',
            $viewData,
            null,
            'script/datatable'
        );
    }

    public function master_data_guru()
    {
        $this->renderMasterDataEntity('guru');
    }

    public function master_data_kepsek()
    {
        $this->renderMasterDataEntity('kepsek');
    }

    public function verifikasi_mahasiswa()
    {
        $viewData = [
            'programOptions' => $this->formatProgramOptions($this->getPlpPrograms()),
            'defaultStatus'  => 'unverified',
        ];

        view_with_layout(
            'admin/plp1/verifikasi_mahasiswa',
            'Admin PLP I - Verifikasi Mahasiswa',
            'admin_plp1',
            $viewData,
            null,
            'script/datatable'
        );
    }

    public function verifikasi_guru()
    {
        $this->renderModulePage('verifikasi_guru', 'Verifikasi Guru');
    }

    public function verifikasi_kepsek()
    {
        $this->renderModulePage('verifikasi_kepsek', 'Verifikasi Kepala Sekolah');
    }

    public function verifikasi_sekolah()
    {
        $viewData = [
            'programOptions' => $this->formatProgramOptions($this->getPlpPrograms()),
            'defaultStatus'  => 'unverified',
        ];

        view_with_layout(
            'admin/plp1/verifikasi_sekolah',
            'Admin PLP I - Verifikasi Sekolah',
            'admin_plp1',
            $viewData,
            null,
            'script/datatable'
        );
    }

    public function verifikasi_sekolah_datatable()
    {
        $req = get_param_datatable();
        $programId = (int) ($this->input->post('program_id') ?? 0);
        $status = $this->input->post('verification_status', true);

        $this->db->select('
            psp.id AS psp_id,
            psp.status,
            psp.surat_mou,
            psp.created_at,
            prodi.nama AS nama_prodi,
            prodi.fakultas AS fakultas,
            sekolah.nama AS nama_sekolah,
            program.id AS id_program,
            program.kode AS kode_program,
            program.nama AS nama_program,
            program.tahun_ajaran
        ');
        $this->db->from('program_sekolah_prodi psp');
        $this->db->join('program_sekolah ps', 'ps.id = psp.id_program_sekolah', 'inner');
        $this->db->join('sekolah', 'sekolah.id = ps.id_sekolah', 'inner');
        $this->db->join('prodi', 'prodi.id = psp.id_prodi', 'inner');
        $this->db->join('program', 'program.id = ps.id_program', 'inner');
        $this->db->where('program.kode', 'plp1');

        if ($programId > 0) {
            $this->db->where('program.id', $programId);
        }

        if (!empty($status) && $status !== 'all') {
            if ($status === 'unverified') {
                $this->db->where(
                    '(psp.status IS NULL OR psp.status = ' . $this->db->escape($status) . ')',
                    null,
                    false
                );
            } else {
                $this->db->where('psp.status', $status);
            }
        }

        $count_total = $this->db->count_all_results('', false);

        $search = trim((string) $req['search']);
        if ($search !== '') {
            $this->db->group_start()
                ->like('sekolah.nama', $search)
                ->or_like('prodi.nama', $search)
                ->or_like('prodi.fakultas', $search)
                ->or_like('program.nama', $search)
                ->or_like('program.kode', $search)
                ->or_like('program.tahun_ajaran', $search)
                ->group_end();
        }

        $count_filtered = $this->db->count_all_results('', false);

        $orderMap = [
            'nama_sekolah' => 'sekolah.nama',
            'nama_prodi' => 'prodi.nama',
            'program' => 'program.nama',
            'status' => 'psp.status',
        ];
        $orderColumn = $orderMap[$req['order_column']] ?? 'sekolah.nama';
        $orderDir = strtolower((string) $req['order_dir']) === 'desc' ? 'desc' : 'asc';
        $this->db->limit($req['length'], $req['start']);
        $this->db->order_by($orderColumn, $orderDir);

        $rows = $this->db->get()->result();
        $formatted = [];
        foreach ($rows as $row) {
            $statusValue = $row->status ?: 'unverified';
            $formatted[] = [
                'id' => (int) $row->psp_id,
                'nama_sekolah' => $row->nama_sekolah,
                'nama_prodi' => $row->nama_prodi,
                'fakultas' => $row->fakultas,
                'kode_program' => $row->kode_program,
                'nama_program' => $row->nama_program,
                'tahun_ajaran' => $row->tahun_ajaran,
                'status' => $statusValue,
                'surat_mou' => $row->surat_mou,
            ];
        }

        datatable_response_array(
            $req['draw'],
            $count_total,
            $count_filtered,
            $formatted
        );
    }

    public function verifikasi_sekolah_detail($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        $row = $this->db
            ->select('
                psp.id,
                psp.status,
                psp.surat_mou,
                psp.created_at,
                psp.updated_at,
                prodi.nama AS nama_prodi,
                prodi.fakultas AS fakultas,
                sekolah.nama AS nama_sekolah,
                program.kode AS kode_program,
                program.nama AS nama_program,
                program.tahun_ajaran
            ')
            ->from('program_sekolah_prodi psp')
            ->join('program_sekolah ps', 'ps.id = psp.id_program_sekolah', 'inner')
            ->join('sekolah', 'sekolah.id = ps.id_sekolah', 'inner')
            ->join('prodi', 'prodi.id = psp.id_prodi', 'inner')
            ->join('program', 'program.id = ps.id_program', 'inner')
            ->where('psp.id', (int) $id)
            ->limit(1)
            ->get()
            ->row();

        if (!$row) {
            response_error('Data MOU tidak ditemukan.', null, 404);
            return;
        }

        $data = [
            'id' => (int) $row->id,
            'nama_sekolah' => $row->nama_sekolah,
            'nama_prodi' => $row->nama_prodi,
            'fakultas' => $row->fakultas,
            'kode_program' => $row->kode_program,
            'nama_program' => $row->nama_program,
            'tahun_ajaran' => $row->tahun_ajaran,
            'surat_mou' => $row->surat_mou,
            'status' => $row->status ?: 'unverified',
            'created_at' => $row->created_at,
            'updated_at' => $row->updated_at,
        ];

        response_json('OK', $data);
    }

    public function verifikasi_sekolah_update_status($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        $status = $this->input->post('status', true);
        $normalizedStatus = strtolower(trim((string) $status));
        $allowedStatuses = ['verified', 'rejected'];
        if (!in_array($normalizedStatus, $allowedStatuses, true)) {
            response_error('Status verifikasi tidak dikenal.', null, 422);
            return;
        }

        $exists = $this->db
            ->select('id')
            ->from('program_sekolah_prodi')
            ->where('id', (int) $id)
            ->limit(1)
            ->get()
            ->row();
        if (!$exists) {
            response_error('Data MOU tidak ditemukan.', null, 404);
            return;
        }

        $now = date('Y-m-d H:i:s');
        $userId = $this->session->userdata('id_user');

        $this->db
            ->where('id', (int) $id)
            ->update('program_sekolah_prodi', [
                'status' => $normalizedStatus,
                'updated_at' => $now,
                'updated_by' => $userId ? (int) $userId : null,
            ]);

        response_json('Status verifikasi MOU berhasil diperbarui.');
    }

    public function verifikasi_mahasiswa_datatable()
    {
        $req = get_param_datatable();
        $req['filter_program_type'] = 'plp1';
        $programId = (int) ($this->input->post('program_id') ?? 0);
        if ($programId > 0) {
            $req['filter_program'] = $programId;
        }
        $status = $this->input->post('verification_status', true);
        $req['filter_status'] = $status !== '' ? $status : 'unverified';

        $uc = new MahasiswaCase();
        $data = $uc->datatable($req);

        datatable_response_array(
            $req['draw'],
            $data['count_total'],
            $data['count_filtered'],
            $data['formatted']
        );
    }

    public function verifikasi_mahasiswa_export()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        $programId = (int) ($this->input->get('program_id') ?? 0);
        $status = $this->input->get('verification_status', true);
        $search = trim((string) $this->input->get('search', true));

        $filters = [
            'filter_program_type' => 'plp1',
            'filter_status' => $status !== '' ? $status : 'unverified',
        ];

        if ($programId > 0) {
            $filters['filter_program'] = $programId;
        }

        if ($search !== '') {
            $filters['search'] = $search;
        }

        $uc = new MahasiswaCase();
        $rows = $uc->exportVerification($filters);
        if ($this->input->get('empty', true) === '1') {
            $rows = [];
        }

        $columns = [
            'Nama',
            'NIM',
            'Email',
            'No HP',
            'Program Studi',
            'Fakultas',
            'Sekolah',
            'Program',
            'Tahun Ajaran',
            'Status',
        ];

        $filename = 'verifikasi_mahasiswa_plp1_' . date('Ymd');
        exportMahasiswaPendaftar::export($filename, $columns, $rows);
    }

    public function verifikasi_mahasiswa_detail($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new MahasiswaCase();
            $detail = $uc->detailForVerification((int) $id);
            response_json('OK', $detail);
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function verifikasi_mahasiswa_update_status($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        $status = $this->input->post('status', true);
        if ($status === null) {
            response_error('Status tidak boleh kosong.', null, 422);
            return;
        }

        try {
            $uc = new MahasiswaCase();
            $uc->updateVerificationStatus((int) $id, $status);
            response_json('Status verifikasi berhasil diperbarui.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function verifikasi_mahasiswa_update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new MahasiswaCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->update((int) $id, $payload);
            response_json('Data mahasiswa berhasil diperbarui.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function verifikasi_mahasiswa_delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new MahasiswaCase();
            $uc->deleteRegistration((int) $id);
            response_json('Data mahasiswa berhasil dihapus.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function master_data_sekolah_datatable()
    {
        $req = get_param_datatable();
        $activeProgram = $this->getActivePlpProgram();
        $programId = $activeProgram ? (int) $activeProgram['id'] : 0;
        if ($programId <= 0) {
            datatable_response_array($req['draw'], 0, 0, []);
            return;
        }

        $uc = new SekolahCase();
        $data = $uc->datatableByProgram($req, $programId);

        datatable_response_array(
            $req['draw'],
            $data['count_total'],
            $data['count_filtered'],
            $data['formatted']
        );
    }

    public function master_data_sekolah_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        $activeProgram = $this->getActivePlpProgram();
        $programId = $activeProgram ? (int) $activeProgram['id'] : 0;
        if ($programId <= 0) {
            response_error('Program aktif tidak tersedia.', null, 422);
            return;
        }

        try {
            $uc = new SekolahCase();
            $payload = $this->input->post(null, true) ?? [];
            $payload['id_program'] = $programId;
            $uc->create($payload);
            response_json('Sekolah berhasil dibuat.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function master_data_sekolah_update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        $activeProgram = $this->getActivePlpProgram();
        $programId = $activeProgram ? (int) $activeProgram['id'] : 0;
        if ($programId <= 0) {
            response_error('Program aktif tidak tersedia.', null, 422);
            return;
        }

        try {
            $uc = new SekolahCase();
            $payload = $this->input->post(null, true) ?? [];
            $payload['id_program'] = $programId;
            $uc->update((int) $id, $payload);
            response_json('Sekolah berhasil diperbarui.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function master_data_sekolah_delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $activeProgram = $this->getActivePlpProgram();
            $programId = $activeProgram ? (int) $activeProgram['id'] : 0;
            if ($programId <= 0) {
                response_error('Program aktif tidak tersedia.', null, 422);
                return;
            }

            $uc = new SekolahCase();
            $uc->deleteByProgram((int) $id, $programId);
            response_json('Sekolah berhasil dihapus.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function master_data_sekolah_import()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        $activeProgram = $this->getActivePlpProgram();
        $programCode = $activeProgram['kode'] ?? 'plp1';

        try {
            $importer = new ImportSekolah();
            $importer->import_sekolah($programCode);
            response_json('Import sekolah berhasil.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function master_data_dosen_datatable()
    {
        $this->masterDataDatatableResponse('dosen');
    }

    public function master_data_mahasiswa_datatable()
    {
        $this->masterDataDatatableResponse('mahasiswa');
    }

    public function master_data_guru_datatable()
    {
        $this->masterDataDatatableResponse('guru');
    }

    public function master_data_kepsek_datatable()
    {
        $this->masterDataDatatableResponse('kepsek');
    }

    public function master_data_mahasiswa_true_datatable()
    {
        $req = get_param_datatable();
        $req['filter_program'] = (int) ($this->input->post('program_id') ?? 0);

        $uc = new MahasiswaTrueCase();
        $data = $uc->datatable($req);

        datatable_response_array(
            $req['draw'],
            $data['count_total'],
            $data['count_filtered'],
            $data['formatted']
        );
    }

    public function master_data_mahasiswa_true_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new MahasiswaTrueCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->create($payload);
            response_json('Data mahasiswa acuan berhasil disimpan.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function master_data_mahasiswa_true_update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new MahasiswaTrueCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->update((int) $id, $payload);
            response_json('Data mahasiswa acuan berhasil diperbarui.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function master_data_mahasiswa_true_delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new MahasiswaTrueCase();
            $uc->delete((int) $id);
            response_json('Data mahasiswa acuan berhasil dihapus.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function master_data_mahasiswa_true_import()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $importer = new ImportMahasiswaTrue();
            $programCode = $this->input->post('program_code', true);
            if ($programCode === null || trim((string) $programCode) === '') {
                $programCode = $this->input->post('program_kode', true);
            }
            $importer->import_mahasiswa_true((string) $programCode);
            response_json('Import mahasiswa berhasil.');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function filter_sekolah()
    {
        try {
            $uc = new SekolahCase();
            $data = $uc->listForFilter();
            response_json('OK', $data);
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function filter_prodi()
    {
        try {
            $uc = new ProdiCase();
            $data = $uc->listForFilter();
            response_json('OK', $data);
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function filter_fakultas()
    {
        try {
            $uc = new ProdiCase();
            $data = $uc->listFakultas();
            response_json('OK', $data);
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function filter_program()
    {
        try {
            $uc = new ProgramCase();
            $data = $uc->listForFilter();
            response_json('OK', $data);
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    protected function renderMasterDataEntity(string $entityKey): void
    {
        $configs = $this->getMasterDataConfigs();
        if (!array_key_exists($entityKey, $configs)) {
            show_404();
            return;
        }

        $activeProgram = $this->getActivePlpProgram();
        $config = $configs[$entityKey];
        $viewData = [
            'activeProgram' => $activeProgram,
            'entityKey'     => $entityKey,
            'config'        => $config,
            'datatablePath' => sprintf('admin/plp1/master-data/%s/datatable', $entityKey),
        ];

        $title = sprintf('Admin PLP I - Master Data %s', $config['title']);
        view_with_layout(
            'admin/plp1/master_data_entity',
            $title,
            'admin_plp1',
            $viewData,
            null,
            'script/datatable'
        );
    }

    protected function masterDataDatatableResponse(string $entityKey): void
    {
        $req = get_param_datatable();
        $req['filter_program_code'] = 'plp1';
        $activeProgram = $this->getActivePlpProgram();
        $req['filter_program_id'] = $activeProgram ? (int) $activeProgram['id'] : 0;

        $uc = new ModuleMasterDataCase();
        $data = $uc->datatableByEntity($entityKey, $req);

        datatable_response_array(
            $req['draw'],
            $data['count_total'],
            $data['count_filtered'],
            $data['formatted']
        );
    }

    protected function getPlpPrograms(): array
    {
        return $this->db->select('id, kode, nama, tahun_ajaran')
            ->from('program')
            ->where('kode', 'plp1')
            ->order_by('tahun_ajaran', 'DESC')
            ->get()
            ->result_array();
    }

    protected function getActivePlpProgram(): ?array
    {
        $row = $this->db->select('id, nama, kode, tahun_ajaran')
            ->from('program')
            ->where('kode', 'plp1')
            ->where('active', 1)
            ->order_by('tahun_ajaran', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        return $row ?: null;
    }

    protected function formatProgramOptions(array $programs): array
    {
        $options = [];
        foreach ($programs as $program) {
            $label = '';
            if (!empty($program['kode'])) {
                $label .= strtoupper($program['kode']);
            } elseif (!empty($program['nama'])) {
                $label .= $program['nama'];
            } else {
                $label .= 'Program';
            }
            if (!empty($program['tahun_ajaran'])) {
                $label .= ' (' . $program['tahun_ajaran'] . ')';
            }

            $options[] = [
                'id'    => (int) $program['id'],
                'label' => trim($label),
            ];
        }

        return $options;
    }

    protected function renderModulePage(string $pageKey, string $pageTitle): void
    {
        $data = $this->buildDashboardData($pageKey, $pageTitle);
        $title = sprintf('Admin PLP I - %s', $pageTitle);
        view_with_layout('admin/plp1/modules/under_development', $title, 'admin_plp1', $data);
    }

    private function buildDashboardData(string $pageKey, string $pageTitle): array
    {
        $activeProgram = $this->getActivePlpProgram();
        $programId = $activeProgram ? (int) $activeProgram['id'] : 0;

        $summary = [
            'mahasiswa' => $programId > 0
                ? (int) $this->db->where('id_program', $programId)->count_all_results('program_mahasiswa')
                : 0,
            'dosen' => $programId > 0
                ? (int) $this->db->where('id_program', $programId)->count_all_results('program_dosen')
                : 0,
            'kepsek' => $programId > 0
                ? (int) $this->db->where('id_program', $programId)->count_all_results('program_kepsek')
                : 0,
            'sekolah' => $programId > 0
                ? (int) $this->db->where('id_program', $programId)->count_all_results('program_sekolah')
                : 0,
        ];

        return [
            'module_label'   => $this->moduleLabel,
            'page_title'     => $pageTitle,
            'description'    => $this->pageDescriptions[$pageKey] ?? '',
            'active_program' => $activeProgram,
            'summary'        => $summary,
        ];
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
                'body'  => 'Akses menu telah aktif. Endpoint layanan ' . $lowerTitle . ' sedang disiapkan.',
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

    private function getMasterDataConfigs(): array
    {
        return [
            'sekolah' => [
                'title'       => 'Sekolah',
                'description' => 'Daftar sekolah mitra yang memiliki mahasiswa PLP I pada program aktif.',
                'columns'     => [
                    ['data' => 'school_name', 'label' => 'Sekolah'],
                    ['data' => 'alamat', 'label' => 'Alamat'],
                    ['data' => 'total_students', 'label' => 'Mahasiswa', 'className' => 'text-center'],
                    ['data' => 'total_teachers', 'label' => 'Guru Pamong', 'className' => 'text-center'],
                    ['data' => 'total_principals', 'label' => 'Kepala Sekolah', 'className' => 'text-center'],
                ],
            ],
            'dosen' => [
                'title'       => 'Dosen Pembimbing',
                'description' => 'Daftar dosen pembimbing yang membina mahasiswa PLP I aktif.',
                'columns'     => [
                    ['data' => 'lecturer_name', 'label' => 'Dosen'],
                    ['data' => 'email', 'label' => 'Email'],
                    ['data' => 'phone', 'label' => 'No HP'],
                    ['data' => 'nama_prodi', 'label' => 'Program Studi'],
                    ['data' => 'total_students', 'label' => 'Mahasiswa Binaan', 'className' => 'text-center'],
                    ['data' => 'id', 'label' => 'Aksi', 'type' => 'edit_action', 'className' => 'text-end'],
                ],
            ],
            'mahasiswa' => [
                'title'       => 'Mahasiswa',
                'description' => 'Daftar mahasiswa yang terdaftar di program PLP I aktif.',
                'columns'     => [
                    ['data' => 'student_name', 'label' => 'Mahasiswa'],
                    ['data' => 'nim', 'label' => 'NIM'],
                    ['data' => 'school_name', 'label' => 'Sekolah'],
                    ['data' => 'teacher_name', 'label' => 'Guru Pamong'],
                    ['data' => 'lecturer_name', 'label' => 'DPL'],
                    ['data' => 'id', 'label' => 'Aksi', 'type' => 'detail_action', 'className' => 'text-end'],
                ],
            ],
            'guru' => [
                'title'       => 'Guru Pamong',
                'description' => 'Guru pamong yang aktif membimbing mahasiswa PLP I pada program berjalan.',
                'columns'     => [
                    ['data' => 'teacher_name', 'label' => 'Guru'],
                    ['data' => 'school_name', 'label' => 'Sekolah'],
                    ['data' => 'email', 'label' => 'Email'],
                    ['data' => 'phone', 'label' => 'No HP'],
                    ['data' => 'total_students', 'label' => 'Mahasiswa Binaan', 'className' => 'text-center'],
                ],
            ],
            'kepsek' => [
                'title'       => 'Kepala Sekolah',
                'description' => 'Kepala sekolah mitra yang terhubung dengan program PLP I aktif.',
                'columns'     => [
                    ['data' => 'principal_name', 'label' => 'Kepala Sekolah'],
                    ['data' => 'school_name', 'label' => 'Sekolah'],
                    ['data' => 'email', 'label' => 'Email'],
                    ['data' => 'phone', 'label' => 'No HP'],
                    ['data' => 'status_pembayaran', 'label' => 'Status Pembayaran', 'type' => 'payment_badge', 'className' => 'text-center'],
                ],
            ],
        ];
    }
}
