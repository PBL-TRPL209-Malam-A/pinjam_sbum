@extends('layout.dosen')

@section('page_caption', 'Dashboard')
@section('page_heading', 'Verifikasi Peminjaman')

@section('dosen_content')
<style>
    .banner-dosen {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
        padding: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }
    .stat-pill {
        background: white;
        border: 1px solid var(--line);
        border-radius: 1rem;
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 140px;
    }
    .stat-num {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-main);
    }
    .stat-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
        line-height: 1.2;
    }
    .req-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
        height: 100%;
        display: flex;
        flex-column: column;
        justify-content: space-between;
        transition: 0.3s;
    }
    .req-card:hover {
        transform: translateY(-2px);
    }
    .req-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    .badge-menunggu {
        background-color: #fcf1d3;
        color: #7d6006;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.4rem 0.85rem;
        border-radius: 2rem;
    }
    .badge-revisi {
        background-color: #fcebeb;
        color: #8b3c3c;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.4rem 0.85rem;
        border-radius: 2rem;
    }
</style>

<!-- Banner Card -->
<div class="banner-dosen mb-4">
    <div style="max-width: 550px;">
        <h2 class="fs-4 fw-bold text-main mb-2">Halo, Dosen Penanggung Jawab</h2>
        <p class="text-secondary small mb-3">
            Tinjau permohonan mahasiswa, verifikasi kelayakan kegiatan, lalu lanjutkan ke keputusan.
        </p>
        <button class="btn btn-main">Buka Antrian</button>
    </div>
    <div class="d-flex gap-3">
        <div class="stat-pill">
            <div class="stat-num">6</div>
            <div class="stat-label">Perlu<br>Review</div>
        </div>
        <div class="stat-pill">
            <div class="stat-num">2</div>
            <div class="stat-label">Butuh<br>Revisi</div>
        </div>
    </div>
</div>

