<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Masjid;
use Carbon\Carbon;

class WalletController extends Controller
{
    /**
     * Display wallet dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Pastikan hanya AJK (role = 1) yang boleh akses
        if ($user->role !== 1) {
            abort(403);
        }

        // Dapatkan wallet masjid
        $wallet = Wallet::where('masjid_id', $user->masjid_id)->first();
        
        // Jika wallet tidak wujud, buat baru
        if (!$wallet) {
            $wallet = Wallet::create([
                'masjid_id' => $user->masjid_id,
                'balance' => 0
            ]);
        }

        // Untuk demo sahaja - data statik
        $totalIncomeThisMonth = 5420.00;
        $totalExpenseThisMonth = 1200.00;
        
        // Data untuk chart (contoh statik)
        $monthlyData = [
            'labels' => ['Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun', 'Jul', 'Ogos', 'Sept', 'Okt'],
            'income' => [4200, 4800, 5100, 5800, 5200, 6100, 5900, 6300, 5800, 5420],
            'expense' => [1200, 1500, 1100, 2800, 1200, 2100, 1900, 1300, 1800, 1200]
        ];

        // Transaksi contoh
        $recentTransactions = [
            ['description' => 'Sumbangan Jumaat', 'date' => '28 Okt 2023', 'amount' => 450, 'type' => 'income'],
            ['description' => 'Bil Elektrik', 'date' => '25 Okt 2023', 'amount' => 210, 'type' => 'expense'],
            ['description' => 'Infaq Kebajikan', 'date' => '20 Okt 2023', 'amount' => 1200, 'type' => 'income'],
            ['description' => 'Pembaikan Masjid', 'date' => '18 Okt 2023', 'amount' => 800, 'type' => 'expense'],
            ['description' => 'Sumbangan Dermawan', 'date' => '15 Okt 2023', 'amount' => 2500, 'type' => 'income'],
        ];

        return view('payment.duit', compact(
            'user', 
            'wallet', 
            'totalIncomeThisMonth', 
            'totalExpenseThisMonth',
            'recentTransactions',
            'monthlyData'
        ));
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
}