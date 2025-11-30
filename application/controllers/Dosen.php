<?php

use Repositories\LecturerRepository;
use UseCases\AspekPenilaian;
use UseCases\Datatable\StudentCase;
use UseCases\Lecturer\AbsensiCase;
use UseCases\Lecturer\DashboardData;
use UseCases\Lecturer\EvaluationCase;
use UseCases\Lecturer\EvaluationPageCase;
use UseCases\Lecturer\LaporanCase;
use UseCases\Lecturer\LearningCase;
use UseCases\Lecturer\TugasCase;
use UseCases\Lecturer\FinalScoreCase;
use UseCases\Lecturer\GroupCase;
use UseCases\Lecturer\LogbookCase;

class Dosen extends MY_Controller
{
    protected $exclude_methods = [];
    public function __construct()
    {
        parent::__construct();
        $this->check_role(['lecturer', 'dosen']);
    }
    public function index()
    {

        $uc = new DashboardData();
        $uc->process();
    }
    public function mahasiswa()
    {
        // Method ini memerlukan login
        view_with_layout('dosen/mahasiswa', 'SIMPLP UNIMED', [], 'css/datatable', 'script/datatable');
    }


    public function absensi()
    {
        // Method ini memerlukan login
        view_with_layout('dosen/absensi', 'SIMPLP UNIMED', [], 'css/datatable', 'script/datatable');
    }
    public function tugas()
    {
        $uc = new TugasCase();
        $tugas_individu = $uc->get_individual_submissions();
        $tugas_kelompok = $uc->get_group_submissions();

        // Method ini memerlukan login
        view_with_layout('dosen/tugas/index', 'SIMPLP UNIMED', [
            'tugas_individu' => $tugas_individu,
            'tugas_kelompok' => $tugas_kelompok,
        ], 'css/datatable', 'script/datatable');
    }
    public function tugas_laporan_kemajuan()
    {
        $uc = new TugasCase();
        $tugas_kelompok = $uc->get_group_submissions_by_type(1);

        // Method ini memerlukan login
        view_with_layout('dosen/tugas/laporan-kemajuan/index', 'SIMPLP UNIMED', [
            'tugas_kelompok' => $tugas_kelompok,
        ], 'css/datatable', 'script/datatable');
    }
    public function tugas_laporan_akhir()
    {
        $uc = new TugasCase();
        $tugas_kelompok = $uc->get_group_submissions_by_type(2);

        // Method ini memerlukan login
        view_with_layout('dosen/tugas/laporan-akhir/index', 'SIMPLP UNIMED', [
            'tugas_kelompok' => $tugas_kelompok,
        ], 'css/datatable', 'script/datatable');
    }
    public function tugas_modul_ajar()
    {
        $uc = new TugasCase();
        $tugas_individu = $uc->get_individual_submissions_by_type(3);

        // Method ini memerlukan login
        view_with_layout('dosen/tugas/modul-ajar/index', 'SIMPLP UNIMED', [
            'tugas_individu' => $tugas_individu,
        ], 'css/datatable', 'script/datatable');
    }
    public function tugas_modul_proyek()
    {
        $uc = new TugasCase();
        $tugas_individu = $uc->get_individual_submissions_by_type(5);

        // Method ini memerlukan login
        view_with_layout('dosen/tugas/modul-projek/index', 'SIMPLP UNIMED', [
            'tugas_individu' => $tugas_individu,
        ], 'css/datatable', 'script/datatable');
    }
    public function tugas_bahan_ajar()
    {
        $uc = new TugasCase();
        $tugas_individu = $uc->get_individual_submissions_by_type(4);

        // Method ini memerlukan login
        view_with_layout('dosen/tugas/bahan-ajar/index', 'SIMPLP UNIMED', [
            'tugas_individu' => $tugas_individu,
        ], 'css/datatable', 'script/datatable');
    }
    public function aktivitas()
    {
        // Method ini memerlukan login
        view_with_layout('dosen/aktivitas', 'SIMPLP UNIMED', [], 'css/datatable', 'script/datatable');
    }

