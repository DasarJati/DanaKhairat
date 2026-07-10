<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;
use App\Models\Payment;
use App\Models\Masjid;
use App\Models\SubscriptionsKariah;
use App\Models\AhliKariah;
use Carbon\Carbon;


class WalletController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            abort(403);
        }

        $wallet = Wallet::where('masjid_id', $user->masjid_id)->first();

        if (!$wallet) {
            $wallet = Wallet::create([
                'masjid_id' => $user->masjid_id,
                'balance' => 0
            ]);
        }

        // Base query for payments
        $paymentsQuery = Payment::where('masjid_id', $user->masjid_id)
            ->where(function ($query) {
                $query->where('status', 'PAID')
                    ->orWhere('status', 'SUCCESS');
            })
            ->with('user');

        // Get selected year from filter (default to current year)
        $selectedYear = $request->input('year', date('Y'));
        $selectedMonth = $request->input('month', date('n'));

        // Apply transaction type filter
        if ($request->filled('flow_type') && $request->flow_type != 'all') {
            $paymentsQuery->where('flow_type', $request->flow_type);
        }

        // Apply date filters
        if ($request->filled('start_date')) {
            $paymentsQuery->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $paymentsQuery->whereDate('created_at', '<=', $request->end_date);
        }

        // Get filtered payments with pagination
        $filteredPayments = $paymentsQuery->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // TOTAL DANA FOR SELECTED YEAR
        $totalDana = Payment::where('masjid_id', $user->masjid_id)
            ->where('flow_type', 'transaction_in')
            ->where('type', '!=', 'Add Dana')
            ->whereYear('created_at', $selectedYear)
            ->sum('amount');

        // TOTAL EXPENSE FOR SELECTED YEAR
        $totalOut = Payment::where('masjid_id', $user->masjid_id)
            ->where('flow_type', 'transaction_out')
            ->where('type', '!=', 'Add Dana')
            ->whereYear('created_at', $selectedYear)
            ->sum('amount');

        // TOTAL INCOME FOR SELECTED MONTH
        $totalIncomeThisMonth = Payment::where('masjid_id', $user->masjid_id)
            ->where('flow_type', 'transaction_in')
            ->where('type', '!=', 'Add Dana')
            ->whereYear('created_at', $selectedYear)
            ->whereMonth('created_at', $selectedMonth)
            ->sum('amount');

        // Get available years from payments
        $availableYears = Payment::where('masjid_id', $user->masjid_id)
            ->selectRaw('DISTINCT YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // If no years found, use current year
        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }

        // Optional: Add a range of years (e.g., last 5 years and next 5 years)
        $currentYear = date('Y');
        $yearRange = range($currentYear - 5, $currentYear + 5);
        $availableYears = array_unique(array_merge($availableYears, $yearRange));
        sort($availableYears); // Sort ascending
        rsort($availableYears); // Sort descending (latest first)

        // Monthly data for chart
        $monthlyIncome = [];
        $monthlyExpense = [];

        for ($month = 1; $month <= 12; $month++) {
            $income = Payment::where('masjid_id', $user->masjid_id)
                ->where('flow_type', 'transaction_in')
                ->where('type', '!=', 'Add Dana')
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $month)
                ->sum('amount');

            $expense = Payment::where('masjid_id', $user->masjid_id)
                ->where('flow_type', 'transaction_out')
                ->where('type', '!=', 'Add Dana')
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $month)
                ->sum('amount');

            $monthlyIncome[] = $income;
            $monthlyExpense[] = $expense;
        }

        $monthlyData = [
            'labels' => ['Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun', 'Jul', 'Ogos', 'Sept', 'Okt', 'Nov', 'Dis'],
            'income' => $monthlyIncome,
            'expense' => $monthlyExpense
        ];

        return view('payment.duit', compact(
            'user',
            'wallet',
            'filteredPayments',
            'totalDana',
            'totalIncomeThisMonth',
            'monthlyData',
            'totalOut',
            'selectedYear',
            'selectedMonth',
            'availableYears'
        ));
    }

    public function indexDana(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            abort(403);
        }

        $wallet = Wallet::where('masjid_id', $user->masjid_id)->first();

        if (!$wallet) {
            $wallet = Wallet::create([
                'masjid_id' => $user->masjid_id,
                'balance' => 0
            ]);
        }

        // Get PENDING VERIFICATION payments query with filters
        // (approve/reject dikendalikan oleh updatePaymentStatus() -> route('finance.status.update'))
        $pendingQuery = Payment::where('masjid_id', $user->masjid_id)
            ->where('status', 'waiting_verification')
            ->whereIn('type', ['new_member', 'renew_member'])
            ->with(['user', 'subscription']);

        // Handle quick date filters
        if ($request->has('quick_date')) {
            $today = now();
            switch ($request->quick_date) {
                case 'today':
                    $pendingQuery->whereDate('created_at', $today->toDateString());
                    break;
                case 'week':
                    $pendingQuery->whereBetween('created_at', [$today->startOfWeek(), $today->endOfWeek()]);
                    break;
                case 'month':
                    $pendingQuery->whereMonth('created_at', $today->month)
                        ->whereYear('created_at', $today->year);
                    break;
            }
        }

        // Apply month filter
        if ($request->filled('month') && $request->month != 'all') {
            $pendingQuery->whereMonth('created_at', $request->month);
        }

        // Apply year filter
        if ($request->filled('year') && $request->year != 'all') {
            $pendingQuery->whereYear('created_at', $request->year);
        }

        // Apply date range filters
        if ($request->filled('start_date')) {
            $pendingQuery->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $pendingQuery->whereDate('created_at', '<=', $request->end_date);
        }

        // Get filtered pending payments with pagination
        $pendingPayments = $pendingQuery->orderBy('created_at', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('payment.dana', compact(
            'user',
            'wallet',
            'pendingPayments'
        ));
    }

    /**
     * Handle payment status update (approve/reject)
     */
    public function updatePaymentStatus(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'action'     => 'required|in:approve,reject',
            'reason'     => 'required_if:action,reject|nullable|string|max:500',
        ]);

        $payment = Payment::with('subscription')->find($request->payment_id);

        if (!$payment) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Payment not found'], 404);
            }
            return redirect()->back()->with('error', 'Pembayaran tidak ditemui.');
        }

        DB::beginTransaction();

        try {
            if ($request->action === 'approve') {
                // Payment diluluskan
                $payment->status = 'paid';
                $payment->save();

                if (in_array($payment->type, ['new_member', 'renew_member']) && $payment->subscription) {
                    // Subscription memang dah wujud (dicipta semasa user buat request),
                    // approve hanya aktifkan ia.
                    $payment->subscription->update(['status' => 'active']);
                } elseif ($payment->type === 'Renew Membership' || $payment->flow_type === 'transaction_in') {
                    // Fallback untuk flow lama
                    $this->handleSubscriptionRenewal($payment);
                }

                $message = 'Pembayaran telah diluluskan dan keahlian diaktifkan.';
            } else {
                // Reject: payment lama dikekalkan sebagai rekod (dengan sebab),
                // satu payment baru dijana untuk user hantar semula resit.
                $subscription = $payment->subscription;
                $originalRemarks = $payment->remarks;

                $payment->status = 'rejected';
                $payment->remarks = $request->reason;
                $payment->save();

                $newPayment = Payment::create([
                    'user_id'        => $payment->user_id,
                    'masjid_id'      => $payment->masjid_id,
                    'amount'         => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'status'         => 'pending',
                    'type'           => $payment->type,
                    'flow_type'      => $payment->flow_type,
                    'remarks'        => $originalRemarks,
                ]);

                if ($subscription) {
                    $subscription->update([
                        'payment_id' => $newPayment->id,
                        'status'     => 'pending_payment',
                    ]);
                }

                $message = 'Pembayaran telah ditolak. Rekod baru dijana untuk penghantaran semula resit.';
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('finance.dana')->with('success', $message);
    }

    /**
     * Handle subscription renewal when payment is approved
     */
    private function handleSubscriptionRenewal($payment)
    {
        // Get the Ahli Khairat record
        $ahliKariah = AhliKariah::where('user_id', $payment->user_id)
            ->where('masjid_id', $payment->masjid_id)
            ->first();

        if (!$ahliKariah) {
            \Log::error('Ahli Khairat not found for user_id: ' . $payment->user_id);
            return;
        }

        // Get the latest subscription (active or expired)
        $latestSubscription = SubscriptionsKariah::where('user_id', $payment->user_id)
            ->where('masjid_id', $payment->masjid_id)
            ->orderBy('end_date', 'desc')
            ->first();

        // Calculate new subscription dates
        $newStartDate = now();
        $newEndDate = now()->addYear();

        // If there's an existing active or future subscription, extend from its end date
        if ($latestSubscription && $latestSubscription->end_date >= now()) {
            $newStartDate = $latestSubscription->end_date;
            $newEndDate = $latestSubscription->end_date->copy()->addYear();
        }
        // If there's an expired subscription, start from today
        elseif ($latestSubscription && $latestSubscription->end_date < now()) {
            $newStartDate = now();
            $newEndDate = now()->addYear();
        }

        // Create new subscription
        $subscription = SubscriptionsKariah::create([
            'user_id' => $payment->user_id,
            'masjid_id' => $payment->masjid_id,
            'start_date' => $newStartDate,
            'end_date' => $newEndDate,
            'status' => 'active',
            'price' => $payment->amount,
            'payment_id' => $payment->id,
        ]);

        // Update wallet balance
        $wallet = Wallet::where('masjid_id', $payment->masjid_id)->first();
        if ($wallet) {
            $wallet->balance += $payment->amount;
            $wallet->save();
        }

        \Log::info('Subscription renewed for user_id: ' . $payment->user_id .
            ' from ' . $newStartDate . ' to ' . $newEndDate);

        return $subscription;
    }

    /**
     * Update wallet balance (tambah income)
     */
    public function addIncome(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        try {
            // Cari atau buat wallet
            $wallet = Wallet::firstOrCreate(
                ['masjid_id' => $user->masjid_id],
                ['balance' => 0]
            );

            // Update wallet balance
            $wallet->balance += $request->amount;
            $wallet->save();

            return redirect()->route('finance')
                ->with('success', 'RM ' . number_format($request->amount, 2) . ' berjaya ditambah ke dompet.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambah baki: ' . $e->getMessage());
        }
    }

    /**
     * Update wallet balance (tambah expense)
     */
    public function addExpense(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        try {
            // Cari wallet
            $wallet = Wallet::where('masjid_id', $user->masjid_id)->first();

            if (!$wallet) {
                return redirect()->back()
                    ->with('error', 'Wallet tidak dijumpai.');
            }

            // Semak baki mencukupi
            if ($wallet->balance < $request->amount) {
                return redirect()->back()
                    ->with('error', 'Baki tidak mencukupi. Baki semasa: RM ' . number_format($wallet->balance, 2));
            }

            // Update wallet balance
            $wallet->balance -= $request->amount;
            $wallet->save();

            return redirect()->route('finance')
                ->with('success', 'RM ' . number_format($request->amount, 2) . ' berjaya dikeluarkan dari dompet.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengurangkan baki: ' . $e->getMessage());
        }
    }

    /**
     * Reset wallet balance
     */
    public function resetBalance(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            abort(403);
        }

        $request->validate([
            'new_balance' => 'required|numeric|min:0',
        ]);

        try {
            $wallet = Wallet::firstOrCreate(
                ['masjid_id' => $user->masjid_id],
                ['balance' => 0]
            );

            $oldBalance = $wallet->balance;
            $wallet->balance = $request->new_balance;
            $wallet->save();

            return redirect()->route('finance')
                ->with('success', 'Baki dompet berjaya diset semula dari RM ' .
                    number_format($oldBalance, 2) . ' ke RM ' .
                    number_format($request->new_balance, 2));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyet semula baki: ' . $e->getMessage());
        }
    }

    /**
     * Get wallet statistics (API)
     */
    public function getStatistics()
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $wallet = Wallet::where('masjid_id', $user->masjid_id)->first();

        return response()->json([
            'balance' => $wallet ? $wallet->balance : 0,
            'updated_at' => $wallet ? $wallet->updated_at->format('d/m/Y H:i') : 'N/A'
        ]);
    }


    public function updateDana(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 1) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            // 'description' => 'required|string|max:255', // Remove or comment this out
        ]);

        try {
            // Cari atau buat wallet
            $wallet = Wallet::firstOrCreate(
                ['masjid_id' => $user->masjid_id],
                ['balance' => 0]
            );

            // Update wallet balance
            $wallet->balance += $request->amount;
            $wallet->save();

            // Optional: Create a transaction record
            Payment::create([
                'user_id' => Auth::id(),
                'masjid_id' => $user->masjid_id,
                'name' => $user->nama,
                'amount' => $request->amount,
                'payment_method' =>  'MANUAL',
                'status' => 'success',
                'type' => 'Add Dana',
                'remarks' => 'Tambah dana oleh AJK. ' . $user->nama,
                'flow_type' => 'transaction_in',
                'created_at' => now(),
                'paid_at' => now()
            ]);

            return redirect()->route('finance')
                ->with('success', 'RM ' . number_format($request->amount, 2) . ' berjaya ditambah ke dana.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambah dana: ' . $e->getMessage());
        }
    }
}