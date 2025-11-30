<?php

use UseCases\Superadmin\ProgramCase;
use UseCases\Superadmin\SekolahCase;
use UseCases\Superadmin\ProdiCase;
use UseCases\Superadmin\KepsekCase;
use UseCases\Superadmin\GuruCase;
use UseCases\Superadmin\DosenCase;
use UseCases\Superadmin\KaprodiCase;
use UseCases\Superadmin\MahasiswaCase;
use UseCases\Superadmin\UtilCase;

defined('BASEPATH') or exit('No direct script access allowed');


class SuperAdmin extends MY_Controller
{
    protected $AuthService;
    protected $SuperAdminRepo;
    protected $exclude_methods = [
        'login',
        'authenticate',
        'logout',
        'filter_sekolah',
        'filter_prodi',
        'filter_fakultas',
        'filter_program',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper('form');
        $this->check_role(['super_admin']);
        $this->AuthService = new AuthService();
    }

    public function login()
    {
        $data['error'] = $this->session->flashdata('error');
        $data['success'] = $this->session->flashdata('success');
        $this->load->view('super_admin/login', $data);
    }

    public function authenticate()
    {
        // dd("here");
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_error('Method Not Allowed', 405);
            return;
        }

        $identifier = $this->input->post('identifier', true);
        $password = $this->input->post('password', true);

        if (empty($identifier) || empty($password)) {
            $this->session->set_flashdata('error', 'Email dan password wajib diisi.');
            redirect('super-admin/login');
            return;
        }

        $user = $this->User_model->get_user_by_email_with_password($identifier);
        // dd($user,password_verify($password, $user->password));
        if (!$user || $user->role !== 'super_admin' || !password_verify($password, $user->password)) {
            $this->session->set_flashdata('error', 'Email atau password salah.');
            redirect('super-admin/login');
            return;
        }

