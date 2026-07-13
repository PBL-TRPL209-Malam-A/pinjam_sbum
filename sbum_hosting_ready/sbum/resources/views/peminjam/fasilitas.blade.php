@extends('layout.app_tailwind')



@section('content')
            <form action="{{ route('peminjam.fasilitas') }}" method="GET">
                <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 mb-6">
                    <div>
                        <div class="text-[#7b8681] text-[20px] mb-1">Peminjam</div>
                        <h1 class="text-[24px] font-medium m-0">Peminjam · Daftar Fasilitas</h1>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <input type="text" name="search" class="h-12 px-4 rounded-2xl border border-[#ddd2c5] bg-[#fffdfa] focus:outline-none focus:border-[#466454] w-full md:w-64 transition" placeholder="Cari data" value="{{ request('search') }}">
                        <button type="submit" class="w-12 h-12 bg-[#cfdacd] rounded-full flex items-center justify-center shrink-0 hover:bg-[#c2cec0] transition" title="Cari">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#33403b" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[28px] p-6 lg:p-8 mb-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-xl font-semibold mb-2">Lihat daftar fasilitas yang tersedia</h2>
                        <p class="mb-0 text-[#5f6963]">
                            Peminjam membuka menu fasilitas dan sistem menampilkan ruangan serta barang inventaris yang bisa dipinjam.
                        </p>
                    </div>
                    <!-- Decorative shapes -->
                    <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/40 rounded-full blur-2xl"></div>
                    <div class="absolute right-20 -bottom-10 w-32 h-32 bg-[#d6e5d6]/60 rounded-full blur-xl"></div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                    <!-- Filter Column -->
                    <div class="xl:col-span-1">
                        <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Filter</div>

                        <div class="flex flex-col gap-4">
                            <select name="category" class="h-12 px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] text-[#727d77] focus:outline-none focus:border-[#466454] transition">
                                <option value="Semua" {{ request('category') == 'Semua' ? 'selected' : '' }}>Kategori: Semua</option>
                                <option value="Ruangan" {{ request('category') == 'Ruangan' ? 'selected' : '' }}>Ruangan</option>
                                <option value="Inventaris" {{ request('category') == 'Inventaris' ? 'selected' : '' }}>Inventaris</option>
                            </select>

                            <select name="location" class="h-12 px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] text-[#727d77] focus:outline-none focus:border-[#466454] transition">
                                <option value="Semua Gedung" {{ request('location') == 'Semua Gedung' ? 'selected' : '' }}>Lokasi: Semua Gedung</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building }}" {{ request('location') == $building ? 'selected' : '' }}>{{ $building }}</option>
                                @endforeach
                            </select>

                            <select name="status" class="h-12 px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] text-[#727d77] focus:outline-none focus:border-[#466454] transition">
                                <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Status: Semua</option>
                                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="tidak tersedia" {{ request('status') == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>

                            <button type="submit" class="h-12 bg-[#5d7d6b] hover:bg-[#496454] text-white font-semibold rounded-2xl transition border-0 cursor-pointer">
                                Terapkan
                            </button>
                        </div>
                    </div>

                    <!-- Facility List -->
                    <div class="xl:col-span-3">
                        <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Daftar Fasilitas</div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @forelse($facilities as $item)
                                <div class="border border-[#e0d7cb] rounded-[24px] bg-[#fffdfa] p-4 lg:p-5 flex flex-col justify-between h-full min-h-[320px] transition hover:shadow-md hover:border-[#cfdacd]">
                                    <div>
                                        @if($item->foto && file_exists(public_path($item->foto)))
                                            <img src="{{ asset($item->foto) }}" alt="{{ $item->nama }}" class="h-32 w-full object-cover rounded-[18px] mb-4">
                                        @else
                                            <div class="h-32 w-full rounded-[18px] mb-4 flex items-center justify-center text-[#727d77] font-semibold {{ $item->kategori === 'Ruangan' ? 'bg-[#dfe8df]' : 'bg-[#e7ddd3]' }}">
                                                <span>{{ $item->kategori }}</span>
                                            </div>
                                        @endif
                                        <h3 class="text-[17px] text-[#4c5752] font-bold mb-3">{{ $item->nama }}</h3>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mt-auto">
                                        <div class="text-[#5f6963] text-[15px]">
                                            <span class="font-semibold text-[#33403b]">{{ $item->kategori }}</span> &middot; {{ $item->detail_meta }}
                                            @if($item->kategori === 'Ruangan' && $item->lokasi)
                                                <br><small class="text-[#7b8681]"><i class="bi bi-geo-alt"></i> {{ $item->lokasi }}</small>
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('peminjam.pengajuan', ['facility_id' => $item->kategori . '-' . $item->id]) }}" class="bg-[#5d7d6b] hover:bg-[#496454] text-white font-semibold text-[13.5px] rounded-full px-4 py-2 transition no-underline text-center">
                                                Ajukan Peminjaman
                                            </a>
                                            <button type="button" class="btn-detail bg-[#eef4ee] hover:bg-[#5d7d6b] hover:text-white text-[#496454] border border-[#c8d8c8] hover:border-[#5d7d6b] font-semibold text-[13.5px] rounded-full px-4 py-2 transition cursor-pointer" data-id="{{ $item->id }}" data-category="{{ $item->kategori }}">
                                                Detail
                                            </button>
                                            @if(strtolower($item->status) === 'tersedia')
                                                <span class="bg-[#dcebd7] text-[#557b58] border border-[#b7d2b6] font-semibold rounded-full px-4 py-2 text-[13.5px]">Tersedia</span>
                                            @elseif(strtolower($item->status) === 'terbatas')
                                                <span class="bg-[#f4e7c9] text-[#92723c] border border-[#e3c98b] font-semibold rounded-full px-4 py-2 text-[13.5px]">Terbatas</span>
                                            @elseif(strtolower($item->status) === 'maintenance')
                                                <span class="bg-[#fdf2e2] text-[#b7791f] border border-[#fbd38d] font-semibold rounded-full px-4 py-2 text-[13.5px]">Maintenance</span>
                                            @else
                                                <span class="bg-[#fde8e8] text-[#c53030] border border-[#feb2b2] font-semibold rounded-full px-4 py-2 text-[13.5px]">Tidak Tersedia</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="md:col-span-2 text-center py-10">
                                    <div class="text-[#7b8681] text-lg">Fasilitas atau barang tidak ditemukan.</div>
                                </div>
                            @endforelse
                        </div>

                        @if($facilities->hasPages())
                            <div class="flex justify-center mt-6">
                                {{ $facilities->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<!-- Modal Detail Fasilitas -->
<div class="modal fade" id="facilityDetailModal" tabindex="-1" aria-labelledby="facilityDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border border-[#e0d7cb] bg-[#fcfbf8] rounded-[24px]">
            <div class="modal-header border-b-0 pb-0 pt-6 px-6">
                <h5 class="modal-title font-bold text-[#33403b] text-xl" id="facilityDetailModalLabel">Detail Fasilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <div id="modalLoading" class="text-center py-10">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-[#7b8681]">Memuat data...</p>
                </div>
                <div id="modalContent" class="d-none">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-5/12 text-center md:text-left">
                            <img id="detailFoto" src="" alt="" class="rounded-2xl object-cover w-full mb-4" style="max-height: 250px; display: none;">
                            <div id="detailFotoFallback" class="rounded-2xl w-full mb-4 flex items-center justify-center text-[#7b8681] font-semibold" style="height: 200px; background:#dfe8df;">
                                <span id="detailKategoriFallback"></span>
                            </div>
                            <div class="p-4 rounded-2xl bg-[#edf2ea] border border-[#dfe7dc] text-left">
                                <h6 class="font-bold mb-2 text-[#33403b] text-[15px]">Informasi PIC</h6>
                                <p class="mb-1 text-[#5f6963] text-[14px]">
                                    <i class="bi bi-person mr-2"></i>Nama: <strong class="text-[#33403b]" id="detailPic"></strong>
                                </p>
                            </div>
                        </div>
                        <div class="w-full md:w-7/12">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="badge bg-[#dcebd7] text-[#557b58] font-semibold py-1.5 px-3 rounded-full" id="detailKategoriBadge"></span>
                                <span class="badge text-white py-1.5 px-3 rounded-full" id="detailStatusBadge"></span>
                            </div>
                            <h4 class="font-bold text-[#33403b] text-2xl mb-4" id="detailNama"></h4>
                            
                            <table class="w-full text-left mb-6 text-[15px]">
                                <tbody>
                                    <tr id="rowKode" class="border-b border-[#e7ddd1]/50">
                                        <td class="text-[#7b8681] py-2 w-1/3">Kode</td>
                                        <td class="py-2"><strong id="detailKode" class="text-[#33403b]"></strong></td>
                                    </tr>
                                    <tr id="rowGedung" class="border-b border-[#e7ddd1]/50">
                                        <td class="text-[#7b8681] py-2">Gedung</td>
                                        <td class="py-2"><strong id="detailGedung" class="text-[#33403b]"></strong></td>
                                    </tr>
                                    <tr id="rowLantai" class="border-b border-[#e7ddd1]/50">
                                        <td class="text-[#7b8681] py-2">Lantai</td>
                                        <td class="py-2"><strong id="detailLantai" class="text-[#33403b]"></strong></td>
                                    </tr>
                                    <tr id="rowKapasitas" class="border-b border-[#e7ddd1]/50">
                                        <td class="text-[#7b8681] py-2">Kapasitas</td>
                                        <td class="py-2"><strong id="detailKapasitas" class="text-[#33403b]"></strong></td>
                                    </tr>
                                    <tr id="rowStok" class="border-b border-[#e7ddd1]/50">
                                        <td class="text-[#7b8681] py-2">Stok Tersedia</td>
                                        <td class="py-2"><strong id="detailStok" class="text-[#33403b]"></strong></td>
                                    </tr>
                                    <tr id="rowStokTotal" class="border-b border-[#e7ddd1]/50">
                                        <td class="text-[#7b8681] py-2">Stok Total</td>
                                        <td class="py-2"><strong id="detailStokTotal" class="text-[#33403b]"></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div class="mb-6">
                                <h6 class="font-bold text-[#33403b] text-[15px] mb-2">Deskripsi / Keterangan</h6>
                                <div class="p-4 rounded-xl bg-white border border-[#e7ddd1] text-[14px] text-[#55615b]" id="detailDeskripsi"></div>
                            </div>
                            
                            <div id="sectionFasilitasPendukung" class="mb-3">
                                <h6 class="font-bold text-[#33403b] text-[15px] mb-2">Fasilitas Pendukung</h6>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-[14px] border border-[#e7ddd1] rounded-lg overflow-hidden">
                                        <thead class="bg-[#f3f6f3] text-[#5c6761]">
                                            <tr>
                                                <th class="py-2 px-3">Nama Fasilitas</th>
                                                <th class="py-2 px-3 text-center w-1/4">Jumlah</th>
                                                <th class="py-2 px-3">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detailFasilitasList" class="divide-y divide-[#e7ddd1]">
                                            <!-- Facilities list will be appended here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.btn-detail');
    const modalElement = document.getElementById('facilityDetailModal');
    const modal = new bootstrap.Modal(modalElement);
    const modalLoading = document.getElementById('modalLoading');
    const modalContent = document.getElementById('modalContent');

    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const category = this.getAttribute('data-category');
            
            // Show Modal and Loading
            modalLoading.classList.remove('d-none');
            modalContent.classList.add('d-none');
            modal.show();

            // Fetch details
            fetch(`/peminjam/fasilitas/detail?id=${id}&kategori=${category}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data detail');
                    }
                    return response.json();
                })
                .then(res => {
                    if (res.success) {
                        const item = res.data;
                        
                        // Set basic fields
                        document.getElementById('detailNama').textContent = item.nama;
                        document.getElementById('detailKode').textContent = item.kode;
                        document.getElementById('detailPic').textContent = item.pic;
                        document.getElementById('detailKategoriBadge').textContent = res.kategori;
                        
                        // Handle Photo
                        const detailFoto = document.getElementById('detailFoto');
                        const detailFotoFallback = document.getElementById('detailFotoFallback');
                        const detailKategoriFallback = document.getElementById('detailKategoriFallback');
                        
                        if (item.foto) {
                            detailFoto.src = item.foto;
                            detailFoto.alt = item.nama;
                            detailFoto.style.display = 'block';
                            detailFotoFallback.classList.add('d-none');
                        } else {
                            detailFoto.style.display = 'none';
                            detailFotoFallback.classList.remove('d-none');
                            detailKategoriFallback.textContent = res.kategori;
                            // Set color classes
                            if (res.kategori === 'Ruangan') {
                                detailFotoFallback.style.background = '#dfe8df';
                            } else {
                                detailFotoFallback.style.background = '#e7ddd3';
                            }
                        }

                        // Set Status Badge style & text
                        const statusBadge = document.getElementById('detailStatusBadge');
                        const statusLower = item.status.toLowerCase();
                        if (statusLower === 'tersedia') {
                            statusBadge.textContent = 'Tersedia';
                            statusBadge.className = 'badge text-[#557b58] font-semibold py-1.5 px-3 rounded-full border border-[#b7d2b6]';
                            statusBadge.style.background = '#dcebd7';
                        } else if (statusLower === 'terbatas') {
                            statusBadge.textContent = 'Terbatas';
                            statusBadge.className = 'badge text-[#92723c] font-semibold py-1.5 px-3 rounded-full border border-[#e3c98b]';
                            statusBadge.style.background = '#f4e7c9';
                        } else if (statusLower === 'maintenance') {
                            statusBadge.textContent = 'Maintenance';
                            statusBadge.className = 'badge text-[#b7791f] font-semibold py-1.5 px-3 rounded-full border border-[#fbd38d]';
                            statusBadge.style.background = '#fdf2e2';
                        } else {
                            statusBadge.textContent = 'Tidak Tersedia';
                            statusBadge.className = 'badge text-[#c53030] font-semibold py-1.5 px-3 rounded-full border border-[#feb2b2]';
                            statusBadge.style.background = '#fde8e8';
                        }

                        // Conditional layouts for Room vs Inventory
                        if (res.kategori === 'Ruangan') {
                            document.getElementById('rowGedung').classList.remove('d-none');
                            document.getElementById('rowLantai').classList.remove('d-none');
                            document.getElementById('rowKapasitas').classList.remove('d-none');
                            document.getElementById('rowStok').classList.add('d-none');
                            document.getElementById('rowStokTotal').classList.add('d-none');
                            document.getElementById('sectionFasilitasPendukung').classList.remove('d-none');
                            
                            document.getElementById('detailGedung').textContent = item.gedung || 'N/A';
                            document.getElementById('detailLantai').textContent = item.lantai || 'N/A';
                            document.getElementById('detailKapasitas').textContent = `${item.kapasitas} orang`;
                            document.getElementById('detailDeskripsi').textContent = item.deskripsi;
                            
                            // Load supporting facilities list
                            const fasilitasTbody = document.getElementById('detailFasilitasList');
                            fasilitasTbody.innerHTML = '';
                            if (item.fasilitas_pendukung && item.fasilitas_pendukung.length > 0) {
                                item.fasilitas_pendukung.forEach(f => {
                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td class="py-2 px-3 text-[#33403b] font-medium">${f.nama}</td>
                                        <td class="py-2 px-3 text-center">${f.jumlah}</td>
                                        <td class="py-2 px-3 text-[#5f6963]">${f.keterangan}</td>
                                    `;
                                    fasilitasTbody.appendChild(tr);
                                });
                            } else {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `<td colspan="3" class="text-center text-[#7b8681] py-3">Tidak ada fasilitas pendukung terdaftar.</td>`;
                                fasilitasTbody.appendChild(tr);
                            }
                        } else {
                            document.getElementById('rowGedung').classList.add('d-none');
                            document.getElementById('rowLantai').classList.add('d-none');
                            document.getElementById('rowKapasitas').classList.add('d-none');
                            document.getElementById('rowStok').classList.remove('d-none');
                            document.getElementById('rowStokTotal').classList.remove('d-none');
                            document.getElementById('sectionFasilitasPendukung').classList.add('d-none');
                            
                            document.getElementById('detailStok').textContent = `${item.stok} unit`;
                            document.getElementById('detailStokTotal').textContent = `${item.stok_total} unit`;
                            document.getElementById('detailDeskripsi').textContent = item.keterangan;
                        }

                        // Hide loading, show content
                        modalLoading.classList.add('d-none');
                        modalContent.classList.remove('d-none');
                    } else {
                        alert(res.message || 'Gagal memuat data detail.');
                        modal.hide();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat menghubungi server: ' + err.message);
                    modal.hide();
                });
        });
    });
});
</script>
@endsection
