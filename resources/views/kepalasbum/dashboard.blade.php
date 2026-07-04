@extends('layout.app_tailwind')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-6">
    <div>
        <div class="text-[19px] text-[#7d8781] mb-1">Kepala SBUM</div>
        <h1 class="text-[32px] font-bold text-[#466454] leading-tight mb-2">Dashboard</h1>
    </div>
</div>

<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <h2 class="text-xl font-semibold text-[#466454] mb-2">Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h2>
    <p class="text-[#7d8781] max-w-2xl mb-6">
        Sistem pengelolaan fasilitas dan peminjaman ruang SBUM siap digunakan. Sebagai Kepala SBUM, Anda memiliki wewenang penuh untuk memantau data staf, persetujuan akhir, serta melihat laporan aktivitas secara real-time.
    </p>
    <a href="{{ route('kepalasbum.persetujuan') }}" class="inline-block bg-[#466454] hover:bg-[#395244] text-white px-5 py-2.5 rounded-[14px] font-semibold transition">Tinjau Pengajuan</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
        <div class="text-sm font-medium text-[#7d8781] mb-2">Total Staf SBUM</div>
        <div class="text-4xl font-bold text-[#466454]">{{ $totalStaff }}</div>
    </div>
    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
        <div class="text-sm font-medium text-[#7d8781] mb-2">Total Peminjaman</div>
        <div class="text-4xl font-bold text-[#466454]">{{ $totalPeminjaman }}</div>
    </div>
    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
        <div class="text-sm font-medium text-[#7d8781] mb-2">Menunggu Persetujuan</div>
        <div class="text-4xl font-bold text-[#466454]">{{ $pendingPersetujuan }}</div>
    </div>
</div>
@endsection
