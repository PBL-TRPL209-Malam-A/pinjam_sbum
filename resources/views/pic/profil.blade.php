@extends('layout.app_tailwind')




@section('content')


<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div class="p-6 lg:p-8">
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Kelola biodata dan akun PIC</h2>
        <p class="text-[#7d8781] max-w-2xl mb-6">
            Halaman ini menampilkan informasi profil, data operasional, kontak, dan ringkasan aktivitas verifikasi kesiapan fasilitas ruangan Anda.
        </p>
        <button class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block" data-bs-toggle="modal" data-bs-target="#editProfilModal">Edit Profil</button>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Left Sidebar: Avatar & Details Summary -->
    <div class="col-lg-3">
        <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-8 text-center mb-6">
            <div class="profile-avatar-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-shield-fill-check text-secondary" style="font-size: 4rem;"></i>
            </div>
            <h3 class="text-xl font-bold text-[#466454] mb-1">{{ $user->nama_lengkap }}</h3>
            <div class="text-[#7d8781] text-sm mb-4">PIC Fasilitas</div>
            <div>
                <span class="bg-[#e2f0d9] text-[#385723] px-6 py-1.5 rounded-full text-sm font-semibold inline-block">Aktif</span>
            </div>

            <div class="text-left mt-6 text-sm">
                <div class="detail-list-item d-flex flex-column">
                    <span class="text-muted small">NIP</span>
                    <span class="font-semibold text-[#33403b]">{{ $user->nim }}</span>
                </div>
                <div class="detail-list-item d-flex flex-column">
                    <span class="text-muted small">Jabatan</span>
                    <span class="font-semibold text-[#33403b]">PIC Fasilitas / Ruangan</span>
                </div>
                <div class="detail-list-item d-flex flex-column">
                    <span class="text-muted small">Email</span>
                    <span class="font-semibold text-[#33403b]">{{ $user->email }}</span>
                </div>
                <div class="detail-list-item d-flex flex-column">
                    <span class="text-muted small">Nomor HP</span>
                    <span class="font-semibold text-[#33403b]">08xx-xxxx-xxxx</span>
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
                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Nama Lengkap</label>
                    <input type="text" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 h-12 focus:outline-none focus:border-[#466454]" value="{{ $user->nama_lengkap }}" readonly style="width: 100%;">
                </div>
                <div class="col-md-6">
                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">NIP</label>
                    <input type="text" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 h-12 focus:outline-none focus:border-[#466454]" value="{{ $user->nim }}" readonly style="width: 100%;">
                </div>
                <div class="col-md-6">
                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Tempat, Tanggal Lahir</label>
                    <input type="text" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 h-12 focus:outline-none focus:border-[#466454]" value="Batam, 12 April 2004" readonly style="width: 100%;">
                </div>
                <div class="col-md-6">
                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Jenis Kelamin</label>
                    <input type="text" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 h-12 focus:outline-none focus:border-[#466454]" value="Laki-laki" readonly style="width: 100%;">
                </div>
            </div>

            <div class="form-section-title">Kontak & Keamanan</div>
            <div>
                <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Email</label>
                <input type="email" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 h-12 focus:outline-none focus:border-[#466454]" value="{{ $user->email }}" readonly style="width:100%;">
            </div>
            <div>
                <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Password</label>
                <input type="password" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 h-12 focus:outline-none focus:border-[#466454]" value="12345678" readonly style="width:100%;">
            </div>
            <div>
                <button class="btn btn-edit-outline" data-bs-toggle="modal" data-bs-target="#editProfilModal" style="border: 1px solid var(--line); border-radius: 0.75rem; height:44px;">Ubah Password</button>
            </div>
        </div>
    </div>

    <!-- Right Column: Ringkasan Aktivitas -->
    <div class="w-full lg:w-1/3">
        <div class="form-section-title px-2">Ringkasan Aktivitas</div>
        <div class="row g-3">
            <div>
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-2xl p-4 text-center transition hover:-translate-y-0.5">
                    <div class="text-xs font-medium text-[#7d8781] leading-tight">Ruangan</div>
                    <div class="text-3xl font-bold text-[#466454]">1</div>
                </div>
            </div>
            <div>
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-2xl p-4 text-center transition hover:-translate-y-0.5">
                    <div class="text-xs font-medium text-[#7d8781] leading-tight">Kesiapan Selesai</div>
                    <div class="text-3xl font-bold text-[#466454]">8</div>
                </div>
            </div>
            <div>
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-2xl p-4 text-center transition hover:-translate-y-0.5">
                    <div class="text-xs font-medium text-[#7d8781] leading-tight">Kendala</div>
                    <div class="text-3xl font-bold text-[#466454]">1</div>
                </div>
            </div>
            <div>
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-2xl p-4 text-center transition hover:-translate-y-0.5">
                    <div class="text-xs font-medium text-[#7d8781] leading-tight">Total Log</div>
                    <div class="text-3xl font-bold text-[#466454]">10</div>
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
                <h5 class="modal-title text-xl font-bold text-[#466454]">Edit Profil & Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pic.profil.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div>
                        <label class="form-label text-secondary fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{ $user->nama_lengkap }}" required style="border-radius:0.75rem;">
                    </div>
                    <div>
                        <label class="form-label text-secondary fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required style="border-radius:0.75rem;">
                    </div>
                    <div>
                        <label class="form-label text-secondary fw-semibold">Password Baru (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control" style="border-radius:0.75rem;">
                    </div>
                    <div>
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
