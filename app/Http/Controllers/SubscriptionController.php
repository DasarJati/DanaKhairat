<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Package;
use App\Models\Bank;
use App\Models\PackageOrders;
use App\Models\SubscriptionsMasjid;
use App\Models\Ajks;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function showPackage()
    {
        $package = Package::all();
        $bank = Bank::where('id', 1)->first();

        $activeTab = 'package';
        $userOrders = collect();
        // $hasActiveSubscription = false;
        $pendingOrder = null;
        $rejectedOrders = collect();
        $hasMasjid = false;
        $approvedOrder = null;
        $hasAnyOrder = false;
        $activeSubscription = null; // ADD THIS
        $hasActiveSubscription = $activeSubscription ? true : false;

        if (Auth::check()) {
            $ajk = Ajks::where('user_id', Auth::id())->first();

            if ($ajk && $ajk->masjid_id) {
                $hasMasjid = true;

                $userOrders = PackageOrders::where('masjid_id', $ajk->masjid_id)
                    ->with('package')
                    ->orderBy('created_at', 'desc')
                    ->get();

                $hasAnyOrder = $userOrders->isNotEmpty();
                $pendingOrder = $userOrders->where('status', 'pending')->first();
                $approvedOrder = $userOrders->where('status', 'approved')->first();
                $rejectedOrders = $userOrders->where('status', 'rejected');

                // CHECK ACTUAL SUBSCRIPTION RECORD
                $activeSubscription = SubscriptionsMasjid::where('masjid_id', $ajk->masjid_id)
                    ->where('status', 'active')
                    ->where('start_date', '<=', Carbon::now())
                    ->where('end_date', '>=', Carbon::now())
                    ->first();

                $expiredSubscription = SubscriptionsMasjid::where('masjid_id', $ajk->masjid_id)
                    ->where('end_date', '<', Carbon::now())
                    ->latest('end_date')
                    ->first();

                $hasActiveSubscription = $activeSubscription ? true : false;

                if ($hasActiveSubscription) {
                    return redirect()->route('pic.dashboard')
                        ->with('info', 'Masjid anda sudah mempunyai langganan aktif.');
                }

                // if (!$hasActiveSubscription && $expiredSubscription) {
                //     return redirect()->route('subscription.status')
                //         ->with('expired', true);
                // }

                if ($hasActiveSubscription) {
                    $activeTab = 'status';
                } elseif ($pendingOrder) {
                    $activeTab = 'status';
                } elseif ($approvedOrder) {
                    $activeTab = 'status';
                }
            }
        }

        return view('Subscription_Package.buy_package', compact(
            'package',
            'bank',
            'userOrders',
            'hasActiveSubscription',
            'pendingOrder',
            'rejectedOrders',
            'approvedOrder',
            'hasMasjid',
            'activeTab',
            'hasAnyOrder',
            'activeSubscription' // ADD THIS
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $package = Package::findOrFail($request->package_id);

            // Get AJK info to get masjid_id
            $ajk = Ajks::where('user_id', Auth::id())->first();

            if (!$ajk || !$ajk->masjid_id) {
                return redirect()->back()->with('error', 'Anda tidak mempunyai masjid yang didaftarkan. Sila hubungi admin.');
            }

            // Check if masjid already has pending order
            $existingPending = PackageOrders::where('masjid_id', $ajk->masjid_id)
                ->where('status', 'pending')
                ->first();

            if ($existingPending) {
                return redirect()->back()->with('error', 'Masjid anda masih mempunyai pesanan yang belum diproses. Sila tunggu pengesahan dari pihak admin.');
            }

            // ✅ CORRECT — check actual active subscription (not expired)
            $existingActive = SubscriptionsMasjid::where('masjid_id', $ajk->masjid_id)
                ->where('status', 'active')
                ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now())
                ->first();

            if ($existingActive) {
                return redirect()->back()->with('error', 'Masjid anda sudah mempunyai langganan aktif.');
            }

            // Handle file upload
            $resitPath = null;

            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                $timestamp = now()->format('Ymd_His');
                $masjidId = $ajk->masjid_id ?? '000000';
                $extension = $file->getClientOriginalExtension();
                $filename = "resit_masjid_{$masjidId}_{$timestamp}.{$extension}";

                $uploadPath = public_path('pembayaran/resit_pembayaran');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $filename);
                $resitPath = 'pembayaran/resit_pembayaran/' . $filename;
            }

            // Create package order with masjid_id
            PackageOrders::create([
                'user_id' => Auth::id(),
                'masjid_id' => $ajk->masjid_id,
                'package_id' => $request->package_id,
                'amount' => $package->price,
                'receipt_path' => $resitPath,
                'status' => 'pending',
                'approved_by' => null,
            ]);

            DB::commit();

            return redirect()->route('subscription.package', ['tab' => 'status'])->with('success', 'Pesanan langganan untuk masjid anda telah diterima. Sila tunggu pengesahan dari pihak admin dalam masa 1-3 hari bekerja.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Subscription store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ralat berlaku. Sila cuba lagi.')->withInput();
        }
    }

   public function showStatus()
{
    $ajk = Ajks::where('user_id', Auth::id())->first();

    if (!$ajk || !$ajk->masjid_id) {
        return redirect()->route('subscription.package');
    }

    // Get bank info
    $bank = Bank::where('id', 1)->first();

    // Get the most recent expired subscription
    $expiredSubscription = SubscriptionsMasjid::where('masjid_id', $ajk->masjid_id)
        ->where(function ($q) {
            $q->where('status', 'expired')
                ->orWhere('end_date', '<', Carbon::now());
        })
        ->with('package')
        ->orderBy('end_date', 'desc')
        ->first();

    // If actually active, redirect to package page
    $activeSubscription = SubscriptionsMasjid::where('masjid_id', $ajk->masjid_id)
        ->where('status', 'active')
        ->where('start_date', '<=', Carbon::now())
        ->where('end_date', '>=', Carbon::now())
        ->first();

    if ($activeSubscription) {
        return redirect()->route('subscription.package');
    }

    // All orders for this masjid
    $orderHistory = PackageOrders::where('masjid_id', $ajk->masjid_id)
        ->with('package')
        ->orderBy('created_at', 'desc')
        ->get();

    // Check for pending renewal
    $pendingOrder = $orderHistory->where('status', 'pending')->first();

    // Get the most recent approved order
    $lastApprovedOrder = $orderHistory->where('status', 'approved')->first();
    
    // Check if there's been any approved subscription before
    $hasApprovedSubscription = PackageOrders::where('masjid_id', $ajk->masjid_id)
        ->where('status', 'approved')
        ->exists();

    // DETECT WHICH PACKAGE WAS LAST USED
    $lastPackage = null;
    
    // Priority 1: Get from most recent approved order
    if ($lastApprovedOrder && $lastApprovedOrder->package) {
        $lastPackage = $lastApprovedOrder->package;
    } 
    // Priority 2: Get from expired subscription
    elseif ($expiredSubscription && $expiredSubscription->package) {
        $lastPackage = $expiredSubscription->package;
    }
    // Priority 3: Get from any order (pending or other status) as fallback
    elseif ($orderHistory->isNotEmpty() && $orderHistory->first()->package) {
        $lastPackage = $orderHistory->first()->package;
    }

    // Determine if this is a renewal
    // A user is considered for renewal if:
    // 1. They have an expired subscription, OR
    // 2. They have previous approved orders, OR
    // 3. They have a pending order
    $isRenewal = ($expiredSubscription || $hasApprovedSubscription || $pendingOrder) ? true : false;

    // Get the appropriate price based on package
    $renewalPrice = 0;
    $newPrice = 0;
    
    if ($lastPackage) {
        $renewalPrice = $lastPackage->renewal_price ?? $lastPackage->price;
        $newPrice = $lastPackage->price;
    }

    // Set the final price based on whether it's a renewal or new
    $finalPrice = $isRenewal ? $renewalPrice : $newPrice;

    // Set displayAmount (same as finalPrice)
    $displayAmount = $finalPrice;

    // Debug information (remove in production)
    \Log::info('Subscription Status Debug', [
        'masjid_id' => $ajk->masjid_id,
        'last_package' => $lastPackage ? $lastPackage->name : 'No package found',
        'is_renewal' => $isRenewal,
        'final_price' => $finalPrice,
        'renewal_price' => $renewalPrice,
        'new_price' => $newPrice,
        'has_expired' => $expiredSubscription ? true : false,
        'has_approved' => $hasApprovedSubscription,
        'has_pending' => $pendingOrder ? true : false
    ]);

    return view('Subscription_Package.subscription_status', compact(
        'expiredSubscription',
        'orderHistory',
        'lastApprovedOrder',
        'lastPackage',
        'pendingOrder',
        'bank',
        'isRenewal',
        'renewalPrice',
        'newPrice',
        'finalPrice',
        'displayAmount',
        'hasApprovedSubscription'
    ));
} 

