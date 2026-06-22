<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ajks;
use App\Models\SubscriptionsMasjid;
use Carbon\Carbon;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Check if user is logged in
        if (!$user) {
            return redirect()->route('login');
        }
        
        // For AJK (role = 1)
        if ($user->role == 1) {
            // Check if user has masjid_id
            if (!$user->masjid_id) {
                // No masjid assigned, redirect to package page
                return redirect()->route('subscription.package')
                    ->with('error', 'Sila daftar langganan untuk masjid anda terlebih dahulu.');
            }
            
            // Check if there's an active subscription for this masjid
            $activeSubscription = SubscriptionsMasjid::where('user_id', $user->id)
                ->where('masjid_id', $user->masjid_id)
                ->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->latest()
                ->first();
            
            if (!$activeSubscription) {
                // No active subscription, redirect to package page
                return redirect()->route('subscription.package')
                    ->with('error', 'Langganan anda telah tamat tempoh atau tidak aktif. Sila langgan semula.');
            }
            
            // Check if subscription is expired
            if (Carbon::parse($activeSubscription->end_date)->isPast()) {
                return redirect()->route('subscription.package')
                    ->with('error', 'Langganan anda telah tamat tempoh. Sila langgan semula.');
            }
        }
        
        // For Ahli Khairat (role = 2)
        if ($user->role == 2) {
            // Check if user has masjid_id
            if (!$user->masjid_id) {
                return redirect()->route('subscription.package')
                    ->with('error', 'Sila daftar langganan untuk Ahli Khairat terlebih dahulu.');
            }
            
            // Check if user has active subscription using the helper method
            if (!$user->isSubscriptionActive()) {
                return redirect()->route('subscription.package')
                    ->with('error', 'Langganan anda telah tamat tempoh atau tidak aktif. Sila langgan semula.');
            }
        }
        
        return $next($request);
    }
}