    public function logbook()
    {
        try {
            $uc = new LogbookCase();
            $grouped_logbooks = $uc->execute_grouped();
            $maxMeeting = 16;

            view_with_layout('dosen/logbook/index', 'SIMPLP UNIMED', [
                'logbooks' => $grouped_logbooks,
                'maxMeeting' => $maxMeeting,
                'editableMeetings' => LogbookCase::EDITABLE_MEETINGS
            ]);
        } catch (Exception $e) {
            log_message('error', 'Dosen::logbook ' . $e->getMessage());
            show_error('Terjadi kesalahan saat memuat data logbook.', 500);
        }
    }
    public function kelompok()
    {
        // Method ini memerlukan login
        $uc = new GroupCase();
        $groups = $uc->get_groups();
        view_with_layout('dosen/kelompok/index', 'SIMPLP UNIMED', [
            'groups' => $groups
        ], 'css/datatable', 'script/datatable');
    }

    public function member($group_id)
    {
        // Method ini memerlukan login
        $uc = new GroupCase();
        $members = $uc->members_of($group_id);
        response_json("berhasil ambil", $members);
    }
    public function update_leader()
    {
        $uc = new GroupCase();
        $leader = $uc->update_leader();
        response_json("berhasil update", $leader);
    }
    public function penilaian()
    {
        try {
            // --- Ambil dosen dari session
            $uc = new EvaluationPageCase;
            $students = $uc->execute();
            // --- Kirim ke view
            view_with_layout(
                'dosen/penilaian/index',
                'SIMPLP UNIMED',
                ['students' => $students],
                'css/datatable',
                'script/datatable'
            );
        } catch (Exception $e) {
            log_message('error', 'penilaian(): ' . $e->getMessage());
            show_error($e->getMessage(), 500);
        }
    }

    public function penilaian_intrakurikuler()
    {
        try {
            // --- Ambil dosen dari session
            $uc = new EvaluationPageCase;
            $students = $uc->execute();
            // --- Kirim ke view
            view_with_layout(
                'dosen/penilaian/intra/index',
                'SIMPLP UNIMED',
                [
                    'students' => $students,
                    'title' => 'Penilaian Intrakurikuler'
                ],
                'css/datatable',
                'script/datatable'
            );
        } catch (Exception $e) {
            log_message('error', 'penilaian(): ' . $e->getMessage());
            show_error($e->getMessage(), 500);
        }
    }
    public function penilaian_ekstrakurikuler()
    {
        try {
            // --- Ambil dosen dari session
            $uc = new EvaluationPageCase;
            $students = $uc->execute();
            // --- Kirim ke view
            view_with_layout(
                'dosen/penilaian/ekstra/index',
                'SIMPLP UNIMED',
                [
                    'students' => $students,
                    'title' => 'Penilaian Ekstrakurikuler'
                ],
                'css/datatable',
                'script/datatable'
            );
        } catch (Exception $e) {
            log_message('error', 'penilaian(): ' . $e->getMessage());
            show_error($e->getMessage(), 500);
        }
    }
    public function penilaian_sikap()
    {
        try {
            // --- Ambil dosen dari session
            $uc = new EvaluationPageCase;
            $students = $uc->execute();
            // --- Kirim ke view
            view_with_layout(
                'dosen/penilaian/sikap/index',
                'SIMPLP UNIMED',
                [
                    'students' => $students,
                    'title' => 'Penilaian Sikap'
                ],
                'css/datatable',
                'script/datatable'
            );
        } catch (Exception $e) {
            log_message('error', 'penilaian(): ' . $e->getMessage());
            show_error($e->getMessage(), 500);
        }
    }

    public function penilaian_analisis()
    {
        try {
            // --- Ambil dosen dari session
            $uc = new EvaluationPageCase;
            $students = $uc->execute();
            // --- Kirim ke view
            view_with_layout(
                'dosen/penilaian/analisis/index',
                'SIMPLP UNIMED',
                [
                    'students' => $students,
                    'title' => 'Penilaian Analisis Mahasiswa'
                ],
                'css/datatable',
                'script/datatable'
            );
        } catch (Exception $e) {
            log_message('error', 'penilaian_analisis(): ' . $e->getMessage());
            show_error($e->getMessage(), 500);
        }
    }
    public function sikap($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_sikap = $aspek->penilaian_sikap;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        view_with_layout("dosen/penilaian/sikap/penilaian", "Penilaian Sikap Mahasiswa", [
            'penilaian_sikap' => $penilaian_sikap,
            'student' => $student
        ]);
    }

