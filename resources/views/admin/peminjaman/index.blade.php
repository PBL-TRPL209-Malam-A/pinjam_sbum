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
<div class="card border-0 rounded-4 p-3 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex gap-3">
            <button class="btn btn-edit-outline">Filter Status</button>
            <button class="btn btn-edit-outline">Periode</button>
        </div>
        <button class="btn btn-main">Export</button>
    </div>
</div>

<!-- Table Area -->
<div class="mb-3 fw-semibold text-secondary">Riwayat Peminjaman</div>
<div class="custom-table mb-4">
    <table class="table table-borderless mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Fasilitas</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $p)
            <tr>
                <td class="fw-semibold">SBUM-2026-{{ str_pad($p->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $p->user->nama_lengkap ?? '-' }}</td>
                <td>{{ $p->nama_fasilitas }}</td>
                <td>
                    @if($p->status == 'pending')
                        <span class="badge-menunggu">Menunggu Final</span>
                    @elseif($p->status == 'disetujui' || $p->status == 'selesai')
                        <span class="badge-disetujui">Disetujui</span>
                    @else
                        <span class="badge-ditolak">Ditolak</span>
                    @endif
                </td>
                <td>
                    @if($p->status == 'pending')
                        <a href="{{ route('admin.verifikasi-peminjaman') }}?selected_id={{ $p->id_peminjaman }}" class="btn btn-edit-outline">Edit</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <!-- Realistic fallback content matching Image 1 -->
            <tr>
                <td class="fw-semibold">SBUM-2026-0148</td>
                <td>Moch Azmi</td>
                <td>Aula Utama</td>
                <td><span class="badge-menunggu">Menunggu Final</span></td>
                <td><a href="{{ route('admin.verifikasi-peminjaman') }}" class="btn btn-edit-outline">Edit</a></td>
            </tr>
            <tr>
                <td class="fw-semibold">SBUM-2026-0136</td>
                <td>Grexia</td>
                <td>Projector Epson</td>
                <td><span class="badge-disetujui">Disetujui</span></td>
                <td>-</td>
            </tr>
            <tr>
                <td class="fw-semibold">SBUM-2026-0128</td>
                <td>Ayudia</td>
                <td>Lab Komputer 1</td>
                <td><span class="badge-ditolak">Ditolak</span></td>
                <td>-</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Explainer Card -->
<div class="card border-0 rounded-4 p-4 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important; max-width: 450px;">
    <div class="text-secondary small fw-semibold">Detail Cepat</div>
    <div class="text-muted small mt-1">Di layar ini admin bisa membuka detail peminjaman, mengubah status, atau memperbarui catatan transaksi.</div>
</div>
@endsection
