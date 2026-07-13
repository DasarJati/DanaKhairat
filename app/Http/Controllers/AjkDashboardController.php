<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tanggungan;
use App\Models\AjkRegister;
use App\Models\TuntutanKhairat;
use App\Models\PembayaranKhairat;
use App\Models\AhliKariah;
use App\Models\Ajks;
use App\Models\Waris;
use App\Models\Payment;
use App\Models\ActivityLog;
use App\Models\UserRegister;
use App\Models\masjid as Masjid;
use App\Models\SubscriptionsKariah;

class AjkDashboardController extends Controller
{

    protected function checkAjkSetup($ajk)
    {
        $masjidId = $ajk->masjid_id;

        if (!$masjidId) {
            return false;
        }

        $harga = \App\Models\HargaKhairat::where('masjid_id', $masjidId)->exists();
        $bank  = \App\Models\Bank::where('masjid_id', $masjidId)->exists();

        return $harga && $bank;
    }


    public function dashboard()
    {

        // Use default auth() helper instead of guard('ajk')
        $user = auth()->user();


        // Check if user is AJK (role 1)
        if ($user->role != 1) {
            return redirect()->route('login')
                ->with('error', 'Akses tidak dibenarkan. Sila log masuk sebagai AJK.');
        }

        $masjidId = $user->masjid_id; // Now using $user instead of $ajk

        if (!$this->checkAjkSetup($user)) {
            return redirect()->route('ajk.setup')
                ->with('warning', 'Sila lengkapkan maklumat AJK terlebih dahulu.');
        }

        // ===============================
        // KIRA Ahli KHAIRAT PENDING
        // ===============================
        $jumlahAhliPending = UserRegister::where('approval_status', 'PENDING')
            ->where('masjid_id', $masjidId)
            ->count();

        // ===============================
        // KIRA TUNTUTAN KHAIRAT PENDING
        // ===============================
        $jumlahTuntutanPending = TuntutanKhairat::where('status', 'PENDING')
            ->where('masjid_id', $masjidId)
            ->count();

        // ===============================
        // SENARAI AHLI
        // ===============================
        $ahli = AhliKariah:: // pastikan ada relasi tanggungan
            where('masjid_id', $masjidId)
            ->orderBy('nama', 'asc') // atau nama kalau ada
            ->get();

        // ===============================
        // KIRAAN AHLI
        // ===============================
        $jumlahKetua = AhliKariah::where('masjid_id', $masjidId)->count();

        $jumlahTanggungan = Tanggungan::whereIn(
            'ahli_id',
            AhliKariah::where('masjid_id', $masjidId)->pluck('user_id')
        )->count();



        $jumlahAhli = AhliKariah::where('masjid_id', $masjidId)->where('is_ketua','1')->count();

        $jumlahLelaki = AhliKariah::where('masjid_id', $masjidId)->where('jantina', 'LELAKI')->count();

        $jumlahPerempuan = AhliKariah::where('masjid_id', $masjidId)->where('jantina', 'PEREMPUAN')->count();

        $imagePath = Masjid::where('id', $masjidId)->value('image_path');

        $payments = Payment::where('masjid_id', $user->masjid_id)
            ->where('flow_type', 'income')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalDana = $payments->sum('amount');

        $logs = ActivityLog::with('user')
            ->whereHas('user', function ($q) use ($masjidId) {
                $q->where('masjid_id', $masjidId);
            })
            ->latest()
            ->take(10)
            ->get()
            ->groupBy(function ($log) {
                return $log->created_at->format('d M Y');
            });

        return view('pic.dashboard', compact(
            'ahli',
            'jumlahAhli',
            'jumlahKetua',
            'jumlahTanggungan',
            'jumlahLelaki',
            'jumlahPerempuan',
            'jumlahTuntutanPending',
            'logs',
            'totalDana',
            'masjidId',
            'jumlahAhliPending',
            'imagePath'
        ));
    }


