@extends('layout.app_tailwind')



@section('content')
            <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 mb-6">
                <div>
                    <div class="text-[#7b8681] text-[20px] mb-1">Mahasiswa</div>
                    <h1 class="text-[24px] font-medium m-0">Riwayat Peminjaman & Bukti</h1>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <input type="text" class="h-12 px-4 rounded-2xl border border-[#ddd2c5] bg-[#fffdfa] focus:outline-none focus:border-[#466454] w-full md:w-64 transition" placeholder="Cari data">
                    <div class="w-12 h-12 bg-[#cfdacd] rounded-full flex shrink-0"></div>
                </div>
            </div>

            @if($peminjaman->isEmpty())
                <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-10 text-center text-[#7b8681]">
                    Belum ada riwayat peminjaman.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($peminjaman as $pjm)
                        @php
                            $facilityName = $pjm->ruangan->isNotEmpty() ? $pjm->ruangan->first()->nama_ruangan : ($pjm->barang->isNotEmpty() ? $pjm->barang->first()->nama_barang : 'Fasilitas');
                            
                            $statusClasses = 'bg-[#fff3cd] text-[#856404]'; // Default pending
                            $statusLabel = str_replace('_', ' ', Str::title($pjm->status));
                            
                            if(in_array($pjm->status, ['ditolak'])) {
                                $statusClasses = 'bg-[#f8d7da] text-[#721c24]';
                            } elseif(in_array($pjm->status, ['selesai', 'dibatalkan'])) {
                                $statusClasses = 'bg-[#e2e3e5] text-[#383d41]';
                            } elseif(in_array($pjm->status, ['siap_digunakan', 'disetujui'])) {
                                $statusClasses = 'bg-[#d4edda] text-[#155724]';
                            }
                        @endphp
                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6 transition hover:shadow-md hover:border-[#cfdacd] flex flex-col justify-between h-full">
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <div class="font-bold text-lg text-[#33403b]">{{ $facilityName }}</div>
                                    <div class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusClasses }}">{{ $statusLabel }}</div>
                                </div>
                                <div class="text-[#5f6963] mb-2 text-[15px]"><strong>ID:</strong> SBUM-2026-{{ str_pad($pjm->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-[#5f6963] mb-2 text-[15px]"><strong>Kegiatan:</strong> {{ $pjm->nama_kegiatan }}</div>
                                <div class="text-[#7b8681] text-[14px]">
                                    <i class="bi bi-calendar mr-1"></i> {{ $pjm->tanggal_pengajuan ? \Carbon\Carbon::parse($pjm->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }}<br>
                                    <i class="bi bi-clock mr-1"></i> {{ $pjm->jam_mulai ? substr($pjm->jam_mulai, 0, 5) : '-' }} - {{ $pjm->jam_selesai ? substr($pjm->jam_selesai, 0, 5) : '-' }}
                                </div>
                            </div>
                            
                            <div class="mt-5 pt-4 border-t border-[#e7ddd1] flex flex-wrap gap-3 justify-end">
                                @if(!in_array($pjm->status, ['ditolak', 'menunggu_dosen']))
                                    <a href="{{ route('mahasiswa.riwayat.pdf', $pjm->id_peminjaman) }}" target="_blank" class="inline-flex items-center gap-2 bg-[#5d7d6b] hover:bg-[#496454] text-white font-semibold rounded-xl px-4 py-2.5 transition no-underline text-sm">
                                        <i class="bi bi-file-earmark-pdf"></i> Cetak Bukti Peminjaman
                                    </a>
                                    @if($pjm->pengembalian)
                                        <a href="{{ route('mahasiswa.riwayat.pdf_pengembalian', $pjm->id_peminjaman) }}" target="_blank" class="inline-flex items-center gap-2 bg-[#3b4d44] hover:bg-[#2c3a33] text-white font-semibold rounded-xl px-4 py-2.5 transition no-underline text-sm">
                                            <i class="bi bi-file-earmark-pdf"></i> Cetak Bukti Pengembalian
                                        </a>
                                    @endif
                                @else
                                    <span class="text-[#7b8681] text-xs"><i class="bi bi-info-circle mr-1"></i> Bukti cetak belum tersedia untuk status ini</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
@endsection
