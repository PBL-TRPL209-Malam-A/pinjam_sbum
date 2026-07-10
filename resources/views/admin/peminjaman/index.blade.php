@extends('layout.app_tailwind')




@section('content')


<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div>
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Catat dan kelola seluruh data peminjaman</h2>
        <p class="text-[#7d8781] max-w-2xl mb-0">
            Admin dapat melihat, mengedit, dan memperbarui data peminjaman untuk memastikan riwayat penggunaan tercatat rapi.
        </p>
    </div>
</div>

<!-- Controls Bar -->
<form action="{{ route('admin.peminjaman') }}" method="GET" class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-end gap-4">
        <!-- Search bar -->
        <div class="w-full md:w-1/4">
            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Cari Kegiatan / Peminjam</label>
            <input type="text" name="search" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 h-11 focus:outline-none focus:border-[#466454]" placeholder="Cari..." value="{{ request('search') }}">
        </div>
        <!-- Status Filter -->
        <div class="w-full md:w-1/4">
            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Status</label>
            <select name="status" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 h-11 focus:outline-none focus:border-[#466454]">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <!-- Date Picker Filter -->
        <div class="w-full md:w-1/4">
            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Tanggal Acara</label>
            <input type="date" name="date" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 h-11 focus:outline-none focus:border-[#466454]" value="{{ request('date') }}">
        </div>
        <!-- Buttons -->
        <div class="w-full md:w-1/4 flex gap-2">
            <button type="submit" class="flex-1 bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition h-11">Filter</button>
            <button type="button" id="exportPdfBtn" class="border border-[#e6ddd2] text-[#33403b] px-4 py-2 rounded-xl font-semibold hover:bg-[#f5f2ec] transition h-11 flex items-center justify-center">
                <i class="bi bi-file-earmark-pdf mr-1"></i> Export
            </button>
        </div>
    </div>
</form>

<!-- Table Area -->
<div class="font-semibold text-[#7d8781] mb-4">Riwayat Peminjaman</div>
<div id="peminjamanTableContainer" class="custom-table mb-3" style="overflow-x: auto; background: #fffdfa;">
    <table class="w-full text-left whitespace-nowrap">
        <thead><tr class="bg-[#f7f3eb] text-[#33403b]"><th class="px-6 py-4 font-semibold text-sm">ID</th><th class="px-6 py-4 font-semibold text-sm">Tanggal & Waktu</th><th class="px-6 py-4 font-semibold text-sm">Peminjam</th><th class="px-6 py-4 font-semibold text-sm">Fasilitas</th><th class="px-6 py-4 font-semibold text-sm">Nama Acara</th><th class="px-6 py-4 font-semibold text-sm">Keterangan Acara</th><th class="px-6 py-4 font-semibold text-sm">Dosen PJ</th><th class="px-6 py-4 font-semibold text-sm">PIC Fasilitas</th><th class="px-6 py-4 font-semibold text-sm text-center">Status</th><th class="px-6 py-4 font-semibold text-sm">Tahapan Persetujuan</th></tr></thead>
        <tbody>
            @forelse($peminjaman as $p)
                @php
                    $statusText = 'Pending';
                    $badgeClass = 'bg-[#fcf1d3] text-[#7d6006] px-4 py-1.5 rounded-full text-xs font-semibold';
                    $tahapanText = 'Menunggu Verifikasi';
                    $tahapanColor = 'text-[#7d6006]';

                    switch($p->status) {
                        case 'menunggu_dosen':
                            $statusText = 'Pending';
                            $badgeClass = 'bg-[#fcf1d3] text-[#7d6006] px-4 py-1.5 rounded-full text-xs font-semibold';
                            $tahapanText = 'Menunggu Dosen PJ';
                            $tahapanColor = 'text-[#7d6006]';
                            break;
                        case 'menunggu_admin':
                            $statusText = 'Pending';
                            $badgeClass = 'bg-[#fcf1d3] text-[#7d6006] px-4 py-1.5 rounded-full text-xs font-semibold';
                            $tahapanText = 'Menunggu Admin';
                            $tahapanColor = 'text-[#7d6006]';
                            break;
                        case 'menunggu_kepala':
                            $statusText = 'Pending';
                            $badgeClass = 'bg-[#fcf1d3] text-[#7d6006] px-4 py-1.5 rounded-full text-xs font-semibold';
                            $tahapanText = 'Menunggu Kepala';
                            $tahapanColor = 'text-[#7d6006]';
                            break;
                        case 'menunggu_pic':
                            $statusText = 'Pending';
                            $badgeClass = 'bg-[#fcf1d3] text-[#7d6006] px-4 py-1.5 rounded-full text-xs font-semibold';
                            $tahapanText = 'Menunggu PIC';
                            $tahapanColor = 'text-[#7d6006]';
                            break;
                        case 'siap_digunakan':
                            $statusText = 'Diterima';
                            $badgeClass = 'bg-[#e2f0d9] text-[#385723] px-4 py-1.5 rounded-full text-xs font-semibold';
                            $tahapanText = 'Verifikasi Tuntas';
                            $tahapanColor = 'text-[#385723]';
                            break;
                        case 'selesai':
                            $statusText = 'Selesai';
                            $badgeClass = 'bg-[#e2f0d9] text-[#385723] px-4 py-1.5 rounded-full text-xs font-semibold';
                            $tahapanText = 'Sudah Dikembalikan';
                            $tahapanColor = 'text-[#385723]';
                            break;
                        case 'proses_pengembalian':
                            $statusText = 'Pengembalian';
                            $badgeClass = 'bg-blue-100 text-blue-800 px-4 py-1.5 rounded-full text-xs font-semibold';
                            $tahapanText = 'Proses Pengembalian';
                            $tahapanColor = 'text-blue-800';
                            break;
                        case 'ditolak':
                            $statusText = 'Ditolak';
                            $badgeClass = 'bg-[#fcebeb] text-[#8b3c3c] px-4 py-1.5 rounded-full text-xs font-semibold';
                            $tahapanText = 'Ditolak';
                            $tahapanColor = 'text-[#8b3c3c]';
                            break;
                        case 'pending':
                        case 'revisi':
                        default:
                            $statusText = 'Pending';
                            $badgeClass = 'bg-[#fcf1d3] text-[#7d6006] px-4 py-1.5 rounded-full text-xs font-semibold';
                            $tahapanText = 'Menunggu Perbaikan';
                            $tahapanColor = 'text-[#7d6006]';
                            break;
                    }
                @endphp
                <tr>
                    <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">SBUM-2026-{{ str_pad($p->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                        <span class="fw-semibold">{{ $p->tanggal_pengajuan ? \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }}</span>
                        <br>
                        <span class="small text-muted">{{ $p->jam_mulai ? substr($p->jam_mulai, 0, 5) : '08:00' }} - {{ $p->jam_selesai ? substr($p->jam_selesai, 0, 5) : '12:00' }}</span>
                    </td>
                    <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $p->user->nama_lengkap ?? '-' }}</td>
                    <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                        {{ $p->ruangan->isNotEmpty() ? $p->ruangan->first()->nama_ruangan : ($p->barang->isNotEmpty() ? $p->barang->first()->nama_barang : '-') }}
                    </td>
                    <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $p->nama_kegiatan }}</td>
                    <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $p->keterangan ?: '-' }}</td>
                    <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $p->dosen->nama_lengkap ?? '-' }}</td>
                    <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                        @if($p->ruangan->isNotEmpty())
                            {{ $p->ruangan->first()->pic->nama_lengkap ?? '-' }}
                        @elseif($p->barang->isNotEmpty())
                            {{ $p->barang->first()->pic->nama_lengkap ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 border-b border-[#e6ddd2] text-center">
                        @if($p->status === 'proses_pengembalian')
                            <a href="{{ route('pic.pengembalian', ['selected_id' => $p->id_peminjaman]) }}" class="hover:opacity-80 transition inline-block">
                                <span class="{{ $badgeClass }} block w-max mx-auto">{{ $statusText }}</span>
                            </a>
                        @else
                            <span class="{{ $badgeClass }} block w-max mx-auto">{{ $statusText }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 border-b border-[#e6ddd2] text-sm font-semibold {{ $tahapanColor }}">{{ $tahapanText }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">Tidak ada data riwayat peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div class="d-flex justify-content-end mb-4">
    {{ $peminjaman->links('pagination::bootstrap-5') }}
</div>

<!-- Explainer & Export Scripts -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4 mb-4">
    <div class="card border-0 rounded-4 p-4" style="background: #fffdfa; border: 1px solid var(--line) !important; max-width: 450px;">
        <div class="text-secondary small fw-semibold">Detail Cepat</div>
        <div class="text-muted small mt-1">Di layar ini admin bisa membuka detail peminjaman, mengubah status, atau memperbarui catatan transaksi.</div>
    </div>
</div>

<!-- PDF Export library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('exportPdfBtn').addEventListener('click', function() {
        const element = document.getElementById('peminjamanTableContainer');
        const opt = {
            margin:       0.3,
            filename:     'riwayat_peminjaman_sbum.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        };
        html2pdf().set(opt).from(element).save();
    });
</script>
@endsection
