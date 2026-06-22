<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\masjid;
use App\Models\HargaKhairat;
use App\Models\Bank;
use App\Models\PolicyHeader;
use App\Models\PolicyMasjid;
use App\Models\SubscriptionsMasjid;
use App\Models\PackageOrders;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AjkMasjidController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $masjidId = $user->masjid_id;

    if ($user->role !== 1) {
        abort(403);
    }

    $masjid = Masjid::find($user->masjid_id);
    $harga = HargaKhairat::where('masjid_id', $user->masjid_id)->first();
    $bank = Bank::where('masjid_id', $user->masjid_id)->first();

    $policyHeader = PolicyHeader::with('sections')
        ->where('masjid_id', $masjidId)
        ->first();

    $activeSubscription = SubscriptionsMasjid::where('masjid_id', $user->masjid_id)
        ->where('status', 'active')
        ->where('start_date', '<=', Carbon::now())
        ->where('end_date', '>=', Carbon::now())
        ->with('package')
        ->first();

    // Add formatted dates for active subscription
    if ($activeSubscription) {
        $activeSubscription->formatted_start = Carbon::parse($activeSubscription->start_date)->format('d M Y');
        $activeSubscription->formatted_end = Carbon::parse($activeSubscription->end_date)->format('d M Y');
        $activeSubscription->days_left = Carbon::now()->diffInDays(Carbon::parse($activeSubscription->end_date), false);
    }

    $pendingOrders = PackageOrders::where('masjid_id', $user->masjid_id)
        ->where('status', 'pending')
        ->with('package')
        ->orderBy('created_at', 'desc')
        ->get();

    // Add formatted dates for pending orders
    foreach ($pendingOrders as $order) {
        $order->formatted_created = Carbon::parse($order->created_at)->format('d M Y, h:i A');
    }

    $approvedOrders = PackageOrders::where('masjid_id', $user->masjid_id)
        ->where('status', 'approved')
        ->with('package')
        ->orderBy('created_at', 'desc')
        ->get();

    // Add formatted dates for approved orders
    foreach ($approvedOrders as $order) {
        $order->formatted_created = Carbon::parse($order->created_at)->format('d M Y');
    }

    return view('masjid.index', compact(
        'user',
        'masjid',
        'harga',
        'bank',
        'policyHeader',
        'activeSubscription',
        'pendingOrders',
        'approvedOrders'
    ));
}

    public function edit()
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            abort(403);
        }

        $masjid = Masjid::find($user->masjid_id);

        return view('masjid.edit', compact('masjid'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'negeri' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'alamat2' => 'nullable|string|max:255',
            'poskod' => 'required|string|max:10',
            'bandar' => 'required|string|max:100',
            'kariah' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $masjid = Masjid::find($user->masjid_id);
            $masjid->update($request->all());

            DB::commit();

            return redirect()->route('masjid.index', ['tab' => 'masjid'])
                ->with('success', 'Maklumat masjid berjaya dikemaskini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengemaskini maklumat masjid: ' . $e->getMessage());
        }
    }

    public function updateHarga(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            abort(403);
        }

        $request->validate([
            'bayaran_tahunan' => 'required|numeric|min:0',
            'yuran_pendaftaran' => 'required|numeric|min:0',

        ]);

        try {
            DB::beginTransaction();

            HargaKhairat::updateOrCreate(
                ['masjid_id' => $user->masjid_id],
                [
                    'bayaran_tahunan' => $request->bayaran_tahunan,
                    'yuran_pendaftaran' => $request->yuran_pendaftaran,

                ]
            );

            DB::commit();


            return redirect()->route('masjid.index', ['tab' => 'payment'])
                ->with('success', 'Harga khairat berjaya dikemaskini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengemaskini harga khairat: ' . $e->getMessage());
        }
    }

    public function updateBank(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            abort(403);
        }

        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'nama_akaun' => 'required|string|max:255',
            'no_akaun' => 'required|string|max:50',
            'qr_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // Get old bank record FIRST before any changes
            $oldBank = Bank::where('masjid_id', $user->masjid_id)->first();

            $bankData = [
                'masjid_id' => $user->masjid_id,
                'nama_bank' => $request->nama_bank,
                'nama_akaun' => $request->nama_akaun,
                'no_akaun' => $request->no_akaun,
            ];

            // Handle QR code upload
            if ($request->hasFile('qr_path')) {
                $file = $request->file('qr_path');

                // Create directory if it doesn't exist
                $uploadPath = public_path('uploads/qr_ajk');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Move file to uploads/qr_ajk directory
                $file->move($uploadPath, $filename);

                // Save path in database (relative path from public)
                $bankData['qr_path'] = 'uploads/qr_ajk/' . $filename;

                // Delete old QR code if exists (NOW using the $oldBank we got earlier)
                if ($oldBank && $oldBank->qr_path) {
                    $oldFilePath = public_path($oldBank->qr_path);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            }

            Bank::updateOrCreate(
                ['masjid_id' => $user->masjid_id],
                $bankData
            );

            DB::commit();

            return redirect()->route('masjid.index', ['tab' => 'bank'])
                ->with('success', 'Maklumat bank berjaya dikemaskini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengemaskini maklumat bank: ' . $e->getMessage());
        }
    }

