<?php

namespace App\Http\Controllers;

use App\Models\AjkRegister;

use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function edit()
{
    $ajk = auth()->user(); // user AJK yang login

    return view('pic.edit', compact('ajk'));
}

    public function update(Request $request)
{
    $ajk = auth()->user(); // atau ambil dari session/login anda

    $request->validate([
        'alamat' => 'nullable|string|max:255',
    ]);

    // Update hanya field yang dibenarkan
    $ajk->update([
        'alamat' => $request->alamat,
        // nanti kalau nak tambah bandar/poskod pun boleh tambah sini
    ]);

    return back()->with('success', 'Maklumat berjaya dikemaskini.');
}

}
