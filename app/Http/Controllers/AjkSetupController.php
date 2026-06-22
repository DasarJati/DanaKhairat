<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\HargaKhairat;
use App\Models\Bank;
use App\Models\masjid as Masjid ;
use App\Models\PolicyHeader;
use App\Models\PolicyMasjid;

class AjkSetupController extends Controller
{
    public function index()
    {
        $ajk = Auth::user();

        $adaHarga = HargaKhairat::where('masjid_id', $ajk->masjid_id)->exists();

        if ($adaHarga) {
            // kalau dah setup, nanti kita redirect
            return redirect()->route('pic.dashboard');
        }

        // kalau belum setup
        return view('pic.setup');
    }

    public function store(Request $request)
    {
        $ajk = Auth::user();
        $masjid = Masjid::findOrFail($ajk->masjid_id);

        DB::transaction(function () use ($request, $ajk) {
            // Simpan ke table hargakhairat
            HargaKhairat::create([
                'masjid_id' => $ajk->masjid_id,
                'bayaran_tahunan' => $request->bayaran_tahunan,
                'yuran_pendaftaran' => $request->yuran_pendaftaran,
                'sumbangan_seorang' => $request->bayaran_kematian
            ]);

            // Simpan ke table bank
            Bank::create([
                'masjid_id' => $ajk->masjid_id,
                'nama_bank' => $request->nama_bank,
                'nama_akaun' => $request->nama_akaun,
                'no_akaun' => $request->no_akaun,
            ]);

            $qrPath = null;
            if ($request->hasFile('qr_bank')) {
                $file = $request->file('qr_bank');

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
                $qrPath = 'uploads/qr_ajk/' . $filename;

                Bank::updateOrCreate(
                    ['masjid_id' => $ajk->masjid_id],
                    [
                        'nama_bank' => $request->nama_bank,
                        'nama_akaun' => $request->nama_akaun,
                        'no_akaun' => $request->no_akaun,
                        'qr_path'    => $qrPath ?? DB::raw('qr_path')
                    ]
                );
            }
        });

        $header = PolicyHeader::create([
                'masjid_id' => $ajk->masjid_id,
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

        return redirect()->route('pic.dashboard')
            ->with('success', 'Maklumat berjaya disimpan.');
    }

}
