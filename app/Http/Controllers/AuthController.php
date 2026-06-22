<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ResetPasswordMail;
use App\Models\AjkRegister;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\SubscriptionsMasjid;
use App\Models\SubscriptionsKariah;



class AuthController extends Controller
{
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

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
        $ajk  = AjkRegister::where('email', $request->email)->first();

        if (!$user && !$ajk) {
            return back()->withErrors([
                'email' => 'Email tidak dijumpai'
            ]);
        }

        $type = $user ? 'user' : 'ajk';

        $url = URL::temporarySignedRoute(
            'password.reset',
            now()->addMinutes(15),
            [
                'email' => $request->email,
                'type'  => $type
            ]
        );

        // 🔥 HANTAR EMAIL
        Mail::to($request->email)->send(
            new ResetPasswordMail($url)
        );

        return back()->with(
            'success',
            'Link reset password telah dihantar ke email anda.'
        );
    }


    public function showResetForm(Request $request)
    {
        return view('auth.resetpassword');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'type' => 'required'
        ]);

        if ($request->type === 'user') {
            $user = User::where('email', $request->email)->firstOrFail();
            $user->password = Hash::make($request->password);
            $user->save();
        }

        if ($request->type === 'ajk') {
            $ajk = AjkRegister::where('email', $request->email)->firstOrFail();
            $ajk->password = Hash::make($request->password);
            $ajk->save();
        }

        return redirect('/login')->with('success', 'Password berjaya ditukar');
    }
}
