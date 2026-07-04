@extends('layout.app_tailwind')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-6">
    <div>
        <div class="text-[19px] text-[#7d8781] mb-1">Admin SBUM</div>
        <h1 class="text-[32px] font-bold text-[#466454] leading-tight mb-2">Dashboard Admin</h1>
    </div>
</div>

<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <h2 class="text-xl font-semibold text-[#466454] mb-2">Ringkasan operasional peminjaman SBUM</h2>
    <p class="text-[#7d8781] max-w-2xl mb-6">
        Pantau pengajuan, konflik jadwal, inventaris, pengembalian, dan proses verifikasi dalam satu layar.
    </p>
    <a href="{{ route('admin.verifikasi-peminjaman') }}" class="inline-flex items-center justify-center bg-[#466454] text-white px-5 py-2.5 rounded-[14px] font-semibold hover:bg-[#395244] transition">
        Lihat Antrian
    </a>
</div>

<!-- Metrics Row -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
        <div class="text-sm font-medium text-[#7d8781] mb-2">Pengajuan Baru</div>
        <div class="text-4xl font-bold text-[#466454]">{{ $totalPengajuanBaru }}</div>
    </div>
    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
        <div class="text-sm font-medium text-[#7d8781] mb-2">Menunggu Verifikasi</div>
        <div class="text-4xl font-bold text-[#466454]">{{ $menungguVerifikasi }}</div>
    </div>
    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
        <div class="text-sm font-medium text-[#7d8781] mb-2">Jadwal Bentrok</div>
        <div class="text-4xl font-bold text-[#466454]">{{ $totalJadwalBentrok }}</div>
    </div>
    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
        <div class="text-sm font-medium text-[#7d8781] mb-2">Return Pending</div>
        <div class="text-4xl font-bold text-[#466454]">{{ $returnPending }}</div>
    </div>
</div>

<!-- Dashboard Grid (2 Columns) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Left Column: Pengajuan Terbaru & Aksi Cepat -->
    <div class="lg:col-span-7">
        <div class="mb-4 font-semibold text-[#7d8781]">Pengajuan Terbaru</div>

        <!-- Submission List -->
        <div class="space-y-3">
            @forelse($recentPeminjaman as $index => $item)
                <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[20px] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="font-semibold text-[#466454]">SBUM-2026-{{ str_pad($item->id_peminjaman, 4, '0', STR_PAD_LEFT) }} &middot; {{ $item->nama_kegiatan }}</div>
                        <div class="text-[#7d8781] text-sm mt-1">
                            {{ $item->user->nama_lengkap ?? '-' }} &middot; 
                            {{ $item->nama_fasilitas_with_type }} &middot; 
                            {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : now()->format('d M Y') }}
                        </div>
                    </div>
                    <div>
                        @if($index % 2 == 1)
                            <span class="inline-block bg-[#fcebeb] text-[#8b3c3c] text-[13px] font-semibold px-4 py-1.5 rounded-full">Bentrok</span>
                        @else
                            <span class="inline-block bg-[#fcf1d3] text-[#7d6006] text-[13px] font-semibold px-4 py-1.5 rounded-full">Menunggu Admin</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[20px] p-6 text-center text-[#7d8781]">
                    Tidak ada pengajuan terbaru.
                </div>
            @endforelse
        </div>

        <!-- Aksi Cepat Card -->
        <div class="mb-4 mt-8 font-semibold text-[#7d8781]">Aksi Cepat</div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.verifikasi-peminjaman') }}" class="bg-[#466454] hover:bg-[#395244] text-white px-5 py-2.5 rounded-[14px] font-semibold transition">Verifikasi</a>
            <a href="{{ route('admin.jadwal') }}" class="border border-[#e6ddd2] text-[#466454] px-5 py-2.5 rounded-[14px] font-semibold hover:bg-[#f5f2ec] transition">Jadwal</a>
            <a href="{{ route('admin.inventaris') }}" class="border border-[#e6ddd2] text-[#466454] px-5 py-2.5 rounded-[14px] font-semibold hover:bg-[#f5f2ec] transition">Inventaris</a>
        </div>
    </div>

    <!-- Right Column: Kalender Hari Ini & Alert Operasional -->
    <div class="lg:col-span-5">
        <div class="mb-4 font-semibold text-[#7d8781]">Kalender Hari Ini</div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 mb-6">
            @forelse($jadwalHariIni as $jadwal)
                <div class="mb-4 last:mb-0">
                    <div class="text-[#7d8781] text-sm font-semibold">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai ?? '08:00')->format('H.i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai ?? '12:00')->format('H.i') }}</div>
                    <div class="font-semibold text-[#466454] mt-1">{{ $jadwal->nama_fasilitas_with_type }} &middot; {{ $jadwal->nama_kegiatan }}</div>
                </div>
            @empty
                <div class="text-[#7d8781] text-center py-2">Tidak ada jadwal hari ini.</div>
            @endforelse
        </div>

        <div class="mb-4 font-semibold text-[#7d8781]">Alert Operasional</div>
        <div class="bg-[#fdf1d3] text-[#7d6006] rounded-[24px] p-4 flex items-center mb-4">
            <i class="bi bi-exclamation-triangle-fill mr-3 text-xl"></i>
            <span class="font-semibold text-sm">{{ $totalJadwalBentrok }} jadwal terindikasi bentrok</span>
        </div>
        <div class="bg-[#edf2ea] text-[#466454] rounded-[24px] p-4 flex items-center">
            <i class="bi bi-check-circle-fill mr-3 text-xl"></i>
            <span class="font-semibold text-sm">{{ $returnPending }} pengembalian siap diverifikasi</span>
        </div>
    </div>
</div>
@endsection
