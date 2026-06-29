<?php
// app/Http/Controllers/BulkRegisterController.php

namespace App\Http\Controllers;


use App\Models\UserRegister;
use App\Models\masjid;
use App\Models\User;
use App\Models\AhliKariah;
use App\Models\Waris;
use App\Models\SubscriptionsKariah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\MembershipService;

class BulkRegisterController extends Controller
{
    /**
     * Show bulk register page
     */
    public function index()
    {
        $masjidID = auth()->user()->masjid_id;
        $masjids = Masjid::where('id', $masjidID)->get();
        return view('Ahli_Kariah.Bulk_Register', compact('masjids'));
    }

    /**
     * Download Excel template for bulk registration
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT AHLI KHAIRAT');
        $sheet->mergeCells('A1:O1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Instructions
        $sheet->setCellValue('A2', 'Panduan:');
        $sheet->setCellValue('A3', '1. Isi semua maklumat yang diperlukan. Kolum dengan * adalah WAJIB.');
        $sheet->setCellValue('A4', '2. Format No. IC: 010101-10-1234');
        $sheet->setCellValue('A5', '3. Jantina: LELAKI atau PEREMPUAN');
        $sheet->setCellValue('A6', '4. Status: AKTIF atau TIDAK AKTIF');
        $sheet->setCellValue('A7', '5. Tarikh: format YYYY-MM-DD (contoh: 2026-01-15)');
        $sheet->setCellValue('A8', '6. Untuk Keahlian: Isi kolum TARIKH MULA KEAHLIAN jika ingin daftar keahlian (tarikh tamat dikira automatik)');
        $sheet->mergeCells('A2:Q2');
        $sheet->mergeCells('A3:Q3');
        $sheet->mergeCells('A4:Q4');
        $sheet->mergeCells('A5:Q5');
        $sheet->mergeCells('A6:Q6');
        $sheet->mergeCells('A7:Q7');
        $sheet->mergeCells('A8:Q8');
        $sheet->getStyle('A2:A8')->getFont()->setSize(10);
        $sheet->getStyle('A2:A8')->getFont()->getColor()->setARGB('FF64748B');

        // Headers (row 10)
        $headers = [
            'A' => 'NAMA*',
            'B' => 'NO. KAD PENGENALAN*',
            'C' => 'JANTINA*',
            'D' => 'NO. TELEFON*',
            'E' => 'EMAIL',
            'F' => 'ALAMAT',
            'G' => 'TARIKH LAHIR',
            'H' => 'UMUR',
            'I' => 'BANGSA',
            'J' => 'STATUS AHLI',
            'K' => 'WARIS NAMA',
            'L' => 'WARIS IC',
            'M' => 'WARIS TELEFON',
            'N' => 'WARIS ALAMAT',
            'O' => 'TARIKH MULA KEAHLIAN',
        ];

        $row = 10;
        foreach ($headers as $col => $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF1E293B');
            $sheet->getStyle($col . $row)->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Sample data (row 11)
        $sampleData = [
            'A' => 'MOHAMMAD BIN AHMAD',
            'B' => '010101-10-1234',
            'C' => 'LELAKI',
            'D' => '012-3456789',
            'E' => 'mohammad@email.com',
            'F' => 'NO 12, JALAN SENTOSA, TAMAN SENTOSA, 43000 KAJANG',
            'G' => '2001-01-01',
            'H' => '25',
            'I' => 'MELAYU',
            'J' => 'AKTIF',
            'K' => 'SITI BINTI ALI',
            'L' => '010101-10-5678',
            'M' => '011-23456789',
            'N' => 'NO 12, JALAN SENTOSA, TAMAN SENTOSA, 43000 KAJANG',
            'O' => '2026-01-15',
        ];

        $row = 11;
        foreach ($sampleData as $col => $value) {
            $sheet->setCellValue($col . $row, $value);
            $sheet->getStyle($col . $row)->getFont()->getColor()->setARGB('FF94A3B8');
        }

        // Apply borders to header and sample
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFE2E8F0'],
                ],
            ],
        ];
        $sheet->getStyle('A10:O11')->applyFromArray($styleArray);

        // Auto-size columns
        foreach (range('A', 'O') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Add validation comments
        $comments = [
            'A' => 'Nama penuh dalam huruf besar (WAJIB)',
            'B' => 'Format: YYMMDD-XX-XXXX (WAJIB)',
            'C' => 'LELAKI atau PEREMPUAN (WAJIB)',
            'D' => 'No telefon bimbit (WAJIB)',
            'E' => 'Alamat emel (pilihan)',
            'F' => 'Alamat lengkap (pilihan)',
            'G' => 'Tarikh lahir format YYYY-MM-DD (pilihan)',
            'H' => 'Umur dalam tahun (pilihan)',
            'I' => 'MELAYU/CINA/INDIA/LAIN (pilihan)',
            'J' => 'AKTIF atau TIDAK AKTIF (pilihan - default AKTIF)',
            'K' => 'Nama waris (pilihan)',
            'L' => 'IC waris format YYMMDD-XX-XXXX (pilihan)',
            'M' => 'No telefon waris (pilihan)',
            'N' => 'Alamat waris (pilihan)',
            'O' => 'Tarikh mula keahlian format YYYY-MM-DD (pilihan)',
        ];

        foreach ($comments as $col => $comment) {
            $sheet->getComment($col . '10')->getText()->createTextRun($comment);
        }

        // Create response
        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="Template_Import_Ahli_Khairat.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    /**
     * Process uploaded Excel file
     */
    public function upload(Request $request, MembershipService $membershipService)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
            'masjid_id' => 'required|exists:masjids,id',
        ]);

        try {
            $masjid_id = $request->masjid_id;
            $file = $request->file('file');

            // Load the spreadsheet
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Find header row
            $headerRow = null;
            $dataStartRow = null;

            foreach ($rows as $index => $row) {
                if (isset($row[0]) && str_contains(strtoupper($row[0]), 'NAMA')) {
                    $headerRow = $index;
                    $dataStartRow = $index + 1;
                    break;
                }
            }

            if ($headerRow === null || $dataStartRow === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format fail tidak sesuai. Sila gunakan template yang disediakan.',
                ], 422);
            }

            // Process data
            $results = [
                'success' => 0,
                'failed' => 0,
                'errors' => [],   // kept for backward compatibility, flat list of "Baris X: ..." strings
                'users' => [],    // kept for backward compatibility, successful rows only
                'rows' => [],     // NEW: every row attempted, with status + message, for full detail display
            ];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                if ($index < $dataStartRow) continue;
                if (empty(array_filter($row))) continue;

                $rowNum = $index + 1;

                // Map columns up front so we can show row detail even on failure
                $nama = trim($row[0] ?? '');
                $ic_number = trim($row[1] ?? '');
                $jantina = trim($row[2] ?? '');
                $notel = trim($row[3] ?? '');
                $email = trim($row[4] ?? '');
                $alamat = trim($row[5] ?? '');
                $tarikh_lahir = trim($row[6] ?? '');
                $umur = trim($row[7] ?? '');
                $bangsa = trim($row[8] ?? '');
                $status_ahli = trim($row[9] ?? '');
                $waris_nama = trim($row[10] ?? '');
                $waris_ic = trim($row[11] ?? '');
                $waris_telefon = trim($row[12] ?? '');
                $waris_alamat = trim($row[13] ?? '');
                $tarikh_mula = trim($row[14] ?? '');

                $fail = function (string $message) use (&$results, $rowNum, $nama, $ic_number, $notel, $email) {
                    $results['failed']++;
                    $results['errors'][] = "Baris {$rowNum}: {$message}";
                    $results['rows'][] = [
                        'row' => $rowNum,
                        'nama' => $nama ?: '-',
                        'ic' => $ic_number ?: '-',
                        'notel' => $notel ?: '-',
                        'email' => $email ?: '-',
                        'status' => 'failed',
                        'message' => $message,
                    ];
                };

                try {
                    // Validate required fields
                    if (empty($nama) || empty($ic_number) || empty($jantina) || empty($notel)) {
                        $fail('Medan wajib tidak lengkap (nama/IC/jantina/no. telefon).');
                        continue;
                    }

                    // Validate IC format
                    $cleanIc = preg_replace('/[^0-9]/', '', $ic_number);
                    if (strlen($cleanIc) !== 12) {
                        $fail("Format IC tidak sah ({$ic_number}).");
                        continue;
                    }

                    // Format IC
                    $formattedIc = substr($cleanIc, 0, 6) . '-' . substr($cleanIc, 6, 2) . '-' . substr($cleanIc, 8, 4);

                    // Check if user already exists
                    $existingUser = User::where('ic_number', $formattedIc)
                        ->where('masjid_id', $masjid_id)
                        ->first();

                    if ($existingUser) {
                        $fail("IC {$formattedIc} sudah wujud dalam sistem.");
                        continue;
                    }

                    // Validate jantina
                    if (!in_array(strtoupper($jantina), ['LELAKI', 'PEREMPUAN'])) {
                        $fail('Jantina tidak sah. Gunakan LELAKI atau PEREMPUAN.');
                        continue;
                    }

                    // Determine status
                    $statusValue = 'active';
                    if (!empty($status_ahli) && strtoupper($status_ahli) == 'TIDAK AKTIF') {
                        $statusValue = 'inactive';
                    }

                    // 1. CREATE USER
                    $user = User::create([
                        'nama' => strtoupper($nama),
                        'ic_number' => $formattedIc,
                        'email' => $email ?: null,
                        'password' => Hash::make('password123'),
                        'masjid_id' => $masjid_id,
                        'role' => 2, // Role 2 = Ahli Kariah
                        'status' => $statusValue,
                        'tel_number' => $notel,
                    ]);

                    // 2. CREATE AHLI KARIAH
                    $ahliKariah = AhliKariah::create([
                        'user_id' => $user->id,
                        'masjid_id' => $masjid_id,
                        'nama' => strtoupper($nama),
                        'email' => $email ?: null,
                        'ic' => $formattedIc,
                        'notel' => $notel,
                        'jantina' => strtoupper($jantina),
                        'alamat' => $alamat ?: null,
                        'status' => $statusValue,
                        'family_id' => null, // Will be updated below
                        'is_ketua' => 1, // Set as ketua
                    ]);

                    // Update family_id to match ahli_kariah id (self-referencing)
                    // $ahliKariah->family_id = $ahliKariah->id;
                    // $ahliKariah->save();

                    // 3. CREATE WARIS (if provided)
                    if (!empty($waris_nama)) {
                        // Format waris IC if provided
                        $formattedWarisIc = null;
                        if (!empty($waris_ic)) {
                            $cleanWarisIc = preg_replace('/[^0-9]/', '', $waris_ic);
                            if (strlen($cleanWarisIc) === 12) {
                                $formattedWarisIc = substr($cleanWarisIc, 0, 6) . '-' . substr($cleanWarisIc, 6, 2) . '-' . substr($cleanWarisIc, 8, 4);
                            }
                        }

                        Waris::create([
                            'ahli_id' => $ahliKariah->id,
                            'nama' => strtoupper($waris_nama),
                            'ic_number' => $formattedWarisIc,
                            'alamat' => $waris_alamat ?: null,
                            'telefon_pejabat' => null,
                            'telefon_bimbit' => $waris_telefon ?: null,
                        ]);
                    }

                    // 4. CREATE SUBSCRIPTION (if dates provided)
                    $subscriptionNote = null;
                    if (!empty($tarikh_mula)) {
                        try {
                            $startDate = Carbon::parse($tarikh_mula);
                            $endDate = $membershipService->getEndDate($startDate);

                            SubscriptionsKariah::create([
                                'user_id' => $user->id,
                                'masjid_id' => $masjid_id,
                                'start_date' => $startDate,
                                'end_date' => $endDate,
                                'status' => 'active',
                                'price' => 0,
                                'payment_id' => null,
                            ]);
                        } catch (\Exception $e) {
                            // Log subscription error but don't fail the whole row - user account was created fine
                            $friendly = $this->friendlyDbErrorMessage($e, $rowNum);
                            $subscriptionNote = "Akaun berjaya dicipta, tetapi keahlian gagal disimpan - {$friendly}";
                            $results['errors'][] = "Baris {$rowNum}: {$subscriptionNote}";
                        }
                    }

                    $results['success']++;
                    $results['users'][] = [
                        'nama' => $user->nama,
                        'ic' => $user->ic_number,
                        'email' => $user->email,
                        'subscription' => !empty($tarikh_mula) ? 'Ya' : 'Tidak',
                    ];
                    $results['rows'][] = [
                        'row' => $rowNum,
                        'nama' => $user->nama,
                        'ic' => $user->ic_number,
                        'notel' => $notel ?: '-',
                        'email' => $user->email ?: '-',
                        'status' => 'success',
                        'message' => $subscriptionNote, // null unless the subscription step had an issue
                    ];
                } catch (\Exception $e) {
                    $fail($this->friendlyDbErrorMessage($e, $rowNum));
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Import selesai!',
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk register upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ralat semasa memproses fail. Sila semak format fail dan cuba lagi.',
            ], 500);
        }
    }

    /**
     * Translate a low-level exception (usually a DB error) into a message
     * that's safe and useful to show an end user, while logging the full
     * detail for developers.
     */
    private function friendlyDbErrorMessage(\Throwable $e, int $rowNum): string
    {
        \Log::error("Bulk register row {$rowNum} failed: " . $e->getMessage());

        if ($e instanceof \Illuminate\Database\QueryException) {
            $code = $e->errorInfo[1] ?? null;

            switch ($code) {
                case 1062:
                    return 'Data pendua dikesan (rekod ini mungkin telah wujud).';
                case 1364:
                case 1048:
                    return 'Ralat sistem: lajur pangkalan data tidak lengkap. Sila hubungi admin sistem.';
                case 1452:
                    return 'Rujukan masjid tidak sah atau telah dipadam.';
                default:
                    return 'Ralat pangkalan data semasa menyimpan baris ini. Sila hubungi admin sistem.';
            }
        }

        return 'Ralat tidak dijangka semasa memproses baris ini.';
    }

    /**
     * Preview uploaded file data
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Find header row
            $headerRow = null;
            $dataStartRow = null;
            $headers = [];

            foreach ($rows as $index => $row) {
                if (isset($row[0]) && str_contains(strtoupper($row[0]), 'NAMA')) {
                    $headerRow = $index;
                    $dataStartRow = $index + 1;
                    $headers = $row;
                    break;
                }
            }

            // Get preview data (max 10 rows)
            $previewData = [];
            $rowCount = 0;
            foreach ($rows as $index => $row) {
                if ($index < $dataStartRow) continue;
                if (empty(array_filter($row))) continue;
                if ($rowCount >= 10) break;

                $previewData[] = $row;
                $rowCount++;
            }

            return response()->json([
                'success' => true,
                'headers' => $headers,
                'data' => $previewData,
                'total_rows' => $rowCount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
