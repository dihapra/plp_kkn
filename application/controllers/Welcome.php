<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$this->check_session();
		$this->load->view('welcome_message');
	}
	public function check_session()
	{
		$role = $this->session->userdata('role');
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
			default:
		}
	}
	public function test()
	{
		$data = getenv("BASE_URL");
		echo $data;
	}

	public function seeder()
	{
		$this->load->model("Seeder_model");
		$this->Seeder_model->run();
	}
}