    public function sikap_edit($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_sikap = $aspek->penilaian_sikap;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(3, $student_id); // type 3 untuk penilaian sikap
        view_with_layout("dosen/penilaian/sikap/edit", "Edit Penilaian Sikap Mahasiswa", [
            'penilaian_sikap' => $penilaian_sikap,
            'student' => $student,
            'scores' => $scores
        ]);
    }

    public function sikap_view($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_sikap = $aspek->penilaian_sikap;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(3, $student_id); // type 3 untuk penilaian sikap
        view_with_layout("dosen/penilaian/sikap/view", "View Penilaian Sikap Mahasiswa", [
            'penilaian_sikap' => $penilaian_sikap,
            'student' => $student,
            'scores' => $scores
        ]);
    }

    public function analisis($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_analisis_mahasiswa = $aspek->penilaian_analisis_mahasiswa;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        view_with_layout("dosen/penilaian/analisis/penilaian", "Penilaian Analisis Mahasiswa", [
            'penilaian_analisis_mahasiswa' => $penilaian_analisis_mahasiswa,
            'student' => $student
        ]);
    }

    public function analisis_edit($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_analisis_mahasiswa = $aspek->penilaian_analisis_mahasiswa;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(4, $student_id); // type 4 for analisis
        view_with_layout("dosen/penilaian/analisis/penilaian", "Edit Penilaian Analisis Mahasiswa", [
            'penilaian_analisis_mahasiswa' => $penilaian_analisis_mahasiswa,
            'student' => $student,
            'scores' => $scores
        ]);
    }

    public function analisis_view($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_analisis_mahasiswa = $aspek->penilaian_analisis_mahasiswa;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(4, $student_id); // type 4 for analisis
        view_with_layout("dosen/penilaian/analisis/view", "View Penilaian Analisis Mahasiswa", [
            'penilaian_analisis_mahasiswa' => $penilaian_analisis_mahasiswa,
            'student' => $student,
            'scores' => $scores
        ]);
    }

    public function nilai_akhir()
    {
        try {
            $uc = new FinalScoreCase();
            $data = $uc->getAllStudentScores();
            view_with_layout("dosen/nilai/index", "Rekapitulasi Nilai Akhir", $data);
        } catch (Exception $e) {
            log_message('error', 'nilai_akhir(): ' . $e->getMessage());
            show_error($e->getMessage(), 500);
        }
    }





    public function intra($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_asistensi_intrakurikuler = $aspek->penilaian_asistensi_intrakurikuler;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        view_with_layout("dosen/penilaian/intra/penilaian", "Penilaian Intrakurikuler", [
            'penilaian_asistensi_intrakurikuler' => $penilaian_asistensi_intrakurikuler,
            'student' => $student
        ]);
    }
    public function intra_edit($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_asistensi_intrakurikuler = $aspek->penilaian_asistensi_intrakurikuler;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(1, $student_id);
        view_with_layout("dosen/penilaian/intra/edit", "Penilaian Intrakurikuler", [
            'penilaian_asistensi_intrakurikuler' => $penilaian_asistensi_intrakurikuler,
            'student' => $student,
            'scores' => $scores
        ]);
    }
    public function intra_view($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_asistensi_intrakurikuler = $aspek->penilaian_asistensi_intrakurikuler;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(1, $student_id);
        view_with_layout("dosen/penilaian/intra/view", "Penilaian Intrakurikuler", [
            'penilaian_asistensi_intrakurikuler' => $penilaian_asistensi_intrakurikuler,
            'student' => $student,
            'scores' => $scores
        ]);
    }
    public function ekstra($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_asistensi_kokurikuler = $aspek->penilaian_asistensi_kokurikuler;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        view_with_layout("dosen/penilaian/ekstra/penilaian", "Penilaian Intrakurikuler", [
            'penilaian_asistensi_kokurikuler' => $penilaian_asistensi_kokurikuler,
            'student' => $student
        ]);
    }
    public function ekstra_edit($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_asistensi_kokurikuler = $aspek->penilaian_asistensi_kokurikuler;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(2, $student_id);
        view_with_layout("dosen/penilaian/intra/edit", "Penilaian Intrakurikuler", [
            'penilaian_asistensi_kokurikuler' => $penilaian_asistensi_kokurikuler,
            'student' => $student,
            'scores' => $scores
        ]);
    }
    public function ekstra_view($student_id)
    {
        $aspek = new AspekPenilaian;
        $penilaian_asistensi_kokurikuler = $aspek->penilaian_asistensi_kokurikuler;
        $nip = $this->session->userdata('nip');
        $lecturer = $this->db->where('nip', $nip)->get('lecturers')->row();
        if (!$lecturer) {
            throw new Exception("Dosen tidak ditemukan");
        }
        $lid = (int) $lecturer->id;
        $student = $this->db->where('id', $student_id)->where('lecture_id', $lid)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(2, $student_id);
        view_with_layout("dosen/penilaian/ekstra/view", "View Penilaian Kokurikuler", [
            'penilaian_asistensi_kokurikuler' => $penilaian_asistensi_kokurikuler,
            'student' => $student,
            'scores' => $scores
        ]);
    }