public function pay(Request $request)
{
    $request->validate([
        'package_id' => 'required|exists:pakej,id',
    ]);

    $package = Package::find($request->package_id);
    $user = Auth::user();
    $ajk = Ajks::where('user_id', $user->id)->first();

$billData = [
    // Core Auth
    'userSecretKey'            => env('TOYYIBPAY_SECRET_KEY'),
    'categoryCode'             => env('TOYYIBPAY_CATEGORY_CODE'),
    
    // Bill Info
    'billName'                 => 'Pembaharuan Langganan',
    'billDescription'          => 'Bayaran pakej ' . $package->name,
    'billPriceSetting'         => 1,
    'billPayorInfo'            => 1,
    'billAmount'               => (int)($package->price * 100),
    'billReturnUrl'            => route('subscription.package'),
    'billCallbackUrl'          => route('subscription.callback'),
    'billExternalReferenceNo'  => 'SUBSCRIPTION-' . time(),
    'billTo'                   => $user->nama,
    'billEmail'                => $user->email,
    'billPhone'                => $ajk->notel, // Ensure this is a string
    
    // Missing parameters from sample (Crucial for routing)
    'billSplitPayment'         => 0,
    'billSplitPaymentArgs'     => '',
    'billPaymentChannel'       => '0',
    'billContentEmail'         => 'Terima kasih kerana melanggan!',
    'billChargeToCustomer'     => 1,
    'billExpiryDate'           => '', // Leave empty if not used
    'billExpiryDays'           => '', // Leave empty if not used
    'enableDuitNowQR'          => 0,
    'chargeDuitNowQR'          => 0
];

$response = Http::asForm()->post('https://toyyibpay.com/index.php/api/createBill', $billData);

// dd($response->json());
    // 3. Handle the response
    if ($response->successful()) {
        $billCode = $response->json()[0]['BillCode'] ?? null;

        if ($billCode) {
            // Optional: Save the pending order in your database here first
            
            // ✅ INSERT DATA HERE BEFORE REDIRECTING
            PackageOrders::create([
                'user_id'      => $user->id,
                'masjid_id'    => $ajk->masjid_id,
                'package_id'   => $package->id,
                'amount'       => $package->price,
                'bill_code'    => $billCode,        // Save this to match in callback
                'status'       => 'pending',
                'payment_type' => 'online',       
                'approved_by'  => null,
                'order_type' => 'New',         // To distinguish from manual upload

            ]);
            // Redirect user to ToyyibPay Payment Page
            return redirect('https://toyyibpay.com/' . $billCode);
            // Use https://dev.toyyibpay.com/ for sandbox
        }
    }

    return redirect()->back()->with('error', 'Gagal menghubungi ToyyibPay. Sila cuba lagi.');
}

