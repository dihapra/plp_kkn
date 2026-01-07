<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property User_model $User_model
 */

class Admin extends MY_Controller
{
    protected $exclude_methods = [];
    protected $DashboardService;
    protected $programRedirectRoute = 'admin/program';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->check_role(['admin', 'super_admin']);
    }

    protected function ensureProgramSelection($programId = null)
    {
        $currentMethod = $this->router->method;
        if (in_array($currentMethod, ['program', 'set_program'], true)) {
            return;
        }

        parent::ensureProgramSelection($programId);
    }

    public function index()
    {
        $service = new DashboardService();
        $data = $service->get_admin_dashboard();

        view_with_layout('admin/index', 'Admin Dashboard', null, $data);
    }

    public function program()
    {
        $programs = $this->getAccessiblePrograms();
        $selectedProgramId = (int) $this->session->userdata('program_id');

        $data = [
            'programs' => $programs,
            'selectedProgramId' => $selectedProgramId,
            'userName' => (string) $this->session->userdata('name'),
        ];

        $this->load->view('admin/program_select', $data);
    }

    public function set_program()
    {
        $programId = (int) $this->input->post('program_id', true);
        if ($programId <= 0) {
            $this->session->set_flashdata('error', 'Pilih program terlebih dahulu.');
            redirect('admin/program');
        }

        $programs = $this->getAccessiblePrograms();
        $programIds = array_map(static function ($program) {
            return (int) $program['id'];
        }, $programs);

        if (!in_array($programId, $programIds, true)) {
            $this->session->set_flashdata('error', 'Program tidak tersedia untuk akun ini.');
            redirect('admin/program');
        }

        $this->session->set_userdata([
            'program_id' => $programId,
            'id_program' => $programId,
        ]);

        $this->session->set_flashdata('success', 'Program berhasil dipilih.');
        redirect('admin');
    }

    private function getAccessiblePrograms(): array
    {
        $userId = (int) $this->session->userdata('id_user');
        if ($userId <= 0) {
            return [];
        }

        return $this->db
            ->select('p.id, p.kode, p.nama, p.tahun_ajaran, p.semester, p.active')
            ->from('akses_modul_user amu')
            ->join('program p', 'p.id = amu.id_program', 'inner')
            ->where('amu.id_user', $userId)
            ->where('amu.aktif', 1)
            ->order_by('p.active', 'DESC')
            ->order_by('p.tahun_ajaran', 'DESC')
            ->order_by('p.nama', 'ASC')
            ->get()
            ->result_array();
    }
}
