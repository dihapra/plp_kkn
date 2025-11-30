<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * Export data Kepala Sekolah per sekolah ke XLSX.
 *
 * Kolom:
 * - Nama Sekolah
 * - Nama Kepsek
 * - Email Kepsek
 * - Telepon Kepsek
 * - Bank Kepsek
 * - No Rekening Kepsek
 * - Nama di Rekening Kepsek
 * - NIK Kepsek
 * - Status Kepsek
 */
class PrincipalsExport
{
    protected $CI;
    protected $db;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->db = $this->CI->db;
    }

    public function export()
    {
        $rows = $this->get_all_principals();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Nama Sekolah');
        $sheet->setCellValue('B1', 'Nama Kepsek');
        $sheet->setCellValue('C1', 'Email Kepsek');
        $sheet->setCellValue('D1', 'Telepon Kepsek');
        $sheet->setCellValue('E1', 'Bank Kepsek');
        $sheet->setCellValue('F1', 'No Rekening Kepsek');
        $sheet->setCellValue('G1', 'Nama di Rekening Kepsek');
        $sheet->setCellValue('H1', 'NIK Kepsek');
        $sheet->setCellValue('I1', 'Status Kepsek');

        // Data
        $rowNum = 2;
        foreach ($rows as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['nama_sekolah']);
            $sheet->setCellValue('B' . $rowNum, $row['nama_kepsek']);
            $sheet->setCellValue('C' . $rowNum, $row['email_kepsek']);
            // phone, account_number, nik sebaiknya diset sebagai string
            $sheet->setCellValueExplicit('D' . $rowNum, $row['telepon_kepsek'], DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $rowNum, $row['bank_kepsek']);
            $sheet->setCellValueExplicit('F' . $rowNum, $row['norek_kepsek'], DataType::TYPE_STRING);
            $sheet->setCellValue('G' . $rowNum, $row['nama_rekening_kepsek']);
            $sheet->setCellValueExplicit('H' . $rowNum, $row['nik_kepsek'], DataType::TYPE_STRING);
            $sheet->setCellValue('I' . $rowNum, $row['status_kepsek']);
            $rowNum++;
        }

        // Auto-size kolom
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output XLSX
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'kepala_sekolah_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Ambil data sekolah + principal dalam bentuk array.
     *
     * @return array
     */
    protected function get_all_principals()
    {
        $this->db
            ->select('s.name AS nama_sekolah,
                      p.name AS nama_kepsek,
                      p.email AS email_kepsek,
                      p.phone AS telepon_kepsek,
                      p.bank AS bank_kepsek,
                      p.account_number AS norek_kepsek,
                      p.account_name AS nama_rekening_kepsek,
                      p.nik AS nik_kepsek,
                      p.status AS status_kepsek')
            ->from('school s')
            ->join('principal p', 'p.school_id = s.id', 'left')
            ->order_by('s.name', 'ASC');

        return $this->db->get()->result_array();
    }
}

