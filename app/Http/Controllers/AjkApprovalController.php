<?php

namespace App\Http\Controllers;

use App\Mail\AjkApprovedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\AjkPakej;
use App\Models\AjkRegister;
use App\Models\User;
use App\Models\masjid;
use App\Models\Ajks;
use App\Models\PolicyHeader;
use App\Models\PolicyMasjid;
use App\Models\Bank;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class AjkApprovalController extends Controller
{
    public function dashboard(Request $request)
    {
        // kira total
        $total = AjkRegister::count();
        $pending = AjkRegister::where('status', 0)->count();
        $approved = AjkRegister::where('status', 1)->count();
        $rejected = AjkRegister::where('status', 2)->count();

        // base query
        $query = AjkRegister::orderBy('created_at', 'desc');

        // filter status (jika ada)
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $applications = $query->get();

        return view('admin.dashboard', compact(
            'applications',
            'total',
            'pending',
            'approved',
            'rejected'
        ));
    }



    public function view($id)
    {
        $app = AjkRegister::with(['package'])->findOrFail($id);
        $pakej = AjkPakej::where('ajk_id', $app->id)->first();

        return view('admin.view', compact('app', 'pakej'));
    }


    public function approveRegister($id)
    {
        DB::beginTransaction();



        try {
            $app = AjkRegister::findOrFail($id);
            $pakej = AjkPakej::where('ajk_id', $app->id)->firstOrFail();

            // ❌ Elak approve dua kali
            if ($app->status != 0) {
                return back()->with('error', 'Permohonan ini telah diproses.');
            }

            /* =====================================================
         * 1️⃣ CREATE MASJID
         * ===================================================== */
            $masjid = masjid::create([
                'negeri_id' => $app->negeri_id,
                'poskod_id' => $app->poskod_id,
                'kariah_id' => $app->kariah_id,
                'nama'      => $app->nama_masjid,
                'type'      => $app->type,
                'alamat'    => $app->alamat,
                'alamat2'   => $app->alamat2,
                'bandar'    => $app->bandar,
                'price'     => $pakej->price ?? 0,
                'resit'     => $pakej->resit ?? null,
                'status'    => 'active',
            ]);

            /* =====================================================
         * 2️⃣ CREATE USER (AJK)
         * ===================================================== */
            $user = User::create([
                'nama'        => $app->nama_masjid,
                'ic_number'   => $app->ic,
                'email'       => $app->email,
                'telefon_bimbit' => $app->telefon,
                'password'      => $app->password,
                'masjid_id'   => $masjid->id,
                'role'        => '1',
                'status'      => 'active',
                'agree_terms' => 1,
                'membership_start' => now(),
                'membership_end'   => now()->addYear(),
            ]);

            /* =====================================================
         * 3️⃣ CREATE AJK (LINK USER ↔ MASJID)
         * ===================================================== */
            Ajks::create([
                'user_id'   => $user->id,
                'masjid_id' => $masjid->id,
                'nama_pendaftar' => $user->nama,
                'type' =>      $app->type,
                'position'  => $app->jawatan ?? 'AJK',
                'ic'        => $app->ic,
                'notel'     => $app->notel,
            ]);

            /* =====================================================
         * 4️⃣ UPDATE STATUS PERMOHONAN
         * ===================================================== */
            $app->update([
                'status' => 1, // approved
            ]);

            $header = PolicyHeader::create([
                'masjid_id' => $masjid->id,
                'big_title' => 'KHAIRAT KEMATIAN ' . strtoupper($masjid->type) . ' ' . strtoupper($masjid->nama)
            ]);

            PolicyMasjid::create([
                'policy_id' => $header->id,
                'title' => 'TUJUAN',
                'description' => "Khairat kematian " . strtoupper($masjid->type) . " " . strtoupper($masjid->nama) . ", adalah berasaskan konsep tabarru (sedekah). Dengan kata lain jika dalam tempoh setahun tiada berlaku kematian di kalangan ahli atau tanggungannya, maka bayaran tahunan yang telah dibayar dikira sedekah yang dikumpulkan untuk membantu membiayai pengurusan jenazah ahli lain atau tanggungannya."
            ]);

            PolicyMasjid::create([
                'policy_id' => $header->id,
                'title' => 'KEAHLIAN',
                'description' => '1.Ketua keluarga (suami atau isteri sekiranya ibu tunggal). Jika ahli meninggal, balunya akan bertukar menjadi ahli. Dia tidak perlu membuat pendaftaran baru, cuma perlu menjelaskan bayaran tahunan sahaja.
                                  2.Bujang berumur 22 tahun ke atas (jika tidak belajar. Jika masih belajar bermula umur 24 tahun)
                                  3.Keahlian akan diaktifkan selepas 30 hari bayaran pendaftaran keahlian dijelaskan.
                                  4.Penamatan keahlian: Keahlian perlu diperbaharui setiap tahun. Keahlian akan ditamatkan secara automatik jika pihak masjid tidak menerima bayaran yuran tahunan selepas 3 bulan tamat atau ahli berpindah dan tinggal di luar kariah Masjid al-Hasanah.'
            ]);

            PolicyMasjid::create([
                'policy_id' => $header->id,
                'title' => 'TANGGUNGAN',
                'description' => ' 1.Pasangan (isteri seorang sahaja. Jika mempunyai lebih dari seorang isteri perlu buat pendaftaran lain)
                                   2.Ibu dan bapa (sila upload Surat beranak)
                                   3.Anak-Anak yang berumur 21 tahun dan ke bawah (kecuali anak yang masih belajar sehingga berumur 24 tahun dan  anak OKU) termasuk anak angkat (sila upload sijil pengangkatan) dan anak tiri (yang tinggal bersama ahli).'
            ]);

            PolicyMasjid::create([
                'policy_id' => $header->id,
                'title' => 'BAYARAN',
                'description' => ' Pendaftaran : RM40 (Sekali bayar ketika mendaftar)
                                   Tahunan : RM60 
                                   Bayaran melalui sistem e-khairat dengan mengunakan Toyyibpay FPX atau online transfer terus ke akaun ke akaun Tetuan Masjid Al-Hasanah Bandar Baru Bangi  ( Bank MBSB - 1005025100001015). Bagi bayaran secara online transfer, sila hantar bukti pembayaran kepada pentadbir sistem e-khairat.'
            ]);

            PolicyMasjid::create([
                'policy_id' => $header->id,
                'title' => 'MANFAAT',
                'description' => 'Jika berlaku kematian:
                 1.Ketua keluarga: RM2,000 (RM1,350 untuk pengurusan jenazah, RM650 saguhati akan diserahkan kepada keluarga)
                 2.Pasangan, anak-anak (7 tahun ke atas) dan ibubapa: RM1,500 (untuk pengurusan jenazah)
                 3.Anak-anak 7 tahun ke bawah: RM700'
            ]);

            PolicyMasjid::create([
                'policy_id' => $header->id,
                'title' => 'KAEDAH SUMBANGAN KEPADA WARIS',
                'description' => '1.Jika pengurusan jenazah sepenuhnya di masjid dan dikebumikan di TanahPerkuburan Sungai Tangkas, bayaran akan dilakukan oleh masjid kepada pihak terlibat (team mandi jenazah, pemandu van dan penggali kubur).
                                  2.Jika pengurusan jenazah dilakukan di hospital dan dikebumikan di Tanah Perkuburan Sungai Tangkas, bayaran untuk team mandi jenazah hospital akan diserahkan kepada ahli waris dan bayaran kepada pemandu van dan penggali kubur akan diserahkan oleh pihak masjid.
                                  3.Jika pengurusan jenazah dilakukan di hospital tetapi dikebumikan di tempat lain, bayaran sebanyak RM2000 diberikan jika kematian ketua keluarga dan sebanyak RM1,500 jika kematian pasangan atau tanggungan (masjid tidak menanggung kos tambahan).'
            ]);

            PolicyMasjid::create([
                'policy_id' => $header->id,
                'title' => 'DOKUMEN YANG DIPERLUKAN',
                'description' => '1.Salian Kad Pengenalan/Passport
                                  2.Salinan surat beranak
                                  3.Salinan surat nikah
                                  4.Salian sijil pengangkatan dari Jabatan Kebajikan Masyarakat jika berkenaan
                                  5.Bill Utiliti (Api atau Air)'
            ]);

            PolicyMasjid::create([
                'policy_id' => $header->id,
                'title' => 'NO TALIAN UNTUK DIHUBUNGI',
                'description' => "1. Hotline " . strtoupper($masjid->type) . " " . strtoupper($masjid->nama) . " (012 - 3456789)
                                  2. Pejabat " . strtoupper($masjid->type) . " " . strtoupper($masjid->nama) . " (03 - 12345678)"
            ]);

            // Bank::create([
            //     'masjid_id' => $masjid->id,
            //     'qr_path' => null,
            //     'nama_bank' => null,
            //     'nama_akaun' => null,
            //     'no_akaun' => null
            // ]);

            /* =====================================================
            * 5️⃣ HANTAR EMAIL
            * ===================================================== */

            $Amount = $masjid->price ?? 0;

            Mail::to($user->email)->send(
                new AjkApprovedMail(
                    $app,
                    $masjid,
                    $Amount
                )
            );
            DB::commit();

            \Log::info('ApproveRegister Data Dump', [
                'AjkRegister' => $app->toArray(),
                'Masjid'      => $masjid->toArray(),
                'User'        => $user->toArray(),
                'Ajk'         => Ajks::where('user_id', $user->id)->where('masjid_id', $masjid->id)->first()->toArray(),
                'Pakej'       => $pakej->toArray(),
            ]);

            return redirect()->back()
                ->with('success', 'Permohonan diluluskan, user & masjid berjaya diwujudkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            dd(
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );
        }
    }



    public function reject($id)
    {
        $app = AjkRegister::findOrFail($id);

        if ($app->status != 0) {
            return redirect()->back()
                ->with('error', 'Permohonan ini telah diproses.');
        }

        $app->update(['status' => 2]);

        return redirect()->route('admin.dashboard')->with('success', 'Permohonan ditolak.');
    }


    public function listMasjid(Request $request)
    {
        // Buang baris ini kerana kolum 'pakej' tak wujud
        // $packages = AjkPakej::select('pakej')->distinct()->get();

        $query = AjkRegister::with('package')
            ->where('status', 1)
            ->orderBy('nama_masjid', 'asc');

        // Buang juga filter hasPackage jika kolum 'pakej' tidak wujud
        /*
    if ($request->package) {
        $query->whereHas('package', function ($q) use ($request) {
            $q->where('pakej', $request->package);
        });
    }
    */

        $approved = $query->get();

        // Hantar array kosong atau buang terus variable packages
        return view('admin.approved', [
            'approved' => $approved,
            'packages' => collect()
        ]);
    }

    public function details($id)
    {
        $app = AjkRegister::with(['package'])->findOrFail($id);
        $pakej = AjkPakej::where('ajk_id', $app->id)->first();

        return view('admin.details', compact('app', 'pakej'));
    }
}
