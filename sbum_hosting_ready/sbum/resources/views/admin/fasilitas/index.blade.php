@extends('layout.app_tailwind')




@section('content')


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

<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div>
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Tambah, ubah, dan hapus data fasilitas</h2>
        <p class="text-[#7d8781] max-w-2xl mb-0">
            Admin mengelola data fasilitas seperti ruangan kampus agar selalu akurat dan siap dipakai pada proses peminjaman.
        </p>
    </div>
</div>

<div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-4 mb-6">
    <div class="flex flex-wrap gap-4 items-center">
        <button class="bg-[#466454] hover:bg-[#395244] text-white px-5 py-2.5 rounded-[14px] font-semibold transition" data-bs-toggle="modal" data-bs-target="#tambahFasilitasModal">Tambah Fasilitas</button>
        <select class="bg-[#fffdfa] border border-[#dfd4c8] rounded-[14px] px-5 py-2.5 min-w-[200px] text-[#5f6963] font-medium outline-none focus:border-[#466454]" id="statusFilter">
            <option value="all">Status Keaktifan (Semua)</option>
            <option value="Aktif">Aktif</option>
            <option value="Tidak Aktif">Tidak Aktif</option>
            <option value="Perawatan">Perawatan</option>
        </select>
    </div>
</div>

<div class="font-semibold text-[#7d8781] mb-4">Tabel Fasilitas</div>
<div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] overflow-x-auto mb-6">
    <table class="w-full text-left whitespace-nowrap">
        <thead><tr class="bg-[#f7f3eb] text-[#33403b]"><th class="px-6 py-4 font-semibold text-sm">Nama</th><th class="px-6 py-4 font-semibold text-sm">Kode Ruangan</th><th class="px-6 py-4 font-semibold text-sm">Gedung</th><th class="px-6 py-4 font-semibold text-sm">Foto</th><th class="px-6 py-4 font-semibold text-sm">Kategori</th><th class="px-6 py-4 font-semibold text-sm">Kapasitas</th><th class="px-6 py-4 font-semibold text-sm">PIC Ruangan</th><th class="px-6 py-4 font-semibold text-sm">Status</th><th class="px-6 py-4 font-semibold text-sm rounded-tr-none">Aksi</th></tr></thead>
        <tbody>
            @forelse($ruangan as $r)
            <tr>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">{{ $r->nama_ruangan }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]"><code>{{ $r->kode_ruangan }}</code></td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $r->nama_gedung ?? '-' }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    @if($r->foto_ruangan)
                        <img src="{{ asset($r->foto_ruangan) }}" alt="{{ $r->nama_ruangan }}" class="w-[80px] h-[80px] object-cover rounded-xl">
                    @else
                        <div class="w-[80px] h-[80px] rounded-xl flex items-center justify-center bg-[#f7f3eb] text-[#7d8781] text-xs border border-dashed border-[#e6ddd2]">
                            Tidak ada foto
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">Ruangan</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $r->kapasitas ?? 'N/A' }} orang</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">{{ $r->pic ? $r->pic->nama_lengkap : '-' }}</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    @if($r->status_ruangan == 'tersedia')
                        <span class="bg-[#e2f0d9] text-[#385723] px-4 py-1.5 rounded-full text-xs font-semibold">Aktif</span>
                    @elseif($r->status_ruangan == 'maintenance')
                        <span class="bg-[#fcf1d3] text-[#7d6006] px-4 py-1.5 rounded-full text-xs font-semibold">Perawatan</span>
                    @else
                        <span class="bg-[#fcebeb] text-[#8b3c3c] px-4 py-1.5 rounded-full text-xs font-semibold">Tidak Aktif</span>
                    @endif
                </td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <div class="flex gap-2">
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
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">Aula Utama Polibatam</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]"><code>kode-ruangan</code></td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">-</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded-3" style="width: 100px; height: 100px; max-width: 100%; border: 1px dashed var(--line); font-size: 0.8rem;">Tidak ada foto</div>
                </td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">Ruangan</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">250 orang</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] font-semibold text-[#33403b]">-</td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]"><span class="bg-[#e2f0d9] text-[#385723] px-4 py-1.5 rounded-full text-xs font-semibold">Aktif</span></td>
                <td class="px-6 py-4 border-b border-[#e6ddd2] text-[#54615b]">
                    <div class="flex gap-2">
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
        <div class="modal-content bg-[#fffdfa] border-0 rounded-2xl shadow-xl">
            <div class="modal-header bg-[#f7f3eb] border-0 rounded-t-2xl pb-4">
                <h5 class="modal-title font-bold text-[#466454] text-lg">Tambah Fasilitas Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.fasilitas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Nama Ruangan</label>
                        <input type="text" name="nama_ruangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="cth: Aula Utama Polibatam" required >
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Kode Ruangan <span class="text-danger">*</span></label>
                        <input type="text" name="kode_ruangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="cth: RNG_AULA" required >
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Gedung</label>
                        <select name="nama_gedung" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" >
                            <option value="">-- Pilih Gedung --</option>
                            <option value="Gedung Utama">Gedung Utama</option>
                            <option value="Gedung Tower A dan Tower B">Gedung Tower A dan Tower B</option>
                            <option value="Gedung Technopreneur">Gedung Technopreneur</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Lantai</label>
                        <input type="text" name="lantai" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="cth: 1" >
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Kapasitas (Orang)</label>
                        <input type="number" name="kapasitas" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="cth: 250" >
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Status</label>
                        <select name="status_ruangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" >
                            <option value="tersedia">Aktif</option>
                            <option value="maintenance">Perawatan</option>
                            <option value="tidak tersedia">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Deskripsi Ruangan</label>
                        <textarea name="deskripsi_ruangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" rows="3"  placeholder="Masukkan deskripsi umum ruangan..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold d-flex justify-content-between align-items-center">
                            <span>Fasilitas Pendukung (AC, Proyektor, dll.)</span>
                            <button type="button" class="btn btn-sm btn-outline-success" id="add-item-btn" style="border-radius: 0.5rem; font-size: 0.8rem;">+ Tambah Item</button>
                        </label>
                        <div id="items-container" class="d-grid gap-2">
                            <div class="row g-2 align-items-center item-row">
                                <div class="col-md-5">
                                    <input type="text" name="fasilitas_items[0][nama_fasilitas]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="Nama Item (cth: AC)" style="border-radius:0.5rem; font-size: 0.9rem;">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="fasilitas_items[0][jumlah]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="Jml" min="1" value="1" style="border-radius:0.5rem; font-size: 0.9rem;">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="fasilitas_items[0][keterangan]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="Keterangan" style="border-radius:0.5rem; font-size: 0.9rem;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">PIC Ruangan <span class="text-danger">*</span></label>
                        <select name="pic_id" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]"  required>
                            <option value="">-- Pilih PIC --</option>
                            @foreach($pics as $p)
                                <option value="{{ $p->id_user }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Foto Ruangan</label>
                        <input type="file" name="foto_ruangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" accept="image/png, image/jpeg, image/jpg, image/webp" >
                        <div class="form-text text-muted" style="font-size: 0.8rem;">Hanya menerima JPG, JPEG, PNG, WEBP (maks. 10MB)</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="border border-[#e6ddd2] text-[#7d8781] px-5 py-2.5 rounded-xl font-semibold hover:bg-[#f5f2ec] transition" data-bs-dismiss="modal" >Batal</button>
                    <button type="submit" class="bg-[#466454] text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-[#395244] transition" >Simpan Fasilitas</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($ruangan as $r)
    <div class="modal fade" id="editFasilitasModal{{ $r->id_ruangan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-[#fffdfa] border-0 rounded-2xl shadow-xl">
                <div class="modal-header bg-[#f7f3eb] border-0 rounded-t-2xl pb-4">
                    <h5 class="modal-title font-bold text-[#466454] text-lg">Ubah Fasilitas Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.fasilitas.update', $r->id_ruangan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Nama Ruangan</label>
                            <input type="text" name="nama_ruangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $r->nama_ruangan }}" required >
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Kode Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="kode_ruangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $r->kode_ruangan }}" required >
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Gedung</label>
                            <select name="nama_gedung" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" >
                                <option value="" {{ empty($r->nama_gedung) ? 'selected' : '' }}>-- Pilih Gedung --</option>
                                <option value="Gedung Utama" {{ $r->nama_gedung == 'Gedung Utama' ? 'selected' : '' }}>Gedung Utama</option>
                                <option value="Gedung Tower A dan Tower B" {{ $r->nama_gedung == 'Gedung Tower A dan Tower B' ? 'selected' : '' }}>Gedung Tower A dan Tower B</option>
                                <option value="Gedung Technopreneur" {{ $r->nama_gedung == 'Gedung Technopreneur' ? 'selected' : '' }}>Gedung Technopreneur</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Lantai</label>
                            <input type="text" name="lantai" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $r->lantai }}" >
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Kapasitas (Orang)</label>
                            <input type="number" name="kapasitas" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $r->kapasitas }}" >
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Status</label>
                            <select name="status_ruangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" >
                                <option value="tersedia" {{ $r->status_ruangan == 'tersedia' ? 'selected' : '' }}>Aktif</option>
                                <option value="maintenance" {{ $r->status_ruangan == 'maintenance' ? 'selected' : '' }}>Perawatan</option>
                                <option value="tidak tersedia" {{ $r->status_ruangan == 'tidak tersedia' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Deskripsi Ruangan</label>
                            <textarea name="deskripsi_ruangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" rows="3"  placeholder="Masukkan deskripsi umum ruangan...">{{ $r->deskripsi_ruangan }}</textarea>
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
                                            <input type="text" name="fasilitas_items[{{ $index }}][nama_fasilitas]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $f->nama_fasilitas }}" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="fasilitas_items[{{ $index }}][jumlah]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $f->jumlah }}" min="1" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="fasilitas_items[{{ $index }}][keterangan]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $f->keterangan }}" placeholder="Keterangan" style="border-radius:0.5rem; font-size: 0.9rem;">
                                        </div>
                                        <div class="col-md-1 d-flex justify-content-end">
                                            <button type="button" class="btn btn-sm btn-danger remove-item-btn" style="border-radius:0.5rem;">&times;</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">PIC Ruangan <span class="text-danger">*</span></label>
                            <select name="pic_id" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]"  required>
                                <option value="">-- Pilih PIC --</option>
                                @foreach($pics as $p)
                                    <option value="{{ $p->id_user }}" {{ $r->pic_id == $p->id_user ? 'selected' : '' }}>{{ $p->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Foto Ruangan</label>
                            @if($r->foto_ruangan)
                                <div class="mb-2">
                                    <img src="{{ asset($r->foto_ruangan) }}" alt="Foto saat ini" class="img-thumbnail" style="max-width: 150px; height: auto;">
                                </div>
                            @endif
                            <input type="file" name="foto_ruangan" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" accept="image/png, image/jpeg, image/jpg, image/webp" >
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

    <div class="modal fade" id="detailFasilitasModal{{ $r->id_ruangan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-[#fffdfa] border-0 rounded-2xl shadow-xl">
                <div class="modal-header bg-[#f7f3eb] border-0 rounded-t-2xl pb-4">
                    <h5 class="modal-title font-bold text-[#466454] text-lg">Detail Fasilitas Ruangan</h5>
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
                                    <span class="bg-[#e2f0d9] text-[#385723] px-4 py-1.5 rounded-full text-xs font-semibold">Aktif</span>
                                @elseif($r->status_ruangan == 'maintenance')
                                    <span class="bg-[#fcf1d3] text-[#7d6006] px-4 py-1.5 rounded-full text-xs font-semibold">Perawatan</span>
                                @else
                                    <span class="bg-[#fcebeb] text-[#8b3c3c] px-4 py-1.5 rounded-full text-xs font-semibold">Tidak Aktif</span>
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
                <div class="modal-footer border-0">
                    <button type="button" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block" data-bs-dismiss="modal" style="border-radius:0.75rem; min-width: 100px;">Tutup</button>
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
                    <input type="text" name="fasilitas_items[${index}][nama_fasilitas]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="Nama Item" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="fasilitas_items[${index}][jumlah]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="Jml" min="1" value="1" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="fasilitas_items[${index}][keterangan]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="Keterangan" style="border-radius:0.5rem; font-size: 0.9rem;">
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
                    <input type="text" name="fasilitas_items[${index}][nama_fasilitas]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="Nama Item" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="fasilitas_items[${index}][jumlah]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="Jml" min="1" value="1" style="border-radius:0.5rem; font-size: 0.9rem;" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="fasilitas_items[${index}][keterangan]" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" placeholder="Keterangan" style="border-radius:0.5rem; font-size: 0.9rem;">
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