        $this->AuthService->set_session($identifier, $user);
        redirect('super-admin/dashboard');
    }

    public function dashboard()
    {
        $uc = new UtilCase();
        $viewData = $uc->dashboard_data();
        $content = $this->load->view('super_admin/dashboard/index', $viewData, true);
        $this->load->view('super_admin/layout/app', [
            'title' => 'Super Admin Dashboard',
            'content' => $content,
        ]);
    }

    public function program()
    {
        view_with_layout("super_admin/program/index", "Super Admin - Program","super_admin",[],null,"script/datatable");
    }

    public function program_datatable(){
          $req = get_param_datatable();
          $uc = new ProgramCase();
          $data = $uc->datatable($req);
          datatable_response_array($req['draw'], $data['count_total'], $data['count_filtered'], $data['formatted']);
    }

    public function sekolah()
    {
        view_with_layout("super_admin/sekolah/index", "Super Admin - Sekolah","super_admin",[],null,"script/datatable");
    }

    public function sekolah_datatable()
    {
        $req = get_param_datatable();
        $uc  = new SekolahCase();
        $data = $uc->datatable($req);
        datatable_response_array($req['draw'], $data['count_total'], $data['count_filtered'], $data['formatted']);
    }

    public function sekolah_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new SekolahCase();
            $payload = [
                'nama'   => $this->input->post('nama', true),
                'alamat'=> $this->input->post('alamat', true),
            ];
            $uc->create($payload);
            response_json('Sekolah berhasil dibuat');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function prodi()
    {
        view_with_layout("super_admin/prodi/index", "Super Admin - Prodi","super_admin",[],null,"script/datatable");
    }

    public function prodi_datatable()
    {
        $req = get_param_datatable();
        $uc  = new ProdiCase();
        $data = $uc->datatable($req);
        datatable_response_array($req['draw'], $data['count_total'], $data['count_filtered'], $data['formatted']);
    }

    public function prodi_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new ProdiCase();
            $payload = [
                'nama'     => $this->input->post('nama', true),
                'fakultas' => $this->input->post('fakultas', true),
            ];
            $uc->create($payload);
            response_json('Prodi berhasil dibuat');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function prodi_update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new ProdiCase();
            $payload = [
                'nama'     => $this->input->post('nama', true),
                'fakultas' => $this->input->post('fakultas', true),
            ];
            $uc->update((int) $id, $payload);
            response_json('Prodi berhasil diperbarui');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function dosen()
    {
        view_with_layout("super_admin/dosen/index", "Super Admin - Dosen","super_admin",[],null,"script/datatable");
    }

    public function dosen_datatable()
    {
        $req = get_param_datatable();
        $uc  = new DosenCase();
        $data = $uc->datatable($req);
        datatable_response_array($req['draw'], $data['count_total'], $data['count_filtered'], $data['formatted']);
    }

    public function dosen_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new DosenCase();
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
            $uc = new DosenCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->update((int) $id, $payload);
            response_json('Dosen berhasil diperbarui');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function kaprodi()
    {
        view_with_layout("super_admin/kaprodi/index", "Super Admin - Kaprodi","super_admin",[],null,"script/datatable");
    }

    public function kaprodi_datatable()
    {
        $req = get_param_datatable();
        $uc  = new KaprodiCase();
        $data = $uc->datatable($req);
        datatable_response_array($req['draw'], $data['count_total'], $data['count_filtered'], $data['formatted']);
    }

    public function kaprodi_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new KaprodiCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->create($payload);
            response_json('Kaprodi berhasil dibuat');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function kaprodi_update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new KaprodiCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->update((int) $id, $payload);
            response_json('Kaprodi berhasil diperbarui');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function mahasiswa()
    {
        view_with_layout("super_admin/mahasiswa/index", "Super Admin - Mahasiswa","super_admin",[],null,"script/datatable");
    }

    public function mahasiswa_datatable()
    {
        $req = get_param_datatable();
        $uc  = new MahasiswaCase();
        $data = $uc->datatable($req);
        datatable_response_array($req['draw'], $data['count_total'], $data['count_filtered'], $data['formatted']);
    }

    public function mahasiswa_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new MahasiswaCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->create($payload);
            response_json('Mahasiswa berhasil dibuat');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function mahasiswa_update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new MahasiswaCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->update((int) $id, $payload);
            response_json('Mahasiswa berhasil diperbarui');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function kepsek()
    {
        view_with_layout("super_admin/kepsek/index", "Super Admin - Kepala Sekolah","super_admin",[],null,"script/datatable");
    }

    public function kepsek_datatable()
    {
        $req = get_param_datatable();
        $req['filter_status'] = $this->input->post('filter_status', true);
        $req['filter_school'] = $this->input->post('filter_school', true);
        $req['filter_program'] = $this->input->post('filter_program', true);
        $uc  = new KepsekCase();
        $data = $uc->datatable($req);
        datatable_response_array($req['draw'], $data['count_total'], $data['count_filtered'], $data['formatted']);
    }

    public function kepsek_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new KepsekCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->create($payload, $_FILES);
            response_json('Kepala sekolah berhasil dibuat');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function kepsek_update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new KepsekCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->update((int) $id, $payload, $_FILES);
            response_json('Kepala sekolah berhasil diperbarui');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function guru()
    {
        view_with_layout("super_admin/guru/index", "Super Admin - Guru","super_admin",[],null,"script/datatable");
    }

    public function guru_datatable()
    {
        $req = get_param_datatable();
        $req['filter_status'] = $this->input->post('filter_status', true);
        $req['filter_school'] = $this->input->post('filter_school', true);
        $uc  = new GuruCase();
        $data = $uc->datatable($req);
        datatable_response_array($req['draw'], $data['count_total'], $data['count_filtered'], $data['formatted']);
    }

    public function guru_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new GuruCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->create($payload, $_FILES);
            response_json('Guru berhasil dibuat');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function guru_update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new GuruCase();
            $payload = $this->input->post(null, true) ?? [];
            $uc->update((int) $id, $payload, $_FILES);
            response_json('Guru berhasil diperbarui');
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

    public function sekolah_update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new SekolahCase();
            $payload = [
                'nama'   => $this->input->post('nama', true),
                'alamat'=> $this->input->post('alamat', true),
            ];
            $uc->update((int) $id, $payload);
            response_json('Sekolah berhasil diperbarui');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function program_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new ProgramCase();
            $payload = [
                'nama'         => $this->input->post('nama', true),
                'tahun_ajaran' => $this->input->post('tahun_ajaran', true),
                'status'       => $this->input->post('status', true),
            ];
            $uc->create($payload);
            response_json('Program berhasil dibuat');
        } catch (Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function program_update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new ProgramCase();
            $payload = [
                'nama'         => $this->input->post('nama', true),
                'tahun_ajaran' => $this->input->post('tahun_ajaran', true),
                'status'       => $this->input->post('status', true),
            ];
            $uc->update((int) $id, $payload);
            response_json('Program berhasil diperbarui');
        } catch (Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function program_toggle($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $uc = new ProgramCase();
            $newStatus = $uc->toggleStatus((int) $id);
            $label = $newStatus ? 'Aktif' : 'Tidak Aktif';
            response_json('Status program berhasil diubah', ['status' => $label]);
        } catch (Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('super-admin/login');
    }


}
