<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengesahan Pendaftaran</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f5f5f7;
            margin: 0;
            padding: 0;
            color: #1d1d1f;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 580px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #e5e5ea;
        }
        .header {
            background: #ffffff;
            padding: 40px 32px 20px;
            text-align: center;
            border-bottom: 1px solid #f2f2f7;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 600;
            color: #1d1d1f;
            letter-spacing: -0.5px;
        }
        .header .subtitle {
            margin-top: 8px;
            font-size: 15px;
            color: #86868b;
            font-weight: 400;
        }
        .content {
            padding: 32px 32px;
        }
        .content h2 {
            color: #1d1d1f;
            font-size: 21px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 18px;
            letter-spacing: -0.3px;
        }
        .content p {
            font-size: 15px;
            line-height: 1.6;
            color: #424245;
            margin-bottom: 16px;
        }
        .info-box {
            background: #f5f5f7;
            padding: 20px;
            border-radius: 12px;
            margin: 24px 0;
            border: 1px solid #e5e5ea;
        }
        .info-box strong {
            color: #1d1d1f;
        }
        .status-wrapper {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-badge {
            display: inline-block;
            background: #e4e4e6;
            color: #424245;
            font-size: 12px;
            font-weight: 500;
            padding: 4px 12px;
            border-radius: 12px;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 24px;
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border: 1px solid #e5e5ea;
        }
        .details-grid .label {
            font-size: 12px;
            color: #86868b;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .details-grid .value {
            font-size: 15px;
            font-weight: 500;
            color: #1d1d1f;
        }
        .details-grid .value.highlight {
            color: #1d1d1f;
            font-weight: 600;
        }
        .divider {
            border: none;
            border-top: 1px solid #e5e5ea;
            margin: 28px 0;
        }
        .section-title {
            font-size: 16px; 
            color: #1d1d1f; 
            margin-bottom: 16px;
            font-weight: 600;
        }
        .step-item {
            display: flex; 
            gap: 16px; 
            margin-bottom: 16px; 
            align-items: flex-start;
        }
        .step-number {
            background: #f5f5f7; 
            color: #1d1d1f; 
            border-radius: 50%; 
            width: 24px; 
            height: 24px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: 600; 
            font-size: 13px; 
            flex-shrink: 0;
            border: 1px solid #e5e5ea;
        }
        .step-text strong {
            color: #1d1d1f;
            font-size: 15px;
        }
        .step-text p {
            margin: 4px 0 0; 
            font-size: 14px; 
            color: #86868b;
        }
        .contact-box {
            text-align: center; 
            padding: 16px 0;
        }
        .contact-box p {
            color: #86868b; 
            font-size: 14px;
        }
        .contact-box a {
            color: #0066cc; 
            text-decoration: none; 
            font-weight: 400;
        }
        .contact-box a:hover {
            text-decoration: underline;
        }
        .btn-wrapper {
            text-align: center; 
            margin-top: 28px;
        }
        .btn {
            display: inline-block;
            background: #0066cc;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 400;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #0077ed;
        }
        .footer {
            background: #f5f5f7;
            padding: 24px 32px;
            text-align: center;
            font-size: 12px;
            color: #86868b;
            border-top: 1px solid #e5e5ea;
            line-height: 1.5;
        }
        .footer .masjid-name {
            font-weight: 500; 
            color: #1d1d1f;
            margin-bottom: 4px;
        }
        .email-footer {
            text-align: center;
            padding: 24px 32px;
            font-size: 12px;
            color: #86868b;
        }
        @media (max-width: 480px) {
            .details-grid {
                grid-template-columns: 1fr;
                gap: 12px 0;
                padding: 16px;
            }
            .header h1 {
                font-size: 22px;
            }
            .content {
                padding: 24px 20px;
            }
            .container {
                margin: 10px;
                border-radius: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Pendaftaran Berjaya</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Assalamualaikum {{ $user->nama }},</h2>

            <p>
                Terima kasih kerana mendaftar sebagai ahli khairat <strong>{{ $masjidName }}</strong>.
            </p>

            <p>
                Permohonan anda telah berjaya dihantar dan sedang dalam proses pengesahan oleh pihak pengurusan.
            </p>

            <div class="info-box">
                <div class="status-wrapper">
                    <strong style="font-size: 15px;">Status Permohonan</strong>
                    <span class="status-badge">Dalam Semakan</span>
                </div>
                <p style="margin-top: 8px; margin-bottom: 0; font-size: 14px; color: #6e6e73;">
                    Sila tunggu untuk proses pengesahan. Anda akan dimaklumkan melalui e-mel ini setelah permohonan diluluskan.
                </p>
            </div>

            <hr class="divider">

            <h3 class="section-title">Ringkasan Permohonan</h3>

            <div class="details-grid">
                <div>
                    <div class="label">Nama Penuh</div>
                    <div class="value">{{ $user->nama }}</div>
                </div>
                <div>
                    <div class="label">No. Kad Pengenalan</div>
                    <div class="value">{{ $user->ic_number }}</div>
                </div>
                <div>
                    <div class="label">E-mel</div>
                    <div class="value">{{ $user->email }}</div>
                </div>
                <div>
                    <div class="label">No. Telefon</div>
                    <div class="value">{{ $user->telefon_bimbit ?? 'Tiada' }}</div>
                </div>
                <div>
                    <div class="label">Jantina</div>
                    <div class="value">{{ $user->jantina ?? 'Tiada' }}</div>
                </div>
                <div>
                    <div class="label">Status</div>
                    <div class="value">{{ $user->statususer ?? 'Tiada' }}</div>
                </div>
                <div>
                    <div class="label">Jumlah Tanggungan</div>
                    <div class="value">{{ $tanggunganCount }} orang</div>
                </div>
                <div>
                    <div class="label">Jumlah Bayaran</div>
                    <div class="value highlight">RM {{ number_format($totalAmount, 2) }}</div>
                </div>
            </div>

            <hr class="divider">

            <h3 class="section-title">Langkah Seterusnya</h3>

            <div style="padding: 0 2px;">
                <div class="step-item">
                    <span class="step-number">1</span>
                    <div class="step-text">
                        <strong>Pengesahan oleh Pengurusan</strong>
                        <p>Pihak pengurusan akan menyemak maklumat yang dihantar.</p>
                    </div>
                </div>
                <div class="step-item">
                    <span class="step-number">2</span>
                    <div class="step-text">
                        <strong>Pemberitahuan Kelulusan</strong>
                        <p>Anda akan menerima e-mel pengesahan setelah diluluskan.</p>
                    </div>
                </div>
                <div class="step-item">
                    <span class="step-number">3</span>
                    <div class="step-text">
                        <strong>Arahan Pembayaran</strong>
                        <p>Pembayaran akan dilakukan selepas kelulusan.</p>
                    </div>
                </div>
            </div>

            <hr class="divider">

        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="masjid-name">{{ $masjidName }}</div>
            <div>Dana Khairat</div>
            <div style="margin-top: 10px; color: #86868b; font-size: 11px;">
                E-mel ini dihantar secara automatik. Sila jangan balas e-mel ini.
            </div>
        </div>

        
    </div>
</body>
</html>