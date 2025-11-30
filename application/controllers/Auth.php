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
    protected $exclude_methods = [
        'login',
        'register_guru_page',
        'register_guru',
        'register_kepala_sekolah_page',
        'register_kepala_sekolah',
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
        $this->load->view("auth/register/guru");
    }

    // Process teacher registration
    public function register_guru()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $useCase = new RegisterTeacherCase();
                $useCase->execute();
                response_json('Pendaftaran berhasil, silakan tunggu verifikasi dari admin.');
            }
        } catch (Exception $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }

    // Page for principal registration
    public function register_kepala_sekolah_page()
    {
        $this->load->view("auth/register/kepala_sekolah");
    }

    // Process principal registration
    public function register_kepala_sekolah()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $useCase = new RegisterPrincipalCase();
                $useCase->execute();
                response_json('Pendaftaran berhasil, silakan tunggu verifikasi dari admin.');
            }
        } catch (Exception $e) {
            response_error($e->getMessage(), $e, 422);
        }
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
        $this->load->model('Seeder_model');
        $this->Seeder_model->run();
    }
}
