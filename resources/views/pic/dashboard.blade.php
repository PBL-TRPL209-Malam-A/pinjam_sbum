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
<div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-4">
    <div class="font-semibold text-[#7d8781]">Fasilitas Ruangan Anda</div>
    <form action="{{ route('pic.dashboard') }}" method="GET" class="flex items-center gap-2 w-full md:max-w-sm">
        <input type="hidden" name="search_item" value="{{ request('search_item') }}">
        <input type="text" name="search_room" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 h-10 focus:outline-none focus:border-[#466454]" placeholder="Cari ruangan..." value="{{ request('search_room') }}">
        <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-4 h-10 rounded-xl font-semibold transition">Cari</button>
        @if(request('search_room'))
            <a href="{{ route('pic.dashboard', ['search_item' => request('search_item')]) }}" class="border border-[#e6ddd2] text-[#33403b] px-4 h-10 rounded-xl font-semibold flex items-center justify-center hover:bg-[#f5f2ec] transition">Reset</a>
        @endif
    </form>
</div>
<div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] overflow-x-auto mb-6">
    <table class="w-full text-left whitespace-nowrap">
        <thead><tr class="bg-[#f7f3eb] text-[#33403b]">
            <th class="px-6 py-4 font-semibold text-sm">Nama Ruangan</th>
                <th class="px-6 py-4 font-semibold text-sm">Kode Ruangan</th>
                <th class="px-6 py-4 font-semibold text-sm">Gedung</th>
                <th class="px-6 py-4 font-semibold text-sm">Lantai</th>
                <th class="px-6 py-4 font-semibold text-sm">Kapasitas</th>
                <th class="px-6 py-4 font-semibold text-sm">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ruangan as $room)
            <tr>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">{{ $room->nama_ruangan }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $room->kode_ruangan }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $room->nama_gedung ?? 'Gedung Utama' }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">Lantai {{ $room->lantai ?? '1' }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $room->kapasitas }} orang</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    @if($room->status_ruangan == 'tersedia')
                        <span class="bg-[#e2f0d9] text-[#385723] px-4 py-1.5 rounded-full text-xs font-semibold">Tersedia</span>
                    @elseif($room->status_ruangan == 'tidak tersedia')
                        <span class="bg-[#fcebeb] text-[#8b3c3c] px-4 py-1.5 rounded-full text-xs font-semibold">Tidak Tersedia</span>
                    @elseif($room->status_ruangan == 'maintenance')
                        <span class="bg-[#fcf1d3] text-[#7d6006] px-4 py-1.5 rounded-full text-xs font-semibold">Maintenance</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-[#7d8781]">Tidak ada data fasilitas ruangan yang dikelola.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Table: Managed Items -->
<div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-4 mt-8">
    <div class="font-semibold text-[#7d8781]">Barang Inventaris Anda</div>
    <form action="{{ route('pic.dashboard') }}" method="GET" class="flex items-center gap-2 w-full md:max-w-sm">
        <input type="hidden" name="search_room" value="{{ request('search_room') }}">
        <input type="text" name="search_item" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 h-10 focus:outline-none focus:border-[#466454]" placeholder="Cari barang..." value="{{ request('search_item') }}">
        <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-4 h-10 rounded-xl font-semibold transition">Cari</button>
        @if(request('search_item'))
            <a href="{{ route('pic.dashboard', ['search_room' => request('search_room')]) }}" class="border border-[#e6ddd2] text-[#33403b] px-4 h-10 rounded-xl font-semibold flex items-center justify-center hover:bg-[#f5f2ec] transition">Reset</a>
        @endif
    </form>
</div>
<div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] overflow-x-auto mb-6">
    <table class="w-full text-left whitespace-nowrap">
        <thead><tr class="bg-[#f7f3eb] text-[#33403b]">
            <th class="px-6 py-4 font-semibold text-sm">Nama Barang</th>
                <th class="px-6 py-4 font-semibold text-sm">Foto</th>
                <th class="px-6 py-4 font-semibold text-sm">Keterangan/Lokasi</th>
                <th class="px-6 py-4 font-semibold text-sm">Total Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barang as $item)
            <tr>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">{{ $item->nama_barang }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    @if($item->foto_barang)
                        <img src="{{ asset($item->foto_barang) }}" alt="{{ $item->nama_barang }}" class="img-fluid rounded-3" style="width: 100px; height: 100px; object-fit: cover; max-width: 100%;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">
                            Tidak ada foto
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $item->keterangan ?: 'Gudang SBUM' }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $item->stok_tersedia }} unit</td>
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
