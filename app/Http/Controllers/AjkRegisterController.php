<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\AjkRegister;
use App\Models\AjkDocs;
use App\Models\AjkPakej;
use App\Models\Ajks;
use App\Models\Negeri;
use App\Models\masjid;
use App\Models\Poskod;
use App\Models\User;



class AjkRegisterController extends Controller
{
    public function showForm()
    {
        $negeri = Negeri::all();
        $kariahs = \App\Models\Kariah::orderBy('nama', 'asc')->get();
        return view('register.form', compact('negeri', 'kariahs'));
    }


    public function completeRegistration(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                // Tab 1: Maklumat Diri & Masjid
                'type' => 'required|string|max:20',
                'negeri_id' => 'required|exists:negeri,id',
                'nama_masjid' => 'required|string|max:150',
                'alamat' => 'required|string|max:255',
                'email' => 'required|email|max:150|unique:users,email', // Changed from ajk_register to users
                'password' => 'required|min:6|confirmed',

                // Tab 2: Dokumen
                'salinan_ic' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'sijil_pendaftaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'slip_akaun' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',

                // Maklumat Pendaftar
                'nama_pendaftar' => 'required|string|max:255',
                'notel' => 'required|string|max:30',
                'ic' => 'required|string|max:30',
            ], [
                'email.unique' => 'Alamat e-mel ini sudah didaftarkan. Sila gunakan e-mel lain.',
                'password.confirmed' => 'Pengesahan kata laluan tidak sepadan.',
                '*.file' => 'Sila muat naik fail yang sah.',
                '*.mimes' => 'Hanya fail PDF, JPG, JPEG, PNG dibenarkan.',
                '*.max' => 'Saiz fail tidak boleh melebihi 2MB.',
                'salinan_ic.required' => 'Sila muat naik salinan IC.',
                'sijil_pendaftaran.required' => 'Sila muat naik sijil pendaftaran.',
                'slip_akaun.required' => 'Sila muat naik slip akaun.',
            ]);

            if ($validator->fails()) {
                $errorMessage = '';
                foreach ($validator->errors()->all() as $error) {
                    $errorMessage .= '• ' . $error . "\n"; // Changed from '\n' to "\n"
                }

                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMessage);
            }

            \DB::beginTransaction();

            // ===============================
            // GET BANDAR
            // ===============================
            $namaBandar = strtoupper(trim($request->bandar_manual ?? ''));

            if (!$namaBandar && !empty($request->bandar_select)) {
                $namaBandar = strtoupper(trim($request->bandar_select));
            }

            if (!$namaBandar) {
                return back()->withInput()
                    ->with('error', 'Sila pilih atau masukkan bandar.');
            }

            // ===============================
            // CHECK/ CREATE POSKOD
            // ===============================
            $poskodRecord = Poskod::firstOrCreate(
                [
                    'poskod_num' => $request->poskod,
                    'nama'       => $namaBandar,
                    'negeri_id'  => $request->negeri_id
                ]
            );

            if (!$poskodRecord) {
                throw new \Exception('Gagal membuat atau mencari rekod poskod.');
            }

            $poskodId = $poskodRecord->id;

            // ===============================
            // AUTO DETECT / CREATE KARIAH
            // ===============================
            if (!$request->kariah_id) {
                $namaKariah = strtoupper(trim($request->kariah ?? ''));

                if (empty($namaKariah)) {
                    throw new \Exception('Nama kariah diperlukan.');
                }

                $existing = \App\Models\Kariah::where('poskod_id', $poskodId)
                    ->where('nama', $namaKariah)
                    ->first();

                if ($existing) {
                    $kariahId = $existing->id;
                } else {
                    $newKariah = \App\Models\Kariah::create([
                        'poskod_id' => $poskodId,
                        'bandar'    => $namaBandar,
                        'nama'      => $namaKariah,
                    ]);

                    if (!$newKariah) {
                        throw new \Exception('Gagal membuat rekod kariah baru.');
                    }

                    $kariahId = $newKariah->id;
                }
            } else {
                $kariahId = $request->kariah_id;
            }

            // Create Masjid first (without file paths initially)
            $masjid = masjid::create([ // Fixed: masjid to Masjid (capital M)
                'negeri_id' => $request->negeri_id,
                'poskod_id' => $poskodId,
                'kariah_id' => $kariahId,
                'nama'      => $request->nama_masjid,
                'type'      => $request->type,
                'alamat'    => $request->alamat,
                'bandar'    => $namaBandar, // Fixed: was $request->namaBandar
                'status'    => 'active',
                // Remove file fields from here - will update after storing files
            ]);

            if (!$masjid || !$masjid->id) {
                throw new \Exception('Gagal mencipta rekod masjid.');
            }

            // Store files using the combined function
            $salinanIcPath = $this->storeFile($request->file('salinan_ic'), 'masjid', $masjid->id);
            $sijilPath = $this->storeFile($request->file('sijil_pendaftaran'), 'masjid', $masjid->id);
            $slipAkaunPath = $this->storeFile($request->file('slip_akaun'), 'masjid', $masjid->id);

            // Update masjid with file paths
            $masjid->update([
                'salinan_ic' => $salinanIcPath,
                'sijil_pendaftaran' => $sijilPath,
                'slip_akaun' => $slipAkaunPath,
            ]);

            // Create User
            $user = User::create([
                'nama'        => $request->nama_pendaftar,
                'masjid_id'   => $masjid->id,
                'ic_number'   => $request->ic,
                'email'       => $request->email,
                'tel_number'       => $request->notel,
                'password'    => Hash::make($request->password),
                'role'        => '1',
                'status'      => 'active',
            ]);

            if (!$user || !$user->id) {
                throw new \Exception('Gagal mencipta rekod pengguna.');
            }

            \DB::commit();

            // Log the registration
            \Log::info('New Masjid Registration', [
                'masjid_id' => $masjid->id,
                'user_id' => $user->id,
                'masjid' => $masjid->nama,
                'email' => $user->email,
            ]);

            // Auto login user
            Auth::login($user);

            return redirect('/login')->with(
                'success',
                'Pendaftaran berjaya dihantar! Pendaftaran akan disahkan dalam masa 24 Jam. Email akan dihantar untuk bukti pengesahan kami. Terima kasih'
            );
        } catch (\Exception $e) {
            \DB::rollBack();

            // Log error with more details
            \Log::error('Registration Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $request->email ?? 'unknown',
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ralat sistem semasa pendaftaran: ' . $e->getMessage());
        }
    }

    /**
     * Store file and return path
     * 
     * @param \Illuminate\Http\UploadedFile|null $file
     * @param string $type (masjid/user/ajk)
     * @param int $id
     * @return string|null
     */
    private function storeFile($file, $type, $id)
    {
        if (!$file) return null;

        // Validate file
        if (!$file->isValid()) {
            throw new \Exception('Fail tidak sah: ' . $file->getClientOriginalName());
        }

        // Create folder structure: uploads/{type}_docs/{id}/
        $folderName = $type . '_docs';
        $path = public_path("uploads/{$folderName}/" . $id);

        // Create folder if doesn't exist
        if (!file_exists($path)) {
            mkdir($path, 0755, true); // Changed to 0755 for better security
        }

        // Generate unique filename with original extension
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;

        // Move file to destination
        $file->move($path, $filename);

        // Return relative path for database storage
        return "uploads/{$folderName}/{$id}/{$filename}";
    }


    public function getKariahsByNegeri($negeri)
    {
        // Cari kariah yang negerinya sama dengan yang dipilih user
        $kariahs = \App\Models\Kariah::where('negeri', $negeri)
            ->orderBy('nama', 'asc')
            ->get(['nama']); // Ambil column nama sahaja

        return response()->json($kariahs);
    }

    public function getPoskodsByNegeri($negeri)
    {
        $negeriRecord = \App\Models\Negeri::where('nama', $negeri)->first();

        if (!$negeriRecord) return response()->json([]);

        return response()->json(
            \App\Models\Poskod::where('negeri_id', $negeriRecord->id)
                ->select('poskod_num')
                ->distinct()
                ->orderBy('poskod_num')
                ->get()
        );
    }
    public function getBandarByPoskod($negeri, $poskod)
    {
        $negeriRecord = \App\Models\Negeri::where('nama', $negeri)->first();

        if (!$negeriRecord) return response()->json([]);

        return response()->json(
            \App\Models\Poskod::where('negeri_id', $negeriRecord->id)
                ->where('poskod_num', $poskod)
                ->select('nama')
                ->distinct()
                ->orderBy('nama')
                ->get()
        );
    }




    public function checkEmail(Request $request)
    {
        $email = $request->email;
        $exists = AjkRegister::where('email', $email)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'E-mel sudah didaftarkan' : 'E-mel tersedia'
        ]);
    }

    /**
     * Get registration status
     */
    public function getStatus($email)
    {
        $registration = AjkRegister::with(['documents', 'package'])
            ->where('email', $email)
            ->first();

        if (!$registration) {
            return response()->json(['error' => 'Pendaftaran tidak dijumpai'], 404);
        }

        return response()->json([
            'status' => $registration->status,
            'status_text' => $this->getStatusText($registration->status),
            'masjid' => $registration->nama_masjid,
            'created_at' => $registration->created_at->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Get status text
     */
    private function getStatusText($status)
    {
        $statuses = [
            0 => 'Menunggu Kelulusan',
            1 => 'Telah Diluluskan',
            2 => 'Ditolak'
        ];

        return $statuses[$status] ?? 'Tidak Diketahui';
    }

    public function showCompleteForm()
    {
        return view('ajk.complete-profile');
    }
}
