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
    .checklist-item {
        background-color: #f7f6f2;
        border: 1px solid #dfd4c8;
        border-radius: 0.75rem;
        padding: 0.85rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
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
    .btn-upload-photo {
        background-color: #f7f6f2;
        border: 1px solid #dfd4c8;
        border-radius: 0.75rem;
        height: 48px;
        font-weight: 500;
        color: var(--text-main);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        transition: 0.2s;
    }
    .btn-upload-photo:hover {
        background-color: #f0e9df;
    }
    .btn-confirm-ready {
        background-color: #557b58;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-confirm-ready:hover {
        background-color: #456447;
    }
    .btn-report-issue {
        background-color: #c95b50;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-report-issue:hover {
        background-color: #b34e44;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="p-6 lg:p-8">
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Pastikan fasilitas siap sebelum digunakan</h2>
        <p class="text-[#7d8781] max-w-2xl">
            PIC memeriksa kondisi ruangan dan perlengkapan, lalu menyimpan status siap atau kendala.
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
        <div class="mb-3 fw-semibold text-secondary text-start">Daftar Permohonan Masuk</div>
        <div class="d-grid gap-2">
            @forelse($peminjaman as $item)
                <div class="list-item-card {{ $item->id_peminjaman == $selectedId ? 'active' : '' }}" onclick="window.location.href='?selected_id={{ $item->id_peminjaman }}'">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold text-main">SBUM-2026-{{ str_pad($item->id_peminjaman, 4, '0', STR_PAD_LEFT) }} · {{ $item->nama_kegiatan }}</div>
                            <div class="text-secondary small mt-1">
                                Peminjam: {{ $item->user->nama_lengkap ?? 'Peminjam' }} · 
                                {{ $item->ruangan->isNotEmpty() ? $item->ruangan->first()->nama_ruangan : ($item->barang->isNotEmpty() ? $item->barang->first()->nama_barang : 'Fasilitas') }}
                            </div>
                            <div class="text-muted small mt-2">
                                {{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} · {{ $item->jam_mulai ? str_replace(':', '.', substr($item->jam_mulai, 0, 5)) : '08.00' }} - {{ $item->jam_selesai ? str_replace(':', '.', substr($item->jam_selesai, 0, 5)) : '12.00' }}
                            </div>
                        </div>
                        <span class="inline-block bg-[#fcf1d3] text-[#7d6006] text-[13px] font-semibold px-4 py-1.5 rounded-full">Menunggu PIC</span>
                    </div>
                </div>
            @empty
                <div class="card p-5 text-center text-secondary border-0" style="background:#fffdfa; border-radius:1.5rem; border: 1px solid var(--line) !important;">
                    Tidak ada permohonan masuk.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Details & Form Kesiapan -->
    <div class="col-lg-6">
        @if($selectedItem)
            <form action="{{ route('pic.kesiapan.store') }}" method="POST" id="kesiapanForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="peminjaman_id" value="{{ $selectedItem->id_peminjaman }}">
                <input type="hidden" name="status_kesiapan" id="kesiapanField" value="siap">

                <div class="mb-3 fw-semibold text-secondary text-start">Detail Kesiapan Fasilitas</div>
                <div class="detail-card mb-4" style="background: #fffdfa; border: 1px solid var(--line); border-radius: 1.5rem; padding: 1.5rem;">
                    <div class="row g-3 mb-4 text-start">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Kegiatan</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->nama_kegiatan }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Fasilitas</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">
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
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Waktu</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->tanggal_pengajuan ? \Carbon\Carbon::parse($selectedItem->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} · {{ $selectedItem->jam_mulai ? str_replace(':', '.', substr($selectedItem->jam_mulai, 0, 5)) : '08.00' }} - {{ $selectedItem->jam_selesai ? str_replace(':', '.', substr($selectedItem->jam_selesai, 0, 5)) : '12.00' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Peminjam</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->user->nama_lengkap ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Dosen Penanggung Jawab</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->dosen->nama_lengkap ?? '-' }}</div>
                            </div>
                        </div>
                        @if($selectedItem->ruangan->count() > 0)
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Jumlah Peserta</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->jumlah_peserta ?? '0' }} Orang</div>
                            </div>
                        </div>
                        @elseif($selectedItem->barang->count() > 0)
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Jumlah Barang</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->barang->first()->pivot->jumlah ?? 1 }} Buah</div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-12">
                            <div class="info-row">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Deskripsi Acara</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->keterangan ?: 'Tidak ada keterangan tambahan.' }}</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4" style="border-top: 1px solid var(--line);">

                    <div class="mb-3 fw-semibold text-secondary text-start">Checklist Kesiapan</div>
                    @if($selectedItem->ruangan->count() > 0)
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check1" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check1">Ruangan bersih dan siap pakai</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check2" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check2">Sound system dan listrik normal</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check3" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check3">Kursi, meja, dan akses ruangan lengkap</label>
                    </div>
                    @elseif($selectedItem->barang->count() > 0)
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check1" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check1">Kondisi fisik barang baik dan tidak cacat</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check2" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check2">Fungsionalitas barang berjalan dengan normal</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check3" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check3">Aksesoris / kelengkapan barang sudah lengkap (jika ada)</label>
                    </div>
                    @endif

                    <div class="mt-4 text-start" id="rejectNoteContainer" style="display: none;">
                        <label class="form-label text-danger small fw-bold">Detail Kendala / Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan" id="catatanField" class="form-control border-danger" rows="3" style="border-radius: 0.75rem; font-size: 0.9rem;" placeholder="Wajib: Deskripsikan kendala atau alasan penolakan..."></textarea>
                    </div>

                    <div class="mt-3 text-start">
                        <label class="form-label text-secondary small fw-semibold">Upload Bukti Kondisi Ruangan (Opsional)</label>
                        <input type="file" name="foto_kondisi" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" style="border-radius: 0.75rem;">
                    </div>

                    <div class="d-grid gap-2 mt-4" id="actionButtonsContainer">
                        <button type="button" onclick="submitKesiapan('siap')" class="btn btn-confirm-ready">Konfirmasi Siap</button>
                        <button type="button" onclick="showRejectNote()" class="btn btn-report-issue">Laporkan Kendala / Tolak</button>
                    </div>
                    <div class="d-grid gap-2 mt-3" id="confirmRejectContainer" style="display: none;">
                        <button type="button" onclick="submitKesiapan('kendala')" class="btn btn-report-issue">Konfirmasi Laporan Kendala</button>
                        <button type="button" onclick="cancelReject()" class="btn btn-light" style="border-radius:0.75rem; color:#7d8781;">Batal</button>
                    </div>
                </div>
            </form>
        @else
            <div class="card p-5 text-center text-secondary border-0" style="background:#fffdfa; border-radius:1.5rem; border: 1px solid var(--line) !important;">
                Pilih permohonan di antrean untuk melakukan konfirmasi kesiapan.
            </div>
        @endif
    </div>
