@extends('layout.pic')

@section('page_caption', 'PIC Ruangan')
@section('page_heading', 'Dashboard PIC')

@section('pic_content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .metric-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
        transition: 0.3s;
    }
    .metric-card:hover {
        transform: translateY(-2px);
    }
    .metric-title {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    .metric-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--text-main);
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
    .badge-aktif {
        background-color: #e2f0d9;
        color: #385723;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
    .badge-tidak-tersedia {
        background-color:  #8b3c3c;
        color: #fcebeb;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
    .badge-maintenance {
        background-color: #fcf1d3;
        color:  #7d6006;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h2>
        <p class="mb-4 text-secondary text-wrap" style="max-width: 650px;">
            Pantau kesiapan fasilitas ruangan yang Anda kelola untuk memastikan semua kegiatan mahasiswa berjalan dengan baik.
        </p>
        <a href="{{ route('pic.kesiapan') }}" class="btn btn-main d-inline-flex align-items-center justify-content-center">Buka Kesiapan</a>
    </div>
</div>

<!-- Metrics Row -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Ruangan Dikelola</div>
            <div class="metric-value">{{ count($ruangan) > 0 ? count($ruangan) : 1 }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Menunggu Konfirmasi</div>
            <div class="metric-value">{{ $peminjamanDisetujui }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Kesiapan Selesai</div>
            <div class="metric-value">{{ $kesiapanSelesai }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Kendala Dilaporkan</div>
            <div class="metric-value">{{ $kendalaDilaporkan }}</div>
        </div>
    </div>
</div>

<!-- Table: Managed Rooms -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div class="fw-semibold text-secondary">Fasilitas Ruangan Anda</div>
    <form action="{{ route('pic.dashboard') }}" method="GET" class="d-flex align-items-center gap-2" style="max-width: 350px; width: 100%;">
        <input type="hidden" name="search_item" value="{{ request('search_item') }}">
        <input type="text" name="search_room" class="form-control" placeholder="Cari ruangan..." value="{{ request('search_room') }}" style="border-radius: 0.75rem; border: 1px solid var(--line); background: #fffdfa; height: 2.5rem; font-size: 0.9rem;">
        <button type="submit" class="btn btn-sm btn-main d-flex align-items-center justify-content-center" style="min-width: auto; height: 2.5rem; border-radius: 0.75rem; padding: 0 1rem; font-size: 0.9rem; color: white;">Cari</button>
        @if(request('search_room'))
            <a href="{{ route('pic.dashboard', ['search_item' => request('search_item')]) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center" style="height: 2.5rem; border-radius: 0.75rem; font-size: 0.9rem;">Reset</a>
        @endif
    </form>
</div>
<div class="custom-table mb-4">
    <table class="table table-borderless mb-0">
        <thead>
            <tr>
                <th>Nama Ruangan</th>
                <th>Kode Ruangan</th>
                <th>Gedung</th>
                <th>Lantai</th>
                <th>Kapasitas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ruangan as $room)
            <tr>
                <td class="fw-semibold">{{ $room->nama_ruangan }}</td>
                <td>{{ $room->kode_ruangan }}</td>
                <td>{{ $room->nama_gedung ?? 'Gedung Utama' }}</td>
                <td>Lantai {{ $room->lantai ?? '1' }}</td>
                <td>{{ $room->kapasitas }} orang</td>
                <td>
                    @if($room->status_ruangan == 'tersedia')
                        <span class="badge-aktif">Tersedia</span>
                    @elseif($room->status_ruangan == 'tidak tersedia')
                        <span class="badge-tidak-tersedia">Tidak Tersedia</span>
                    @elseif($room->status_ruangan == 'maintenance')
                        <span class="badge-maintenance">Maintenance</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data fasilitas ruangan yang dikelola.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Table: Managed Items -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 mt-4">
    <div class="fw-semibold text-secondary">Barang Inventaris Anda</div>
    <form action="{{ route('pic.dashboard') }}" method="GET" class="d-flex align-items-center gap-2" style="max-width: 350px; width: 100%;">
        <input type="hidden" name="search_room" value="{{ request('search_room') }}">
        <input type="text" name="search_item" class="form-control" placeholder="Cari barang..." value="{{ request('search_item') }}" style="border-radius: 0.75rem; border: 1px solid var(--line); background: #fffdfa; height: 2.5rem; font-size: 0.9rem;">
        <button type="submit" class="btn btn-sm btn-main d-flex align-items-center justify-content-center" style="min-width: auto; height: 2.5rem; border-radius: 0.75rem; padding: 0 1rem; font-size: 0.9rem; color: white;">Cari</button>
        @if(request('search_item'))
            <a href="{{ route('pic.dashboard', ['search_room' => request('search_room')]) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center" style="height: 2.5rem; border-radius: 0.75rem; font-size: 0.9rem;">Reset</a>
        @endif
    </form>
</div>
<div class="custom-table mb-4">
    <table class="table table-borderless mb-0">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Foto</th>
                <th>Keterangan/Lokasi</th>
                <th>Total Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barang as $item)
            <tr>
                <td class="fw-semibold">{{ $item->nama_barang }}</td>
                <td>
                    @if($item->foto_barang)
                        <img src="{{ asset($item->foto_barang) }}" alt="{{ $item->nama_barang }}" class="img-fluid rounded-3" style="width: 100px; height: 100px; object-fit: cover; max-width: 100%;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">
                            Tidak ada foto
                        </div>
                    @endif
                </td>
                <td>{{ $item->keterangan ?: 'Gudang SBUM' }}</td>
                <td>{{ $item->stok_tersedia }} unit</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-muted">Tidak ada data barang inventaris yang didelegasikan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
