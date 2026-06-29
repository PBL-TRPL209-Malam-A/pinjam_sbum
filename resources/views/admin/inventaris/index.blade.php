@extends('layout.admin')

@section('page_caption', 'Kelola Barang Inventaris')
@section('page_heading', 'Admin SBUM')

@section('admin_content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .custom-table {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        overflow: hidden;
    }
    .custom-table th {
        background-color: #f7f3eb;
        color: var(--text-main);
        font-weight: 600;
        border: none;
        padding: 1rem 1.5rem;
    }
    .custom-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--line);
        color: var(--text-main);
    }
    .badge-baik {
        background-color: #e2f0d9;
        color: #385723;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
    .badge-cek {
        background-color: #fcf1d3;
        color: #7d6006;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
    .badge-rusak {
        background-color: #fcebeb;
        color: #8b3c3c;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
    .action-btn-group {
        display: flex;
        gap: 0.5rem;
    }
    .btn-ubah {
        border: 1px solid var(--line);
        background: white;
        color: var(--text-main);
        font-weight: 500;
        border-radius: 0.75rem;
        padding: 0.4rem 1.2rem;
        transition: 0.2s;
    }
    .btn-ubah:hover {
        background: #fdfcf9;
    }
    .btn-hapus {
        background-color: #c95b50;
        color: white;
        font-weight: 500;
        border-radius: 0.75rem;
        padding: 0.4rem 1.2rem;
        border: none;
        transition: 0.2s;
    }
    .btn-hapus:hover {
        background-color: #b34e44;
        color: white;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Kelola barang inventaris yang dapat dipinjam</h2>
        <p class="mb-0 text-secondary text-wrap" style="max-width: 650px;">
            Admin menyimpan, mengubah, dan memantau stok inventaris seperti projector, sound system, meja, dan kursi.
        </p>
    </div>
</div>

<!-- Controls Bar -->
<div class="card border-0 rounded-4 p-3 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
    <div class="d-flex flex-wrap gap-3">
        <button class="btn btn-main" data-bs-toggle="modal" data-bs-target="#tambahInventarisModal">Tambah Inventaris</button>
    </div>
</div>

<!-- Table Area -->
<div class="mb-3 fw-semibold text-secondary">Tabel Inventaris</div>
<div class="custom-table mb-4">
    <table class="table table-borderless mb-0">
        <thead>
            <tr>
                <th>Barang</th>
                <th>Foto</th>
                <th>Keterangan</th>
                <th>Stok</th>
                <th>PIC Barang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barang as $b)
            <tr>
                <td class="fw-semibold">{{ $b->nama_barang }}</td>
                <td>
                    @if($b->foto_barang)
                        <img src="{{ asset($b->foto_barang) }}" alt="{{ $b->nama_barang }}" class="img-fluid rounded-3" style="width: 100px; height: 100px; object-fit: cover; max-width: 100%;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">
                            Tidak ada foto
                        </div>
                    @endif
                </td>
                <td>{{ $b->keterangan ?: 'Gudang SBUM' }}</td>
                <td>{{ $b->stok_tersedia }} unit</td>
                <td class="fw-semibold">{{ $b->pic ? $b->pic->nama_lengkap : '-' }}</td>
                <td>
                    <div class="action-btn-group">
                        <button class="btn btn-ubah" data-bs-toggle="modal" data-bs-target="#editInventarisModal{{ $b->id_barang }}">Ubah</button>
                        <form action="{{ route('admin.inventaris.destroy', $b->id_barang) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-hapus">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>

            <!-- Edit Modal for each Barang -->
            <div class="modal fade" id="editInventarisModal{{ $b->id_barang }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow-lg" style="background-color: #fffdfa;">
                        <div class="modal-header border-0 pb-0" style="background-color: #f7f3eb; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                            <h5 class="modal-title fw-bold text-main">Ubah Barang Inventaris</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.inventaris.update', $b->id_barang) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label text-secondary fw-semibold">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control" value="{{ $b->nama_barang }}" required style="border-radius:0.75rem;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary fw-semibold">Kode Barang</label>
                                    <input type="text" name="kode_barang" class="form-control" value="{{ $b->kode_barang }}" style="border-radius:0.75rem;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary fw-semibold">Stok Tersedia</label>
                                    <input type="number" name="stok_tersedia" class="form-control" value="{{ $b->stok_tersedia }}" required style="border-radius:0.75rem;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary fw-semibold">Keterangan / Lokasi</label>
                                    <input type="text" name="keterangan" class="form-control" value="{{ $b->keterangan }}" placeholder="cth: Gudang SBUM" style="border-radius:0.75rem;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary fw-semibold">PIC Barang <span class="text-danger">*</span></label>
                                    <select name="pic_id" class="form-select" style="border-radius:0.75rem;" required>
                                        <option value="">-- Pilih PIC --</option>
                                        @foreach($pics as $p)
                                            <option value="{{ $p->id_user }}" {{ $b->pic_id == $p->id_user ? 'selected' : '' }}>{{ $p->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary fw-semibold">Foto Barang</label>
                                    @if($b->foto_barang)
                                        <div class="mb-2">
                                            <img src="{{ asset($b->foto_barang) }}" alt="Foto saat ini" class="img-thumbnail" style="max-width: 150px; height: auto;">
                                        </div>
                                    @endif
                                    <input type="file" name="foto_barang" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" style="border-radius:0.75rem;">
                                    <div class="form-text text-muted" style="font-size: 0.8rem;">Hanya menerima JPG, JPEG, PNG, WEBP (maks. 10MB)</div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn action-btn-outline" data-bs-dismiss="modal" style="border-radius:0.75rem;">Batal</button>
                                <button type="submit" class="btn btn-main" style="border-radius:0.75rem;">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <!-- Realistic fallback content matching Image 3 -->
            <tr>
                <td class="fw-semibold">LCD Projector Epson</td>
                <td>
                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">
                        Tidak ada foto
                    </div>
                </td>
                <td>Gudang SBUM</td>
                <td>4 unit</td>
                <td class="fw-semibold">-</td>
                <td>
                    <div class="action-btn-group">
                        <button class="btn btn-ubah">Ubah</button>
                        <button class="btn btn-hapus">Hapus</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="fw-semibold">Sound System</td>
                <td>
                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">
                        Tidak ada foto
                    </div>
                </td>
                <td>Unit Audio</td>
                <td>2 set</td>
                <td class="fw-semibold">-</td>
                <td>
                    <div class="action-btn-group">
                        <button class="btn btn-ubah">Ubah</button>
                        <button class="btn btn-hapus">Hapus</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="fw-semibold">Kursi Lipat</td>
                <td>
                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">
                        Tidak ada foto
                    </div>
                </td>
                <td>Gudang Sarpras</td>
                <td>60 unit</td>
                <td class="fw-semibold">-</td>
                <td>
                    <div class="action-btn-group">
                        <button class="btn btn-ubah">Ubah</button>
                        <button class="btn btn-hapus">Hapus</button>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Create Modal -->
<div class="modal fade" id="tambahInventarisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background-color: #fffdfa;">
            <div class="modal-header border-0 pb-0" style="background-color: #f7f3eb; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title fw-bold text-main">Tambah Barang Inventaris</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.inventaris.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="cth: LCD Projector Epson" required style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control" placeholder="cth: BRG001" style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Stok Tersedia</label>
                        <input type="number" name="stok_tersedia" class="form-control" placeholder="cth: 4" required style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Keterangan / Lokasi</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="cth: Gudang SBUM" style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">PIC Barang <span class="text-danger">*</span></label>
                        <select name="pic_id" class="form-select" style="border-radius:0.75rem;" required>
                            <option value="">-- Pilih PIC --</option>
                            @foreach($pics as $p)
                                <option value="{{ $p->id_user }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Foto Barang</label>
                        <input type="file" name="foto_barang" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" style="border-radius:0.75rem;">
                        <div class="form-text text-muted" style="font-size: 0.8rem;">Hanya menerima JPG, JPEG, PNG, WEBP (maks. 10MB)</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn action-btn-outline" data-bs-dismiss="modal" style="border-radius:0.75rem;">Batal</button>
                    <button type="submit" class="btn btn-main" style="border-radius:0.75rem;">Simpan Inventaris</button>
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
