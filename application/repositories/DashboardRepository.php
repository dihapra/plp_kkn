<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DashboardRepository
{
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }
    /**
     * Mendapatkan total mahasiswa
     */
    public function get_total_mahasiswa()
    {
        return $this->db->count_all('student');
    }

    /**
     * Mendapatkan total dosen
     */
    public function get_total_dosen()
    {
        return $this->db->count_all('lecturers');
    }

    /**
     * Mendapatkan total guru
     */
    public function get_total_guru()
    {
        $this->db->select('COUNT(id) as total');
        $this->db->from('teachers');
        $this->db->where('status_data', 'verified');
        $query = $this->db->get();
        return $query->row()->total;
    }

    /**
     * Mendapatkan total sekolah
     */
    public function get_total_sekolah()
    {
        return $this->db->count_all('school');
    }
    /**
     * Mendapatkan total kepsek
     */
    public function get_total_kepsek()
    {
        $this->db->select('COUNT(id) as total');
        $this->db->from('principal');
        $this->db->where('status_data', 'verified');
        $query = $this->db->get();
        return $query->row()->total;
    }
    /**
     * Mendapatkan total sekolah
     */
    public function get_total_sekolah_empty()
    {
        $this->db->select('COUNT(school.id) as total');
        $this->db->from('school');
        $this->db->join('principal', 'principal.school_id = school.id', 'left');
        $this->db->where('principal.school_id IS NULL'); // hanya ambil yg belum ada principal
        $query = $this->db->get();
        return $query->row()->total;
    }

    /**
     * Mendapatkan jumlah mahasiswa berdasarkan fakultas
     */
    public function get_mahasiswa_per_fakultas()
    {
        $this->db->select('fakultas, COUNT(id) as total');
        $this->db->group_by('fakultas');
        $query = $this->db->get('student');
        return $query->result_array();
    }

    /**
     * Mendapatkan jumlah dosen berdasarkan fakultas
     */
    public function get_dosen_per_fakultas()
    {
        $this->db->select('fakultas, COUNT(id) as total');
        $this->db->group_by('fakultas');
        $query = $this->db->get('lecturers');
        return $query->result_array();
    }

    /**
     * Mendapatkan jumlah dosen yang belum mengerjakan aktivitasnya
     */
    public function get_dosen_belum_aktivitas()
    {
        $this->db->where('status_aktivitas', 'belum');
        return $this->db->count_all_results('lecturers');
    }

    /**
     * Mendapatkan jumlah mahasiswa yang tidak mengabsensi hari ini
     */
    public function get_mahasiswa_tidak_absen_hari_ini()
    {
        $this->db->where('tanggal', date('Y-m-d'));
        $this->db->where('status_absen', 'tidak');
        return $this->db->count_all_results('absensi_mahasiswa');
    }
}
