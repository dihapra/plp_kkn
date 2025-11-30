<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExportService
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

        // Header
        $colIndex = 'A';
        foreach ($columns as $col) {
            $sheet->setCellValue($colIndex . '1', $col);
            $sheet->getColumnDimension($colIndex)->setAutoSize(true);
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

        // Output
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename.xlsx\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
