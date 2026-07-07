@extends('layout.app_tailwind')



@section('content')
            <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 mb-6">
                <div>
                    <div class="text-[#7b8681] text-[20px] mb-1">Peminjam</div>
                    <h1 class="text-[24px] font-medium m-0">Notifikasi Peminjam</h1>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <input type="text" class="h-12 px-4 rounded-2xl border border-[#ddd2c5] bg-[#fffdfa] focus:outline-none focus:border-[#466454] w-full md:w-64 transition" placeholder="Cari notifikasi">
                    <div class="w-12 h-12 bg-[#cfdacd] rounded-full flex shrink-0"></div>
                </div>
            </div>

            <div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[28px] p-6 lg:p-8 mb-6 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-xl font-semibold mb-2">Pantau informasi terbaru akun peminjam</h2>
                    <p class="mb-4 text-[#5f6963]">
                        Halaman ini menampilkan pemberitahuan penting terkait pengajuan, persetujuan, jadwal penggunaan, pengembalian, dan aktivitas akun peminjam.
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <button class="h-12 px-6 bg-[#5d7d6b] hover:bg-[#496454] text-white font-semibold rounded-2xl transition border-0 cursor-pointer shadow-sm">Tandai Sudah Dibaca</button>
                        <button class="h-12 px-6 bg-[#fffdfa] hover:bg-[#f5f2ec] text-[#5f6963] border border-[#dfd4c8] font-semibold rounded-2xl transition cursor-pointer">Lihat Semua</button>
                    </div>
                </div>
                <!-- Decorative shapes -->
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/40 rounded-full blur-2xl"></div>
                <div class="absolute right-20 -bottom-10 w-32 h-32 bg-[#d6e5d6]/60 rounded-full blur-xl"></div>
            </div>

            <div class="flex flex-wrap gap-2 mb-6">
                <span class="px-5 py-2.5 rounded-full border border-[#ddd2c5] bg-[#fffdfa] text-[#5f6963] font-medium text-sm transition hover:bg-[#edf2ea] cursor-pointer">Semua</span>
                <span class="px-5 py-2.5 rounded-full border border-[#ddd2c5] bg-[#fffdfa] text-[#5f6963] font-medium text-sm transition hover:bg-[#edf2ea] cursor-pointer">Persetujuan</span>
                <span class="px-5 py-2.5 rounded-full border border-[#ddd2c5] bg-[#fffdfa] text-[#5f6963] font-medium text-sm transition hover:bg-[#edf2ea] cursor-pointer">Jadwal</span>
                <span class="px-5 py-2.5 rounded-full border border-[#ddd2c5] bg-[#fffdfa] text-[#5f6963] font-medium text-sm transition hover:bg-[#edf2ea] cursor-pointer">Pengembalian</span>
                <span class="px-5 py-2.5 rounded-full border border-[#ddd2c5] bg-[#fffdfa] text-[#5f6963] font-medium text-sm transition hover:bg-[#edf2ea] cursor-pointer">Sistem</span>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2">
                    <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Daftar Notifikasi</div>

                    <div class="flex flex-col gap-4">
                        @forelse($notifikasi as $notif)
                            @php
                                $iconClasses = 'bg-[#e4ece8] text-[#4f6a5c] border-[#cfddd5]';
                                $icon = 'bi-info-circle-fill';
                                $title = 'Pemberitahuan';
                                $text = 'Status pengajuan Anda telah diperbarui menjadi: ' . str_replace('_', ' ', $notif->status);
                                $badgeClasses = 'bg-[#e4ece8] text-[#4f6a5c] border-[#cfddd5]';
                                $badgeText = 'Info';
                                
                                if(in_array($notif->status, ['menunggu_dosen', 'menunggu_admin', 'menunggu_kepala_sbum', 'menunggu_pic'])) {
                                    $iconClasses = 'bg-[#f4e7c9] text-[#92723c] border-[#e3c98b]';
                                    $icon = 'bi-hourglass-split';
                                    $title = 'Menunggu verifikasi';
                                    $text = 'Pengajuan untuk <strong>' . ($notif->jenis_peminjaman === 'ruangan' ? ($notif->ruangan->first()->nama_ruangan ?? 'Ruangan') : ($notif->barang->first()->nama_barang ?? 'Barang')) . '</strong> masih menunggu proses verifikasi.';
                                    $badgeClasses = 'bg-[#f4e7c9] text-[#92723c] border-[#e3c98b]';
                                    $badgeText = 'Warning';
                                } elseif($notif->status === 'disetujui') {
                                    $iconClasses = 'bg-[#dcebd7] text-[#557b58] border-[#b7d2b6]';
                                    $icon = 'bi-check-circle-fill';
                                    $title = 'Pengajuan disetujui';
                                    $text = 'Pengajuan peminjaman <strong>' . ($notif->jenis_peminjaman === 'ruangan' ? ($notif->ruangan->first()->nama_ruangan ?? 'Ruangan') : ($notif->barang->first()->nama_barang ?? 'Barang')) . '</strong> untuk kegiatan ' . $notif->nama_kegiatan . ' telah disetujui.';
                                    $badgeClasses = 'bg-[#dcebd7] text-[#557b58] border-[#b7d2b6]';
                                    $badgeText = 'Success';
                                } elseif($notif->status === 'ditolak') {
                                    $iconClasses = 'bg-[#f3dedd] text-[#a4534d] border-[#e1aba5]';
                                    $icon = 'bi-x-circle-fill';
                                    $title = 'Pengajuan ditolak';
                                    $text = 'Pengajuan <strong>' . ($notif->jenis_peminjaman === 'ruangan' ? ($notif->ruangan->first()->nama_ruangan ?? 'Ruangan') : ($notif->barang->first()->nama_barang ?? 'Barang')) . '</strong> ditolak.';
                                    $badgeClasses = 'bg-[#f3dedd] text-[#a4534d] border-[#e1aba5]';
                                    $badgeText = 'Danger';
                                } elseif($notif->status === 'selesai' || $notif->status === 'dikembalikan') {
                                    $iconClasses = 'bg-[#dcebd7] text-[#557b58] border-[#b7d2b6]';
                                    $icon = 'bi-arrow-repeat';
                                    $title = 'Pengembalian Selesai';
                                    $text = 'Fasilitas <strong>' . ($notif->jenis_peminjaman === 'ruangan' ? ($notif->ruangan->first()->nama_ruangan ?? 'Ruangan') : ($notif->barang->first()->nama_barang ?? 'Barang')) . '</strong> telah selesai dikembalikan.';
                                    $badgeClasses = 'bg-[#dcebd7] text-[#557b58] border-[#b7d2b6]';
                                    $badgeText = 'Success';
                                }
                            @endphp
                            <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-5 flex gap-4 transition hover:shadow-md hover:border-[#cfdacd]">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 text-xl {{ $iconClasses }} border">
                                    <i class="bi {{ $icon }}"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-3">
                                        <div>
                                            <div class="font-bold text-[#33403b] text-[17px] mb-1.5">{{ $title }}</div>
                                            <div class="text-[#69746f] text-[15px] leading-relaxed mb-2.5">
                                                {!! $text !!}
                                            </div>
                                        </div>
                                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $badgeClasses }} border shrink-0 text-center min-w-[90px]">{{ $badgeText }}</span>
                                    </div>
                                    <div class="text-[#8a938e] text-[13px]">{{ \Carbon\Carbon::parse($notif->tanggal_pengajuan)->diffForHumans() }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-8 text-center text-[#7b8681]">
                                Tidak ada notifikasi terbaru.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="xl:col-span-1">
                    <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Ringkasan Notifikasi</div>

                    <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6 mb-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-[#fffcf8] border border-[#e3d9cd] rounded-[16px] p-4 text-center">
                                <div class="text-[#7b8681] text-sm">Belum Dibaca</div>
                                <div class="text-3xl font-bold text-[#33403b] mt-2">{{ $belumDibaca }}</div>
                            </div>
                            <div class="bg-[#fffcf8] border border-[#e3d9cd] rounded-[16px] p-4 text-center">
                                <div class="text-[#7b8681] text-sm">Hari Ini</div>
                                <div class="text-3xl font-bold text-[#33403b] mt-2">{{ $hariIni }}</div>
                            </div>
                            <div class="bg-[#fffcf8] border border-[#e3d9cd] rounded-[16px] p-4 text-center">
                                <div class="text-[#7b8681] text-sm">Persetujuan</div>
                                <div class="text-3xl font-bold text-[#33403b] mt-2">{{ $persetujuan }}</div>
                            </div>
                            <div class="bg-[#fffcf8] border border-[#e3d9cd] rounded-[16px] p-4 text-center">
                                <div class="text-[#7b8681] text-sm">Jadwal</div>
                                <div class="text-3xl font-bold text-[#33403b] mt-2">{{ $jadwal }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6">
                        <div class="font-semibold text-lg text-[#33403b] mb-2">Catatan</div>
                        <div class="text-[#7b8681] text-[14.5px] leading-relaxed">
                            Notifikasi akan muncul otomatis saat ada perubahan status pengajuan, jadwal penggunaan, dan proses pengembalian fasilitas peminjam.
                        </div>
                    </div>
                </div>
            </div>
@endsection
