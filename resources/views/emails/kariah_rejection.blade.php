<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Keahlian Kariah - Tidak Diluluskan</title>
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
        .status-badge {
            background-color: #fee2e2;
            color: #dc2626;
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
        .message-body {
            font-size: 15px;
            line-height: 1.8;
            color: #515154;
            margin-bottom: 32px;
        }
        .reason-box {
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 16px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .reason-box p {
            margin: 0;
            color: #1d1d1f;
            font-size: 15px;
            line-height: 1.6;
        }
        .reason-box strong {
            color: #dc2626;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #86868b;
            border-top: 1px solid #e8e8ed;
            padding-top: 24px;
        }
        .contact-link {
            color: #007aff;
            text-decoration: none;
        }
        .contact-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="email-container">
        
        <!-- Header / Logo Section -->
        <div class="header">
            <div class="brand-icon">🕌</div>
            <h1>Permohonan Keahlian Khairat</h1>
        </div>

        <div style="text-align: center;">
            <p class="salutation">Assalamualaikum {{ $user->nama }},</p>
            <span class="status-badge">❌ Tidak Diluluskan</span>
        </div>

        

        <!-- Message Body -->
        <div class="message-body">
            <p>Maaf, permohonan keahlian khairat anda telah <strong>tidak diluluskan</strong> oleh pihak pengurusan.</p>
            
            {{-- <div class="reason-box">
                <p><strong>📌 Sebab Penolakan:</strong><br>
                {{ $rejectionReason }}</p>
            </div> --}}

            <p>Jika anda mempunyai sebarang pertanyaan atau ingin membuat permohonan semula, sila hubungi pihak pengurusan untuk mendapatkan maklumat lanjut.</p>
            
            <p>Kami berharap anda dapat memohon semula pada masa akan datang.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>E-mel ini dijanakan secara automatik oleh sistem. Sila jangan balas e-mel ini.</p>
        </div>

    </div>

</body>
</html>