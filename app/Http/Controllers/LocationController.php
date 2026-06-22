<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Poskod;
use App\Models\Negeri;
use App\Models\Kariah;
use App\Models\masjid;

class LocationController extends Controller
{
    // Get all states
    public function getNegeri()
    {
        try {
            $negeri = Negeri::orderBy('nama')->get();
            return response()->json($negeri);
        } catch (\Exception $e) {
            \Log::error('Error in getNegeri: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    // Get all postcodes
    public function getPoskods()
    {
        try {
            $poskods = Poskod::select('id', 'poskod_num', 'nama', 'negeri_id')
                ->orderBy('poskod_num')
                ->get();

            return response()->json($poskods);
        } catch (\Exception $e) {
            \Log::error('Error in getPoskods: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    // Get cities by postcode
    public function getBandarByPoskod($poskod)
    {
        try {
            $bandar = Poskod::where('poskod_num', $poskod)
                ->select('nama')
                ->distinct()
                ->orderBy('nama')
                ->get();

            return response()->json($bandar);
        } catch (\Exception $e) {
            \Log::error('Error in getBandarByPoskod: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    // Get postcodes by state
    public function getPoskodByNegeri($negeriId)
    {
        try {
            $poskods = Poskod::where('negeri_id', $negeriId)
                ->select('poskod_num', 'nama')
                ->orderBy('poskod_num')
                ->get();
            return response()->json($poskods);
        } catch (\Exception $e) {
            \Log::error('Error in getPoskodByNegeri: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function testDatabase()
    {
        try {
            // Test database connection
            $connected = \DB::connection()->getPdo();

            // Check if table exists
            $tableExists = \Schema::hasTable('poskod');

            // Get table columns
            $columns = \Schema::getColumnListing('poskod');

            return response()->json([
                'database_connected' => true,
                'table_exists' => $tableExists,
                'columns' => $columns,
                'poskod_count' => $tableExists ? Poskod::count() : 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function debugPoskod()
    {
        try {
            // Get raw data without any Eloquent transformations
            $poskods = DB::table('poskod')->get();

            return response()->json([
                'raw_data' => $poskods,
                'count' => count($poskods)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Get all kariahs
    // Get all kariahs
    public function getKariahByPoskod($poskodId, $bandar) // ✅ comma between parameters
    {
        try {
            // Fetch kariah filtered by poskod_id and bandar
            $kariahs = Kariah::where('poskod_id', $poskodId)
                ->where('bandar', 'like', $bandar)
                ->get();

            return response()->json($kariahs);
        } catch (\Exception $e) {
            \Log::error('Error in getKariahByPoskod: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    ///UNTUK USER
    public function getPoskod($negeri_id)
    {
        // Ambil poskod yang unik untuk negeri tersebut
        $poskod = Poskod::where('negeri_id', $negeri_id)
            ->select('poskod_num')
            ->distinct()
            ->orderBy('poskod_num')
            ->get();

        return response()->json($poskod);
    }

    public function getBandar($poskod_num)
    {
        // Cari semua bandar yang ada bawah nombor poskod itu
        $bandar = Poskod::where('poskod_num', $poskod_num)
            ->select('id', 'nama')
            ->get();

        return response()->json($bandar);
    }

    // Ambil masjid berdasarkan ID unik dari table poskod
    public function getMasjid($poskod_id)
    {
        $masjid = masjid::where('poskod_id', $poskod_id)
            ->where('status', 'active')
            ->select('id', 'nama')
            ->get();

        return response()->json($masjid);
    }
}
