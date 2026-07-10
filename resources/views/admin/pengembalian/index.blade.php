@extends('layout.app_tailwind')




@section('content')


<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div>
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Kelola seluruh data pengembalian fasilitas</h2>
        <p class="text-[#7d8781] max-w-2xl mb-0">
            Catat dan proses seluruh pengembalian fasilitas yang telah selesai dipinjam oleh mahasiswa, staf, maupun dosen.
        </p>
    </div>
</div>

<!-- Table Area -->
<div class="font-semibold text-[#7d8781] mb-4">Data Pengembalian</div>
<div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] overflow-x-auto mb-6">
    <table class="w-full text-left whitespace-nowrap">
        <thead><tr class="bg-[#f7f3eb] text-[#33403b]"><th class="px-6 py-4 font-semibold text-sm">ID Return</th><th class="px-6 py-4 font-semibold text-sm">Fasilitas</th><th class="px-6 py-4 font-semibold text-sm">Peminjam</th><th class="px-6 py-4 font-semibold text-sm">Kondisi</th><th class="px-6 py-4 font-semibold text-sm">Aksi</th></tr></thead>
        <tbody>
            @forelse($pengembalian as $p)
            <tr>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">RET-2026-{{ str_pad($p->id_pengembalian, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $p->peminjaman->nama_fasilitas ?? '-' }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $p->peminjaman->user->nama_lengkap ?? '-' }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    @if($p->status_pengembalian == 'pending')
                        <span class="bg-[#fcf1d3] text-[#7d6006] px-4 py-1.5 rounded-full text-xs font-semibold">Perlu Pemeriksaan</span>
                    @else
                        <span class="bg-[#e2f0d9] text-[#385723] px-4 py-1.5 rounded-full text-xs font-semibold">Baik</span>
                    @endif
                </td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <a href="{{ route('admin.verifikasi-pengembalian') }}?selected_id={{ $p->id_pengembalian }}&kategori={{ $p->kategori }}" class="border border-[#e6ddd2] text-[#33403b] px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-[#fdfcf9]">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-[#7d8781]">Belum ada data pengembalian fasilitas saat ini.</td>
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
