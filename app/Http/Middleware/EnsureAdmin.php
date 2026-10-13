<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    /**
     * Hanya Super Admin (role 0) dibenarkan mengakses laluan admin.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.form');
        }

        if ((int) $user->role !== 0) {
            abort(403, 'Anda tidak dibenarkan mengakses halaman ini.');
        }

        return $next($request);
    }
}
