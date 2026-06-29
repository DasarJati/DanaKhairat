{{-- resources/views/emails/approval_status.blade.php --}}

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Permohonan Keahlian Khairat</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header .logo {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #0f172a;
            font-size: 24px;
            margin: 0;
        }
        .header .masjid-name {
            color: #ea580c;
            font-size: 18px;
            margin-top: 5px;
            font-weight: 600;
        }
        .content {
            margin: 20px 0;
        }
        .content p {
            margin-bottom: 12px;
        }
        .message-box {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #ea580c;
            white-space: pre-line;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 24px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }
        .status-approved {
            background: #dcfce7;
            color: #166534;
        }
        .status-pending {
            background: #fef9c3;
            color: #854d0e;
        }
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
        .details {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }
        .details h3 {
            margin-top: 0;
            color: #0f172a;
            font-size: 16px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details td {
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .details td:last-child {
            border-bottom: none;
        }
        .details td:first-child {
            font-weight: bold;
            color: #64748b;
            width: 40%;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }
        .button {
            display: inline-block;
            background: #ea580c;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }
        .button:hover {
            background: #c2410c;
        }
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 25px 0;
        }
        .info-note {
            background: #f0f9ff;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            color: #0369a1;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🕌</div>
            <h1>Status Keahlian Khairat</h1>
            @if($masjid)
                <div class="masjid-name">{{ $masjid->nama }}</div>
            @endif
        </div>

        <div class="content">
            <p>Assalamualaikum <strong>{{ $user->nama }}</strong>,</p>

            <div class="message-box">
                {!! nl2br(e($messageText)) !!}
            </div>

            <div style="text-align: center; margin: 20px 0;">
                @if($user->approval_status == 'APPROVED')
                    <span class="status-badge status-approved">✅ DILULUSKAN</span>
                @elseif($user->approval_status == 'PENDING')
                    <span class="status-badge status-pending">⏳ MENUNGGU KELULUSAN</span>
                @elseif($user->approval_status == 'REJECTED')
                    <span class="status-badge status-rejected">❌ DITOLAK</span>
                @endif
            </div>

            <div class="details">
                <h3>📋 Maklumat Keahlian</h3>
                <table>
                    <tr>
                        <td>Nama Penuh</td>
                        <td><strong>{{ $user->nama }}</strong></td>
                    </tr>
                    <tr>
                        <td>No. Kad Pengenalan</td>
                        <td>{{ $user->ic_number }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td>No. Telefon</td>
                        <td>{{ $user->notel ?? $user->tel_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tarikh Daftar</td>
                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @if($user->approved_at)
                    <tr>
                        <td>Tarikh Diluluskan</td>
                        <td>{{ $user->approved_at->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            @if($user->approval_status == 'APPROVED')
                <div style="text-align: center;">
                    <a href="{{ url('/login') }}" class="button">
                        🔑 Log Masuk Ke Sistem
                    </a>
                </div>
                <div class="info-note">
                    <strong>ℹ️ Maklumat Log Masuk:</strong><br>
                    Email: <strong>{{ $user->email }}</strong><br>
                    Kata laluan lalai: <strong>password123</strong>
                    <br><span style="font-size: 12px; color: #64748b;">(Sila tukar kata laluan selepas log masuk)</span>
                </div>
            @endif

            @if($user->approval_status == 'REJECTED' && $user->rejection_reason)
                <div style="background: #fee2e2; border-radius: 8px; padding: 12px 16px; margin-top: 15px; border-left: 4px solid #dc2626;">
                    <p style="margin: 0; font-size: 13px; color: #991b1b;">
                        <strong>📌 Sebab Penolakan:</strong><br>
                        {{ $user->rejection_reason }}
                    </p>
                </div>
            @endif

            <div class="divider"></div>

            <p style="font-size: 14px; color: #64748b; text-align: center;">
                Sekiranya anda mempunyai sebarang pertanyaan, sila hubungi pihak masjid.
            </p>
        </div>

        <div class="footer">
            <p>
                Emel ini dihantar secara automatik daripada Sistem e-Khairat.<br>
                Sila jangan balas emel ini.
            </p>
            <p style="margin-top: 10px;">
                <small>© {{ date('Y') }} {{ $masjid ? $masjid->nama : 'Sistem e-Khairat' }}. Hak Cipta Terpelihara.</small>
            </p>
        </div>
    </div>
</body>
</html>