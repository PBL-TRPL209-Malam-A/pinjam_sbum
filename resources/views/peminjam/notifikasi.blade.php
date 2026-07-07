@extends('layout.app_tailwind')



@section('content')
            <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 mb-6">
                <div>
                    <div class="text-[#7b8681] text-[20px] mb-1">Peminjam</div>
                    <h1 class="text-[24px] font-medium m-0">Notifikasi Peminjam</h1>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <input type="text" class="h-12 px-4 rounded-2xl border border-[#ddd2c5] bg-[#fffdfa] focus:outline-none focus:border-[#466454] w-full md:w-64 transition" placeholder="Cari notifikasi">
                    <div class="w-12 h-12 bg-[#cfdacd] rounded-full flex shrink-0"></div>
                </div>
            </div>

            <div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[28px] p-6 lg:p-8 mb-6 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-xl font-semibold mb-2">Pantau informasi terbaru akun peminjam</h2>
                    <p class="mb-4 text-[#5f6963]">
                        Halaman ini menampilkan pemberitahuan penting terkait pengajuan, persetujuan, jadwal penggunaan, pengembalian, dan aktivitas akun peminjam.
                    </p>

                </div>
                <!-- Decorative shapes -->
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/40 rounded-full blur-2xl"></div>
                <div class="absolute right-20 -bottom-10 w-32 h-32 bg-[#d6e5d6]/60 rounded-full blur-xl"></div>
            </div>

            <div class="flex flex-wrap gap-2 mb-6" id="filter-container">
                <span onclick="filterNotif('semua', this)" class="filter-btn active-filter px-5 py-2.5 rounded-full border border-[#466454] bg-[#466454] text-white font-medium text-sm transition cursor-pointer">Semua</span>
                <span onclick="filterNotif('persetujuan', this)" class="filter-btn px-5 py-2.5 rounded-full border border-[#ddd2c5] bg-[#fffdfa] text-[#5f6963] font-medium text-sm transition hover:bg-[#edf2ea] cursor-pointer">Persetujuan</span>
                                <span onclick="filterNotif('pengembalian', this)" class="filter-btn px-5 py-2.5 rounded-full border border-[#ddd2c5] bg-[#fffdfa] text-[#5f6963] font-medium text-sm transition hover:bg-[#edf2ea] cursor-pointer">Pengembalian</span>
                            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2">
                    <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Daftar Notifikasi</div>

                    <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#f7f3eb] border-b border-[#e0d7cb]">
                                        <th class="py-4 px-6 text-[#5c6761] font-semibold text-sm">Fasilitas & Kegiatan</th>
                                        <th class="py-4 px-6 text-[#5c6761] font-semibold text-sm">Tanggal</th>
                                        <th class="py-4 px-6 text-[#5c6761] font-semibold text-sm text-center">Status</th>
                                        <th class="py-4 px-6 text-[#5c6761] font-semibold text-sm text-center">Waktu</th>
