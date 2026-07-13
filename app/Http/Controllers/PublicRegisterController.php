<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\masjid as Masjid;
use App\Models\HargaKhairat;
use App\Models\UserRegister;
use App\Models\TanggungansRegister;
use App\Models\Payment;
use App\Models\Negeri;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class PublicRegisterController extends Controller
{
    /**
     * Show form with optional pre-filled data from query parameters
     */
    public function create(Request $request, $masjidName = null)
    {
        // If masjidName is provided via route
        if ($masjidName) {
            // Try to find by slug first
            $masjid = Masjid::where('slug', $masjidName)->first();

            // If not found by slug, try by name
            if (!$masjid) {
                $masjid = Masjid::where('nama', 'LIKE', $masjidName)->first();
            }

            if (!$masjid) {
                // If still not found, try to find by ID (backward compatibility)
                $masjid = Masjid::find($masjidName);
            }

            if (!$masjid) {
                // Return a friendly 404 page instead of aborting
                abort(404, 'Masjid tidak dijumpai. Sila semak pautan anda.');
            }
        }
        // If masjid_id is provided via query parameter
        elseif ($request->has('masjid_id')) {
            $masjid = Masjid::findOrFail($request->masjid_id);
            $nameSlug = $this->generateSlug($masjid->nama);
            return redirect()->route('public.daftar.create', ['masjidName' => $nameSlug]);
        } else {
            abort(404, 'Masjid tidak dijumpai');
        }

        // Get HargaKhairat for this masjid
        $hargaKhairat = HargaKhairat::where('masjid_id', $masjid->id)->first();

        // If no HargaKhairat found, create default values
        if (!$hargaKhairat) {
            $hargaKhairat = (object) [
                'yuran_pendaftaran' => 0,
                'bayaran_tahunan' => 0
            ];
        }

        // Get pre-fill data from query parameters
        $prefill = $request->only([
            'nama',
            'ic_number',
            'email',
            'telefon_bimbit',
            'tarikh_lahir',
            'jantina',
            'bangsa',
            'alamat'
        ]);

        // Generate a unique token for this registration session
        $token = $request->get('token', Str::random(32));

        return view('Ahli_Kariah.Public_Register', compact('masjid', 'prefill', 'token', 'hargaKhairat'));
    }

    /**
     * Generate a shareable link with pre-filled information
     */
    public function generateShareableLink(Request $request)
    {
        $request->validate([
            'masjid_id' => 'required|exists:masjids,id',
            'nama' => 'sometimes|string',
            'ic_number' => 'sometimes|string',
            'email' => 'sometimes|email',
            'telefon_bimbit' => 'sometimes|string',
            'tarikh_lahir' => 'sometimes|date',
            'jantina' => 'sometimes|in:Lelaki,Perempuan',
            'bangsa' => 'sometimes|string',
            'alamat' => 'sometimes|string',
        ]);

        $masjid = Masjid::find($request->masjid_id);
        $nameSlug = $this->generateSlug($masjid->nama);

        // Build the URL with masjid name instead of ID
        $params = $request->except('_token', 'masjid_id');
        $url = route('public.daftar.create', ['masjidName' => $nameSlug]) . '?' . http_build_query($params);

        return response()->json([
            'shareable_link' => $url,
            'masjid' => $masjid->nama,
            'slug' => $nameSlug
        ]);
    }

    public function store(Request $request, $slug)
    {
        // Try to find masjid by slug first
        $masjid = Masjid::where('slug', $slug)->first();

        // If not found by slug, try by name
        if (!$masjid) {
            $masjid = Masjid::where('nama', 'LIKE', $slug)->first();
        }

        if (!$masjid) {
            // If still not found, try to find by ID (backward compatibility)
            $masjid = Masjid::find($slug);
        }

        if (!$masjid) {
            return response()->json([
                'success' => false,
                'errors' => 'Masjid tidak dijumpai. Sila semak pautan anda.'
            ], 404);
        }

        // ── 1. Basic field validation ────────────────────────────────────
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'ic_number'     => 'required|string|unique:user_register,ic_number',
            'email'         => 'required|email|unique:user_register,email',
            'password'      => 'required|min:6',
            'jantina'       => 'nullable|string',
            'bangsa'        => 'nullable|string',
            'statususer'    => 'nullable|string',
            'alamat'        => 'nullable|string',
            'telefon_bimbit' => 'nullable|string',
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
            'ic_number.required' => 'No. Kad Pengenalan diperlukan.',
            'ic_number.unique'   => 'No. Kad Pengenalan ini telah didaftarkan dalam sistem.',
            'email.required'     => 'Alamat e-mel diperlukan.',
            'email.email'        => 'Format e-mel tidak sah.',
            'email.unique'       => 'Alamat e-mel ini telah digunakan. Sila gunakan e-mel lain.',
            'password.required'  => 'Kata laluan diperlukan.',
            'password.min'       => 'Kata laluan mestilah sekurang-kurangnya 6 aksara.',
            'tanggungan.*.ic.required_with' => 'Sila masukkan No. Kad Pengenalan untuk setiap tanggungan.',
            'tanggungan.*.nama.required_with' => 'Sila masukkan nama untuk setiap tanggungan.',
            'tanggungan.*.hubungan.required_with' => 'Sila pilih hubungan untuk setiap tanggungan.',
        ]);

        // ── 2. Check umur pemohon (from IC) ───────────────────────────────
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

        // ── 3. Validate tanggungan (age validation) ───────────────────────
        $tanggunganData = [];
        if (!empty($request->tanggungan)) {
            foreach ($request->tanggungan as $index => $t) {
                if (empty($t['ic'])) {
                    continue;
                }

                $infoT = $this->extractDobAgeFromIc($t['ic']);
                if (!$infoT) {
                    return response()->json([
                        'success' => false,
                        'errors' => 'Nombor IC tanggungan "' . ($t['nama'] ?? 'Tanpa Nama') . '" tidak sah.'
                    ], 422);
                }

                $ageT = $infoT['umur'];
                $hubungan = strtoupper(trim((string) $t['hubungan']));
                $isOku = isset($t['oku']) && filter_var($t['oku'], FILTER_VALIDATE_BOOLEAN);

                // Skip validation if OKU or age >= 60 (warga emas)
                if ($isOku || $ageT >= 60) {
                    continue;
                }

                // Children (ANAK) must be <= 24 years old
                if ($hubungan === 'ANAK' && $ageT > 24) {
                    return response()->json([
                        'success' => false,
                        'errors' => 'Anak bernama "' . $t['nama'] . '" tidak layak (umur ' . $ageT . ' tahun melebihi 24 tahun).'
                    ], 422);
                }

                $tanggunganData[] = array_merge($t, [
                    'tarikh_lahir' => $infoT['tarikh_lahir'],
                    'umur' => $ageT,
                    'is_oku' => $isOku,
                ]);
            }
        }

        // ── 4. Get HargaKhairat for this masjid ───────────────────────────
        $harga = HargaKhairat::where('masjid_id', $masjid->id)->first();
        if (!$harga) {
            return response()->json([
                'success' => false,
                'errors' => 'Harga khairat belum ditetapkan untuk masjid ini. Sila hubungi pentadbir.'
            ], 422);
        }

        $yuranPendaftaran = $harga->yuran_pendaftaran ?? 0;
        $bayaranTahunan   = $harga->bayaran_tahunan ?? 0;
        $jumlahBayaran    = $yuranPendaftaran + $bayaranTahunan;

        // ── 5. Create User + Tanggungan ────────────────────────────────────
        DB::beginTransaction();

        try {
            $user = UserRegister::create([
                'nama'           => strtoupper($request->nama ?? ''),
                'ic_number'      => $request->ic_number,
                'tarikh_lahir'   => $info['tarikh_lahir'],
                'umur'           => $umur,
                'jantina'        => $request->jantina ? strtoupper($request->jantina) : null,
                'bangsa'         => $request->bangsa ? strtoupper($request->bangsa) : 'MELAYU',
                'statususer'     => $request->statususer ? strtoupper($request->statususer) : null,
                'alamat'         => $request->alamat ?? '',
                'telefon_bimbit' => $request->telefon_bimbit ?? '',
                'email'          => $request->email,
                'password'       => $request->password,
                'masjid_id'      => $masjid->id,
                'amount'         => $jumlahBayaran,
                'agree_terms'    => $request->has('agree_terms') ? 1 : 0,
                'approval_status' => 'PENDING',
                'Paid'           => 'PENDING',
                'ahli_type'      => 'New',
                'status'         => 'active',
            ]);

            foreach ($tanggunganData as $t) {
                // Handle document upload for tanggungan (to AWS S3)
                $documentPath = null;

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
                        \Log::error('Tanggungan document upload error: ' . $e->getMessage());
                        // Continue without document
                    }
                }

                TanggungansRegister::create([
                    'user_register_id' => $user->id,
                    'nama'              => $t['nama'],
                    'ic_number'         => $t['ic'],
                    'tarikh_lahir'      => $t['tarikh_lahir'] ?? null,
                    'hubungan'          => $t['hubungan'],
                    'jantina'           => $t['jantina'] ?? null,
                    'document_path'     => $documentPath,
                    'oku'               => isset($t['oku']) ? filter_var($t['oku'], FILTER_VALIDATE_BOOLEAN) : false,
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

            \Log::info('Public user registered successfully', [
                'user_id' => $user->id,
                'masjid_id' => $masjid->id,
                'tanggungan_count' => count($tanggunganData),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan anda telah dihantar! Kami akan mengambil beberapa hari untuk membuat pengesahan.',
                'redirect_url' => route('success-daftar'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Public registration failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'errors' => 'Pendaftaran gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extract date of birth and age from Malaysian IC number.
     */
    private function extractDobAgeFromIc($ic)
    {
        $ic = preg_replace('/[^0-9]/', '', $ic);

        if (strlen($ic) !== 12) {
            return null;
        }

        $year  = (int) substr($ic, 0, 2);
        $month = (int) substr($ic, 2, 2);
        $day   = (int) substr($ic, 4, 2);

        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
            return null;
        }

        $year = $year < 30 ? 2000 + $year : 1900 + $year;

        $tarikh_lahir = sprintf('%04d-%02d-%02d', $year, $month, $day);

        return [
            'tarikh_lahir' => $tarikh_lahir,
            'umur'         => Carbon::parse($tarikh_lahir)->age,
        ];
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }

    /**
     * Show success page after registration
     */
    public function success(Request $request)
    {
        // Get data from session (set during store method)
        $data = [
            'nama' => session('nama', 'Pemohon'),
            'email' => session('email', ''),
            'ic_number' => session('ic_number', ''),
            'masjid' => session('masjid', ''),
            'yuran_pendaftaran' => session('yuran_pendaftaran', 0),
            'bayaran_tahunan' => session('bayaran_tahunan', 0),
            'jumlah_bayaran' => session('jumlah_bayaran', 0),
            'payment_date' => session('payment_date', now()->format('d/m/Y')),
            'payment_time' => session('payment_time', now()->format('h:i A'))
        ];

        return view('Ahli_Kariah.Success_Register', $data);
    }
}
