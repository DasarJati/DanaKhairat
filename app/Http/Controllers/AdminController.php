<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRegister;
use App\Models\AjkRegister;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

public function main()
{
    // Total role 1
    $totalMasjid = AjkRegister::where('type', 'masjid')->count();
    $totalSurau = AjkRegister::where('type', 'surau')->count();
    $totalRole1 = $totalMasjid + $totalSurau;

    // Total role 2
    $totalRole2 = User::where('role', 2)->count();

    // Total tuntutan pending
    $totalPendingTuntutan = UserRegister::where('approval_status', 'PENDING')->count();

    return view('admin.main', compact(
        'totalRole1',
         'totalMasjid',
        'totalSurau',
        'totalRole2',
        'totalPendingTuntutan'
    ));
}

public function listKariah(Request $request)
{
    // Ambil semua institusi untuk dropdown filter
    $institusi = Masjid::orderBy('nama')->get();

    // Query asas (role 2 sahaja)
    $query = User::where('role', 2);

    // Kalau ada filter masjid_id
    if ($request->filled('masjid_id')) {
        $query->where('masjid_id', $request->masjid_id);
    }

    $kariah = $query->orderBy('nama')->paginate(10);

    return view('admin.listkariah', compact('kariah', 'institusi'));
}

}