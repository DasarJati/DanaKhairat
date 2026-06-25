<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\masjid as Masjid;
use App\Models\HargaKhairat;
use App\Models\UserRegister;
use App\Models\Payment;
use App\Models\Negeri;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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

        // ── 1. Basic field + file validation ────────────────────────────
        $validated = $request->validate([
            'nama'       => 'required|string',
            'ic_number'  => 'required|string|unique:user_register,ic_number',
            'email'      => 'required|email|unique:user_register,email',
            'password'   => 'required|min:6',
            'resit_file' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
        ], [
            'nama.required'      => 'Nama penuh diperlukan.',
            'ic_number.required' => 'No. Kad Pengenalan diperlukan.',
            'ic_number.unique'   => 'No. Kad Pengenalan ini telah didaftarkan dalam sistem.',
            'email.required'     => 'Alamat e-mel diperlukan.',
            'email.email'        => 'Format e-mel tidak sah.',
            'email.unique'       => 'Alamat e-mel ini telah digunakan. Sila gunakan e-mel lain.',
            'password.required'  => 'Kata laluan diperlukan.',
            'password.min'       => 'Kata laluan mestilah sekurang-kurangnya 6 aksara.',
            'resit_file.mimes'   => 'Format fail resit hendaklah JPG, JPEG, PNG atau PDF sahaja.',
            'resit_file.max'     => 'Saiz fail resit tidak boleh melebihi 5MB.',
        ]);

        

        // ── 2. Validate waris IC (format + age ≥ 18) ────────────────────
        if ($request->filled('waris_ic')) {
            $infoW = $this->extractDobAgeFromIc($request->waris_ic);
            if (!$infoW) {
                return response()->json([
                    'success' => false,
                    'errors'  => 'Nombor IC waris tidak sah. Sila masukkan nombor IC waris yang betul (12 digit).',
                ], 422);
            }
            if ($infoW['umur'] < 18) {
                return response()->json([
                    'success' => false,
                    'errors'  => 'Waris mestilah berumur 18 tahun ke atas. Umur waris: ' . $infoW['umur'] . ' tahun.',
                ], 422);
            }
        }

        // ── 3. Age & Gender ──────────────────────────────────────────────
        $jantina = strtoupper($request->jantina ?? 'LELAKI');
        $umur    = null;
        if (!empty($request->tarikh_lahir)) {
            $umur = now()->diffInYears(\Carbon\Carbon::parse($request->tarikh_lahir), true);
        }

        // ── 4. Get HargaKhairat for this masjid ─────────────────────────
        $hargaKhairat = HargaKhairat::where('masjid_id', $masjid->id)->first();
        $yuranPendaftaran = $hargaKhairat ? $hargaKhairat->yuran_pendaftaran : 0;
        $bayaranTahunan   = $hargaKhairat ? $hargaKhairat->bayaran_tahunan  : 0;
        $jumlahBayaran    = $yuranPendaftaran + $bayaranTahunan;

        // ── 5. Handle receipt upload to AWS S3 ───────────────────────────
        $resitPath = null;
        $s3Path = null; // Define this for cleanup on failure

        if ($request->hasFile('resit_file')) {
            $file = $request->file('resit_file');

            if ($file->isValid()) {
                try {
                    // Generate filename
                    $timestamp = now()->format('Ymd_His');
                    $icNumber  = preg_replace('/[^0-9]/', '', $request->ic_number ?? '000000');
                    $filename  = "resit_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();

                    // S3 path - same folder structure as before
                    $s3Path = 'register/payment_receipt/' . $filename;

                    // Upload to S3
                    $uploaded = Storage::disk('s3')->put($s3Path, file_get_contents($file), 'public');

                    if (!$uploaded) {
                        throw new \Exception('Failed to upload file to S3');
                    }

                    // Get the S3 URL
                    $resitPath = Storage::disk('s3')->url($s3Path);

                    \Log::info('Receipt uploaded to S3', [
                        'path' => $s3Path,
                        'url' => $resitPath,
                        'user_ic' => $request->ic_number,
                        'masjid_id' => $masjid->id
                    ]);
                } catch (\Exception $e) {
                    \Log::error('S3 Upload Error: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'errors'  => 'Gagal memuat naik resit pembayaran ke pelayan. Sila cuba lagi.',
                    ], 422);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'errors'  => 'Fail resit tidak sah atau rosak. Sila pilih fail yang lain.',
                ], 422);
            }
        }

        // ── 6. Create User with receipt_path included directly ───────────
        DB::beginTransaction();

        try {
            $user = UserRegister::create([
                'nama' => strtoupper($request->nama ?? ''),
                'ic_number' => $request->ic_number ?? '',
                'tarikh_lahir' => $request->tarikh_lahir ?? null,
                'umur' => $umur,
                'jantina' => $jantina,
                'bangsa' => strtoupper($request->bangsa ?? 'MELAYU'),
                'statususer' => $request->statususer ? strtoupper($request->statususer) : null,
                'alamat' => $request->alamat ?? '',
                'telefon_bimbit' => $request->telefon_bimbit ?? '',
                'email' => $request->email ?? '',
                'password' => $request->password ? bcrypt($request->password) : bcrypt('password123'),
                'masjid_id' => $masjid->id,
                'agree_terms' => $request->has('agree_terms') ? 1 : 0,
                'approval_status' => 'PENDING',
                'Paid' => 'PENDING',
                'amount' => $jumlahBayaran,
                'ahli_type' => $request->ahli_type,
                'waris_nama' => $request->waris_nama ? strtoupper($request->waris_nama) : null,
                'waris_ic' => $request->waris_ic ?? null,
                'waris_alamat' => $request->waris_alamat ?? null,
                'waris_telefon_pejabat' => $request->waris_telefon_pejabat ?? null,
                'waris_telefon_bimbit' => $request->waris_telefon_bimbit ?? null,
                'receipt_path' => $resitPath,
            ]);

            DB::commit();

            // Log success
            \Log::info('User registered with S3 receipt', [
                'user_id' => $user->id,
                'receipt_path' => $resitPath,
                'receipt_url' => $resitPath
            ]);

            // JSON Response (for AJAX / SweetAlert)
            return response()->json([
                'success' => true,
                'message' => 'Permohonan anda telah dihantar! Kami akan memeriksa dalam 1-3 hari.',
                'redirect_url' => route('success-daftar'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            // Delete uploaded file from S3 if transaction fails
            if ($resitPath && $s3Path) {
                try {
                    Storage::disk('s3')->delete($s3Path);
                    \Log::info('Deleted S3 file after transaction rollback', ['path' => $s3Path]);
                } catch (\Exception $deleteError) {
                    \Log::error('Failed to delete S3 file: ' . $deleteError->getMessage());
                }
            }

            \Log::error('Registration failed: ' . $e->getMessage());
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
