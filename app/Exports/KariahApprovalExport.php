<?php
// app/Exports/KariahApprovalExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class KariahApprovalExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    ShouldAutoSize, 
    WithStyles,
    WithTitle,
    WithEvents
{
    protected $data;
    protected $masjid;
    protected $includeAllStatus;
    protected $totalMembers;

    public function __construct($data, $masjid = null, $includeAllStatus = false)
    {
        $this->data = $data;
        $this->masjid = $masjid;
        $this->includeAllStatus = $includeAllStatus;
        $this->totalMembers = $data->count();
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        $headers = [
            'BIL',
            'NAMA',
            'NO. KAD PENGENALAN',
            'JANTINA',
            'NO. TELEFON',
            'EMAIL',
            'ALAMAT',
            'TARIKH PENDAFTARAN',
            'STATUS KELULUSAN',
            'TARIKH KELULUSAN',
        ];

        if ($this->includeAllStatus) {
            $headers[] = 'CATATAN';
        }

        return $headers;
    }

    public function map($user): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        // Parse IC number
        $ic = $user->ic_number;
        if (strlen($ic) >= 12) {
            $ic = substr($ic, 0, 6) . '-' . substr($ic, 6, 2) . '-' . substr($ic, 8, 4);
        }

        $statusLabels = [
            'PENDING' => 'MENUNGGU',
            'APPROVED' => 'DILULUSKAN',
            'REJECTED' => 'DITOLAK',
        ];

        $row = [
            $rowNumber,
            strtoupper($user->nama),
            $ic,
            $user->jantina ?? '-',
            $user->notel ?? $user->tel_number ?? '-',
            $user->email ?? '-',
            $user->alamat ?? '-',
            $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-',
            $statusLabels[$user->approval_status] ?? $user->approval_status,
            $user->approved_at ? $user->approved_at->format('d/m/Y H:i') : '-',
        ];

        if ($this->includeAllStatus) {
            $row[] = $user->rejection_reason ?? $user->notes ?? '-';
        }

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        // Style header row
        $lastCol = $this->includeAllStatus ? 'K' : 'J';
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E293B'], // Dark slate
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Apply border to all cells
        $lastRow = $this->totalMembers + 1;
        $sheet->getStyle('A1:' . $lastCol . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        // Color rows based on status
        $statusColumn = $this->includeAllStatus ? 'I' : 'I';
        for ($row = 2; $row <= $lastRow; $row++) {
            $statusCell = $statusColumn . $row;
            $status = $sheet->getCell($statusCell)->getValue();

            $color = 'F8FAFC'; // Default light gray
            if ($status == 'DILULUSKAN') {
                $color = 'DCFCE7'; // Light green
            } elseif ($status == 'MENUNGGU') {
                $color = 'FEF9C3'; // Light yellow
            } elseif ($status == 'DITOLAK') {
                $color = 'FEE2E2'; // Light red
            }

            $sheet->getStyle('A' . $row . ':' . $lastCol . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => str_replace('#', '', $color)],
                ],
            ]);
        }

        return $sheet;
    }

    public function title(): string
    {
        return 'Senarai Ahli Khairat';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastCol = $this->includeAllStatus ? 'K' : 'J';

                // Add summary information at the top
                $sheet->insertNewRowBefore(1, 4);

                $sheet->mergeCells('A1:' . $lastCol . '1');
                $masjidName = $this->masjid ? $this->masjid->nama : 'Sistem e-Khairat';
                $sheet->setCellValue('A1', 'SENARAI AHLI KHAIRAT - ' . strtoupper($masjidName));
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => '1E293B'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->mergeCells('A2:' . $lastCol . '2');
                $sheet->setCellValue('A2', 'Dijana pada: ' . Carbon::now()->format('d/m/Y H:i:s'));
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['size' => 10, 'color' => ['rgb' => '64748B']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A3:' . $lastCol . '3');
                $sheet->setCellValue('A3', 'Jumlah Ahli: ' . $this->totalMembers . ' orang');
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['size' => 10, 'color' => ['rgb' => '64748B']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Adjust column widths
                $columns = range('A', $lastCol);
                foreach ($columns as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Freeze header row
                $sheet->freezePane('A5');
            },
        ];
    }
}