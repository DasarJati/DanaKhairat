<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $policy->big_title ?? 'Terma & Syarat' }} @if($masjid) - {{ $masjid->nama }} @endif</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f9fafb;
            color: #1f2937;
            margin: 0;
            padding: 24px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            padding: 32px;
        }

        h1 {
            font-size: 22px;
            font-weight: 800;
            color: #ea580c;
            margin-bottom: 4px;
        }

        .masjid-name {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 24px;
        }

        .section {
            border-top: 1px solid #f3f4f6;
            padding: 20px 0;
        }

        .section:first-of-type {
            border-top: none;
        }

        .section h2 {
            font-size: 15px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
        }

        .section p {
            font-size: 14px;
            line-height: 1.7;
            color: #4b5563;
            white-space: pre-line;
        }

        .empty {
            text-align: center;
            color: #9ca3af;
            padding: 40px 0;
        }

        .close-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 24px;
            font-size: 13px;
            color: #ea580c;
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        @if($policy)
            <h1>{{ $policy->big_title }}</h1>
            @if($masjid)
                <p class="masjid-name"><i class="fas fa-mosque"></i> {{ $masjid->nama }}</p>
            @endif

            @forelse($policy->sections as $section)
                <div class="section">
                    <h2>{{ $section->title }}</h2>
                    <p>{{ $section->description }}</p>
                </div>
            @empty
                <div class="empty">Tiada kandungan terma & syarat ditetapkan buat masa ini.</div>
            @endforelse
        @else
            <div class="empty">
                <i class="fas fa-info-circle" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                Terma & Syarat belum ditetapkan untuk masjid ini. Sila hubungi pentadbir.
            </div>
        @endif

        <a href="javascript:window.close()" class="close-btn">
            <i class="fas fa-times"></i> Tutup Tetingkap
        </a>
    </div>
</body>

</html>