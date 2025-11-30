<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelImportService
{
    /**
     * Import dan validasi data dari file Excel/CSV
     *
     * @param string $filePath
     * @param array $rules format: ['kolom' => 'required|valid_email']
     * @return array ['valid' => [...], 'invalid' => [...]]
     */
    public static function importWithValidation($filePath,  $rules): array
    {
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);

        if (!in_array($ext, ['csv', 'xlsx'])) {
            throw new Exception("Unsupported file type: .$ext");
        }

        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (empty($rows) || count($rows) < 2) return [];

        $headers = array_map('trim', $rows[0]);
        $validData = [];
        $invalidData = [];

        for ($i = 1; $i < count($rows); $i++) {
            $rowAssoc = [];
            foreach ($headers as $index => $key) {
                $rowAssoc[$key] = $rows[$i][$index] ?? null;
            }

            $errors = self::validateRow($rowAssoc, $rules);

            if (empty($errors)) {
                $validData[] = $rowAssoc;
            } else {
                $rowAssoc['_errors'] = $errors;
                $rowAssoc['_row'] = $i + 1;
                $invalidData[] = $rowAssoc;
            }
        }

        return [
            'valid' => $validData,
            'invalid' => $invalidData
        ];
    }

    /**
     * Validasi sederhana per baris (hanya contoh)
     */
    protected static function validateRow(array $row, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = trim($row[$field] ?? '');
            $rulesArray = explode('|', $ruleString);

            foreach ($rulesArray as $rule) {
                if ($rule === 'required' && $value === '') {
                    $errors[$field][] = 'Wajib diisi.';
                } elseif ($rule === 'valid_email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'Format email salah.';
                }
            }
        }

        return $errors;
    }
}
