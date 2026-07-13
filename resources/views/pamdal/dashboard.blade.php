@extends('layout.app_tailwind')




@section('content')


<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div class="p-6 lg:p-8">
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h2>
        <p class="text-[#7d8781] max-w-2xl mb-6">
            Pantau pengawasan ketertiban fasilitas, kelayakan penggunaan kapasitas, dan pelaporan kendala secara langsung untuk memastikan ketertiban area kampus.
        </p>
        <a href="{{ route('pamdal.monitoring') }}" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block">Buka Monitoring</a>
    </div>
</div>

<!-- Metrics Row -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Kegiatan Mendatang</div>
            <div class="text-4xl font-bold text-[#466454]">{{ $todaySchedules }}</div>
        </div>
    </div>
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Total Pengawasan</div>
            <div class="text-4xl font-bold text-[#466454]">{{ $totalPengawasan }}</div>
        </div>
    </div>
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Aman Terkendali</div>
            <div class="text-4xl font-bold text-[#466454]">{{ $amanTerkendali }}</div>
        </div>
    </div>
    <div>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
            <div class="text-sm font-medium text-[#7d8781] mb-2">Ada Kendala</div>
            <div class="text-4xl font-bold text-[#466454]">{{ $adaKendala }}</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Jadwal Kegiatan -->
    <div class="lg:col-span-2">
        <h3 class="text-xl font-bold text-[#466454] mb-3">Jadwal Kegiatan Mendatang</h3>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6">
            @forelse($jadwalKegiatan as $jadwal)
                @php
                    $fasilitasText = $jadwal->nama_fasilitas;
                @endphp
                <div class="{{ !$loop->last ? 'mb-4 border-b border-[#e6ddd2] pb-4' : '' }}">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <div class="font-semibold text-[#466454]">{{ $jadwal->nama_kegiatan }}</div>
                            <div class="text-[#7d8781] text-sm mt-1">
                                {{ $fasilitasText }} · 
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($jadwal->tanggal_pengajuan)->format('d M') }}</span> ·
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H.i') }} WIB
                            </div>
                        </div>
                        <div>
                            <span class="inline-block bg-[#edf2ea] text-[#466454] text-[12px] font-bold px-3 py-1 rounded-full">
                                {{ $jadwal->user->nama_lengkap ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-[#7d8781] text-sm italic text-center py-4">Belum ada kegiatan yang disetujui. Area aman.</div>
            @endforelse
        </div>
    </div>

    <!-- Info Card -->
    <div class="lg:col-span-1">
        <h3 class="text-xl font-bold text-[#466454] mb-3">Tugas Utama Pamdal</h3>
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 h-auto">
            <ul class="text-[#7d8781] text-sm list-disc list-inside space-y-3">
                <li>Memantau ketertiban dan kapasitas ruangan saat kegiatan peminjam berlangsung.</li>
                <li>Mencatat temuan lapangan dan melaporkan status pengawasan ke dalam sistem.</li>
                <li>Menjaga keamanan area sekitar fasilitas selama waktu peminjaman.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