</div>

<script>
    function showRejectNote() {
        document.getElementById('rejectNoteContainer').style.display = 'block';
        document.getElementById('actionButtonsContainer').style.display = 'none';
        document.getElementById('confirmRejectContainer').style.display = 'block';
        document.getElementById('catatanField').required = true;
    }
    function cancelReject() {
        document.getElementById('rejectNoteContainer').style.display = 'none';
        document.getElementById('actionButtonsContainer').style.display = 'block';
        document.getElementById('confirmRejectContainer').style.display = 'none';
        document.getElementById('catatanField').required = false;
        document.getElementById('catatanField').value = '';
    }

    function submitKesiapan(status) {
        document.getElementById('kesiapanField').value = status;
        
        var check1 = document.getElementById('check1');
        var check2 = document.getElementById('check2');
        var check3 = document.getElementById('check3');

        if (status === 'siap') {
            if (!check1 || !check2 || !check3 || !check1.checked || !check2.checked || !check3.checked) {
                alert('Silahkan centang semua checklist kesiapan sebelum mengonfirmasi siap.');
                return;
            }
        } else {
            if (check1) check1.removeAttribute('required');
            if (check2) check2.removeAttribute('required');
            if (check3) check3.removeAttribute('required');
        }

        document.getElementById('kesiapanForm').submit();
    }
</script>
@endsection
