<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class exportMahasiswaPendaftar
{
    /**
     * Export data ke file Excel dan langsung download.
     *
     * @param string $filename - nama file
     * @param array $columns - ['ID', 'Nama', 'Email']
     * @param array $data - array of array [['1', 'Budi', 'budi@gmail.com'], ...]
     */
    public static function export($filename, $columns, $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // dd($columns);
        // Header
        $colIndex = 'A';
        foreach ($columns as $col) {
            $sheet->setCellValue($colIndex . '1', $col);
            // $sheet->getColumnDimension($colIndex)->setAutoSize(true);
            $colIndex++;
        }

        // Data
        $row = 2;
        foreach ($data as $rowData) {
            $col = 'A';
            foreach ($rowData as $cell) {
                $sheet->setCellValue($col . $row, $cell);
                $col++;
            }
            $row++;
        }

        // dd($filename);
        // exit();
        // Output
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        header_remove();
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $tempFile = tempnam(sys_get_temp_dir(), 'plp_export_');
        $writer->save($tempFile);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
        header('Content-Length: ' . filesize($tempFile));
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        header('Expires: 0');

        readfile($tempFile);
        @unlink($tempFile);
        exit;
    }
}
