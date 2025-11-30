<?php

use UseCases\AspekPenilaian;
use UseCases\Lecturer\LaporanCase;
use UseCases\Lecturer\LearningCase;
use UseCases\Lecturer\TugasCase;

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_URI $uri
 * @property CI_Upload $upload
 * @property CI_Pagination $pagination
 * @property CI_Output $output
 *
 */
require_once(APPPATH . 'controllers/Dosen.php');
class Tugas extends Dosen
{
    protected $exclude_methods = [];
    public function __construct()
    {
        parent::__construct();
    }
    public function report_revision($submission_id)
    {
        try {
            // Ambil data JSON dari body request
            $request_data = json_decode(trim(file_get_contents('php://input')), true);

            if (!$request_data) {
                throw new Exception("Data request tidak valid.");
            }

            // Paksa data JSON masuk ke $_POST agar bisa divalidasi
            $_POST = $request_data;

            // Validasi
            $rules = [
                ['field' => 'content_revision', 'label' => 'Feedback Revisi', 'rules' => 'required'],
            ];
            foreach ($rules as $rule) {
                $this->form_validation->set_rules($rule['field'], $rule['label'], $rule['rules']);
            }

            if ($this->form_validation->run() === FALSE) {
                throw new Exception(validation_errors());
            }

            $data = [
                'submission_id' => $submission_id,
                'deskripsi'     => $_POST['content_revision'],
                'created_at'    => date('Y-m-d H:i:s'),
                'created_by'    => $this->session->userdata('user_id')
            ];

            $this->db->trans_start(); // Mulai transaksi

            // 1. Insert ke tabel submission_revisions
            $this->db->insert('submission_revisions', $data);

            // 2. Update status di tabel submission
            $this->db->where('id', $submission_id);
            $this->db->update('submission', [
                'status' => 'revisi'
            ]);

            $this->db->trans_complete(); // Selesaikan transaksi

            // 3. Cek apakah transaksi berhasil
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception("Gagal menyimpan revisi.");
            }

            return response_json('Berhasil menambahkan revisi');
        } catch (Exception $e) {
            response_error($e->getMessage(), $e);
        }
    }

    public function laporan_kemajuan($submisi_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_laporan =  $aspek_case->laporan_aspek;
        $aspek_presentasi =  $aspek_case->presentasi_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_group_report_submission_by_id(1, $submisi_id);
        view_with_layout('dosen/tugas/laporan-kemajuan/penilaian', 'Laporan Kemajuan', [
            'aspek_laporan' => $aspek_laporan,
            'aspek_presentasi' => $aspek_presentasi,
            'detail_laporan' => $detail_laporan
        ]);
    }
    public function laporan_kemajuan_edit($submisi_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_laporan =  $aspek_case->laporan_aspek;
        $aspek_presentasi =  $aspek_case->presentasi_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_group_report_submission_by_id(1, $submisi_id);
        $laporan_case = new LaporanCase();
        $scores = $laporan_case->get_penilaian_by_submisi_id($submisi_id);
        view_with_layout('dosen/tugas/laporan-kemajuan/edit', 'Laporan Kemajuan Edit', [
            'aspek_laporan' => $aspek_laporan,
            'aspek_presentasi' => $aspek_presentasi,
            'detail_laporan' => $detail_laporan,
            'score_laporan' => $scores['nilai_laporan'],
            'score_presentasi' => $scores['nilai_presentasi'],
        ]);
    }
    public function laporan_kemajuan_view($submisi_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_laporan =  $aspek_case->laporan_aspek;
        $aspek_presentasi =  $aspek_case->presentasi_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_group_report_submission_by_id(1, $submisi_id);
        $laporan_case = new LaporanCase();
        $scores = $laporan_case->get_penilaian_by_submisi_id($submisi_id);
        view_with_layout('dosen/tugas/laporan-kemajuan/view', 'Laporan Kemajuan', [
            'aspek_laporan' => $aspek_laporan,
            'aspek_presentasi' => $aspek_presentasi,
            'detail_laporan' => $detail_laporan,
            'score_laporan' => $scores['nilai_laporan'],
            'score_presentasi' => $scores['nilai_presentasi'],
        ]);
    }
    public function laporan_akhir($submisi_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_laporan =  $aspek_case->laporan_aspek;
        $aspek_presentasi =  $aspek_case->presentasi_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_group_report_submission_by_id(2, $submisi_id);
        view_with_layout('dosen/tugas/laporan-akhir/penilaian', 'Laporan akhir', [
            'aspek_laporan' => $aspek_laporan,
            'aspek_presentasi' => $aspek_presentasi,
            'detail_laporan' => $detail_laporan
        ]);
    }
    public function laporan_akhir_edit($submisi_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_laporan =  $aspek_case->laporan_aspek;
        $aspek_presentasi =  $aspek_case->presentasi_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_group_report_submission_by_id(2, $submisi_id);
        $laporan_case = new LaporanCase();
        $scores = $laporan_case->get_penilaian_by_submisi_id($submisi_id);
        view_with_layout('dosen/tugas/laporan-akhir/edit', 'Laporan akhir Edit', [
            'aspek_laporan' => $aspek_laporan,
            'aspek_presentasi' => $aspek_presentasi,
            'detail_laporan' => $detail_laporan,
            'score_laporan' => $scores['nilai_laporan'],
            'score_presentasi' => $scores['nilai_presentasi'],
        ]);
    }
    public function laporan_akhir_view($submisi_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_laporan =  $aspek_case->laporan_aspek;
        $aspek_presentasi =  $aspek_case->presentasi_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_group_report_submission_by_id(2, $submisi_id);
        $laporan_case = new LaporanCase();
        $scores = $laporan_case->get_penilaian_by_submisi_id($submisi_id);
        view_with_layout('dosen/tugas/laporan-akhir/view', 'Laporan akhir', [
            'aspek_laporan' => $aspek_laporan,
            'aspek_presentasi' => $aspek_presentasi,
            'detail_laporan' => $detail_laporan,
            'score_laporan' => $scores['nilai_laporan'],
            'score_presentasi' => $scores['nilai_presentasi'],
        ]);
    }
    public function modul_ajar($submission_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_modul = $aspek_case->modul_ajar_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_individual_report_submission_by_id(3, $submission_id);
        view_with_layout('dosen/tugas/modul-ajar/penilaian', 'Modul Ajar', [
            'aspek_modul' => $aspek_modul,
            'detail_laporan' => $detail_laporan
        ]);
    }
    public function modul_ajar_edit($submission_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_modul = $aspek_case->modul_ajar_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_individual_report_submission_by_id(3, $submission_id);
        $uc = new LearningCase();
        $scores = $uc->get_penilaian_by_submisi_id($submission_id);
        view_with_layout('dosen/tugas/modul-ajar/edit', 'Modul Ajar', [
            'aspek_modul' => $aspek_modul,
            'detail_laporan' => $detail_laporan,
            'scores' => $scores
        ]);
    }
    public function modul_ajar_view($submission_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_modul = $aspek_case->modul_ajar_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_individual_report_submission_by_id(3, $submission_id);
        $uc = new LearningCase();
        $scores = $uc->get_penilaian_by_submisi_id($submission_id);
        view_with_layout('dosen/tugas/modul-ajar/view', 'Modul Ajar', [
            'aspek_modul' => $aspek_modul,
            'detail_laporan' => $detail_laporan,
            'scores' => $scores
        ]);
    }
    public function bahan_ajar($submission_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_modul = $aspek_case->bahan_ajar_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_individual_report_submission_by_id(4, $submission_id);
        view_with_layout('dosen/tugas/bahan-ajar/penilaian', 'bahan Ajar', [
            'aspek_modul' => $aspek_modul,
            'detail_laporan' => $detail_laporan
        ]);
    }
    public function bahan_ajar_edit($submission_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_modul = $aspek_case->bahan_ajar_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_individual_report_submission_by_id(4, $submission_id);
        $uc = new LearningCase();
        $scores = $uc->get_penilaian_by_submisi_id($submission_id);
        view_with_layout('dosen/tugas/bahan-ajar/edit', 'bahan Ajar', [
            'aspek_modul' => $aspek_modul,
            'detail_laporan' => $detail_laporan,
            'scores' => $scores
        ]);
    }
    public function bahan_ajar_view($submission_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_modul = $aspek_case->bahan_ajar_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_individual_report_submission_by_id(4, $submission_id);
        $uc = new LearningCase();
        $scores = $uc->get_penilaian_by_submisi_id($submission_id);
        view_with_layout('dosen/tugas/bahan-ajar/view', 'Modul Projek', [
            'aspek_modul' => $aspek_modul,
            'detail_laporan' => $detail_laporan,
            'scores' => $scores
        ]);
    }
    public function modul_projek($submission_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_modul = $aspek_case->modul_projek_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_individual_report_submission_by_id(5, $submission_id);
        view_with_layout('dosen/tugas/modul-projek/penilaian', 'Modul Projek', [
            'aspek_modul' => $aspek_modul,
            'detail_laporan' => $detail_laporan
        ]);
    }
    public function modul_projek_edit($submission_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_modul = $aspek_case->modul_projek_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_individual_report_submission_by_id(5, $submission_id);
        $uc = new LearningCase();
        $scores = $uc->get_penilaian_by_submisi_id($submission_id);
        view_with_layout('dosen/tugas/modul-projek/edit', 'Modul Projek', [
            'aspek_modul' => $aspek_modul,
            'detail_laporan' => $detail_laporan,
            'scores' => $scores
        ]);
    }
    public function modul_projek_view($submission_id)
    {
        $aspek_case = new AspekPenilaian;
        $aspek_modul = $aspek_case->modul_projek_aspek;
        $tugas_case = new TugasCase();
        $detail_laporan = $tugas_case->get_individual_report_submission_by_id(5, $submission_id);
        $uc = new LearningCase();
        $scores = $uc->get_penilaian_by_submisi_id($submission_id);
        view_with_layout('dosen/tugas/modul-projek/view', 'Modul Projek', [
            'aspek_modul' => $aspek_modul,
            'detail_laporan' => $detail_laporan,
            'scores' => $scores
        ]);
    }
}
