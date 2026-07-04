@extends('layout.app_tailwind')




@section('content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .detail-card, .status-side-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
    }
    .info-row {
        margin-bottom: 0.85rem;
    }
    .info-label {
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 0.2rem;
    }
    .info-value {
        color: var(--text-main);
        font-weight: 600;
        font-size: 0.95rem;
    }
    .status-badge-ready {
        background-color: #e2f0d9;
        color: #385723;
        font-weight: 600;
        padding: 0.6rem;
        border-radius: 0.75rem;
        text-align: center;
        display: block;
        width: 100%;
        border: 1px solid #c5e1b5;
        font-size: 0.95rem;
    }
    .btn-confirm-return {
        background-color: var(--primary-main);
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-confirm-return:hover {
        background-color: var(--primary-dark);
    }
    .btn-reject-return {
        background-color: #c95b50;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-reject-return:hover {
        background-color: #b34e44;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="p-6 lg:p-8">
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Konfirmasi pengembalian fasilitas</h2>
        <p class="text-[#7d8781] max-w-2xl">
            PIC memeriksa kondisi ruangan dan perlengkapan pasca-pakai, lalu mengonfirmasi status selesai.
        </p>
    </div>
</div>

@php
    $selectedId = request()->query('selected_id');
    $selectedItem = null;
@endphp

@foreach($peminjaman as $item)
    @if(!$selectedId && $loop->first)
        @php $selectedId = $item->id_peminjaman; @endphp
    @endif
    @if($item->id_peminjaman == $selectedId)
        @php $selectedItem = $item; @endphp
    @endif
@endforeach

<style>
    .list-item-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.25rem;
        padding: 1.25rem;
        cursor: pointer;
        transition: 0.2s;
        margin-bottom: 1rem;
        text-align: left;
    }
    .list-item-card.active {
        background-color: #f7f3eb;
        border-left: 5px solid var(--primary-main);
    }
    .badge-verifikasi-soft {
        background-color: #fcf1d3;
        color: #7d6006;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 2rem;
    }
</style>

<div class="row g-4 mb-4">
    <!-- Left Column: Antrian Permohonan Masuk -->
    <div class="col-lg-6">
        <div class="mb-3 fw-semibold text-secondary text-start">Daftar Antrean Pengembalian</div>
        <div class="d-grid gap-2">
            @forelse($peminjaman as $item)
                <div class="list-item-card {{ $item->id_peminjaman == $selectedId ? 'active' : '' }}" onclick="window.location.href='?selected_id={{ $item->id_peminjaman }}'">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold text-main">SBUM-2026-{{ str_pad($item->id_peminjaman, 4, '0', STR_PAD_LEFT) }} · {{ $item->nama_kegiatan }}</div>
                            <div class="text-secondary small mt-1">
                                Peminjam: {{ $item->user->nama_lengkap ?? 'Mahasiswa' }} · 
                                {{ $item->ruangan->isNotEmpty() ? $item->ruangan->first()->nama_ruangan : ($item->barang->isNotEmpty() ? $item->barang->first()->nama_barang : 'Fasilitas') }}
                            </div>
                            <div class="text-muted small mt-2">
                                @php
                                    $returnDate = $item->jenis_peminjaman === 'ruangan'
                                        ? ($item->pengembalianRuangan->tanggal_pengembalian ?? null)
                                        : ($item->pengembalianBarang->tanggal ?? null);
                                    $returnTime = $item->jenis_peminjaman === 'ruangan'
                                        ? ($item->pengembalianRuangan->jam_selesai_aktual ?? null)
                                        : ($item->pengembalianBarang->jam_selesai_aktual ?? null);
                                @endphp
                                Pengembalian: {{ $returnDate ? \Carbon\Carbon::parse($returnDate)->translatedFormat('d M Y') : '-' }} · {{ $returnTime ? substr($returnTime, 0, 5) : '12.00' }}
                            </div>
                        </div>
                        <span class="inline-block bg-[#fcf1d3] text-[#7d6006] text-[13px] font-semibold px-4 py-1.5 rounded-full">Menunggu Verifikasi</span>
                    </div>
                </div>
            @empty
                <div class="card p-5 text-center text-secondary border-0" style="background:#fffdfa; border-radius:1.5rem; border: 1px solid var(--line) !important;">
                    Tidak ada antrean pengembalian.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Details & Form Pengembalian -->
    <div class="col-lg-6">
        @if($selectedItem)
            @php
                $fotoPath = $selectedItem->jenis_peminjaman === 'ruangan' 
                    ? ($selectedItem->pengembalianRuangan->foto_kondisi ?? null)
                    : ($selectedItem->pengembalianBarang->foto_kondisi ?? null);
                $dokumenPath = $selectedItem->jenis_peminjaman === 'ruangan' 
                    ? ($selectedItem->pengembalianRuangan->dokumen_administrasi ?? null)
                    : ($selectedItem->pengembalianBarang->dokumen_administrasi ?? null);
                $returnDate = $selectedItem->jenis_peminjaman === 'ruangan'
                    ? ($selectedItem->pengembalianRuangan->tanggal_pengembalian ?? null)
                    : ($selectedItem->pengembalianBarang->tanggal ?? null);
                $returnTime = $selectedItem->jenis_peminjaman === 'ruangan'
                    ? ($selectedItem->pengembalianRuangan->jam_selesai_aktual ?? null)
                    : ($selectedItem->pengembalianBarang->jam_selesai_aktual ?? null);
                $returnCatatan = $selectedItem->jenis_peminjaman === 'ruangan'
                    ? ($selectedItem->pengembalianRuangan->catatan ?? null)
                    : ($selectedItem->pengembalianBarang->catatan ?? null);
            @endphp
            <form action="{{ route('pic.pengembalian.store') }}" method="POST" id="pengembalianForm">
                @csrf
                <input type="hidden" name="peminjaman_id" value="{{ $selectedItem->id_peminjaman }}">
                <input type="hidden" name="status_pengembalian" id="pengembalianField" value="selesai">

                <div class="mb-3 fw-semibold text-secondary text-start">Detail Verifikasi Pengembalian</div>
                <div class="detail-card mb-4" style="background: #fffdfa; border: 1px solid var(--line); border-radius: 1.5rem; padding: 1.5rem;">
                    <div class="row g-3 mb-4 text-start">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">ID Peminjaman</div>
                                <div class="info-value">SBUM-2026-{{ str_pad($selectedItem->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Fasilitas</div>
                                <div class="info-value">
                                    @if($selectedItem->ruangan->isNotEmpty())
                                        {{ $selectedItem->ruangan->first()->nama_ruangan }}
                                    @elseif($selectedItem->barang->isNotEmpty())
                                        {{ $selectedItem->barang->first()->nama_barang }}
                                    @else
                                        Fasilitas
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($selectedItem->jenis_peminjaman === 'barang')
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Jumlah Barang</div>
                                <div class="info-value">
                                    {{ count($selectedItem->barang) > 0 ? ($selectedItem->barang->first()->pivot->jumlah ?? 1) : 1 }} Buah
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Tanggal Pengembalian</div>
                                <div class="info-value">{{ $returnDate ? \Carbon\Carbon::parse($returnDate)->translatedFormat('d M Y') : '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Jam Selesai Aktual</div>
                                <div class="info-value">{{ $returnTime ? substr($returnTime, 0, 5) : '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="info-row">
                                <div class="info-label">Catatan Pengembalian Mahasiswa</div>
                                <div class="info-value">{{ $returnCatatan ?: '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4" style="border-top: 1px solid var(--line);">

                    <div class="mb-3 fw-semibold text-secondary text-start">Bukti Fisik Pasca-Pakai</div>
                    <div class="mb-3 text-start">
                        <div class="info-label mb-2">Foto Kondisi Fasilitas</div>
                        @if($fotoPath)
                            @php
                                $fotoUrl = asset($fotoPath);
                            @endphp
                            <div class="mb-3">
                                <img src="{{ $fotoUrl }}" alt="Bukti Foto Kondisi" class="rounded-4 border" style="width: 120px; height: 120px; object-fit: cover; cursor: pointer;" onclick="zoomImage(this)">
                            </div>
                        @else
                            <div class="text-secondary small mb-3" style="color: #6c757d;">Bukti tidak tersedia</div>
                        @endif

                        <div class="info-label mb-2">Dokumen Administrasi</div>
                        @if($dokumenPath)
                            @php
                            $dokumenUrl = asset($dokumenPath);
                            @endphp
                            <div>
                                <a href="{{ $dokumenUrl }}" download class="btn btn-upload-photo" style="background-color: var(--soft-bg); border-color: var(--line); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 0.75rem; text-decoration: none; padding: 0.5rem 1.5rem; width: auto; font-size: 0.9rem;">
                                    <i class="bi bi-file-earmark-pdf text-danger"></i> Unduh Dokumen PDF
                                </a>
                            </div>
                        @else
                            <div class="text-secondary small" style="color: #6c757d;">Bukti tidak tersedia</div>
                        @endif
                    </div>

                    <div class="mt-4 text-start">
                        <label class="form-label text-secondary small fw-semibold">Catatan Verifikasi PIC</label>
                        <textarea name="catatan" class="form-control" rows="3" style="border-radius: 0.75rem; border-color: #dfd4c8; font-size: 0.9rem;" placeholder="Masukkan catatan hasil verifikasi pengembalian..."></textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="button" onclick="submitPengembalian('selesai')" class="btn btn-confirm-return">Terima Pengembalian</button>
                        <button type="button" onclick="submitPengembalian('ditolak')" class="btn btn-reject-return">Tolak Pengembalian</button>
                    </div>
                </div>
            </form>
        @else
            <div class="card p-5 text-center text-secondary border-0" style="background:#fffdfa; border-radius:1.5rem; border: 1px solid var(--line) !important;">
                Pilih antrean pengembalian di kolom kiri untuk melakukan verifikasi.
            </div>
        @endif
    </div>
</div>

<!-- Image Zoom Modal -->
<div class="modal fade" id="zoomPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: transparent; border: none;">
            <div class="modal-body text-center p-0 position-relative">
                <img id="zoomedImage" src="" class="img-fluid rounded-4 border shadow-lg" style="max-height: 85vh; object-fit: contain;">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>

<script>
    function submitPengembalian(status) {
        document.getElementById('pengembalianField').value = status;
        document.getElementById('pengembalianForm').submit();
    }

    function zoomImage(element) {
        const zoomedImage = document.getElementById('zoomedImage');
        zoomedImage.src = element.src;
        const modal = new bootstrap.Modal(document.getElementById('zoomPhotoModal'));
        modal.show();
    }
</script>
@endsection
