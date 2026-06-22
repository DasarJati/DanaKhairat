<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Models\Ajks;
use App\Models\HargaKhairat;
use App\Models\Bank;

class AjkProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // pastikan hanya AJK (role = 1)
        if ($user->role !== 1) {
            abort(403);
        }

        // dapatkan rekod AJK
        $ajk = Ajks::where('user_id', $user->id)->first();

        // dapatkan harga khairat & bank ikut masjid_id
        $harga = HargaKhairat::where('masjid_id', $user->masjid_id)->first();
        $bank  = Bank::where('masjid_id', $user->masjid_id)->first();

        // pass semua ke view
        return view('pic.profile', compact('user', 'ajk', 'harga', 'bank'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {   // 🔹 integer 1
            abort(403);
        }

        $request->validate([
            'position' => 'required|string',
            'ic'       => 'required|string',
        ]);

        Ajks::updateOrCreate(
            ['user_id' => $user->id],
            [
                'masjid_id' => $user->masjid_id,
                'position'  => $request->position,
                'ic'        => $request->ic,
            ]
        );

        return redirect()->route('ajk.profile')
            ->with('success', 'Profil AJK berjaya dikemaskini.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'], // Built-in Laravel rule to check current password
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.current_password' => 'Kata laluan semasa anda salah.',
            'password.confirmed' => 'Pengesahan kata laluan tidak sepadan.',
            'password.min' => 'Kata laluan baru mestilah sekurang-kurangnya 8 aksara.',
        ]);

        $user = Auth::user();

        // Update the password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Kata laluan anda telah berjaya dikemaskini!');
    }
}