// public function displayPackage(Request $request)
// {
//     $status = $request->status_id; // 1 = success, 2 = pending, 3 = fail
//     $billCode = $request->billcode;

//     if ($status == 1) {
//         // 1. Find the order/user associated with this billCode
//         // 2. Update PackageOrders status to 'approved'
//         // 3. Create/Update SubscriptionsMasjid record
        
//         return redirect()->route('Subscription_Package.buy_package')
//             ->with('success', 'Pembayaran Berjaya! Langganan anda kini aktif.');
//     }

//     return redirect()->route('Subscription_Package.buy_package')
//         ->with('error', 'Pembayaran tidak berjaya atau dibatalkan.');
// }

// STEP 2: Handle Background Callback (ToyyibPay -> Your Server)
public function paymentCallback(Request $request)
{
    // ToyyibPay sends: status_id, billcode, order_id
    $status = $request->status_id; 
    $billCode = $request->billcode;
    $refNo = $request->order_id; // This matches 'billExternalReferenceNo' you sent earlier

    if ($status == '1') {
        // Find the order using the Reference Number or BillCode
        $order = PackageOrders::where('bill_code', $billCode)
                    ->where('status', 'pending')
                    ->first();

        if ($order) {
            DB::transaction(function () use ($order) {
                // 1. Update Order Status
                $order->update([
                    'status' => 'approved', // Matching your current DB enum
                    'approved_by' => 0, // System approved
                ]);

                // 2. Create/Update Subscription
                SubscriptionsMasjid::updateOrCreate(
                    ['masjid_id' => $order->masjid_id],
                    [
                        'user_id' => $order->user_id,
                        'package_id' => $order->package_id,
                        'start_date' => now(),
                        'end_date' => now()->addYear(),
                        'status' => 'active',
                    ]
                );
            });
        }
    }

    // ToyyibPay expects a 'OK' string to stop retrying the callback
    return response('OK');
}

