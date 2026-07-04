@extends('layout.app_tailwind')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-6">
    <div>
        <div class="text-[19px] text-[#7d8781] mb-1">Kepala SBUM</div>
        <h1 class="text-[32px] font-bold text-[#466454] leading-tight mb-2">Profil Kepala SBUM</h1>
    </div>
</div>



                <div class="card intro-card shadow-none mb-5">
                    <div class="p-6 lg:p-8">
                        <h2 class="text-xl font-semibold text-[#466454] mb-2">Kelola biodata dan akun Kepala SBUM</h2>
                        <p class="mb-4 text-secondary">
                            Halaman ini menampilkan informasi profil, data personal, kontak, dan ringkasan aktivitas operasional Kepala SBUM.
                        </p>
                        <button class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block">Edit Profil</button>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-xl-3">
                        <div class="profile-left">
                            <div class="text-center mb-4">
                                <div class="profile-avatar"></div>
                                <div class="fw-semibold mb-2">{{ auth()->user()->nama_lengkap }}</div>
                                <div class="text-secondary mb-3">Kepala SBUM</div>
                                <span class="status-badge-soft">Aktif</span>
                            </div>

                            <div class="mb-4">
                                <div class="text-secondary mb-2">NIP / ID</div>
                                <div>{{ auth()->user()->nim ?? 'K001' }}</div>
                                <div class="line-separator"></div>
                            </div>

                            <div class="mb-4">
                                <div class="text-secondary mb-2">Departemen</div>
                                <div>Sarana Prasarana & SBUM</div>
                                <div class="line-separator"></div>
                            </div>

                            <div class="mb-4">
                                <div class="text-secondary mb-2">Email</div>
                                <div>{{ auth()->user()->email }}</div>
                                <div class="line-separator"></div>
                            </div>

                            <div class="mb-4">
                                <div class="text-secondary mb-2">Nomor HP</div>
                                <div>08xx-xxxx-xxxx</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-5">
                        <div class="mb-4 fw-semibold text-secondary">Data Diri</div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control soft-input" value="{{ auth()->user()->nama_lengkap }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">NIP / ID</label>
                                <input type="text" class="form-control soft-input" value="{{ auth()->user()->nim ?? 'K001' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Tempat, Tanggal Lahir</label>
                                <input type="text" class="form-control soft-input" value="Batam, 12 April 1980" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Jenis Kelamin</label>
                                <input type="text" class="form-control soft-input" value="Laki-laki" readonly>
                            </div>
                        </div>

                        <div class="mb-4 fw-semibold text-secondary">Kontak & Keamanan</div>

                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Email</label>
                            <input type="text" class="form-control soft-input" value="{{ auth()->user()->email }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Password</label>
                            <input type="password" class="form-control soft-input" value="********" readonly>
                        </div>

                        <button class="btn btn-soft">Ubah Password</button>
                    </div>

                    <div class="col-xl-4">
                        <div class="mb-4 fw-semibold text-secondary">Ringkasan Aktivitas</div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="activity-card">
                                    <div class="activity-title">Staf Dikelola</div>
                                    <div class="activity-number">3</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="activity-card">
                                    <div class="activity-title">Persetujuan</div>
                                    <div class="activity-number">2</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="activity-card">
                                    <div class="activity-title">Laporan Peminjaman</div>
                                    <div class="activity-number">1</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="activity-card">
                                    <div class="activity-title">Laporan Pengembalian</div>
                                    <div class="activity-number">1</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
