<?php

namespace App\Http\Controllers;


use App\Models\Payment;
use App\Models\Masjid;
use App\Models\Bank;
use App\Models\TransferBack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class AdminwalletController extends Controller
{
public function wallet(Request $request)
{
    $wakalahFee = 5;
    $selectedMasjid = $request->masjid_id;

    // Base query (guna untuk semua kiraan + table)
    $baseQuery = Payment::when($selectedMasjid, function ($q) use ($selectedMasjid) {
        $q->where('masjid_id', $selectedMasjid);
    })
    ->whereIn('type', ['New Member', 'Renew Membership']);
    

    // 📊 CARD DATA
    $totalTransaksi = (clone $baseQuery)->count();

    $totalTransfer = (clone $baseQuery)
        ->sum(DB::raw("amount - $wakalahFee"));
    
    

    $totalWakalah = $totalTransaksi * $wakalahFee;

    // 📋 TABLE DATA
    $payments = (clone $baseQuery)
    ->with('masjid')
    ->whereIn('type', ['New Member', 'Renew Membership'])
    ->latest()
    ->get();

    // 📌 DROPDOWN DATA
    $masjids = Masjid::where('status', 'active')
        ->orderBy('nama')
        ->get();

    return view('admin.wallet', compact(
        'totalTransaksi',
        'totalTransfer',
        'totalWakalah',
        'payments',
        'wakalahFee',
        'masjids',
        'selectedMasjid'
    ));
}

public function showUpload($id)
{
    $payment = Payment::with('masjid')->findOrFail($id);

    // Ambil bank ikut masjid payment tu
    $bank = Bank::where('masjid_id', $payment->masjid_id)->first();

    if (!$bank) {
        return back()->with('error', 'Maklumat bank belum ditetapkan untuk masjid ini.');
    }

    $transferAmount = $payment->amount - 3;
    return view('admin.upload', compact('payment', 'bank', 'transferAmount'));

}

public function uploadResit(Request $request, $id)
{
   
    $request->validate([
        'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $payment = Payment::findOrFail($id);

    DB::beginTransaction();

    try {

        // 1. Simpan file ke public/upload/transferbackresit
        $file = $request->file('receipt');

        $filename = time().'_'.$file->getClientOriginalName();

        $file->move(public_path('upload/transferbackresit'), $filename);

        $path = 'upload/transferbackresit/' . $filename;


        // 2. Update table payments
        $payment->update([
            'user_id' => $payment->user_id,
            'masjid_id' => $payment->masjid_id,
            'status' => 'PAID'
        ]);


        // 3. Insert ke table transfer_back

        $wakalah = 5;

        TransferBack::create([
            'user_id' => $payment->user_id,
            'masjid_id' => $payment->masjid_id,
            'name' => $payment->name ?? 'Transfer',
            'amount' => $payment->amount,
            'final_amount' => $payment->amount - $wakalah,
            'paid_by' => 'null',
            'resit_path' => $path,
        ]);

        DB::commit();
        return redirect()->route('admin.wallet')
            ->with('success', 'Resit berjaya dimuat naik & direkodkan.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', $e->getMessage());
    }
}
}