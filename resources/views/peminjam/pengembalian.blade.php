@extends('layout.app_tailwind')



@section('content')
            <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 mb-6">
                <div>
                    <div class="text-[#7b8681] text-[20px] mb-1">Peminjam</div>
                    <h1 class="text-[24px] font-medium m-0">Peminjam · Ajukan Pengembalian</h1>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <input type="text" class="h-12 px-4 rounded-2xl border border-[#ddd2c5] bg-[#fffdfa] focus:outline-none focus:border-[#466454] w-full md:w-64 transition" placeholder="Cari data">
                    <div class="w-12 h-12 bg-[#cfdacd] rounded-full flex shrink-0"></div>
                </div>
            </div>

            <div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[28px] p-6 lg:p-8 mb-6 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-xl font-semibold mb-2">Ajukan pengembalian fasilitas</h2>
                    <p class="mb-0 text-[#5f6963]">
                        Peminjam membuka menu pengembalian, mengisi data, lalu submit agar admin dapat memverifikasi.
                    </p>
                </div>
                <!-- Decorative shapes -->
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/40 rounded-full blur-2xl"></div>
                <div class="absolute right-20 -bottom-10 w-32 h-32 bg-[#d6e5d6]/60 rounded-full blur-xl"></div>
            </div>

            <form action="{{ route('peminjam.pengembalian.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <div class="xl:col-span-2">
                        <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Form Pengembalian</div>
 
                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6 flex flex-col gap-5">
                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">ID Peminjaman</label>
                                <select name="peminjaman_id" id="peminjamanSelect" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition" required>
                                    <option value="">Pilih PJM Aktif</option>
                                    @foreach($peminjaman as $p)
                                        @php
                                            $facilityName = $p->ruangan->isNotEmpty() ? $p->ruangan->first()->nama_ruangan : ($p->barang->isNotEmpty() ? $p->barang->first()->nama_barang : 'Fasilitas');
                                        @endphp
                                        <option value="{{ $p->id_peminjaman }}" 
                                            data-fasilitas="{{ $facilityName }}"
                                            data-kegiatan="{{ $p->nama_kegiatan }}"
                                            data-waktu="{{ $p->tanggal_pengajuan ? \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} · {{ $p->jam_mulai ? substr($p->jam_mulai, 0, 5) : '08:00' }} - {{ $p->jam_selesai ? substr($p->jam_selesai, 0, 5) : '12:00' }}">
                                            [SBUM-2026-{{ str_pad($p->id_peminjaman, 4, '0', STR_PAD_LEFT) }}] - {{ $p->nama_kegiatan }} ({{ $facilityName }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
 
                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">Fasilitas</label>
                                <input type="text" id="facilityInput" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#f7f3eb] focus:outline-none focus:border-[#466454] transition" readonly value="-">
                            </div>
 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[#5c6761] font-semibold mb-2">Tanggal Selesai Aktual</label>
                                    <input type="date" name="tanggal_selesai_aktual" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition" value="{{ now()->format('Y-m-d') }}" required>
                                </div>
                                <div>
                                    <label class="block text-[#5c6761] font-semibold mb-2">Jam Selesai Aktual</label>
                                    <input type="time" name="jam_selesai_aktual" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition" value="{{ now()->format('H:i') }}" required>
                                </div>
                            </div>
 
                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">Kondisi Fasilitas</label>
                                <input type="hidden" name="kondisi" id="kondisiInput" value="baik">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="status-good h-12 flex items-center justify-center rounded-2xl border-2 border-[#557b58] bg-[#dcebd7] text-[#557b58] font-semibold cursor-pointer transition hover:bg-[#c5dec0]">
                                        Baik
                                    </div>
                                    <div class="status-note h-12 flex items-center justify-center rounded-2xl border-2 border-transparent bg-[#f4e7c9] text-[#92723c] font-semibold cursor-pointer transition hover:bg-[#ebd5a7]">
                                        Ada Catatan
                                    </div>
                                </div>
                            </div>
 
                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">Catatan Pengembalian</label>
                                <textarea name="catatan" class="w-full px-4 py-3 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition min-h-[100px]" placeholder="Masukkan catatan pengembalian (opsional)"></textarea>
                            </div>
 
                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">Upload Foto Kondisi Fasilitas <span class="text-[#7b8681] font-normal text-sm">(Format: .jpg, .jpeg, .png, maks 5MB)</span></label>
                                <input type="file" name="foto_kondisi" class="w-full px-4 py-2.5 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition" accept="image/png, image/jpeg, image/jpg" required>
                            </div>
 
                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">Upload Dokumen Administrasi Pasca-Pakai <span class="text-[#7b8681] font-normal text-sm">(Format: .pdf, maks 10MB)</span></label>
                                <input type="file" name="dokumen_administrasi" class="w-full px-4 py-2.5 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition" accept="application/pdf" required>
                            </div>
                        </div>
                    </div>
 
                    <div class="xl:col-span-1">
                        <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Ringkasan Pengembalian</div>
 
                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6 mb-5 shadow-sm">
                            <div class="text-[#7b8681] mb-2 text-sm">Peminjaman Aktif</div>
                            <div class="font-semibold text-lg text-[#33403b] mb-2" id="summaryKegiatan">-</div>
                            <div class="text-[#5f6963] text-sm" id="summaryWaktu">-</div>
                        </div>
 
                        <div class="flex flex-col gap-3">
                            <button type="submit" class="h-12 bg-[#5d7d6b] hover:bg-[#496454] text-white font-semibold rounded-2xl transition border-0 cursor-pointer shadow-sm w-full">Submit Pengembalian</button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const peminjamanSelect = document.getElementById('peminjamanSelect');
        if (peminjamanSelect) {
            peminjamanSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const facility = selectedOption.getAttribute('data-fasilitas');
                    const kegiatan = selectedOption.getAttribute('data-kegiatan');
                    const waktu = selectedOption.getAttribute('data-waktu');
                    
                    document.getElementById('facilityInput').value = facility;
                    document.getElementById('summaryKegiatan').innerText = kegiatan;
                    document.getElementById('summaryWaktu').innerText = facility + ' · ' + waktu;
                } else {
                    document.getElementById('facilityInput').value = '-';
                    document.getElementById('summaryKegiatan').innerText = '-';
                    document.getElementById('summaryWaktu').innerText = '-';
                }
            });
        }

        const choiceGood = document.querySelector('.status-good');
        const choiceNote = document.querySelector('.status-note');
        const kondisiInput = document.getElementById('kondisiInput');

        if (choiceGood && choiceNote && kondisiInput) {
            choiceGood.addEventListener('click', function() {
                kondisiInput.value = 'baik';
                choiceGood.classList.add('border-[#557b58]');
                choiceGood.classList.remove('border-transparent');
                choiceNote.classList.remove('border-[#92723c]');
                choiceNote.classList.add('border-transparent');
            });

            choiceNote.addEventListener('click', function() {
                kondisiInput.value = 'ada_catatan';
                choiceNote.classList.add('border-[#92723c]');
                choiceNote.classList.remove('border-transparent');
                choiceGood.classList.remove('border-[#557b58]');
                choiceGood.classList.add('border-transparent');
            });
        }
    });
</script>
@endsection
