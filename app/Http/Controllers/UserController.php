<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\AhliKariah;
use App\Models\ActivityLog;
use App\Models\TuntutanKhairat;
use App\Models\PembayaranKhairat;
use App\Models\User;
use App\Models\masjid as Masjid;
use App\Models\HargaKhairat;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Tanggungan;



class UserController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        // Log activity - Page view
        // ActivityLog::create([
        //     'user_id' => $user->id,
        //     'entity_type' => 'dashboard',
        //     'description' => 'Mengakses halaman dashboard utama'
        // ]);

        $user->load('waris', 'tanggungan', 'ahliKariah.masjid', 'subscriptions');

        /**
         * ============================
         * AMBIL SEMUA TUNTUTAN USER
         * ============================
         */
        $tuntutan = TuntutanKhairat::where('user_id', $user->id)
            ->whereIn('status', ['DRAFT', 'PENDING', 'APPROVED', 'REJECTED'])
            ->get()
            ->keyBy('ahli_id');

        /**
         * ============================
         * PEMBAYARAN
         * ============================
         */
        $pembayaran = PembayaranKhairat::whereHas('tuntutan', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->orderBy('tarikh_kelulusan', 'desc')
            ->get();

        /**
         * ============================
         * STATUS AKTIF KHAIRAT - AMBIL SUBSCRIPTION LATEST
         * ============================
         */
        // Get the LATEST subscription (by end_date or created_at)
        $latestSubscription = $user->subscriptions()
            ->orderBy('end_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();

        // Check if subscription is active
        $statusAktif = false;

        if ($latestSubscription) {
            $statusAktif = $latestSubscription->status === 'active' &&
                $latestSubscription->end_date &&
                Carbon::now()->lte(Carbon::parse($latestSubscription->end_date));
        }

        // Also check user status from ahli_kariah table
        if (!$statusAktif && $user->ahliKariah) {
            $statusAktif = $user->ahliKariah->status === 'active';
        }

        /**
         * ============================
         * FAMILY MEMBERS COUNT
         * ============================
         */
        // Get the user's family_id from ahli_kariah table
        $ahliKariah = $user->ahliKariah;
        $familyId = $ahliKariah ? $ahliKariah->family_id : null;

        // Count total family members (ketua + tanggungan) by family_id
        $totalFamilyMembers = 0;
        $ketuaCount = 0;
        $tanggunganCount = 0;

        if ($familyId) {
            // Count ketua (is_ketua = 1) in ahli_kariah table for this family
            $ketuaCount = AhliKariah::where('family_id', $familyId)
                ->where('is_ketua', '1')
                ->count();

            $noketua  = AhliKariah::where('family_id', $familyId)
                ->where('is_ketua', '0')
                ->count();

            $tanggungan = Tanggungan::where('family_id', $familyId)->count();

            // Count tanggungan from tanggungan table for this family_id
            $tanggunganCount = $noketua + $tanggungan;

            $totalFamilyMembers = $ketuaCount + $tanggunganCount;
        }

        // If no family_id found, default to current user as ketua
        if (!$familyId) {
            $ketuaCount = 1;
            $tanggunganCount = $user->tanggungan ? $user->tanggungan->count() : 0;
            $totalFamilyMembers = $ketuaCount + $tanggunganCount;
        }

        /**
         * ============================
         * DATA PEMOHON UTAMA
         * ============================
         */
        // TUNTUTAN PEMOHON UTAMA
        $tuntutanAhli = TuntutanKhairat::where('user_id', $user->id)
            ->whereNull('ahli_id')
            ->latest()
            ->first();

        // TUNTUTAN TANGGUNGAN
        $tuntutan = TuntutanKhairat::where('user_id', $user->id)
            ->whereNotNull('ahli_id')
            ->get()
            ->keyBy('ahli_id');

        $statusTuntutan = $tuntutanAhli->status ?? null;

        $isApproved = $statusTuntutan === 'APPROVED';
        $isPending  = $statusTuntutan === 'PENDING';
        $isRejected = $statusTuntutan === 'REJECTED';

        // ❗ INI YANG HILANG SEBELUM NI
        $isTakLayak = $isApproved || $isPending;
        $isLayak    = $statusAktif && !$isTakLayak;

        // Get activity logs for the user
        $audit_logs = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'tuntutan',
            'pembayaran',
            'statusAktif',
            'tuntutanAhli',
            'isApproved',
            'isPending',
            'isRejected',
            'isTakLayak',
            'isLayak',
            'latestSubscription',
            'totalFamilyMembers',
            'ketuaCount',
            'tanggunganCount',
            'audit_logs'
        ));
    }


    public function Keluarga()
    {
        $user = Auth::user();

        $ahliKariah = AhliKariah::where('user_id', $user->id)->first();

        if (!$ahliKariah) {
            return view('user.keluarga', [
                'user'               => $user,
                'tanggungan'         => collect(),
                'familyAhli'         => collect(),
                'jumlahAhli'         => 0,
                'jumlahTuntutan'     => 0,
                'umurKetua'          => 0,
                'ketuaHasClaim'      => null,
                'tanggunganIdexits'  => [],
            ]);
        }

        $familyId = $ahliKariah->family_id;

        // Get ALL tanggungan under same family_id
        $allAhliIds = AhliKariah::where('family_id', $familyId)->pluck('id')->toArray();

        $tanggunganIds = Tanggungan::whereIn('ahli_id', $allAhliIds)
            ->orWhere('family_id', $familyId)
            ->pluck('id')
            ->toArray();

        $tanggunganIdexits = TuntutanKhairat::whereIn('tanggungan_id', $tanggunganIds)
            ->pluck('tanggungan_id')
            ->toArray();

        $noketua = AhliKariah::where('family_id', $familyId)
        ->where('is_ketua', '0')
        ->count();
        $counttanggungan = Tanggungan::where('family_id', $familyId)->count();

        // Get ALL tanggungan under same family_id
        $tanggungan = Tanggungan::whereIn('ahli_id', $allAhliIds)
            ->orWhere('family_id', $familyId)
            ->get()
            ->map(function ($t) use ($tanggunganIdexits) {
                if (!empty($t->ic_number) && strlen($t->ic_number) >= 6) {
                    $cleanedIc  = preg_replace('/[^0-9]/', '', $t->ic_number);
                    $tahun      = substr($cleanedIc, 0, 2);
                    $bulan      = substr($cleanedIc, 2, 2);
                    $hari       = substr($cleanedIc, 4, 2);
                    $tahunPenuh = ($tahun <= 29) ? '20' . $tahun : '19' . $tahun;
                    $t->umur    = checkdate((int)$bulan, (int)$hari, (int)$tahunPenuh)
                        ? Carbon::createFromDate($tahunPenuh, $bulan, $hari)->age
                        : 0;
                } else {
                    $t->umur = 0;
                }

                $claimExists       = in_array($t->id, $tanggunganIdexits);
                $t->layak          = !($t->hubungan === 'ANAK' && $t->umur >= 24 && !$t->oku);
                $t->hasClaim       = $claimExists;
                $t->bolehMohon     = !$claimExists;
                $tuntutanT         = TuntutanKhairat::where('tanggungan_id', $t->id)->latest()->first();
                $t->statusTuntutan = $tuntutanT?->status ?? 'TIADA';
                $t->tuntutanInfo   = $tuntutanT;

                return $t;
            });

        // Get other ahli_kariah in same family (not current user) — old ketua etc
        $familyAhli = AhliKariah::where('family_id', $familyId)
            ->where('id', '!=', $ahliKariah->id)
            ->where('status', 'active')
            ->get()
            ->map(function ($fa) {
                // Calculate age
                if (!empty($fa->ic) && strlen($fa->ic) >= 6) {
                    $cleanedIc  = preg_replace('/[^0-9]/', '', $fa->ic);
                    $tahun      = substr($cleanedIc, 0, 2);
                    $bulan      = substr($cleanedIc, 2, 2);
                    $hari       = substr($cleanedIc, 4, 2);
                    $tahunPenuh = ($tahun <= 29) ? '20' . $tahun : '19' . $tahun;
                    $fa->umur   = checkdate((int)$bulan, (int)$hari, (int)$tahunPenuh)
                        ? Carbon::createFromDate($tahunPenuh, $bulan, $hari)->age
                        : 0;
                } else {
                    $fa->umur = 0;
                }

                $fa->tuntutanInfo = TuntutanKhairat::where('ahli_id', $fa->id)
                    ->where('type', 'AHLI')
                    ->whereIn('status', ['PROCESSING', 'SUCCESS', 'PENDING'])
                    ->latest()->first();

                $fa->hasClaim = $fa->tuntutanInfo !== null;

                return $fa;
            });

        $ketuaHasClaim = TuntutanKhairat::where('ahli_id', $ahliKariah->id)
            ->where('type', 'AHLI')
            ->whereIn('status', ['PROCESSING', 'SUCCESS'])
            ->first();

        $jumlahAhli     = $counttanggungan+$noketua;
        $jumlahTuntutan = TuntutanKhairat::whereIn('tanggungan_id', $tanggunganIds)->count();

        // Ketua age
        $umurKetua = 0;
        if (!empty($user->ic_number) && strlen($user->ic_number) >= 6) {
            $cleanedIc  = preg_replace('/[^0-9]/', '', $user->ic_number);
            $tahun      = substr($cleanedIc, 0, 2);
            $bulan      = substr($cleanedIc, 2, 2);
            $hari       = substr($cleanedIc, 4, 2);
            $tahunPenuh = ($tahun <= 29) ? '20' . $tahun : '19' . $tahun;
            if (checkdate((int)$bulan, (int)$hari, (int)$tahunPenuh)) {
                $umurKetua = Carbon::createFromDate($tahunPenuh, $bulan, $hari)->age;
            }
        }

        return view('user.keluarga', compact(
            'user',
            'umurKetua',
            'tanggungan',
            'familyAhli',       // ← new
            'tanggunganIdexits',
            'jumlahAhli',
            'jumlahTuntutan',
            'ketuaHasClaim',
        ));
    }

    public function tuntutan(User $user)
    {
        $tuntutan = TuntutanKhairat::where('user_id', $user->id)
            ->whereIn('status', ['PENDING', 'APPROVED', 'REJECTED'])
            ->with('pembayaran')
            ->get();
        return view('user.tuntutan', compact('tuntutan'));
    }

    public function transactions(User $user)
    {
        // Get price
        $harga = HargaKhairat::where('masjid_id', $user->masjid_id)->first();

        if (!$harga) {
            throw new \Exception('Harga khairat belum ditetapkan oleh masjid ini.');
        }

        // Get payments
        $pembayaran = Payment::where('user_id', $user->id)
            ->orderBy('paid_at', 'desc')
            ->get();

        // Get subscription info
        $ahliKariah = AhliKariah::where('user_id', $user->id)->first();
        $subscriptionStatus = null; // 'active', 'expired', or 'none'
        $subscriptionStartDate = null;
        $subscriptionEndDate = null;
        $lastExpiredDate = null;
        $daysRemaining = 0;

        if ($ahliKariah) {
            // Get the most recent subscription (active or expired)
            $latestSubscription = $ahliKariah->subscriptions()
                ->orderBy('end_date', 'desc')
                ->first();

            // Get active subscription
            $activeSubscription = $ahliKariah->subscriptions()
                ->where('status', 'active')
                ->where('end_date', '>=', now())
                ->latest()
                ->first();

            if ($activeSubscription) {
                // ACTIVE - Show active period
                $subscriptionStatus = 'active';
                $subscriptionStartDate = $activeSubscription->start_date;
                $subscriptionEndDate = $activeSubscription->end_date;
                $daysRemaining = now()->diffInDays($activeSubscription->end_date, false);
            } elseif ($latestSubscription && $latestSubscription->end_date < now()) {
                // EXPIRED - Show last expired date and ask to renew
                $subscriptionStatus = 'expired';
                $lastExpiredDate = $latestSubscription->end_date;
            } else {
                // NO SUBSCRIPTION
                $subscriptionStatus = 'none';
            }
        } else {
            $subscriptionStatus = 'none';
        }

        // Pass variables to the view
        return view('user.transaction', compact(
            'pembayaran',
            'harga',
            'user',
            'subscriptionStatus',
            'subscriptionStartDate',
            'subscriptionEndDate',
            'lastExpiredDate',
            'daysRemaining'
        ));
    }

    public function storeTransaction(Request $request, User $user)
{
    // Validate the incoming modal data
    $request->validate([
        'amount'         => 'required|numeric|min:1',
        'payment_method' => 'required|string',
        'paid_at'        => 'required|date',
        'resit'          => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
    ]);

    $resitPath = null;
    $s3Path = null;

    // Handle the receipt upload to S3
    if ($request->hasFile('resit')) {
        $file = $request->file('resit');

        if ($file->isValid()) {
            try {
                // Generate unique filename
                $timestamp = now()->format('Ymd_His');
                $userId = $user->id;
                $extension = $file->getClientOriginalExtension();
                $filename = "resit_renew_{$userId}_{$timestamp}." . $extension;

                // S3 path - using 'images/ahli_renew' folder
                $s3Path = 'images/ahli_renew/' . $filename;

                // Upload to S3
                $uploaded = Storage::disk('s3')->put($s3Path, file_get_contents($file), 'public');

                if (!$uploaded) {
                    throw new \Exception('Failed to upload receipt to S3');
                }

                // Get the S3 URL
                $resitPath = Storage::disk('s3')->url($s3Path);

                Log::info('Renewal receipt uploaded to S3', [
                    'user_id' => $user->id,
                    'path' => $s3Path,
                    'url' => $resitPath,
                    'filename' => $filename
                ]);

            } catch (\Exception $e) {
                Log::error('S3 Upload Error (Renewal Receipt): ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);

                return back()->with('error', 'Gagal memuat naik resit pembayaran. Sila cuba lagi.');
            }
        } else {
            return back()->with('error', 'Fail resit tidak sah atau rosak. Sila pilih fail yang lain.');
        }
    }

    // Begin transaction
    DB::beginTransaction();

    try {
        // Create the payment record
        $payment = Payment::create([
            'user_id'          => $user->id,
            'masjid_id'        => $user->masjid_id ?? 1,
            'name'             => $user->nama ?? $user->name ?? 'Ahli Khairat',
            'amount'           => $request->amount,
            'payment_method'   => $request->payment_method,
            'status'           => 'PENDING',
            'type'             => $request->type,             // Hidden input: Renew Membership
            'transaction_type' => $request->transaction_type, // Hidden input: transaction_in
            'paid_at'          => $request->paid_at,
            'resit_path'       => $resitPath,
            'remarks'          => $request->remarks,
        ]);

        DB::commit();

        Log::info('Renewal transaction recorded', [
            'payment_id' => $payment->id,
            'user_id' => $user->id,
            'amount' => $request->amount,
            'resit_path' => $resitPath
        ]);

        return back()->with('success', 'Transaksi berjaya direkodkan.');

    } catch (\Exception $e) {
        DB::rollBack();

        // Delete uploaded file from S3 if transaction fails
        if ($resitPath && $s3Path) {
            try {
                Storage::disk('s3')->delete($s3Path);
                Log::info('Deleted S3 file after transaction rollback', [
                    'path' => $s3Path,
                    'user_id' => $user->id
                ]);
            } catch (\Exception $deleteError) {
                Log::error('Failed to delete S3 file: ' . $deleteError->getMessage());
            }
        }

        Log::error('Renewal transaction failed: ' . $e->getMessage(), [
            'user_id' => $user->id,
            'error' => $e->getMessage()
        ]);

        return back()->with('error', 'Gagal merekodkan transaksi: ' . $e->getMessage());
    }
}
}
