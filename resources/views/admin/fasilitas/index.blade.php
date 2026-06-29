@extends('layout.admin')

@section('page_caption', 'Kelola Data Fasilitas')
@section('page_heading', 'Admin SBUM')

@section('admin_content')
<style>
    /* ... Style CSS Anda tetap sama seperti sebelumnya (dipertahankan semuanya) ... */
    .banner-card { background-color: #edf2ea; border: 1px solid #dfe7dc; border-radius: 1.5rem; }
    .custom-table { background: #fffdfa; border: 1px solid var(--line); border-radius: 1.5rem; overflow: hidden; }
    .custom-table th { background-color: #f7f3eb; color: var(--text-main); font-weight: 600; border: none; padding: 1rem 1.5rem; }
    .custom-table td { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--line); color: var(--text-main); }
    .badge-aktif { background-color: #e2f0d9; color: #385723; font-size: 0.85rem; font-weight: 600; padding: 0.4rem 1.25rem; border-radius: 2rem; display: inline-block; }
    .badge-perawatan { background-color: #fcf1d3; color: #7d6006; font-size: 0.85rem; font-weight: 600; padding: 0.4rem 1.25rem; border-radius: 2rem; display: inline-block; }
    .badge-nonaktif { background-color: #fcebeb; color: #8b3c3c; font-size: 0.85rem; font-weight: 600; padding: 0.4rem 1.25rem; border-radius: 2rem; display: inline-block; }
    .action-btn-group { display: flex; gap: 0.5rem; }
    .btn-ubah { border: 1px solid var(--line); background: white; color: var(--text-main); font-weight: 500; border-radius: 0.75rem; padding: 0.4rem 1.2rem; transition: 0.2s; }
    .btn-ubah:hover { background: #fdfcf9; }
    .btn-hapus { background-color: #c95b50; color: white; font-weight: 500; border-radius: 0.75rem; padding: 0.4rem 1.2rem; border: none; transition: 0.2s; }
    .btn-soft-filter { background: #fffdfa; border: 1px solid #dfd4c8; border-radius: 1rem; height: 44px; min-width: 180px; color: #5f6963; font-weight: 500; transition: 0.2s; padding: 0 1.25rem; text-align: left; }
    .btn-soft-filter:hover { background: #f7f2eb; }
    .btn-detail { border: 1px solid var(--primary-main); background: white; color: var(--primary-main); font-weight: 500; border-radius: 0.75rem; padding: 0.4rem 1.2rem; transition: 0.2s; text-decoration: none; display: inline-block; }
    .btn-detail:hover { background: #edf3ee; color: var(--primary-dark); }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 0.75rem;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 0.75rem;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 0.75rem;">
        <ul class="mb-0 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Tambah, ubah, dan hapus data fasilitas</h2>
        <p class="mb-0 text-secondary text-wrap" style="max-width: 650px;">
            Admin mengelola data fasilitas seperti ruangan kampus agar selalu akurat dan siap dipakai pada proses peminjaman.
        </p>
    </div>
</div>

<div class="card border-0 rounded-4 p-3 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
    <div class="d-flex flex-wrap gap-3 align-items-center">
        <button class="btn btn-main" data-bs-toggle="modal" data-bs-target="#tambahFasilitasModal">Tambah Fasilitas</button>
        <select class="btn-soft-filter" id="statusFilter" style="appearance: none; -webkit-appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 fill=%22%235f6963%22 viewBox=%220 0 16 16%22><path d=%22M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z%22/></svg>'); background-repeat: no-repeat; background-position: right 1.25rem center; background-size: 10px; cursor: pointer;">
            <option value="all">Status Keaktifan (Semua)</option>
            <option value="Aktif">Aktif</option>
            <option value="Tidak Aktif">Tidak Aktif</option>
            <option value="Perawatan">Perawatan</option>
        </select>
    </div>
</div>

<div class="mb-3 fw-semibold text-secondary">Tabel Fasilitas</div>
<div class="custom-table mb-4">
    <table class="table table-borderless mb-0">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kode Ruangan</th>
                <th>Foto</th>
                <th>Kategori</th>
                <th>Kapasitas</th>
                <th>PIC Ruangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ruangan as $r)
            <tr>
                <td class="fw-semibold">{{ $r->nama_ruangan }}</td>
                <td><code>{{ $r->kode_ruangan }}</code></td>
                <td>
                    @if($r->foto_ruangan)
                        <img src="{{ asset($r->foto_ruangan) }}" alt="{{ $r->nama_ruangan }}" class="img-fluid rounded-3" style="width: 150px; height: 150px; object-fit: cover; max-width: 100%;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 150px; height: 150px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">
                            Tidak ada foto
                        </div>
                    @endif
                </td>
                <td>Ruangan</td>
                <td>{{ $r->kapasitas ?? 'N/A' }} orang</td>
                <td class="fw-semibold">{{ $r->pic ? $r->pic->nama_lengkap : '-' }}</td>
                <td>
                    @if($r->status_ruangan == 'tersedia')
                        <span class="badge-aktif">Aktif</span>
                    @elseif($r->status_ruangan == 'maintenance')
                        <span class="badge-perawatan">Perawatan</span>
                    @else
                        <span class="badge-nonaktif">Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    <div class="action-btn-group">
                        <button class="btn btn-detail" data-bs-toggle="modal" data-bs-target="#detailFasilitasModal{{ $r->id_ruangan }}">Detail</button>
                        <button class="btn btn-ubah" data-bs-toggle="modal" data-bs-target="#editFasilitasModal{{ $r->id_ruangan }}">Ubah</button>
                        <form action="{{ route('admin.fasilitas.destroy', $r->id_ruangan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-hapus">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td class="fw-semibold">Aula Utama Polibatam</td>
                <td><code>kode-ruangan</code></td>
                <td>
                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">Tidak ada foto</div>
                </td>
                <td>Ruangan</td>
                <td>250 orang</td>
                <td class="fw-semibold">-</td>
                <td><span class="badge-aktif">Aktif</span></td>
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

<div class="card border-0 rounded-4 p-4 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important; max-width: 450px;">
    <div class="text-secondary small fw-semibold">Drawer Form Tambah/Ubah</div>
    <div class="text-muted small mt-1">Form biasanya memuat nama fasilitas, kategori, lokasi, kapasitas, status, dan PIC fasilitas.</div>
</div>


<div class="modal fade" id="tambahFasilitasModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background-color: #fffdfa;">
            <div class="modal-header border-0 pb-0" style="background-color: #f7f3eb; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title fw-bold text-main">Tambah Fasilitas Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.fasilitas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Nama Ruangan</label>
                        <input type="text" name="nama_ruangan" class="form-control" placeholder="cth: Aula Utama Polibatam" required style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Kode Ruangan <span class="text-danger">*</span></label>
                        <input type="text" name="kode_ruangan" class="form-control" placeholder="cth: RNG_AULA" required style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Gedung</label>
                        <input type="text" name="nama_gedung" class="form-control" placeholder="cth: Gedung Utama" style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Lantai</label>
                        <input type="text" name="lantai" class="form-control" placeholder="cth: 1" style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Kapasitas (Orang)</label>
                        <input type="number" name="kapasitas" class="form-control" placeholder="cth: 250" style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Status</label>
                        <select name="status_ruangan" class="form-select" style="border-radius:0.75rem;">
                            <option value="tersedia">Aktif</option>
                            <option value="maintenance">Perawatan</option>
                            <option value="tidak tersedia">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Deskripsi Ruangan</label>
                        <textarea name="deskripsi_ruangan" class="form-control" rows="3" style="border-radius:0.75rem;" placeholder="Masukkan deskripsi umum ruangan..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold d-flex justify-content-between align-items-center">
                            <span>Fasilitas Pendukung (AC, Proyektor, dll.)</span>
                            <button type="button" class="btn btn-sm btn-outline-success" id="add-item-btn" style="border-radius: 0.5rem; font-size: 0.8rem;">+ Tambah Item</button>
                        </label>
                        <div id="items-container" class="d-grid gap-2">
                            <div class="row g-2 align-items-center item-row">
                                <div class="col-md-5">
                                    <input type="text" name="fasilitas_items[0][nama_fasilitas]" class="form-control" placeholder="Nama Item (cth: AC)" style="border-radius:0.5rem; font-size: 0.9rem;">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="fasilitas_items[0][jumlah]" class="form-control" placeholder="Jml" min="1" value="1" style="border-radius:0.5rem; font-size: 0.9rem;">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="fasilitas_items[0][keterangan]" class="form-control" placeholder="Keterangan" style="border-radius:0.5rem; font-size: 0.9rem;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">PIC Ruangan <span class="text-danger">*</span></label>
                        <select name="pic_id" class="form-select" style="border-radius:0.75rem;" required>
                            <option value="">-- Pilih PIC --</option>
                            @foreach($pics as $p)
                                <option value="{{ $p->id_user }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Foto Ruangan</label>
                        <input type="file" name="foto_ruangan" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" style="border-radius:0.75rem;">
                        <div class="form-text text-muted" style="font-size: 0.8rem;">Hanya menerima JPG, JPEG, PNG, WEBP (maks. 10MB)</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn action-btn-outline" data-bs-dismiss="modal" style="border-radius:0.75rem;">Batal</button>
                    <button type="submit" class="btn btn-main" style="border-radius:0.75rem;">Simpan Fasilitas</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($ruangan as $r)
    <div class="modal fade" id="editFasilitasModal{{ $r->id_ruangan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg" style="background-color: #fffdfa;">
                <div class="modal-header border-0 pb-0" style="background-color: #f7f3eb; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                    <h5 class="modal-title fw-bold text-main">Ubah Fasilitas Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.fasilitas.update', $r->id_ruangan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Nama Ruangan</label>
                            <input type="text" name="nama_ruangan" class="form-control" value="{{ $r->nama_ruangan }}" required style="border-radius:0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Kode Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="kode_ruangan" class="form-control" value="{{ $r->kode_ruangan }}" required style="border-radius:0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Gedung</label>
                            <input type="text" name="nama_gedung" class="form-control" value="{{ $r->nama_gedung }}" style="border-radius:0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Lantai</label>
                            <input type="text" name="lantai" class="form-control" value="{{ $r->lantai }}" style="border-radius:0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Kapasitas (Orang)</label>
                            <input type="number" name="kapasitas" class="form-control" value="{{ $r->kapasitas }}" style="border-radius:0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Status</label>
                            <select name="status_ruangan" class="form-select" style="border-radius:0.75rem;">
                                <option value="tersedia" {{ $r->status_ruangan == 'tersedia' ? 'selected' : '' }}>Aktif</option>
                                <option value="maintenance" {{ $r->status_ruangan == 'maintenance' ? 'selected' : '' }}>Perawatan</option>
                                <option value="tidak tersedia" {{ $r->status_ruangan == 'tidak tersedia' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Deskripsi Ruangan</label>
                            <textarea name="deskripsi_ruangan" class="form-control" rows="3" style="border-radius:0.75rem;" placeholder="Masukkan deskripsi umum ruangan...">{{ $r->deskripsi_ruangan }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold d-flex justify-content-between align-items-center">
                                <span>Fasilitas Pendukung (AC, Proyektor, dll.)</span>
                                <button type="button" class="btn btn-sm btn-outline-success add-edit-item-btn" data-ruangan-id="{{ $r->id_ruangan }}" style="border-radius: 0.5rem; font-size: 0.8rem;">+ Tambah Item</button>
                            </label>
                            <div id="edit-items-container-{{ $r->id_ruangan }}" class="d-grid gap-2">
                                @foreach($r->fasilitas as $index => $f)
                                    <div class="row g-2 align-items-center edit-item-row">
                                        <input type="hidden" name="fasilitas_items[{{ $index }}][id_fasilitas]" value="{{ $f->id_fasilitas }}">
                                        <div class="col-md-5">
                                            <input type="text" name="fasilitas_items[{{ $index }}][nama_fasilitas]" class="form-control" value="{{ $f->nama_fasilitas }}" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="fasilitas_items[{{ $index }}][jumlah]" class="form-control" value="{{ $f->jumlah }}" min="1" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="fasilitas_items[{{ $index }}][keterangan]" class="form-control" value="{{ $f->keterangan }}" placeholder="Keterangan" style="border-radius:0.5rem; font-size: 0.9rem;">
                                        </div>
                                        <div class="col-md-1 d-flex justify-content-end">
                                            <button type="button" class="btn btn-sm btn-danger remove-item-btn" style="border-radius:0.5rem;">&times;</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">PIC Ruangan <span class="text-danger">*</span></label>
                            <select name="pic_id" class="form-select" style="border-radius:0.75rem;" required>
                                <option value="">-- Pilih PIC --</option>
                                @foreach($pics as $p)
                                    <option value="{{ $p->id_user }}" {{ $r->pic_id == $p->id_user ? 'selected' : '' }}>{{ $p->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Foto Ruangan</label>
                            @if($r->foto_ruangan)
                                <div class="mb-2">
                                    <img src="{{ asset($r->foto_ruangan) }}" alt="Foto saat ini" class="img-thumbnail" style="max-width: 150px; height: auto;">
                                </div>
                            @endif
                            <input type="file" name="foto_ruangan" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" style="border-radius:0.75rem;">
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

    <div class="modal fade" id="detailFasilitasModal{{ $r->id_ruangan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg" style="background-color: #fffdfa;">
                <div class="modal-header border-0 pb-0" style="background-color: #f7f3eb; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                    <h5 class="modal-title fw-bold text-main">Detail Fasilitas Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-5 mb-3 text-center">
                            @if($r->foto_ruangan)
                                <img src="{{ asset($r->foto_ruangan) }}" alt="{{ $r->nama_ruangan }}" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 250px; object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-4 w-100" style="height: 200px; border: 2px dashed var(--line); font-size: 0.9rem;">
                                    Tidak ada foto ruangan
                                </div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            <h4 class="fw-bold text-main mb-1">{{ $r->nama_ruangan }}</h4>
                            <p class="text-muted mb-3">{{ $r->nama_gedung }} - Lantai {{ $r->lantai }}</p>
                            
                            <div class="mb-3">
                                <span class="text-secondary small fw-semibold d-block">Kategori</span>
                                <span class="fw-semibold text-main">Ruangan</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-secondary small fw-semibold d-block">Kapasitas</span>
                                <span class="fw-semibold text-main">{{ $r->kapasitas ?? 'N/A' }} orang</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-secondary small fw-semibold d-block">PIC Ruangan</span>
                                <span class="fw-semibold text-main">{{ $r->pic ? $r->pic->nama_lengkap : '-' }}</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-secondary small fw-semibold d-block mb-1">Status Keaktifan</span>
                                @if($r->status_ruangan == 'tersedia')
                                    <span class="badge-aktif">Aktif</span>
                                @elseif($r->status_ruangan == 'maintenance')
                                    <span class="badge-perawatan">Perawatan</span>
                                @else
                                    <span class="badge-nonaktif">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <hr style="border-top: 1px solid var(--line); margin: 1.5rem 0;">
                    
                    <div class="mb-4">
                        <h6 class="fw-bold text-secondary mb-2">Keterangan / Deskripsi Ruangan</h6>
                        <p class="text-main mb-0" style="white-space: pre-wrap;">{{ $r->deskripsi_ruangan ?? 'Tidak ada deskripsi tambahan.' }}</p>
                    </div>
                    
                    <div>
                        <h6 class="fw-bold text-secondary mb-3">Fasilitas Pendukung (Detail)</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($r->fasilitas as $item)
                                <span class="badge px-3 py-2 text-start text-wrap shadow-none" style="background-color: #eef4ee; color: #496454; border: 1px solid #dfe7dc; border-radius: 0.75rem; font-size: 0.85rem; font-weight: normal;">
                                    <strong class="d-block">{{ $item->nama_fasilitas }}</strong>
                                    <span class="d-block text-muted mt-1">{{ $item->jumlah }} unit @if($item->keterangan) · {{ $item->keterangan }} @endif</span>
                                </span>
                            @empty
                                <span class="text-muted italic small">Tidak ada fasilitas pendukung tambahan.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-main" data-bs-dismiss="modal" style="border-radius:0.75rem; min-width: 100px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
/* ... Isi JavaScript Anda tetap sama persis (dipertahankan semuanya) ... */
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('adminSearchInput');
    const searchButton = document.getElementById('adminSearchButton');
    const statusFilter = document.getElementById('statusFilter');
    
    function performSearch() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const selectedStatus = statusFilter ? statusFilter.value.toLowerCase() : 'all';
        const tbody = document.querySelector('.custom-table tbody');
        if (!tbody) return;
        const rows = tbody.querySelectorAll('tr');
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            if (row.id === 'noResultsRow') { row.remove(); return; }
            if (row.cells.length === 1 && row.innerText.includes('Belum ada')) { return; }
            
            const name = row.cells[0] ? row.cells[0].innerText.toLowerCase() : '';
            const code = row.cells[1] ? row.cells[1].innerText.toLowerCase() : '';
            const category = row.cells[3] ? row.cells[3].innerText.toLowerCase() : '';
            const capacity = row.cells[4] ? row.cells[4].innerText.toLowerCase() : '';
            const status = row.cells[5] ? row.cells[5].innerText.toLowerCase().trim() : '';
            
            const matchesSearch = name.includes(query) || code.includes(query) || category.includes(query) || capacity.includes(query) || status.includes(query);
            const matchesStatus = (selectedStatus === 'all') || (status === selectedStatus);
            
            if (matchesSearch && matchesStatus) {
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
                tr.innerHTML = '<td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">Tidak ada data fasilitas yang cocok dengan kriteria pencarian/filter.</td>';
                tbody.appendChild(tr);
            }
        } else {
            if (existingNoResults) { existingNoResults.remove(); }
        }
    }
    
    if (searchInput) { searchInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); performSearch(); } }); }
    if (searchButton) { searchButton.addEventListener('click', function(e) { e.preventDefault(); performSearch(); }); }
    if (statusFilter) { statusFilter.addEventListener('change', performSearch); }

    const addItemBtn = document.getElementById('add-item-btn');
    const itemsContainer = document.getElementById('items-container');
    let itemIndex = 1000;
    if (addItemBtn && itemsContainer) {
        addItemBtn.addEventListener('click', function() {
            const index = itemIndex++;
            const div = document.createElement('div');
            div.className = 'row g-2 align-items-center item-row';
            div.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="fasilitas_items[${index}][nama_fasilitas]" class="form-control" placeholder="Nama Item" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="fasilitas_items[${index}][jumlah]" class="form-control" placeholder="Jml" min="1" value="1" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="fasilitas_items[${index}][keterangan]" class="form-control" placeholder="Keterangan" style="border-radius:0.5rem; font-size: 0.9rem;">
                </div>
                <div class="col-md-1 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-danger remove-item-btn" style="border-radius:0.5rem;">&times;</button>
                </div>
            `;
            itemsContainer.appendChild(div);
            div.querySelector('.remove-item-btn').addEventListener('click', function() { div.remove(); });
        });
    }

    let editItemIndex = 1000;
    document.querySelectorAll('.add-edit-item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const ruanganId = this.getAttribute('data-ruangan-id');
            const container = document.getElementById(`edit-items-container-${ruanganId}`);
            if (!container) return;
            const index = editItemIndex++;
            const div = document.createElement('div');
            div.className = 'row g-2 align-items-center edit-item-row';
            div.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="fasilitas_items[${index}][nama_fasilitas]" class="form-control" placeholder="Nama Item" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="fasilitas_items[${index}][jumlah]" class="form-control" placeholder="Jml" min="1" value="1" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="fasilitas_items[${index}][keterangan]" class="form-control" placeholder="Keterangan" style="border-radius:0.5rem; font-size: 0.9rem;">
                </div>
                <div class="col-md-1 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-danger remove-item-btn" style="border-radius:0.5rem;">&times;</button>
                </div>
            `;
            container.appendChild(div);
            div.querySelector('.remove-item-btn').addEventListener('click', function() { div.remove(); });
        });
    });

    document.querySelectorAll('.edit-item-row .remove-item-btn').forEach(btn => {
        btn.addEventListener('click', function() { this.closest('.edit-item-row').remove(); });
    });
});
</script>
@endsection