// You might also want to add this helper function in your controller or create a trait

    /**
     * Delete old QR code file
     */
    private function deleteOldQrCode($masjidId)
    {
        $bank = Bank::where('masjid_id', $masjidId)->first();
        if ($bank && $bank->qr_path) {
            $filePath = public_path($bank->qr_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    // Or if you want to compress/resize the image before saving:
    // if ($request->hasFile('qr_path')) {
    //     $file = $request->file('qr_path');
    //     $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //     $uploadPath = public_path('uploads/qr_ajk');

    //     // Resize image if needed (requires Intervention Image package)
    //     // Image::make($file)->resize(300, 300)->save($uploadPath . '/' . $filename);

    //     // Or just move the original
    //     $file->move($uploadPath, $filename);

    //     $bankData['qr_path'] = 'uploads/qr_ajk/' . $filename;
    // }

    public function updateSumbangan(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            abort(403);
        }

        $request->validate([
            'sumbangan_seorang' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            HargaKhairat::updateOrCreate(
                ['masjid_id' => $user->masjid_id],
                [
                    'sumbangan_seorang' => $request->sumbangan_seorang,
                    // Biarkan field lain dengan nilai sedia ada atau ambil dari request jika ada
                ]
            );

            DB::commit();


            return redirect()->route('masjid.index', ['tab' => 'payment'])
                ->with('success', 'Jumlah sumbangan berjaya dikemaskini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengemaskini jumlah sumbangan: ' . $e->getMessage());
        }
    }

    public function updatePolicyHeader(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'big_title' => 'required|string|max:255'
        ]);

        $policyHeader = PolicyHeader::where('masjid_id', $user->masjid_id)->first();

        if ($policyHeader) {
            $policyHeader->update([
                'big_title' => $request->big_title
            ]);
        }

        return redirect()->route('masjid.index', ['tab' => 'policy'])
            ->with('success', 'Tajuk polisi berjaya dikemaskini.');
    }

    public function updatePolicy(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $policy = PolicyMasjid::findOrFail($id);
        $policy->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return redirect()->route('masjid.index', ['tab' => 'policy'])
            ->with('success', 'Polisi berjaya dikemaskini.');
    }

    public function updateImage(Request $request)
    {
        $user = Auth::user();
 
        if ($user->role !== 1) {
            abort(403);
        }
 
        $request->validate([
            'image_path' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
 
        try {
            DB::beginTransaction();
 
            $masjid = Masjid::find($user->masjid_id);
 
            // Delete old image if it exists
            if ($masjid->image_path) {
                $oldFilePath = public_path($masjid->image_path);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
 
            // Save new image to public/masjid/image
            $uploadPath = public_path('masjid/image');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
 
            $file = $request->file('image_path');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
 
            $masjid->update([
                'image_path' => 'masjid/image/' . $filename,
            ]);
 
            DB::commit();
 
            return redirect()->route('masjid.index', ['tab' => 'masjid'])
                ->with('success', 'Gambar masjid berjaya dikemaskini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengemaskini gambar masjid: ' . $e->getMessage());
        }
    }
}
