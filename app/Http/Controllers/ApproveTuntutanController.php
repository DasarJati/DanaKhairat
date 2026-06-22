<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TuntutanKhairat;
use App\Models\User;
use App\Models\Tanggungan;
use App\Models\PembayaranKhairat;
use App\Models\ActivityLog;
use App\Models\AhliKariah;
use App\Models\HargaKhairat;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\KhairatItems;

class ApproveTuntutanController extends Controller
{
    public function index()
    {
        try {
            // Check if user is logged in
            $user = auth()->user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Sila log masuk terlebih dahulu.');
            }

            // Get masjid_id from user
            $masjidId = $user->masjid_id;

            if (!$masjidId) {
                return redirect()->route('dashboard')->with('error', 'Tiada masjid dikaitkan dengan akaun anda. Sila hubungi pentadbir.');
            }

            // Query tuntutan for this masjid with relationships
            $tuntutan = TuntutanKhairat::with(['tanggungan', 'user', 'pembayaran'])
                ->where('masjid_id', $masjidId)
                ->orderBy('created_at', 'desc')
                ->get();

            // Add computed attributes to each tuntutan
            foreach ($tuntutan as $item) {
                // Set nama_ahli based on type
                if ($item->type === 'AHLI' && $item->ahli) {
                    $item->nama_ahli = $item->ahli->nama;
                    $item->ic_ahli = $item->ahli->ic;
                } elseif ($item->type === 'TANGGUNGAN' && $item->tanggungan) {
                    $item->nama_ahli = $item->tanggungan->nama;
                    $item->ic_ahli = $item->tanggungan->ic_number;
                } else {
                    // Fallback for manual entries or missing relations
                    $item->nama_ahli = $item->nama_ahli ?? 'Nama tidak dijumpai';
                    $item->ic_ahli = $item->ic_ahli ?? 'IC tidak dijumpai';
                }
            }

            // Calculate statistics
            $totalCount = TuntutanKhairat::where('masjid_id', auth()->user()->masjid_id)->count();
            $pendingCount = TuntutanKhairat::where('masjid_id', auth()->user()->masjid_id)->where('status', 'PENDING')->count();
            $approvedCount = TuntutanKhairat::where('masjid_id', auth()->user()->masjid_id)->where('status', 'SUCCESS')->count();
            $rejectedCount = TuntutanKhairat::where('masjid_id', auth()->user()->masjid_id)->where('status', 'REJECTED')->count();
            $progressCount = TuntutanKhairat::where('masjid_id', auth()->user()->masjid_id)->where('status', 'PROCESSING')->count();

            // Get Ahli Khairat list for dropdown
            $ahliKariahList = AhliKariah::where('masjid_id', $masjidId)
                ->where('status', 'active') // Only get active members
                ->orderBy('nama')
                ->get();

            return view('khairat.khairatquest', compact(
                'tuntutan',
                'totalCount',
                'pendingCount',
                'approvedCount',
                'rejectedCount',
                'progressCount',
                'ahliKariahList'
            ));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in Tuntutan Khairat index: ' . $e->getMessage());

            // Return view with error message
            return view('khairat.khairatquest')->withErrors([
                'error' => 'Terjadi ralat semasa memuatkan data. Sila cuba sebentar lagi.'
            ])->with([
                'tuntutan' => collect([]),
                'totalCount' => 0,
                'pendingCount' => 0,
                'approvedCount' => 0,
                'rejectedCount' => 0,
                'ahliKariahList' => collect([])
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate request
            $validated = $request->validate([
                'nama_ahli' => 'required|string|max:255',
                'ic_ahli' => 'required|string|max:20',
                'jumlah' => 'required|numeric|min:0',
                'tarikh_meninggal' => 'nullable|date',
                'masjid_id' => 'required|exists:masjids,id',
                'user_id' => 'required|exists:users,id'
            ]);

            // Create tuntutan khairat record
            $tuntutan = TuntutanKhairat::create([
                'nama_ahli' => $validated['nama_ahli'],
                'ic_ahli' => $validated['ic_ahli'],
                'tarikh_meninggal' => $validated['tarikh_meninggal'] ?? null,
                'masjid_id' => $validated['masjid_id'],
                'user_id' => $validated['user_id'],
                'status' => 'pending'
            ]);

            // Create payment record
            PembayaranKhairat::create([
                'tuntutan_id' => $tuntutan->id,
                'jumlah' => $validated['jumlah'],
                'status' => 'pending'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rekod kematian berjaya ditambah',
                'data' => $tuntutan
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah rekod: ' . $e->getMessage()
            ], 500);
        }
    }


    public function showForm($id)
    {
        $tuntutan = TuntutanKhairat::with([
            'user',
            'tanggungan',
            'dokumen',
        ])->findOrFail($id);

        return view('khairat.approveform', compact('tuntutan'));
    }


    public function update(Request $request, $id)
    {
        $tuntutan = TuntutanKhairat::findOrFail($id);

        if ($request->action === 'approve') {

            // 1. Update status tuntutan
            $tuntutan->update(['status' => 'APPROVED']);

            // 2. Create rekod pembayaran (anti duplicate)
            PembayaranKhairat::firstOrCreate(
                ['tuntutan_id' => $tuntutan->id], // UNIQUE CHECK
                [
                    'tarikh_kelulusan' => now(),
                    'ic_penerima'      => $tuntutan->ic_ahli,
                    'nama_penerima'    => $tuntutan->nama_ahli,
                    'status'           => 'PROCESSING',
                ]
            );
        } else {

            $tuntutan->update(['status' => 'REJECTED']);
        }

        return redirect()
            ->route('butiran.ahli', $tuntutan->user_id)
            ->with('success', 'Tuntutan berjaya dikemaskini.');
    }

    public function approve($id)
    {

        try {
            $tuntutan = TuntutanKhairat::findOrFail($id);

            $user = User::where('id', $tuntutan->user_id)->first();

            // Only allow approve if still pending
            if ($tuntutan->status !== 'PENDING') {
                return back()->with('error', 'Status tidak sah untuk diluluskan.');
            }

            // 1️⃣ Update tuntutan status
            $tuntutan->update([
                'status' => 'APPROVED',
                'diluluskan_oleh' => auth()->id(),
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'entity_type' => 'Laporan Kematian',
                'description' => 'Meluluskan permohonan khairat bagi ' . $tuntutan->nama_pewaris
            ]);

            // $user->update([
            //     'status' => 'inactive',
            // ]);




            // 2️⃣ Create pembayaran record
            // PembayaranKhairat::create([
            //     'tuntutan_id'     => $tuntutan->id,
            //     'tarikh_kelulusan' => now(),
            //     'jumlah_bayar'    => 0, // or config value
            //     'status'          => 'PROCESSING', 
            // ]);



            return back()->with('success', 'Permohonan berjaya diluluskan.');
        } catch (\Exception $e) {

            return back()->with('error', 'Ralat berlaku semasa proses kelulusan.');
        }
    }


    public function reject(Request $request, $id)
    {
        $tuntutan = TuntutanKhairat::findOrFail($id);

        if ($tuntutan->status !== 'PENDING') {
            return back()->with('error', 'Status tidak sah untuk ditolak.');
        }

        $tuntutan->update([
            'status' => 'REJECTED',
            'approve_by' => auth()->id(),
            'approved_at' => now()
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'entity_type' => 'Laporan Kematian',
            'description' => 'Menolak permohonan khairat bagi ' . $tuntutan->nama_pewaris
        ]);


        return back()->with('success', 'Permohonan telah ditolak.');
    }

    public function pengurusan(Request $request, $id)
    {
        $tuntutan = TuntutanKhairat::findOrFail($id);

        $wallet = Wallet::where('masjid_id', $tuntutan->masjid_id)->first();

        $bayaran = HargaKhairat::where('masjid_id', $tuntutan->masjid_id)->first();


        // Change this condition - check if status is APPROVED (since you only show button for APPROVED)
        if ($tuntutan->status !== 'APPROVED') {
            return back()->with('error', 'Hanya permohonan yang telah disahkan boleh diproses.');
        }

        $tuntutan->update([
            'status' => 'PAID', // This changes to PAID, which is why your badge disappears
            'approve_by' => auth()->id(),
            'approved_at' => now()
        ]);

        $wallet->update([
            'balance' => $wallet->balance - $bayaran->sumbangan_seorang
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'entity_type' => 'Laporan Kematian',
            'description' => 'Pengurusan Kematian khairat bagi ' . $tuntutan->nama_pewaris
        ]);

        Payment::create([
            'user_id' => auth()->id(),
            'masjid_id' => $tuntutan->masjid_id,
            'name' => $tuntutan->nama_ahli,
            'amount' => $bayaran->sumbangan_seorang,
            'payment_method' => 'MANUAL',
            'status' => 'PAID',
            'resit_path' => null,
            'remarks' =>  'Bayaran khairat untuk ' . $tuntutan->nama_ahli . ' (Tuntutan ID: ' . $tuntutan->id . ')',
            'type' => 'KHAIRAT',
            'transaction_type' => 'transaction_out',
            'paid_at' => now()
        ]);

        return back()->with('success', 'Urusan kematian telah selesai.');
    }

    public function updateNote(Request $request, $id)
    {
        $tuntutan = TuntutanKhairat::findOrFail($id);

        $tuntutan->update([
            'nota' => $request->nota,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'entity_type' => 'Laporan Kematian',
            'description' => 'Mengemaskini nota tuntutan khairat bagi ' . $tuntutan->nama_pewaris
        ]);

        return back()->with('success', 'Catatan berjaya dikemas kini.');
    }

    public function storeManual(Request $request)
    {
        try {
            \DB::beginTransaction();

            $validated = $request->validate([
                'claim_type' => 'required|in:ahli,tanggungan',
                'jumlah' => 'required|numeric|min:0',
                'tarikh_meninggal' => 'nullable|date',
                'masjid_id' => 'required|integer',
                'user_id' => 'required|integer',
                'nama' => 'required|string|max:255',
                'ic' => 'required|string|max:20',
            ]);

            $ahliId = null;
            $tanggunganId = null;

            if ($request->claim_type === 'ahli') {
                // Check if ahli exists by IC
                $ahli = AhliKariah::where('ic', $request->ic)->first();

                if (!$ahli && $request->ahli_id) {
                    $ahli = AhliKariah::find($request->ahli_id);
                }

                if ($ahli) {
                    $ahli->update([
                        'status' => 'inactive',
                        'status_at' => now(),
                        'status_reason' => 'MENINGGAL'
                    ]);
                    $ahliId = $ahli->id;
                } else {
                    // Create new ahli if doesn't exist
                    $ahli = AhliKariah::create([
                        'nama' => $request->nama,
                        'ic' => $request->ic,
                        'masjid_id' => $request->masjid_id,
                        'user_id' => $request->user_id,
                        'status' => 'inactive',
                    ]);
                    $ahliId = $ahli->id;
                }
            } else {
                // For tanggungan claim
                $tanggunganId = $request->tanggungan_id;
                $tanggungan = Tanggungan::find($tanggunganId);

                if ($tanggungan) {
                    // Get the Ahli Khairat (the contributor)
                    $ahli = AhliKariah::where('user_id', $tanggungan->user_id)
                        ->where('masjid_id', $request->masjid_id)
                        ->first();

                    if ($ahli) {
                        $ahliId = $ahli->id;
                    }
                }
            }

            // Create tuntutan
            $tuntutan = TuntutanKhairat::create([
                'masjid_id' => $request->masjid_id,
                'user_id' => $request->user_id,
                'ahli_id' => $ahliId,
                'tanggungan_id' => $tanggunganId,
                'type' => $request->claim_type,
                'date_death' => $request->tarikh_meninggal,
                'status' => 'SUCCESS',
                'approve_by' => auth()->id(),
                'approved_at' => now(),
                'death_certificate' => null,
                'police_report' => null,
                'other_report' => null,
            ]);

            // Create payment
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'masjid_id' => $request->masjid_id,
                'name' => 'Pengeluaran Dana Khairat',
                'amount' => $request->jumlah,
                'payment_method' => 'MANUAL',
                'status' => 'PAID',
                'remarks' => 'Bayaran khairat untuk ' . $request->nama,
                'type' => 'Khairat',
                'transaction_type' => 'transaction_out',
                'paid_at' => now(),
                'reference_type' => 'tuntutan_khairat',
                'reference_id' => $tuntutan->id,
            ]);

            // Update wallet
            $wallet = Wallet::firstOrCreate(
                ['masjid_id' => $payment->masjid_id],
                ['balance' => 0]
            );

            if ($payment->transaction_type === 'transaction_out') {
                if ($wallet->balance < $payment->amount) {
                    throw new \Exception('Baki wallet tidak mencukupi. Baki semasa: RM ' . number_format($wallet->balance, 2));
                }
                $wallet->balance -= $payment->amount;
            } else {
                $wallet->balance += $payment->amount;
            }

            $wallet->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rekod kematian berjaya ditambah',
                'data' => $tuntutan
            ], 201);
        } catch (\Exception $e) {
            \DB::rollBack();

            \Log::error('Store manual error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store death record for Ahli Khairat (main member)
     */
    // public function storeDeathRecord(Request $request)
    // {
    //     $request->validate([
    //         'tanggungan_id' => 'required|exists:tanggungan,id',
    //         'ahli_id' => 'required|exists:ahli_kariah,id',
    //         'tarikh_meninggal' => 'required|date',
    //         'sijil_kematian' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    //         'laporan_polis' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    //         'maklumat_lain' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    //         'catatan' => 'nullable|string',
    //         'status_jenazah' => 'required|in:PROCESSING,SUCCESS',
    //     ]);

    //     $ajk = auth()->user();
    //     $masjidId = $ajk->masjid_id;

    //     $tanggungan = Tanggungan::findOrFail($request->tanggungan_id);
    //     $ahliKariah = AhliKariah::findOrFail($request->ahli_id);
    //     $hargaKhairat = HargaKhairat::where('masjid_id', $masjidId)->first();

    //     $icNumber = preg_replace('/[^0-9]/', '', $tanggungan->ic_number ?? 'unknown');
    //     $timestamp = now()->format('Ymd_His');

    //     $existingRecord = TuntutanKhairat::where('tanggungan_id', $request->tanggungan_id)
    //         ->whereIn('status', ['PAID', 'SUCCESS', 'APPROVED'])
    //         ->first();

    //     if ($existingRecord) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Rekod kematian untuk tanggungan ini sudah wujud.'
    //         ], 400);
    //     }

    //     // Upload files (same as above)
    //     $deathCertPath = null;
    //     $policeReportPath = null;
    //     $otherReportPath = null;

    //     if ($request->hasFile('sijil_kematian')) {
    //         $file = $request->file('sijil_kematian');
    //         if ($file->isValid()) {
    //             $filename = "death_certificate_tanggungan_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
    //             $uploadPath = public_path('khairat/death_certificate');
    //             if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
    //             $file->move($uploadPath, $filename);
    //             $deathCertPath = 'khairat/death_certificate/' . $filename;
    //         }
    //     }

    //     if ($request->hasFile('laporan_polis')) {
    //         $file = $request->file('laporan_polis');
    //         if ($file->isValid()) {
    //             $filename = "police_report_tanggungan_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
    //             $uploadPath = public_path('khairat/police_report');
    //             if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
    //             $file->move($uploadPath, $filename);
    //             $policeReportPath = 'khairat/police_report/' . $filename;
    //         }
    //     }

    //     if ($request->hasFile('maklumat_lain')) {
    //         $file = $request->file('maklumat_lain');
    //         if ($file->isValid()) {
    //             $filename = "other_report_tanggungan_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
    //             $uploadPath = public_path('khairat/other_report');
    //             if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
    //             $file->move($uploadPath, $filename);
    //             $otherReportPath = 'khairat/other_report/' . $filename;
    //         }
    //     }

    //     $isSuccess = $request->status_jenazah === 'SUCCESS';
    //     $tuntutanStatus = $isSuccess ? 'APPROVED' : 'PENDING';
    //     $amount = $hargaKhairat ? $hargaKhairat->sumbangan_tanggungan : 0.00;

    //     $deathRecord = TuntutanKhairat::create([
    //         'masjid_id' => $masjidId,
    //         'user_id' => $ahliKariah->user_id,
    //         'ahli_id' => $request->ahli_id,
    //         'tanggungan_id' => $request->tanggungan_id,
    //         'type' => 'TANGGUNGAN',
    //         'date_death' => $request->tarikh_meninggal,
    //         'status' => $tuntutanStatus,
    //         'note' => $request->catatan,
    //         'death_certificate' => $deathCertPath,
    //         'police_report' => $policeReportPath,
    //         'other_report' => $otherReportPath,
    //         'approve_by' => $isSuccess ? $ajk->name : null,
    //         'approved_at' => $isSuccess ? now() : null,
    //         'amount' => $amount,
    //     ]);

    //     if ($isSuccess) {
    //         try {
    //             $wallet = Wallet::where('masjid_id', $masjidId)->first();

    //             if (!$wallet) {
    //                 $wallet = Wallet::create([
    //                     'masjid_id' => $masjidId,
    //                     'balance' => 0,
    //                 ]);
    //             }

    //             if ($wallet->balance < $amount) {
    //                 $deathRecord->update(['status' => 'PENDING', 'approve_by' => null, 'approved_at' => null]);
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Baki dompet tidak mencukupi. Sila tambah dana terlebih dahulu. (Baki semasa: RM ' . number_format($wallet->balance, 2) . ')'
    //                 ], 400);
    //             }

    //             $wallet->update(['balance' => $wallet->balance - $amount]);

    //             $payment = Payment::create([
    //                 'user_id' => $ajk->id,
    //                 'masjid_id' => $masjidId,
    //                 'name' => 'Pengeluaran Dana Khairat - Tanggungan',
    //                 'amount' => $amount,
    //                 'payment_method' => 'WALLET',
    //                 'status' => 'PAID',
    //                 'remarks' => 'Pembayaran tuntutan khairat untuk tanggungan ' . $tanggungan->nama . ' (Tanggungan kepada: ' . $ahliKariah->nama . ')',
    //                 'type' => 'Khairat',
    //                 'transaction_type' => 'transaction_out',
    //                 'paid_at' => now(),
    //                 'reference_type' => 'tuntutan_khairat',
    //                 'reference_id' => $deathRecord->id,
    //             ]);

    //             $deathRecord->update(['status' => 'SUCCESS']);

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Rekod kematian tanggungan berhasil disimpan. Pembayaran telah diproses.',
    //                 'data' => $deathRecord,
    //                 'payment' => $payment,
    //                 'wallet_balance' => $wallet->balance
    //             ]);
    //         } catch (\Exception $e) {
    //             \Log::error('Payment processing error: ' . $e->getMessage());
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Rekod disimpan tetapi gagal memproses pembayaran. Error: ' . $e->getMessage()
    //             ], 500);
    //         }
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Rekod kematian tanggungan berhasil disimpan. Menunggu kelulusan untuk pemprosesan pembayaran.',
    //         'data' => $deathRecord
    //     ]);
    // }


    /**
     * View death record for Ahli Khairat
     */
    public function viewDeathRecord($ahliId)
    {
        $ajk = auth()->user();
        $masjidId = $ajk->masjid_id;

        $deathRecord = TuntutanKhairat::where('ahli_id', $ahliId)
            ->where('masjid_id', $masjidId)
            ->where('type', 'AHLI')
            ->with(['ahli', 'user'])
            ->latest()
            ->first();

        if (!$deathRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Tiada rekod kematian ditemui.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $deathRecord->id,
                'nama' => $deathRecord->ahli->nama ?? 'N/A',
                'ic' => $deathRecord->ahli->ic ?? 'N/A',
                'tarikh_meninggal' => $deathRecord->date_death ? date('d/m/Y', strtotime($deathRecord->date_death)) : '-',
                'death_certificate_path' => $deathRecord->death_certificate ? asset('storage/' . $deathRecord->death_certificate) : null,
                'police_report_path' => $deathRecord->police_report ? asset('storage/' . $deathRecord->police_report) : null,
                'other_report_path' => $deathRecord->other_report ? asset('storage/' . $deathRecord->other_report) : null,
                'catatan' => $deathRecord->note ?? '-',
                'status' => $deathRecord->status,
                'type' => $deathRecord->type,
                'created_at' => $deathRecord->created_at ? date('d/m/Y H:i', strtotime($deathRecord->created_at)) : '-'
            ]
        ]);
    }

    /**
     * View death record for Tanggungan
     */
    public function viewDeathRecordTanggungan($tanggunganId)
    {
        $ajk = auth()->user();
        $masjidId = $ajk->masjid_id;

        $deathRecord = TuntutanKhairat::where('tanggungan_id', $tanggunganId)
            ->where('masjid_id', $masjidId)
            ->where('type', 'TANGGUNGAN')
            ->with(['tanggungan', 'ahli'])
            ->latest()
            ->first();

        if (!$deathRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Tiada rekod kematian ditemui.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $deathRecord->id,
                'nama' => $deathRecord->tanggungan->nama ?? 'N/A',
                'ic' => $deathRecord->tanggungan->ic_number ?? 'N/A',
                'hubungan' => $deathRecord->tanggungan->hubungan ?? '-',
                'tarikh_meninggal' => $deathRecord->date_death ? date('d/m/Y', strtotime($deathRecord->date_death)) : '-',
                'death_certificate_path' => $deathRecord->death_certificate ? asset('storage/' . $deathRecord->death_certificate) : null,
                'catatan' => $deathRecord->note ?? '-',
                'status' => $deathRecord->status,
                'created_at' => $deathRecord->created_at ? date('d/m/Y H:i', strtotime($deathRecord->created_at)) : '-'
            ]
        ]);
    }

    /**
     * Store death record for Luar (non-member)
     */
    public function storeDeathRecordLuar(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'ic' => 'required|string|max:20',
            'tarikh_meninggal' => 'required|date',
            'sijil_kematian' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Made nullable
            'laporan_polis' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'maklumat_lain' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'catatan' => 'nullable|string',
            'status_jenazah' => 'required|in:PROCESSING,SUCCESS',
            'items_modal' => 'array|required_if:status_jenazah,SUCCESS',
            'total_amount_modal' => 'nullable|numeric|min:0',
            'lain_lain_text_modal' => 'nullable|string'
        ]);

        $ajk = auth()->user();
        $masjidId = $ajk->masjid_id;

        // Get IC number for filename
        $icNumber = preg_replace('/[^0-9]/', '', $request->ic);
        $timestamp = now()->format('Ymd_His');

        // Check if death record already exists for this IC (LUAR)
        $existingRecord = TuntutanKhairat::whereHas('ahli', function ($query) use ($request) {
            $query->where('ic', $request->ic);
        })->whereIn('status', ['PAID', 'SUCCESS', 'APPROVED', 'PROCESSING'])
            ->first();

        if ($existingRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Rekod kematian untuk IC ini sudah wujud.'
            ], 400);
        }

        // Upload files - all nullable now
        $deathCertPath = null;
        $policeReportPath = null;
        $otherReportPath = null;

        // Handle Sijil Kematian (Death Certificate) - OPTIONAL now
        if ($request->hasFile('sijil_kematian')) {
            $file = $request->file('sijil_kematian');
            if ($file->isValid()) {
                try {
                    $filename = "death_certificate_luar_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
                    $uploadPath = public_path('khairat/death_certificate');
                    if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                    $file->move($uploadPath, $filename);
                    $deathCertPath = 'khairat/death_certificate/' . $filename;
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memuat naik sijil kematian. Error: ' . $e->getMessage()
                    ], 422);
                }
            }
        }

        // Handle Laporan Polis (Police Report)
        if ($request->hasFile('laporan_polis')) {
            $file = $request->file('laporan_polis');
            if ($file->isValid()) {
                try {
                    $filename = "police_report_luar_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
                    $uploadPath = public_path('khairat/police_report');
                    if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                    $file->move($uploadPath, $filename);
                    $policeReportPath = 'khairat/police_report/' . $filename;
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memuat naik laporan polis. Error: ' . $e->getMessage()
                    ], 422);
                }
            }
        }

        // Handle Maklumat Lain (Other Documents)
        if ($request->hasFile('maklumat_lain')) {
            $file = $request->file('maklumat_lain');
            if ($file->isValid()) {
                try {
                    $filename = "other_report_luar_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
                    $uploadPath = public_path('khairat/other_report');
                    if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                    $file->move($uploadPath, $filename);
                    $otherReportPath = 'khairat/other_report/' . $filename;
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memuat naik maklumat lain. Error: ' . $e->getMessage()
                    ], 422);
                }
            }
        }

        $isSuccess = $request->status_jenazah === 'SUCCESS';
        $tuntutanStatus = $isSuccess ? 'APPROVED' : 'PROCESSING';
        $amount = $request->total_amount_modal ?? 0.00;

        // Create temporary Ahli Khairat record for LUAR (inactive)
        $ahli = AhliKariah::create([
            'nama' => $request->nama,
            'ic' => $request->ic,
            'masjid_id' => $masjidId,
            'user_id' => null,
            'status' => 'inactive',
            'alamat' => null,
            'telefon_bimbit' => null,
            'created_by' => auth()->id(),
        ]);

        // Create death record for LUAR
        $deathRecord = TuntutanKhairat::create([
            'masjid_id' => $masjidId,
            'user_id' => auth()->id(),
            'ahli_id' => $ahli->id,
            'tanggungan_id' => null,
            'type' => 'LUAR',
            'date_death' => $request->tarikh_meninggal,
            'status' => $tuntutanStatus,
            'note' => $request->catatan,
            'death_certificate' => $deathCertPath,
            'police_report' => $policeReportPath,
            'other_report' => $otherReportPath,
            'approve_by' => auth()->id(),
            'approved_at' => $isSuccess ? now() : null,
            'amount' => $amount,
        ]);

        // If status is SUCCESS and items are provided, save the items
        if ($isSuccess && $request->has('items_modal')) {
            // Define the mapping between item values and their labels/descriptions
            $itemMapping = [
                'pengurusan_jenazah' => [
                    'label' => 'Pengurusan Jenazah',
                    'description' => 'Bayaran untuk pengurusan jenazah'
                ],
                'pengangkutan_jenazah' => [
                    'label' => 'Van Jenazah',
                    'description' => 'Bayaran untuk pengangkutan jenazah'
                ],
                'tanah_perkuburan' => [
                    'label' => 'Gali Kubur',
                    'description' => 'Bayaran untuk menggali kubur'
                ],
                'kain_kafan' => [
                    'label' => 'Kain Kafan',
                    'description' => 'Bayaran untuk kain kafan'
                ],
                'air_mandian' => [
                    'label' => 'Air / Mandian',
                    'description' => 'Bayaran untuk air dan keperluan mandian'
                ],
                'imam_bilal' => [
                    'label' => 'Imam / Bilal',
                    'description' => 'Bayaran untuk imam dan bilal'
                ],
                'lain_lain' => [
                    'label' => 'Lain-lain',
                    'description' => $request->lain_lain_text_modal ?? 'Bayaran untuk lain-lain keperluan'
                ],
            ];

            foreach ($request->items_modal as $item) {
                $amountField = 'amount_' . $item . '_modal';
                $itemAmount = $request->input($amountField, 0);

                // Only save if amount is greater than 0
                if ($itemAmount > 0) {
                    $itemLabel = $itemMapping[$item]['label'] ?? ucfirst(str_replace('_', ' ', $item));
                    $itemDescription = $itemMapping[$item]['description'] ?? 'Bayaran untuk ' . $itemLabel;

                    KhairatItems::create([
                        'tuntutan_id' => $deathRecord->id,
                        'item_name' => $item,
                        'item_label' => $itemLabel,
                        'description' => $itemDescription,
                        'amount' => $itemAmount
                    ]);
                }
            }
        }

        // If status is SUCCESS, process payment and wallet deduction
        if ($isSuccess && $amount > 0) {
            try {
                

                $payment = Payment::create([
                    'user_id' => $ajk->id,
                    'masjid_id' => $masjidId,
                    'name' => 'Pengeluaran Dana Khairat - Bukan Ahli',
                    'amount' => $amount,
                    'payment_method' => 'WALLET',
                    'status' => 'PAID',
                    'remarks' => 'Pembayaran tuntutan khairat untuk bukan ahli ' . $request->nama . ' (No. IC: ' . $request->ic . ')',
                    'type' => 'Khairat',
                    'transaction_type' => 'transaction_out',
                    'paid_at' => now(),
                    'reference_type' => 'tuntutan_khairat',
                    'reference_id' => $deathRecord->id,
                ]);

                $deathRecord->update(['status' => 'SUCCESS']);

                return response()->json([
                    'success' => true,
                    'message' => 'Rekod kematian bukan ahli berhasil disimpan. Pembayaran telah diproses. (RM ' . number_format($amount, 2) . ' telah dikeluarkan dari dompet)',
                    'data' => $deathRecord,
                    'payment' => $payment,
                    
                ]);
            } catch (\Exception $e) {
                \Log::error('Payment processing error for LUAR: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Rekod disimpan tetapi gagal memproses pembayaran. Error: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Rekod kematian bukan ahli berhasil disimpan. Menunggu kelulusan untuk pemprosesan pembayaran.',
            'data' => $deathRecord
        ]);
    }

    /**
     * Store death record for Ahli Khairat (Main Member)
     */
    public function storeDeathRecordAhli(Request $request)
    {
        $request->validate([
            'ahli_id' => 'required|exists:ahli_kariah,id',
            'tarikh_meninggal' => 'required|date',
            'sijil_kematian' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'laporan_polis' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'maklumat_lain' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'catatan' => 'nullable|string',
            'status_jenazah' => 'required|in:PROCESSING,SUCCESS',
            'items' => 'array|required_if:status_jenazah,SUCCESS',
            'total_amount' => 'nullable|numeric|min:0',
            'lain_lain_text' => 'nullable|string'
        ]);

        $ajk = auth()->user();
        $masjidId = $ajk->masjid_id;

        // Get the Ahli Khairat record (the deceased)
        $ahliKariah = AhliKariah::with('user')->findOrFail($request->ahli_id);

        // Get IC number for filename
        $icNumber = preg_replace('/[^0-9]/', '', $ahliKariah->ic_number ?? $ahliKariah->user->ic_number ?? 'unknown');
        $timestamp = now()->format('Ymd_His');

        // Check if death record already exists for this Ahli Khairat
        $existingRecord = TuntutanKhairat::where('ahli_id', $request->ahli_id)
            ->whereIn('status', ['PAID', 'SUCCESS', 'APPROVED', 'PROCESSING'])
            ->first();

        if ($existingRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Rekod kematian untuk ahli ini sudah wujud.'
            ], 400);
        }

        // Upload files
        $deathCertPath = null;
        $policeReportPath = null;
        $otherReportPath = null;

        // Handle Sijil Kematian (Death Certificate)
        if ($request->hasFile('sijil_kematian')) {
            $file = $request->file('sijil_kematian');
            if ($file->isValid()) {
                try {
                    $filename = "death_certificate_ahli_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
                    $uploadPath = public_path('khairat/death_certificate');
                    if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                    $file->move($uploadPath, $filename);
                    $deathCertPath = 'khairat/death_certificate/' . $filename;
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memuat naik sijil kematian. Error: ' . $e->getMessage()
                    ], 422);
                }
            }
        }

        // Handle Laporan Polis (Police Report)
        if ($request->hasFile('laporan_polis')) {
            $file = $request->file('laporan_polis');
            if ($file->isValid()) {
                try {
                    $filename = "police_report_ahli_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
                    $uploadPath = public_path('khairat/police_report');
                    if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                    $file->move($uploadPath, $filename);
                    $policeReportPath = 'khairat/police_report/' . $filename;
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memuat naik laporan polis. Error: ' . $e->getMessage()
                    ], 422);
                }
            }
        }

        // Handle Maklumat Lain (Other Documents)
        if ($request->hasFile('maklumat_lain')) {
            $file = $request->file('maklumat_lain');
            if ($file->isValid()) {
                try {
                    $filename = "other_report_ahli_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
                    $uploadPath = public_path('khairat/other_report');
                    if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                    $file->move($uploadPath, $filename);
                    $otherReportPath = 'khairat/other_report/' . $filename;
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memuat naik maklumat lain. Error: ' . $e->getMessage()
                    ], 422);
                }
            }
        }

        $isSuccess = $request->status_jenazah === 'SUCCESS';
        $tuntutanStatus = $isSuccess ? 'APPROVED' : 'PROCESSING';
        $amount = $request->total_amount ?? 0.00;

        // Create death record for AHLI
        $deathRecord = TuntutanKhairat::create([
            'masjid_id' => $masjidId,
            'user_id' => auth()->id(),
            'ahli_id' => $request->ahli_id,
            'tanggungan_id' => null,
            'type' => 'AHLI',
            'date_death' => $request->tarikh_meninggal,
            'status' => $tuntutanStatus,
            'note' => $request->catatan,
            'death_certificate' => $deathCertPath,
            'police_report' => $policeReportPath,
            'other_report' => $otherReportPath,
            'approve_by' => auth()->id(),
            'approved_at' => $isSuccess ? now() : null,
            'amount' => $amount,
        ]);

        // If status is SUCCESS and items are provided, save the items
        if ($isSuccess && $request->has('items')) {
            // Define the mapping between item values and their labels/descriptions
            $itemMapping = [
                'pengurusan_jenazah' => [
                    'label' => 'Pengurusan Jenazah',
                    'description' => 'Bayaran untuk pengurusan jenazah'
                ],
                'pengangkutan_jenazah' => [
                    'label' => 'Van Jenazah',
                    'description' => 'Bayaran untuk pengangkutan jenazah'
                ],
                'tanah_perkuburan' => [
                    'label' => 'Gali Kubur',
                    'description' => 'Bayaran untuk menggali kubur'
                ],
                'kain_kafan' => [
                    'label' => 'Kain Kafan',
                    'description' => 'Bayaran untuk kain kafan'
                ],
                'air_mandian' => [
                    'label' => 'Air / Mandian',
                    'description' => 'Bayaran untuk air dan keperluan mandian'
                ],
                'imam_bilal' => [
                    'label' => 'Imam / Bilal',
                    'description' => 'Bayaran untuk imam dan bilal'
                ],
                'lain_lain' => [
                    'label' => 'Lain-lain',
                    'description' => $request->lain_lain_text ?? 'Bayaran untuk lain-lain keperluan'
                ],
            ];

            foreach ($request->items as $item) {
                $amountField = 'amount_' . $item;
                $itemAmount = $request->input($amountField, 0);

                // Only save if amount is greater than 0
                if ($itemAmount > 0) {
                    $itemLabel = $itemMapping[$item]['label'] ?? ucfirst(str_replace('_', ' ', $item));
                    $itemDescription = $itemMapping[$item]['description'] ?? 'Bayaran untuk ' . $itemLabel;

                    KhairatItems::create([
                        'tuntutan_id' => $deathRecord->id,
                        'item_name' => $item,
                        'item_label' => $itemLabel,
                        'description' => $itemDescription,
                        'amount' => $itemAmount
                    ]);
                }
            }
        }

        // If status is SUCCESS, process payment and wallet deduction
        if ($isSuccess) {
            try {

                $ahliKariah->update(['status' => 'inactive']);
                

                $payment = Payment::create([
                    'user_id' => $ajk->id,
                    'masjid_id' => $masjidId,
                    'name' => 'Pengeluaran Dana Khairat - Ahli',
                    'amount' => $amount,
                    'payment_method' => 'WALLET',
                    'status' => 'PAID',
                    'remarks' => 'Pembayaran tuntutan khairat untuk ahli ' . $ahliKariah->nama . ' (No. IC: ' . $ahliKariah->ic_number . ')',
                    'type' => 'Khairat',
                    'transaction_type' => 'transaction_out',
                    'paid_at' => now(),
                    'reference_type' => 'tuntutan_khairat',
                    'reference_id' => $deathRecord->id,
                ]);

                $deathRecord->update(['status' => 'SUCCESS']);

                return response()->json([
                    'success' => true,
                    'message' => 'Rekod kematian ahli berjaya disimpan.',
                    'data' => $deathRecord,
                    'payment' => $payment,
                    
                ]);
            } catch (\Exception $e) {
                \Log::error('Payment processing error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Rekod disimpan tetapi gagal memproses pembayaran. Error: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Rekod kematian ahli berhasil disimpan. Menunggu kelulusan untuk pemprosesan pembayaran.',
            'data' => $deathRecord
        ]);
    }

    /**
     * Store death record for Tanggungan (dependent)
     */
    public function storeDeathRecordTanggungan(Request $request)
    {

        // Debug logging
        \Log::info('storeDeathRecordTanggungan called', [
            'user_id' => auth()->id(),
            'all_inputs' => $request->all(),
            'files' => $request->hasFile('sijil_kematian') ? 'has file' : 'no file'
        ]);

        // Check authentication
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda perlu login terlebih dahulu.'
            ], 401);
        }

        $request->validate([
            'tarikh_meninggal' => 'required|date',
            'sijil_kematian' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'laporan_polis' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'maklumat_lain' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'catatan' => 'nullable|string',
            'status_jenazah' => 'required|in:PROCESSING,SUCCESS',
            'items' => 'array|required_if:status_jenazah,SUCCESS',
            'total_amount' => 'nullable|numeric|min:0',
            'lain_lain_text' => 'nullable|string'
        ]);

        $ajk = auth()->user();
        $masjidId = $ajk->masjid_id;

        // Get the Tanggungan record (the deceased)
        $tanggungan = Tanggungan::findOrFail($request->tanggungan_id);
     
        // Get IC number for filename (use tanggungan's IC)
        $icNumber = preg_replace('/[^0-9]/', '', $tanggungan->ic_number ?? 'unknown');
        $timestamp = now()->format('Ymd_His');

        // Check if death record already exists for this Tanggungan
        $existingRecord = TuntutanKhairat::where('tanggungan_id', $request->tanggungan_id)
            ->whereIn('status', ['PAID', 'SUCCESS', 'APPROVED', 'PROCESSING'])
            ->first();

        if ($existingRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Rekod kematian untuk tanggungan ini sudah wujud.'
            ], 400);
        }

        // Upload files
        $deathCertPath = null;
        $policeReportPath = null;
        $otherReportPath = null;

        // Handle Sijil Kematian (Death Certificate)
        if ($request->hasFile('sijil_kematian')) {
            $file = $request->file('sijil_kematian');
            if ($file->isValid()) {
                try {
                    $filename = "death_certificate_tanggungan_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
                    $uploadPath = public_path('khairat/death_certificate');
                    if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                    $file->move($uploadPath, $filename);
                    $deathCertPath = 'khairat/death_certificate/' . $filename;
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memuat naik sijil kematian. Error: ' . $e->getMessage()
                    ], 422);
                }
            }
        }

        // Handle Laporan Polis (Police Report)
        if ($request->hasFile('laporan_polis')) {
            $file = $request->file('laporan_polis');
            if ($file->isValid()) {
                try {
                    $filename = "police_report_tanggungan_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
                    $uploadPath = public_path('khairat/police_report');
                    if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                    $file->move($uploadPath, $filename);
                    $policeReportPath = 'khairat/police_report/' . $filename;
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memuat naik laporan polis. Error: ' . $e->getMessage()
                    ], 422);
                }
            }
        }

        // Handle Maklumat Lain (Other Documents)
        if ($request->hasFile('maklumat_lain')) {
            $file = $request->file('maklumat_lain');
            if ($file->isValid()) {
                try {
                    $filename = "other_report_tanggungan_{$icNumber}_{$timestamp}." . $file->getClientOriginalExtension();
                    $uploadPath = public_path('khairat/other_report');
                    if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);
                    $file->move($uploadPath, $filename);
                    $otherReportPath = 'khairat/other_report/' . $filename;
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memuat naik maklumat lain. Error: ' . $e->getMessage()
                    ], 422);
                }
            }
        }

        $isSuccess = $request->status_jenazah === 'SUCCESS';
        $tuntutanStatus = $isSuccess ? 'APPROVED' : 'PROCESSING';

        // Use total_amount from items if provided, otherwise use default harga khairat
        $amount = $request->total_amount ?? 0.00;

        // Create death record for TANGGUNGAN
        $deathRecord = TuntutanKhairat::create([
            'masjid_id' => $masjidId,
            'user_id' => auth()->id(),
            'ahli_id' => $request->ahli_id_tanggungan,
            'tanggungan_id' => $request->tanggungan_id,
            'type' => 'TANGGUNGAN',
            'date_death' => $request->tarikh_meninggal,
            'status' => $tuntutanStatus,
            'note' => $request->catatan,
            'death_certificate' => $deathCertPath,
            'police_report' => $policeReportPath,
            'other_report' => $otherReportPath,
            'approve_by' => auth()->id(),
            'approved_at' => $isSuccess ? now() : null,
            'amount' => $amount,
        ]);

        // If status is SUCCESS and items are provided, save the items to khairat_items table
        if ($isSuccess && $request->has('items')) {
            // Define the mapping between item values and their labels/descriptions
            $itemMapping = [
                'pengurusan_jenazah' => [
                    'label' => 'Pengurusan Jenazah',
                    'description' => 'Bayaran untuk pengurusan jenazah'
                ],
                'pengangkutan_jenazah' => [
                    'label' => 'Van Jenazah',
                    'description' => 'Bayaran untuk pengangkutan jenazah'
                ],
                'tanah_perkuburan' => [
                    'label' => 'Gali Kubur',
                    'description' => 'Bayaran untuk menggali kubur'
                ],
                'kain_kafan' => [
                    'label' => 'Kain Kafan',
                    'description' => 'Bayaran untuk kain kafan'
                ],
                'air_mandian' => [
                    'label' => 'Air / Mandian',
                    'description' => 'Bayaran untuk air dan keperluan mandian'
                ],
                'imam_bilal' => [
                    'label' => 'Imam / Bilal',
                    'description' => 'Bayaran untuk imam dan bilal'
                ],
                'lain_lain' => [
                    'label' => 'Lain-lain',
                    'description' => $request->lain_lain_text ?? 'Bayaran untuk lain-lain keperluan'
                ],
            ];

            foreach ($request->items as $item) {
                $amountField = 'amount_' . $item;
                $itemAmount = $request->input($amountField, 0);

                // Only save if amount is greater than 0
                if ($itemAmount > 0) {
                    $itemLabel = $itemMapping[$item]['label'] ?? ucfirst(str_replace('_', ' ', $item));
                    $itemDescription = $itemMapping[$item]['description'] ?? 'Bayaran untuk ' . $itemLabel;

                    KhairatItems::create([
                        'tuntutan_id' => $deathRecord->id,
                        'item_name' => $item,
                        'item_label' => $itemLabel,
                        'description' => $itemDescription,
                        'amount' => $itemAmount
                    ]);
                }
            }
        }

        // If status is SUCCESS, process payment and wallet deduction
        if ($isSuccess) {
            try {
                
                $payment = Payment::create([
                    'user_id' => $ajk->id,
                    'masjid_id' => $masjidId,
                    'name' => 'Pengeluaran Dana Khairat - Tanggungan',
                    'amount' => $amount,
                    'payment_method' => 'WALLET',
                    'status' => 'PAID',
                    'remarks' => 'Pembayaran tuntutan khairat untuk tanggungan ' . $tanggungan->nama . ' (No. IC: ' . $tanggungan->ic_number . ')',
                    'type' => 'Khairat',
                    'transaction_type' => 'transaction_out',
                    'paid_at' => now(),
                    'reference_type' => 'tuntutan_khairat',
                    'reference_id' => $deathRecord->id,
                ]);

                $deathRecord->update(['status' => 'SUCCESS']);

                return response()->json([
                    'success' => true,
                    'message' => 'Rekod kematian tanggungan berhasil disimpan. Pembayaran telah diproses. (RM ' . number_format($amount, 2) . ' telah dikeluarkan dari dompet)',
                    'data' => $deathRecord,
                    'payment' => $payment,
                    'items' => $deathRecord->items // This will work if you have the relationship defined
                ]);
            } catch (\Exception $e) {
                \Log::error('Payment processing error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Rekod disimpan tetapi gagal memproses pembayaran. Error: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Rekod kematian tanggungan berhasil disimpan. Menunggu kelulusan untuk pemprosesan pembayaran.',
            'data' => $deathRecord
        ]);
    }

    /**
     * Helper function to upload file
     */
    private function uploadFile($file, $folder, $filename)
    {
        $extension = $file->getClientOriginalExtension();
        $fullFilename = $filename . '.' . $extension;
        return $file->storeAs($folder, $fullFilename, 'public');
    }
}
