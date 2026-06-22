<p>Assalamualaikum {{ $app->nama_masjid }},</p>

<p>
    Permohonan pendaftaran masjid anda telah
    <strong>berjaya diluluskan</strong>.
</p>

<hr>

<h3>Maklumat Pembayaran</h3>

<table width="100%" border="1" cellpadding="5">

    <tr>
        <td>Nama Masjid</td>
        <td>{{ $masjid->nama }}</td>
    </tr>

    <tr>
        <td>Tarikh</td>
        {{ \Carbon\Carbon::parse($masjid->created_at)->format('d/m/Y') }}
    </tr>

    <tr>
        <td>Jumlah Bayaran</td>
        <td>
            <strong>
                RM {{ number_format($Amount,2) }}
            </strong>
        </td>
    </tr>

</table>

<br>

<p>

    <a href="{{ url('/login') }}">
        Log Masuk Sistem
    </a>

</p>

<p>Terima kasih.</p>