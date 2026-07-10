@extends('layout.app_tailwind')




@section('content')


<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div>
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Kelola biodata dan akun admin</h2>
        <p class="text-[#7d8781] max-w-2xl mb-6">
            Halaman ini menampilkan informasi profil, data operasional, kontak, dan ringkasan aktivitas admin.
        </p>
        <button class="inline-flex items-center justify-center bg-[#466454] text-white px-5 py-2.5 rounded-[14px] font-semibold hover:bg-[#395244] transition" data-bs-toggle="modal" data-bs-target="#editProfilModal">Edit Profil</button>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Left Sidebar: Avatar & Details Summary -->
    <div class="lg:w-1/4 w-full">
        <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-8 text-center">
            <div class="w-[140px] h-[140px] rounded-full bg-[#dfe7df] mx-auto mb-5 flex items-center justify-center">
                <i class="bi bi-person-fill text-[#7d8781] text-[4rem]"></i>
            </div>
            <h3 class="text-xl font-bold text-[#466454] mb-1">{{ $user->nama_lengkap }}</h3>
            <div class="text-[#7d8781] text-sm mb-4">Administrator SBUM</div>
            <div>
                <span class="bg-[#e2f0d9] text-[#385723] text-sm font-semibold px-8 py-1.5 rounded-full inline-block">Aktif</span>
            </div>

            <div class="text-left mt-6 text-sm">
                <div class="flex flex-col border-b border-[#f0e9df] py-3 last:border-0">
                    <span class="text-[#7d8781] text-xs mb-1">NIP</span>
                    <span class="font-semibold text-[#466454]">{{ $user->nim }}</span>
                </div>
                <div class="flex flex-col border-b border-[#f0e9df] py-3 last:border-0">
                    <span class="text-[#7d8781] text-xs mb-1">Jabatan</span>
                    <span class="font-semibold text-[#466454]">Administrator SBUM</span>
                </div>
                <div class="flex flex-col border-b border-[#f0e9df] py-3 last:border-0">
                    <span class="text-[#7d8781] text-xs mb-1">Email</span>
                    <span class="font-semibold text-[#466454]">{{ $user->email }}</span>
                </div>
                <div class="flex flex-col border-b border-[#f0e9df] py-3 last:border-0">
                    <span class="text-[#7d8781] text-xs mb-1">Nomor HP</span>
                    <span class="font-semibold text-[#466454]">08xx-xxxx-xxxx</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Center Column: Data Diri Form fields -->
    <div class="lg:w-5/12 w-full">
        <div class="bg-transparent border border-[#e6ddd2] rounded-[24px] p-6">
            <div class="font-semibold text-[#7d8781] text-[15px] mb-4">Data Diri</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Nama Lengkap</label>
                    <input type="text" class="w-full bg-[#fffdfa] border border-[#dfd4c8] text-[#33403b] rounded-xl px-4 py-2 h-12 focus:outline-none" value="{{ $user->nama_lengkap }}" readonly>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">NIP</label>
                    <input type="text" class="w-full bg-[#fffdfa] border border-[#dfd4c8] text-[#33403b] rounded-xl px-4 py-2 h-12 focus:outline-none" value="{{ $user->nim }}" readonly>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Tempat, Tanggal Lahir</label>
                    <input type="text" class="w-full bg-[#fffdfa] border border-[#dfd4c8] text-[#33403b] rounded-xl px-4 py-2 h-12 focus:outline-none" value="Batam, 12 April 2004" readonly>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Jenis Kelamin</label>
                    <input type="text" class="w-full bg-[#fffdfa] border border-[#dfd4c8] text-[#33403b] rounded-xl px-4 py-2 h-12 focus:outline-none" value="Laki-laki" readonly>
                </div>
            </div>

            <div class="font-semibold text-[#7d8781] text-[15px] mb-4">Kontak & Keamanan</div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Email</label>
                <input type="email" class="w-full bg-[#fffdfa] border border-[#dfd4c8] text-[#33403b] rounded-xl px-4 py-2 h-12 focus:outline-none" value="{{ $user->email }}" readonly >
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Password</label>
                <input type="password" class="w-full bg-[#fffdfa] border border-[#dfd4c8] text-[#33403b] rounded-xl px-4 py-2 h-12 focus:outline-none" value="12345678" readonly >
            </div>
            <div>
                <button class="border border-[#e6ddd2] text-[#466454] px-5 py-2.5 rounded-[14px] font-semibold hover:bg-[#f5f2ec] transition h-12 w-full text-center" data-bs-toggle="modal" data-bs-target="#editProfilModal" >Ubah Password</button>
            </div>
        </div>
    </div>

    <!-- Right Column: Ringkasan Aktivitas -->
    <div class="lg:w-1/3 w-full">
        <div class="font-semibold text-[#7d8781] text-[15px] mb-4 px-2">Ringkasan Aktivitas</div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-2xl p-4 text-center hover:-translate-y-1 transition duration-200">
                    <div class="text-xs text-[#7d8781] font-medium mb-1">Pengajuan</div>
                    <div class="text-2xl font-bold text-[#466454]">18</div>
                </div>
            </div>
            <div>
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-2xl p-4 text-center hover:-translate-y-1 transition duration-200">
                    <div class="text-xs text-[#7d8781] font-medium mb-1">Disetujui</div>
                    <div class="text-2xl font-bold text-[#466454]">12</div>
                </div>
            </div>
            <div>
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-2xl p-4 text-center hover:-translate-y-1 transition duration-200">
                    <div class="text-xs text-[#7d8781] font-medium mb-1">Ditolak</div>
                    <div class="text-2xl font-bold text-[#466454]">3</div>
                </div>
            </div>
            <div>
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-2xl p-4 text-center hover:-translate-y-1 transition duration-200">
                    <div class="text-xs text-[#7d8781] font-medium mb-1">Pengembalian</div>
                    <div class="text-2xl font-bold text-[#466454]">5</div>
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
                <h5 class="modal-title font-bold text-[#466454] text-lg">Edit Profil & Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.profil.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $user->nama_lengkap }}" required >
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Email</label>
                        <input type="email" name="email" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" value="{{ $user->email }}" required >
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Password Baru (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" >
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" >
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="border border-[#e6ddd2] text-[#7d8781] px-5 py-2.5 rounded-xl font-semibold hover:bg-[#f5f2ec] transition" data-bs-dismiss="modal" >Batal</button>
                    <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block" >Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
