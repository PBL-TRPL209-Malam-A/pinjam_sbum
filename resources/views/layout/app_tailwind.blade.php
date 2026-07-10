<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBUM</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-sbum.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- AlpineJS for interactive components like mobile sidebar -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f5f2ec] text-[#33403b] font-sans antialiased min-h-screen" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <div class="w-full bg-white flex relative min-h-screen">
            
            <!-- Sidebar (Desktop & Mobile Off-canvas) -->
            <aside 
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-50 w-[270px] bg-[#f5f2ec] border-r border-[#e6ddd2] py-[22px] px-[18px] flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:w-[270px] shrink-0"
            >
                <div class="flex items-center justify-center relative px-2 pb-5 pt-2">
                    <img src="{{ asset('images/logo-polibatam.png') }}" alt="Logo Polibatam" class="w-[110px] max-w-full h-auto object-contain">
                    
                    <!-- Close button for mobile -->
                    <button @click="sidebarOpen = false" class="lg:hidden absolute right-2 w-8 h-8 flex items-center justify-center rounded-full bg-[#f5f2ec] text-[#54615b]">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <nav class="grid gap-2.5 mt-3 overflow-y-auto pr-1 custom-scrollbar">
                    @if(auth()->check() && auth()->user()->isPeminjam())
                    <!-- Peminjam Menu -->
                    <a href="{{ route('peminjam.dashboard') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('peminjam.dashboard') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-grid-fill"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('peminjam.fasilitas') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('peminjam.fasilitas*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-box-seam"></i>
                        </span>
                        <span>Fasilitas</span>
                    </a>
                    <a href="{{ route('peminjam.jadwal') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('peminjam.jadwal*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-calendar-check"></i>
                        </span>
                        <span>Jadwal</span>
                    </a>
                    <a href="{{ route('peminjam.pengajuan') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('peminjam.pengajuan*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-pencil-square"></i>
                        </span>
                        <span>Pengajuan Saya</span>
                    </a>
                    <a href="{{ route('peminjam.pengembalian') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('peminjam.pengembalian*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-arrow-return-left"></i>
                        </span>
                        <span>Pengembalian</span>
                    </a>
                    <a href="{{ route('peminjam.riwayat') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('peminjam.riwayat*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-clock-history"></i>
                        </span>
                        <span class="leading-tight">Riwayat<br>Peminjaman</span>
                    </a>
                    <a href="{{ route('peminjam.notifikasi') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('peminjam.notifikasi*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-bell"></i>
                        </span>
                        <span>Notifikasi</span>
                    </a>
                    <a href="{{ route('peminjam.profil') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('peminjam.profil*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-person"></i>
                        </span>
                        <span>Profil</span>
                    </a>
                    
                    @elseif(auth()->check() && auth()->user()->isAdmin())
                    <!-- Admin Menu -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('admin.dashboard') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-grid-fill"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.fasilitas') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('admin.fasilitas*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-building"></i>
                        </span>
                        <span>Data Fasilitas</span>
                    </a>
                    <a href="{{ route('admin.inventaris') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('admin.inventaris*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-box"></i>
                        </span>
                        <span>Barang Inventaris</span>
                    </a>
                    <a href="{{ route('admin.verifikasi-peminjaman') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('admin.verifikasi-peminjaman*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-check2-square"></i>
                        </span>
                        <span>Verifikasi Peminjaman</span>
                    </a>
                    <a href="{{ route('admin.jadwal') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('admin.jadwal*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-calendar3"></i>
                        </span>
                        <span>Jadwal</span>
                    </a>
                    <a href="{{ route('admin.peminjaman') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('admin.peminjaman') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-journal-text"></i>
                        </span>
                        <span>Data Peminjaman</span>
                    </a>
                    <a href="{{ route('admin.pengembalian') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('admin.pengembalian') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-arrow-return-left"></i>
                        </span>
                        <span>Pengembalian</span>
                    </a>
                    <a href="{{ route('admin.verifikasi-pengembalian') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('admin.verifikasi-pengembalian*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-shield-check"></i>
                        </span>
                        <span>Verif Pengembalian</span>
                    </a>
                    <a href="{{ route('admin.profil') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('admin.profil*') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0">
                            <i class="bi bi-person"></i>
                        </span>
                        <span>Profil</span>
                    </a>
                    

                    @elseif(auth()->check() && auth()->user()->isDosen())
                    <!-- Dosen Menu -->
                    <a href="{{ route('dosen.dashboard') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('dosen.dashboard') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-grid-fill"></i></span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('dosen.verifikasi-peminjaman') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('dosen.verifikasi-peminjaman') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-check2-square"></i></span>
                        <span>Verifikasi Peminjaman</span>
                    </a>
                    <a href="{{ route('dosen.profil') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('dosen.profil') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-person"></i></span>
                        <span>Profil</span>
                    </a>

                    @elseif(auth()->check() && auth()->user()->isPic())
                    <!-- PIC Menu -->
                    <a href="{{ route('pic.dashboard') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('pic.dashboard') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-grid-fill"></i></span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('pic.kesiapan') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('pic.kesiapan') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-check-circle"></i></span>
                        <span>Kesiapan</span>
                    </a>
                    <a href="{{ route('pic.pengembalian') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('pic.pengembalian') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-arrow-return-left"></i></span>
                        <span>Pengembalian</span>
                    </a>
                    <a href="{{ route('pic.profil') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('pic.profil') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-person"></i></span>
                        <span>Profil</span>
                    </a>

                    @elseif(auth()->check() && auth()->user()->isKepalaSbum())
                    <!-- Kepala SBUM Menu -->
                    <a href="{{ route('kepalasbum.dashboard') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('kepalasbum.dashboard') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-grid-fill"></i></span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('kepalasbum.staff') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('kepalasbum.staff') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-people"></i></span>
                        <span>Data Staff</span>
                    </a>
                    <a href="{{ route('kepalasbum.persetujuan') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('kepalasbum.persetujuan') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-shield-check"></i></span>
                        <span>Persetujuan</span>
                    </a>
                    <a href="{{ route('kepalasbum.laporan') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('kepalasbum.laporan') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-file-earmark-text"></i></span>
                        <span>Laporan Peminjaman</span>
                    </a>
                    <a href="{{ route('kepalasbum.laporan-pengembalian') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('kepalasbum.laporan-pengembalian') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-file-earmark-check"></i></span>
                        <span>Laporan Pengembalian</span>
                    </a>
                    <a href="{{ route('kepalasbum.profil') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('kepalasbum.profil') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-person"></i></span>
                        <span>Profil</span>
                    </a>

                    @elseif(auth()->check() && auth()->user()->isPamdal())
                    <!-- Pamdal Menu -->
                    <a href="{{ route('pamdal.dashboard') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('pamdal.dashboard') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-grid-fill"></i></span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('pamdal.monitoring') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('pamdal.monitoring') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-eye"></i></span>
                        <span>Pengawasan</span>
                    </a>
                    <a href="{{ route('pamdal.profil') }}" class="flex items-center gap-3 py-3.5 px-4 rounded-2xl transition duration-200 no-underline {{ request()->routeIs('pamdal.profil') ? 'bg-[#eff3f0] !text-[#466454] font-semibold' : '!text-[#54615b] text-[15px] hover:bg-[#f3f7f3] hover:!text-[#466454]' }}">
                        <span class="w-[22px] h-[22px] rounded-full bg-[#dbe4dd] flex items-center justify-center text-[11px] shrink-0"><i class="bi bi-person"></i></span>
                        <span>Profil</span>
                    </a>

                    @endif
                </nav>

                <div class="mt-auto p-3.5 pt-6 lg:pt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-transparent border-0 text-[#5b635f] p-0 text-[16px] hover:text-red-600 transition flex items-center gap-2">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </aside>
            <style>
                .custom-scrollbar::-webkit-scrollbar { width: 4px; }
                .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background: #dbe4dd; border-radius: 10px; }
                .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #c5d3c7; }
            </style>
            
            <div class="flex-1 flex flex-col min-w-0 h-full overflow-y-auto overflow-x-hidden">
                <!-- Mobile Header with Hamburger -->
                <div class="lg:hidden flex items-center justify-between p-4 border-b border-[#e6ddd2] bg-white sticky top-0 z-30">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/logo-polibatam.png') }}" alt="Logo Polibatam" class="h-8 object-contain">
                    </div>
                    <button @click="sidebarOpen = true" class="p-2 bg-[#edf2ea] hover:bg-[#e4ece0] rounded-xl text-[#54615b] transition">
                        <i class="bi bi-list text-2xl leading-none"></i>
                    </button>
                </div>

                <main class="py-6 px-4 md:px-6 lg:px-8 flex-1">
                    @yield('content')
                </main>
            </div>
            
            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" style="display: none;" x-transition.opacity class="fixed inset-0 bg-[#33403b]/40 z-40 lg:hidden backdrop-blur-sm" @click="sidebarOpen = false"></div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
