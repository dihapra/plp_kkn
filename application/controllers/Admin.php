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
        return;
    }

    public function index()
    {
         redirect('admin/program');
    }

    public function program()
    {
        $programs = $this->getAccessiblePrograms();

        $data = [
            'programs' => $programs,
            'selectedProgramId' => 0,
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
            return;
        }

        $programs = $this->getAccessiblePrograms();
        $programIds = array_map(static function ($program) {
            return (int) $program['id'];
        }, $programs);

        if (!in_array($programId, $programIds, true)) {
            $this->session->set_flashdata('error', 'Program tidak tersedia untuk akun ini.');
            redirect('admin/program');
            return;
        }

        $this->session->set_flashdata('success', 'Program berhasil dipilih.');

        $selectedProgram = null;
        foreach ($programs as $program) {
            // dd($program, $programId);
            if ((int) $program['id'] == $programId) {
                $selectedProgram = $program;
                break;
            }
        }
        // dd($selectedProgram);
        if (!$selectedProgram) {
            $this->session->set_flashdata('error', 'Program tidak ditemukan.');
            redirect('admin/program');
            return;
        }

        $redirectRoute = 'admin/program';
        switch ($selectedProgram['kode']) {
            case 'plp1':
                $redirectRoute = 'admin/plp1';
                break;
            case 'plp2':
                $redirectRoute = 'admin/plp2';
                break;
            case 'kkn':
                $redirectRoute = 'admin/kkn';
                break;
            default:
                redirect('admin/program');
                return;
        }
                // dd($redirectRoute);
        redirect($redirectRoute);
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
