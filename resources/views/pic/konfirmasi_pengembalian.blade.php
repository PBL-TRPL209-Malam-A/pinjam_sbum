@extends('layout.app_tailwind')




@section('content')


<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div class="p-6 lg:p-8">
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Konfirmasi pengembalian fasilitas</h2>
        <p class="text-[#7d8781] max-w-2xl">
            PIC memeriksa kondisi ruangan dan perlengkapan pasca-pakai, lalu mengonfirmasi status selesai.
        </p>
    </div>
</div>

@php
    $selectedId = request()->query('selected_id');
    $selectedItem = null;
@endphp

@foreach($peminjaman as $item)
    @if(!$selectedId && $loop->first)
        @php $selectedId = $item->id_peminjaman; @endphp
    @endif
    @if($item->id_peminjaman == $selectedId)
        @php $selectedItem = $item; @endphp
    @endif
@endforeach



<div class="flex flex-col lg:flex-row gap-6 mb-6">
    <!-- Left Column: Antrian Permohonan Masuk -->
    <div class="w-full lg:w-1/2">
        <div class="font-semibold text-[#7d8781] mb-4">Daftar Antrean Pengembalian</div>
        <div class="flex flex-col gap-3">
            @forelse($peminjaman as $item)
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[20px] p-5 cursor-pointer transition hover:bg-[#f7f3eb] border-l-[6px] {{ $item->id_peminjaman == $selectedId ? 'border-l-[#466454] bg-[#f7f3eb]' : 'border-l-[#dfd4c8]' }}" onclick="window.location.href='?selected_id={{ $item->id_peminjaman }}'">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-bold text-[#466454]">SBUM-2026-{{ str_pad($item->id_peminjaman, 4, '0', STR_PAD_LEFT) }} · {{ $item->nama_kegiatan }}</div>
                            <div class="text-[#54615b] text-sm mt-1">
                                Peminjam: {{ $item->user->nama_lengkap ?? 'Peminjam' }} · 
                                {{ $item->ruangan->isNotEmpty() ? $item->ruangan->first()->nama_ruangan : ($item->barang->isNotEmpty() ? $item->barang->first()->nama_barang : 'Fasilitas') }}
                            </div>
                            <div class="text-[#7b8681] text-xs mt-2">
                                @php
                                    $returnDate = $item->jenis_peminjaman === 'ruangan'
                                        ? ($item->pengembalianRuangan->tanggal_pengembalian ?? null)
                                        : ($item->pengembalianBarang->tanggal ?? null);
                                    $returnTime = $item->jenis_peminjaman === 'ruangan'
                                        ? ($item->pengembalianRuangan->jam_selesai_aktual ?? null)
                                        : ($item->pengembalianBarang->jam_selesai_aktual ?? null);
                                @endphp
                                Pengembalian: {{ $returnDate ? \Carbon\Carbon::parse($returnDate)->translatedFormat('d M Y') : '-' }} · {{ $returnTime ? substr($returnTime, 0, 5) : '12.00' }}
                            </div>
                        </div>
                        <span class="inline-block bg-[#fcf1d3] text-[#7d6006] text-[13px] font-semibold px-4 py-1.5 rounded-full">Menunggu Verifikasi</span>
                    </div>
                </div>
            @empty
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-10 text-center text-[#7d8781]">
                    Tidak ada antrean pengembalian.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Details & Form Pengembalian -->
    <div class="w-full lg:w-1/2">
        @if($selectedItem)
            @php
                $fotoPath = $selectedItem->jenis_peminjaman === 'ruangan' 
                    ? ($selectedItem->pengembalianRuangan->foto_kondisi ?? null)
                    : ($selectedItem->pengembalianBarang->foto_kondisi ?? null);
                $dokumenPath = $selectedItem->jenis_peminjaman === 'ruangan' 
                    ? ($selectedItem->pengembalianRuangan->dokumen_administrasi ?? null)
                    : ($selectedItem->pengembalianBarang->dokumen_administrasi ?? null);
                $returnDate = $selectedItem->jenis_peminjaman === 'ruangan'
                    ? ($selectedItem->pengembalianRuangan->tanggal_pengembalian ?? null)
                    : ($selectedItem->pengembalianBarang->tanggal ?? null);
                $returnTime = $selectedItem->jenis_peminjaman === 'ruangan'
                    ? ($selectedItem->pengembalianRuangan->jam_selesai_aktual ?? null)
                    : ($selectedItem->pengembalianBarang->jam_selesai_aktual ?? null);
                $returnCatatan = $selectedItem->jenis_peminjaman === 'ruangan'
                    ? ($selectedItem->pengembalianRuangan->catatan ?? null)
                    : ($selectedItem->pengembalianBarang->catatan ?? null);
            @endphp
            <form action="{{ route('pic.pengembalian.store') }}" method="POST" id="pengembalianForm">
                @csrf
                <input type="hidden" name="peminjaman_id" value="{{ $selectedItem->id_peminjaman }}">
                <input type="hidden" name="status_pengembalian" id="pengembalianField" value="selesai">

                <div class="font-semibold text-[#7d8781] mb-4">Detail Verifikasi Pengembalian</div>
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-6 lg:p-8 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <div class="mb-3">
                                <div class="text-[#7d8781] text-xs font-semibold mb-1 uppercase tracking-wider">ID Peminjaman</div>
                                <div class="text-[#33403b] font-semibold text-sm">SBUM-2026-{{ str_pad($selectedItem->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <div class="text-[#7d8781] text-xs font-semibold mb-1 uppercase tracking-wider">Fasilitas</div>
                                <div class="text-[#33403b] font-semibold text-sm">
                                    @if($selectedItem->ruangan->isNotEmpty())
                                        {{ $selectedItem->ruangan->first()->nama_ruangan }}
                                    @elseif($selectedItem->barang->isNotEmpty())
                                        {{ $selectedItem->barang->first()->nama_barang }}
                                    @else
                                        Fasilitas
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($selectedItem->jenis_peminjaman === 'barang')
                        <div>
                            <div class="mb-3">
                                <div class="text-[#7d8781] text-xs font-semibold mb-1 uppercase tracking-wider">Jumlah Barang</div>
                                <div class="text-[#33403b] font-semibold text-sm">
                                    {{ count($selectedItem->barang) > 0 ? ($selectedItem->barang->first()->pivot->jumlah ?? 1) : 1 }} Buah
                                </div>
                            </div>
                        </div>
                        @endif
                        <div>
                            <div class="mb-3">
                                <div class="text-[#7d8781] text-xs font-semibold mb-1 uppercase tracking-wider">Tanggal Pengembalian</div>
                                <div class="text-[#33403b] font-semibold text-sm">{{ $returnDate ? \Carbon\Carbon::parse($returnDate)->translatedFormat('d M Y') : '-' }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <div class="text-[#7d8781] text-xs font-semibold mb-1 uppercase tracking-wider">Jam Selesai Aktual</div>
                                <div class="text-[#33403b] font-semibold text-sm">{{ $returnTime ? substr($returnTime, 0, 5) : '-' }}</div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="mb-3">
                                <div class="text-[#7d8781] text-xs font-semibold mb-1 uppercase tracking-wider">Catatan Pengembalian Peminjam</div>
                                <div class="text-[#33403b] font-semibold text-sm">{{ $returnCatatan ?: '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6 border-[#e6ddd2]">

                    <div class="font-semibold text-[#7d8781] mb-4">Bukti Fisik Pasca-Pakai</div>
                    <div class="mb-3 text-start">
                        <div class="info-label mb-2">Foto Kondisi Fasilitas</div>
                        @if($fotoPath)
                            @php
                                $fotoUrl = asset($fotoPath);
                            @endphp
                            <div class="mb-3">
                                <img src="{{ $fotoUrl }}" alt="Bukti Foto Kondisi" class="rounded-4 border" style="width: 120px; height: 120px; object-fit: cover; cursor: pointer;" onclick="zoomImage(this)">
                            </div>
                        @else
                            <div class="text-secondary small mb-3" style="color: #6c757d;">Bukti tidak tersedia</div>
                        @endif

                        <div class="info-label mb-2">Dokumen Administrasi</div>
                        @if($dokumenPath)
                            @php
                            $dokumenUrl = asset($dokumenPath);
                            @endphp
                            <div>
                                <a href="{{ $dokumenUrl }}" download class="inline-flex items-center justify-center gap-2 bg-[#f7f6f2] border border-[#dfd4c8] text-[#33403b] rounded-xl px-6 py-2.5 font-medium hover:bg-[#f0e9df] transition">
                                    <i class="bi bi-file-earmark-pdf text-danger"></i> Unduh Dokumen PDF
                                </a>
                            </div>
                        @else
                            <div class="text-secondary small" style="color: #6c757d;">Bukti tidak tersedia</div>
                        @endif
                    </div>

                    <div class="mt-4 mb-4">
                        <label class="form-label text-secondary fw-semibold">Keputusan Verifikasi <span class="text-danger">*</span></label>
                        <div class="d-flex flex-column gap-2">
                            <label class="d-flex align-items-center p-3 border rounded-3" style="cursor: pointer; background:#dcebd7; border-color:#b7d2b6 !important;">
                                <input type="radio" name="status_pengembalian" value="selesai" class="form-check-input mt-0 me-3" required onchange="handleDecisionChange(this.value)">
                                <span class="fw-semibold text-[#466454]">Terima Pengembalian</span>
                            </label>
                            <label class="d-flex align-items-center p-3 border rounded-3" style="cursor: pointer; background:#fdf0f0; border-color:#f5c2c7 !important;">
                                <input type="radio" name="status_pengembalian" value="ditolak" class="form-check-input mt-0 me-3" required onchange="handleDecisionChange(this.value)">
                                <span class="fw-semibold text-danger">Tolak Pengembalian</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-4 mb-3" id="rejectNoteContainer" style="display: none;">
                        <label class="block text-sm font-semibold text-[#54615b] mb-1.5" id="rejectLabel">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan" id="catatanField" class="w-full bg-[#fffdfa] border border-[#e6ddd2] text-[#33403b] rounded-xl px-4 py-2 focus:outline-none focus:border-[#466454]" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>

                    <div class="modal-footer border-0 pt-0 d-flex w-100 mt-4 px-0">
                        <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-3 rounded-xl font-semibold transition w-100 border-0" style="border-radius:0.75rem;">Simpan Keputusan</button>
                    </div>
                </div>
            </form>
        @else
            <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-10 text-center text-[#7d8781]">
                Pilih antrean pengembalian di kolom kiri untuk melakukan verifikasi.
            </div>
        @endif
    </div>
</div>

<!-- Image Zoom Modal -->
<div class="modal fade" id="zoomPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: transparent; border: none;">
            <div class="modal-body text-center p-0 position-relative">
                <img id="zoomedImage" src="" class="img-fluid rounded-4 border shadow-lg" style="max-height: 85vh; object-fit: contain;">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>

<script>
    function handleDecisionChange(value) {
        const rejectContainer = document.getElementById('rejectNoteContainer');
        const catatanField = document.getElementById('catatanField');
        const rejectLabel = document.getElementById('rejectLabel');
        
        if (value === 'ditolak') {
            rejectContainer.style.display = 'block';
            catatanField.required = true;
            catatanField.classList.add('border-danger');
            catatanField.classList.remove('border-[#e6ddd2]');
            rejectLabel.classList.add('text-danger');
            rejectLabel.classList.remove('text-[#54615b]');
        } else {
            rejectContainer.style.display = 'none';
            catatanField.required = false;
            catatanField.classList.remove('border-danger');
            catatanField.classList.add('border-[#e6ddd2]');
            rejectLabel.classList.remove('text-danger');
            rejectLabel.classList.add('text-[#54615b]');
        }
    }

    function zoomImage(element) {
        const zoomedImage = document.getElementById('zoomedImage');
        zoomedImage.src = element.src;
        const modal = new bootstrap.Modal(document.getElementById('zoomPhotoModal'));
        modal.show();
    }
</script>
@endsection
