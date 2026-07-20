<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\SubscriptionsMasjid;
use App\Models\SubscriptionsKariah;



class AuthController extends Controller
{
    private const PASSWORD_WORDS = [
        'Anggerik', 'Awan', 'Berlian', 'Bintang', 'Cahaya', 'Delima',
        'Embun', 'Harimau', 'Jelita', 'Kenanga', 'Kereta', 'Langit',
        'Melur', 'Mutiara', 'Pelangi', 'Permata', 'Rimba', 'Samudera',
        'Suria', 'Teratai',
    ];

    public function showLogin()
    {
        return view('pic.login'); // blade kau
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user) {
    //         return back()->with('error', 'Email tidak ditemui dalam sistem.');
    //     }

    //     if (!Hash::check($request->password, $user->password)) {
    //         return back()->with('error', 'Kata laluan tidak tepat.');
    //     }


    //     if ($user->status !== 'active') {
    //         return back()->with('error', 'Akaun anda belum aktif.');
    //     }

    //     // Use default web guard for ALL users
    //     Auth::login($user);

    //     ActivityLog::create([
    //         'user_id' => auth()->id(),
    //         'entity_type' => 'login',
    //         'description' => 'User logged in successfully'
    //     ]);


    //     // Redirect berdasarkan role
    //     switch ($user->role) {
    //         case 0: // Super Admin
    //             return redirect()->route('admin.dashboard');

    //         case 1: // AJK / Masjid Admin
    //             // ✅ CHECK SUBSCRIPTION MASJID
    //             // $masjidSub = \App\Models\SubscriptionsMasjid::where('ajk_id', $user->id)
    //             //     ->where('masjid_id', $user->masjid_id)
    //             //     ->where('status', 'active')
    //             //     ->whereDate('end_date', '>=', now())
    //             //     ->first();

    //             // Log::info('Masjid subscription check:', [
    //             //     'user_id' => $user->id, 
    //             //     'masjid_id' => $user->masjid_id, 
    //             //     'masjidSub' => $masjidSub
    //             // ]);

    //             // // If no active subscription, redirect to packages page
    //             // if (!$masjidSub) {
    //             //     return redirect()->route('package.index')
    //             //         ->with('error', 'Sila langgan package masjid terlebih dahulu.');
    //             // }

    //             // If has active subscription, go to dashboard
    //             return redirect()->route('pic.dashboard');

    //         case 2: // Ahli Khairat
    //             return redirect()->route('user.dashboard');

    //         default:
    //             return back()->with('error', 'Role tidak dikenali.');
    //     }
    // }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'login_type' => 'required'
        ]);

        // Map login_type to role
        $roleMap = [
            'admin' => [0, 1],
            'ahli' => [2]
        ];

        $allowedRoles = $roleMap[$request->login_type] ?? [];

        if (empty($allowedRoles)) {
            return back()->with('error', 'Jenis log masuk tidak sah.');
        }

        // Find user by email AND allowed roles
        $user = User::where('email', $request->email)
            ->whereIn('role', $allowedRoles)
            ->first();

        if (!$user) {
            return back()->with('error', 'Akaun dengan e-mel ini tidak ditemui untuk log masuk sebagai ' .
                ($request->login_type == 'ahli' ? 'Ahli' : 'Admin/PIC') . '.');
        }

        // Wrong password
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Kata laluan salah. Sila cuba lagi.');
        }

        // Account inactive
        if ($user->status !== 'active') {
            return back()->with('error', 'Akaun anda tidak aktif. Sila hubungi pentadbir.');
        }

        // Login
        Auth::login($user);

        // ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'entity_type' => 'login',
            'description' => 'Log masuk ke sistem'
        ]);

        // =========================
        // REDIRECT
        // =========================

        switch ($user->role) {
            case 0:
                return redirect()->route('admin.dashboard');
            case 1:
                return redirect()->route('pic.dashboard');
            case 2:
                return redirect()->route('user.dashboard');
            default:
                Auth::logout();
                return back()->with('error', 'Peranan pengguna tidak dikenal pasti.');
        }
    }


    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // ke halaman login selepas logout
    }

    //FORGOT PASSWORD

    public function showEmailForm()
    {
        return view('auth.forgotpassword');
    }

    public function resetPasswordAndEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'Alamat e-mel diperlukan.',
            'email.email' => 'Sila masukkan alamat e-mel yang sah.',
        ]);

        $email = Str::lower(trim($validated['email']));
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Alamat e-mel ini tidak dijumpai dalam sistem.'
            ])->withInput();
        }

        $temporaryPassword = $this->generateTemporaryPassword();

        try {
            DB::transaction(function () use ($user, $email, $temporaryPassword) {
                $user->password = Hash::make($temporaryPassword);
                $user->save();

                Mail::to($email)->send(new ResetPasswordMail($temporaryPassword));
            });
        } catch (\Throwable $exception) {
            Log::error('Failed to reset and email a temporary password.', [
                'user_id' => $user->id,
                'exception' => $exception,
            ]);

            return back()->withErrors([
                'email' => 'Kata laluan tidak dapat ditetapkan semula sekarang. Sila cuba lagi.'
            ])->withInput();
        }

        // 🔥 HANTAR EMAIL
        return back()->with(
            'success',
            'Kata laluan baharu telah dihantar ke e-mel anda.'
        );
    }

    private function generateTemporaryPassword(): string
    {
        $symbols = '!@#$%&*+-=?{}';
        $word = self::PASSWORD_WORDS[random_int(0, count(self::PASSWORD_WORDS) - 1)];
        $firstSymbol = $symbols[random_int(0, strlen($symbols) - 1)];
        $secondSymbol = $symbols[random_int(0, strlen($symbols) - 1)];
        $numbers = random_int(100, 999);

        return $word.$firstSymbol.$secondSymbol.$numbers;
    }

}
