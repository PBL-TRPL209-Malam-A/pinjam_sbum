@extends('layout.admin')

@section('page_caption', 'Kelola Data Peminjaman')
@section('page_heading', 'Admin SBUM')

@section('admin_content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .custom-table {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        overflow: hidden;
    }
    .custom-table th {
        background-color: #f7f3eb;
        color: var(--text-main);
        font-weight: 600;
        border: none;
        padding: 1rem 1.5rem;
    }
    .custom-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--line);
        color: var(--text-main);
    }
    .badge-menunggu {
        background-color: #fcf1d3;
        color: #7d6006;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
    .badge-disetujui {
        background-color: #e2f0d9;
        color: #385723;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
    .badge-ditolak {
        background-color: #fcebeb;
        color: #8b3c3c;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
    .btn-edit-outline {
        border: 1px solid var(--line);
        background: white;
        color: var(--text-main);
        font-weight: 500;
        border-radius: 0.75rem;
        padding: 0.4rem 1.5rem;
        transition: 0.2s;
    }
    .btn-edit-outline:hover {
        background: #fdfcf9;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Catat dan kelola seluruh data peminjaman</h2>
        <p class="mb-0 text-secondary text-wrap">
            Admin dapat melihat, mengedit, dan memperbarui data peminjaman untuk memastikan riwayat penggunaan tercatat rapi.
        </p>
    </div>
</div>

<!-- Controls Bar -->
<form action="{{ route('admin.peminjaman') }}" method="GET" class="card border-0 rounded-4 p-3 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
    <div class="row g-3 align-items-end">
        <!-- Search bar -->
        <div class="col-md-3 text-start">
            <label class="form-label text-secondary small fw-semibold">Cari Kegiatan / Peminjam</label>
            <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}" style="border-radius: 0.75rem; border-color: #dfd4c8; font-size: 0.9rem;">
        </div>
        <!-- Status Filter -->
        <div class="col-md-3 text-start">
            <label class="form-label text-secondary small fw-semibold">Status</label>
            <select name="status" class="form-select" style="border-radius: 0.75rem; border-color: #dfd4c8; font-size: 0.9rem;">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <!-- Date Picker Filter -->
        <div class="col-md-3 text-start">
            <label class="form-label text-secondary small fw-semibold">Tanggal Acara</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}" style="border-radius: 0.75rem; border-color: #dfd4c8; font-size: 0.9rem;">
        </div>
        <!-- Buttons -->
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-main flex-grow-1" style="height: 38px; border-radius: 0.75rem; min-width: auto; font-size: 0.9rem;">Filter</button>
            <button type="button" id="exportPdfBtn" class="btn btn-edit-outline" style="height: 38px; border-radius: 0.75rem; border: 1px solid var(--primary-main); color: var(--primary-main); font-size: 0.9rem;">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
            </button>
        </div>
    </div>
</form>

<!-- Table Area -->
<div class="mb-3 fw-semibold text-secondary">Riwayat Peminjaman</div>
<div id="peminjamanTableContainer" class="custom-table mb-3" style="overflow-x: auto; background: #fffdfa;">
    <table class="table table-borderless mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal & Waktu</th>
                <th>Peminjam</th>
                <th>Fasilitas</th>
                <th>Nama Acara</th>
                <th>Keterangan Acara</th>
                <th>Dosen PJ</th>
                <th>PIC Fasilitas</th>
                <th>Status</th>
                <th>Tahapan Persetujuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $p)
                @php
                    $statusText = 'Pending';
                    $badgeClass = 'badge-menunggu';
                    $tahapanText = 'Menunggu Verifikasi';

                    switch($p->status) {
                        case 'menunggu_dosen':
                            $statusText = 'Pending';
                            $badgeClass = 'badge-menunggu';
                            $tahapanText = 'Menunggu Dosen PJ';
                            break;
                        case 'menunggu_admin':
                            $statusText = 'Pending';
                            $badgeClass = 'badge-menunggu';
                            $tahapanText = 'Menunggu Admin';
                            break;
                        case 'menunggu_kepala':
                            $statusText = 'Pending';
                            $badgeClass = 'badge-menunggu';
                            $tahapanText = 'Menunggu Kepala';
                            break;
                        case 'menunggu_pic':
                            $statusText = 'Pending';
                            $badgeClass = 'badge-menunggu';
                            $tahapanText = 'Menunggu PIC';
                            break;
                        case 'siap_digunakan':
                            $statusText = 'Diterima';
                            $badgeClass = 'badge-disetujui';
                            $tahapanText = 'Verifikasi Tuntas';
                            break;
                        case 'selesai':
                            $statusText = 'Selesai';
                            $badgeClass = 'badge-disetujui';
                            $tahapanText = 'Sudah Dikembalikan';
                            break;
                        case 'proses_pengembalian':
                            $statusText = 'Pengembalian';
                            $badgeClass = 'badge-menunggu';
                            $tahapanText = 'Proses Pengembalian';
                            break;
                        case 'ditolak':
                            $statusText = 'Ditolak';
                            $badgeClass = 'badge-ditolak';
                            $tahapanText = 'Ditolak';
                            break;
                        case 'pending':
                        case 'revisi':
                        default:
                            $statusText = 'Pending';
                            $badgeClass = 'badge-menunggu';
                            $tahapanText = 'Menunggu Perbaikan';
                            break;
                    }
                @endphp
                <tr>
                    <td class="fw-semibold">SBUM-2026-{{ str_pad($p->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <span class="fw-semibold">{{ $p->tanggal_pengajuan ? \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }}</span>
                        <br>
                        <span class="small text-muted">{{ $p->jam_mulai ? substr($p->jam_mulai, 0, 5) : '08:00' }} - {{ $p->jam_selesai ? substr($p->jam_selesai, 0, 5) : '12:00' }}</span>
                    </td>
                    <td>{{ $p->user->nama_lengkap ?? '-' }}</td>
                    <td>
                        {{ $p->ruangan->isNotEmpty() ? $p->ruangan->first()->nama_ruangan : ($p->barang->isNotEmpty() ? $p->barang->first()->nama_barang : '-') }}
                    </td>
                    <td>{{ $p->nama_kegiatan }}</td>
                    <td>{{ $p->keterangan ?: '-' }}</td>
                    <td>{{ $p->dosen->nama_lengkap ?? '-' }}</td>
                    <td>
                        @if($p->ruangan->isNotEmpty())
                            {{ $p->ruangan->first()->pic->nama_lengkap ?? '-' }}
                        @elseif($p->barang->isNotEmpty())
                            {{ $p->barang->first()->pic->nama_lengkap ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'proses_pengembalian')
                            <a href="{{ route('pic.pengembalian', ['selected_id' => $p->id_peminjaman]) }}" class="text-decoration-none">
                                <span class="{{ $badgeClass }}">{{ $statusText }}</span>
                            </a>
                        @else
                            <span class="{{ $badgeClass }}">{{ $statusText }}</span>
                        @endif
                    </td>
                    <td class="small text-muted">{{ $tahapanText }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">Tidak ada data riwayat peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div class="d-flex justify-content-end mb-4">
    {{ $peminjaman->links('pagination::bootstrap-5') }}
</div>

<!-- Explainer & Export Scripts -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4 mb-4">
    <div class="card border-0 rounded-4 p-4" style="background: #fffdfa; border: 1px solid var(--line) !important; max-width: 450px;">
        <div class="text-secondary small fw-semibold">Detail Cepat</div>
        <div class="text-muted small mt-1">Di layar ini admin bisa membuka detail peminjaman, mengubah status, atau memperbarui catatan transaksi.</div>
    </div>
</div>

<!-- PDF Export library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('exportPdfBtn').addEventListener('click', function() {
        const element = document.getElementById('peminjamanTableContainer');
        const opt = {
            margin:       0.3,
            filename:     'riwayat_peminjaman_sbum.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        };
        html2pdf().set(opt).from(element).save();
    });
</script>
@endsection
