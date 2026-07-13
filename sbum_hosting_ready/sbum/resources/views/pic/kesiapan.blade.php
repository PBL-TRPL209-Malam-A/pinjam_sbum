@extends('layout.app_tailwind')




@section('content')


<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div class="p-6 lg:p-8">
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Pastikan fasilitas siap sebelum digunakan</h2>
        <p class="text-[#7d8781] max-w-2xl">
            PIC memeriksa kondisi ruangan dan perlengkapan, lalu menyimpan status siap atau kendala.
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
        <div class="font-semibold text-[#7d8781] mb-4">Daftar Permohonan Masuk</div>
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
                                {{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} · {{ $item->jam_mulai ? str_replace(':', '.', substr($item->jam_mulai, 0, 5)) : '08.00' }} - {{ $item->jam_selesai ? str_replace(':', '.', substr($item->jam_selesai, 0, 5)) : '12.00' }}
                            </div>
                        </div>
                        <span class="inline-block bg-[#fcf1d3] text-[#7d6006] text-[13px] font-semibold px-4 py-1.5 rounded-full">Menunggu PIC</span>
                    </div>
                </div>
            @empty
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-10 text-center text-[#7d8781]">
                    Tidak ada permohonan masuk.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Details & Form Kesiapan -->
    <div class="w-full lg:w-1/2">
        @if($selectedItem)
            <form action="{{ route('pic.kesiapan.store') }}" method="POST" id="kesiapanForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="peminjaman_id" value="{{ $selectedItem->id_peminjaman }}">
                <input type="hidden" name="status_kesiapan" id="kesiapanField" value="siap">

                <div class="font-semibold text-[#7d8781] mb-4">Detail Kesiapan Fasilitas</div>
                <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-6 lg:p-8 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <div class="mb-3">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Kegiatan</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->nama_kegiatan }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Fasilitas</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">
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
                        <div>
                            <div class="mb-3">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Waktu</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->tanggal_pengajuan ? \Carbon\Carbon::parse($selectedItem->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} · {{ $selectedItem->jam_mulai ? str_replace(':', '.', substr($selectedItem->jam_mulai, 0, 5)) : '08.00' }} - {{ $selectedItem->jam_selesai ? str_replace(':', '.', substr($selectedItem->jam_selesai, 0, 5)) : '12.00' }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Peminjam</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->user->nama_lengkap ?? '-' }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Dosen Penanggung Jawab</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->dosen->nama_lengkap ?? '-' }}</div>
                            </div>
                        </div>
                        @if($selectedItem->ruangan->count() > 0)
                        <div>
                            <div class="mb-3">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Jumlah Peserta</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->jumlah_peserta ?? '0' }} Orang</div>
                            </div>
                        </div>
                        @elseif($selectedItem->barang->count() > 0)
                        <div>
                            <div class="mb-3">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Jumlah Barang</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->barang->first()->pivot->jumlah ?? 1 }} Buah</div>
                            </div>
                        </div>
                        @endif
                        <div class="md:col-span-2">
                            <div class="mb-3">
                                <div class="info-label" style="color: var(--text-muted); font-size: 0.85rem;">Deskripsi Acara</div>
                                <div class="info-value" style="color: var(--text-main); font-weight: 600;">{{ $selectedItem->keterangan ?: 'Tidak ada keterangan tambahan.' }}</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6 border-[#e6ddd2]">

                    <div class="font-semibold text-[#7d8781] mb-4">Checklist Kesiapan</div>
                    @if($selectedItem->ruangan->count() > 0)
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check1" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check1">Ruangan bersih dan siap pakai</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check2" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check2">Sound system dan listrik normal</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check3" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check3">Kursi, meja, dan akses ruangan lengkap</label>
                    </div>
                    @elseif($selectedItem->barang->count() > 0)
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check1" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check1">Kondisi fisik barang baik dan tidak cacat</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check2" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check2">Fungsionalitas barang berjalan dengan normal</label>
                    </div>
                    <div class="checklist-item text-start">
                        <input class="form-check-input mt-0" type="checkbox" id="check3" required>
                        <label class="form-check-label text-main fw-semibold ms-2" for="check3">Aksesoris / kelengkapan barang sudah lengkap (jika ada)</label>
                    </div>
                    @endif

                    <div class="mt-4 mb-4 text-start">
                        <label class="form-label text-secondary fw-semibold">Status Kesiapan <span class="text-danger">*</span></label>
                        <div class="d-flex flex-column gap-2">
                            <label class="d-flex align-items-center p-3 border rounded-3" style="cursor: pointer; background:#dcebd7; border-color:#b7d2b6 !important;">
                                <input type="radio" name="status_kesiapan" value="siap" class="form-check-input mt-0 me-3" required onchange="handleDecisionChange(this.value)">
                                <span class="fw-semibold text-[#466454]">Fasilitas Siap Digunakan</span>
                            </label>
                            <label class="d-flex align-items-center p-3 border rounded-3" style="cursor: pointer; background:#fdf0f0; border-color:#f5c2c7 !important;">
                                <input type="radio" name="status_kesiapan" value="kendala" class="form-check-input mt-0 me-3" required onchange="handleDecisionChange(this.value)">
                                <span class="fw-semibold text-danger">Terdapat Kendala / Ditolak</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3 text-start" id="rejectNoteContainer" style="display: none;">
                        <label class="form-label fw-bold" id="rejectLabel">Detail Kendala / Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan" id="catatanField" class="form-control" rows="3" style="border-radius: 0.75rem; font-size: 0.9rem;" placeholder="Wajib: Deskripsikan kendala atau alasan penolakan..."></textarea>
                    </div>

                    <div class="mt-3 text-start">
                        <label class="form-label text-secondary small fw-semibold">Upload Bukti Kondisi (Opsional)</label>
                        <input type="file" name="foto_kondisi" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" style="border-radius: 0.75rem;">
                    </div>

                    <div class="modal-footer border-0 pt-0 d-flex w-100 mt-4 px-0">
                        <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-3 rounded-xl font-semibold transition w-100 border-0" style="border-radius:0.75rem;">Simpan Kesiapan</button>
                    </div>
                </div>
            </form>
        @else
            <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-10 text-center text-[#7d8781]">
                Pilih permohonan di antrean untuk melakukan konfirmasi kesiapan.
            </div>
        @endif
    </div>
</div>

<script>
    function handleDecisionChange(value) {
        const rejectContainer = document.getElementById('rejectNoteContainer');
        const catatanField = document.getElementById('catatanField');
        const rejectLabel = document.getElementById('rejectLabel');
        
        const check1 = document.getElementById('check1');
        const check2 = document.getElementById('check2');
        const check3 = document.getElementById('check3');

        if (value === 'kendala') {
            rejectContainer.style.display = 'block';
            catatanField.required = true;
            catatanField.classList.add('border-danger');
            catatanField.classList.remove('border-warning');
            rejectLabel.classList.add('text-danger');
            rejectLabel.classList.remove('text-warning');
            
            // Hapus wajib centang jika ditolak
            if(check1) check1.required = false;
            if(check2) check2.required = false;
            if(check3) check3.required = false;
        } else {
            rejectContainer.style.display = 'none';
            catatanField.required = false;
            catatanField.classList.remove('border-danger');
            catatanField.classList.remove('border-warning');
            rejectLabel.classList.remove('text-danger');
            rejectLabel.classList.remove('text-warning');
            
            // Wajib centang jika disetujui
            if(check1) check1.required = true;
            if(check2) check2.required = true;
            if(check3) check3.required = true;
        }
    }
</script>
@endsection
