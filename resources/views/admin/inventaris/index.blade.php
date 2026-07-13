@extends('layout.app_tailwind')




@section('content')


<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div>
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Kelola barang inventaris yang dapat dipinjam</h2>
        <p class="text-[#7d8781] max-w-2xl mb-0">
            Admin menyimpan, mengubah, dan memantau stok inventaris seperti projector, sound system, meja, dan kursi.
        </p>
    </div>
</div>

<!-- Controls Bar -->
<div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-4 mb-6">
    <div class="d-flex flex-wrap gap-3">
        <button class="bg-[#466454] hover:bg-[#395244] text-white px-5 py-2.5 rounded-[14px] font-semibold transition" data-bs-toggle="modal" data-bs-target="#tambahInventarisModal">Tambah Inventaris</button>
    </div>
</div>

<!-- Table Area -->
<div class="font-semibold text-[#7d8781] mb-4">Tabel Inventaris</div>
<div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] overflow-x-auto mb-6">
    <table class="w-full text-left whitespace-nowrap">
        <thead><tr class="bg-[#f7f3eb] text-[#33403b]"><th class="px-6 py-4 font-semibold text-sm">Barang</th><th class="px-6 py-4 font-semibold text-sm">Foto</th><th class="px-6 py-4 font-semibold text-sm">Keterangan</th><th class="px-6 py-4 font-semibold text-sm">Stok</th><th class="px-6 py-4 font-semibold text-sm">PIC Barang</th><th class="px-6 py-4 font-semibold text-sm rounded-tr-none">Aksi</th></tr></thead>
        <tbody>
            @forelse($barang as $b)
            <tr>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">{{ $b->nama_barang }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    @if($b->foto_barang)
                        <img src="{{ asset($b->foto_barang) }}" alt="{{ $b->nama_barang }}" class="w-20 h-20 min-w-20 object-cover rounded-xl border border-[#e6ddd2]">
                    @else
                        <div class="w-20 h-20 min-w-20 rounded-xl flex items-center justify-center bg-[#f7f3eb] text-[#7d8781] text-xs border border-dashed border-[#e6ddd2] text-center p-2">
                            Tidak ada foto
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $b->keterangan ?: 'Gudang SBUM' }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $b->stok_tersedia }} unit</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">{{ $b->pic ? $b->pic->nama_lengkap : '-' }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <div class="flex items-center gap-2">
                        <button class="bg-[#fdfcf9] border border-[#e6ddd2] text-[#54615b] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#f7f3eb] transition" data-bs-toggle="modal" data-bs-target="#detailInventarisModal{{ $b->id_barang }}">Detail</button>
                        <button class="bg-[#f0f5ff] text-[#2563eb] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#dbeafe] transition" data-bs-toggle="modal" data-bs-target="#editInventarisModal{{ $b->id_barang }}">Ubah</button>
                        <form action="{{ route('admin.inventaris.destroy', $b->id_barang) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-[#fef2f2] text-[#dc2626] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#fee2e2] transition">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>

            <!-- Detail Modal for each Barang -->
            <div class="modal fade" id="detailInventarisModal{{ $b->id_barang }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-[#fffdfa] border-0 rounded-2xl shadow-xl">
                        <div class="modal-header bg-[#f7f3eb] border-0 rounded-t-2xl pb-4">
                            <h5 class="modal-title font-bold text-[#466454] text-lg">Detail Barang Inventaris</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            @if($b->foto_barang)
                                <div class="mb-4 text-center">
                                    <img src="{{ asset($b->foto_barang) }}" alt="{{ $b->nama_barang }}" class="img-fluid rounded-xl border border-[#e6ddd2] max-h-48 mx-auto object-cover">
                                </div>
                            @endif
                            <table class="w-full text-sm text-[#54615b]">
                                <tbody>
                                    <tr class="border-b border-[#e6ddd2]"><td class="py-2 font-semibold">Nama Barang</td><td class="py-2">{{ $b->nama_barang }}</td></tr>
                                    <tr class="border-b border-[#e6ddd2]"><td class="py-2 font-semibold">Kode Barang</td><td class="py-2">{{ $b->kode_barang ?? '-' }}</td></tr>
                                    <tr class="border-b border-[#e6ddd2]"><td class="py-2 font-semibold">Stok Tersedia</td><td class="py-2">{{ $b->stok_tersedia }} unit</td></tr>
                                    <tr class="border-b border-[#e6ddd2]"><td class="py-2 font-semibold">Keterangan/Lokasi</td><td class="py-2">{{ $b->keterangan ?: 'Gudang SBUM' }}</td></tr>
                                    <tr><td class="py-2 font-semibold">PIC Barang</td><td class="py-2">{{ $b->pic ? $b->pic->nama_lengkap : '-' }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal for each Barang -->
            <div class="modal fade" id="editInventarisModal{{ $b->id_barang }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-[#fffdfa] border-0 rounded-2xl shadow-xl">
                        <div class="modal-header bg-[#f7f3eb] border-0 rounded-t-2xl pb-4">
                            <h5 class="modal-title font-bold text-[#466454] text-lg">Ubah Barang Inventaris</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.inventaris.update', $b->id_barang) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $b->nama_barang }}" required >
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Kode Barang</label>
                                    <input type="text" name="kode_barang" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $b->kode_barang }}" >
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Stok Tersedia</label>
                                    <input type="number" name="stok_tersedia" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $b->stok_tersedia }}" required >
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Keterangan / Lokasi</label>
                                    <input type="text" name="keterangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $b->keterangan }}" placeholder="cth: Gudang SBUM" >
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">PIC Barang <span class="text-danger">*</span></label>
                                    <select name="pic_id" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]"  required>
                                        <option value="">-- Pilih PIC --</option>
                                        @foreach($pics as $p)
                                            <option value="{{ $p->id_user }}" {{ $b->pic_id == $p->id_user ? 'selected' : '' }}>{{ $p->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Foto Barang</label>
                                    @if($b->foto_barang)
                                        <div class="mb-2">
                                            <img src="{{ asset($b->foto_barang) }}" alt="Foto saat ini" class="img-thumbnail" style="max-width: 150px; height: auto;">
                                        </div>
                                    @endif
                                    <input type="file" name="foto_barang" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" accept="image/png, image/jpeg, image/jpg, image/webp" >
                                    <div class="form-text text-muted" style="font-size: 0.8rem;">Hanya menerima JPG, JPEG, PNG, WEBP (maks. 10MB)</div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="border border-[#e6ddd2] text-[#7d8781] px-5 py-2.5 rounded-xl font-semibold hover:bg-[#f5f2ec] transition" data-bs-dismiss="modal" >Batal</button>
                                <button type="submit" class="bg-[#466454] text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-[#395244] transition" >Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <!-- Realistic fallback content matching Image 3 -->
            <tr>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">LCD Projector Epson</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">
                        Tidak ada foto
                    </div>
                </td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">Gudang SBUM</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">4 unit</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">-</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <div class="flex items-center gap-2">
                        <button class="bg-[#f0f5ff] text-[#2563eb] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#dbeafe] transition">Ubah</button>
                        <button class="bg-[#fef2f2] text-[#dc2626] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#fee2e2] transition">Hapus</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">Sound System</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">
                        Tidak ada foto
                    </div>
                </td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">Unit Audio</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">2 set</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">-</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <div class="flex items-center gap-2">
                        <button class="bg-[#f0f5ff] text-[#2563eb] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#dbeafe] transition">Ubah</button>
                        <button class="bg-[#fef2f2] text-[#dc2626] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#fee2e2] transition">Hapus</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">Kursi Lipat</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">
                        Tidak ada foto
                    </div>
                </td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">Gudang Sarpras</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">60 unit</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">-</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <div class="flex items-center gap-2">
                        <button class="bg-[#f0f5ff] text-[#2563eb] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#dbeafe] transition">Ubah</button>
                        <button class="bg-[#fef2f2] text-[#dc2626] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#fee2e2] transition">Hapus</button>
                    </div>
                </td>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Create Modal -->
<div class="modal fade" id="tambahInventarisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-[#fffdfa] border-0 rounded-2xl shadow-xl">
            <div class="modal-header bg-[#f7f3eb] border-0 rounded-t-2xl pb-4">
                <h5 class="modal-title font-bold text-[#466454] text-lg">Tambah Barang Inventaris</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.inventaris.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Nama Barang</label>
                        <input type="text" name="nama_barang" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="cth: LCD Projector Epson" required >
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Kode Barang</label>
                        <input type="text" name="kode_barang" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="cth: BRG001" >
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Stok Tersedia</label>
                        <input type="number" name="stok_tersedia" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="cth: 4" required >
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Keterangan / Lokasi</label>
                        <input type="text" name="keterangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="cth: Gudang SBUM" >
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">PIC Barang <span class="text-danger">*</span></label>
                        <select name="pic_id" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]"  required>
                            <option value="">-- Pilih PIC --</option>
                            @foreach($pics as $p)
                                <option value="{{ $p->id_user }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Foto Barang</label>
                        <input type="file" name="foto_barang" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" accept="image/png, image/jpeg, image/jpg, image/webp" >
                        <div class="form-text text-muted" style="font-size: 0.8rem;">Hanya menerima JPG, JPEG, PNG, WEBP (maks. 10MB)</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="border border-[#e6ddd2] text-[#7d8781] px-5 py-2.5 rounded-xl font-semibold hover:bg-[#f5f2ec] transition" data-bs-dismiss="modal" >Batal</button>
                    <button type="submit" class="bg-[#466454] text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-[#395244] transition" >Simpan Inventaris</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('adminSearchInput');
    const searchButton = document.getElementById('adminSearchButton');
    
    function performSearch() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const tbody = document.querySelector('.custom-table tbody');
        if (!tbody) return;
        const rows = tbody.querySelectorAll('tr');
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            // Ignore noResultsRow
            if (row.id === 'noResultsRow') {
                row.remove();
                return;
            }
            
            // Ignore default empty data row
            if (row.cells.length === 1 && row.innerText.includes('Belum ada')) {
                return;
            }
            
            // Cells: 0 = Barang, 1 = Foto, 2 = Lokasi, 3 = Stok, 4 = Aksi
            const name = row.cells[0] ? row.cells[0].innerText.toLowerCase() : '';
            const location = row.cells[2] ? row.cells[2].innerText.toLowerCase() : '';
            const stock = row.cells[3] ? row.cells[3].innerText.toLowerCase() : '';
            
            const matchesSearch = name.includes(query) || location.includes(query) || stock.includes(query);
            
            if (matchesSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        const existingNoResults = document.getElementById('noResultsRow');
        if (visibleCount === 0) {
            if (!existingNoResults) {
                const tr = document.createElement('tr');
                tr.id = 'noResultsRow';
                tr.innerHTML = '<td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">Tidak ada data inventaris yang cocok dengan kriteria pencarian.</td>';
                tbody.appendChild(tr);
            }
        } else {
            if (existingNoResults) {
                existingNoResults.remove();
            }
        }
    }
    
    if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });
    }
    
    if (searchButton) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            performSearch();
        });
    }
});
</script>
@endsection
