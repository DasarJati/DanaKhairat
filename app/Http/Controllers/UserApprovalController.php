<?php

namespace App\Http\Controllers;

use App\Models\UserRegister;
use App\Models\User;
use App\Models\AhliKariah;
use App\Models\Waris;
use App\Models\Payment;
use App\Models\HargaKhairat;
use App\Models\masjid as Masjid;
use App\Models\Wallet;
use App\Mail\UserApprovedMail;
use App\Models\SubscriptionsKariah;
use App\Models\Tanggungan;

use App\Services\MembershipService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserApprovalController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->status;

        $applications = UserRegister::when($status !== null && $status !== '', function ($q) use ($status) {
            $q->where('approval_status', $status);
        })
            ->latest()
            ->get();

        return view('admin.user', [
            'applications' => $applications,
            'total'    => UserRegister::count(),
            'pending'  => UserRegister::where('approval_status', 'PENDING')->count(),
            'approved' => UserRegister::where('approval_status', 'APPROVED')->count(),
            'rejected' => UserRegister::where('approval_status', 'REJECTED')->count(),
        ]);
    }

    public function indexAJK(Request $request)
    {
        $status = $request->status;
        $search = $request->search;
        $masjid_id = auth()->user()->masjid_id; // Get current AJK's masjid_id

        $applications = UserRegister::where('masjid_id', $masjid_id)

            // STATUS FILTER
            ->when($status, function ($q) use ($status) {
                $q->where('approval_status', $status);
            })

            // SEARCH FILTER
            ->when($search, function ($q) use ($search) {

                $cleanSearch = str_replace(['-', ' '], '', $search);

                $q->where(function ($query) use ($search, $cleanSearch) {

                    // Search Nama
                    $query->where('nama', 'like', '%' . $search . '%')

                        // Search IC
                        ->orWhereRaw("
                    REPLACE(REPLACE(ic_number, '-', ''), ' ', '') 
                    LIKE ?
                ", ['%' . $cleanSearch . '%'])

                        // Search Alamat
                        ->orWhere('alamat', 'like', '%' . $search . '%');
                });
            })

            // PENDING FIRST
            ->orderByRaw("
        CASE 
            WHEN approval_status = 'PENDING' THEN 1
            WHEN approval_status = 'APPROVED' THEN 2
            WHEN approval_status = 'REJECTED' THEN 3
            ELSE 4
        END
    ")

            // LATEST FIRST
            ->latest()

            ->paginate(10)
            ->withQueryString();

        return view('Ahli_Kariah.List_Approve_Kariah', [
            'applications' => $applications,
            'total'    => UserRegister::where('masjid_id', $masjid_id)->count(),
            'pending'  => UserRegister::where('masjid_id', $masjid_id)->where('approval_status', 'PENDING')->count(),
            'approved' => UserRegister::where('masjid_id', $masjid_id)->where('approval_status', 'APPROVED')->count(),
            'rejected' => UserRegister::where('masjid_id', $masjid_id)->where('approval_status', 'REJECTED')->count(),
            'currentStatus' => $status,
            'currentSearch' => $search,
        ]);
    }

    public function indexApprove($id)
    {
        // Use the $id parameter from the route, not hardcoded value
        $user = UserRegister::with('tanggungan')->findOrFail($id);

        return view('Ahli_Kariah.Approve_Kariah', compact('user'));
    }

    // public function approveKariah($id, MembershipService $membershipService)
    // {
    //     $userRegister = UserRegister::findOrFail($id);
    //     $tanggunganRegisters = $userRegister->tanggungan;

    //     if ($userRegister->approval_status === 'APPROVED') {
    //         return back()->with('warning', 'User already approved');
    //     }

    //     // CHECK IF EMAIL OR IC ALREADY EXISTS WITH ROLE = 2 (AHLI)
    //     $existingUser = User::where(function ($query) use ($userRegister) {
    //         $query->where('ic_number', $userRegister->ic_number)
    //             ->orWhere('email', $userRegister->email);
    //     })
    //         ->where('role', 2) // Only check for Ahli role
    //         ->first();

    //     if ($existingUser) {
    //         $field = $existingUser->ic_number == $userRegister->ic_number ? 'No. IC' : 'Email';
    //         return back()->with('error', "$field sudah wujud dalam sistem sebagai AHLI. Sila gunakan $field lain.");
    //     }

    //     $userRegister->update([
    //         'approval_status' => 'APPROVED',
    //     ]);

    //     $user = User::create([
    //         'nama' => $userRegister->nama,
    //         'masjid_id' => $userRegister->masjid_id,
    //         'ic_number' => $userRegister->ic_number,
    //         'email' => $userRegister->email,
    //         'password' => $userRegister->password,
    //         'role' => '2',
    //         'status' => 'active',
    //         'tel_number' => $userRegister->telefon_bimbit,
    //     ]);

    //     // GENERATE FAMILY ID
    //     // Format: FAM-{masjid_id}-{year}{month}{day}-{random_4_digit}
    //     // Example: FAM-1-20250115-7842
    //     $familyId = $this->generateFamilyId($userRegister->masjid_id);

    //     $ahli = AhliKariah::create([
    //         'family_id' => $familyId, // Set the generated family_id
    //         'user_id' => $user->id,
    //         'masjid_id' => $userRegister->masjid_id,
    //         'nama' => $userRegister->nama,
    //         'email' => $userRegister->email,
    //         'ic' => $userRegister->ic_number,
    //         'notel' => $userRegister->telefon_bimbit,
    //         'jantina' => $userRegister->jantina,
    //         'alamat' => $userRegister->alamat,
    //         'status' => 'active',
    //         'is_ketua' => 1 // Ketua keluarga
    //     ]);

    //     // Create Tanggungan records for each dependent with the SAME family_id
    //     foreach ($tanggunganRegisters as $tanggunganRegister) {
    //         // Check if tanggungan IC already exists in the system
    //         $existingTanggungan = AhliKariah::where('ic', $tanggunganRegister->ic_number)->first();
    //         if ($existingTanggungan) {
    //             // Optionally skip or handle duplicate
    //             continue; // Skip this tanggungan if already exists
    //         }

    //         Tanggungan::create([
    //             'ahli_id' => $ahli->id,
    //             'family_id' => $familyId,
    //             'nama' => $tanggunganRegister->nama,
    //             'ic_number' => $tanggunganRegister->ic_number,
    //             'hubungan' => $tanggunganRegister->hubungan,
    //             'document_path' => $tanggunganRegister->document_path,
    //             'oku' => $tanggunganRegister->oku,
    //             'status' => 'active'
    //         ]);
    //     }

    //     $payment = Payment::create([
    //         'user_id' => $user->id,
    //         'masjid_id' => $userRegister->masjid_id,
    //         'amount' => $userRegister->amount,
    //         'payment_method' => 'Manual',
    //         'status' => 'pending',
    //         'receipt_path' => $userRegister->receipt_path,
    //         'remarks' => 'Register New Member',
    //         'type' => 'new_member',
    //         'flow_type' =>  'income',
    //         'paid_at' => null,

    //     ]);

    //     SubscriptionsKariah::create([
    //         'user_id' => $user->id,
    //         'masjid_id' => $userRegister->masjid_id,
    //         'start_date' => $membershipService->getStartDate(),
    //         'end_date'   => $membershipService->getEndDate(),
    //         'status' => 'pending_payment',
    //         'price' => $userRegister->amount,
    //         'payment_id' => $payment->id,
    //         'payment_type' => 'manual',
    //         'transaction_for' => 'new',
    //     ]);

    //     return back()->with('success', 'User berjaya diluluskan dengan family ID: ' . $familyId);
    // }

    public function approveKariah($id, MembershipService $membershipService)
    {
        $userRegister = UserRegister::findOrFail($id);
        $tanggunganRegisters = $userRegister->tanggungan;

        if ($userRegister->approval_status === 'APPROVED') {
            return back()->with('warning', 'User already approved');
        }

        // CHECK IF EMAIL OR IC ALREADY EXISTS WITH ROLE = 2 (AHLI)
        $existingUser = User::where(function ($query) use ($userRegister) {
            $query->where('ic_number', $userRegister->ic_number)
                ->orWhere('email', $userRegister->email);
        })
            ->where('role', 2) // Only check for Ahli role
            ->first();

        if ($existingUser) {
            $field = $existingUser->ic_number == $userRegister->ic_number ? 'No. IC' : 'Email';
            return back()->with('error', "$field sudah wujud dalam sistem sebagai AHLI. Sila gunakan $field lain.");
        }

        $userRegister->update([
            'approval_status' => 'APPROVED',
        ]);

        $user = User::create([
            'nama' => $userRegister->nama,
            'masjid_id' => $userRegister->masjid_id,
            'ic_number' => $userRegister->ic_number,
            'email' => $userRegister->email,
            'password' => Hash::make($userRegister->password),
            'role' => '2',
            'status' => 'active',
            'tel_number' => $userRegister->telefon_bimbit,
        ]);

        // GENERATE FAMILY ID
        // Format: FAM-{masjid_id}-{year}{month}{day}-{random_4_digit}
        // Example: FAM-1-20250115-7842
        $familyId = $this->generateFamilyId($userRegister->masjid_id);

        $ahli = AhliKariah::create([
            'family_id' => $familyId, // Set the generated family_id
            'user_id' => $user->id,
            'masjid_id' => $userRegister->masjid_id,
            'nama' => $userRegister->nama,
            'email' => $userRegister->email,
            'ic' => $userRegister->ic_number,
            'notel' => $userRegister->telefon_bimbit,
            'jantina' => $userRegister->jantina,
            'alamat' => $userRegister->alamat,
            'status' => 'active',
            'is_ketua' => 1 // Ketua keluarga
        ]);

        // Create Tanggungan records for each dependent with the SAME family_id
        $tanggunganCreated = [];
        foreach ($tanggunganRegisters as $tanggunganRegister) {
            // Check if tanggungan IC already exists in the system
            $existingTanggungan = AhliKariah::where('ic', $tanggunganRegister->ic_number)->first();
            if ($existingTanggungan) {
                // Optionally skip or handle duplicate
                continue; // Skip this tanggungan if already exists
            }

            $tanggungan = Tanggungan::create([
                'ahli_id' => $ahli->id,
                'family_id' => $familyId,
                'nama' => $tanggunganRegister->nama,
                'ic_number' => $tanggunganRegister->ic_number,
                'hubungan' => $tanggunganRegister->hubungan,
                'document_path' => $tanggunganRegister->document_path,
                'oku' => $tanggunganRegister->oku,
                'status' => 'active'
            ]);
            $tanggunganCreated[] = $tanggungan;
        }

        $payment = Payment::create([
            'user_id' => $user->id,
            'masjid_id' => $userRegister->masjid_id,
            'amount' => $userRegister->amount,
            'payment_method' => 'Manual',
            'status' => 'pending',
            'receipt_path' => $userRegister->receipt_path,
            'remarks' => 'Register New Member',
            'type' => 'new_member',
            'flow_type' => 'income',
            'paid_at' => null,
        ]);

        $subscription = SubscriptionsKariah::create([
            'user_id' => $user->id,
            'masjid_id' => $userRegister->masjid_id,
            'start_date' => $membershipService->getStartDate(),
            'end_date'   => $membershipService->getEndDate(),
            'status' => 'pending_payment',
            'price' => $userRegister->amount,
            'payment_id' => $payment->id,
            'payment_type' => 'manual',
            'transaction_for' => 'new',
        ]);

        $plainPassword = $userRegister->password;

        // SEND EMAIL NOTIFICATION
        try {
            $masjidName = $userRegister->masjid->nama ?? $userRegister->masjid->name ?? 'Masjid';
            $totalAmount = $userRegister->amount ?? 0;
            $tanggunganCount = count($tanggunganCreated);

            Log::info('Attempting to send approval email to: ' . $user->email);

            Mail::send('emails.kariah_approval', [
                'user' => $user,
                'userRegister' => $userRegister,
                'password' => $plainPassword,
                'masjidName' => $masjidName,
                'familyId' => $familyId,
                'tanggunganCount' => $tanggunganCount,
                'totalAmount' => $totalAmount,
                'payment' => $payment,
                'subscription' => $subscription,
            ], function ($message) use ($user, $masjidName) {
                $message->to($user->email)
                    ->subject('Kelulusan Pendaftaran Keahlian khairat');
            });

            Log::info('Approval email sent successfully to: ' . $user->email);
        } catch (\Exception $mailError) {
            // Log error but don't fail the approval process
            Log::error('Failed to send approval email: ' . $mailError->getMessage());
            Log::error('Email error trace: ' . $mailError->getTraceAsString());
            // Continue - we don't want to fail approval if email fails
        }

        return back()->with('success', 'User berjaya diluluskan dengan family ID: ' . $familyId);
    }

    /**
     * Generate a unique family ID
     * Format: FAM-{masjid_id}-{date}-{random_4_digit}
     */
    private function generateFamilyId($masjidId)
    {
        $date = now()->format('Ymd');
        $random = str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);

        // Check for uniqueness
        $familyId = "FAM-{$masjidId}-{$date}-{$random}";

        // Ensure uniqueness (loop until we find a unique one)
        $attempts = 0;
        while (AhliKariah::where('family_id', $familyId)->exists() && $attempts < 10) {
            $random = str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
            $familyId = "FAM-{$masjidId}-{$date}-{$random}";
            $attempts++;
        }

        return $familyId;
    }

    public function rejectKariah(Request $request, $id)
    {
        $userRegister = UserRegister::findOrFail($id);
        

        if ($userRegister->approval_status === 'REJECTED') {
            return back()->with('warning', 'User already rejected');
        }

        $userRegister->update([
            'approval_status' => 'REJECTED',
        ]);

        // SEND EMAIL NOTIFICATION
        try {
            $masjidName = $userRegister->masjid->nama ?? $userRegister->masjid->name ?? 'Masjid';

            // Get rejection reason from request if provided
            $rejectionReason = $request->input('reason', 'Permohonan anda tidak memenuhi syarat kelayakan.');

            Log::info('Attempting to send rejection email to: ' . $userRegister->email);

            Mail::send('emails.kariah_rejection', [
                'user' => $userRegister,
                'masjidName' => $masjidName,
                'rejectionReason' => $rejectionReason,
            ], function ($message) use ($userRegister, $masjidName) {
                $message->to($userRegister->email)
                    ->subject('Permohonan Keahlian Khairat Tidak Diluluskan');
            });

            Log::info('Rejection email sent successfully to: ' . $userRegister->email);
        } catch (\Exception $mailError) {
            Log::error('Failed to send rejection email: ' . $mailError->getMessage());
            Log::error('Email error trace: ' . $mailError->getTraceAsString());
        }

        return back()->with('success', 'User berjaya ditolak');
    }


    public function show($id)
    {
        $user = UserRegister::findOrFail($id);
        $payment = Payment::where('user_id', $user->id)->latest()->first();
        return view('user.approve', compact('user', 'payment'));
    }
    public function approve($id)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Ambil data pendaftaran
            $register = UserRegister::findOrFail($id);

            $amount = $register->amount ?? 0;

            // Elak approve dua kali
            if ($register->approval_status === 'APPROVED') {
                return back()->with('error', 'Permohonan ini telah diluluskan.');
            }



            // 2️⃣ Create USER (login table)
            $user = User::create([
                'nama'          => $register->nama,
                'ic_number'     => $register->ic_number,
                'tarikh_lahir'  => $register->tarikh_lahir,
                'umur'          => $register->umur,
                'jantina'    => $register->jantina,
                'bangsa'     => $register->bangsa,
                'statususer' => $register->statususer,
                'alamat'     => $register->alamat,
                'telefon_bimbit'     => $register->telefon_bimbit,
                'email'      => $register->email,
                'password'   => $register->password,
                'masjid_id'  => $register->masjid_id,
                'role'        => '2',
                'status'     => 'ACTIVE',
                'membership_start' => now(),
                'membership_end'   => now()->addYear(),
            ]);

            // 3️⃣ Create Ahli Khairat
            AhliKariah::create([
                'user_id'  => $user->id,
                'masjid_id' => $register->masjid_id,
                'ic'       => $register->ic_number,
                'notel'    => $register->telefon_bimbit,
                'alamat'   => $register->alamat,
                'status'   => 'active',
                'membership_start' => now(),
                'membership_end'   => now()->addYear(),
            ]);

            // 4️⃣ Create WARIS
            Waris::create([
                'user_id'          => $user->id,
                'nama'             => $register->waris_nama,
                'ic_number'        => $register->waris_ic,
                'alamat'           => $register->waris_alamat,
                'telefon_pejabat'  => $register->waris_telefon_pejabat,
                'telefon_bimbit'   => $register->waris_telefon_bimbit,
            ]);

            // 5️⃣ Update USER REGISTER
            $register->update([
                'approval_status' => 'APPROVED',
            ]);

            // 6️⃣ Update PAYMENT → SUCCESS
            Payment::where('user_id', $register->id) // Use user_register_id instead of user_id
                ->where('masjid_id', $register->masjid_id)
                ->update([
                    'status'  => 'SUCCESS',
                    'paid_at' => now(),
                    'user_id' => $register->id // Set the new user_id
                ]);

            // 7️⃣ Ambil Payment SUCCESS
            $payment = Payment::where('user_id', $register->id)
                ->where('masjid_id', $register->masjid_id)
                ->where('status', 'SUCCESS')
                ->latest()
                ->first();




            $wallet = Wallet::firstOrCreate(
                ['masjid_id' => $register->masjid_id],
                ['balance' => 0]
            );

            $wallet->balance += $amount;
            $wallet->save();

            // 8️⃣ Ambil Harga Khairat Masjid
            $harga = HargaKhairat::where('masjid_id', $register->masjid_id)->first();

            // 9️⃣ Ambil Nama Masjid
            $masjid = Masjid::find($register->masjid_id);

            // 🔟 Calculate Details
            $yuranPendaftaran = $harga->yuran_pendaftaran ?? 0;
            $bayaranTahunan   = $harga->bayaran_tahunan ?? 0;
            $wakalah          = 5.00;

            $totalAmount = $payment->amount ?? 0;

            Mail::to($user->email)->send(
                new UserApprovedMail(
                    $user,
                    $payment,
                    $harga,
                    $masjid,
                    $wakalah,
                    $totalAmount,

                )
            );

            DB::commit();

            return back()->with('success', 'Ahli berjaya diluluskan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with(
                'error',
                'Ralat semasa kelulusan: ' . dd($e->getMessage())
            );
        }
    }

    public function reject($id)
    {
        $register = UserRegister::findOrFail($id);

        if ($register->approval_status === 'REJECTED') {
            return back()->with('error', 'Permohonan ini telah ditolak.');
        }

        $register->update([
            'approval_status' => 'REJECTED',
        ]);

        return back()->with('success', 'Permohonan telah ditolak.');
    }
}
