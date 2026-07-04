@extends('layout.app_tailwind')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-6">
    <div>
        <div class="text-[19px] text-[#7d8781] mb-1">Kepala SBUM</div>
        <h1 class="text-[32px] font-bold text-[#466454] leading-tight mb-2">Laporan Pengembalian Fasilitas</h1>
    </div>
</div>



                <div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-[#466454] mb-2">Laporan pengembalian dan kondisi fasilitas</h2>
                    <p class="mb-0 text-[#54615b]">
                        Kepala SBUM melihat jumlah pengembalian, kondisi fasilitas setelah dipakai, dan item yang perlu tindak lanjut.
                    </p>
                </div>

                <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <form action="{{ route('kepalasbum.laporan-pengembalian') }}" method="GET" id="filterForm" class="flex-1">
                            <div class="flex flex-wrap gap-4">
                                <div class="flex flex-col">
                                    <label class="text-[#7d8781] font-semibold mb-2 text-sm">Periode</label>
                                    <select name="periode" class="bg-white border border-[#e6ddd2] text-[#54615b] px-4 py-2.5 rounded-[14px] font-medium focus:outline-none focus:border-[#466454]" onchange="document.getElementById('filterForm').submit()">
                                        <option value="">Semua Periode</option>
                                        @for($i = 0; $i < 6; $i++)
                                            @php
                                                $date = \Carbon\Carbon::now()->subMonths($i);
                                                $val = $date->format('Y-m');
                                                $label = $date->translatedFormat('F Y');
                                            @endphp
                                            <option value="{{ $val }}" {{ request('periode') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-[#7d8781] font-semibold mb-2 text-sm">Jenis</label>
                                    <select name="jenis" class="bg-white border border-[#e6ddd2] text-[#54615b] px-4 py-2.5 rounded-[14px] font-medium focus:outline-none focus:border-[#466454]" onchange="document.getElementById('filterForm').submit()">
                                        <option value="">Semua Fasilitas</option>
                                        <option value="ruangan" {{ request('jenis') == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                                        <option value="barang" {{ request('jenis') == 'barang' ? 'selected' : '' }}>Barang</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                        <a href="{{ route('kepalasbum.laporan-pengembalian.export', ['periode' => request('periode'), 'jenis' => request('jenis')]) }}" class="bg-[#54645c] hover:bg-[#3f4d46] text-white px-5 py-2.5 rounded-[14px] font-semibold transition mt-4 md:mt-0 whitespace-nowrap self-end">Unduh Excel</a>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
                        <div class="text-xs font-medium text-[#7d8781] leading-tight mb-2">Total Pengembalian</div>
                        <div class="text-3xl font-bold text-[#466454]">{{ $totalPengembalian ?? 0 }}</div>
                    </div>
                    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
                        <div class="text-xs font-medium text-[#7d8781] leading-tight mb-2">Kondisi Baik</div>
                        <div class="text-3xl font-bold text-[#466454]">{{ $kondisiBaik ?? 0 }}</div>
                    </div>
                    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
                        <div class="text-xs font-medium text-[#7d8781] leading-tight mb-2">Perlu Tindak Lanjut</div>
                        <div class="text-3xl font-bold text-[#466454]">{{ $tindakLanjut ?? 0 }}</div>
                    </div>
                    <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 hover:-translate-y-1 transition duration-300">
                        <div class="text-xs font-medium text-[#7d8781] leading-tight mb-2">Terlambat</div>
                        <div class="text-3xl font-bold text-[#466454]">{{ $terlambat ?? 0 }}</div>
                    </div>
                </div>

                <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] overflow-hidden shadow-sm p-6 mb-6">
                    <h3 class="text-xl font-bold text-[#466454] mb-6">Grafik Tren Pengembalian</h3>
                    <div class="pt-3">
                        @php
                            $chartData = collect();
                            foreach($pengembalian->sortBy('tanggal_kembali') as $p) {
                                if(!$p->tanggal_kembali) continue;
                                $date = \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M');
                                $chartData[$date] = ($chartData[$date] ?? 0) + 1;
                            }
                            $chartData = $chartData->take(-7);
                            
                            $maxCount = $chartData->max() ?: 1;
                            $xStep = $chartData->count() > 1 ? (450 - 30) / ($chartData->count() - 1) : 420;
                            
                            $path = "M ";
                            $points = [];
                            $i = 0;
                            foreach($chartData as $date => $count) {
                                $x = 30 + ($i * $xStep);
                                $y = 150 - (($count / $maxCount) * (150 - 40));
                                $points[] = ['x' => $x, 'y' => $y, 'label' => $date, 'val' => $count];
                                $path .= "$x,$y ";
                                if ($i < $chartData->count() - 1) $path .= "L ";
                                $i++;
                            }
                            if(empty($points)) {
                                $path = "M 30,150 L 450,150";
                            }
                        @endphp
                        <svg viewBox="0 0 500 200" style="width: 100%; height: auto; max-height: 250px;">
                            <!-- grid lines -->
                            <line x1="0" y1="50" x2="500" y2="50" stroke="#f1ece4" stroke-width="1"/>
                            <line x1="0" y1="100" x2="500" y2="100" stroke="#f1ece4" stroke-width="1"/>
                            <line x1="0" y1="150" x2="500" y2="150" stroke="#f1ece4" stroke-width="1"/>
                            
                            <!-- line -->
                            <path d="{{ $path }}" fill="none" stroke="#5d7d6b" stroke-width="3" stroke-linecap="round"/>
                            
                            <!-- dots -->
                            @foreach($points as $pt)
                                <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="5" fill="#496454"/>
                                <text x="{{ $pt['x'] }}" y="{{ $pt['y'] - 15 }}" font-size="10" fill="#496454" text-anchor="middle">{{ $pt['val'] }}</text>
                                <text x="{{ $pt['x'] }}" y="170" font-size="10" fill="#888" text-anchor="middle">{{ $pt['label'] }}</text>
                            @endforeach
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-bold mb-4 mt-8 text-[#466454]">Daftar Pengembalian</h2>

                <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] overflow-hidden shadow-sm mb-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-[#f5f2ec] text-[#466454]">
                                <tr>
                                    <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Fasilitas</th>
                                    <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Peminjam</th>
                                    <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Tanggal Pengembalian</th>
                                    <th style="text-align: center;" class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Status</th>
                                    <th style="text-align: right; padding-right: 2rem;" class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Kondisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengembalian as $p)
                                <tr>
                                    <td class="p-4 border-b border-[#e6ddd2] font-semibold text-[#466454]">
                                        @if($p->peminjaman->ruangan->isNotEmpty())
                                            {{ $p->peminjaman->ruangan->first()->nama_ruangan }}
                                        @elseif($p->peminjaman->barang->isNotEmpty())
                                            {{ $p->peminjaman->barang->first()->nama_barang }}
                                        @else
                                            Fasilitas
                                        @endif
                                    </td>
                                    <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $p->peminjaman->user->nama_lengkap ?? '-' }}</td>
                                    <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">{{ date('d M Y', strtotime($p->tanggal_kembali)) }}</td>
                                    <td style="text-align: center;" class="p-4 border-b border-[#e6ddd2]">
                                        @if($p->status_pengembalian == 'dikonfirmasi_admin')
                                            <span class="inline-block bg-[#d1fae5] text-[#065f46] text-[13px] font-semibold px-3 py-1 rounded-full border border-[#a7f3d0]">Selesai</span>
                                        @else
                                            <span class="inline-block bg-[#fef08a] text-[#854d0e] text-[13px] font-semibold px-3 py-1 rounded-full border border-[#fde047]">Proses</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right; padding-right: 1.5rem;" class="p-4 border-b border-[#e6ddd2]">
                                        @if($p->kondisi_kembali == 'baik')
                                            <span class="inline-block bg-[#d1fae5] text-[#065f46] text-[13px] font-semibold px-3 py-1 rounded-full border border-[#a7f3d0]">Baik</span>
                                        @elseif($p->kondisi_kembali == 'hilang' || $p->kondisi_kembali == 'rusak')
                                            <span class="inline-block bg-[#fee2e2] text-[#991b1b] text-[13px] font-semibold px-3 py-1 rounded-full border border-[#fecaca]">Terlambat / Rusak</span>
                                        @else
                                            <span class="inline-block bg-[#e0e7ff] text-[#3730a3] text-[13px] font-semibold px-3 py-1 rounded-full border border-[#c7d2fe]">Perlu Diperiksa</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @if($pengembalian->isEmpty())
                                <tr>
                                    <td colspan="5" class="p-4 border-b border-[#e6ddd2] text-center text-[#7d8781]">Belum ada data pengembalian yang sesuai dengan filter.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
@endsection
