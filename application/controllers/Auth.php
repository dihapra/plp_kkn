<?php

use UseCases\Admin\VerifyTeacherCase;
use UseCases\Auth\LoginCase;
use UseCases\Auth\RegisterPrincipalCase;
use UseCases\Auth\RegisterTeacherCase;

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property User_model $User_model
 */

class Auth extends MY_Controller
{
    protected $AuthService;
    protected $mkdkAllowedStatuses = ['Lulus', 'Proses', 'Belum Lulus'];
    protected $exclude_methods = [
        'login',
        'logout',
        'register_guru_page',
        'register_guru',
        'register_kepala_sekolah_page',
        'register_kepala_sekolah',
        'register_mahasiswa_page',
        'register_mahasiswa',
        'get_faculties_for_registration',
        'get_study_programs_for_registration',
        // 'seed'
    ];
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->AuthService = new AuthService();
    }
    // public function test(){
    //     $data 
    // }
    public function validate_teacher($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        try {
            $useCase = new VerifyTeacherCase();
            $payload = [
                'nik'             => $this->input->post('nik', true),
                'account_number'  => $this->input->post('account_number', true),
                'bank'            => $this->input->post('bank', true),
                'account_name'    => $this->input->post('account_name', true),
            ];

            $useCase->execute($id, $payload);
            response_json("berhasil verif guru");
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th);
        }
    }
    public function reject_teacher($id)
    {
        try {
            $useCase = new VerifyTeacherCase();
            $useCase->reject($id);
            response_json("berhasil tolak guru");
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th);
        }
    }
    public function profile()
    {
        view_with_layout('profile/index', 'profil');
    }

    public function login()
    {
        $loginCase = new LoginCase();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $loginCase->execute();
            } catch (\Throwable $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                redirect(base_url('/login'));
            }
        } else {
            $loginCase->redirectIfAuthenticated();
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('/');
    }

    // Page for teacher registration
    public function register_guru_page()
    {
        // $this->load->view("auth/register/guru");
        show_404();
    }

    // Process teacher registration
    public function register_guru()
    {
        // try {
        //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //         $useCase = new RegisterTeacherCase();
        //         $useCase->execute();
        //         response_json('Pendaftaran berhasil, silakan tunggu verifikasi dari admin.');
        //     }
        // } catch (Exception $e) {
        //     response_error($e->getMessage(), $e, 422);
        // }
        $this->denyRegistrationFeature('guru pamong');
    }

    // Page for principal registration
    public function register_kepala_sekolah_page()
    {
        // $this->load->view("auth/register/kepala_sekolah");
        show_404();
    }

    // Process principal registration
    public function register_kepala_sekolah()
    {
        // try {
        //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //         $useCase = new RegisterPrincipalCase();
        //         $useCase->execute();
        //         response_json('Pendaftaran berhasil, silakan tunggu verifikasi dari admin.');
        //     }
        // } catch (Exception $e) {
        //     response_error($e->getMessage(), $e, 422);
        // }
        $this->denyRegistrationFeature('kepala sekolah');
    }

    // Page for student registration
    public function register_mahasiswa_page()
    {
        $data = [
            'activeProgram' => $this->findActiveProgramRow(),
        ];
        $this->load->view("auth/register/mahasiswa", $data);
    }

    // Process student registration
    public function register_mahasiswa()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response_error('Method Not Allowed', null, 405);
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules([
            ['field' => 'name', 'label' => 'Nama Mahasiswa', 'rules' => 'required|trim'],
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email|trim'],
            ['field' => 'nim', 'label' => 'NIM', 'rules' => 'required|exact_length[10]|numeric'],
            ['field' => 'phone', 'label' => 'Nomor WhatsApp', 'rules' => 'required|trim'],
            ['field' => 'religion', 'label' => 'Agama', 'rules' => 'required|in_list[Islam,Kristen Protestan,Katolik,Hindu,Buddha,Konghucu]'],
            ['field' => 'faculty', 'label' => 'Fakultas', 'rules' => 'required|trim'],
            ['field' => 'program_studi', 'label' => 'Program Studi', 'rules' => 'required|trim'],
            ['field' => 'total_sks', 'label' => 'Total SKS', 'rules' => 'required|integer|greater_than[0]'],
            ['field' => 'mkdk[filsafat_pendidikan]', 'label' => 'Filsafat Pendidikan', 'rules' => 'required|in_list[Lulus,Proses,Belum Lulus]'],
            ['field' => 'mkdk[profesi_kependidikan]', 'label' => 'Profesi Kependidikan', 'rules' => 'required|in_list[Lulus,Proses,Belum Lulus]'],
            ['field' => 'mkdk[perkembangan_peserta_didik]', 'label' => 'Perkembangan Peserta Didik', 'rules' => 'required|in_list[Lulus,Proses,Belum Lulus]'],
            ['field' => 'mkdk[psikologi_pendidikan]', 'label' => 'Psikologi Pendidikan', 'rules' => 'required|in_list[Lulus,Proses,Belum Lulus]'],
            ['field' => 'agreement_plp', 'label' => 'Pernyataan PLP', 'rules' => 'required'],
            ['field' => 'agreement_tugas', 'label' => 'Pernyataan Tugas', 'rules' => 'required'],
            ['field' => 'agreement_profesional', 'label' => 'Pernyataan Profesional', 'rules' => 'required'],
            ['field' => 'agreement_etika', 'label' => 'Pernyataan Etika', 'rules' => 'required'],
            ['field' => 'agreement_lapor', 'label' => 'Pernyataan Lapor', 'rules' => 'required'],
            ['field' => 'statement_rewrite', 'label' => 'Penegasan Pernyataan', 'rules' => 'required|trim'],
        ]);

        if ($this->form_validation->run() === FALSE) {
            response_error(strip_tags(validation_errors()), null, 422);
            return;
        }

        try {
            $this->db->trans_begin();

            $name = trim($this->input->post('name', true));
            $email = trim($this->input->post('email', true));
            $nim = trim($this->input->post('nim', true));
            $phone = trim($this->input->post('phone', true));
            $religion = trim($this->input->post('religion', true));
            $faculty = trim($this->input->post('faculty', true));
            $programStudi = trim($this->input->post('program_studi', true));
            $totalSks = (int) $this->input->post('total_sks', true);
            $mkdkInput = (array) $this->input->post('mkdk');
            $mkdk = $this->normalizeMkdkStatuses($mkdkInput);

            $this->ensureUniqueMahasiswa($email, $nim);

            $activeProgramId = $this->getActiveProgramId();
            $prodiId = $this->resolveProdiId($programStudi, $faculty);

            $userData = [
                'email'      => $email,
                'username'   => $name,
                'password'   => password_hash($nim, PASSWORD_BCRYPT),
                'role'       => 'mahasiswa',
                'fakultas'   => $faculty,
                'has_change' => 0,
                'id_program' => $activeProgramId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->insert('users', $userData);
            $userId = (int) $this->db->insert_id();

            if ($userId <= 0) {
                throw new Exception('Gagal membuat akun mahasiswa.');
            }

            $mahasiswaData = [
                'id_user'    => $userId,
                'nama'       => $name,
                'nim'        => $nim,
                'email'      => $email,
                'no_hp'      => $phone,
                'agama'      => $religion,
                'id_prodi'   => $prodiId,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->insert('mahasiswa', $mahasiswaData);
            $mahasiswaId = (int) $this->db->insert_id();

            if ($mahasiswaId <= 0) {
                throw new Exception('Gagal menyimpan data mahasiswa.');
            }

            $programMahasiswaData = [
                'id_program' => $activeProgramId,
                'id_mahasiswa' => $mahasiswaId,
                'status' => 'unverified',
                'valid_from' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->insert('program_mahasiswa', $programMahasiswaData);
            $programMahasiswaId = (int) $this->db->insert_id();

            if ($programMahasiswaId <= 0) {
                throw new Exception('Gagal menyimpan data program mahasiswa.');
            }

            $syaratData = [
                'id_program_mahasiswa'       => $programMahasiswaId,
                'filsafat_pendidikan'        => $mkdk['filsafat_pendidikan'],
                'profesi_kependidikan'       => $mkdk['profesi_kependidikan'],
                'perkembangan_peserta_didik' => $mkdk['perkembangan_peserta_didik'],
                'psikologi_pendidikan'       => $mkdk['psikologi_pendidikan'],
                'total_sks'                  => $totalSks,
            ];
            $this->db->insert('syarat_mapel', $syaratData);

            $this->db->trans_commit();

            response_json(
                 'Pendaftaran mahasiswa berhasil dikirim, data anda akan diverifikasi. Silakan cek email secara berkala untuk informasi selanjutnya.'
            );
        } catch (Throwable $e) {
            $this->db->trans_rollback();
            response_error($e->getMessage(), $e, 422);
        }
    }

    public function get_faculties_for_registration()
    {
        $faculties = $this->db->distinct()
            ->select('fakultas')
            ->from('prodi')
            ->order_by('fakultas', 'ASC')
            ->get()
            ->result_array();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($faculties));
    }

    public function get_study_programs_for_registration()
    {
        $faculty = $this->input->get('faculty', true);
        if (empty($faculty)) {
            response_error('Fakultas tidak valid', null, 422);
            return;
        }

        $programs = $this->db->select('id, nama, fakultas')
            ->from('prodi')
            ->where('fakultas', $faculty)
            ->order_by('nama', 'ASC')
            ->get()
            ->result_array();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($programs));
    }

    private function ensureUniqueMahasiswa(string $email, string $nim): void
    {
        $emailExists = $this->db->where('email', $email)->count_all_results('users') > 0;
        if ($emailExists) {
            throw new Exception('Email sudah terdaftar pada sistem.');
        }

        $nimExists = $this->db->where('nim', $nim)->count_all_results('mahasiswa') > 0;
        if ($nimExists) {
            throw new Exception('NIM sudah terdaftar pada sistem.');
        }
    }

    private function getActiveProgramId(): int
    {
        $program = $this->findActiveProgramRow();
        if (!$program) {
            throw new Exception('Belum ada program aktif yang tersedia.');
        }

        return (int) $program['id'];
    }

    private function findActiveProgramRow(): ?array
    {
        $program = $this->db->select('id, nama, tahun_ajaran')
            ->from('program')
            ->where('active', 1)
            ->order_by('updated_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        return $program ?: null;
    }

    private function resolveProdiId(string $prodiName, string $fakultas): int
    {
        $builder = $this->db->select('id')
            ->from('prodi')
            ->where('nama', $prodiName)
            ->limit(1);

        if ($fakultas !== '') {
            $builder->where('fakultas', $fakultas);
        }

        $row = $builder->get()->row();
        if (!$row) {
            throw new Exception('Program studi tidak ditemukan pada fakultas tersebut.');
        }

        return (int) $row->id;
    }

    private function normalizeMkdkStatuses(array $input): array
    {
        $result = [];
        $keys = ['filsafat_pendidikan', 'profesi_kependidikan', 'perkembangan_peserta_didik', 'psikologi_pendidikan'];

        foreach ($keys as $key) {
            $value = isset($input[$key]) ? $input[$key] : null;
            $result[$key] = in_array($value, $this->mkdkAllowedStatuses, true) ? $value : 'Belum Lulus';
        }

        return $result;
    }

    private function denyRegistrationFeature(string $label): void
    {
        response_error(sprintf('Fitur pendaftaran %s belum tersedia.', $label), null, 403);
    }

    public function update_password()
    {
        try {
            $this->AuthService->update_password();
            response_json([
                'status' => 'success',
                'message' => 'Password berhasil diperbarui.'
            ], 200);
        } catch (Throwable $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }

    public function validate_recaptcha($response)
    {
        $secret = getenv('CAPTCHA');
        if (empty($secret)) {
            $this->form_validation->set_message('validate_recaptcha', 'Secret key CAPTCHA tidak tersedia.');
            return false;
        }

        if (empty($response)) {
            $this->form_validation->set_message('validate_recaptcha', 'Response CAPTCHA tidak diterima.');
            return false;
        }

        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $data = array(
            'secret' => $secret,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );

        if (function_exists('curl_version')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded'
            ));
            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                curl_close($ch);
                $this->form_validation->set_message('validate_recaptcha', 'Error cURL: ' . $error_msg);
                return false;
            }
            curl_close($ch);
        } else {
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data),
                ),
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if ($result === false) {
                $this->form_validation->set_message('validate_recaptcha', 'Tidak dapat menghubungi server validasi.');
                return false;
            }
        }

        $resultJson = json_decode($result);

        if (!isset($resultJson->success) || $resultJson->success !== true) {
            $this->form_validation->set_message('validate_recaptcha', 'Verifikasi CAPTCHA gagal. Silakan coba lagi.');
            return false;
        }

        return true;
    }

    public function seed()
    {
        $useCase = new \UseCases\Admin\SeederCase();
        $useCase->run();
    }

    public function seed_admin()
    {
        $useCase = new \UseCases\Admin\SeederCase();
        $useCase->initialize_data();
        $useCase->user_seeder();
    }
}
