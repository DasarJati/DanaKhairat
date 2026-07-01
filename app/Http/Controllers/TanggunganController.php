<?php

namespace App\Http\Controllers;

use App\Models\AhliKariah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Tanggungan;
use Carbon\Carbon;

class TanggunganController extends Controller
{
    public function create()
    {
        return view('user.tanggungan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama.*'         => 'required|string|max:255',
            'ic_number.*'    => 'required|string|max:20',
            'hubungan.*'     => 'required|in:ISTERI,SUAMI,ANAK,IBU,AYAH',
            'tarikh_lahir.*' => 'required|date',
        ]);

        $userId = Auth::id();
        $ahliID = AhliKariah::where('user_id', $userId)->first();
        $total  = count($request->nama);

        // Check if ahli already has a family_id, if not generate new one
        if (!empty($ahliID->family_id)) {
            $familyId = $ahliID->family_id;
        } else {
            // Get latest FAMxxxx and increment
            $latest = AhliKariah::whereNotNull('family_id')
                ->orderByDesc('family_id')
                ->value('family_id');

            if ($latest) {
                $number   = (int) substr($latest, 3); // strip 'FAM', cast to int
                $familyId = 'FAM' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $familyId = 'FAM0001';
            }

            // Save it back to the ahli record
            $ahliID->family_id = $familyId;
            $ahliID->save();
        }

        for ($i = 0; $i < $total; $i++) {

            $umur = Carbon::parse($request->tarikh_lahir[$i])->age;

            if (
                $request->hubungan[$i] === 'ANAK'
                && $umur > 24
                && !isset($request->oku[$i])
            ) {
                return back()
                    ->withErrors([
                        "hubungan.$i" => 'Anak mestilah berumur 24 tahun ke bawah kecuali OKU'
                    ])
                    ->withInput();
            }

            Tanggungan::create([
                'family_id' => $familyId,           // <-- attach family_id
                'ahli_id'   => $ahliID->id,
                'nama'      => $request->nama[$i],
                'ic_number' => $request->ic_number[$i],
                'hubungan'  => $request->hubungan[$i],
                'oku'       => isset($request->oku[$i]) ? 1 : 0,
            ]);
        }

        return redirect()
            ->route('ahlikeluarga.index')
            ->with('success', 'Semua tanggungan berjaya disimpan');
    }
}
