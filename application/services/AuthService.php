<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Loader $load
 * @property User_model $User_model
 * @property CI_Upload $upload
 */

class AuthService
{
    protected $CI;
    /** @var User_model */
    protected $model;
    protected $AuthValidator;
    protected $db;
    protected $AuthRepository;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('User_model');
        /** @var User_model $this->CI->User_model */
        $this->model = $this->CI->User_model;
        $this->db = $this->CI->db;
        $this->AuthValidator = new AuthValidator();
        $this->AuthRepository = new AuthRepository();
    }

    public function check_session()
    {
        $role = $this->CI->session->userdata('role');
        switch ($role) {
            case 'super_admin':
                redirect(base_url('/super-admin'));
                break;
            case 'admin':
                redirect(base_url('/admin'));
                break;
            case 'lecturer':
            case 'dosen':
                redirect(base_url('/dosen'));
                break;
            case 'student':
            case 'mahasiswa':
                redirect(base_url('/mahasiswa'));
                break;
            case 'teacher':
            case 'guru':
                redirect(base_url('/guru'));
                break;
            case 'principal':
            case 'kepsek':
                redirect(base_url('/kepala-sekolah'));
                break;
            case 'kaprodi':
                redirect(base_url('/kaprodi'));
                break;
            default:
                $this->CI->load->helper('form');
                return $this->CI->load->view('auth/login/index');
        }
    }

    public function set_session($identifier, $user)
    {
        $session_data = [
            'identifier' => $identifier,
            'name' => $user->username,
            'role' => $user->role,
            'logged_in' => TRUE,
            'id_user' => $user->id,
            'nim' => property_exists($user, 'nim') ? $user->nim : null,
            'nip' => property_exists($user, 'nip') ? $user->nip : null,
            'fakultas' => property_exists($user, 'fakultas') ? $user->fakultas : null,
        ];
        $this->CI->session->set_userdata($session_data);
    }

    public function update_password()
    {
        $user_id = $this->CI->session->userdata('user_id'); // ganti jika berbeda

        $current_password = $this->CI->input->post("current_password", true);
        $new_password     = $this->CI->input->post("new_password", true);

        if (!$current_password || !$new_password) {
            throw new Exception("Password lama dan baru wajib diisi.");
        }

        $user = $this->model->get_by_id($user_id);
        if (!$user) {
            throw new Exception("User tidak ditemukan.");
        }

        if (!password_verify($current_password, $user->password)) {
            throw new Exception("Password lama salah.");
        }

        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $updated = $this->model->update($user_id, ['password' => $hashed]);

        if (!$updated) {
            throw new Exception("Gagal menyimpan password baru.");
        }
    }

    public function submit_register()
    {
        try {
            $this->AuthValidator->register_validator();
            $this->validate_file_register();
            $data_array = $this->get_data_register();

            $teacher = $this->db->where('email', $data_array['data']['email'])->get('teachers')->row();
            $check_nik = $this->db->where('nik', $data_array['data']['nik'])->get('teachers')->row();
            if ($teacher) {
                throw new Exception("email sudah dipake");
            }
            if ($check_nik) {
                throw new Exception("NIK sudah dipakai");
            }
            $this->db->trans_begin();
            $teacher_id = $this->AuthRepository->save_teacher($data_array['data']);
            $data_array['temp_data']['teacher_id'] = $teacher_id;
            $this->AuthRepository->save_temp_data($data_array['temp_data']);
            $this->db->trans_commit();
            return true;
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            response_error($th->getMessage(), $th);
        }
    }

    private function get_data_register()
    {
        $nik = $this->CI->input->post('nik', true);
        $teacher_name =  $this->CI->input->post('name', true);
        $uploadData = $this->handle_upload($nik);
        $data = $this->asign_teacher_data($teacher_name, $nik, $uploadData);
        $user_data = $this->asign_user_data();
        $kelompok = [
            'name' => "kelompok $teacher_name"
        ];
        $anggotaIds = $this->CI->input->post('anggotaId');
        $string_anggota_id = implode(',', $anggotaIds);
        $temp_data = [
            'student_ids' => $string_anggota_id,
            'email' => $user_data['email'],
            'password' => $user_data['password'],
            'ketua' => $this->CI->input->post('ketua')
        ];
        return [
            'data' => $data,
            'temp_data' => $temp_data,
        ];
    }

    public function handle_upload($nik)
    {
        $upload_path = "./uploads/teachers/$nik";

        // Buat folder jika belum ada
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true); // recursive mkdir
        }

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048; // 2MB

        $this->CI->load->library('upload', $config);

        if (!$this->CI->upload->do_upload('book')) {
            throw new Exception($this->CI->upload->display_errors(), 1);
        }
        $book_data =  $this->CI->upload->data();
        if (!$this->CI->upload->do_upload('identification_card')) {
            throw new Exception($this->CI->upload->display_errors(), 1);
        }
        $identification_card = $this->CI->upload->data();
        return [
            'book_data' => $book_data,
            'identification_card' => $identification_card,

        ];
    }

    private function asign_teacher_data($teacher_name, $nik, $uploadData)
    {
        return [
            'name'         => $teacher_name,
            'nik'          => $nik,
            'email' => $this->CI->input->post('email', true),
            'phone' => $this->CI->input->post('phone', true),
            'school_id'    => $this->CI->input->post('school_id', true),
            'account_name' => $this->CI->input->post('account_name', true),
            'account_number' => $this->CI->input->post('account_number', true),
            'bank'         => $this->CI->input->post('bank', true),
            'book'    => 'uploads/teachers/' . $nik . '/' . $uploadData['book_data']['file_name'],
            'identification_card' => 'uploads/teachers/' . $nik . '/' . $uploadData['identification_card']['file_name'],
            'status_data' => 'unverified',
            'status' => $this->CI->input->post('status', true),
        ];
    }

    private function validate_file_register()
    {

        // Cek apakah ada file
        if (empty($_FILES['book']['name'])) {
            throw new Exception("file Buku rekening tidak ada");
        }
        if (empty($_FILES['identification_card']['name'])) {
            throw new Exception("file KTP tidak ada");
        }
    }

    private function asign_user_data()
    {
        return [
            'email'        => $this->CI->input->post('email', true),
            'password'     => password_hash($this->CI->input->post('password', true), PASSWORD_BCRYPT),
            'role' => 'teacher',
        ];
    }
}
