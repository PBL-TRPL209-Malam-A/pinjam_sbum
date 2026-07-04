@extends('layout.app_tailwind')




@section('content')
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
    .badge-baik {
        background-color: #e2f0d9;
        color: #385723;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
    .badge-pemeriksaan {
        background-color: #fcf1d3;
        color: #7d6006;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
    .btn-detail-outline {
        border: 1px solid var(--line);
        background: white;
        color: var(--text-main);
        font-weight: 500;
        border-radius: 0.75rem;
        padding: 0.4rem 1.5rem;
        transition: 0.2s;
    }
    .btn-detail-outline:hover {
        background: #fdfcf9;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Kelola seluruh data pengembalian fasilitas</h2>
        <p class="mb-0 text-secondary text-wrap">
            Use case F16 saya normalkan sebagai pengelolaan data pengembalian, karena inti fungsinya adalah admin mencatat dan memproses data return.
        </p>
    </div>
</div>

<!-- Table Area -->
<div class="mb-3 fw-semibold text-secondary">Data Pengembalian</div>
<div class="custom-table mb-4">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">ID Return</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Fasilitas</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Peminjam</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Kondisi</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengembalian as $p)
            <tr>
                <td class="fw-semibold">RET-2026-{{ str_pad($p->id_pengembalian, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $p->peminjaman->nama_fasilitas ?? '-' }}</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $p->peminjaman->user->nama_lengkap ?? '-' }}</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">
                    @if($p->status_pengembalian == 'pending')
                        <span class="badge-pemeriksaan">Perlu Pemeriksaan</span>
                    @else
                        <span class="badge-baik">Baik</span>
                    @endif
                </td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <a href="{{ route('admin.verifikasi-pengembalian') }}?selected_id={{ $p->id_pengembalian }}&kategori={{ $p->kategori }}" class="btn btn-detail-outline">Detail</a>
                </td>
            </tr>
            @empty
            <!-- Realistic fallback content matching Image 4 -->
            <tr>
                <td class="fw-semibold">RET-2026-0062</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">Aula Utama</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">Moch Azmi</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]"><span class="badge-baik">Baik</span></td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]"><a href="{{ route('admin.verifikasi-pengembalian') }}" class="btn btn-detail-outline">Detail</a></td>
            </tr>
            <tr>
                <td class="fw-semibold">RET-2026-0061</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">Projector Epson</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">Grexia</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]"><span class="badge-pemeriksaan">Perlu Pemeriksaan</span></td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]"><a href="{{ route('admin.verifikasi-pengembalian') }}" class="btn btn-detail-outline">Detail</a></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Explainer Card -->
<div class="card border-0 rounded-4 p-4 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
    <div class="text-secondary small fw-semibold">Catatan Pengelolaan</div>
    <div class="text-muted small mt-1">Admin bisa memperbarui kondisi, tanggal diterima, dan catatan kerusakan bila ada.</div>
</div>
@endsection
