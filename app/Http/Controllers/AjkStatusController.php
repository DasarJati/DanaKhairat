<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AjkRegister;

class AjkStatusController extends Controller
{
        public function index()
    {
        return view('pic.status');
    }

    public function check(Request $request)
    {
        $request->validate([
            'keyword' => 'required'
        ]);

        $ajk = AjkRegister::where('nama_masjid', 'LIKE', "%{$request->keyword}%")
                ->orWhere('email', $request->keyword)
                ->first();

        // ✅ kalau tak jumpa — bagi message
        if (!$ajk) {
            return redirect()->route('check.status')
                ->with('not_found', 'Rekod tidak dijumpai. Sila semak semula.');
        }

        // ✅ kalau jumpa — hantar data
        return view('pic.status', compact('ajk'));
    }

}