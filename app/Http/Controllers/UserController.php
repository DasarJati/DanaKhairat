<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
use App\Models\SubscriptionsKariah;



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

            if (empty($familyId)) {
                $noketua = 0;
            } else {
                $noketua = AhliKariah::where('family_id', $familyId)
                    ->where('is_ketua', '0')
                    ->count();
            }

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

        if (empty($familyId)) {
            $noketua = 0;
        } else {
            $noketua = AhliKariah::where('family_id', $familyId)
                ->where('is_ketua', '0')
                ->count();
        }
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
        if (empty($familyId)) {
            $familyAhli = collect(); // Return empty collection
        } else {
            $familyAhli = AhliKariah::where('family_id', $familyId)
                ->where('is_ketua', '0')
                ->get()
                ->map(function ($fa) {
                    // Calculate age
                    if (!empty($fa->ic) && strlen($fa->ic) >= 6) {
                        $cleanedIc  = preg_replace('/[^0-9]/', '', $fa->ic);
                        $tahun      = substr($cleanedIc, 0, 2);
                        $bulan      = substr($cleanedIc, 2, 2);
                        $hari       = substr($cleanedIc, 4, 2);
                        $tahunPenuh = ($tahun <= 29) ? '20' . $tahun : '19' . $tahun;

                        $fa->umur = checkdate((int)$bulan, (int)$hari, (int)$tahunPenuh)
                            ? Carbon::createFromDate($tahunPenuh, $bulan, $hari)->age
                            : 0;
                    } else {
                        $fa->umur = 0;
                    }

                    $fa->tuntutanInfo = TuntutanKhairat::where('ahli_id', $fa->id)
                        ->where('type', 'AHLI')
                        ->whereIn('status', ['PROCESSING', 'SUCCESS', 'PENDING'])
                        ->latest()
                        ->first();

                    $fa->hasClaim = $fa->tuntutanInfo !== null;

                    return $fa;
                });
        }

        $ketuaHasClaim = TuntutanKhairat::where('ahli_id', $ahliKariah->id)
            ->where('type', 'AHLI')
            ->whereIn('status', ['PROCESSING', 'SUCCESS'])
            ->first();

        $jumlahAhli     = $counttanggungan + $noketua;
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

        $Auth = auth()->user();
        $user = User::findOrFail($Auth->id);
        // Get price
        $harga = HargaKhairat::where('masjid_id', $user->masjid_id)->first();

        if (!$harga) {
            throw new \Exception('Harga khairat belum ditetapkan oleh masjid ini.');
        }

        // Get payments with subscription relationship
        $pembayaran = Payment::where('user_id', $user->id)
            ->with('subscription')
            ->orderBy('paid_at', 'desc')
            ->get();

        // Get subscription info
        $ahliKariah = AhliKariah::where('user_id', $user->id)->first();
        $subscriptionStatus = 'none';
        $subscriptionStartDate = null;
        $subscriptionEndDate = null;
        $lastExpiredDate = null;
        $daysRemaining = 0;
        $subscription = null;
        $isNewRegistration = false;
        $transactionFor = null;

        // Get all subscriptions for this user, ordered by created_at desc
        $allSubscriptions = SubscriptionsKariah::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current year
        $currentYear = now()->year;
        $nextYear = $currentYear + 1;

        // ===== CHECK FOR ACTIVE SUBSCRIPTION =====
        $activeSubscription = $allSubscriptions
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();

        if ($activeSubscription) {
            // ACTIVE - Show active period
            $subscriptionStatus = 'active';
            $subscription = $activeSubscription;
            $subscriptionStartDate = $activeSubscription->start_date;
            $subscriptionEndDate = $activeSubscription->end_date;
            $daysRemaining = now()->diffInDays($activeSubscription->end_date, false);
            $transactionFor = $activeSubscription->transaction_for ?? 'renew';
            $isNewRegistration = ($transactionFor == 'new');
        } else {
            // ===== CHECK FOR PENDING PAYMENT (NEW) =====
            // Only get pending_payment with 'new' transaction_for
            $pendingPaymentSubscription = $allSubscriptions
                ->where('status', 'pending_payment')
                ->where('transaction_for', 'new')
                ->first();

            if ($pendingPaymentSubscription) {
                $subscriptionStatus = 'pending_payment';
                $subscription = $pendingPaymentSubscription;
                $subscriptionStartDate = $pendingPaymentSubscription->created_at;
                $transactionFor = 'new';
                $isNewRegistration = true;
            } else {
                // ===== CHECK FOR WAITING VERIFICATION (NEW) =====
                $waitingVerificationSubscription = $allSubscriptions
                    ->where('status', 'waiting_verification')
                    ->where('transaction_for', 'new')
                    ->first();

                if ($waitingVerificationSubscription) {
                    $subscriptionStatus = 'waiting_verification';
                    $subscription = $waitingVerificationSubscription;
                    $subscriptionStartDate = $waitingVerificationSubscription->created_at;
                    $transactionFor = 'new';
                    $isNewRegistration = true;
                } else {
                    // ===== CHECK FOR PENDING PAYMENT (RENEW) =====
                    // Get the most recent pending_payment for renewal
                    $pendingRenewalSubscription = $allSubscriptions
                        ->where('status', 'pending_payment')
                        ->where('transaction_for', 'renew')
                        ->first();

                    if ($pendingRenewalSubscription) {
                        $subscriptionStatus = 'pending_payment';
                        $subscription = $pendingRenewalSubscription;
                        $subscriptionStartDate = $pendingRenewalSubscription->created_at;
                        $transactionFor = 'renew';
                        $isNewRegistration = false;
                    } else {
                        // ===== CHECK FOR WAITING VERIFICATION (RENEW) =====
                        $waitingRenewalSubscription = $allSubscriptions
                            ->where('status', 'waiting_verification')
                            ->where('transaction_for', 'renew')
                            ->first();

                        if ($waitingRenewalSubscription) {
                            $subscriptionStatus = 'waiting_verification';
                            $subscription = $waitingRenewalSubscription;
                            $subscriptionStartDate = $waitingRenewalSubscription->created_at;
                            $transactionFor = 'renew';
                            $isNewRegistration = false;
                        } else {
                            // ===== CHECK FOR CANCELLED =====
                            $cancelledSubscription = $allSubscriptions
                                ->where('status', 'cancelled')
                                ->first();

                            if ($cancelledSubscription) {
                                $subscriptionStatus = 'cancelled';
                                $subscription = $cancelledSubscription;
                                $transactionFor = $cancelledSubscription->transaction_for ?? 'renew';
                                $isNewRegistration = ($transactionFor == 'new');
                            } else {
                                // ===== CHECK FOR EXPIRED =====
                                // Get the most recent expired subscription
                                $expiredSubscription = $allSubscriptions
                                    ->where('status', 'expired')
                                    ->where('end_date', '<', now())
                                    ->first();

                                if ($expiredSubscription) {
                                    $subscriptionStatus = 'expired';
                                    $subscription = $expiredSubscription;
                                    $lastExpiredDate = $expiredSubscription->end_date;
                                    $transactionFor = $expiredSubscription->transaction_for ?? 'renew';
                                    $isNewRegistration = ($transactionFor == 'new');
                                } else {
                                    // ===== CHECK FOR ANY SUBSCRIPTION =====
                                    $anySubscription = $allSubscriptions->first();

                                    if ($anySubscription) {
                                        $subscriptionStatus = $anySubscription->status ?? 'none';
                                        $subscription = $anySubscription;
                                        $transactionFor = $anySubscription->transaction_for ?? 'renew';
                                        $isNewRegistration = ($transactionFor == 'new');

                                        if ($subscriptionStatus == 'pending_payment' || $subscriptionStatus == 'waiting_verification') {
                                            $subscriptionStartDate = $anySubscription->created_at;
                                        }
                                    } else {
                                        $subscriptionStatus = 'none';
                                        $transactionFor = null;
                                        $isNewRegistration = false;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // ===== CHECK IF USER CAN RENEW =====
        // User can renew if:
        // 1. They have an active subscription that ends within 30 days
        // 2. OR they have an expired subscription (with penalty)
        // 3. OR they have no subscription at all (new registration)
        $canRenew = true;
        $renewalBlocked = false;
        $renewalMessage = '';

        // If user has an active subscription
        if ($activeSubscription) {
            $daysUntilExpiry = now()->diffInDays($activeSubscription->end_date, false);

            // Only allow renewal if within 30 days of expiry
            if ($daysUntilExpiry > 30) {
                $canRenew = false;
                $renewalBlocked = true;
                $renewalMessage = 'Keahlian anda masih aktif untuk ' . ceil($daysUntilExpiry) . ' hari. Pembaharuan hanya dibenarkan dalam tempoh 30 hari sebelum tarikh tamat.';
            }
        }

        // If user has an expired subscription, they can renew (with penalty)
        if ($subscriptionStatus == 'expired') {
            $canRenew = true;
        }

        // If user has no subscription, they can register new
        if ($subscriptionStatus == 'none') {
            $canRenew = true;
        }

        // If user has pending_payment or waiting_verification, they cannot make new transaction
        if (in_array($subscriptionStatus, ['pending_payment', 'waiting_verification'])) {
            $canRenew = false;
            $renewalBlocked = true;
            $renewalMessage = 'Anda mempunyai permohonan yang sedang diproses. Sila tunggu pengesahan sebelum membuat permohonan baru.';
        }

        // Calculate total amount for summary
        $totalAmount = $harga->bayaran_tahunan;
        $showRegistrationFee = false;
        $showPenalty = false;
        $registrationFee = $harga->yuran_pendaftaran ?? 0;
        $processingFee = $harga->yuran_processing ?? 0;
        $penaltyFee = $harga->yuran_penalti ?? 0;

        // For new registration with pending/waiting status
        if ($isNewRegistration && in_array($subscriptionStatus, ['pending_payment', 'waiting_verification'])) {
            $showRegistrationFee = true;
            $totalAmount += $registrationFee;
            $totalAmount += $processingFee;
        }

        // For expired subscriptions
        if ($subscriptionStatus == 'expired') {
            $showPenalty = true;
            $totalAmount += $penaltyFee;
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
            'daysRemaining',
            'subscription',
            'transactionFor',
            'isNewRegistration',
            'totalAmount',
            'showRegistrationFee',
            'showPenalty',
            'registrationFee',
            'processingFee',
            'penaltyFee',
            'canRenew',
            'renewalBlocked',
            'renewalMessage'
        ));
    }

    public function updateTransaction(Request $request, $subscriptionId)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::beginTransaction();

        try {

            $subscription = SubscriptionsKariah::with('payment')
                ->findOrFail($subscriptionId);

            if ($subscription->user_id != auth()->id()) {
                return back()->with('error', 'Anda tidak mempunyai akses.');
            }

            if ($subscription->status !== 'pending_payment') {
                return back()->with('error', 'Status tidak sah.');
            }

            if (!$subscription->payment) {
                return back()->with('error', 'Rekod pembayaran tidak dijumpai.');
            }

            // Upload receipt to S3 dengan nama fail & folder custom
            $receiptPath = null;

            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                $extension = $file->getClientOriginalExtension();

                $icNumber = auth()->user()->ic_number ?? auth()->id();
                $fileName = 'resit_' . $icNumber . '_' . now()->format('Ymd_His') . '.' . $extension;

                $storedPath = $file->storeAs(
                    'register/payment_receipt',
                    $fileName,
                    ['disk' => 's3', 'visibility' => 'public']
                );

                // Simpan URL S3 penuh, bukan path relatif
                $receiptPath = Storage::disk('s3')->url($storedPath);
            }

            // Update payment
            $subscription->payment->update([
                'status'       => 'waiting_verification',
                'receipt_path' => $receiptPath,
            ]);

            // Update subscription
            $subscription->update([
                'status' => 'waiting_verification',
            ]);

            DB::commit();

            return back()->with('success', 'Pembayaran berjaya dihantar.');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }


    public function storeTransaction(Request $request, User $user)
    {
        // Validate the incoming modal data
        $request->validate([
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'paid_at'        => 'required|date',
            'receipt'        => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
        ]);

        $receiptPath = null;
        $s3Path = null;

        // Handle the receipt upload to S3
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');

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
                'flow_type' => $request->flow_type, // Hidden input: income
                'paid_at'          => $request->paid_at,
                'receipt_path'       => $resitPath,
                'remarks'          => $request->remarks,
            ]);

            DB::commit();

            Log::info('Renewal transaction recorded', [
                'payment_id' => $payment->id,
                'user_id' => $user->id,
                'amount' => $request->amount,
                'receipt_path' => $resitPath
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
