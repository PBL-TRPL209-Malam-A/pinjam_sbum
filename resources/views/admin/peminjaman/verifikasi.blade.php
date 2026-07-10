@extends('layout.app_tailwind')




@section('content')

<style>
    .timeline-step {
        background-color: #e6ddd2;
    }
    .timeline-step.completed {
        background-color: #466454;
    }
</style>

<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div>
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Admin memverifikasi pengajuan peminjaman</h2>
        <p class="text-[#7d8781] max-w-2xl mb-0">
            Admin mengecek kelengkapan data, ketersediaan fasilitas, dan hasil verifikasi dosen sebelum lanjut ke tahap berikutnya.
        </p>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-6">
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
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-6 lg:p-8 mb-6">
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

                    <div class="mt-4 mb-4 text-start">
                        <label class="form-label text-secondary fw-semibold">Keputusan Verifikasi <span class="text-danger">*</span></label>
                        <div class="d-flex flex-column gap-2">
                            <label class="d-flex align-items-center p-3 border rounded-3" style="cursor: pointer; background:#dcebd7; border-color:#b7d2b6 !important;">
                                <input type="radio" name="status_pengajuan" value="disetujui" class="form-check-input mt-0 me-3" required onchange="handleDecisionChange(this.value)">
                                <span class="fw-semibold text-[#466454]">Disetujui (Lanjut ke Kepala SBUM)</span>
                            </label>
                            <label class="d-flex align-items-center p-3 border rounded-3" style="cursor: pointer; background:#fdf4d6; border-color:#f5da79 !important;">
                                <input type="radio" name="status_pengajuan" value="disetujui_bypass" class="form-check-input mt-0 me-3" required onchange="handleDecisionChange(this.value)">
                                <span class="fw-semibold" style="color: #9c6c06;">Disetujui & Bypass Kepala SBUM</span>
                            </label>
                            <label class="d-flex align-items-center p-3 border rounded-3" style="cursor: pointer; background:#fdf0f0; border-color:#f5c2c7 !important;">
                                <input type="radio" name="status_pengajuan" value="ditolak" class="form-check-input mt-0 me-3" required onchange="handleDecisionChange(this.value)">
                                <span class="fw-semibold text-danger">Tolak Pengajuan</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-4 text-start" id="rejectNoteContainer" style="display: none;">
                        <label class="form-label fw-bold" id="rejectLabel">Catatan / Alasan <span class="text-danger">*</span></label>
                        <textarea name="catatan" id="catatanField" class="form-control" rows="3" placeholder="Wajib: Berikan alasan jika ditolak atau bypass..." style="border-radius: 0.75rem;"></textarea>
                    </div>

                    <div class="modal-footer border-0 pt-0 d-flex w-100 mt-4 px-0">
                        <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-3 rounded-xl font-semibold transition w-100 border-0" style="border-radius:0.75rem;">Simpan Keputusan</button>
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

    function handleDecisionChange(value) {
        var rejectContainer = document.getElementById('rejectNoteContainer');
        var catatanField = document.getElementById('catatanField');
        var rejectLabel = document.getElementById('rejectLabel');
        
        var check1 = document.getElementById('check1');
        var check2 = document.getElementById('check2');
        var check3 = document.getElementById('check3');

        if (value === 'ditolak') {
            rejectContainer.style.display = 'block';
            catatanField.required = true;
            catatanField.classList.add('border-danger');
            catatanField.classList.remove('border-warning');
            rejectLabel.classList.add('text-danger');
            rejectLabel.classList.remove('text-warning');
            
            if(check1) check1.required = false;
            if(check2) check2.required = false;
            if(check3) check3.required = false;
        } else if (value === 'disetujui_bypass') {
            rejectContainer.style.display = 'block';
            catatanField.required = true;
            catatanField.classList.remove('border-danger');
            catatanField.classList.add('border-warning');
            rejectLabel.classList.remove('text-danger');
            rejectLabel.classList.add('text-warning');
            
            if(check1) check1.required = true;
            if(check2) check2.required = true;
            if(check3) check3.required = true;
        } else {
            rejectContainer.style.display = 'none';
            catatanField.required = false;
            catatanField.classList.remove('border-danger');
            catatanField.classList.remove('border-warning');
            rejectLabel.classList.remove('text-danger');
            rejectLabel.classList.remove('text-warning');
            
            if(check1) check1.required = true;
            if(check2) check2.required = true;
            if(check3) check3.required = true;
        }
    }
</script>

@endsection