// STEP 3: Handle User Return (Frontend Redirect)
public function paymentReturn(Request $request)
{
    // User returns with GET parameters: ?status_id=1&billcode=xxx&order_id=xxx
    if ($request->status_id == 1) {
        return redirect()->route('subscription.package')
            ->with('success', 'Pembayaran Berjaya! Langganan anda telah diaktifkan secara automatik.');
    }

    return redirect()->route('subscription.package')
        ->with('error', 'Pembayaran tidak berjaya atau dibatalkan.');
}

public function renew(Request $request)
{
    $request->validate([
        'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    try {
        DB::beginTransaction();

        $package = Package::findOrFail($request->package_id);

        // Get AJK info to get masjid_id
        $ajk = Ajks::where('user_id', Auth::id())->first();

        if (!$ajk || !$ajk->masjid_id) {
            return redirect()->back()->with('error', 'Anda tidak mempunyai masjid yang didaftarkan. Sila hubungi admin.');
        }

        // Check if masjid already has pending order
        $existingPending = PackageOrders::where('masjid_id', $ajk->masjid_id)
            ->where('status', 'pending')
            ->first();

        if ($existingPending) {
            return redirect()->back()->with('error', 'Masjid anda masih mempunyai pesanan yang belum diproses. Sila tunggu pengesahan dari pihak admin.');
        }

        // Check for active subscription
        $existingActive = SubscriptionsMasjid::where('masjid_id', $ajk->masjid_id)
            ->where('status', 'active')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();

        if ($existingActive) {
            return redirect()->back()->with('error', 'Masjid anda sudah mempunyai langganan aktif.');
        }

        // Check if this is a renewal or new subscription
        $previousSubscription = SubscriptionsMasjid::where('masjid_id', $ajk->masjid_id)
            ->where('status', 'expired')
            ->orWhere('end_date', '<', Carbon::now())
            ->first();

        $paymentType = $previousSubscription ? 'Renew' : 'New';
        
        // Verify the amount matches expected price
        $expectedAmount = $paymentType === 'Renew' ? $package->renew_price : $package->price;
        
       

        // Handle file upload
        $resitPath = null;

        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $timestamp = now()->format('Ymd_His');
            $masjidId = $ajk->masjid_id ?? '000000';
            $extension = $file->getClientOriginalExtension();
            $filename = "resit_masjid_{$masjidId}_{$timestamp}.{$extension}";

            $uploadPath = public_path('pembayaran/resit_pembayaran');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $filename);
            $resitPath = 'pembayaran/resit_pembayaran/' . $filename;
        }

        // Create package order with payment_type
        PackageOrders::create([
            'user_id' => Auth::id(),
            'masjid_id' => $ajk->masjid_id,
            'package_id' => $request->package_id,
            'amount' => $request->amount,
            'receipt_path' => $resitPath,
            'status' => 'pending',
            'payment_type' => $paymentType,
            'approved_by' => null,
        ]);

        DB::commit();

        return redirect()->route('subscription.package', ['#renew'])
            ->with('success', 'Pesanan langganan untuk masjid anda telah diterima. Sila tunggu pengesahan dari pihak admin dalam masa 1-3 hari bekerja.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Subscription renew error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Ralat berlaku. Sila cuba lagi.')->withInput();
    }
}


}
