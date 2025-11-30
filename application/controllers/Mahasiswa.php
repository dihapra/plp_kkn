<?php

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\ListItem;
use PhpOffice\PhpWord\TemplateProcessor;
use Repositories\SchoolRepository;
use Repositories\StudentRepository;
use Repositories\SubmissionRepository;
use UseCases\Student\SaveLogbookCase;
use UseCases\Student\UploadTugasCase;

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property User_model $User_model
 */

class Mahasiswa extends MY_Controller
{
    protected $exclude_methods = ['get_logbook_by_meeting'];
    protected $DashboardService;
    protected $StudentRepository;
    protected $SubmissionRepository;
    protected $SchoolRepository;


    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->AuthService = new AuthService();
        $this->DashboardService = new DashboardService();
        $this->StudentRepository = new StudentRepository();
        $this->SubmissionRepository = new SubmissionRepository();
        $this->SchoolRepository = new SchoolRepository();
    }
    public function index()
    {
        $student = $this->StudentRepository->get_student_relation($this->session->userdata('nim'));
        view_with_layout('mahasiswa/index', 'SIMPLP UNIMED', ['student' => $student]);
    }
    public function tugas()
    {
        $nim = $this->session->userdata('nim');
        $student = $this->StudentRepository->get_by_key('nim', $nim);

        // Ambil submission individu (untuk tugas terbimbing)
        $submission = $this->SubmissionRepository->get_by_student_id($student->id);

        // Ambil submission kelompok (hanya jika ada group_id)
        $submission_group = [];
        if (!empty($student->group_id)) {
            $submission_group = $this->SubmissionRepository->get_by_group_id($student->group_id);
        }
        // var_dump($submission);
        // exit();
        view_with_layout('mahasiswa/tugas', 'SIMPLP UNIMED', [
            'student' => $student,
            'submission' => $submission,
            'submission_group' => $submission_group
        ]);
    }
    public function aktivitas()
    {
        view_with_layout('mahasiswa/aktivitas/index', 'SIMPLP UNIMED');
    }
    public function upload_tugas($type)
    {
        try {
            $uc = new UploadTugasCase();
            $uc->upload_laporan($type);
            response_json([
                'status' => 'success',
                'message' => 'Berhasil Menyimpan data',
            ], 200);
        } catch (Throwable $e) {
            response_error(
                $e->getMessage(),
                $e,
                422
            );
        }
    }
    public function save_logbook()
    {
        try {
            $uc = new SaveLogbookCase();
            $uc->save_logbook();
            response_json('Berhasil Menyimpan data');
        } catch (\Throwable $th) {
            response_error($th->getMessage(), $th, 422);
        }
    }
    public function get_activity()
    {
        try {
            $data = $this->db->select('student_activity.*')->get('student_activity')->result();
            response_json("berhasil ambil data", $data);
        } catch (Throwable $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }
    public function get_attendance()
    {
        try {
            $email = $this->session->userdata('identifier');
            $student = $this->db->where('email', $email)->get('student')->row();
            $data = $this->db->where('student_id', $student->id)->get('attendance')->result_array(); // Kondisi berdasarkan student_id
            response_json("berhasil ambil data", $data);
        } catch (Throwable $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }
    public function get_logbook_by_student()
    {
        try {
            $email = $this->session->userdata('identifier');
            $student = $this->db->where('email', $email)->get('student')->row();
            $uc = new SaveLogbookCase();
            $data = $uc->get_by_student_id($student->id);
            response_json("berhasil ambil data", $data);
        } catch (Throwable $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }
    public function get_logbook_by_meeting($meeting_number)
    {
        try {
            $email = $this->session->userdata('identifier');
            $student = $this->db->where('email', $email)->get('student')->row();
            $uc = new SaveLogbookCase();
            $data = $uc->get_by_student_and_meeting($student->id, $meeting_number);
            response_json("berhasil ambil data", $data);
        } catch (Throwable $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }
    public function update_logbook()
    {
        try {
            $uc = new SaveLogbookCase();
            $uc->update_logbook();
            response_json("berhasil simpan data");
        } catch (Throwable $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }

    public function cetak_surat_tugas()
    {
        $user = $this->session->userdata();

        $mahasiswa = $this->StudentRepository->get_by_key('nim', $user['nim']);
        $school_id = $mahasiswa->school_id;
        $kepsek = $this->SchoolRepository->surat_tugas_kepsek($school_id);
        $teachers = $this->StudentRepository->get_student_with_teacher($school_id);
        // dd($kepsek, $teachers);
        $templatePath = FCPATH . 'storage/templates/Template Surat Tugas Guru Pamong PLP II 2025.docx';
        $tp = new TemplateProcessor($templatePath);
        $nomorAcak = rand(100, 999) . '/PLP/UNIMED/2025';
        if (!file_exists($templatePath)) {
            die('❌ Template tidak ditemukan di: ' . $templatePath);
        }

        // ====== Header/isi surat ======
        // pastikan $kepsek tidak null agar tidak error
        if (!$kepsek) {
            $this->session->set_flashdata('error', 'Data sekolah atau kepala sekolah tidak ditemukan.');
            redirect('/mahasiswa');
            return; // pastikan keluar dari fungsi
        }
        if (empty($mahasiswa->teacher_id)) {
            $this->session->set_flashdata('error', 'Data guru pamong tidak ditemukan.');
            redirect('/mahasiswa');
            return; // pastikan keluar dari fungsi
        }
        // set value ke template
        $tp->setValues([
            'nomor_surat'  => $nomorAcak,
            'lampiran'     => '1 berkas',
            'nama_sekolah' => $kepsek->nama_sekolah,
            'kepsek_nama'  => $kepsek->kepsek_nama,
            'nik'          => $kepsek->nik,
            'account_name' => $kepsek->account_name,
            'no_rekening'  => $kepsek->no_rekening,
            'status_perkawinan' => $kepsek->status_perkawinan
        ]);


        // ====== Kelompokkan per guru ======
        $grouped = [];

        foreach ($teachers as $r) {
            $g = $r->guru_pamong; // pakai -> karena stdClass

            if (!isset($grouped[$g])) {
                $grouped[$g] = [
                    'guru_pamong'       => $r->guru_pamong,
                    'nik'               => $r->nik,
                    'account_name'      => $r->account_name,
                    'no_rekening'       => $r->no_rekening,
                    'status_perkawinan' => $r->status_perkawinan,
                    'list'              => [],
                ];
            }

            $grouped[$g]['list'][] = [
                'nama_mahasiswa' => $r->nama_mahasiswa,
                'nim'            => $r->nim,
                'prodi'          => $r->prodi,
            ];
        }

        // dd($g);
        // ====== Bangun tabel dengan merge kolom + bullet list ======
        $phpWord = new PhpWord();
        $phpWord->addNumberingStyle('bulletDot', [
            'type'   => 'singleLevel',
            'levels' => [[
                'format'  => 'bullet',
                'text'    => '•',
                'left'    => 360,
                'hanging' => 360,
                'tabPos'  => 360,
            ]],
        ]);
        // (opsional) style global tabel
        $tableStyle = [
            'borderSize'         => 6,
            'borderColor'        => '000000',
            'borderInsideHSize'  => 6,        // << penting
            'borderInsideVSize'  => 6,        // << penting
            'borderInsideColor'  => '000000',
            'cellMargin'         => 80,
        ];

        $cellAllBorders = [                   // dipakai untuk setiap cell
            'borderSize'  => 6,
            'borderColor' => '000000',
            'valign'      => 'center',
        ];

        $table = new \PhpOffice\PhpWord\Element\Table($tableStyle);
        // dd($table);
        // header
        $table->addRow();
        $table->addCell(800, $cellAllBorders)->addText('No', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(2500, $cellAllBorders)->addText('Nama Guru', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(3500, $cellAllBorders)->addText('Data Pembayaran Guru', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(3000, $cellAllBorders)->addText('Nama Mahasiswa', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(2500, $cellAllBorders)->addText('NIM', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(3500, $cellAllBorders)->addText('Prodi', ['bold' => true], ['alignment' => 'center']);

        // penomoran per-guru
        $no = 1;

        foreach ($grouped as $g) {
            $first = true;

            // siapkan item bullet (tanpa HTML / numbering style)
            $payItems = [
                'NIK: ' . $g['nik'],
                'Nama di Rekening: ' . $g['account_name'],
                'No. Rekening: ' . $g['no_rekening'],
                'Status Perkawinan: ' . $g['status_perkawinan'],
            ];

            foreach ($g['list'] as $item) {
                $table->addRow();

                // === kolom No (vMerge)
                if ($first) {
                    $cellNo = $table->addCell(800, $cellAllBorders + ['vMerge' => 'restart', 'valign' => 'center']);
                    $cellNo->addText((string)$no, ['bold' => true], ['alignment' => 'center']);
                } else {
                    $table->addCell(800, $cellAllBorders + ['vMerge' => 'continue']);
                }

                // === kolom Nama Guru (vMerge + center)
                if ($first) {
                    $cellGuru = $table->addCell(2500, $cellAllBorders + ['vMerge' => 'restart', 'valign' => 'center']);
                    $cellGuru->addText($g['guru_pamong'], ['bold' => true], ['alignment' => 'center']);
                } else {
                    $table->addCell(2500, $cellAllBorders + ['vMerge' => 'continue']);
                }

                // === kolom Data Pembayaran (vMerge + bullet manual)
                if ($first) {
                    $cellPay = $table->addCell(3500, $cellAllBorders + ['vMerge' => 'restart', 'valign' => 'top']);

                    // buat satu TextRun dan tambahkan baris-baris bullet
                    $run = $cellPay->addTextRun();
                    $lastIdx = count($payItems) - 1;
                    foreach ($payItems as $idx => $txt) {
                        $run->addText('• ' . $txt);
                        if ($idx !== $lastIdx) {
                            $run->addTextBreak(); // baris baru antar item
                        }
                    }
                } else {
                    $table->addCell(3500, $cellAllBorders + ['vMerge' => 'continue']);
                }

                // === kolom mahasiswa (baris biasa) ===
                $table->addCell(3000, $cellAllBorders)->addText($item['nama_mahasiswa']);
                $table->addCell(2500, $cellAllBorders)->addText($item['nim']);
                $table->addCell(3500, $cellAllBorders)->addText($item['prodi']);

                $first = false;
            }

            $no++; // nomor naik per-guru
        }


        // Sisipkan tabel ke placeholder ${TABEL_GURU} dalam template
        $tp->setComplexBlock('TABEL_GURU', $table);
        // ====== Output aman ======
        $filename = 'Surat_Tugas_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $kepsek->nama_sekolah) . '.docx';
        $tempFile = FCPATH . 'storage/temp/' . time() . '_surat_tugas.docx';
        @mkdir(dirname($tempFile), 0777, true);
        $tp->saveAs($tempFile);

        // pastikan file benar-benar ada dan bisa dibaca
        if (!file_exists($tempFile) || filesize($tempFile) < 2000) {
            log_message('error', 'File Word gagal dibuat atau kosong: ' . $tempFile);
        }

        // bersihkan output buffer SEBELUM kirim header
        if (ob_get_length()) ob_end_clean();

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($tempFile));

        flush();
        readfile($tempFile);
        @unlink($tempFile);
        exit;
    }
}
