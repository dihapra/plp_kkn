<?php

use PhpOffice\PhpSpreadsheet\Cell\DataType;

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Loader $load
 * @property CI_Upload $upload
 */
class TeachersExport
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
        $school_id = empty($_GET['schoolId']) ? $_GET['schoolId'] : 0;
        $teachers = $this->get_all_teachers($school_id);
        // var_dump($teachers);
        // exit();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Sekolah');
        $sheet->setCellValue('C1', 'Nama Guru');
        $sheet->setCellValue('D1', 'No Handphone Guru');
        $sheet->setCellValue('E1', 'Email Guru');
        $sheet->setCellValue('F1', 'Bank');
        $sheet->setCellValue('G1', 'No Rekening');
        $sheet->setCellValue('H1', 'Nama Rekening');
        $sheet->setCellValue('I1', 'Status Pernikahan');
        $sheet->setCellValue('J1', 'NIK');
        $sheet->setCellValue('K1', 'Jumlah Mahasiswa');
        // $sheet->setCellValue('I1', '');

        // Isi data mulai dari baris kedua
        $rowNum = 2;
        $no = 1;
        foreach ($teachers as $data) {
            $sheet->setCellValue('A' . $rowNum, $no);
            $sheet->setCellValue('B' . $rowNum, $data['school_name']);
            $sheet->setCellValue('C' . $rowNum, $data['name']);
            $sheet->setCellValueExplicit('D' . $rowNum, $data['phone'], DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $rowNum, $data['email']);
            $sheet->setCellValue('F' . $rowNum, $data['bank']);
            $sheet->setCellValueExplicit('G' . $rowNum, $data['account_number'], DataType::TYPE_STRING);
            $sheet->setCellValue('H' . $rowNum, $data['account_name']);
            $sheet->setCellValue('I' . $rowNum, $data['status']);
            $sheet->setCellValueExplicit('J' . $rowNum, $data['nik'], DataType::TYPE_STRING);
            $sheet->setCellValue('K' . $rowNum, $data['total_students']);
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
    public function get_all_teachers($school_id = 0)
    {
        $this->db->select('teachers.*, school.name as school_name, COUNT(student.id) as total_students');
        $this->db->from('teachers');
        $this->db->join('school', 'school.id = teachers.school_id', 'left');
        $this->db->join('student', 'student.teacher_id = teachers.id', 'left'); // join ke student
        $this->db->where('status_data', 'verified');
        if (!empty($school_id) && $school_id != 0) {
            $this->db->where('teachers.school_id', $school_id);
        }

        $this->db->group_by('teachers.id'); // biar COUNT() gak tabrakan

        return $this->db->get()->result_array();
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
