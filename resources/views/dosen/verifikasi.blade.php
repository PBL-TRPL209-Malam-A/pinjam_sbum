@extends('layout.app_tailwind')




@section('content')
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
        <button class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block">Buka Antrian</button>
    </div>
    <div class="d-flex gap-3">
        <div class="bg-white border border-[#e6ddd2] rounded-2xl px-6 py-3 flex items-center gap-4 min-w-[140px]">
            <div class="text-3xl font-bold text-[#466454]">{{ $menungguVerifikasi }}</div>
            <div class="text-xs font-medium text-[#7d8781] leading-tight">Perlu<br>Review</div>
        </div>
        <div class="bg-white border border-[#e6ddd2] rounded-2xl px-6 py-3 flex items-center gap-4 min-w-[140px]">
            <div class="text-3xl font-bold text-[#466454]">{{ $ditolakRevisi }}</div>
            <div class="text-xs font-medium text-[#7d8781] leading-tight">Butuh<br>Revisi</div>
        </div>
    </div>
</div>

<!-- Grid Request cards -->
<div class="mb-3 fw-semibold text-secondary">Daftar Permohonan Masuk</div>
<div class="row g-4 mb-4">
    @forelse($peminjaman as $item)
    <div class="col-md-6">
        <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 flex flex-col justify-between h-full hover:-translate-y-1 transition duration-300">
            <div>
                <div class="req-header">
                    <div>
                        <h4 class="fs-5 fw-bold text-main mb-0">{{ $item->user->nama_lengkap ?? 'Mahasiswa' }}</h4>
                        <div class="text-[#7d8781] text-sm">{{ $item->user->nim ?? '-' }} · Kegiatan</div>
                    </div>
                    @if($item->status == 'menunggu_dosen')
                        <span class="badge-menunggu">Menunggu Verifikasi</span>
                    @elseif($item->status == 'revisi')
                        <span class="badge-revisi">Butuh Revisi</span>
                    @else
                        <span class="inline-block bg-[#f3f4f6] text-[#4b5563] text-[13px] font-semibold px-4 py-1.5 rounded-full" style="background-color: #f7f6f2; color: #55615b; font-size: 0.8rem; font-weight: 600; padding: 0.4rem 0.85rem; border-radius: 2rem;">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <div class="text-secondary small fw-semibold">Ruangan / Fasilitas:</div>
                    <div class="text-main fw-semibold">{{ $item->nama_fasilitas_with_type }}</div>
                </div>
                <div class="mb-4">
                    <div class="text-secondary small fw-semibold">Tanggal & Waktu:</div>
                    <div class="text-main fw-semibold small">{{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} · {{ $item->jam_mulai ? str_replace(':', '.', substr($item->jam_mulai, 0, 5)) : '08.00' }} - {{ $item->jam_selesai ? str_replace(':', '.', substr($item->jam_selesai, 0, 5)) : '12.00' }}</div>
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
                            <span class="text-muted small d-block">Keterangan / Deskripsi Kegiatan</span>
                            <span class="fw-semibold text-main">{{ $item->keterangan ?: 'Tidak ada keterangan tambahan.' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted small d-block">Fasilitas</span>
                            <span class="fw-semibold text-main">{{ $item->nama_fasilitas_with_type }}</span>
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
                            <span class="text-muted small d-block">Waktu</span>
                            <span class="fw-semibold text-main">{{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} · {{ $item->jam_mulai ? str_replace(':', '.', substr($item->jam_mulai, 0, 5)) : '08.00' }} - {{ $item->jam_selesai ? str_replace(':', '.', substr($item->jam_selesai, 0, 5)) : '12.00' }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Checklist Verifikasi Dosen</label>
                            @if($item->jenis_peminjaman == 'barang')
                                <div class="d-flex align-items-center mb-2">
                                    <input class="form-check-input mt-0" type="checkbox" id="check1_{{ $item->id_peminjaman }}" required>
                                    <label class="form-check-label text-main fw-semibold ms-2" for="check1_{{ $item->id_peminjaman }}">Kebutuhan barang sesuai dengan kegiatan akademik/proyek</label>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <input class="form-check-input mt-0" type="checkbox" id="check2_{{ $item->id_peminjaman }}" required>
                                    <label class="form-check-label text-main fw-semibold ms-2" for="check2_{{ $item->id_peminjaman }}">Jumlah dan jenis barang masuk akal dan diperlukan mahasiswa</label>
                                </div>
                            @else
                                <div class="d-flex align-items-center mb-2">
                                    <input class="form-check-input mt-0" type="checkbox" id="check1_{{ $item->id_peminjaman }}" required>
                                    <label class="form-check-label text-main fw-semibold ms-2" for="check1_{{ $item->id_peminjaman }}">Kegiatan mahasiswa sesuai dengan ranah akademik/kemahasiswaan</label>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <input class="form-check-input mt-0" type="checkbox" id="check2_{{ $item->id_peminjaman }}" required>
                                    <label class="form-check-label text-main fw-semibold ms-2" for="check2_{{ $item->id_peminjaman }}">Waktu pelaksanaan tidak mengganggu kegiatan belajar mengajar rutin</label>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Catatan Dosen</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan akademik..." style="border-radius: 0.75rem; border-color: #dfd4c8;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 d-flex gap-2">
                        <button type="submit" onclick="document.getElementById('statusField{{ $item->id_peminjaman }}').value='verif_dosen'; document.getElementById('check1_{{ $item->id_peminjaman }}').required=true; document.getElementById('check2_{{ $item->id_peminjaman }}').required=true;" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block" style="border-radius:0.75rem;">Setujui</button>
                        <button type="submit" onclick="document.getElementById('statusField{{ $item->id_peminjaman }}').value='revisi'; document.getElementById('check1_{{ $item->id_peminjaman }}').required=false; document.getElementById('check2_{{ $item->id_peminjaman }}').required=false;" class="btn btn-warning text-white flex-grow-1" style="border-radius:0.75rem; background-color:#dca134; border:none;">Minta Revisi</button>
                        <button type="submit" onclick="document.getElementById('statusField{{ $item->id_peminjaman }}').value='ditolak'; document.getElementById('check1_{{ $item->id_peminjaman }}').required=false; document.getElementById('check2_{{ $item->id_peminjaman }}').required=false;" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-semibold transition inline-block" style="border-radius:0.75rem; background-color:#c95b50; border:none;">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <div class="card p-5 border-0 shadow-none text-secondary" style="background:#fffdfa; border-radius:1.5rem; border: 1px solid var(--line) !important;">
            <p class="mb-0 fw-semibold text-muted">Permohonan peminjaman tidak ada.</p>
        </div>
    </div>
    @endforelse
</div>
@endsection
