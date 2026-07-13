@extends('layout.app_tailwind')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-6">
    <div>
        <div class="text-[19px] text-[#7d8781] mb-1">Kepala SBUM</div>
        <h1 class="text-[32px] font-bold text-[#466454] leading-tight mb-2">Persetujuan Akhir Peminjaman</h1>
    </div>
</div>



                <div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-[#466454] mb-2">Final approval untuk pengajuan yang sudah diverifikasi</h2>
                    <p class="mb-0 text-[#54615b]">
                        Kepala SBUM memberikan persetujuan akhir setelah verifikasi dosen dan admin selesai.
                    </p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success border-0 mb-4" style="background:#edf5ee; color:#496454; border-radius:1rem;">
                        {{ session('success') }}
                    </div>
                @endif

                                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-[#466454]">Daftar Pengajuan Final</h2>
                    @if($peminjaman->count() > 0)
                    <form action="{{ route('kepalasbum.verifikasi-semua') }}" method="POST" class="inline" onsubmit="return confirm('Anda yakin ingin menyetujui SEMUA pengajuan peminjaman ini?');">
                        @csrf
                        <button type="submit" class="bg-[#557a67] text-white px-5 py-2 rounded-xl font-semibold hover:bg-[#466454] transition shadow-sm flex items-center gap-2">
                            <i class="bi bi-check2-all"></i> Setujui Semua
                        </button>
                    </form>
                    @endif
                </div>


                <div class="grid gap-4 mb-6">
                    @forelse($peminjaman as $p)
                    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
                        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="font-bold text-[#7d8781]">SBUM-2026-{{ str_pad($p->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</span>
                                    <span class="inline-block bg-[#fdf5e6] text-[#b8860b] border border-[#f5deb3] text-[13px] font-semibold px-3 py-1 rounded-full">Menunggu Final</span>
                                </div>
                                <h3 class="text-lg font-bold text-[#466454] mb-2">{{ $p->nama_kegiatan }}</h3>
                                <p class="mb-1 text-[#7d8781] text-sm">
                                    Peminjam: <strong class="text-[#33403b]">{{ $p->user->nama_lengkap ?? '-' }}</strong> - 
                                    <span>
                                        @if($p->ruangan->isNotEmpty())
                                            {{ $p->ruangan->first()->nama_ruangan }}
                                        @elseif($p->barang->isNotEmpty())
                                            {{ $p->barang->first()->nama_barang }}
                                        @else
                                            Fasilitas
                                        @endif
                                    </span>
                                </p>
                                <p class="mb-0 text-[#7d8781] text-sm">
                                    {{ $p->tanggal_pengajuan ? \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} - {{ $p->jam_mulai ? str_replace(':', '.', substr($p->jam_mulai, 0, 5)) : '08.00' }} - {{ $p->jam_selesai ? str_replace(':', '.', substr($p->jam_selesai, 0, 5)) : '12.00' }} - 
                                    <span class="text-green-600 font-medium">Sudah diverifikasi dosen dan admin</span>
                                </p>
                            </div>

                            <div class="w-full mt-4 border-t border-[#e6ddd2] pt-4">
                                <form action="{{ route('kepalasbum.verifikasi', $p->id_peminjaman) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label text-secondary fw-semibold text-sm">Keputusan Akhir <span class="text-danger">*</span></label>
                                        <div class="flex flex-col sm:flex-row gap-3 mt-1">
                                            <label class="flex items-center p-3 border rounded-xl flex-1 cursor-pointer bg-[#dcebd7]" style="border-color:#b7d2b6 !important;">
                                                <input type="radio" name="status_pengajuan" value="disetujui_kepala" class="form-check-input mt-0 me-3" required onchange="handleDecisionChange{{ $p->id_peminjaman }}(this.value)">
                                                <span class="fw-semibold text-[#466454]">Setujui</span>
                                            </label>
                                            <label class="flex items-center p-3 border rounded-xl flex-1 cursor-pointer bg-[#fdf0f0]" style="border-color:#f5c2c7 !important;">
                                                <input type="radio" name="status_pengajuan" value="ditolak" class="form-check-input mt-0 me-3" required onchange="handleDecisionChange{{ $p->id_peminjaman }}(this.value)">
                                                <span class="fw-semibold text-danger">Tolak</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3" id="rejectNoteContainer{{ $p->id_peminjaman }}">
                                        <label class="form-label text-secondary fw-semibold text-sm">Catatan Keputusan <span class="text-danger" id="catatan_req_{{ $p->id_peminjaman }}" style="display:none;">*</span></label>
                                        <textarea name="catatan" id="catatan_form_{{ $p->id_peminjaman }}" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" rows="2" placeholder="Tambahkan catatan (Wajib diisi jika ditolak)"></textarea>
                                    </div>
                                    
                                    <div class="flex gap-2 justify-between items-center mt-4">
                                        <button type="button" class="text-[#466454] font-semibold underline" data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->id_peminjaman }}">Lihat Detail</button>
                                        <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-5 py-2.5 rounded-xl font-semibold transition">Simpan Keputusan</button>
                                    </div>
                                </form>
                                <script>
                                    function handleDecisionChange{{ $p->id_peminjaman }}(value) {
                                        const reqIndicator = document.getElementById('catatan_req_{{ $p->id_peminjaman }}');
                                        const field = document.getElementById('catatan_form_{{ $p->id_peminjaman }}');
                                        if (value === 'ditolak') {
                                            reqIndicator.style.display = 'inline';
                                            field.required = true;
                                            field.classList.add('border-danger');
                                            field.classList.remove('border-[#e6ddd2]');
                                        } else {
                                            reqIndicator.style.display = 'none';
                                            field.required = false;
                                            field.classList.remove('border-danger');
                                            field.classList.add('border-[#e6ddd2]');
                                        }
                                    }
                                </script>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Detail -->
                    <div class="modal fade" id="detailModal{{ $p->id_peminjaman }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-[24px] border-0 shadow-lg">
                                <div class="modal-header border-b border-[#e6ddd2] p-6 bg-[#f7f3eb] rounded-t-[24px]">
                                    <h5 class="text-xl font-bold text-[#466454]">Detail Permohonan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-6 text-start">
                                    <div class="mb-4">
                                        <span class="text-[#7d8781] text-sm block mb-1">Nama Kegiatan</span>
                                        <span class="font-bold text-[#466454] text-lg">{{ $p->nama_kegiatan }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <span class="text-[#7d8781] text-sm block mb-1">Peminjam</span>
                                        <span class="font-semibold text-[#54615b]">{{ $p->user->nama_lengkap ?? '-' }}</span>
                                    </div>
                                    <div class="mb-4">
                                         <span class="text-[#7d8781] text-sm block mb-1">Tanggal & Waktu</span>
                                         <span class="font-semibold text-[#54615b]">{{ $p->tanggal_pengajuan ? \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} · {{ $p->jam_mulai ? str_replace(':', '.', substr($p->jam_mulai, 0, 5)) : '08.00' }} - {{ $p->jam_selesai ? str_replace(':', '.', substr($p->jam_selesai, 0, 5)) : '12.00' }}</span>
                                     </div>
                                    @if($p->ruangan->count() > 0)
                                    <div class="mb-4">
                                        <span class="text-[#7d8781] text-sm block mb-1">Jumlah Peserta</span>
                                        <span class="font-semibold text-[#54615b]">{{ $p->jumlah_peserta ?? 0 }} orang</span>
                                    </div>
                                    @elseif($p->barang->count() > 0)
                                    <div class="mb-4">
                                        <span class="text-[#7d8781] text-sm block mb-1">Jumlah Barang</span>
                                        <span class="font-semibold text-[#54615b]">{{ $p->barang->first()->pivot->jumlah ?? 1 }} Buah</span>
                                    </div>
                                    @endif
                                    <div class="mb-4">
                                        <span class="text-[#7d8781] text-sm block mb-1">Keterangan Acara</span>
                                        <span class="font-semibold text-[#54615b]">{{ $p->keterangan ?: 'Tidak ada keterangan tambahan.' }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <span class="text-[#7d8781] text-sm block mb-1">Dosen Penanggung Jawab</span>
                                        <span class="font-semibold text-[#54615b]">{{ $p->dosen ? $p->dosen->nama_lengkap : 'N/A' }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <span class="text-[#7d8781] text-sm block mb-1">Nama Ruangan / Barang</span>
                                        <span class="font-semibold text-[#54615b]">
                                            @if($p->ruangan->isNotEmpty())
                                                {{ $p->ruangan->first()->nama_ruangan }}
                                            @elseif($p->barang->isNotEmpty())
                                                {{ $p->barang->first()->nama_barang }}
                                            @else
                                                Fasilitas
                                            @endif
                                        </span>
                                    </div>
                                    <div class="mb-4">
                                        <span class="text-[#7d8781] text-sm block mb-1">PIC Fasilitas</span>
                                        <span class="font-semibold text-[#54615b]">
                                            @if($p->ruangan->isNotEmpty() && $p->ruangan->first()->pic)
                                                {{ $p->ruangan->first()->pic->nama_lengkap }}
                                            @elseif($p->barang->isNotEmpty() && $p->barang->first()->pic)
                                                {{ $p->barang->first()->pic->nama_lengkap }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                    <div class="mb-4">
                                        <span class="text-[#7d8781] text-sm block mb-1">Admin Verifikator</span>
                                        <span class="font-semibold text-[#54615b]">
                                            @php
                                                $adminVerif = $p->verifikasi->firstWhere('peran_verifikasi', 'Admin SBUM');
                                            @endphp
                                            {{ $adminVerif && $adminVerif->verifikator ? $adminVerif->verifikator->nama_lengkap : 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-6 pt-0">
                                    <button type="button" class="bg-[#466454] hover:bg-[#395244] text-white px-5 py-2.5 rounded-[14px] font-semibold transition" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-8 text-center text-[#7d8781]">
                        Tidak ada pengajuan yang membutuhkan persetujuan akhir.
                    </div>
                    @endforelse
                </div>


@endsection
