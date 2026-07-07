@extends('layout.app_tailwind')




@section('content')
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
        background-color: #557a67;
    }
    .btn-decision-verify {
        background-color: #16a34a;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-decision-verify:hover {
        background-color: #15803d;
    }
    .btn-decision-reject {
        background-color: #dc2626;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-decision-reject:hover {
        background-color: #b91c1c;
    }
    .btn-decision-revision {
        border: none;
        background-color: #eab308;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        width: 100%;
        transition: 0.2s;
    }
    .btn-decision-revision:hover {
        background-color: #ca8a04;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Admin memverifikasi pengajuan peminjaman</h2>
        <p class="mb-0 text-secondary text-wrap" style="max-width: 650px;">
            Admin mengecek kelengkapan data, ketersediaan fasilitas, dan hasil verifikasi dosen sebelum lanjut ke tahap berikutnya.
        </p>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Antrian Verifikasi -->
    <div class="col-lg-7">
        <div class="mb-3 fw-semibold text-secondary">Antrian Verifikasi</div>

        <div class="d-grid gap-3">
            @php
                $selectedId = request()->query('selected_id');
                $selectedItem = null;
            @endphp

            @forelse($peminjamanQueue as $item)
                @if(!$selectedId && $loop->first)
                    @php $selectedId = $item->id_peminjaman; @endphp
                @endif
                @if($item->id_peminjaman == $selectedId)
                    @php $selectedItem = $item; @endphp
                @endif

                <div class="queue-card {{ $item->id_peminjaman == $selectedId ? 'active' : '' }}" onclick="window.location.href='?selected_id={{ $item->id_peminjaman }}'">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold text-main">SBUM-2026-{{ str_pad($item->id_peminjaman, 4, '0', STR_PAD_LEFT) }} · {{ $item->nama_kegiatan }}</div>                            <div class="text-secondary small mt-1">
                                Peminjam: {{ $item->user->nama_lengkap ?? 'Peminjam' }} · 
                                {{ $item->nama_fasilitas_with_type }}
                            </div>
                            <div class="text-muted small mt-2">
                                Sudah diverifikasi dosen · {{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} · {{ $item->jam_mulai ? str_replace(':', '.', substr($item->jam_mulai, 0, 5)) : '08.00' }} - {{ $item->jam_selesai ? str_replace(':', '.', substr($item->jam_selesai, 0, 5)) : '12.00' }}
                            </div>
                        </div>
                        <span class="badge-warning-soft">Menunggu Admin</span>
                    </div>
 
                    <!-- Timeline flow -->
                    @php
                        $statusSteps = [
                            'menunggu_dosen' => 1,
                            'menunggu_pic' => 2,
                            'menunggu_admin' => 3,
                            'menunggu_kepala' => 4,
                            'siap_digunakan' => 5,
                            'pending' => 1,
                            'disetujui' => 5,
                            'revisi' => 1,
                            'ditolak' => 1,
                        ];
                        $currentStep = $statusSteps[$item->status] ?? 2;
                    @endphp
                    <div class="timeline-bar d-flex gap-1 mt-2">
                        <div class="timeline-step {{ $currentStep >= 1 ? 'completed' : '' }}" style="flex: 1; height: 8px; border-radius: 10px;"></div>
                        <div class="timeline-step {{ $currentStep >= 2 ? 'completed' : '' }}" style="flex: 1; height: 8px; border-radius: 10px;"></div>
                        <div class="timeline-step {{ $currentStep >= 3 ? 'completed' : '' }}" style="flex: 1; height: 8px; border-radius: 10px;"></div>
                        <div class="timeline-step {{ $currentStep >= 4 ? 'completed' : '' }}" style="flex: 1; height: 8px; border-radius: 10px;"></div>
                        <div class="timeline-step {{ $currentStep >= 5 ? 'completed' : '' }}" style="flex: 1; height: 8px; border-radius: 10px;"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-1" style="font-size:0.65rem;">
                        <span>Diajukan</span>
                        <span>Dosen PJ</span>
                        <span>PIC</span>
                        <span>Admin</span>
                        <span>Kepala</span>
                    </div>
 
                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-sm btn-link text-main fw-semibold p-0 text-decoration-none" style="font-size: 0.8rem; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#adminDetailModal{{ $item->id_peminjaman }}" onclick="openDetailModal(event)">
                            <i class="bi bi-info-circle me-1"></i> Detail
                        </button>
                    </div>
                </div>
 
                <!-- Modal for each request -->
                <div class="modal fade" id="adminDetailModal{{ $item->id_peminjaman }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 shadow-lg" style="background-color: #fffdfa;">
                            <div class="modal-header border-0 pb-0" style="background-color: #f7f3eb; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                                <h5 class="modal-title fw-bold text-main">Detail Peminjaman</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <span class="text-muted small d-block">Nama Kegiatan</span>
                                    <span class="fw-bold text-main fs-5">{{ $item->nama_kegiatan }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block">Waktu Acara</span>
                                    <span class="fw-semibold text-main">
                                        {{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} · 
                                        {{ $item->jam_mulai ? str_replace(':', '.', substr($item->jam_mulai, 0, 5)) : '08.00' }} - {{ $item->jam_selesai ? str_replace(':', '.', substr($item->jam_selesai, 0, 5)) : '12.00' }}
                                    </span>
                                </div>
                                @if($item->ruangan->count() > 0)
                                <div class="mb-3">
                                    <span class="text-muted small d-block">Jumlah Peserta</span>
                                    <span class="fw-semibold text-main">{{ $item->jumlah_peserta ?? '0' }} Orang</span>
                                </div>
                                @elseif($item->barang->count() > 0)
                                <div class="mb-3">
                                    <span class="text-muted small d-block">Jumlah Barang</span>
                                    <span class="fw-semibold text-main">{{ $item->barang->first()->pivot->jumlah ?? 1 }} Buah</span>
                                </div>
                                @endif
                                <div class="mb-3">
                                    <span class="text-muted small d-block">Keterangan / Deskripsi Acara</span>
                                    <span class="fw-semibold text-main">{{ $item->keterangan ?: 'Tidak ada keterangan tambahan.' }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block">Dosen Penanggung Jawab</span>
                                    <span class="fw-semibold text-main">{{ $item->dosen->nama_lengkap ?? '-' }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block">PIC Fasilitas</span>
                                    <span class="fw-semibold text-main">
                                        @if(count($item->ruangan) > 0)
                                            {{ $item->ruangan->first()->pic->nama_lengkap ?? '-' }}
                                        @elseif(count($item->barang) > 0)
                                            {{ $item->barang->first()->pic->nama_lengkap ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-main w-100" data-bs-dismiss="modal" style="border-radius:0.75rem;">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card p-5 text-center text-secondary border-0 w-100" style="background:#fffdfa; border-radius:1.5rem; border: 1px solid var(--line) !important;">
                    Antrian verifikasi peminjaman tidak ada.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Verification Form & Decisions -->
    <div class="col-lg-5">
        @if($selectedItem)
            <form action="{{ route('admin.verifikasi-peminjaman.verifikasi', $selectedItem->id_peminjaman) }}" method="POST" id="decisionForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="status_pengajuan" id="statusField" value="disetujui">

                <div class="mb-3 fw-semibold text-secondary text-start">Checklist Verifikasi</div>
                <div class="checklist-card mb-4">
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check1" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check1">Data peminjaman lengkap</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check2" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check2">Fasilitas tersedia pada jam tersebut</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check3" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check3">Dokumen pendukung valid</label>
                    </div>

                    <div class="mt-4 text-start" id="rejectNoteContainer" style="display: none;">
                        <label class="form-label text-danger fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan" id="catatanField" class="form-control border-danger" rows="3" placeholder="Wajib: Berikan alasan kenapa pengajuan ini ditolak..." style="border-radius: 0.75rem;"></textarea>
                    </div>
                </div>

                <div class="mb-3 fw-semibold text-secondary text-start">Keputusan</div>
                <div class="decision-card">
                    <div class="d-grid gap-2" id="actionButtonsContainer">
                        <button type="submit" onclick="setStatus('disetujui')" class="btn btn-decision-verify">Verifikasi</button>
                        <button type="button" onclick="showRejectNote()" class="btn btn-decision-reject">Tolak</button>
                    </div>
                    <div class="d-grid gap-2 mt-3" id="confirmRejectContainer" style="display: none;">
                        <button type="button" onclick="cancelReject()" class="btn btn-light" style="border-radius:0.75rem; color:#7d8781;">Batal</button>
                        <button type="submit" onclick="setStatus('ditolak')" class="btn btn-decision-reject">Konfirmasi Tolak</button>
                    </div>
                </div>
            </form>
        @else
            <div class="card p-5 text-center text-secondary border-0" style="background:#fffdfa; border-radius:1.5rem; border: 1px solid var(--line) !important;">
                Antrian verifikasi peminjaman tidak ada.
            </div>
        @endif
    </div>
</div>

<script>
    function openDetailModal(event) {
        if (event) {
            event.stopPropagation();
        }
    }

    function setStatus(status) {
        var statusField = document.getElementById('statusField');
        if (statusField) {
            statusField.value = status;
        }
        
        // Remove 'required' logic for checkboxes if rejecting or requesting revision
        var check1 = document.getElementById('check1');
        var check2 = document.getElementById('check2');
        var check3 = document.getElementById('check3');
        if (check1 && check2 && check3) {
            if (status === 'ditolak' || status === 'revisi') {
                check1.removeAttribute('required');
                check2.removeAttribute('required');
                check3.removeAttribute('required');
            } else {
                check1.setAttribute('required', 'required');
                check2.setAttribute('required', 'required');
                check3.setAttribute('required', 'required');
            }
        }
    }
</script>

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
</script>

@endsection
