{{-- resources/views/emails/subscription_expiring.blade.php --}}
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Keahlian</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #f97316;
        }
        .header h1 {
            color: #f97316;
            margin: 0;
        }
        .content {
            padding: 20px 0;
        }
        .warning-box {
            background: #fff7ed;
            border-left: 4px solid #f97316;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .details {
            background: #f9fafb;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            background: #f97316;
            color: #ffffff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 0;
        }
        .btn:hover {
            background: #ea580c;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
        .highlight {
            color: #f97316;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🕌 Peringatan Keahlian</h1>
        </div>

        <div class="content">
            <p>Salam sejahtera <strong>{{ $user->nama }}</strong>,</p>

            <div class="warning-box">
                <p><strong>⚠️ Perhatian!</strong></p>
                <p>Keahlian anda akan tamat tempoh dalam <strong>{{ $daysRemaining }} hari</strong> lagi.</p>
            </div>

            <div class="details">
                <h3>📋 Maklumat Keahlian</h3>
                <p><strong>Tarikh Mula:</strong> {{ \Carbon\Carbon::parse($subscription->start_date)->format('d/m/Y') }}</p>
                <p><strong>Tarikh Tamat:</strong> <span class="highlight">{{ \Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y') }}</span></p>
                <p><strong>Status:</strong> {{ ucfirst($subscription->status) }}</p>
                <p><strong>Jumlah Bayaran:</strong> RM {{ number_format($subscription->price, 2) }}</p>
            </div>

            <div class="info-box">
                <p><strong>💡 Tindakan Anda</strong></p>
                <p>Sila perbaharui keahlian anda sebelum tarikh tamat untuk memastikan perkhidmatan anda tidak terganggu.</p>
                <p>Anda boleh memperbaharui keahlian melalui <a href="{{ url('/login') }}">portal kami</a>.</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Log Masuk & Perbaharui</a>
            </div>

            <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">
                Jika anda mempunyai sebarang pertanyaan, sila hubungi pihak pentadbir masjid anda.
            </p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} DanaKhairat. Hak cipta terpelihara.</p>
            <p>Email ini dihantar secara automatik. Sila jangan balas email ini.</p>
        </div>
    </div>
</body>
</html>