    public function index()
    {
        $ajk = auth()->user();
        $masjidId = $ajk->masjid_id;

        if (!$this->checkAjkSetup($ajk)) {
            return redirect()->route('ajk.setup')
                ->with('warning', 'Sila lengkapkan maklumat AJK terlebih dahulu.');
        }

        $senarai = collect();

        // Eager load user + tanggungan + tuntutan
        $ahliKariahs = AhliKariah::with(['tanggungan', 'subscriptions'])
            ->where('masjid_id', $masjidId)
            ->where('is_ketua', 1)
            ->where('user_id', '!=', '0')
            ->orderBy('alamat', 'asc')
            ->get();

        foreach ($ahliKariahs as $ahli) {

            $user = $ahli->user;
            $subscription = $ahli->subscriptions()->latest('created_at')->first();
            $namaKetua = $user->nama ?? '-';

            // kira jumlah tanggungan
            $familyId = $ahli->family_id;

            $totalTanggungan = Tanggungan::whereNotNull('family_id')
                ->where('family_id', $familyId)
                ->count();

            $totalAhli = AhliKariah::whereNotNull('family_id')
                ->where('family_id', $familyId)
                ->where('is_ketua', '0')
                ->count();
            
            

            $jumlahTanggunganAhli = $totalTanggungan + $totalAhli;

            // dd($jumlahTanggunganAhli);



            // status ikut user
            $statusAktif = $user && strtolower($user->status) === 'active';

            $senarai->push((object)[
                'id'        => $ahli->id,
                'family_id' => $ahli->family_id,
                'user_id'   => $ahli->user_id,
                'nama'      => $user->nama ?? '-',
                'nama_ketua' => $namaKetua,
                'ic'        => $ahli->ic,
                'notel'     => $ahli->notel,
                'umur'      => $ahli->umur,
                'jantina'   => $ahli->jantina,
                'alamat'    => $ahli->alamat,
                'status'    => $ahli->status,
                'jenis'     => 'INDIVIDU',
                'status_ahli' => $user->status ?? 'inactive',
                'status_tuntutan' => $statusAktif ? 'Aktif' : 'Tidak Aktif',
                'type'      => 'ahli',
                'membership_start' => $subscription->start_date ?? null,
                'membership_end'   => $subscription->end_date ?? null,
                'hubungan'  => 'Ahli Utama',
                'jumlah_tanggungan' => $jumlahTanggunganAhli,

            ]);
        }

        // Pagination
        $page = request()->get('page', 1);
        $perPage = 10;
        $paginatedSenarai = new \Illuminate\Pagination\LengthAwarePaginator(
            $senarai->forPage($page, $perPage),
            $senarai->count(),
            $perPage,
            $page,
            [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]
        );

        // Statistics
        $jumlahAhliUtama = $ahliKariahs->count();

        $jumlahTanggungan = Tanggungan::whereIn(
            'ahli_id',
            $ahliKariahs->pluck('id')
        )->count();

        $jumlahAhli = $jumlahAhliUtama; // display ahli sahaja
        $aktif = AhliKariah::where('status', 'active')
            ->where('masjid_id', $masjidId)
            ->where(function ($query) {
                $query->where('user_id', '!=', 0)
                    ->whereNotNull('user_id');
            })
            ->count();

        $takAktif = AhliKariah::where('status', 'inactive')
            ->where('masjid_id', $masjidId)
            ->where(function ($query) {
                $query->where('user_id', '!=', 0)
                    ->whereNotNull('user_id');
            })
            ->count();

        $counts = SubscriptionsKariah::where('masjid_id', $masjidId)
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('subscriptions_kariah')
                    ->groupBy('user_id');
            })
            ->selectRaw("
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as aktif_count,
        SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) as expired_count
    ")
            ->first();

        $aktifsubscription = $counts->aktif_count ?? 0;
        $takAktifSubscription = $counts->expired_count ?? 0;



        return view('khairat.list', compact(
            'jumlahAhli',
            'jumlahAhliUtama',
            'jumlahTanggungan',
            'aktif',
            'takAktif',
            'paginatedSenarai',
            'masjidId',
            'aktifsubscription',
            'takAktifSubscription'
        ));
    }

    // Helper function to calculate age from IC
    private function calculateAge($icNumber)
    {
        if (!$icNumber || strlen($icNumber) < 12) return null;

        $year = substr($icNumber, 0, 2);
        $month = substr($icNumber, 2, 2);
        $day = substr($icNumber, 4, 2);

        // Handle year (assuming 00-21 is 2000-2021, 22-99 is 1922-1999)
        $currentYear = date('Y');
        $prefix = ($year > ($currentYear % 100)) ? 1900 : 2000;
        $fullYear = $prefix + $year;

        $birthDate = $fullYear . '-' . $month . '-' . $day;
        $age = date_diff(date_create($birthDate), date_create('today'))->y;

        return $age;
    }

    public function butiranAhli($id)
    {
        $ajkUser = auth()->user();
        $masjidID = $ajkUser->masjid_id;



        // Get AhliKariah record directly with relationships
        $ahliKariah = AhliKariah::with([
            'tanggungan',
            'subscriptions',
            'waris',
            'user', // Load the associated user if needed
            'tuntutan' => function ($q) {
                $q->where('type', 'AHLI');
            }
        ])
            ->where('masjid_id', $masjidID)
            ->findOrFail($id);

        $allTanggungan = Tanggungan::whereNotNull('family_id')
            ->where('family_id', $ahliKariah->family_id)
            ->get();

        // Get other ahli_kariah members in the same family (not the current ketua)
        $familyAhli = AhliKariah::whereNotNull('family_id')
            ->where('family_id', $ahliKariah->family_id)
            ->where('is_ketua', '0')
            ->whereNotNull('user_id')
            ->where('user_id', '!=', 0)
            // ->where('status', 'active')
            ->get();



        // Get the associated User if needed for additional user data
        $user = $ahliKariah->user;

        // Get waris records (using ahli_kariah id or user_id? Adjust as needed)
        $waris = Waris::where('ahli_id', $ahliKariah->id)->get();
        // Or if waris uses user_id:
        // $waris = Waris::where('user_id', $ahliKariah->user_id)->get();

        // IMPORTANT: Check for death records using ahli_id (from ahli_kariah table)
        $tuntutanPaid = TuntutanKhairat::where('ahli_id', $ahliKariah->id)
            ->whereIn('status', ['PAID', 'SUCCESS', 'APPROVED'])
            ->where('type', 'AHLI')
            ->latest()
            ->first();

        // Get processing tuntutan for this ahli using ahli_id
        $tuntutanProcessing = TuntutanKhairat::where('ahli_id', $ahliKariah->id)
            ->where('type', 'AHLI')
            ->where('status', 'PROCESSING')
            ->latest()
            ->first();

        // Get pending tuntutan for this ahli using ahli_id
        $tuntutanPending = TuntutanKhairat::where('ahli_id', $ahliKariah->id)
            ->where('type', 'AHLI')
            ->where('status', 'PENDING')
            ->first();

        // Get subscription data directly from AhliKariah
        $latestSubscription = $ahliKariah->subscriptions()
            ->latest('id')
            ->first();

        // Check if there's an ACTIVE subscription
        $activeSubscription = $ahliKariah->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();

        $isSubscriptionActive = $activeSubscription !== null;

        // Use LATEST subscription for display
        $subscriptionForDisplay = $latestSubscription;
        $subscriptionStartDate = $subscriptionForDisplay?->start_date;
        $subscriptionEndDate = $subscriptionForDisplay?->end_date;
        $subscriptionStatus = $subscriptionForDisplay?->status;

        // Check user status from ahli_kariah
        $isUserActive = $ahliKariah->status === 'active';

        // Check if user is deceased
        $isDead = $tuntutanPaid !== null;
        $isProcessing = $tuntutanProcessing !== null;
        $isPending = $tuntutanPending !== null;

        // Get death date
        $deathRecord = $tuntutanPaid ?? $tuntutanProcessing ?? $tuntutanPending;
        $tarikhMeninggal = $deathRecord?->date_death;
        $deathStatus = $deathRecord?->status;

        // Determine button eligibility
        $canMakeClaim = $isUserActive && $isSubscriptionActive && !$isDead && !$isProcessing && !$isPending;

        // Set status for view (using ahli_kariah data)
        $ahliKariah->status_keahlian = $isSubscriptionActive ? 'Aktif' : 'Tamat Tempoh';

        if (!$this->checkAjkSetup($ajkUser)) {
            return redirect()->route('ajk.setup')
                ->with('warning', 'Sila lengkapkan maklumat AJK terlebih dahulu.');
        }

        // Get tuntutan for each tanggungan using tanggungan_id
        foreach ($allTanggungan as $tanggungan) {
            $tanggungan->tuntutan_records = TuntutanKhairat::where('tanggungan_id', $tanggungan->id)
                ->where('type', 'TANGGUNGAN')->get();

            $tanggungan->latest_tuntutan = TuntutanKhairat::where('tanggungan_id', $tanggungan->id)
                ->where('type', 'TANGGUNGAN')
                ->where('status', '!=', 'DRAFT')
                ->orderByRaw("FIELD(status, 'PAID', 'SUCCESS', 'APPROVED', 'PROCESSING', 'PENDING')")
                ->latest()->first();

            $tanggungan->has_paid_record = TuntutanKhairat::where('tanggungan_id', $tanggungan->id)
                ->where('type', 'TANGGUNGAN')
                ->whereIn('status', ['PAID', 'SUCCESS', 'APPROVED'])->exists();

            $tanggungan->has_processing_record = TuntutanKhairat::where('tanggungan_id', $tanggungan->id)
                ->where('type', 'TANGGUNGAN')->where('status', 'PROCESSING')->exists();

            $tanggungan->has_pending_record = TuntutanKhairat::where('tanggungan_id', $tanggungan->id)
                ->where('type', 'TANGGUNGAN')->where('status', 'PENDING')->exists();
        }





        return view('khairat.butiran', [
            'ahli'                      => $ahliKariah, // Now passing AhliKariah instead of User
            'user'                      => $user, // Pass user data if needed
            'ahliKariah'                => $ahliKariah,
            // 'tanggungan'                => $ahliKariah->tanggungan ?? collect(),
            'waris'                     => $ahliKariah->waris ?? collect(),
            'tuntutanPending'           => $tuntutanPending,
            'tuntutanProcessing'        => $tuntutanProcessing,
            'tuntutanPaid'              => $tuntutanPaid,
            'isDead'                    => $isDead,
            'isProcessing'              => $isProcessing,
            'isPending'                 => $isPending,
            'isUserActive'              => $isUserActive,
            'tarikhMeninggal'           => $tarikhMeninggal,
            'deathStatus'               => $deathStatus,
            'isSubscriptionActive'      => $isSubscriptionActive,
            'activeSubscription'        => $activeSubscription,
            'latestSubscription'        => $latestSubscription,
            'subscriptionForDisplay'    => $subscriptionForDisplay,
            'subscriptionStartDate'     => $subscriptionStartDate,
            'subscriptionEndDate'       => $subscriptionEndDate,
            'subscriptionStatus'        => $subscriptionStatus,
            'canMakeClaim'              => $canMakeClaim,
            'tanggungan'   => $allTanggungan,   // ← was $ahliKariah->tanggungan
            'familyAhli'   => $familyAhli,      // ← other ahli in same family
        ]);
    }

    public function butiranKhairat($type, $id)
    {
        $tuntutan = null;

        if ($type === 'AHLI') {
            $tuntutan = TuntutanKhairat::where('ahli_id', $id)
                ->with(['ahli', 'user', 'masjid', 'items'])
                ->first();
        } else if ($type === 'TANGGUNGAN') {
            $tuntutan = TuntutanKhairat::where('tanggungan_id', $id)
                ->with(['tanggungan', 'user', 'masjid', 'ahli', 'items'])
                ->first();
        } else if ($type === 'LUAR') {
            $tuntutan = TuntutanKhairat::where('ahli_id', $id)
                ->with(['ahli', 'user', 'masjid', 'items'])
                ->first();
        }

        if (!$tuntutan) {
            abort(404, 'Rekod tuntutan tidak ditemui');
        }

        // Get deceased name for modal
        $deceasedName = '';
        if ($tuntutan->type == 'AHLI') {
            $deceasedName = $tuntutan->ahli->nama ?? 'Nama Tidak Ditemui';
        } elseif ($tuntutan->type == 'TANGGUNGAN') {
            $deceasedName = $tuntutan->tanggungan->nama ?? 'Nama Tanggungan';
        } else {
            $deceasedName = $tuntutan->nama_simpanan ?? 'Nama Tidak Ditemui';
        }

        return view('khairat.butiranKhairat', [
            'tuntutan' => $tuntutan,
            'type' => $type,
            'id' => $id,
            'deceasedName' => $deceasedName
        ]);
    }

    public function updateStatusTuntutan(Request $request, $id)
    {
        try {
            $tuntutan = TuntutanKhairat::findOrFail($id);
            $originalStatus = $tuntutan->status;

            $tuntutan->status = $request->status;
            $tuntutan->note = $request->note;

            $totalAmount = 0;

            if ($request->status === 'SUCCESS' && $request->has('items')) {
                // Delete existing items if any
                $tuntutan->items()->delete();

                foreach ($request->items as $item) {
                    $amountField = 'amount_' . str_replace('-', '_', $item);
                    $amount = $request->input($amountField, 0);
                    $totalAmount += $amount;

                    $itemLabels = [
                        'pengurusan_jenazah' => 'Pengurusan Jenazah',
                        'pengangkutan_jenazah' => 'Van Jenazah',
                        'tanah_perkuburan' => 'Gali Kubur',
                        'kain_kafan' => 'Kain Kafan',
                        'air_mandian' => 'Air / Mandian',
                        'imam_bilal' => 'Imam / Bilal',
                        'lain_lain' => 'Lain-lain'
                    ];

                    $description = null;
                    if ($item === 'lain_lain' && $request->has('lain_lain_text')) {
                        $description = $request->lain_lain_text;
                    }

                    $tuntutan->items()->create([
                        'item_label' => $itemLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)),
                        'amount' => $amount,
                        'description' => $description
                    ]);
                }

                $tuntutan->amount = $totalAmount;
                $tuntutan->approved_at = now();
                $tuntutan->approve_by = auth()->id();
            }

            $tuntutan->save();

            // Create payment record if status changed to SUCCESS
            if ($request->status === 'SUCCESS' && $originalStatus !== 'SUCCESS') {
                try {
                    $masjidId = $tuntutan->masjid_id ?? auth()->user()->masjid_id;
                    $ajk = auth()->user();

                    // Get deceased name based on type
                    $deceasedName = '';
                    if ($tuntutan->type == 'AHLI' && $tuntutan->ahli) {
                        $deceasedName = $tuntutan->ahli->nama;
                    } elseif ($tuntutan->type == 'TANGGUNGAN' && $tuntutan->tanggungan) {
                        $deceasedName = $tuntutan->tanggungan->nama;
                    } elseif ($tuntutan->type == 'LUAR' && $tuntutan->ahli) {
                        $deceasedName = $tuntutan->ahli->nama;
                    } else {
                        $deceasedName = 'Unknown';
                    }

                    // Get IC number
                    $icNumber = '';
                    if ($tuntutan->type == 'AHLI' && $tuntutan->ahli) {
                        $icNumber = $tuntutan->ahli->ic_number ?? $tuntutan->ahli->user->ic_number ?? '';
                    } elseif ($tuntutan->type == 'TANGGUNGAN' && $tuntutan->tanggungan) {
                        $icNumber = $tuntutan->tanggungan->ic_number ?? '';
                    } elseif ($tuntutan->type == 'LUAR' && $tuntutan->ahli) {
                        $icNumber = $tuntutan->ahli->ic_number ?? '';
                    }

                    // Create payment record
                    $payment = Payment::create([
                        'user_id' => $ajk->id,
                        'masjid_id' => $masjidId,
                        'name' => 'Pengeluaran Dana Khairat - ' . $tuntutan->type,
                        'amount' => $totalAmount,
                        'payment_method' => 'WALLET',
                        'status' => 'PAID',
                        'remarks' => 'Pembayaran tuntutan khairat untuk ' . $deceasedName . ' (No. IC: ' . $icNumber . ') - ID Tuntutan: ' . $tuntutan->id,
                        'type' => 'Khairat',
                        'flow_type' => 'expense',
                        'paid_at' => now(),
                        'reference_type' => 'tuntutan_khairat',
                        'reference_id' => $tuntutan->id,
                    ]);

                    // Update tuntutan status to PAID
                    $tuntutan->status = 'SUCCESS';
                    $tuntutan->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Status Pengurusan berjaya dikemaskini.)',
                        'payment' => $payment,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Payment processing error in updateStatusTuntutan: ' . $e->getMessage());

                    // Revert tuntutan status back to PROCESSING if payment fails
                    $tuntutan->status = 'PROCESSING';
                    $tuntutan->approved_at = null;
                    $tuntutan->approve_by = null;
                    $tuntutan->save();

                    return response()->json([
                        'success' => false,
                        'message' => 'Status dikemaskini tetapi gagal memproses pembayaran. Error: ' . $e->getMessage()
                    ], 500);
                }
            }

            // For non-SUCCESS updates or if already SUCCESS
            return response()->json([
                'success' => true,
                'message' => 'Status Pengurusan berjaya dikemaskini'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateReceipt($tuntutanId)
    {
        $tuntutan = TuntutanKhairat::with(['ahli', 'tanggungan', 'user', 'masjid', 'items'])
            ->findOrFail($tuntutanId);

        // Check if status is SUCCESS
        if ($tuntutan->status !== 'SUCCESS') {
            return redirect()->back()->with('error', 'Resit hanya boleh dijana untuk tuntutan yang telah selesai (SUCCESS).');
        }

        return view('khairat.e-receipt', [
            'tuntutan' => $tuntutan
        ]);
    }
}