    public function datatable_absensi()
    {
        $req = get_param_datatable();
        $uc = new AbsensiCase();
        $result = $uc->datatable($req);
        $formatter = $uc->absensi_formatter($result);
        datatable_response($req['draw'], $result, $formatter);
    }
    public function datatable_student()
    {
        $req = get_param_datatable();
        $uc = new StudentCase();
        $result = $uc->lecture_datatable($req);
        $formatter = $uc->student_formatter($result);
        datatable_response($req['draw'], $result, $formatter);
    }
    public function save_absensi()
    {
        try {
            $uc = new AbsensiCase;
            $uc->verif_by_lecture();
            response_json('Berhasil Mengabsen');
        } catch (\Throwable $th) {
            //throw $th;
            response_error($th->getMessage(), $th, 422);
        }
    }
    public function get_activity()
    {
        try {
            $data = $this->db->select('lecturer_activity.*')->get('lecturer_activity')->result();
            response_json("berhasil ambil data", $data);
        } catch (Throwable $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }

    public function get_logbook_detail($id)
    {
        try {
            $uc = new LogbookCase();
            $logbook = $uc->get_logbook_by_id($id);
            if (!$logbook) {
                throw new Exception("Logbook tidak ditemukan.");
            }
            response_json("success", $logbook);
        } catch (Exception $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }

    public function save_logbook_feedback()
    {
        try {
            $logbook_id = (int) $this->input->post('logbook_id');
            $feedback = trim($this->input->post('feedback'));

            if ($logbook_id <= 0) {
                throw new Exception('Logbook tidak valid.');
            }

            $uc = new LogbookCase();
            $logbook = $uc->save_feedback($logbook_id, $feedback);

            response_json('Feedback berhasil disimpan', $logbook);
        } catch (Exception $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }


    public function insert_nilai_laporan()
    {
        try {
            $uc = new LaporanCase;
            $uc->execute();
            response_json('berhasil simpan penilaian');
        } catch (Exception $e) {
            // kamu juga bisa tambahkan log_message('error', ...) di sini
            response_error($e->getMessage(), $e);
        }
    }
    public function insert_nilai_ajar($type)
    {
        try {
            if (!in_array($type, [3, 4, 5])) {
                throw new Exception("Tipe tidak valid, hanya diperbolehkan 3, 4, atau 5.");
            }
            $uc = new LearningCase;
            $uc->execute($type);
            response_json('berhasil simpan penilaian');
        } catch (Exception $e) {
            // kamu juga bisa tambahkan log_message('error', ...) di sini
            response_error($e->getMessage(), $e);
        }
    }
    public function insert_nilai_extra_intra_sikap($type)
    {
        try {
            if (!in_array($type, [1, 2, 3, 4])) {
                throw new Exception("Tipe tidak valid, hanya diperbolehkan 1, 2, 3, atau 4.");
            }
            $uc = new EvaluationCase;
            $uc->save($type);
            response_json('berhasil simpan penilaian');
        } catch (Exception $e) {
            // kamu juga bisa tambahkan log_message('error', ...) di sini
            response_error($e->getMessage(), $e);
        }
    }
}
