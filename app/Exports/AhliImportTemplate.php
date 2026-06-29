<?php
// app/Exports/AhliImportTemplate.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AhliImportTemplate implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            [
                'MOHAMMAD BIN AHMAD',
                '010101-10-1234',
                'LELAKI',
                '012-3456789',
                'mohammad@email.com',
                'NO 12, JALAN SENTOSA 1, TAMAN SENTOSA, 43000 KAJANG, SELANGOR',
                'SITI BINTI ALI',
                '010101-10-5678',
                'NO 12, JALAN SENTOSA 1, TAMAN SENTOSA, 43000 KAJANG, SELANGOR',
                '03-12345678',
                '011-23456789',
                '120.00',
                '2026-01-15',
                '2026-01-15',
                '12'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',              // A
            'No Kad Pengenalan', // B
            'Jantina',           // C
            'Telefon Bimbit',    // D
            'Emel',              // E
            'Alamat',            // F
            'Waris Nama',        // G
            'Waris IC',          // H
            'Waris Alamat',      // I
            'Waris Telefon Pejabat', // J
            'Waris Telefon Bimbit',  // K
            'Jumlah Bayaran',    // L
            'Tarikh Bayaran',    // M
            'Tarikh Mula',       // N
            'Tempoh (Bulan)',    // O
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header row
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F97316'], // DJariah orange
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Add instruction sheet
        $this->addInstructionsSheet($sheet);

        return $sheet;
    }

    protected function addInstructionsSheet(Worksheet $sheet)
    {
        // Set column widths
        foreach (range('A', 'O') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add validation comments
        $comments = [
            'A' => 'Nama penuh dalam huruf besar',
            'B' => 'Format: YYMMDD-XX-XXXX (contoh: 010101-10-1234)',
            'C' => 'LELAKI atau PEREMPUAN',
            'D' => 'No telefon bimbit (contoh: 012-3456789)',
            'E' => 'Alamat emel (pilihan)',
            'F' => 'Alamat lengkap',
            'G' => 'Nama waris (pilihan)',
            'H' => 'IC waris (pilihan)',
            'I' => 'Alamat waris (pilihan)',
            'J' => 'No telefon pejabat waris (pilihan)',
            'K' => 'No telefon bimbit waris (pilihan)',
            'L' => 'Jumlah bayaran (RM) - pilihan',
            'M' => 'Tarikh bayaran (YYYY-MM-DD) - pilihan',
            'N' => 'Tarikh mula keahlian (YYYY-MM-DD) - pilihan',
            'O' => 'Tempoh keahlian dalam bulan (contoh: 12) - pilihan',
        ];

        foreach ($comments as $col => $comment) {
            $sheet->getComment($col . '1')->getText()->createTextRun($comment);
        }
    }
}