@extends('layout.auth')

@section('content')
<div class="relative bg-white overflow-hidden font-sans">
    
    <!-- Navbar -->
    <header class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <img src="{{ asset('images/logo-sbum.png') }}" alt="SBUM Logo" class="h-10 w-auto">
                    <span class="font-extrabold text-2xl text-[#1f5f4d] tracking-tight">SBUM</span>
                </div>
                
                <nav class="hidden md:flex space-x-8">
                    <a href="#beranda" class="text-[#1f5f4d] font-semibold text-sm hover:text-green-600 transition">Beranda</a>
                    <a href="#fasilitas" class="text-gray-500 font-medium text-sm hover:text-[#1f5f4d] transition">Fasilitas</a>
                    <a href="#alur" class="text-gray-500 font-medium text-sm hover:text-[#1f5f4d] transition">Alur Peminjaman</a>
                    <a href="#tentang" class="text-gray-500 font-medium text-sm hover:text-[#1f5f4d] transition">Tentang</a>
                    <a href="#kontak" class="text-gray-500 font-medium text-sm hover:text-[#1f5f4d] transition">Kontak</a>
                </nav>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-bold text-[#1f5f4d] bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition shadow-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-[#1f5f4d] rounded-xl hover:bg-[#184b3c] transition shadow-md shadow-green-900/20">Daftar</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="pt-20">
        <section id="beranda" class="relative bg-gradient-to-b from-[#1f5f4d] to-[#184b3c] overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img src="{{ asset('images/gedungpoli.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-20 mix-blend-overlay">
                <div class="absolute inset-0 bg-[#1f5f4d]/80 mix-blend-multiply"></div>
            </div>

            <!-- Decorative Circles -->
            <div class="absolute top-[-150px] left-[-120px] w-[320px] h-[320px] rounded-full bg-white/5 blur-3xl"></div>
            <div class="absolute top-[-60px] right-[-130px] w-[420px] h-[420px] rounded-full bg-white/5 blur-3xl"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 flex flex-col lg:flex-row items-center gap-12">
                <!-- Text Content -->
                <div class="lg:w-1/2 text-left z-10">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white/90 text-xs font-bold uppercase tracking-wider mb-6 border border-white/10 backdrop-blur-sm">
                        <i class="bi bi-shield-check"></i>
                        <span>Sistem Booking umum & Manajemen Fasilitas</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-[1.15] mb-6 tracking-tight">
                        Solusi Mudah untuk <br class="hidden md:block"/>
                        <span class="text-green-300 text-transparent bg-clip-text bg-gradient-to-r from-green-300 to-emerald-100">Peminjaman Fasilitas</span> Kampus
                    </h1>
                    <p class="text-lg text-white/80 mb-10 max-w-xl leading-relaxed">
                        SBUM membantu mahasiswa dan admin dalam mengelola pengajuan fasilitas kampus secara cepat, transparan, dan efisien. Mulai dari aula, laboratorium, ruang seminar, hingga lapangan dapat diajukan dalam satu dashboard yang rapi.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#fasilitas" class="inline-flex justify-center items-center px-8 py-4 text-base font-bold text-[#1f5f4d] bg-white rounded-xl hover:bg-gray-50 transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 duration-200">
                            Ajukan Peminjaman
                        </a>
                        <a href="#fitur" class="inline-flex justify-center items-center px-8 py-4 text-base font-bold text-white bg-white/10 border border-white/20 rounded-xl hover:bg-white/20 transition backdrop-blur-sm">
                            Lihat Fasilitas
                        </a>
                    </div>
                </div>

                <!-- Dashboard Mockup -->
                <div class="lg:w-1/2 w-full z-10">
                    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-[0_32px_64px_-12px_rgba(0,0,0,0.3)] border border-white/20 overflow-hidden transform transition hover:-translate-y-2 duration-500">
                        <!-- Mockup Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center text-[#1f5f4d] font-bold border border-green-200 shadow-inner">
                                    <img src="{{ asset('images/logo-sbum.png') }}" class="w-6 h-6 object-contain" />
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-sm">Dashboard Mahasiswa</div>
                                    <div class="text-xs text-gray-500">Ringkasan aktivitas peminjaman</div>
                                </div>
                            </div>
                            <div class="px-3 py-1.5 bg-gray-100 rounded-full text-xs font-semibold text-gray-600 flex items-center gap-1.5">
                                <i class="bi bi-person-circle"></i> Mahasiswa
                            </div>
                        </div>
                        
                        <!-- Mockup Body -->
                        <div class="flex h-[380px]">
                            <!-- Sidebar -->
                            <div class="w-20 bg-gray-50/50 border-r border-gray-100 p-3 flex flex-col gap-2">
                                <div class="w-full aspect-square rounded-xl bg-white border border-gray-100 shadow-sm flex items-center justify-center text-[#1f5f4d]"><i class="bi bi-grid-fill"></i></div>
                                <div class="w-full aspect-square rounded-xl flex items-center justify-center text-gray-400 hover:bg-white transition"><i class="bi bi-building"></i></div>
                                <div class="w-full aspect-square rounded-xl flex items-center justify-center text-gray-400 hover:bg-white transition"><i class="bi bi-calendar-event"></i></div>
                                <div class="w-full aspect-square rounded-xl flex items-center justify-center text-gray-400 hover:bg-white transition"><i class="bi bi-chat-square-text"></i></div>
                            </div>
                            <!-- Content -->
                            <div class="flex-1 p-6 bg-white/50 flex flex-col gap-5">
                                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
                                    <div class="absolute right-0 top-0 w-32 h-32 bg-green-50 rounded-bl-full -z-10"></div>
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-gray-900 mb-1">Halo, PoliBatam 👋</h3>
                                            <p class="text-xs text-gray-500 max-w-[200px]">Kelola peminjaman ruangan dan fasilitas dari satu tempat.</p>
                                        </div>
                                        <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold flex items-center gap-1">
                                            <i class="bi bi-check-circle-fill"></i> Aktif
                                        </span>
                                    </div>
                                    <div class="flex gap-2 mt-4">
                                        <span class="px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-xs font-semibold text-gray-600">Ajukan</span>
                                        <span class="px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-xs font-semibold text-gray-600">Jadwal</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
                                        <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Pengajuan Aktif</div>
                                        <div class="text-3xl font-extrabold text-gray-900">2</div>
                                    </div>
                                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
                                        <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Riwayat Selesai</div>
                                        <div class="text-3xl font-extrabold text-gray-900">5</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-12 bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-[#1f5f4d] mb-2">50+</div>
                        <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Ruangan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-[#1f5f4d] mb-2">12</div>
                        <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Laboratorium</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-[#1f5f4d] mb-2">5</div>
                        <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Gedung</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-[#1f5f4d] mb-2">24/7</div>
                        <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Akses Sistem</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features / Fasilitas Section -->
        <section id="fasilitas" class="py-24 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Fasilitas yang Dapat Dipinjam</h2>
                    <p class="text-lg text-gray-600">Jelajahi berbagai fasilitas kampus yang tersedia untuk mendukung kegiatan akademik dan non-akademik Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Facility Card 1 -->
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 border border-gray-100 group">
                        <div class="h-48 bg-gradient-to-br from-amber-700 to-amber-300 relative overflow-hidden">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors duration-300"></div>
                            <span class="absolute top-4 right-4 bg-white/95 backdrop-blur text-[#1f5f4d] text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                <i class="bi bi-star-fill text-yellow-500 mr-1"></i> Populer
                            </span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Ruang Aula Utama</h3>
                            <div class="flex flex-wrap gap-3 text-sm text-gray-500 mb-4">
                                <span class="flex items-center gap-1.5"><i class="bi bi-people"></i> Kapasitas 500 org</span>
                                <span class="flex items-center gap-1.5"><i class="bi bi-display"></i> Proyektor & Sound</span>
                            </div>
                            <p class="text-gray-600 text-sm line-clamp-2">Cocok untuk kegiatan seminar besar, kuliah umum, atau acara seremonial tingkat kampus.</p>
                        </div>
                    </div>
                    
                    <!-- Facility Card 2 -->
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 border border-gray-100 group">
                        <div class="h-48 bg-gradient-to-br from-sky-700 to-sky-300 relative overflow-hidden">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors duration-300"></div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Laboratorium Komputer</h3>
                            <div class="flex flex-wrap gap-3 text-sm text-gray-500 mb-4">
                                <span class="flex items-center gap-1.5"><i class="bi bi-people"></i> Kapasitas 40 org</span>
                                <span class="flex items-center gap-1.5"><i class="bi bi-pc-display"></i> 40 PC High-end</span>
                            </div>
                            <p class="text-gray-600 text-sm line-clamp-2">Fasilitas lab lengkap dengan spesifikasi komputer tinggi untuk kebutuhan praktikum IT.</p>
                        </div>
                    </div>

                    <!-- Facility Card 3 -->
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 border border-gray-100 group">
                        <div class="h-48 bg-gradient-to-br from-green-700 to-green-300 relative overflow-hidden">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors duration-300"></div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Lapangan Olahraga</h3>
                            <div class="flex flex-wrap gap-3 text-sm text-gray-500 mb-4">
                                <span class="flex items-center gap-1.5"><i class="bi bi-geo-alt"></i> Outdoor</span>
                                <span class="flex items-center gap-1.5"><i class="bi bi-brightness-high"></i> Penerangan Malam</span>
                            </div>
                            <p class="text-gray-600 text-sm line-clamp-2">Fasilitas olahraga outdoor yang mencakup lapangan basket, futsal, dan voli.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-[#1f5f4d] font-bold hover:text-green-800 transition">
                        Lihat semua fasilitas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gradient-to-br from-[#184b3c] to-[#1f5f4d] pt-16 pb-8 border-t border-[#184b3c]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div class="md:col-span-1">
                        <div class="flex items-center gap-3 mb-6">
                            <img src="{{ asset('images/logo-sbum.png') }}" alt="SBUM Logo" class="h-10 w-auto brightness-0 invert">
                            <span class="font-extrabold text-2xl text-white tracking-tight">SBUM</span>
                        </div>
                        <p class="text-white/70 text-sm leading-relaxed mb-6">
                            Sistem Booking umum & Manajemen Fasilitas Politeknik Negeri Batam. Mengelola peminjaman dengan lebih mudah dan transparan.
                        </p>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-white font-bold mb-6">Tautan Cepat</h4>
                        <ul class="space-y-3">
                            <li><a href="#beranda" class="text-white/70 hover:text-white transition text-sm">Beranda</a></li>
                            <li><a href="#fasilitas" class="text-white/70 hover:text-white transition text-sm">Fasilitas</a></li>
                            <li><a href="#alur" class="text-white/70 hover:text-white transition text-sm">Alur Peminjaman</a></li>
                            <li><a href="#tentang" class="text-white/70 hover:text-white transition text-sm">Tentang</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-6">Bantuan</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-white/70 hover:text-white transition text-sm">FAQ</a></li>
                            <li><a href="#" class="text-white/70 hover:text-white transition text-sm">Panduan Penggunaan</a></li>
                            <li><a href="#" class="text-white/70 hover:text-white transition text-sm">Syarat & Ketentuan</a></li>
                            <li><a href="#" class="text-white/70 hover:text-white transition text-sm">Kebijakan Privasi</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-6">Kontak</h4>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <i class="bi bi-geo-alt text-white/50 mt-1"></i>
                                <span class="text-white/70 text-sm">Jl. Ahmad Yani Batam Kota, Kota Batam, Kepulauan Riau, Indonesia</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-envelope text-white/50"></i>
                                <span class="text-white/70 text-sm">info@polibatam.ac.id</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-telephone text-white/50"></i>
                                <span class="text-white/70 text-sm">+62 778 469858</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-white/50">
                    <p>&copy; 2026 Sistem Booking Umum & Manajemen Fasilitas. All rights reserved.</p>
                    <p>Designed for TRPL PBL 209 2A MALAM</p>
                </div>
            </div>
        </footer>
    </main>
</div>
@endsection
