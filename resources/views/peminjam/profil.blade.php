@extends('layout.app_tailwind')



@section('content')
            <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 mb-6">
                <div>
                    <div class="text-[#7b8681] text-[20px] mb-1">Peminjam</div>
                    <h1 class="text-[24px] font-medium m-0">Profil Peminjam</h1>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <input type="text" class="h-12 px-4 rounded-2xl border border-[#ddd2c5] bg-[#fffdfa] focus:outline-none focus:border-[#466454] w-full md:w-64 transition" placeholder="Cari data">
                    <div class="w-12 h-12 bg-[#cfdacd] rounded-full flex shrink-0"></div>
                </div>
            </div>

            <div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[28px] p-6 lg:p-8 mb-6 relative overflow-hidden">
                <div class="relative z-10 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Kelola biodata dan akun peminjam</h2>
                        <p class="mb-0 text-[#5f6963]">
                            Halaman ini menampilkan informasi profil, data akademik, kontak, dan ringkasan aktivitas peminjaman peminjam.
                        </p>
                    </div>
                    <button class="h-12 px-6 bg-[#5d7d6b] hover:bg-[#496454] text-white font-semibold rounded-2xl transition border-0 cursor-pointer shadow-sm shrink-0 whitespace-nowrap">Edit Profil</button>
                </div>
                <!-- Decorative shapes -->
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/40 rounded-full blur-2xl"></div>
                <div class="absolute right-20 -bottom-10 w-32 h-32 bg-[#d6e5d6]/60 rounded-full blur-xl"></div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                <div class="xl:col-span-1">
                    <div class="text-center mb-6">
                        <div class="w-[140px] h-[180px] bg-[#d7e1d7] rounded-full mx-auto mb-4 overflow-hidden border-4 border-white shadow-sm">
                            <!-- Avatar placeholder -->
                        </div>
                        <div class="font-bold text-[#33403b] text-lg mb-1">{{ auth()->user()->nama_lengkap }}</div>
                        <div class="text-[#7b8681] text-sm mb-3">Peminjam TRPL</div>
                        <span class="inline-flex items-center justify-center min-w-[120px] h-8 rounded-full bg-[#e5eee5] text-[#557b58] border border-[#d2dfd2] text-sm font-semibold">Aktif</span>
                    </div>

                    <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6">
                        <div class="mb-4 pb-4 border-b border-[#e2d9ce]">
                            <div class="text-[#7b8681] text-sm font-semibold mb-1">NIM</div>
                            <div class="text-[#33403b] font-medium">{{ auth()->user()->nim ?? '4342511024' }}</div>
                        </div>

                        <div class="mb-4 pb-4 border-b border-[#e2d9ce]">
                            <div class="text-[#7b8681] text-sm font-semibold mb-1">Program Studi</div>
                            <div class="text-[#33403b] font-medium">Teknologi Rekayasa Perangkat Lunak</div>
                        </div>

                        <div class="mb-4 pb-4 border-b border-[#e2d9ce]">
                            <div class="text-[#7b8681] text-sm font-semibold mb-1">Email</div>
                            <div class="text-[#33403b] font-medium truncate" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</div>
                        </div>

                        <div>
                            <div class="text-[#7b8681] text-sm font-semibold mb-1">Nomor HP</div>
                            <div class="text-[#33403b] font-medium">08xx-xxxx-xxxx</div>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-2">
                    <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Data Diri</div>

                    <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">Nama Lengkap</label>
                                <input type="text" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] text-[#4d5852] focus:outline-none transition" value="{{ auth()->user()->nama_lengkap }}" readonly>
                            </div>
                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">NIM</label>
                                <input type="text" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] text-[#4d5852] focus:outline-none transition" value="{{ auth()->user()->nim ?? '4342511024' }}" readonly>
                            </div>
                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">Tempat, Tanggal Lahir</label>
                                <input type="text" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] text-[#4d5852] focus:outline-none transition" value="Batam, 12 April 2004" readonly>
                            </div>
                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">Jenis Kelamin</label>
                                <input type="text" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] text-[#4d5852] focus:outline-none transition" value="Laki-laki" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Kontak & Keamanan</div>

                    <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6">
                        <div class="mb-4">
                            <label class="block text-[#5c6761] font-semibold mb-2">Email</label>
                            <input type="text" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] text-[#4d5852] focus:outline-none transition" value="{{ auth()->user()->email }}" readonly>
                        </div>

                        <div class="mb-5">
                            <label class="block text-[#5c6761] font-semibold mb-2">Password</label>
                            <input type="password" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] text-[#4d5852] focus:outline-none transition" value="123456789" readonly>
                        </div>

                        <button class="h-12 px-6 bg-[#fffdfa] hover:bg-[#f5f2ec] text-[#5f6963] border border-[#dfd4c8] font-semibold rounded-2xl transition cursor-pointer">Ubah Password</button>
                    </div>
                </div>

                <div class="xl:col-span-1">
                    <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Ringkasan Aktivitas</div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-[#fffcf8] border border-[#e3d9cd] rounded-[20px] p-4 text-center">
                            <div class="text-[#7b8681] text-sm font-semibold">Pengajuan</div>
                            <div class="text-[28px] font-bold text-[#33403b] mt-1">8</div>
                        </div>
                        <div class="bg-[#fffcf8] border border-[#e3d9cd] rounded-[20px] p-4 text-center">
                            <div class="text-[#7b8681] text-sm font-semibold">Disetujui</div>
                            <div class="text-[28px] font-bold text-[#33403b] mt-1">5</div>
                        </div>
                        <div class="bg-[#fffcf8] border border-[#e3d9cd] rounded-[20px] p-4 text-center">
                            <div class="text-[#7b8681] text-sm font-semibold">Pengembalian</div>
                            <div class="text-[28px] font-bold text-[#33403b] mt-1">4</div>
                        </div>
                        <div class="bg-[#fffcf8] border border-[#e3d9cd] rounded-[20px] p-4 text-center">
                            <div class="text-[#7b8681] text-sm font-semibold">Notifikasi</div>
                            <div class="text-[28px] font-bold text-[#33403b] mt-1">3</div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
