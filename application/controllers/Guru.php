<?php

use UseCases\AspekPenilaian;
use UseCases\Datatable\StudentCase;
use UseCases\Teacher\AbsensiCase;
use UseCases\Teacher\DashboardData;
use UseCases\Teacher\LogbookCase;
use UseCases\Teacher\EvaluationCase;
use UseCases\Teacher\EvaluationPageCase;

class Guru extends MY_Controller
{
    protected $exclude_methods = [];
    public function __construct()
    {
        parent::__construct();
        $this->check_role(['teacher', 'guru']);
    }
    public function index()
    {
        $uc = new DashboardData();
        $data = $uc->execute();
        view_with_layout("guru/index", "Dashboard Guru", $data);
    }
    public function aktivitas()
    {
        // Method ini memerlukan login
        view_with_layout('guru/aktivitas', 'SIMPLP UNIMED', [], 'css/datatable', 'script/datatable');
    }
    public function get_activity()
    {
        try {
            $data = $this->db->select('teacher_activity.*')->get('teacher_activity')->result();
            response_json("berhasil ambil data", $data);
        } catch (Throwable $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }
    public function absensi()
    {
        // Method ini memerlukan login
        view_with_layout('guru/absensi', 'SIMPLP UNIMED', [], 'css/datatable', 'script/datatable');
    }
    public function mahasiswa()
    {
        // Method ini memerlukan login
        view_with_layout('guru/mahasiswa', 'SIMPLP UNIMED', [], 'css/datatable', 'script/datatable');
    }

    public function logbook()
    {
        try {
            $uc = new LogbookCase();
            $grouped_logbooks = $uc->execute_grouped();
            $maxMeeting = 16; // As per PRD

            view_with_layout('guru/logbook/index', 'SIMPLP UNIMED', [
                'logbooks' => $grouped_logbooks,
                'maxMeeting' => $maxMeeting,
                'editableMeetings' => LogbookCase::EDITABLE_MEETINGS
            ]);
        } catch (Exception $e) {
            log_message('error', 'logbook(): ' . $e->getMessage());
            show_error($e->getMessage(), 500);
        }
    }

    public function penilaian()
    {
        try {
            $uc = new EvaluationPageCase();
            $students = $uc->execute();
            view_with_layout(
                'guru/penilaian/index',
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
            $uc = new EvaluationPageCase();
            $students = $uc->execute();
            view_with_layout(
                'guru/penilaian/intra/index',
                'SIMPLP UNIMED',
                [
                    'students' => $students,
                    'title' => 'Penilaian Intrakurikuler'
                ],
                'css/datatable',
                'script/datatable'
            );
        } catch (Exception $e) {
            log_message('error', 'penilaian_intrakurikuler(): ' . $e->getMessage());
            show_error($e->getMessage(), 500);
        }
    }

    public function penilaian_ekstrakurikuler()
    {
        try {
            $uc = new EvaluationPageCase();
            $students = $uc->execute();
            view_with_layout(
                'guru/penilaian/ekstrakurikuler/index',
                'SIMPLP UNIMED',
                [
                    'students' => $students,
                    'title' => 'Penilaian Ekstrakurikuler'
                ],
                'css/datatable',
                'script/datatable'
            );
        } catch (Exception $e) {
            log_message('error', 'penilaian_ekstrakurikuler(): ' . $e->getMessage());
            show_error($e->getMessage(), 500);
        }
    }

    public function penilaian_sikap()
    {
        try {
            $uc = new EvaluationPageCase();
            $students = $uc->execute();
            view_with_layout(
                'guru/penilaian/sikap/index',
                'SIMPLP UNIMED',
                [
                    'students' => $students,
                    'title' => 'Penilaian Sikap'
                ],
                'css/datatable',
                'script/datatable'
            );
        } catch (Exception $e) {
            log_message('error', 'penilaian_sikap(): ' . $e->getMessage());
            show_error($e->getMessage(), 500);
        }
    }

    public function intra($student_id)
    {
        $aspek = new AspekPenilaian();
        $penilaian_asistensi_intrakurikuler = $aspek->penilaian_asistensi_intrakurikuler;
        $teacher_id = $this->session->userdata('teacher_id');
        $teacher = $this->db->where('id', $teacher_id)->get('teachers')->row();
        if (!$teacher) {
            throw new Exception("Guru tidak ditemukan");
        }
        $student = $this->db->where('id', $student_id)->where('teacher_id', $teacher_id)->get('student')->row();
        view_with_layout("guru/penilaian/intra/penilaian", "Penilaian Intrakurikuler", [
            'penilaian_asistensi_intrakurikuler' => $penilaian_asistensi_intrakurikuler,
            'student' => $student
        ]);
    }

    public function intra_edit($student_id)
    {
        $aspek = new AspekPenilaian();
        $penilaian_asistensi_intrakurikuler = $aspek->penilaian_asistensi_intrakurikuler;
        $teacher_id = $this->session->userdata('teacher_id');
        $teacher = $this->db->where('id', $teacher_id)->get('teachers')->row();
        if (!$teacher) {
            throw new Exception("Guru tidak ditemukan");
        }
        $student = $this->db->where('id', $student_id)->where('teacher_id', $teacher_id)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(1, $student_id); // type 1 for intra
        view_with_layout("guru/penilaian/intra/edit", "Penilaian Intrakurikuler", [
            'penilaian_asistensi_intrakurikuler' => $penilaian_asistensi_intrakurikuler,
            'student' => $student,
            'scores' => $scores
        ]);
    }

    public function intra_view($student_id)
    {
        $aspek = new AspekPenilaian();
        $penilaian_asistensi_intrakurikuler = $aspek->penilaian_asistensi_intrakurikuler;
        $teacher_id = $this->session->userdata('teacher_id');
        $teacher = $this->db->where('id', $teacher_id)->get('teachers')->row();
        if (!$teacher) {
            throw new Exception("Guru tidak ditemukan");
        }
        $student = $this->db->where('id', $student_id)->where('teacher_id', $teacher_id)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(1, $student_id); // type 1 for intra
        view_with_layout("guru/penilaian/intra/view", "Penilaian Intrakurikuler", [
            'penilaian_asistensi_intrakurikuler' => $penilaian_asistensi_intrakurikuler,
            'student' => $student,
            'scores' => $scores
        ]);
    }

    public function ekstra($student_id)
    {
        $aspek = new AspekPenilaian();
        $penilaian_asistensi_kokurikuler = $aspek->penilaian_asistensi_kokurikuler;
        $teacher_id = $this->session->userdata('teacher_id');
        $teacher = $this->db->where('id', $teacher_id)->get('teachers')->row();
        if (!$teacher) {
            throw new Exception("Guru tidak ditemukan");
        }
        $student = $this->db->where('id', $student_id)->where('teacher_id', $teacher_id)->get('student')->row();
        view_with_layout("guru/penilaian/ekstrakurikuler/penilaian", "Penilaian Ekstrakurikuler", [
            'penilaian_asistensi_kokurikuler' => $penilaian_asistensi_kokurikuler,
            'student' => $student
        ]);
    }

    public function ekstra_edit($student_id)
    {
        $aspek = new AspekPenilaian();
        $penilaian_asistensi_kokurikuler = $aspek->penilaian_asistensi_kokurikuler;
        $teacher_id = $this->session->userdata('teacher_id');
        $teacher = $this->db->where('id', $teacher_id)->get('teachers')->row();
        if (!$teacher) {
            throw new Exception("Guru tidak ditemukan");
        }
        $student = $this->db->where('id', $student_id)->where('teacher_id', $teacher_id)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(2, $student_id); // type 2 for ekstra
        view_with_layout("guru/penilaian/ekstrakurikuler/edit", "Penilaian Ekstrakurikuler", [
            'penilaian_asistensi_kokurikuler' => $penilaian_asistensi_kokurikuler,
            'student' => $student,
            'scores' => $scores
        ]);
    }

    public function ekstra_view($student_id)
    {
        $aspek = new AspekPenilaian();
        $penilaian_asistensi_kokurikuler = $aspek->penilaian_asistensi_kokurikuler;
        $teacher_id = $this->session->userdata('teacher_id');
        $teacher = $this->db->where('id', $teacher_id)->get('teachers')->row();
        if (!$teacher) {
            throw new Exception("Guru tidak ditemukan");
        }
        $student = $this->db->where('id', $student_id)->where('teacher_id', $teacher_id)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(2, $student_id); // type 2 for ekstra
        view_with_layout("guru/penilaian/ekstrakurikuler/view", "Penilaian Ekstrakurikuler", [
            'penilaian_asistensi_kokurikuler' => $penilaian_asistensi_kokurikuler,
            'student' => $student,
            'scores' => $scores
        ]);
    }

    public function sikap($student_id)
    {
        $aspek = new AspekPenilaian();
        $penilaian_sikap_mahasiswa = $aspek->penilaian_sikap;
        $teacher_id = $this->session->userdata('teacher_id');
        $teacher = $this->db->where('id', $teacher_id)->get('teachers')->row();
        if (!$teacher) {
            throw new Exception("Guru tidak ditemukan");
        }
        $student = $this->db->where('id', $student_id)->where('teacher_id', $teacher_id)->get('student')->row();
        view_with_layout("guru/penilaian/sikap/penilaian", "Penilaian Sikap", [
            'penilaian_sikap_mahasiswa' => $penilaian_sikap_mahasiswa,
            'student' => $student
        ]);
    }

    public function sikap_edit($student_id)
    {
        $aspek = new AspekPenilaian();
        $penilaian_sikap_mahasiswa = $aspek->penilaian_sikap;
        $teacher_id = $this->session->userdata('teacher_id');
        $teacher = $this->db->where('id', $teacher_id)->get('teachers')->row();
        if (!$teacher) {
            throw new Exception("Guru tidak ditemukan");
        }
        $student = $this->db->where('id', $student_id)->where('teacher_id', $teacher_id)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(3, $student_id); // type 3 for sikap
        view_with_layout("guru/penilaian/sikap/edit", "Penilaian Sikap", [
            'penilaian_sikap_mahasiswa' => $penilaian_sikap_mahasiswa,
            'student' => $student,
            'scores' => $scores
        ]);
    }

    public function sikap_view($student_id)
    {
        $aspek = new AspekPenilaian();
        $penilaian_sikap_mahasiswa = $aspek->penilaian_sikap;
        $teacher_id = $this->session->userdata('teacher_id');
        $teacher = $this->db->where('id', $teacher_id)->get('teachers')->row();
        if (!$teacher) {
            throw new Exception("Guru tidak ditemukan");
        }
        $student = $this->db->where('id', $student_id)->where('teacher_id', $teacher_id)->get('student')->row();
        $uc = new EvaluationCase();
        $scores = $uc->get_score_by_student_id(3, $student_id); // type 3 for sikap
        view_with_layout("guru/penilaian/sikap/view", "Penilaian Sikap", [
            'penilaian_sikap_mahasiswa' => $penilaian_sikap_mahasiswa,
            'student' => $student,
            'scores' => $scores
        ]);
    }

    public function insert_nilai_extra_intra_sikap()
    {
        try {
            $type = $this->input->post('type');
            if (!in_array($type, [1, 2, 3])) {
                throw new Exception("Tipe tidak valid, hanya diperbolehkan 1, 2, atau 3.");
            }
            $uc = new EvaluationCase();
            $uc->save($type);
            response_json('berhasil simpan penilaian');
        } catch (Exception $e) {
            response_error($e->getMessage(), $e);
        }
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
        $result = $uc->teacher_datatable($req);
        $formatter = $uc->student_formatter($result);
        datatable_response($req['draw'], $result, $formatter);
    }
    public function save_absensi()
    {
        try {
            $uc = new AbsensiCase;
            $uc->verif_by_teacher();
            response_json('Berhasil Mengabsen');
        } catch (\Throwable $th) {
            //throw $th;
            response_error($th->getMessage(), $th, 422);
        }
    }

    public function get_logbook_detail($id)
    {
        try {
            $uc = new LogbookCase();
            $logbook = $uc->get_logbook_by_id($id);
            if (!$logbook) {
                throw new Exception("Logbook not found.");
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
                throw new Exception("Logbook tidak valid.");
            }

            $uc = new LogbookCase();
            $logbook = $uc->save_feedback($logbook_id, $feedback);

            response_json('Feedback berhasil disimpan', $logbook);
        } catch (Exception $e) {
            response_error($e->getMessage(), $e, 422);
        }
    }
}