<th class="py-4 px-6 text-[#5c6761] font-semibold text-sm text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($notifikasi as $notif)
                                        @php
                                            $fasilitas = $notif->jenis_peminjaman === 'ruangan' ? (($notif->ruangan->first()->nama_ruangan ?? 'Ruangan') . ' (' . ($notif->ruangan->first()->nama_gedung ?? 'Gedung tidak ditentukan') . ')') : ($notif->barang->first()->nama_barang ?? 'Barang');
                                            
                                            $badgeClasses = 'bg-[#e4ece8] text-[#4f6a5c] border-[#cfddd5]';
                                            $statusText = str_replace('_', ' ', $notif->status);
                                            $kategori = 'persetujuan'; // Default
                                            
                                            if(in_array($notif->status, ['menunggu_dosen', 'menunggu_admin', 'menunggu_kepala', 'menunggu_pic', 'ditolak', 'batal'])) {
                                                $badgeClasses = $notif->status === 'ditolak' || $notif->status === 'batal' ? 'bg-[#f3dedd] text-[#a4534d] border-[#e1aba5]' : 'bg-[#f4e7c9] text-[#92723c] border-[#e3c98b]';
                                                $statusText = in_array($notif->status, ['ditolak', 'batal']) ? ucfirst($notif->status) : 'Menunggu Verifikasi';
                                                $kategori = 'persetujuan';
                                            } elseif($notif->status === 'disetujui') {
                                                $badgeClasses = 'bg-[#dcebd7] text-[#557b58] border-[#b7d2b6]';
                                                $kategori = 'persetujuan';
                                            } elseif($notif->status === 'selesai' || $notif->status === 'dikembalikan') {
                                                $badgeClasses = 'bg-[#dcebd7] text-[#557b58] border-[#b7d2b6]';
                                                $kategori = 'pengembalian';
                                            }
                                        @endphp
                                        <tr class="notif-row border-b border-[#e0d7cb] hover:bg-[#faf7f2] transition" data-category="{{ $kategori }}">
                                            <td class="py-4 px-6">
                                                <div class="font-bold text-[#33403b] mb-1">{{ $fasilitas }}</div>
                                                <div class="text-[#69746f] text-sm">{{ $notif->nama_kegiatan }}</div>
                                            </td>
                                            <td class="py-4 px-6">
                                                <div class="text-[#33403b] font-medium">{{ \Carbon\Carbon::parse($notif->tanggal_pengajuan)->translatedFormat('d M Y') }}</div>
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $badgeClasses }} border inline-block text-center whitespace-nowrap capitalize">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-center text-[#8a938e] text-sm whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($notif->tanggal_pengajuan)->diffForHumans() }}
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                <button onclick="document.getElementById('modalDetail{{ $notif->id_peminjaman }}').classList.remove('hidden')" class="text-[#466454] font-medium text-sm hover:underline">Detail</button>
                                            </td>
                                        </tr>

                                        <!-- Modal Detail -->
                                        <div id="modalDetail{{ $notif->id_peminjaman }}" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm">
                                            <div class="bg-[#fffdfa] rounded-[24px] w-full max-w-lg mx-4 overflow-hidden shadow-2xl relative border border-[#e0d7cb]">
                                                <div class="bg-[#f7f3eb] px-6 py-4 border-b border-[#e0d7cb] flex justify-between items-center">
                                                    <h3 class="text-lg font-bold text-[#33403b]">Detail Peminjaman</h3>
                                                    <button onclick="document.getElementById('modalDetail{{ $notif->id_peminjaman }}').classList.add('hidden')" class="text-[#7b8681] hover:text-[#33403b] transition">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                                <div class="p-6">
                                                    <div class="mb-4">
                                                        <div class="text-[#7b8681] text-xs uppercase tracking-wider font-semibold mb-1">Nama Kegiatan</div>
                                                        <div class="text-[#33403b] font-medium">{{ $notif->nama_kegiatan }}</div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <div class="text-[#7b8681] text-xs uppercase tracking-wider font-semibold mb-1">Fasilitas</div>
                                                        <div class="text-[#33403b] font-medium">{{ $fasilitas }}</div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <div class="text-[#7b8681] text-xs uppercase tracking-wider font-semibold mb-1">Waktu Pelaksanaan</div>
                                                        <div class="text-[#33403b] font-medium">
                                                            {{ \Carbon\Carbon::parse($notif->tanggal_pengajuan)->translatedFormat('d M Y') }} • 
                                                            {{ $notif->jam_mulai ? str_replace(':', '.', substr($notif->jam_mulai, 0, 5)) : '08.00' }} - {{ $notif->jam_selesai ? str_replace(':', '.', substr($notif->jam_selesai, 0, 5)) : '12.00' }}
                                                        </div>
                                                    </div>
                                                    @if($notif->status == 'ditolak' && $notif->verifikasi->where('status', 'ditolak')->last())
                                                    <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-xl">
                                                        <div class="text-red-700 text-xs uppercase tracking-wider font-bold mb-1">Alasan Penolakan</div>
                                                        <div class="text-red-800 font-medium text-sm">{{ $notif->verifikasi->where('status', 'ditolak')->last()->catatan }}</div>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="px-6 py-4 border-t border-[#e0d7cb] bg-[#faf7f2] flex justify-end">
                                                    <button onclick="document.getElementById('modalDetail{{ $notif->id_peminjaman }}').classList.add('hidden')" class="px-5 py-2.5 rounded-full bg-[#466454] text-white font-medium text-sm hover:bg-[#385043] transition">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                                                            @empty
                                        <tr>
                                            <td colspan="5" class="py-8 text-center text-[#7b8681]">Tidak ada notifikasi atau riwayat peminjaman terbaru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-1">
                    <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Ringkasan Notifikasi</div>

                    <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6 mb-5">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-[#fffcf8] border border-[#e3d9cd] rounded-[16px] p-4 text-center">
                                <div class="text-[#7b8681] text-sm">Belum Dibaca</div>
                                <div class="text-3xl font-bold text-[#33403b] mt-2">{{ $belumDibaca }}</div>
                            </div>
                            <div class="bg-[#fffcf8] border border-[#e3d9cd] rounded-[16px] p-4 text-center">
                                <div class="text-[#7b8681] text-sm">Hari Ini</div>
                                <div class="text-3xl font-bold text-[#33403b] mt-2">{{ $hariIni }}</div>
                            </div>
                            <div class="bg-[#fffcf8] border border-[#e3d9cd] rounded-[16px] p-4 text-center">
                                <div class="text-[#7b8681] text-sm">Persetujuan</div>
                                <div class="text-3xl font-bold text-[#33403b] mt-2">{{ $persetujuan }}</div>
                            </div>

                        </div>
                    </div>

                    <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6">
                        <div class="font-semibold text-lg text-[#33403b] mb-2">Catatan</div>
                        <div class="text-[#7b8681] text-[14.5px] leading-relaxed">
                            Notifikasi akan muncul otomatis saat ada perubahan status pengajuan, jadwal penggunaan, dan proses pengembalian fasilitas peminjam.
                        </div>
                    </div>
                </div>
            </div>
