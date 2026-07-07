@extends('layout.app_tailwind')



@section('content')
            @if(session('success'))
                <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-2xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-6">
                <div>
                    <div class="text-[19px] text-[#7d8781] mb-1">Peminjam</div>
                    <h1 class="text-[28px] font-normal m-0 text-[#33403b]">Dashboard Peminjam</h1>
                </div>

                <div class="flex items-center gap-3.5 w-full md:w-auto relative">
                    <i class="bi bi-search absolute left-4 text-[#a5a5a5]"></i>
                    <input type="text" class="w-full md:w-[250px] h-[48px] border border-[#ddd2c5] rounded-2xl pl-10 pr-4 outline-none bg-[#fffdfa] text-[#33403b] focus:ring-2 focus:ring-[#587a68]/20 transition" placeholder="Cari fasilitas atau ID">
                    <div class="w-[38px] h-[38px] rounded-full bg-[#ccd8cc] shrink-0 flex items-center justify-center text-white cursor-pointer hover:bg-[#b5c4b5] transition shadow-sm">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-[1fr_290px] gap-7">
                <div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[28px] p-6 md:p-[30px] min-h-[176px] flex flex-col justify-center relative overflow-hidden shadow-sm">
                    <!-- Decorative element for glassmorphism / modern vibe -->
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/40 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-10 right-20 w-32 h-32 bg-[#587a68]/10 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="text-[24px] md:text-[31px] font-extrabold mb-2 text-[#33403b] relative z-10">Halo, <span class="text-[#587a68]">{{ auth()->user()->nama_lengkap }}</span> 👋</div>
                    <div class="text-[#55635d] leading-[1.8] max-w-[700px] mb-6 relative z-10">
                        Kelola peminjaman ruangan dan fasilitas kampus dari satu dashboard yang sederhana dan mudah dipantau.
                    </div>
                    <div class="flex gap-3.5 flex-col md:flex-row relative z-10">
                        <a href="{{ route('peminjam.pengajuan') }}" class="min-w-[190px] h-[50px] rounded-2xl font-bold bg-[#587a68] text-white flex items-center justify-center no-underline hover:bg-[#466454] transition shadow-md hover:shadow-lg hover:-translate-y-0.5">
                            <i class="bi bi-plus-lg mr-2"></i> Ajukan Peminjaman
                        </a>
                        <a href="{{ route('peminjam.jadwal') }}" class="min-w-[190px] h-[50px] rounded-2xl font-bold border border-[#d8cfc2] bg-[#fffdfa] text-[#6b756f] flex items-center justify-center no-underline hover:bg-[#f8f4ee] hover:text-[#4f5954] transition shadow-sm hover:shadow">
                            <i class="bi bi-calendar-week mr-2"></i> Lihat Jadwal
                        </a>
                    </div>
                </div>

                <div>
                    <div class="text-[15px] font-bold mt-1 mb-3.5 text-[#52605a]">Jadwal Terdekat</div>
                    @if($jadwalTerdekat)
                    <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[22px] p-[22px] shadow-sm hover:shadow-md transition">
                        <div class="text-[16px] mb-3 text-[#5a615e] font-medium flex items-center gap-2">
                            <i class="bi bi-geo-alt text-[#587a68]"></i>
                            {{ $jadwalTerdekat->jenis_peminjaman === 'ruangan' ? ($jadwalTerdekat->ruangan->first()->nama_ruangan ?? 'Ruangan') : ($jadwalTerdekat->barang->first()->nama_barang ?? 'Barang') }}
                        </div>
                        <div class="text-[15px] text-[#4d5953] mb-3.5 flex items-center gap-2">
                            <i class="bi bi-clock"></i>
                            {{ \Carbon\Carbon::parse($jadwalTerdekat->tanggal_pengajuan)->translatedFormat('d M Y') }} · 
                            {{ \Carbon\Carbon::parse($jadwalTerdekat->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwalTerdekat->jam_selesai)->format('H:i') }}
                        </div>
                        <span class="inline-flex items-center justify-center min-w-[116px] px-4 py-2 rounded-full text-[14px] font-bold bg-[#dcebd7] text-[#557b58] border border-[#b7d2b6]">
                            Disetujui
                        </span>
                    </div>
                    @else
                    <div class="bg-transparent border border-dashed border-[#dfe7dc] rounded-[22px] p-[22px] text-center text-gray-500">
                        Belum ada jadwal terdekat.
                    </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[18px] my-8">
                <div class="py-2.5 px-4 bg-white/50 backdrop-blur-sm border border-[#e6ddd2] rounded-2xl hover:-translate-y-1 transition duration-300">
                    <div class="text-[15px] text-[#8a948e] mb-2 font-medium">Pengajuan Aktif</div>
                    <div class="text-[48px] md:text-[64px] leading-none text-[#31413a] font-light">{{ $pengajuanAktif }}</div>
                </div>
                <div class="py-2.5 px-4 bg-white/50 backdrop-blur-sm border border-[#e6ddd2] rounded-2xl hover:-translate-y-1 transition duration-300">
                    <div class="text-[15px] text-[#8a948e] mb-2 font-medium">Menunggu Persetujuan</div>
                    <div class="text-[48px] md:text-[64px] leading-none text-[#31413a] font-light">{{ $menungguPersetujuan }}</div>
                </div>
                <div class="py-2.5 px-4 bg-white/50 backdrop-blur-sm border border-[#e6ddd2] rounded-2xl hover:-translate-y-1 transition duration-300">
                    <div class="text-[15px] text-[#8a948e] mb-2 font-medium">Riwayat Selesai</div>
                    <div class="text-[48px] md:text-[64px] leading-none text-[#31413a] font-light">{{ $riwayatSelesai }}</div>
                </div>
                <div class="py-2.5 px-4 bg-white/50 backdrop-blur-sm border border-[#e6ddd2] rounded-2xl hover:-translate-y-1 transition duration-300">
                    <div class="text-[15px] text-[#8a948e] mb-2 font-medium">Notifikasi Baru</div>
                    <div class="text-[48px] md:text-[64px] leading-none text-[#31413a] font-light">{{ $notifikasiBaru }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-[420px_1fr] gap-[34px]">
                <div>
                    <div class="text-[15px] font-bold mt-1 mb-3.5 text-[#52605a]">Aksi Cepat</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-[18px]">
                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[22px] p-6 md:p-7 min-h-[116px] hover:shadow-md transition cursor-pointer group hover:-translate-y-1">
                            <a href="{{ route('peminjam.pengajuan') }}" class="no-underline block h-full">
                                <div class="text-[16px] text-[#5e6762] mb-3 group-hover:text-[#587a68] transition flex items-center gap-2 font-semibold">
                                    <i class="bi bi-plus-circle"></i> Ajukan Peminjaman
                                </div>
                                <div class="text-[14px] text-[#8a928d] leading-[1.6]">Buat pengajuan baru</div>
                            </a>
                        </div>
                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[22px] p-6 md:p-7 min-h-[116px] hover:shadow-md transition cursor-pointer group hover:-translate-y-1">
                            <a href="{{ route('peminjam.jadwal') }}" class="no-underline block h-full">
                                <div class="text-[16px] text-[#5e6762] mb-3 group-hover:text-[#587a68] transition flex items-center gap-2 font-semibold">
                                    <i class="bi bi-search"></i> Cek Ketersediaan
                                </div>
                                <div class="text-[14px] text-[#8a928d] leading-[1.6]">Lihat slot fasilitas</div>
                            </a>
                        </div>
                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[22px] p-6 md:p-7 min-h-[116px] hover:shadow-md transition cursor-pointer group hover:-translate-y-1">
                            <a href="{{ route('peminjam.riwayat') }}" class="no-underline block h-full">
                                <div class="text-[16px] text-[#5e6762] mb-3 group-hover:text-[#587a68] transition flex items-center gap-2 font-semibold">
                                    <i class="bi bi-clock-history"></i> Status Pengajuan
                                </div>
                                <div class="text-[14px] text-[#8a928d] leading-[1.6]">Pantau progres verifikasi</div>
                            </a>
                        </div>
                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[22px] p-6 md:p-7 min-h-[116px] hover:shadow-md transition cursor-pointer group hover:-translate-y-1">
                            <a href="{{ route('peminjam.pengembalian') }}" class="no-underline block h-full">
                                <div class="text-[16px] text-[#5e6762] mb-3 group-hover:text-[#587a68] transition flex items-center gap-2 font-semibold">
                                    <i class="bi bi-arrow-return-left"></i> Ajukan Pengembalian
                                </div>
                                <div class="text-[14px] text-[#8a928d] leading-[1.6]">Submit return fasilitas</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-[15px] font-bold mt-1 mb-3.5 text-[#52605a]">Status Pengajuan Terbaru</div>
                    <div class="grid gap-[18px]">
                        @forelse($pengajuanTerbaru as $pengajuan)
                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[22px] p-5 md:p-[22px] hover:shadow-sm transition">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-3">
                                <div>
                                    <div class="text-[16px] text-[#707975] mb-2 font-medium">
                                        SBUM-2026-{{ str_pad($pengajuan->id_peminjaman, 4, '0', STR_PAD_LEFT) }} <span class="mx-1">•</span> 
                                        {{ $pengajuan->jenis_peminjaman === 'ruangan' ? ($pengajuan->ruangan->first()->nama_ruangan ?? 'Ruangan') : ($pengajuan->barang->first()->nama_barang ?? 'Barang') }}
                                    </div>
                                    <div class="text-[15px] text-[#4e5953]">{{ $pengajuan->nama_kegiatan }} <span class="mx-1">•</span> {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d M Y') }}</div>
                                </div>
                                @php
                                    $statusClass = 'bg-[#f3e7c8] text-[#92723c] border-[#e3c98b]';
                                    $statusText = 'Menunggu';
                                    if(in_array($pengajuan->status, ['menunggu_dosen', 'menunggu_admin', 'menunggu_kepala_sbum', 'menunggu_pic'])) {
                                        if($pengajuan->status == 'menunggu_dosen') $statusText = 'Menunggu Dosen';
                                        elseif($pengajuan->status == 'menunggu_admin') $statusText = 'Menunggu Admin';
                                        elseif($pengajuan->status == 'menunggu_kepala_sbum') $statusText = 'Menunggu Ka. SBUM';
                                        elseif($pengajuan->status == 'menunggu_pic') $statusText = 'Menunggu PIC';
                                    } elseif($pengajuan->status == 'disetujui' || $pengajuan->status == 'selesai') {
                                        $statusClass = 'bg-[#dcebd7] text-[#557b58] border-[#b7d2b6]';
                                        $statusText = 'Disetujui';
                                    } elseif($pengajuan->status == 'ditolak' || $pengajuan->status == 'batal') {
                                        $statusClass = 'bg-red-100 text-red-700 border-red-200';
                                        $statusText = $pengajuan->status == 'batal' ? 'Dibatalkan' : 'Ditolak';
                                    }
                                @endphp
                                <span class="inline-flex items-center justify-center px-4 py-2 rounded-full text-[13px] font-bold border {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </div>

                            <!-- Modern Progress Bar with Tailwind -->
                            <div class="grid grid-cols-3 gap-3.5 items-center mt-4">
                                <div class="h-2 rounded-full relative overflow-hidden bg-[#557a67]"></div>
                                <div class="h-2 rounded-full relative overflow-hidden {{ in_array($pengajuan->status, ['menunggu_admin', 'menunggu_kepala_sbum', 'menunggu_pic', 'disetujui', 'selesai']) ? 'bg-[#557a67]' : 'bg-[#d9d4cc]' }}"></div>
                                <div class="h-2 rounded-full relative overflow-hidden {{ in_array($pengajuan->status, ['menunggu_kepala_sbum', 'menunggu_pic', 'disetujui', 'selesai']) ? 'bg-[#557a67]' : 'bg-[#d9d4cc]' }}"></div>
                            </div>
                            <div class="grid grid-cols-3 gap-3.5 mt-2 text-[12px] md:text-[13px] font-medium text-[#7a847e]">
                                <span>Diajukan</span>
                                <span class="text-center">Dosen</span>
                                <span class="text-right">Admin</span>
                            </div>
                        </div>
                        @empty
                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[22px] p-[22px] flex items-center justify-center min-h-[120px]">
                            <p class="text-[#7c8580] m-0">Belum ada data pengajuan terbaru.</p>
                        </div>
                        @endforelse

                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[22px] p-5 mt-2 bg-gradient-to-r from-[#fffdfa] to-[#f5f9f6]">
                            <div class="text-[16px] text-[#5e6662] mb-2 font-bold flex items-center gap-2">
                                <i class="bi bi-info-circle text-[#587a68]"></i> Catatan Dashboard
                            </div>
                            <p class="text-[#7c8580] leading-relaxed m-0 text-[14px]">
                                Dashboard ini memantau aktivitas peminjaman dan ketersediaan fasilitas Anda secara <span class="font-bold">real-time</span>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
@endsection
