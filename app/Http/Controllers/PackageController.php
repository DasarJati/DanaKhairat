<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\SubscriptionsMasjid;
use App\Models\User;
use Carbon\Carbon;

class PackageController extends Controller
{
    /**
     * Display the package comparison page.
     */
    public function index()
    {
        // Check if user is logged in and is AJK/Masjid Admin
        if (!Auth::check() || Auth::user()->role != 1) {
            return redirect()->route('login')->with('error', 'Sila log masuk sebagai AJK Masjid.');
        }

        $user = Auth::user();
        
        // Check if already has active subscription
        $activeSub = SubscriptionsMasjid::where('ajk_id', $user->id)
            ->where('masjid_id', $user->masjid_id)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->first();
            
        if ($activeSub) {
            return redirect()->route('pic.dashboard')
                ->with('info', 'Anda sudah mempunyai langganan aktif.');
        }

        $packages = [
            [
                'name' => 'BASIC',
                'code' => 'basic',
                'monthly_price' => 99,
                'per_member_price' => 10,
                'description' => 'Sesuai untuk institusi kecil hingga sederhana',
                'features' => [
                    ['text' => '200 - 500 ahli', 'checked' => false],
                    ['text' => '1 akaun Pengurus Sistem', 'checked' => false],
                    ['text' => 'Paparan Khas + Logo', 'checked' => false],
                    ['text' => 'Training percuma (online)', 'checked' => false],
                ],
                'max_members' => 500,
                'cta_text' => 'Serial Sekarang →',
                'highlighted' => false,
                'color' => 'warning',
                'border_color' => 'border-warning',
                'bg_color' => 'bg-warning',
                'text_color' => 'text-white',
            ],
            [
                'name' => 'PREMIUM',
                'code' => 'premium', 
                'monthly_price' => 149,
                'per_member_price' => 10,
                'description' => 'Untuk institusi besar dengan keperluan lengkap',
                'features' => [
                    ['text' => 'Tiada had Ahli', 'checked' => true],
                    ['text' => '3 akaun Pengurus', 'checked' => true],
                    ['text' => 'Website khas identiti masjid', 'checked' => true],
                    ['text' => '2 training setahun', 'checked' => true],
                ],
                'max_members' => 999999, // Unlimited
                'cta_text' => 'Serial Sekarang →',
                'highlighted' => true,
                'color' => 'primary',
                'border_color' => 'border-primary',
                'bg_color' => 'bg-primary',
                'text_color' => 'text-white',
            ]
        ];

        $terms = 'Terma & Syarat dikenakan';

        return view('pic.package', compact('packages', 'terms', 'activeSub'));
    }

    /**
     * Process package selection and redirect to payment
     */
    public function selectPackage(Request $request, $packageCode)
    {
        if (!Auth::check() || Auth::user()->role != 1) {
            return redirect()->route('login')->with('error', 'Sila log masuk sebagai AJK Masjid.');
        }

        $user = Auth::user();
        $packages = [
            'basic' => [
                'name' => 'BASIC',
                'price' => 99,
                'duration_months' => 1,
                'max_members' => 500,
                'color' => 'warning',
            ],
            'premium' => [
                'name' => 'PREMIUM',
                'price' => 149,
                'duration_months' => 1,
                'max_members' => 999999,
                'color' => 'primary',
            ]
        ];

        if (!isset($packages[$packageCode])) {
            return redirect()->route('pic.package')
                ->with('error', 'Pakej tidak ditemui.');
        }

        $package = $packages[$packageCode];

        // Store package selection in session for payment
        session([
            'selected_package' => [
                'code' => $packageCode,
                'name' => $package['name'],
                'price' => $package['price'],
                'duration_months' => $package['duration_months'],
                'max_members' => $package['max_members'],
                'color' => $package['color'],
                'ajk_id' => $user->id,
                'masjid_id' => $user->masjid_id,
                'user_email' => $user->email,
                'user_name' => $user->name,
            ]
        ]);

          // ✅ Redirect ikut pakej
    if ($packageCode === 'basic') {
        return redirect()->route('payment.basic');
    }

    return redirect()->route('payment.premium');

}

    /**
     * Show payment method selection
     */
    public function showPaymentMethod()
    {
        if (!Auth::check() || Auth::user()->role != 1) {
            return redirect()->route('login');
        }

        if (!session('selected_package')) {
            return redirect()->route('pic.package')
                ->with('error', 'Sila pilih pakej terlebih dahulu.');
        }

        $package = session('selected_package');

        return view('packages.payment-method', compact('package'));
    }

    /**
     * Process payment (simplified - integrate with your actual payment gateway)
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:stripe,paypal,bank_transfer',
        ]);

        if (!Auth::check() || Auth::user()->role != 1) {
            return redirect()->route('login');
        }

        $package = session('selected_package');
        $user = Auth::user();

        if (!$package) {
            return redirect()->route('pic.package')
                ->with('error', 'Sesi pakej telah tamat. Sila pilih semula.');
        }

        // Here you would integrate with actual payment gateway
        // For demonstration, we'll simulate successful payment

        $paymentStatus = 'completed'; // In real app, check from payment gateway response
        $transactionId = 'TXN_' . strtoupper(uniqid());

        if ($paymentStatus === 'completed') {
            // Create subscription record
            $subscription = new SubscriptionsMasjid();
            $subscription->ajk_id = $user->id;
            $subscription->masjid_id = $user->masjid_id;
            $subscription->package_name = $package['name'];
            $subscription->package_code = $package['code'];
            $subscription->max_members = $package['max_members'];
            $subscription->amount = $package['price'];
            $subscription->payment_method = $request->payment_method;
            $subscription->transaction_id = $transactionId;
            $subscription->start_date = Carbon::now();
            $subscription->end_date = Carbon::now()->addMonths($package['duration_months']);
            $subscription->status = 'active';
            $subscription->save();

            // Clear session
            session()->forget('selected_package');

            // Log the subscription
            Log::info('New masjid subscription created:', [
                'subscription_id' => $subscription->id,
                'ajk_id' => $user->id,
                'masjid_id' => $user->masjid_id,
                'package' => $package['name']
            ]);

            return redirect()->route('pic.dashboard')
                ->with('success', 'Langganan berjaya! Anda kini boleh mengakses sistem.');
        } else {
            return redirect()->route('payment.method')
                ->with('error', 'Pembayaran gagal. Sila cuba lagi.');
        }
    }

    /**
     * Show payment success page
     */
    public function paymentSuccess()
    {
        return view('packages.payment-success');
    }

    /**
     * Show payment failed page
     */
    public function paymentFailed()
    {
        return view('packages.payment-failed');
    }

    /**
     * Check subscription status (API endpoint)
     */
    public function checkSubscription()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $user = Auth::user();
        
        if ($user->role != 1) {
            return response()->json(['error' => 'User is not AJK/Masjid Admin'], 403);
        }

        $subscription = SubscriptionsMasjid::where('ajk_id', $user->id)
            ->where('masjid_id', $user->masjid_id)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'has_subscription' => !is_null($subscription),
            'subscription' => $subscription,
            'remaining_days' => $subscription ? Carbon::now()->diffInDays($subscription->end_date, false) : 0
        ]);
    }
}