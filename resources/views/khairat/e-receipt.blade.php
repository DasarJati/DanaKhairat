<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resit - {{ $tuntutan->reference_no ?? 'Receipt' }}</title>
    <style>
        /* Compact Print Settings */
        @page { size: portrait; margin: 0.5cm; }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            padding: 15px;
            font-size: 12px;
        }

        .receipt-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
        }

        /* Header Layout: Logo/Title Left, Ref No Right */
        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-left h1 { font-size: 18px; text-transform: uppercase; }
        .header-right { text-align: right; }

        /* Compact Grid for Info */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 30px;
            margin-bottom: 15px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
        }

        .info-item { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding: 2px 0; }
        .label { color: #666; font-weight: bold; font-size: 11px; }

        /* Table Settings */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .details-table th {
            background: #eee;
            text-align: left;
            padding: 6px;
            border: 1px solid #ddd;
        }

        .details-table td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        /* Summary Section */
        .summary-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
        }

        .status-box {
            border: 2px solid #059669;
            color: #059669;
            padding: 5px 15px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .total-box {
            
            border: 2px solid #000;
            border:
            color: white;
            padding: 5px 5px;
            text-align: right;
            min-width: 200px;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .no-print { text-align: center; margin-top: 10px; }
        .btn { padding: 8px 20px; cursor: pointer; background: #444; color: #fff; border: none; }

        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none; }
            .receipt-container { border: 1px solid #000; width: 100%; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="header-flex">
        <div class="header-left">
            <h1>{{ $tuntutan->masjid->nama ?? 'MASJID AL-Dasar Jati' }}</h1>
            <p>{{ $tuntutan->masjid->alamat ?? 'RESIT RASMI PENGURUSAN JENAZAH' }}</p>
        </div>
        <div class="header-right">
            <p><strong>NO:</strong> {{ $tuntutan->reference_no ?? 'TRX-'.$tuntutan->id }}</p>
            <p><strong>TARIKH:</strong> {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Info side-by-side to save height -->
    <div class="info-grid">
        <div class="info-item">
            <span class="label">NAMA SI MATI:</span>
            <span>
                @if($tuntutan->type == 'AHLI') 
                    {{ $tuntutan->ahli->nama ?? 'Nama Tidak Ditemui' }}
                @elseif($tuntutan->type == 'TANGGUNGAN') 
                    {{ $tuntutan->tanggungan->nama ?? 'Nama Tidak Ditemui' }}
                @elseif($tuntutan->type == 'LUAR') 
                    {{ $tuntutan->ahli->nama ?? 'Nama Tidak Ditemui' }}
                @else 
                    {{ $tuntutan->nama_simpanan ?? 'Nama Tidak Ditemui' }}
                @endif
            </span>
        </div>
        <div class="info-item">
            <span class="label">NO. IC:</span>
            <span>
                @if($tuntutan->type == 'AHLI')
                    {{ $tuntutan->ahli->no_ahli ?? $tuntutan->ahli->ic ?? '-' }}
                @elseif($tuntutan->type == 'TANGGUNGAN')
                    {{ $tuntutan->tanggungan->ic_number ?? $tuntutan->ic_number ?? '-' }}
                @elseif($tuntutan->type == 'LUAR')
                    {{ $tuntutan->ahli->no_ahli ?? $tuntutan->ahli->ic ?? '-' }}
                @else
                    {{ $tuntutan->ic_number ?? '-' }}
                @endif
            </span>
        </div>
        <div class="info-item">
            <span class="label">JENIS:</span>
            <span>
                @if($tuntutan->type == 'AHLI')
                    AHLI
                @elseif($tuntutan->type == 'TANGGUNGAN')
                    TANGGUNGAN 
                @elseif($tuntutan->type == 'LUAR')
                    BUKAN AHLI 
                @else
                    Lain-lain
                @endif
            </span>
        </div>
        <div class="info-item">
            <span class="label">DIURUSKAN OLEH:</span>
            <span>{{ $tuntutan->user->nama ?? 'Admin' }}</span>
        </div>
        @if($tuntutan->type == 'TANGGUNGAN')
        <div class="info-item">
            <span class="label">TANGGUNGAN KEPADA:</span>
            <span>{{ $tuntutan->ahli->nama ?? '-' }}</span>
        </div>
        @endif
        {{-- @if($tuntutan->type == 'LUAR')
        <div class="info-item">
            <span class="label">KETERANGAN:</span>
            <span>Bukan ahli / Luar</span>
        </div>
        @endif --}}
    </div>

    <table class="details-table">
        <thead>
            <tr>
                <th>Keterangan Item</th>
                <th style="width: 120px; text-align: right;">Jumlah (RM)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tuntutan->items as $item)
            <tr>
                <td>{{ $item->item_label }} 
                @if($item->description && $item->item_label == 'Lain-lain')
                    <small style="color:#666;">({{ $item->description }})</small>
                @endif
                </td>
                <td style="text-align: right;">{{ number_format($item->amount, 2) }}</td>
            </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center; color: #999;">Tiada item direkodkan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-wrapper">
        <div class="status-box">✓ JENAZAH SELESAI DIURUSKAN</div>
        <div class="total-box">
            <span style="font-size: 11px; vertical-align: middle;">JUMLAH KESELURUHAN: </span>
            <span style="font-size: 18px; font-weight: bold; margin-left: 10px;">RM {{ number_format($tuntutan->items->sum('amount'), 2) }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Ini adalah cetakan komputer. Tiada tandatangan diperlukan.</p>
        <p style="margin-top: 5px;">Terima kasih atas sumbangan dan kepercayaan anda.</p>
    </div>
</div>

<div class="no-print">
    <button class="btn" onclick="window.print()">CETAK RESIT</button>
</div>

</body>
</html>