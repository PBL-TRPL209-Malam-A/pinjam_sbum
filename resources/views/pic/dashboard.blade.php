@extends('layout.app_tailwind')




@section('content')


<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div class="p-6 lg:p-8">
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h2>
        <p class="text-[#7d8781] max-w-2xl mb-6">
            Pantau kesiapan fasilitas ruangan yang Anda kelola untuk memastikan semua kegiatan peminjam berjalan dengan baik.
        </p>
        <a href="{{ route('pic.kesiapan') }}" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block">Buka Kesiapan</a>
    </div>
</div>

<!-- Metrics Row -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Ruangan Dikelola</div>
            <div class="text-4xl font-bold text-[#466454]">{{ count($ruangan) > 0 ? count($ruangan) : 1 }}</div>
        </div>
    </div>
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Menunggu Konfirmasi</div>
            <div class="text-4xl font-bold text-[#466454]">{{ $peminjamanDisetujui }}</div>
        </div>
    </div>
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Kesiapan Selesai</div>
            <div class="text-4xl font-bold text-[#466454]">{{ $kesiapanSelesai }}</div>
        </div>
    </div>
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Kendala Dilaporkan</div>
            <div class="text-4xl font-bold text-[#466454]">{{ $kendalaDilaporkan }}</div>
        </div>
    </div>
</div>

<!-- Table: Managed Rooms -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div class="fw-semibold text-secondary">Fasilitas Ruangan Anda</div>
    <form action="{{ route('pic.dashboard') }}" method="GET" class="d-flex align-items-center gap-2" style="max-width: 350px; width: 100%;">
        <input type="hidden" name="search_item" value="{{ request('search_item') }}">
        <input type="text" name="search_room" class="form-control" placeholder="Cari ruangan..." value="{{ request('search_room') }}" style="border-radius: 0.75rem; border: 1px solid var(--line); background: #fffdfa; height: 2.5rem; font-size: 0.9rem;">
        <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-3 py-1.5 rounded-lg text-sm transition inline-block" style="min-width: auto; height: 2.5rem; border-radius: 0.75rem; padding: 0 1rem; font-size: 0.9rem; color: white;">Cari</button>
        @if(request('search_room'))
            <a href="{{ route('pic.dashboard', ['search_item' => request('search_item')]) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center" style="height: 2.5rem; border-radius: 0.75rem; font-size: 0.9rem;">Reset</a>
        @endif
    </form>
</div>
<div class="custom-table mb-4">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Nama Ruangan</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Kode Ruangan</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Gedung</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Lantai</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Kapasitas</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ruangan as $room)
            <tr>
                <td class="fw-semibold">{{ $room->nama_ruangan }}</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $room->kode_ruangan }}</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $room->nama_gedung ?? 'Gedung Utama' }}</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">Lantai {{ $room->lantai ?? '1' }}</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $room->kapasitas }} orang</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">
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
        <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-3 py-1.5 rounded-lg text-sm transition inline-block" style="min-width: auto; height: 2.5rem; border-radius: 0.75rem; padding: 0 1rem; font-size: 0.9rem; color: white;">Cari</button>
        @if(request('search_item'))
            <a href="{{ route('pic.dashboard', ['search_room' => request('search_room')]) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center" style="height: 2.5rem; border-radius: 0.75rem; font-size: 0.9rem;">Reset</a>
        @endif
    </form>
</div>
<div class="custom-table mb-4">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Nama Barang</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Foto</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Keterangan/Lokasi</th>
                <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Total Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barang as $item)
            <tr>
                <td class="fw-semibold">{{ $item->nama_barang }}</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">
                    @if($item->foto_barang)
                        <img src="{{ asset($item->foto_barang) }}" alt="{{ $item->nama_barang }}" class="img-fluid rounded-3" style="width: 100px; height: 100px; object-fit: cover; max-width: 100%;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">
                            Tidak ada foto
                        </div>
                    @endif
                </td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $item->keterangan ?: 'Gudang SBUM' }}</td>
                <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $item->stok_tersedia }} unit</td>
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
