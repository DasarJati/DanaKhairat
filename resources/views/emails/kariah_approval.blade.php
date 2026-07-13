<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengesahan Akaun Ahli Kariah</title>
    <style>
        /* Apple-inspired Minimalist Styling */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f5f5f7;
            color: #1d1d1f;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .email-container {
            max-width: 560px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 32px;
        }
        .brand-icon {
            font-size: 40px;
            margin-bottom: 12px;
            display: inline-block;
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.5px;
            margin: 0 0 8px 0;
            color: #1d1d1f;
        }
        .salutation {
            font-size: 17px;
            color: #1d1d1f;
            font-weight: 500;
            margin-bottom: 6px;
            text-align: center;
        }
        .congrats-text {
            font-size: 15px;
            color: #515154;
            margin: 0 0 20px 0;
            text-align: center;
        }
        .status-badge {
            background-color: #e3f9e5;
            color: #1a7f37;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 24px;
        }
        .card {
            background: #fbfbfd;
            border: 1px solid #e8e8ed;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 28px;
        }
        .card-title {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #86868b;
            margin-top: 0;
            margin-bottom: 16px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f2f2f7;
            font-size: 15px;
        }
        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .info-label {
            color: #86868b;
        }
        .info-value {
            font-weight: 500;
            color: #1d1d1f;
            text-align: right;
            padding-left: 12px;
            padding-right: 0;
        }
        .footer-text {
            font-size: 15px;
            line-height: 1.6;
            color: #515154;
            text-align: center;
            margin-bottom: 32px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #86868b;
            border-top: 1px solid #e8e8ed;
            padding-top: 24px;
        }
        .login-button {
            display: inline-block;
            background-color: #007aff;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 32px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 16px;
            margin: 8px 0 16px 0;
            transition: background-color 0.2s;
        }
        .login-button:hover {
            background-color: #005bbf;
        }
        .login-link {
            display: block;
            color: #007aff;
            text-decoration: none;
            font-weight: 500;
            margin-top: 4px;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="email-container">
        
        <!-- Header / Logo Section -->
        <div class="header">
            <div class="brand-icon">🕌</div>
            <h1>Pengesahan Keahlian Khairat</h1>
        </div>

        <div style="text-align: center;">
            <p class="salutation">Assalamualaikum {{ $user->nama }},</p>
            <p class="congrats-text">Tahniah, permohonan keahlian anda telah diluluskan.</p>
            <span class="status-badge">Akaun Aktif</span>
        </div>

        <!-- Information Card -->
        <div class="card">
            <p class="card-title">Maklumat Log Masuk</p>
            
            <div class="info-row ">
                <span class="info-label">E-mel </span>
                <span class="info-value">{{ $user->email }}</span>
            </div>

            <div class="info-row ">
                <span class="info-label">Kata Laluan</span>
                <span class="info-value" style="font-family: monospace; letter-spacing: 0.5px;">{{ $password }}</span>
            </div>
        </div>

        <!-- Message Body -->
        <p class="footer-text">
            Sila gunakan kredensial di atas untuk log masuk ke dalam <strong>Sistem Dana Khairat</strong> bagi urusan pengurusan profil serta melengkapkan bayaran keahlian anda. Demi keselamatan akaun, anda dinasihatkan untuk menukar kata laluan selepas log masuk buat kali pertama.
        </p>

        <!-- Login Button -->
        <div style="text-align: center; margin: 24px 0 28px 0;">
            <a href="https://danakhairat.my/login" class="login-button">🔐 Log Masuk Sebagai Ahli</a>
            <br>
            <a href="https://danakhairat.my/login" class="login-link">https://danakhairat.my</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>E-mel ini dijanakan secara automatik oleh sistem. Sila jangan balas e-mel ini.</p>
        </div>

    </div>

</body>
</html>