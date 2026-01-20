<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/superadmincontroller/Modulebase.php');

use UseCases\Superadmin\MahasiswaCase;
use UseCases\Superadmin\ModuleMasterDataCase;
use UseCases\Superadmin\SekolahCase;
use UseCases\Superadmin\ProgramCase;
use Imports\ImportSekolah;

class Plp1 extends Modulebase
{
    protected $moduleLabel = 'PLP I';
    protected $moduleSlug  = 'plp';
    protected $exclude_methods = ['verifikasi_mahasiswa_export'];
    protected $pageDescriptions = [
        'master_data'         => 'Master data PLP I berisi relasi mahasiswa dengan dosen pembimbing, guru pamong, dan sekolah mitra aktif.',
        'activities'            => 'Pantau seluruh aktivitas mahasiswa PLP I dari tahap briefing hingga monitoring lapangan.',
        'report'                => 'Konsolidasi laporan mingguan maupun akhir agar dapat segera disahkan.',
        'absensi'               => 'Review data kehadiran mahasiswa dan guru pamong secara terpusat.',
        'verifikasi_mahasiswa'  => 'Kelola proses pemeriksaan dokumen mahasiswa sebelum diteruskan ke dosen pembimbing.',
        'verifikasi_guru'       => 'Pastikan data guru pamong terverifikasi sebelum diberi akses akun.',
        'verifikasi_kepsek'     => 'Pantau verifikasi akun kepala sekolah dan kelengkapan berkasnya.',
    ];

    public function activities()
    {
        $this->renderModulePage('activities', 'Kegiatan');
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
        redirect('super-admin/plp/master-data/mahasiswa');
        return;
    }

    public function master_data_sekolah()
    {
        $activeProgram = $this->getActivePlpProgram();
        $viewData = [
            'activeProgram' => $activeProgram,
        ];

        view_with_layout(
            'super_admin/plp1/master-data/sekolah/index',
            'Super Admin - PLP I Master Data Sekolah',
            'super_admin',
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
            'super_admin/plp1/master-data/mahasiswa_true/index',
            'Super Admin - PLP I Data Mahasiswa Admin',
            'super_admin',
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
            'super_admin/plp1/verifikasi_mahasiswa',
            'Super Admin - PLP I Verifikasi Mahasiswa',
            'super_admin',
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
            'super_admin/plp1/verifikasi_sekolah',
            'Super Admin - PLP I Verifikasi Sekolah',
            'super_admin',
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
        // dd(true);
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
        // dd($filename);
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
            'datatablePath' => sprintf('super-admin/plp/master-data/%s/datatable', $entityKey),
        ];

        $title = sprintf('Super Admin - PLP I Master Data %s', $config['title']);
        view_with_layout(
            'super_admin/plp1/master_data_entity',
            $title,
            'super_admin',
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
                ],
            ],
            'mahasiswa' => [
                'title'       => 'Mahasiswa',
                'description' => 'Daftar mahasiswa yang terdaftar di program PLP I aktif.',
                'columns'     => [
                    ['data' => 'student_name', 'label' => 'Mahasiswa'],
                    ['data' => 'nim', 'label' => 'NIM'],
                    ['data' => 'email', 'label' => 'Email'],
                    ['data' => 'phone', 'label' => 'No HP'],
                    ['data' => 'program_studi', 'label' => 'Program Studi'],
                    ['data' => 'fakultas', 'label' => 'Fakultas'],
                    ['data' => 'status', 'label' => 'Status', 'type' => 'status_badge', 'className' => 'text-center'],
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
