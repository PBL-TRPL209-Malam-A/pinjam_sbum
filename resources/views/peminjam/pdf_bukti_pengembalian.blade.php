<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Bukti Pengembalian Fasilitas</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-title {
            text-align: center;
        }
        .header-title h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header-title h3 {
            margin: 5px 0 0 0;
            font-size: 12px;
            font-weight: normal;
        }
        .doc-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .doc-title h1 {
            font-size: 16px;
            text-decoration: underline;
            margin: 0 0 5px 0;
        }
        .doc-title p {
            margin: 0;
            font-size: 12px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 6px 8px;
            vertical-align: top;
            border-bottom: 1px solid #eaeaea;
        }
        .info-table .label {
            width: 30%;
            font-weight: bold;
            color: #555;
        }
        .info-table .value {
            width: 70%;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        .status-selesai { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-dikonfirmasi_admin { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-menunggu { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        
        .sign-table {
            width: 100%;
            margin-top: 40px;
            text-align: center;
        }
        .sign-table td {
            width: 50%;
            vertical-align: bottom;
        }
        .sign-title {
            margin-bottom: 60px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .sign-role {
            font-size: 10px;
            color: #666;
        }
        .footer-note {
            margin-top: 50px;
            font-size: 10px;
            color: #777;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <table class="header-table">
        <tr>
            <td style="width: 15%; text-align: left; vertical-align: middle;">
                <img src="{{ public_path('images/logo-polibatam.png') }}" alt="Logo Polibatam" style="width: 80px; height: auto;">
            </td>
            <td class="header-title" style="width: 85%;">
                <h2>Sistem Booking & Peminjaman Fasilitas</h2>
                <h2>Layanan Administrasi Umum (SBUM)</h2>
                <h3>Gedung Utama Lt. 1, Politeknik Negeri Batam</h3>
                <h3 style="font-size: 10px; color: #555;">Email: sbum@polibatam.ac.id | Website: sbum.polibatam.ac.id</h3>
            </td>
        </tr>
    </table>

    <!-- Judul Dokumen -->
    <div class="doc-title">
        <h1>SURAT BUKTI PENGEMBALIAN FASILITAS</h1>
        <p>Nomor Bukti: SBUM-R-{{ $peminjaman->pengembalian->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->pengembalian->tanggal_kembali)->format('Y') : date('Y') }}-{{ str_pad($peminjaman->pengembalian->id_pengembalian, 4, '0', STR_PAD_LEFT) }}</p>
    </div>

    <!-- Tabel Informasi -->
    <table class="info-table">
        <tr>
            <td class="label">Nama Peminjam</td>
            <td class="value">{{ $peminjaman->user->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIM / NIM Asal</td>
            <td class="value">{{ $peminjaman->user->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Kegiatan</td>
            <td class="value"><strong>{{ $peminjaman->nama_kegiatan }}</strong></td>
        </tr>
        <tr>
            <td class="label">Fasilitas yang Dikembalikan</td>
            <td class="value">
                @if($peminjaman->jenis_peminjaman === 'ruangan')
                    Ruangan: {{ $peminjaman->ruangan->first()->nama_ruangan ?? 'N/A' }} ({{ $peminjaman->ruangan->first()->kode_ruangan ?? '-' }} - {{ $peminjaman->ruangan->first()->nama_gedung ?? '-' }})
                @else
                    Barang: {{ $peminjaman->barang->first()->nama_barang ?? 'N/A' }} ({{ $peminjaman->barang->first()->kode_barang ?? '-' }}) - Jumlah: {{ $peminjaman->barang->first()->pivot->jumlah ?? 1 }} unit
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Tanggal Kembali</td>
            <td class="value">
                {{ $peminjaman->pengembalian->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->pengembalian->tanggal_kembali)->translatedFormat('l, d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="label">Kondisi Fasilitas</td>
            <td class="value">
                <strong style="text-transform: capitalize;">{{ $peminjaman->pengembalian->kondisi_kembali }}</strong><br>
                <small>{{ $peminjaman->pengembalian->catatan_kondisi ?? '-' }}</small>
            </td>
        </tr>
        <tr>
            <td class="label">Status Pengembalian</td>
            <td class="value">
                <span class="status-badge status-{{ $peminjaman->pengembalian->status_pengembalian }}">
                    {{ str_replace('_', ' ', $peminjaman->pengembalian->status_pengembalian) }}
                </span>
            </td>
        </tr>
    </table>

    <!-- Tanda Tangan / Footer Formal -->
    <table class="sign-table">
        <tr>
            <td>
                <div class="sign-title">Peminjam Fasilitas,</div>
                <div style="height: 50px;"></div>
                <div class="sign-name">{{ $peminjaman->user->nama_lengkap ?? '-' }}</div>
                <div class="sign-role">NIM: {{ $peminjaman->user->nim ?? '-' }}</div>
            </td>
            <td>
                <div class="sign-title">Mengetahui,<br>Admin SBUM</div>
                <div style="height: 50px; font-style: italic; color: #587a68; font-size: 10px; line-height: 50px;">
                    [DIVERIFIKASI SECARA ELEKTRONIK]
                </div>
                <div class="sign-name">Admin SBUM Politeknik</div>
                <div class="sign-role">SBUM-POLIBATAM-AUTO</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini diterbitkan secara otomatis oleh Sistem Booking & Peminjaman SBUM Politeknik Negeri Batam.<br>
        Bukti ini sah dan merupakan bukti fisik bahwa peminjam terkait telah mengembalikan fasilitas.
    </div>

</body>
</html>
