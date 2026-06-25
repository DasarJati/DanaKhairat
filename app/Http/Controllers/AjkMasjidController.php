<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\masjid;
use App\Models\HargaKhairat;
use App\Models\Bank;
use App\Models\PolicyHeader;
use App\Models\PolicyMasjid;
use App\Models\SubscriptionsMasjid;
use App\Models\PackageOrders;
use Carbon\Carbon;


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

        // Handle QR code upload to S3
        if ($request->hasFile('qr_path')) {
            $file = $request->file('qr_path');
            
            if ($file->isValid()) {
                try {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // S3 path - using 'images/bank_masjid' folder
                    $s3Path = 'images/bank_masjid/' . $filename;
                    
                    // Upload to S3
                    $uploaded = Storage::disk('s3')->put($s3Path, file_get_contents($file), 'public');
                    
                    if (!$uploaded) {
                        throw new \Exception('Failed to upload QR code to S3');
                    }
                    
                    // Get the S3 URL
                    $qrPath = Storage::disk('s3')->url($s3Path);
                    
                    \Log::info('Bank QR code uploaded to S3', [
                        'masjid_id' => $user->masjid_id,
                        'path' => $s3Path,
                        'url' => $qrPath
                    ]);
                    
                    // Save path in database (S3 URL)
                    $bankData['qr_path'] = $qrPath;

                    // Delete old QR code from S3 if exists
                    if ($oldBank && $oldBank->qr_path) {
                        try {
                            $oldPath = $oldBank->qr_path;
                            
                            // If it's a full URL, extract the path
                            if (filter_var($oldPath, FILTER_VALIDATE_URL)) {
                                $parsedUrl = parse_url($oldPath);
                                $oldPath = ltrim($parsedUrl['path'], '/');
                            }
                            
                            // Delete from S3
                            if (Storage::disk('s3')->exists($oldPath)) {
                                Storage::disk('s3')->delete($oldPath);
                                \Log::info('Old bank QR code deleted from S3', [
                                    'masjid_id' => $user->masjid_id,
                                    'path' => $oldPath
                                ]);
                            }
                        } catch (\Exception $e) {
                            \Log::warning('Failed to delete old QR code from S3: ' . $e->getMessage());
                            // Continue execution even if delete fails
                        }
                    }
                    
                } catch (\Exception $e) {
                    \Log::error('S3 Upload Error (Bank QR): ' . $e->getMessage());
                    throw new \Exception('Gagal memuat naik kod QR ke pelayan. Sila cuba lagi.');
                }
            } else {
                throw new \Exception('Fail QR tidak sah atau rosak. Sila pilih fail yang lain.');
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
        \Log::error('Bank update failed: ' . $e->getMessage());
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
        
        if (!$masjid) {
            throw new \Exception('Masjid tidak dijumpai.');
        }
 
        // Delete old image from S3 if it exists
        if ($masjid->image_path) {
            try {
                // Extract the S3 path from the stored URL or path
                $oldPath = $masjid->image_path;
                
                // If it's a full URL, extract the path
                if (filter_var($oldPath, FILTER_VALIDATE_URL)) {
                    $parsedUrl = parse_url($oldPath);
                    $oldPath = ltrim($parsedUrl['path'], '/');
                }
                
                // Remove the bucket name from path if exists
                $oldPath = str_replace('images/masjid/', 'images/masjid/', $oldPath);
                
                // Delete from S3
                if (Storage::disk('s3')->exists($oldPath)) {
                    Storage::disk('s3')->delete($oldPath);
                    \Log::info('Old masjid image deleted from S3', [
                        'masjid_id' => $masjid->id,
                        'path' => $oldPath
                    ]);
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to delete old image from S3: ' . $e->getMessage());
                // Continue execution even if delete fails
            }
        }
 
        // Upload new image to S3
        $file = $request->file('image_path');
        
        if ($file->isValid()) {
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // S3 path - using 'images/masjid' folder
            $s3Path = 'images/masjid/' . $filename;
            
            // Upload to S3
            $uploaded = Storage::disk('s3')->put($s3Path, file_get_contents($file), 'public');
            
            if (!$uploaded) {
                throw new \Exception('Failed to upload image to S3');
            }
            
            // Get the S3 URL
            $imagePath = Storage::disk('s3')->url($s3Path);
            
            \Log::info('Masjid image uploaded to S3', [
                'masjid_id' => $masjid->id,
                'path' => $s3Path,
                'url' => $imagePath
            ]);
 
            // Update masjid with new image path
            $masjid->update([
                'image_path' => $imagePath,
            ]);
 
            DB::commit();
 
            return redirect()->route('masjid.index', ['tab' => 'masjid'])
                ->with('success', 'Gambar masjid berjaya dikemaskini.');
        } else {
            throw new \Exception('Fail imej tidak sah atau rosak.');
        }
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Masjid image update failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal mengemaskini gambar masjid: ' . $e->getMessage());
    }
}

/**
 * Upload file to S3
 */
private function uploadToS3($file, $folder, $filename = null)
{
    if (!$filename) {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    }
    
    $path = $folder . '/' . $filename;
    $uploaded = Storage::disk('s3')->put($path, file_get_contents($file), 'public');
    
    if (!$uploaded) {
        throw new \Exception('Failed to upload file to S3');
    }
    
    return [
        'path' => $path,
        'url' => Storage::disk('s3')->url($path)
    ];
}

/**
 * Delete file from S3
 */
private function deleteFromS3($path)
{
    if (empty($path)) {
        return;
    }
    
    // If it's a full URL, extract the path
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        $parsedUrl = parse_url($path);
        $path = ltrim($parsedUrl['path'], '/');
    }
    
    if (Storage::disk('s3')->exists($path)) {
        return Storage::disk('s3')->delete($path);
    }
    
    return false;
}


}
