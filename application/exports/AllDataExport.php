<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Loader $load
 * @property CI_Upload $upload
 */
class AllDataExport
{
    protected $CI;
    protected $db;
    protected $AuthRepository;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->AuthRepository = new AuthRepository();
        $this->db = $this->CI->db;
    }
    public function export()
    {
        $req['prodi'] = $this->CI->input->post('prodi');
        $req['fakultas'] = $this->CI->input->post('fakultas');
        $result = $this->admin_export_all($req);
        $formatter = $this->formatter($result);
        // Buat objek Spreadsheet baru
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Buat header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Sekolah');
        $sheet->setCellValue('C1', 'Nama Kepala Sekolah');
        $sheet->setCellValue('D1', 'No Handphone Kepala Sekolah');
        $sheet->setCellValue('D1', 'Nama Guru Pamong');
        $sheet->setCellValue('E1', 'Nama DPL');
        $sheet->setCellValue('F1', 'Email Mahasiswa');
        $sheet->setCellValue('G1', 'Nama Mahasiswa');
        $sheet->setCellValue('H1', 'NIM');
        $sheet->setCellValue('I1', 'No Handphone Mahasiswa');
        $sheet->setCellValue('J1', 'Prodi');
        $sheet->setCellValue('K1', 'Fakultas');

        // Isi data mulai dari baris kedua
        $rowNum = 2;
        $no = 1;
        foreach ($formatter as $data) {
            $sheet->setCellValue('A' . $rowNum, $no);
            $sheet->setCellValue('B' . $rowNum, $data['school_name']);
            $sheet->setCellValue('C' . $rowNum, $data['principal']);
            $sheet->setCellValue('D' . $rowNum, $data['principal_phone']);
            $sheet->setCellValue('D' . $rowNum, $data['teacher_name']);
            $sheet->setCellValue('E' . $rowNum, $data['lecturer_name']);
            $sheet->setCellValue('F' . $rowNum, $data['student_email']);
            $sheet->setCellValue('G' . $rowNum, $data['student_name']);
            $sheet->setCellValue('H' . $rowNum, $data['nim']);
            $sheet->setCellValue('I' . $rowNum, $data['student_phone']);
            $sheet->setCellValue('J' . $rowNum, $data['student_prodi']);
            $sheet->setCellValue('K' . $rowNum, $data['student_fakultas']);
            $no++;
            $rowNum++;
        }
        // Siapkan writer untuk format XLSX
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'all_data_export_' . date('YmdHis') . '.xlsx';
        
        // Set header untuk download file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Output file ke browser
        $writer->save('php://output');
        exit;
    }
    public function admin_export_all($param)
    {
        $this->db->select('
        student.id,
        school.name as school_name,
        school.principal,
        school.phone as principal_phone,
        lecturers.name as lecturer_name,
        student.email as student_email,
        student.name as student_name,
        student.nim,
        student.phone as student_phone,
        student.prodi as student_prodi,
        student.fakultas as student_fakultas,
         teachers.name as teacher_name
    ');
        $this->db->from('student');
        $this->db->join('teachers', 'teachers.id = student.teacher_id', 'left');
        $this->db->join('lecturers', 'lecturers.id = student.lecture_id', 'left');
        $this->db->join('school', 'school.id = student.school_id', 'left');
        // $count_total = $this->db->count_all_results('', false);

        // Filter berdasarkan fakultas dan prodi jika ada
        if (!empty($param['fakultas'])) {
            $this->db->where('student.fakultas', $param['fakultas']);
        }
        if (!empty($param['prodi'])) {
            $this->db->where('student.prodi', $param['prodi']);
        }

        $query = $this->db->get();
        return [
            'query' => $query->result(),
        ];
    }



    public function formatter($result)
    {

        $formatter = [];
        foreach ($result['query'] as $r) {
            $formatter[] = array(
                'id' => $r->id,
                'school_name' => $r->school_name,
                'principal' => $r->principal,
                'principal_phone' => $r->principal_phone,
                'lecturer_name' => $r->lecturer_name,
                'student_email' => $r->student_email,
                'student_name' => $r->student_name,
                'nim' => $r->nim,
                'student_phone' => $r->student_phone,
                'student_prodi' => $r->student_prodi,
                'student_fakultas' => $r->student_fakultas,
                'teacher_name' => $r->teacher_name
            );
        }
        return $formatter;
    }
}
