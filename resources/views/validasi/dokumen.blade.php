<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Dokumen Peminjaman - Polibatam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .valid-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-top: 5px solid #28a745;
        }
        .status-icon {
            width: 80px;
            height: 80px;
            background: #d4edda;
            color: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        .status-icon svg {
            width: 40px;
            height: 40px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="valid-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="status-icon mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="fw-bold text-success mb-1">DOKUMEN VALID</h3>
                        <p class="text-muted small">Telah disahkan secara elektronik</p>
                    </div>

                    <div class="mb-4 pb-4 border-bottom">
                        <h6 class="fw-bold text-secondary mb-3">KETERANGAN PENERBITAN</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Penerbit:</span>
                            @php
                                $displayText = 'Kepala Bagian SBUM';
                            @endphp
                            <span class="fw-semibold">{{ $displayText }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Waktu Terbit:</span>
                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($tanggalTerbit)->translatedFormat('d F Y, H:i') }} WIB</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">ID Peminjaman:</span>
                            <span class="fw-semibold text-primary">PMJ-{{ str_pad($peminjaman->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-secondary mb-3">RINCIAN PEMINJAMAN</h6>
                        
                        <div class="mb-3">
                            <label class="text-muted small d-block">Nama Peminjam</label>
                            <div class="fw-bold">{{ $peminjaman->user->nama_lengkap ?? '-' }}</div>
                            <div class="small text-muted">{{ $peminjaman->user->nim ?? $peminjaman->user->nik ?? '-' }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small d-block">Kegiatan</label>
                            <div class="fw-bold">{{ $peminjaman->nama_kegiatan }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small d-block">Fasilitas Disetujui</label>
                            <div class="fw-bold text-primary">{{ $peminjaman->nama_fasilitas_with_type }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small d-block">Waktu Pelaksanaan</label>
                            <div class="fw-bold">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengajuan)->translatedFormat('d F Y') }} <br>
                                Pukul {{ str_replace(':', '.', substr($peminjaman->jam_mulai, 0, 5)) }} - {{ str_replace(':', '.', substr($peminjaman->jam_selesai, 0, 5)) }} WIB
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted small d-block">Status Saat Ini</label>
                            @php
                                $statusLabel = str_replace('_', ' ', \Illuminate\Support\Str::title($peminjaman->status));
                                $statusColor = 'secondary';
                                if (in_array($peminjaman->status, ['siap_digunakan', 'selesai', 'disetujui'])) {
                                    $statusColor = 'success';
                                    $statusLabel = $peminjaman->status == 'selesai' ? 'Selesai / Dikembalikan' : 'Disetujui & Siap Digunakan';
                                } elseif (in_array($peminjaman->status, ['ditolak', 'batal', 'bermasalah'])) {
                                    $statusColor = 'danger';
                                    if ($peminjaman->status == 'bermasalah') $statusLabel = 'Bermasalah (Ada Catatan)';
                                } else {
                                    $statusColor = 'warning text-dark';
                                }
                            @endphp
                            <div class="badge bg-{{ $statusColor }} px-3 py-2 mt-1" style="font-size: 0.85rem;">{{ $statusLabel }}</div>
                        </div>
                    </div>
                    
                    <div class="alert alert-success bg-opacity-10 border-success border-opacity-25 mt-4 mb-0 text-center">
                        <small>Data ini ditarik langsung secara <i>real-time</i> dari Sistem Informasi Peminjaman Fasilitas SBUM Polibatam.</small>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <img src="{{ asset('assets/images/logo_polibatam.png') }}" alt="Polibatam" style="height: 40px; opacity: 0.5;">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