@endsection

<script>
function filterNotif(kategori, el) {
    // Update active button styling
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-[#466454]', 'text-white', 'border-[#466454]', 'active-filter');
        btn.classList.add('bg-[#fffdfa]', 'text-[#5f6963]', 'border-[#ddd2c5]');
    });
    el.classList.remove('bg-[#fffdfa]', 'text-[#5f6963]', 'border-[#ddd2c5]');
    el.classList.add('bg-[#466454]', 'text-white', 'border-[#466454]', 'active-filter');

    // Filter rows
    let visibleCount = 0;
    const rows = document.querySelectorAll('.notif-row');
    rows.forEach(row => {
        if (kategori === 'semua' || row.getAttribute('data-category') === kategori) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Handle empty state
    let emptyRow = document.getElementById('empty-row');
    if (visibleCount === 0) {
        if (!emptyRow) {
            const tbody = document.querySelector('tbody');
            const tr = document.createElement('tr');
            tr.id = 'empty-row';
            tr.innerHTML = '<td colspan="5" class="py-8 text-center text-[#7b8681]">Tidak ada notifikasi di kategori ini.</td>';
            tbody.appendChild(tr);
        } else {
            emptyRow.style.display = '';
        }
    } else if (emptyRow) {
        emptyRow.style.display = 'none';
    }
}

// Search functionality
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('input[placeholder="Cari notifikasi"]');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const activeCategory = document.querySelector('.active-filter').textContent.toLowerCase().trim();
            const filterCat = activeCategory === 'semua' ? 'semua' : activeCategory;

            let visibleCount = 0;
            const rows = document.querySelectorAll('.notif-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matchesSearch = text.includes(term);
                const matchesCat = filterCat === 'semua' || row.getAttribute('data-category') === filterCat;
                
                if (matchesSearch && matchesCat) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle empty state
            let emptyRow = document.getElementById('empty-row');
            if (visibleCount === 0) {
                if (!emptyRow) {
                    const tbody = document.querySelector('tbody');
                    const tr = document.createElement('tr');
                    tr.id = 'empty-row';
                    tr.innerHTML = '<td colspan="5" class="py-8 text-center text-[#7b8681]">Tidak ada notifikasi yang sesuai.</td>';
                    tbody.appendChild(tr);
                } else {
                    emptyRow.style.display = '';
                }
            } else if (emptyRow) {
                emptyRow.style.display = 'none';
            }
        });
    }
});
</script>
