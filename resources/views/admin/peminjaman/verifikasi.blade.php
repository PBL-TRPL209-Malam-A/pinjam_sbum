@extends('layout.admin')

@section('page_caption', 'Verifikasi Pengajuan Peminjaman')
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
    .btn-decision-verify {
        background-color: var(--primary-main);
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-decision-verify:hover {
        background-color: var(--primary-dark);
    }
    .btn-decision-reject {
        background-color: #c95b50;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-decision-reject:hover {
        background-color: #b34e44;
    }
    .btn-decision-revision {
        border: 1px solid var(--line);
        background: white;
        color: var(--text-main);
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        width: 100%;
        transition: 0.2s;
    }
    .btn-decision-revision:hover {
        background-color: #fdfcf9;
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
                            <div class="fw-bold text-main">SBUM-2026-{{ str_pad($item->id_peminjaman, 4, '0', STR_PAD_LEFT) }} · {{ $item->nama_kegiatan }}</div>
                            <div class="text-secondary small mt-1">
                                Peminjam: {{ $item->user->nama_lengkap ?? 'Mahasiswa' }} · 
                                {{ count($item->ruangan) > 0 ? $item->ruangan->first()->nama_ruangan : (count($item->barang) > 0 ? $item->barang->first()->nama_barang : 'Fasilitas') }}
                            </div>
                            <div class="text-muted small mt-2">
                                Sudah diverifikasi dosen · {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : now()->format('d M Y') }} · 08.00 - 12.00
                            </div>
                        </div>
                        <span class="badge-warning-soft">Menunggu Admin</span>
                    </div>

                    <!-- Timeline flow -->
                    <div class="timeline-bar">
                        <div class="timeline-step completed"></div>
                        <div class="timeline-step {{ $item->status != 'pending' ? 'completed' : '' }}"></div>
                        <div class="timeline-step"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-1" style="font-size:0.75rem;">
                        <span>Diajukan</span>
                        <span>Admin</span>
                        <span>Kepala SBUM</span>
                    </div>
                </div>
            @empty
                <!-- Mock item if queue is empty to show working mockup -->
                <div class="queue-card active">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold text-main">SBUM-2026-0148 · Seminar Mahasiswa Baru</div>
                            <div class="text-secondary small mt-1">Peminjam: Moch Azmi Aris Sandita - Aula Utama Polibatam</div>
                            <div class="text-muted small mt-2">Sudah diverifikasi dosen · 12 Apr 2026 · 08.00 - 12.00</div>
                        </div>
                        <span class="badge-warning-soft">Menunggu Admin</span>
                    </div>

                    <div class="timeline-bar">
                        <div class="timeline-step completed"></div>
                        <div class="timeline-step"></div>
                        <div class="timeline-step"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-1" style="font-size:0.75rem;">
                        <span>Diajukan</span>
                        <span>Admin</span>
                        <span>Kepala SBUM</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Verification Form & Decisions -->
    <div class="col-lg-5">
        <form action="{{ route('admin.verifikasi-peminjaman.verifikasi', $selectedItem ? $selectedItem->id_peminjaman : 1) }}" method="POST" id="decisionForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="status_pengajuan" id="statusField" value="disetujui">

            <div class="mb-3 fw-semibold text-secondary">Checklist Verifikasi</div>
            <div class="checklist-card mb-4">
                <div class="checklist-item">
                    <input class="form-check-input mt-0" type="checkbox" id="check1" required>
                    <label class="form-check-label text-main fw-semibold" for="check1">Data peminjaman lengkap</label>
                </div>
                <div class="checklist-item">
                    <input class="form-check-input mt-0" type="checkbox" id="check2" required>
                    <label class="form-check-label text-main fw-semibold" for="check2">Fasilitas tersedia pada jam tersebut</label>
                </div>
                <div class="checklist-item">
                    <input class="form-check-input mt-0" type="checkbox" id="check3" required>
                    <label class="form-check-label text-main fw-semibold" for="check3">Dokumen pendukung valid</label>
                </div>

                <div class="mt-4">
                    <label class="form-label text-secondary fw-semibold">Catatan Admin</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan verifikasi" style="border-radius: 0.75rem; border-color: #dfd4c8;"></textarea>
                </div>
            </div>

            <div class="mb-3 fw-semibold text-secondary">Keputusan</div>
            <div class="decision-card">
                <div class="d-grid gap-2">
                    <button type="submit" onclick="setStatus('disetujui')" class="btn btn-decision-verify">Verifikasi</button>
                    <button type="submit" onclick="setStatus('ditolak')" class="btn btn-decision-reject">Tolak</button>
                    <button type="submit" onclick="setStatus('revisi')" class="btn btn-decision-revision">Minta Revisi</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function setStatus(status) {
        document.getElementById('statusField').value = status;
        
        // Remove 'required' logic for checkboxes if rejecting or requesting revision
        var check1 = document.getElementById('check1');
        var check2 = document.getElementById('check2');
        var check3 = document.getElementById('check3');
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
</script>
@endsection
