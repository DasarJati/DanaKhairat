<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembayaranKhairat;

class PembayaranController extends Controller
{
public function update(Request $request, $id)
{
    $request->validate([
        'no_akaun'      => 'required|string|max:50',
        'jumlah_bayar'  => 'required|numeric|min:0',
        'resit_bayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $bayar = PembayaranKhairat::findOrFail($id);

    $ajk = auth()->guard('ajk')->user();
    $masjidId = $ajk->id;

    // Extension fail (jpg / png / pdf)
    $extension = $request->file('resit_bayaran')->getClientOriginalExtension();

    // Folder: public/uploads/resit/{masjid_id}
    $folderPath = public_path("uploads/resit/{$masjidId}");

    // Create folder jika belum wujud
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0755, true);
    }

    // Nama fail ikut ID pembayaran
    $filename = $bayar->tuntutan_id . '.' . $extension;

    // Simpan fail
    $request->file('resit_bayaran')->move($folderPath, $filename);

    // Path simpan dalam DB
    $path = "uploads/resit/{$masjidId}/{$filename}";

    $bayar->update([
        'no_akaun'      => $request->no_akaun,
        'jumlah_bayar'  => $request->jumlah_bayar,
        'resit_bayaran' => $path,
        'status'        => 'SELESAI',
        'dibayar_oleh'  => $masjidId,
        'dibayar_pada'  => now(),
    ]);

    return back()->with('success', 'Pembayaran berjaya direkodkan.');
}

}
