<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Bukti Peminjaman Fasilitas</title>
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
            margin-bottom: 25px;
        }
        .doc-title h1 {
            margin: 0;
            font-size: 18px;
            text-decoration: underline;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .doc-title p {
            margin: 5px 0 0 0;
            font-size: 11px;
            color: #666;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .info-table td {
            padding: 8px 10px;
            vertical-align: top;
        }
        .info-table td.label {
            width: 30%;
            font-weight: bold;
            color: #555;
            background-color: #fcfcfc;
            border: 1px solid #eee;
        }
        .info-table td.value {
            width: 70%;
            border: 1px solid #eee;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            font-weight: bold;
            border-radius: 3px;
            font-size: 10px;
            text-transform: uppercase;
        }
        .status-siap_digunakan { background-color: #d4edda; color: #155724; }
        .status-selesai { background-color: #cce5ff; color: #004085; }
        .status-menunggu_dosen { background-color: #fff3cd; color: #856404; }
        .status-menunggu_admin { background-color: #e2e3e5; color: #383d41; }
        .status-menunggu_kepala { background-color: #f8d7da; color: #721c24; }
        .status-menunggu_pic { background-color: #fff3cd; color: #856404; }
        .status-ditolak { background-color: #f8d7da; color: #721c24; }
        .status-revisi { background-color: #fff3cd; color: #856404; }
        
        .sign-table {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .sign-table td {
            width: 50%;
            text-align: center;
            padding-bottom: 60px;
        }
        .sign-title {
            font-weight: bold;
            margin-bottom: 50px;
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
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-size: 10px;
            color: #888;
            text-align: center;
        }
        .verif-log-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        .verif-log-table th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        .verif-log-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <table class="header-table">
        <tr>
            <td style="width: 15%; text-align: left;">
                <div style="font-weight: bold; font-size: 24px; color: #587a68; border: 3px solid #587a68; padding: 5px; text-align: center; display: inline-block; width: 60px;">
                    SBUM
                </div>
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
        <h1>SURAT BUKTI PEMINJAMAN FASILITAS</h1>
        <p>Nomor Bukti: SBUM-{{ $peminjaman->tanggal_pengajuan ? $peminjaman->tanggal_pengajuan->format('Y') : date('Y') }}-{{ str_pad($peminjaman->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</p>
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
            <td class="label">Fasilitas yang Dipinjam</td>
            <td class="value">
                @if($peminjaman->jenis_peminjaman === 'ruangan')
                    Ruangan: {{ $peminjaman->ruangan->first()->nama_ruangan ?? 'N/A' }} ({{ $peminjaman->ruangan->first()->kode_ruangan ?? '-' }} - {{ $peminjaman->ruangan->first()->nama_gedung ?? '-' }})
                @else
                    Barang: {{ $peminjaman->barang->first()->nama_barang ?? 'N/A' }} ({{ $peminjaman->barang->first()->kode_barang ?? '-' }}) - Jumlah: {{ $peminjaman->barang->first()->pivot->jumlah ?? 1 }} unit
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Waktu Peminjaman</td>
            <td class="value">
                {{ $peminjaman->tanggal_pengajuan ? \Carbon\Carbon::parse($peminjaman->tanggal_pengajuan)->translatedFormat('l, d F Y') : '-' }} <br>
                Jam: {{ $peminjaman->jam_mulai ? substr($peminjaman->jam_mulai, 0, 5) : '08:00' }} s.d. {{ $peminjaman->jam_selesai ? substr($peminjaman->jam_selesai, 0, 5) : '12:00' }} WIB
            </td>
        </tr>
        <tr>
            <td class="label">Jumlah Estimasi Peserta</td>
            <td class="value">{{ $peminjaman->jumlah_peserta ?? '-' }} Orang</td>
        </tr>
        <tr>
            <td class="label">Keterangan / Kebutuhan</td>
            <td class="value">{{ $peminjaman->keterangan ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Status Permohonan</td>
            <td class="value">
                <span class="status-badge status-{{ $peminjaman->status }}">
                    {{ str_replace('_', ' ', $peminjaman->status) }}
                </span>
            </td>
        </tr>
    </table>

    <!-- Log Verifikasi -->
    <h3 style="margin-bottom: 8px; font-size: 12px; border-bottom: 1px solid #ccc; padding-bottom: 3px;">LOG VERIFIKASI DOKUMEN</h3>
    <table class="verif-log-table">
        <thead>
            <tr>
                <th style="width: 25%;">Peran Verifikasi</th>
                <th style="width: 35%;">Nama Pemeriksa</th>
                <th style="width: 15%;">Keputusan</th>
                <th style="width: 25%;">Tanggal & Waktu</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dosen Penanggung Jawab -->
            <tr>
                <td>Dosen Penanggung Jawab</td>
                <td>{{ $peminjaman->dosen->nama_lengkap ?? '-' }}</td>
                <td>
                    @if(in_array($peminjaman->status, ['menunggu_admin', 'menunggu_kepala', 'menunggu_pic', 'siap_digunakan', 'selesai']))
                        Disetujui
                    @elseif($peminjaman->status === 'ditolak')
                        Ditolak / Batal
                    @elseif($peminjaman->status === 'revisi')
                        Butuh Revisi
                    @else
                        Menunggu
                    @endif
                </td>
                <td>
                    @php
                        $dosenLog = $peminjaman->verifikasi->where('peran_verifikasi', 'Dosen')->first();
                    @endphp
                    {{ $dosenLog ? \Carbon\Carbon::parse($dosenLog->tanggal)->translatedFormat('d M Y H:i') : '-' }}
                </td>
            </tr>
            <!-- Admin SBUM -->
            <tr>
                <td>Admin SBUM (Operasional)</td>
                <td>Staf Administrasi SBUM</td>
                <td>
                    @if(in_array($peminjaman->status, ['menunggu_kepala', 'menunggu_pic', 'siap_digunakan', 'selesai']))
                        Disetujui
                    @elseif($peminjaman->status === 'menunggu_admin')
                        Menunggu
                    @else
                        -
                    @endif
                </td>
                <td>
                    @php
                        $adminLog = $peminjaman->verifikasi->where('peran_verifikasi', 'Admin SBUM')->first();
                    @endphp
                    {{ $adminLog ? \Carbon\Carbon::parse($adminLog->tanggal)->translatedFormat('d M Y H:i') : '-' }}
                </td>
            </tr>
            <!-- Kepala SBUM -->
            <tr>
                <td>Kepala Bagian SBUM</td>
                <td>Kepala SBUM</td>
                <td>
                    @if(in_array($peminjaman->status, ['menunggu_pic', 'siap_digunakan', 'selesai']))
                        Disetujui
                    @elseif($peminjaman->status === 'menunggu_kepala')
                        Menunggu
                    @else
                        -
                    @endif
                </td>
                <td>
                    @php
                        $kepalaLog = $peminjaman->verifikasi->where('peran_verifikasi', 'Kepala SBUM')->first();
                    @endphp
                    {{ $kepalaLog ? \Carbon\Carbon::parse($kepalaLog->tanggal)->translatedFormat('d M Y H:i') : '-' }}
                </td>
            </tr>
            <!-- PIC Fasilitas -->
            <tr>
                <td>PIC Kesiapan Fasilitas</td>
                <td>
                    @if($peminjaman->jenis_peminjaman === 'ruangan')
                        {{ $peminjaman->ruangan->first()->pic->nama_lengkap ?? '-' }}
                    @else
                        {{ $peminjaman->barang->first()->pic->nama_lengkap ?? '-' }}
                    @endif
                </td>
                <td>
                    @if(in_array($peminjaman->status, ['siap_digunakan', 'selesai']))
                        Disetujui
                    @elseif($peminjaman->status === 'menunggu_pic')
                        Menunggu
                    @else
                        -
                    @endif
                </td>
                <td>
                    @php
                        $picLog = $peminjaman->verifikasi->where('peran_verifikasi', 'PIC Fasilitas')->first();
                    @endphp
                    {{ $picLog ? \Carbon\Carbon::parse($picLog->tanggal)->translatedFormat('d M Y H:i') : '-' }}
                </td>
            </tr>
        </tbody>
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
                <div class="sign-title">Mengetahui,<br>Kepala Bagian SBUM</div>
                <div style="height: 50px; font-style: italic; color: #587a68; font-size: 10px; line-height: 50px;">
                    [DIVERIFIKASI SECARA ELEKTRONIK]
                </div>
                <div class="sign-name">Kepala SBUM Politeknik</div>
                <div class="sign-role">NIP. SBUM-POLIBATAM-AUTO</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini diterbitkan secara otomatis oleh Sistem Booking & Peminjaman SBUM Politeknik Negeri Batam.<br>
        Bukti ini sah dan tidak memerlukan tanda tangan basah selama log verifikasi sistem dinyatakan lengkap dan disetujui.
    </div>

</body>
</html>
