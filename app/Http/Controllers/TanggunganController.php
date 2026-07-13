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
            // Generate new family ID using the same method as approval
            $familyId = $this->generateFamilyId($ahliID->masjid_id);

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
                'family_id' => $familyId,
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

    private function generateFamilyId($masjidId, $useOldFormat = false)
{
    if ($useOldFormat) {
        // Old format: FAM0001, FAM0002, etc.
        $latest = AhliKariah::whereNotNull('family_id')
            ->where('family_id', 'NOT LIKE', 'FAM-%') // Exclude new format
            ->orderByDesc('family_id')
            ->value('family_id');

        if ($latest) {
            $number = (int) substr($latest, 3);
            $familyId = 'FAM' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $familyId = 'FAM0001';
        }
        
        return $familyId;
    }

    // New format: FAM-{masjid_id}-{year}{month}{day}-{random_4_digit}
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
}
