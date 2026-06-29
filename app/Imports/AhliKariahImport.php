<?php
// app/Imports/AhliKariahImport.php

namespace App\Imports;

use App\Models\AhliKariah;
use App\Models\User;
use App\Models\Waris;
use App\Models\SubscriptionsKariah;
use App\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;

class AhliKariahImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable, SkipsErrors;

    protected $masjidId;
    protected $results = [
        'success' => 0,
        'failed' => 0,
        'errors' => [],
        'users' => []
    ];

    public function __construct($masjidId)
    {
        $this->masjidId = $masjidId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::beginTransaction();
            try {
                $this->processRow($row);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->results['failed']++;
                $this->results['errors'][] = [
                    'row' => $row,
                    'error' => $e->getMessage()
                ];
                Log::error('Bulk import error: ' . $e->getMessage(), ['row' => $row->toArray()]);
            }
        }
    }

    protected function processRow($row)
    {
        // Format IC number
        $icNumber = $this->formatIC($row['ic_number']);

        // Check if user already exists
        $existingUser = User::where('ic_number', $icNumber)->first();
        if ($existingUser) {
            throw new \Exception("IC Number '{$icNumber}' sudah wujud dalam sistem.");
        }

        // Generate unique email if not provided
        $email = $row['email'] ?? $this->generateEmail($row['nama'], $icNumber);

        // Create User
        $user = User::create([
            'nama' => strtoupper($row['nama']),
            'ic_number' => $icNumber,
            'email' => $email,
            'password' => Hash::make($row['password'] ?? 'password123'),
            'masjid_id' => $this->masjidId,
            'role' => 2, // Ahli Kariah role
            'status' => 'active',
            'tel_number' => $row['telefon_bimbit'] ?? null,
        ]);

        // Create Ahli Kariah
        $ahliKariah = AhliKariah::create([
            'user_id' => $user->id,
            'masjid_id' => $this->masjidId,
            'nama' => strtoupper($row['nama']),
            'email' => $email,
            'ic' => $icNumber,
            'notel' => $row['telefon_bimbit'] ?? null,
            'jantina' => strtoupper($row['jantina'] ?? 'LELAKI'),
            'alamat' => $row['alamat'] ?? null,
            'status' => 'active',
            'family_id' => null,
            'is_ketua' => 1,
        ]);

        // Generate family ID (same as ahli_id for ketua)
        $ahliKariah->family_id = $ahliKariah->id;
        $ahliKariah->save();

        // Create Waris (if provided)
        if (!empty($row['waris_nama'])) {
            Waris::create([
                'ahli_id' => $ahliKariah->id,
                'nama' => strtoupper($row['waris_nama']),
                'ic_number' => $this->formatIC($row['waris_ic'] ?? null),
                'alamat' => $row['waris_alamat'] ?? null,
                'telefon_pejabat' => $row['waris_telefon_pejabat'] ?? null,
                'telefon_bimbit' => $row['waris_telefon_bimbit'] ?? null,
            ]);
        }

        // Create Subscription (if payment info provided)
        if (!empty($row['payment_date']) && !empty($row['payment_amount'])) {
            $this->createSubscription($user, $ahliKariah, $row);
        }

        $this->results['success']++;
        $this->results['users'][] = [
            'nama' => $row['nama'],
            'ic' => $icNumber,
            'email' => $email,
        ];
    }

    protected function createSubscription($user, $ahliKariah, $row)
    {
        // Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'masjid_id' => $this->masjidId,
            'amount' => $row['payment_amount'],
            'payment_date' => $row['payment_date'],
            'payment_method' => $row['payment_method'] ?? 'online',
            'status' => 'completed',
            'reference_no' => 'BULK-' . Str::random(8),
        ]);

        // Calculate dates
        $startDate = $row['start_date'] ?? now();
        $duration = $row['duration_months'] ?? 12;
        $endDate = \Carbon\Carbon::parse($startDate)->addMonths($duration);

        // Create subscription
        SubscriptionsKariah::create([
            'user_id' => $user->id,
            'masjid_id' => $this->masjidId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'price' => $row['payment_amount'],
            'payment_id' => $payment->id,
        ]);
    }

    protected function formatIC($ic)
    {
        if (empty($ic)) return null;
        
        // Remove all non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $ic);
        
        // Format as YYMMDD-XX-XXXX
        if (strlen($cleaned) === 12) {
            return substr($cleaned, 0, 6) . '-' . substr($cleaned, 6, 2) . '-' . substr($cleaned, 8, 4);
        }
        
        return $ic;
    }

    protected function generateEmail($name, $ic)
    {
        $base = Str::slug($name, '.');
        $suffix = substr($ic, -4);
        return strtolower($base . '.' . $suffix . '@example.com');
    }

    public function rules(): array
    {
        return [
            '*.nama' => 'required|string|max:255',
            '*.ic_number' => 'required|string|max:14',
            '*.jantina' => 'nullable|in:LELAKI,PEREMPUAN',
            '*.telefon_bimbit' => 'nullable|string|max:15',
            '*.email' => 'nullable|email|max:255',
            '*.alamat' => 'nullable|string',
            '*.waris_nama' => 'nullable|string|max:255',
            '*.waris_ic' => 'nullable|string|max:14',
            '*.waris_telefon_bimbit' => 'nullable|string|max:15',
            '*.payment_amount' => 'nullable|numeric|min:0',
            '*.payment_date' => 'nullable|date',
        ];
    }

    public function getResults()
    {
        return $this->results;
    }
}