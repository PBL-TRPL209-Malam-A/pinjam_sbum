@extends('layout.app_tailwind')




@section('content')


<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div class="p-6 lg:p-8">
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Verifikasi permohonan peminjam dengan cepat</h2>
        <p class="text-[#7d8781] max-w-2xl mb-6">
            Dashboard ini membantu dosen memantau pengajuan yang perlu diverifikasi, keputusan terbaru, dan jadwal kegiatan peminjam.
        </p>
        <a href="{{ route('dosen.verifikasi-peminjaman') }}" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block">Buka Verifikasi</a>
    </div>
</div>

<!-- Metrics Row -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Menunggu Verifikasi</div>
            <div class="text-4xl font-bold text-[#466454]">{{ $menungguVerifikasi }}</div>
        </div>
    </div>
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Disetujui Hari Ini</div>
            <div class="text-4xl font-bold text-[#466454]">{{ $disetujuiHariIni }}</div>
        </div>
    </div>
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Ditolak / Revisi</div>
            <div class="text-4xl font-bold text-[#466454]">{{ $ditolakRevisi }}</div>
        </div>
    </div>
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Kegiatan Terdekat</div>
            <div class="text-4xl font-bold text-[#466454]">{{ $kegiatanTerdekat }}</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Left Column: Antrian Verifikasi -->
    <div class="lg:col-span-7">
        <div class="mb-4 font-semibold text-[#7d8781]">Antrian Verifikasi</div>

        @forelse($peminjaman as $index => $item)
            <div class="list-item-card" onclick="window.location.href='{{ route('dosen.verifikasi-peminjaman') }}?selected_id={{ $item->id_peminjaman }}'">
                <div>
                    <div class="font-semibold text-[#466454]">SBUM-2026-{{ str_pad($item->id_peminjaman, 4, '0', STR_PAD_LEFT) }} - {{ $item->nama_kegiatan }}</div>
                    <div class="text-[#7d8781] text-sm mt-1">
                        {{ $item->user->nama_lengkap ?? '-' }} · 
                        {{ $item->nama_fasilitas_with_type }} · 
                        {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : now()->format('d M Y') }}
                    </div>
                </div>
                <div>
                    @if($item->status == 'menunggu_dosen')
                        <span class="inline-block bg-[#fcf1d3] text-[#7d6006] text-[13px] font-semibold px-4 py-1.5 rounded-full">Perlu Verifikasi</span>
                    @else
                        <span class="inline-block bg-[#f3f4f6] text-[#4b5563] text-[13px] font-semibold px-4 py-1.5 rounded-full">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[20px] p-5 text-center text-[#7d8781] text-sm italic">
                Belum ada antrian verifikasi saat ini.
            </div>
        @endforelse
    </div>

    <!-- Right Column: Riwayat Keputusan & Jadwal -->
    <div class="lg:col-span-5">
        <div class="mb-4 font-semibold text-[#7d8781]">Riwayat Keputusan</div>
        
        @forelse($riwayatKeputusan as $riwayat)
            @php
                $isDisetujui = $riwayat->status == 'disetujui';
                $bgClass = $isDisetujui ? 'bg-[#edf2ea]' : 'bg-[#fcebeb]';
                $textClass = $isDisetujui ? 'text-[#466454]' : 'text-[#8b3c3c]';
                $statusText = $isDisetujui ? 'Disetujui' : 'Ditolak/Revisi';
                $fasilitasText = $riwayat->peminjaman->nama_fasilitas;
            @endphp
            <div class="{{ $bgClass }} {{ $textClass }} rounded-[16px] p-4 flex items-center mb-3 font-semibold text-sm">
                {{ $statusText }} · {{ $fasilitasText }} - {{ $riwayat->tanggal->format('d M') }}
            </div>
        @empty
            <div class="text-[#7d8781] text-sm italic mb-3">Belum ada riwayat keputusan.</div>
        @endforelse

        <div class="mb-4 mt-8 font-semibold text-[#7d8781]">Jadwal Kegiatan Peminjam</div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 mb-6">
            @forelse($jadwalKegiatan as $jadwal)
                @php
                    $fasilitasText = $jadwal->nama_fasilitas;
                @endphp
                <div class="{{ !$loop->last ? 'mb-4' : '' }}">
                    <div class="text-[#7d8781] text-sm font-semibold">{{ \Carbon\Carbon::parse($jadwal->tanggal_pengajuan)->format('d M') }} · {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H.i') }}</div>
                    <div class="font-semibold text-[#466454] mt-1">{{ $fasilitasText }} · {{ $jadwal->nama_kegiatan }}</div>
                </div>
            @empty
                <div class="text-[#7d8781] text-sm italic">Belum ada jadwal kegiatan terdekat yang disetujui.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
