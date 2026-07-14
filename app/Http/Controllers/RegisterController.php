<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Aws\S3\S3Client;

use App\Models\User;
use App\Models\Tanggungan;
use App\Models\Waris;
use App\Models\Negeri;
use App\Models\Bank;
use App\Models\Masjid;
use App\Models\HargaKhairat;
use App\Models\TanggungansRegister;
use App\Models\Payment;
use App\Models\UserRegister;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use App\Mail\RegistrationConfirmationMail;


class RegisterController extends Controller
{
    public function showForm(Request $request)
    {
        $negeri = Negeri::orderBy('nama')->get();
        return view('user.register', compact('negeri'));
    }



    public function store(Request $request)
    {
        Log::info('=== REGISTRATION REQUEST ===');
        Log::info('All request data:', $request->all());
        Log::info('Alamat from request:', ['alamat' => $request->alamat]);
        Log::info('Alamat from input:', ['alamat' => $request->input('alamat')]);
        // ✅ BASIC VALIDATION
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'ic_number'     => 'required|string|unique:user_register,ic_number',
            'email'         => 'required|email|unique:user_register,email',
            'password'      => 'required|min:6',
            'masjid_id'     => 'required|exists:masjids,id',
            'masjid_name'   => 'required|string',
            'jantina'       => 'nullable|string',
            'bangsa'        => 'nullable|string',
            'statususer'    => 'nullable|string',
            'alamat'        => 'nullable|string',
            'telefon_bimbit' => 'nullable|string',
            'resit_file'    => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
            // Tanggungan validation
            'tanggungan'    => 'nullable|array',
            'tanggungan.*.nama' => 'required_with:tanggungan|string|max:255',
            'tanggungan.*.ic' => 'required_with:tanggungan|string',
            'tanggungan.*.tarikh_lahir' => 'nullable|date',
            'tanggungan.*.hubungan' => 'required_with:tanggungan|string|in:ISTERI,SUAMI,ANAK,IBU,AYAH,MERTUA,LAIN',
            'tanggungan.*.jantina' => 'nullable|string|in:LELAKI,PEREMPUAN,LAIN',
            'tanggungan.*.oku' => 'nullable|boolean',
            'tanggungan.*.dokumen' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
        ], [
            'ic_number.unique' => 'No. Kad Pengenalan ini telah didaftarkan dalam sistem.',
            'email.unique'     => 'Alamat e-mel ini telah digunakan. Sila gunakan e-mel lain.',
            'resit_file.max'   => 'Saiz fail resit tidak boleh melebihi 5MB.',
            'resit_file.mimes' => 'Format fail resit hendaklah JPG, JPEG, PNG atau PDF sahaja.',
            'tanggungan.*.ic.required_with' => 'Sila masukkan No. Kad Pengenalan untuk setiap tanggungan.',
            'tanggungan.*.nama.required_with' => 'Sila masukkan nama untuk setiap tanggungan.',
            'tanggungan.*.hubungan.required_with' => 'Sila pilih hubungan untuk setiap tanggungan.',
        ]);

        Log::info('Validated data:', $validated);
        Log::info('Alamat after validation:', ['alamat' => $validated['alamat'] ?? 'NOT FOUND']);

        // ✅ CHECK UMUR PEMOHON (FROM IC)
        $info = $this->extractDobAgeFromIc($request->ic_number);
        if (!$info) {
            return response()->json([
                'success' => false,
                'errors' => 'Nombor IC tidak sah. Sila masukkan nombor IC yang betul (12 digit).'
            ], 422);
        }

        $umur = $info['umur'];

        if ($umur < 24) {
            return response()->json([
                'success' => false,
                'errors' => 'Pendaftaran hanya dibenarkan untuk umur 24 tahun ke atas. Umur anda: ' . $umur . ' tahun.'
            ], 422);
        }

        // ✅ VALIDATE TANGGUNGAN (Age validation)
        $tanggunganData = [];
        if (!empty($request->tanggungan)) {
            foreach ($request->tanggungan as $index => $t) {
                // Skip if no IC (optional)
                if (empty($t['ic'])) {
                    continue;
                }

                // Extract age from IC
                $infoT = $this->extractDobAgeFromIc($t['ic']);
                if (!$infoT) {
                    return response()->json([
                        'success' => false,
                        'errors' => 'Nombor IC tanggungan "' . ($t['nama'] ?? 'Tanpa Nama') . '" tidak sah.'
                    ], 422);
                }

                $ageT = $infoT['umur'];
                $hubungan = strtoupper(trim((string)$t['hubungan']));
                $isOku = isset($t['oku']) && filter_var($t['oku'], FILTER_VALIDATE_BOOLEAN);

                // Skip validation if OKU or age >= 60 (warga emas)
                if ($isOku || $ageT >= 60) {
                    continue;
                }

                // Validate children (ANAK) must be <= 24 years old
                if ($hubungan === 'ANAK' && $ageT > 24) {
                    return response()->json([
                        'success' => false,
                        'errors' => 'Anak bernama "' . $t['nama'] . '" tidak layak (umur ' . $ageT . ' tahun melebihi 24 tahun).'
                    ], 422);
                }

                // Store for later
                $tanggunganData[] = array_merge($t, [
                    'tarikh_lahir' => $infoT['tarikh_lahir'],
                    'umur' => $ageT,
                    'is_oku' => $isOku,
                ]);
            }
        }

        // ✅ Get HargaKhairat
        $harga = HargaKhairat::where('masjid_id', $request->masjid_id)->first();
        if (!$harga) {
            return response()->json([
                'success' => false,
                'errors' => 'Harga khairat belum ditetapkan untuk masjid ini. Sila hubungi pentadbir.'
            ], 422);
        }

        $yuranPendaftaran = $harga->yuran_pendaftaran ?? 0;
        $bayaranTahunan = $harga->bayaran_tahunan ?? 0;
        $jumlahBayaran = $yuranPendaftaran + $bayaranTahunan;

        // ✅ HANDLE RESIT UPLOAD TO AWS S3
        $resitPath = null;
        $s3Path = null;

        if ($request->hasFile('resit_file')) {
            $file = $request->file('resit_file');

            if ($file->isValid()) {
                try {
                    $timestamp = now()->format('Ymd_His');
                    $icNumber = preg_replace('/[^0-9]/', '', $request->ic_number);
                    $filename = "resit_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();

                    $s3Path = 'register/payment_receipt/' . $filename;

                    $uploaded = Storage::disk('s3')->put($s3Path, file_get_contents($file), 'public');

                    if (!$uploaded) {
                        throw new \Exception('Failed to upload file to S3');
                    }

                    $resitPath = Storage::disk('s3')->url($s3Path);

                    Log::info('Receipt uploaded to S3', [
                        'path' => $s3Path,
                        'url' => $resitPath,
                        'user_ic' => $request->ic_number
                    ]);
                } catch (\Exception $e) {
                    Log::error('S3 Upload Error: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'errors' => 'Gagal memuat naik resit pembayaran. Sila cuba lagi.'
                    ], 422);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => 'Fail resit tidak sah atau rosak.'
                ], 422);
            }
        }

        DB::beginTransaction();

        try {
            // ✅ CREATE USER
            $user = UserRegister::create([
                'nama'           => $request->nama,
                'ic_number'      => $request->ic_number,
                'tarikh_lahir'   => $info['tarikh_lahir'],
                'umur'           => $umur,
                'jantina'        => $request->jantina,
                'bangsa'         => $request->bangsa,
                'statususer'     => $request->statususer,
                'alamat'         => $request->alamat ?? '',
                'telefon_bimbit' => $request->telefon_bimbit,
                'email'          => $request->email,
                'password'       => $request->password,
                'masjid_id'      => $request->masjid_id,
                'amount'         => $jumlahBayaran,
                'agree_terms'    => $request->agree_terms ? 1 : 0,
                'ahli_type'      => 'New',
                'status'         => 'active',
                'receipt_path'   => $resitPath,
                // Waris columns removed
            ]);

            // ✅ CREATE TANGGUNGAN
            foreach ($tanggunganData as $t) {
                // Handle document upload for tanggungan
                $documentPath = null;
                $docS3Path = null;

                if (isset($t['dokumen']) && $t['dokumen'] instanceof \Illuminate\Http\UploadedFile) {
                    try {
                        $docFile = $t['dokumen'];
                        $timestamp = now()->format('Ymd_His');
                        $icNumber = preg_replace('/[^0-9]/', '', $t['ic']);
                        $docFilename = "tanggungan_{$icNumber}_{$timestamp}." . $docFile->getClientOriginalExtension();

                        $docS3Path = 'register/tanggungan_documents/' . $docFilename;

                        $uploaded = Storage::disk('s3')->put($docS3Path, file_get_contents($docFile), 'public');

                        if ($uploaded) {
                            $documentPath = Storage::disk('s3')->url($docS3Path);
                        }
                    } catch (\Exception $e) {
                        Log::error('Tanggungan document upload error: ' . $e->getMessage());
                        // Continue without document
                    }
                }

                TanggungansRegister::create([
                    'user_register_id' => $user->id,
                    'nama'             => $t['nama'],
                    'ic_number'        => $t['ic'],
                    'tarikh_lahir'     => $t['tarikh_lahir'] ?? null,
                    'hubungan'         => $t['hubungan'],
                    'jantina'          => $t['jantina'] ?? null,
                    'document_path'    => $documentPath,
                    'oku'              => isset($t['oku']) ? filter_var($t['oku'], FILTER_VALIDATE_BOOLEAN) : false,
                ]);
            }

            DB::commit();

            try {
                $masjidName = $request->masjid_name ?? $request->masjid_nama ?? 'Masjid';

                Log::info('Attempting to send email to: ' . $user->email);

                Mail::send('emails.registration_confirmation', [
                    'user' => $user,
                    'masjidName' => $masjidName,
                    'tanggunganCount' => count($tanggunganData),
                    'totalAmount' => $jumlahBayaran
                ], function ($message) use ($user, $masjidName) {
                    $message->to($user->email)
                            ->subject('Pengesahan Pendaftaran Keahlian Khairat - ' . $masjidName);
                });

                // Mail::to($user->email)->send(
                //     new RegistrationConfirmationMail(
                //         $user,
                //         $masjidName,
                //         count($tanggunganData),
                //         $jumlahBayaran
                //     )
                // );

                Log::info('Registration confirmation email sent to: ' . $user->email);
            } catch (\Exception $mailError) {
                // Log error but don't fail the registration
                Log::error('Failed to send registration email: ' . $mailError->getMessage());
                Log::error('Email error trace: ' . $mailError->getTraceAsString());
                // Continue - we don't want to fail registration if email fails
            }

            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'tanggungan_count' => count($tanggunganData),
                'receipt_path' => $resitPath
                
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan anda telah dihantar! Kami akan mengambil beberapa hari untuk membuat pengesahan.',
                'redirect_url' => route('success-daftar'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            // Delete uploaded files if transaction fails
            if ($resitPath && $s3Path) {
                try {
                    Storage::disk('s3')->delete($s3Path);
                } catch (\Exception $deleteError) {
                    Log::error('Failed to delete S3 file: ' . $deleteError->getMessage());
                }
            }

            Log::error('Registration failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'errors' => 'Pendaftaran gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX: Check if IC already exists in user_register table.
     * Used by the registration form before allowing user to proceed to next step.
     */
    public function checkIc(Request $request)
    {
        $ic = preg_replace('/[^0-9]/', '', $request->query('ic', ''));
        $exists = \App\Models\UserRegister::where(
            DB::raw("REPLACE(ic_number, '-', '')"),
            $ic
        )->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Display Terma & Syarat (policy) for a specific masjid.
     * Opened in a new tab via the "Klik Untuk Baca Terma & Syarat" link.
     */
    public function showPolicy($masjidId)
    {
        $masjid = Masjid::find($masjidId);

        $policy = \App\Models\PolicyHeader::with('sections')
            ->where('masjid_id', $masjidId)
            ->first();

        return view('user.policy_khairat', [
            'masjid' => $masjid,
            'policy' => $policy,
        ]);
    }

    public function getHargaKhairat($masjidId)
    {
        $harga = HargaKhairat::where('masjid_id', $masjidId)->first();

        if (!$harga) {
            return response()->json(null, 404);
        }

        return response()->json([
            'bayaran_tahunan'     => $harga->bayaran_tahunan,
            'yuran_pendaftaran'  => $harga->yuran_pendaftaran,
        ]);
    }
    public function getBankByMasjid($masjidId)
    {
        $bank = Bank::where('masjid_id', $masjidId)->first();

        if (!$bank) {
            return response()->json(null, 404);
        }

        return response()->json([
            'qr_path'   => $bank->qr_path,
            'nama_bank' => $bank->nama_bank,
            'nama_akaun' => $bank->nama_akaun,
            'no_akaun'  => $bank->no_akaun,
        ]);
    }

    private function extractDobAgeFromIc($ic)
    {
        // Remove ANY non-digit
        $ic = preg_replace('/[^0-9]/', '', $ic);

        // Skip if empty or not 12 digits
        if (strlen($ic) !== 12) {
            return null;
        }

        $dob = substr($ic, 0, 6);

        $year  = (int) substr($dob, 0, 2);
        $month = (int) substr($dob, 2, 2);
        $day   = (int) substr($dob, 4, 2);

        // Validate date parts
        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
            return null;
        }

        $year = $year < 30 ? 2000 + $year : 1900 + $year;

        $tarikh_lahir = "$year-$month-$day";

        return [
            'tarikh_lahir' => $tarikh_lahir,
            'umur' => \Carbon\Carbon::parse($tarikh_lahir)->age
        ];
    }
}