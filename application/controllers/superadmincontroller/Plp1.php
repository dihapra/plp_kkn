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
