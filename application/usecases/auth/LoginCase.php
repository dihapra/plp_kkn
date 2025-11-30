<?php

namespace UseCases\Auth;

use Exception;

defined('BASEPATH') or exit('No direct script access allowed');

class LoginCase
{
    protected $CI;
    protected $UserModel;
    protected $validator;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('User_model');
        $this->UserModel = $this->CI->User_model;
        $this->validator = new \AuthValidator();
        $this->db = $this->CI->db;
    }

    public function execute(): void
    {
        $this->validator->login_validator();

        $identifier = $this->CI->input->post('identifier', true);
        $password   = $this->CI->input->post('password', true);

        $user = $this->UserModel->get_user_by_email_with_password($identifier);
        if (!$user || !password_verify($password, $user->password)) {
            throw new Exception('Email atau password salah. Silakan coba lagi.');
        }

        // $this->ensureTeacherVerified($user);

        $this->setSession($identifier, $user);
        $this->redirectByRole($user->role);
    }

    public function redirectIfAuthenticated(): void
    {
        $role = $this->CI->session->userdata('role');
        if ($role) {
            $this->redirectByRole($role);
            return;
        }

        $this->CI->load->helper('form');
        $this->CI->load->view('auth/login/index');
    }

   

    private function setSession(string $identifier, $user): void
    {
        $session_data = [
            'identifier' => $identifier,
            'name'       => $user->username,
            'role'       => $user->role,
            'logged_in'  => true,
            'id_user'    => $user->id,
            'fakultas'   => property_exists($user, 'fakultas') ? $user->fakultas : null,
        ];
        $this->CI->session->set_userdata($session_data);
    }

    private function redirectByRole(?string $role): void
    {
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
                $this->CI->load->view('auth/login/index');
        }
    }
}
