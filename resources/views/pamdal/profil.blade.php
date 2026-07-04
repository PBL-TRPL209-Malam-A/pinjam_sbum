@extends('layout.app_tailwind')




@section('content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .profile-avatar-circle {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background-color: #dfe7df;
        margin: 0 auto 1.25rem;
    }
    .badge-aktif {
        background-color: #e2f0d9;
        color: #385723;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 2rem;
        border-radius: 2rem;
        display: inline-block;
    }
    .profile-sidebar {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 2rem 1.5rem;
        text-align: center;
    }
    .detail-list {
        text-align: left;
        margin-top: 1.5rem;
        font-size: 0.9rem;
    }
    .detail-list-item {
        border-bottom: 1px solid #f0e9df;
        padding: 0.75rem 0;
    }
    .detail-list-item:last-child {
        border-bottom: 0;
    }
    .form-section-title {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }
    .profile-input {
        background-color: #fffdfa;
        border: 1px solid #dfd4c8;
        border-radius: 0.75rem;
        height: 48px;
        padding: 0 1rem;
        color: var(--text-main);
        font-weight: 500;
        font-size: 0.9rem;
    }
    .stat-box {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1rem;
        padding: 1rem;
        text-align: center;
        transition: 0.2s;
    }
    .stat-box:hover {
        transform: translateY(-2px);
    }
    .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    .stat-num {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="p-6 lg:p-8">
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Kelola biodata dan akun Pamdal</h2>
        <p class="mb-4 text-secondary">
            Halaman ini menampilkan informasi profil, data operasional, kontak, dan ringkasan aktivitas pengawasan keamanan dan ketertiban.
        </p>
        <button class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block" data-bs-toggle="modal" data-bs-target="#editProfilModal">Edit Profil</button>
    </div>
</div>

<div class="row g-4">
    <!-- Left Sidebar: Avatar & Details Summary -->
    <div class="col-lg-3">
        <div class="profile-sidebar">
            <div class="profile-avatar-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-shield-lock-fill text-secondary" style="font-size: 4rem;"></i>
            </div>
            <h3 class="fs-5 fw-bold text-main mb-1">{{ $user->nama_lengkap }}</h3>
            <div class="text-secondary small mb-3">Petugas Pamdal</div>
            <div>
                <span class="badge-aktif">Aktif</span>
            </div>

            <div class="detail-list">
                <div class="detail-list-item d-flex flex-column">
                    <span class="text-muted small">NIP</span>
                    <span class="fw-semibold text-main">{{ $user->nim }}</span>
                </div>
                <div class="detail-list-item d-flex flex-column">
                    <span class="text-muted small">Jabatan</span>
                    <span class="fw-semibold text-main">Petugas Pengamanan Dalam</span>
                </div>
                <div class="detail-list-item d-flex flex-column">
                    <span class="text-muted small">Email</span>
                    <span class="fw-semibold text-main">{{ $user->email }}</span>
                </div>
                <div class="detail-list-item d-flex flex-column">
                    <span class="text-muted small">Nomor HP</span>
                    <span class="fw-semibold text-main">08xx-xxxx-xxxx</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Center Column: Data Diri Form fields -->
    <div class="col-lg-5">
        <div class="card border-0 rounded-4 p-4 shadow-none" style="background: transparent; border: 1px solid var(--line) !important;">
            <div class="form-section-title">Data Diri</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control profile-input" value="{{ $user->nama_lengkap }}" readonly style="width: 100%;">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-semibold">NIP</label>
                    <input type="text" class="form-control profile-input" value="{{ $user->nim }}" readonly style="width: 100%;">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-semibold">Tempat, Tanggal Lahir</label>
                    <input type="text" class="form-control profile-input" value="Batam, 12 April 2004" readonly style="width: 100%;">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary small fw-semibold">Jenis Kelamin</label>
                    <input type="text" class="form-control profile-input" value="Laki-laki" readonly style="width: 100%;">
                </div>
            </div>

            <div class="form-section-title">Kontak & Keamanan</div>
            <div class="mb-3">
                <label class="form-label text-secondary small fw-semibold">Email</label>
                <input type="email" class="form-control profile-input" value="{{ $user->email }}" readonly style="width:100%;">
            </div>
            <div class="mb-3">
                <label class="form-label text-secondary small fw-semibold">Password</label>
                <input type="password" class="form-control profile-input" value="12345678" readonly style="width:100%;">
            </div>
            <div>
                <button class="btn btn-edit-outline" data-bs-toggle="modal" data-bs-target="#editProfilModal" style="border: 1px solid var(--line); border-radius: 0.75rem; height:44px;">Ubah Password</button>
            </div>
        </div>
    </div>

    <!-- Right Column: Ringkasan Aktivitas -->
    <div class="col-lg-4">
        <div class="form-section-title px-2">Ringkasan Aktivitas</div>
        <div class="row g-3">
            <div class="col-6">
                <div class="stat-box">
                    <div class="text-xs font-medium text-[#7d8781] leading-tight">Hari Kerja</div>
                    <div class="text-3xl font-bold text-[#466454]">24</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-box">
                    <div class="text-xs font-medium text-[#7d8781] leading-tight">Pengawasan Selesai</div>
                    <div class="text-3xl font-bold text-[#466454]">14</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-box">
                    <div class="text-xs font-medium text-[#7d8781] leading-tight">Kendala</div>
                    <div class="text-3xl font-bold text-[#466454]">2</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-box">
                    <div class="text-xs font-medium text-[#7d8781] leading-tight">Total Log</div>
                    <div class="text-3xl font-bold text-[#466454]">16</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile & Password Modal -->
<div class="modal fade" id="editProfilModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background-color: #fffdfa;">
            <div class="modal-header border-0 pb-0" style="background-color: #f7f3eb; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title fw-bold text-main">Edit Profil & Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pamdal.profil.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{ $user->nama_lengkap }}" required style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Password Baru (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control" style="border-radius:0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" style="border-radius:0.75rem;">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn action-btn-outline" data-bs-dismiss="modal" style="border-radius:0.75rem;">Batal</button>
                    <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block" style="border-radius:0.75rem;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
