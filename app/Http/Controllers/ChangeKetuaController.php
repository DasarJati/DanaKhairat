<?php
// app/Http/Controllers/ChangeKetuaController.php

namespace App\Http\Controllers;

use App\Models\AhliKariah;
use App\Models\Tanggungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ChangeKetuaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $ketuaList = AhliKariah::where('masjid_id', $user->masjid_id)
            ->where('is_ketua', 1)
            ->with(['tanggungan', 'user', 'masjid'])
            ->get();

        return view('khairat.changeketua', compact('ketuaList'));
    }

    public function showChangeForm($ahliId)
    {
        $ketua = AhliKariah::with(['tanggungan', 'user', 'masjid'])
            ->where('id', $ahliId)
            ->where('is_ketua', 1)
            ->firstOrFail();

        $potentialWives = $ketua->tanggungan()
            ->whereIn('hubungan', ['ISTERI', 'SUAMI'])
            ->get();

        return view('khairat.changeform', compact('ketua', 'potentialWives'));
    }

    public function changeKetua(Request $request, $ahliId)
    {
        $request->validate([
            'tanggungan_id' => 'required|exists:tanggungan,id',
            'confirm_swap'  => 'required|accepted',
            'new_email'                 => 'required|email|unique:users,email',
            'new_password'              => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            // Get current ketua
            $currentKetua = AhliKariah::where('id', $ahliId)
                ->where('is_ketua', 1)
                ->firstOrFail();

            // Get the tanggungan to become new ketua
            $tanggungan = Tanggungan::where('id', $request->tanggungan_id)
                ->where('ahli_id', $currentKetua->id)
                ->firstOrFail();

            $currentUser = $currentKetua->user;

            // Generate email for new user
            $newEmail    = $request->new_email;
            $newTel      = $request->new_tel;
            $newPassword = $request->filled('new_password')
                ? $request->new_password
                : $tanggungan->ic_number;

            
            // Create new User account for the tanggungan
            $newUser = User::create([
                'nama'       => $tanggungan->nama,
                'ic_number'  => $tanggungan->ic_number,
                'email'      => $newEmail,
                'password'   => Hash::make($newPassword),
                'masjid_id'  => $currentKetua->masjid_id,
                'role'       => 2,
                'status'     => 'active',
                'tel_number' => $newTel,
            ]);

            // Create new AhliKariah record for the new ketua
            AhliKariah::create([
                'user_id'   => $newUser->id,
                'masjid_id' => $currentKetua->masjid_id,
                'nama'      => $tanggungan->nama,
                'email'     => $newEmail,
                'ic'        => $tanggungan->ic_number,
                'notel'     => $newTel,
                'Jantina'   => $tanggungan->jantina,
                'alamat'    => $currentKetua->alamat,
                'status'    => 'active',
                'family_id' => $currentKetua->family_id,
                'is_ketua'  => '1',
            ]);

            // Transfer subscriptions to new user
            if ($currentKetua->subscriptions()->exists()) {
                foreach ($currentKetua->subscriptions as $subscription) {
                    $subscription->update(['user_id' => $newUser->id]);
                }
            }

            // Demote old ketua to regular ahli — keep record & user account active
            $currentKetua->is_ketua = '0';
            $currentKetua->save();

            $currentUser->status = 'inactive';

            // Delete the tanggungan record (now promoted to AhliKariah)
            $tanggungan->delete();

            DB::commit();

            return redirect()->route('change-ketua.index')
                ->with('success', 'Ketua berjaya ditukar kepada ' . $tanggungan->nama . '. Kata laluan lalai: ' . $tanggungan->ic_number);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Change Ketua failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Gagal menukar ketua: ' . $e->getMessage());
        }
    }

    private function generateEmail($name)
    {
        $baseEmail = strtolower(preg_replace('/[^a-zA-Z0-9]/', '.', $name));
        $email     = $baseEmail . '@example.com';

        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = $baseEmail . $counter . '@example.com';
            $counter++;
        }

        return $email;
    }
}
