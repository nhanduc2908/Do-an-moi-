<?php

namespace App\Services\Module27_ReportAnalytics;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportService
{
    public function exportToExcel($data, $fileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $headers = array_keys($data[0] ?? []);
        foreach ($headers as $col => $header) {
            $sheet->setCellValueByColumnAndRow($col + 1, 1, $header);
        }
        
        // Set data
        foreach ($data as $row => $rowData) {
            foreach ($rowData as $col => $value) {
                $sheet->setCellValueByColumnAndRow($col + 1, $row + 2, $value);
            }
        }
        
        $writer = new Xlsx($spreadsheet);
        $path = storage_path("exports/{$fileName}.xlsx");
        $writer->save($path);
        
        return $path;
    }

    public function exportToCsv($data, $fileName)
    {
        $path = storage_path("exports/{$fileName}.csv");
        $handle = fopen($path, 'w');
        
        // Add headers
        if (!empty($data)) {
            fputcsv($handle, array_keys($data[0]));
        }
        
        // Add data
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);
        
        return $path;
    }

    public function exportToPdf($html, $fileName)
    {
        $pdf = new \Dompdf\Dompdf();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        
        $path = storage_path("exports/{$fileName}.pdf");
        file_put_contents($path, $pdf->output());
        
        return $path;
    }

    public function exportToJson($data, $fileName)
    {
        $path = storage_path("exports/{$fileName}.json");
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
        
        return $path;
    }
}