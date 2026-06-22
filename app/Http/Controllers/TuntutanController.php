<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Tanggungan;
use App\Models\TuntutanKhairat;
use App\Models\TuntutanDokumen;
use App\Models\AhliKariah;
use Symfony\Component\HttpFoundation\Response;


class TuntutanController extends Controller
{
public function modalForm($ahli_id)
{
    $user = auth()->user();

    // 1️⃣ Cuba cari dalam tanggungan
    $ahli = $user->tanggungan()->find($ahli_id);

    // 2️⃣ Kalau tak jumpa → mungkin ketua keluarga
    if (!$ahli) {
        if ($user->id != $ahli_id) {
            abort(404);
        }

        // fake object untuk samakan struktur
        $ahli = (object) [
            'id' => $user->id,
            'nama' => $user->nama,
            'ic_number' => $user->ic_number,
            'hubungan' => 'Ahli Utama',
        ];
    }

    // 3️⃣ Cari draft (ikut ahli_id)
    $draft = TuntutanKhairat::where('user_id', $user->id)
        ->where('ahli_id', $ahli_id)
        ->where('status', 'DRAFT')
        ->first();

    // 4️⃣ Senarai pewaris
    $pewaris = collect();

    // kalau si mati bukan user → user jadi pewaris
    if ($ahli_id != $user->id) {
        $pewaris->push([
            'id' => 'user_' . $user->id,
            'nama' => $user->nama,
            'ic' => $user->ic_number,
            'hubungan' => 'Ketua Keluarga',
        ]);
    }

    // tanggungan lain
    foreach ($user->tanggungan as $t) {
        if ($t->id != $ahli_id) {
            $pewaris->push([
                'id' => 'tanggungan_' . $t->id,
                'nama' => $t->nama,
                'ic' => $t->ic_number,
                'hubungan' => $t->hubungan,
            ]);
        }
    }

    return view('user.borang', compact('user', 'ahli', 'draft', 'pewaris'));
}


public function store(Request $request)
{
    // 1️⃣ Validate input based on TuntutanKhairat model
    $data = $request->validate([
        'ahli_id'           => 'required|integer',
        'tarikh_kematian'   => 'required|date',
        'sebab_kematian'    => 'nullable|string',
        'sijil_kematian'    => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048',
        'ic_pewaris'        => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',  // This is laporan polis
        'bukti_bank'        => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',   // This is other documents
    ]);

    DB::beginTransaction();

    try {
        // Get the authenticated user
        $user = auth()->user();
        
        // Get AhliKariah to get masjid_id
        $ahliKariah = AhliKariah::where('user_id', $user->id)->first();
        
        // Determine the type (ahli or tanggungan)
        $type = 'AHLI'; // default
        $tanggunganId = null;
        
        // Check if this is a tanggungan
        $tanggungan = Tanggungan::find($request->ahli_id);
        if ($tanggungan) {
            $type = 'TANGGUNGAN';
            $tanggunganId = $tanggungan->id;
        }

        // Check for existing DRAFT or PENDING claim
        $existingClaim = TuntutanKhairat::where('user_id', $user->id)
            ->where('ahli_id', $request->ahli_id)
            ->whereIn('status', ['DRAFT', 'PENDING'])
            ->first();

        if ($existingClaim) {
            DB::rollBack();
            return back()->withErrors('Tuntutan untuk ahli ini sudah wujud dan sedang diproses.');
        }

        // Create the claim
        $tuntutan = TuntutanKhairat::create([
            'masjid_id'         => $ahliKariah->masjid_id ?? null,
            'user_id'           => $user->id,
            'ahli_id'           => $ahliKariah->id,
            'tanggungan_id'     => $tanggunganId,
            'type'              => $type,
            'date_death'        => $request->tarikh_kematian,
            'status'            => 'PROCESSING',
            'note'              => $request->sebab_kematian,
        ]);

        // 4️⃣ Define folder path
        // Get IC of deceased
        $deceasedIc = '';
        if ($type === 'AHLI' && $ahliKariah) {
            $deceasedIc = $ahliKariah->ic;
        } elseif ($type === 'TANGGUNGAN' && $tanggungan) {
            $deceasedIc = $tanggungan->ic_number;
        }
        
        $relativePath = 'uploads/tuntutan/' . $deceasedIc . '/' . $tuntutan->id;
        $folder = public_path($relativePath);

        // Create folder if it doesn't exist
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $time = time();

        // 5️⃣ Save files
        $sijilPath = null;
        $laporanPolisPath = null;
        $otherPath = null;

        // Sijil Kematian (required)
        if ($request->hasFile('sijil_kematian')) {
            $sijilExt = $request->file('sijil_kematian')->extension();
            $sijilName = $time . '_sijil_kematian.' . $sijilExt;
            $request->file('sijil_kematian')->move($folder, $sijilName);
            $sijilPath = $relativePath . '/' . $sijilName;
            
            // Update tuntutan with death certificate path
            $tuntutan->update(['death_certificate' => $sijilPath]);
        }

        // Laporan Polis (ic_pewaris in form)
        if ($request->hasFile('ic_pewaris')) {
            $polisExt = $request->file('ic_pewaris')->extension();
            $polisName = $time . '_laporan_polis.' . $polisExt;
            $request->file('ic_pewaris')->move($folder, $polisName);
            $laporanPolisPath = $relativePath . '/' . $polisName;
            
            // Update tuntutan with police report path
            $tuntutan->update(['police_report' => $laporanPolisPath]);
        }

        // Dokumen Lain (bukti_bank in form)
        if ($request->hasFile('bukti_bank')) {
            $otherExt = $request->file('bukti_bank')->extension();
            $otherName = $time . '_dokumen_lain.' . $otherExt;
            $request->file('bukti_bank')->move($folder, $otherName);
            $otherPath = $relativePath . '/' . $otherName;
            
            // Update tuntutan with other report path
            $tuntutan->update(['other_report' => $otherPath]);
        }

        DB::commit();

        return redirect()
            ->route('ahlikeluarga')
            ->with('success', 'Tuntutan kematian berjaya dihantar untuk proses kelulusan.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors('Ralat sistem: ' . $e->getMessage());
    }
}

}