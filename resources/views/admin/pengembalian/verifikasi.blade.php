@extends('layout.admin')

@section('page_caption', 'Verifikasi Pengembalian')
@section('page_heading', 'Admin SBUM')

@section('admin_content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .queue-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.25rem;
        padding: 1.25rem;
        cursor: pointer;
        transition: 0.2s;
        border-left: 5px solid #dfd4c8;
    }
    .queue-card.active {
        background-color: #f7f3eb;
        border-left-color: var(--primary-main);
    }
    .checklist-card, .decision-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
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
    .timeline-bar {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .timeline-step {
        flex: 1;
        height: 8px;
        border-radius: 10px;
        background-color: #dfd4c8;
    }
    .timeline-step.completed {
        background-color: var(--primary-main);
    }
    .badge-menunggu {
        background-color: #fcf1d3;
        color: #7d6006;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        border-radius: 2rem;
        display: inline-block;
        width: 100%;
        text-align: center;
    }
    .btn-verify-done {
        background-color: var(--primary-main);
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-verify-done:hover {
        background-color: var(--primary-dark);
    }
    .btn-verify-check {
        background-color: #fcf1d3;
        color: #7d6006;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: 1px solid #f9e2ae;
        width: 100%;
        transition: 0.2s;
    }
    .btn-verify-check:hover {
        background-color: #fce8bc;
    }
    .btn-verify-alert {
        background-color: #c95b50;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-verify-alert:hover {
        background-color: #b34e44;
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
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Admin memverifikasi fasilitas yang dikembalikan</h2>
        <p class="mb-0 text-secondary text-wrap">
            Admin memeriksa apakah fasilitas sesuai data peminjaman dan apakah kondisi akhir dinyatakan baik atau perlu tindak lanjut.
        </p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 mb-4 text-start" role="alert" style="background-color: #e2f0d9; color: #385723;">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@php
    $selectedId = request()->query('selected_id');
    $kategori = request()->query('kategori', 'ruangan');
    $selectedItem = null;
    
    if (!$selectedId && $pengembalian->isNotEmpty()) {
        $first = $pengembalian->first();
        $selectedId = $first->id_pengembalian;
        $kategori = $first->kategori;
    }

    if ($selectedId) {
        $selectedItem = $pengembalian->first(function($item) use ($selectedId, $kategori) {
            return $item->id_pengembalian == $selectedId && $item->kategori == $kategori;
        });
    }
@endphp

<div class="row g-4">
    <!-- Left Column: Antrian Pengembalian -->
    <div class="col-lg-7">
        <div class="mb-3 fw-semibold text-secondary text-start">Antrian Pengembalian</div>
        <div class="d-grid gap-3">
            @forelse($pengembalian as $item)
                <div class="queue-card {{ $item->id_pengembalian == $selectedId && $item->kategori == $kategori ? 'active' : '' }}" onclick="window.location.href='?selected_id={{ $item->id_pengembalian }}&kategori={{ $item->kategori }}'">
                    <div class="d-flex justify-content-between align-items-start text-start">
                        <div>
                            <div class="fw-bold text-main">SBUM-2026-{{ str_pad($item->peminjaman->id_peminjaman, 4, '0', STR_PAD_LEFT) }} · {{ $item->peminjaman->nama_kegiatan }}</div>
                            <div class="text-secondary small mt-1">
                                Peminjam: {{ $item->peminjaman->user->nama_lengkap ?? 'Mahasiswa' }} · 
                                {{ $item->kategori === 'ruangan' ? ($item->peminjaman->ruangan->isNotEmpty() ? $item->peminjaman->ruangan->first()->nama_ruangan : 'Ruangan') : ($item->peminjaman->barang->isNotEmpty() ? $item->peminjaman->barang->first()->nama_barang : 'Barang') }}
                            </div>
                            <div class="text-muted small mt-2">
                                Pengembalian: {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->translatedFormat('d M Y') : '-' }}
                            </div>
                        </div>
                        <span class="badge-verifikasi-soft" style="background-color: #fcf1d3; color: #7d6006; font-size: 0.85rem; font-weight: 600; padding: 0.4rem 1rem; border-radius: 2rem;">Menunggu Verifikasi</span>
                    </div>

                    <!-- Timeline flow -->
                    <div class="timeline-container mt-3">
                        <div class="d-flex justify-content-between align-items-center position-relative mb-1" style="height: 8px;">
                            <!-- Progress line background -->
                            <div class="position-absolute top-50 start-0 end-0 translate-middle-y" style="height: 4px; background-color: #dfd4c8; z-index: 1;"></div>
                            
                            <!-- Progress line active -->
                            @php
                                $status = $item->status_pengembalian;
                                $width = '0%';
                                if ($status === 'menunggu_admin') {
                                    $width = '50%';
                                } elseif ($status === 'selesai') {
                                    $width = '100%';
                                }
                            @endphp
                            <div class="position-absolute top-50 start-0 translate-middle-y" style="height: 4px; background-color: var(--primary-main); width: {{ $width }}; z-index: 2; transition: 0.3s;"></div>

                            <!-- Step 1: Diajukan -->
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; background-color: var(--primary-main); border: 2px solid #fffdfa; z-index: 3;"></div>
                            
                            <!-- Step 2: PIC Fasilitas -->
                            @php
                                $step2Color = ($status === 'menunggu_admin' || $status === 'selesai') ? 'var(--primary-main)' : '#dfd4c8';
                            @endphp
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; background-color: {{ $step2Color }}; border: 2px solid #fffdfa; z-index: 3;"></div>
                            
                            <!-- Step 3: Admin SBUM -->
                            @php
                                $step3Color = ($status === 'selesai') ? 'var(--primary-main)' : '#dfd4c8';
                            @endphp
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; background-color: {{ $step3Color }}; border: 2px solid #fffdfa; z-index: 3;"></div>
                        </div>

                        <!-- Labels -->
                        <div class="d-flex justify-content-between text-secondary" style="font-size: 0.75rem;">
                            <!-- Label 1 -->
                            <div style="width: 30%; text-align: left; line-height: 1.2;">
                                <span class="d-block fw-semibold text-main">Diajukan</span>
                                <span class="text-muted small text-truncate d-block" style="max-width: 120px;">{{ $item->peminjaman->user->nama_lengkap ?? 'Mahasiswa' }}</span>
                            </div>
                            <!-- Label 2 -->
                            @php
                                $picName = 'PIC Fasilitas';
                                if ($item->kategori === 'ruangan' && $item->peminjaman->ruangan->isNotEmpty()) {
                                    $picName = $item->peminjaman->ruangan->first()->pic->nama_lengkap ?? 'PIC';
                                } elseif ($item->kategori === 'barang' && $item->peminjaman->barang->isNotEmpty()) {
                                    $picName = $item->peminjaman->barang->first()->pic->nama_lengkap ?? 'PIC';
                                }
                            @endphp
                            <div style="width: 40%; text-align: center; line-height: 1.2;">
                                <span class="d-block fw-semibold" style="color: {{ ($status === 'menunggu_admin' || $status === 'selesai') ? 'var(--text-main)' : 'var(--text-muted)' }};">PIC Fasilitas</span>
                                <span class="text-muted small text-truncate d-block mx-auto" style="max-width: 120px;">{{ $picName }}</span>
                            </div>
                            <!-- Label 3 -->
                            <div style="width: 30%; text-align: right; line-height: 1.2;">
                                <span class="d-block fw-semibold" style="color: {{ ($status === 'selesai') ? 'var(--text-main)' : 'var(--text-muted)' }};">Admin SBUM</span>
                                <span class="text-muted small d-block">Admin</span>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Trigger Button -->
                    <div class="text-end mt-2">
                        <button type="button" class="btn btn-sm btn-edit-outline" style="border: 1px solid var(--primary-main); color: var(--primary-main); border-radius: 0.5rem; font-size: 0.8rem; padding: 0.25rem 0.75rem;" onclick="openDetailModal(event, '{{ $item->id_pengembalian }}', '{{ $item->kategori }}')">
                            Detail
                        </button>
                    </div>
                </div>
            @empty
                <div class="card p-5 text-center text-secondary border-0" style="background:#fffdfa; border-radius:1.5rem; border: 1px solid var(--line) !important;">
                    Tidak ada antrean pengembalian.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Decisions & Decisions -->
    <div class="col-lg-5">
        @if($selectedItem)
            <form action="{{ route('admin.verifikasi-pengembalian.verifikasi', $selectedItem->id_pengembalian) }}" method="POST" id="decisionForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="kategori" value="{{ $selectedItem->kategori }}">
                <input type="hidden" name="status_keputusan" id="keputusanField" value="disetujui">

                <div class="mb-3 fw-semibold text-secondary text-start">Panel Validasi</div>
                <div class="checklist-card mb-4">
                    <div class="mb-3 fw-semibold text-secondary text-start" style="font-size: 0.95rem;">Checklist Verifikasi Fisik</div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check1" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check1">Fasilitas kembali sesuai jumlah</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check2" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check2">Tidak ada kerusakan mayor</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check3" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check3">Catatan mahasiswa sesuai pemeriksaan</label>
                    </div>

                    <div class="mt-4 text-start">
                        <label class="form-label text-secondary fw-semibold">Catatan Admin</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: fasilitas kembali lengkap dan kondisi dinyatakan baik." style="border-radius: 0.75rem; border-color: #dfd4c8;"></textarea>
                    </div>
                </div>

                <div class="mb-3 fw-semibold text-secondary text-start">Keputusan Verifikasi</div>
                <div class="decision-card">
                    
                    <div class="d-grid gap-2">
                        <button type="button" onclick="submitDecision('disetujui')" class="btn btn-verify-done">Verifikasi Selesai</button>
                        <button type="button" onclick="submitDecision('bermasalah')" class="btn btn-verify-alert">Tandai Bermasalah</button>
                    </div>

                    <div class="text-muted small mt-4 text-center">
                        Hasil verifikasi akan memperbarui status pengembalian agar riwayat fasilitas tetap terdokumentasi.
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

<!-- Modal Popups Definition -->
@foreach($pengembalian as $item)
    @php
        $pjm = $item->peminjaman;
        $fotoPath = null;
        $dokumenPath = null;
        $returnDate = null;
        $returnTime = null;
        $returnCatatan = null;

        if ($item->kategori === 'ruangan' && $pjm->pengembalianRuangan) {
            $fotoPath = $pjm->pengembalianRuangan->foto_kondisi;
            $dokumenPath = $pjm->pengembalianRuangan->dokumen_administrasi;
            $returnDate = $pjm->pengembalianRuangan->tanggal_pengembalian;
            $returnTime = $pjm->pengembalianRuangan->jam_selesai_aktual;
            $returnCatatan = $pjm->pengembalianRuangan->catatan;
        } elseif ($item->kategori === 'barang' && $pjm->pengembalianBarang) {
            $fotoPath = $pjm->pengembalianBarang->foto_kondisi;
            $dokumenPath = $pjm->pengembalianBarang->dokumen_administrasi;
            $returnDate = $pjm->pengembalianBarang->tanggal;
            $returnTime = $pjm->pengembalianBarang->jam_selesai_aktual;
            $returnCatatan = $pjm->pengembalianBarang->catatan;
        }

        $picVer = null;
        if ($item->kategori === 'ruangan' && $pjm->pengembalianRuangan) {
            $picVer = \App\Models\VerifikasiPengembalian::where('id_pengembalian_ruangan', $pjm->pengembalianRuangan->id_pengembalian_ruangan)
                ->where('peran_verifikasi', 'PIC Fasilitas')
                ->first();
        } elseif ($item->kategori === 'barang' && $pjm->pengembalianBarang) {
            $picVer = \App\Models\VerifikasiPengembalian::where('id_pengembalian_barang', $pjm->pengembalianBarang->id_pengembalian_barang)
                ->where('peran_verifikasi', 'PIC Fasilitas')
                ->first();
        }
        $picCatatan = $picVer ? $picVer->catatan : 'Pemeriksaan Kesiapan oleh PIC';

        $fotoUrl = $fotoPath ? (str_starts_with($fotoPath, 'storage/') ? asset($fotoPath) : asset('storage/' . $fotoPath)) : null;
        $dokumenUrl = $dokumenPath ? (str_starts_with($dokumenPath, 'storage/') ? asset($dokumenPath) : asset('storage/' . $dokumenPath)) : null;
    @endphp
    <!-- Detail Modal for Return SBUM -->
    <div class="modal fade" id="detailModalReturn{{ $item->id_pengembalian }}{{ $item->kategori }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
                <div class="modal-header border-0 pb-0" style="padding: 1.5rem 1.5rem 0 1.5rem;">
                    <h5 class="modal-title fw-bold text-main">Rincian Pengembalian Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem;">
                    <div class="row g-3 mb-4 text-start">
                        <div class="col-md-6">
                            <div class="info-row d-flex flex-column align-items-start mb-0">
                                <span class="text-secondary small fw-semibold" style="font-size: 0.8rem; color: var(--text-muted);">ID Peminjaman</span>
                                <span class="fw-bold text-main">SBUM-2026-{{ str_pad($pjm->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row d-flex flex-column align-items-start mb-0">
                                <span class="text-secondary small fw-semibold" style="font-size: 0.8rem; color: var(--text-muted);">Fasilitas</span>
                                <span class="fw-bold text-main">
                                    {{ $item->kategori === 'ruangan' ? ($pjm->ruangan->isNotEmpty() ? $pjm->ruangan->first()->nama_ruangan : 'Ruangan') : ($pjm->barang->isNotEmpty() ? $pjm->barang->first()->nama_barang : 'Barang') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row d-flex flex-column align-items-start mb-0">
                                <span class="text-secondary small fw-semibold" style="font-size: 0.8rem; color: var(--text-muted);">Tanggal Pengembalian</span>
                                <span class="fw-bold text-main">{{ $returnDate ? \Carbon\Carbon::parse($returnDate)->translatedFormat('d M Y') : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row d-flex flex-column align-items-start mb-0">
                                <span class="text-secondary small fw-semibold" style="font-size: 0.8rem; color: var(--text-muted);">Jam Selesai Aktual</span>
                                <span class="fw-bold text-main">{{ $returnTime ? substr($returnTime, 0, 5) : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="info-row d-flex flex-column align-items-start mb-0">
                                <span class="text-secondary small fw-semibold" style="font-size: 0.8rem; color: var(--text-muted);">Catatan Pengembalian Mahasiswa</span>
                                <span class="fw-bold text-main">{{ $returnCatatan ?: '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row d-flex flex-column align-items-start mb-0">
                                <span class="text-secondary small fw-semibold" style="font-size: 0.8rem; color: var(--text-muted);">PIC yang Menyetujui</span>
                                <span class="fw-bold text-main">{{ ($picVer && $picVer->verifikator) ? $picVer->verifikator->nama_lengkap : 'Tidak Ditemukan' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row d-flex flex-column align-items-start mb-0">
                                <span class="text-secondary small fw-semibold" style="font-size: 0.8rem; color: var(--text-muted);">Catatan Verifikasi PIC Fasilitas</span>
                                <span class="fw-bold text-main">{{ $picCatatan }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3" style="border-top: 1px solid var(--line);">

                    <div class="row g-3 text-start">
                        <div class="col-md-6">
                            <span class="text-secondary small fw-semibold d-block mb-2" style="font-size: 0.8rem; color: var(--text-muted);">Foto Kondisi Pasca-Pakai</span>
                            @if($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="Foto Kondisi Pasca-Pakai" class="rounded-4 border" style="width: 120px; height: 120px; object-fit: cover; cursor: pointer;" onclick="window.open('{{ $fotoUrl }}', '_blank')">
                            @else
                                <span class="text-secondary small mb-3" style="color: #6c757d;">Bukti tidak tersedia</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <span class="text-secondary small fw-semibold d-block mb-2" style="font-size: 0.8rem; color: var(--text-muted);">Dokumen Administrasi (PDF)</span>
                            @if($dokumenUrl)
                                <a href="{{ $dokumenUrl }}" download class="btn btn-upload-photo" style="background-color: var(--soft-bg); border-color: var(--line); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 0.75rem; text-decoration: none; padding: 0.5rem 1.5rem; width: auto; font-size: 0.9rem;">
                                    <i class="bi bi-file-earmark-pdf text-danger"></i> Unduh Dokumen PDF
                                </a>
                            @else
                                <span class="text-secondary small" style="color: #6c757d;">Bukti tidak tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0" style="padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal" style="font-size: 0.9rem;">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    function submitDecision(status) {
        document.getElementById('keputusanField').value = status;
        
        var check1 = document.getElementById('check1');
        var check2 = document.getElementById('check2');
        var check3 = document.getElementById('check3');

        if (status === 'disetujui') {
            if (!check1.checked || !check2.checked || !check3.checked) {
                alert('Silahkan centang semua checklist verifikasi sebelum menyetujui pengembalian.');
                return;
            }
        } else {
            check1.removeAttribute('required');
            check2.removeAttribute('required');
            check3.removeAttribute('required');
        }

        document.getElementById('decisionForm') || document.querySelector('form').submit();
    }

    function openDetailModal(event, id, kategori) {
        event.stopPropagation();
        const modalId = `detailModalReturn${id}${kategori}`;
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }
</script>
@endsection