<!-- Grid Request cards -->
<div class="mb-3 fw-semibold text-secondary">Daftar Permohonan Masuk</div>
<div class="row g-4 mb-4">
    @forelse($peminjaman as $item)
    <div class="col-md-6">
        <div class="req-card">
            <div>
                <div class="req-header">
                    <div>
                        <h4 class="fs-5 fw-bold text-main mb-0">{{ $item->user->nama_lengkap ?? 'Mahasiswa' }}</h4>
                        <div class="text-secondary small">{{ $item->user->nim ?? '-' }} · Kegiatan</div>
                    </div>
                    @if($item->status == 'pending')
                        <span class="badge-menunggu">Menunggu Verifikasi</span>
                    @else
                        <span class="badge-revisi">Butuh Revisi</span>
                    @endif
                </div>
                <div class="mb-3">
                    <div class="text-secondary small fw-semibold">Ruangan / Fasilitas:</div>
                    <div class="text-main fw-semibold">{{ count($item->ruangan) > 0 ? $item->ruangan->first()->nama_ruangan : (count($item->barang) > 0 ? $item->barang->first()->nama_barang : 'Fasilitas') }}</div>
                </div>
                <div class="mb-4">
                    <div class="text-secondary small fw-semibold">Tanggal & Waktu:</div>
                    <div class="text-main fw-semibold small">{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : now()->format('d M Y') }} · 08.00 - 12.00</div>
                </div>
            </div>
            <div>
                <button class="btn action-btn-outline w-100" style="border: 1px solid var(--line); border-radius: 0.75rem;" data-bs-toggle="modal" data-bs-target="#verifModal{{ $item->id_peminjaman }}">Lihat Detail</button>
            </div>
        </div>
    </div>

    <!-- Modal for each request -->
    <div class="modal fade" id="verifModal{{ $item->id_peminjaman }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg" style="background-color: #fffdfa;">
                <div class="modal-header border-0 pb-0" style="background-color: #f7f3eb; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                    <h5 class="modal-title fw-bold text-main">Tinjau Permohonan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dosen.verifikasi', $item->id_peminjaman) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status_pengajuan" id="statusField{{ $item->id_peminjaman }}" value="verif_dosen">
                    
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <span class="text-muted small d-block">Peminjam</span>
                            <span class="fw-bold text-main fs-5">{{ $item->user->nama_lengkap ?? '-' }} ({{ $item->user->nim ?? '-' }})</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted small d-block">Kegiatan</span>
                            <span class="fw-semibold text-main">{{ $item->nama_kegiatan }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted small d-block">Fasilitas</span>
                            <span class="fw-semibold text-main">{{ count($item->ruangan) > 0 ? $item->ruangan->first()->nama_ruangan : (count($item->barang) > 0 ? $item->barang->first()->nama_barang : 'Fasilitas') }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted small d-block">Waktu</span>
                            <span class="fw-semibold text-main">{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : now()->format('d M Y') }} · 08.00 - 12.00</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Catatan Dosen</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan akademik..." style="border-radius: 0.75rem; border-color: #dfd4c8;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 d-flex gap-2">
                        <button type="submit" onclick="document.getElementById('statusField{{ $item->id_peminjaman }}').value='verif_dosen'" class="btn btn-main flex-grow-1" style="border-radius:0.75rem;">Setujui</button>
                        <button type="submit" onclick="document.getElementById('statusField{{ $item->id_peminjaman }}').value='revisi'" class="btn btn-warning text-white flex-grow-1" style="border-radius:0.75rem; background-color:#dca134; border:none;">Minta Revisi</button>
                        <button type="submit" onclick="document.getElementById('statusField{{ $item->id_peminjaman }}').value='ditolak'" class="btn btn-danger flex-grow-1" style="border-radius:0.75rem; background-color:#c95b50; border:none;">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <!-- Fallback requests matching Image 2 exactly -->
    <div class="col-md-6">
        <div class="req-card">
            <div>
                <div class="req-header">
                    <div>
                        <h4 class="fs-5 fw-bold text-main mb-0">Moch Azmi Aris Sandita</h4>
                        <div class="text-secondary small">NIM 4342511024 · Himpunan TRPL</div>
                    </div>
                    <span class="badge-menunggu">Menunggu Verifikasi</span>
                </div>
                <div class="mb-3">
                    <div class="text-secondary small fw-semibold">Ruangan / Fasilitas:</div>
                    <div class="text-main fw-semibold">Aula Utama Polibatam</div>
                </div>
                <div class="mb-4">
                    <div class="text-secondary small fw-semibold">Tanggal & Waktu:</div>
                    <div class="text-main fw-semibold small">12 Apr 2026 · 08.00 - 12.00</div>
                </div>
            </div>
            <div>
                <button class="btn action-btn-outline w-100" style="border: 1px solid var(--line); border-radius: 0.75rem;">Lihat Detail</button>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="req-card">
            <div>
                <div class="req-header">
                    <div>
                        <h4 class="fs-5 fw-bold text-main mb-0">Ayudia Permata Pabatia</h4>
                        <div class="text-secondary small">NIM 4342511020 · Workshop UKM</div>
                    </div>
                    <span class="badge-menunggu">Menunggu Verifikasi</span>
                </div>
                <div class="mb-3">
                    <div class="text-secondary small fw-semibold">Ruangan / Fasilitas:</div>
                    <div class="text-main fw-semibold">Lab Komputer 1</div>
                </div>
                <div class="mb-4">
                    <div class="text-secondary small fw-semibold">Tanggal & Waktu:</div>
                    <div class="text-main fw-semibold small">14 Apr 2026 · 09.00 - 11.00</div>
                </div>
            </div>
            <div>
                <button class="btn action-btn-outline w-100" style="border: 1px solid var(--line); border-radius: 0.75rem;">Lihat Detail</button>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="req-card">
            <div>
                <div class="req-header">
                    <div>
                        <h4 class="fs-5 fw-bold text-main mb-0">Danudenta Arhab Tsaqif</h4>
                        <div class="text-secondary small">NIM 4342511021 · Seminar Prodi</div>
                    </div>
                    <span class="badge-menunggu">Menunggu Verifikasi</span>
                </div>
                <div class="mb-3">
                    <div class="text-secondary small fw-semibold">Ruangan / Fasilitas:</div>
                    <div class="text-main fw-semibold">Ruang Multimedia 2</div>
                </div>
                <div class="mb-4">
                    <div class="text-secondary small fw-semibold">Tanggal & Waktu:</div>
                    <div class="text-main fw-semibold small">13 Apr 2026 · 13.00 - 16.00</div>
                </div>
            </div>
            <div>
                <button class="btn action-btn-outline w-100" style="border: 1px solid var(--line); border-radius: 0.75rem;">Lihat Detail</button>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="req-card">
            <div>
                <div class="req-header">
                    <div>
                        <h4 class="fs-5 fw-bold text-main mb-0">Grexia Angelina</h4>
                        <div class="text-secondary small">NIM 4342511008 · Rapat Panitia</div>
                    </div>
                    <span class="badge-revisi">Butuh Revisi</span>
                </div>
                <div class="mb-3">
                    <div class="text-secondary small fw-semibold">Ruangan / Fasilitas:</div>
                    <div class="text-main fw-semibold">Ruang Rapat SBUM</div>
                </div>
                <div class="mb-4">
                    <div class="text-secondary small fw-semibold">Tanggal & Waktu:</div>
                    <div class="text-main fw-semibold small">15 Apr 2026 · 15.00 - 17.00</div>
                </div>
            </div>
            <div>
                <button class="btn action-btn-outline w-100" style="border: 1px solid var(--line); border-radius: 0.75rem;" disabled>Butuh Revisi</button>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection
