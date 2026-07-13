@extends('layout.auth')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center py-10 px-4 relative">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        <img src="{{ asset('images/gedungpoli.jpg') }}" alt="Background" class="w-full h-full object-cover">
    </div>

    <div class="w-full max-w-[560px] bg-white/50 backdrop-blur-xl border border-white/60 rounded-[24px] p-8 md:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.12)] relative overflow-hidden mx-auto z-20">
        
        <div class="relative z-10">
            <h1 class="text-[28px] font-extrabold text-center text-gray-900 mb-2">Daftar Akun</h1>
            <p class="text-center text-gray-800 font-medium text-[14px] mb-8">Lengkapi biodata singkat untuk membuat akun peminjam</p>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                @if($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                        <ul class="list-disc pl-5 mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-[13px] font-bold text-gray-900 mb-2">Nama Lengkap <span class="text-[#d68a52]">*</span></label>
                        <div class="flex items-center bg-white/90 border border-white/60 rounded-[10px] overflow-hidden focus-within:border-[#466454] transition">
                            <span class="pl-3.5 pr-2.5 text-[#9aa195]">
                                <i class="bi bi-person"></i>
                            </span>
                            <input
                                type="text"
                                name="nama_lengkap"
                                class="w-full h-[46px] pr-3 bg-transparent outline-none text-[#333] text-[14px] placeholder:text-[#a5a5a5]"
                                placeholder="Nama lengkap Anda"
                                value="{{ old('nama_lengkap') }}" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-gray-900 mb-2">NIM / NIK / NIP <span class="text-[#d68a52]">*</span></label>
                        <div class="flex items-center bg-white/90 border border-white/60 rounded-[10px] overflow-hidden focus-within:border-[#466454] transition">
                            <span class="pl-3.5 pr-2.5 text-[#9aa195]">
                                <i class="bi bi-card-text"></i>
                            </span>
                            <input
                                type="text"
                                name="nim"
                                class="w-full h-[46px] pr-3 bg-transparent outline-none text-[#333] text-[14px] placeholder:text-[#a5a5a5]"
                                placeholder="Nomor identitas"
                                value="{{ old('nim') }}" required>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-[13px] font-bold text-gray-900 mb-2">No. Telepon / WhatsApp <span class="text-[#d68a52]">*</span></label>
                    <div class="flex items-center bg-white/90 border border-white/60 rounded-[10px] overflow-hidden focus-within:border-[#466454] transition">
                        <span class="pl-3.5 pr-2.5 text-[#9aa195]">
                            <i class="bi bi-telephone"></i>
                        </span>
                        <input
                            type="text"
                            name="no_telepon"
                            class="w-full h-[46px] pr-3 bg-transparent outline-none text-[#333] text-[14px] placeholder:text-[#a5a5a5]"
                            placeholder="Contoh: 081234567890"
                            value="{{ old('no_telepon') }}" required>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-[13px] font-bold text-gray-900 mb-2">Alamat Email <span class="text-[#d68a52]">*</span></label>
                    <div class="flex items-center bg-white/90 border border-white/60 rounded-[10px] overflow-hidden focus-within:border-[#466454] transition">
                        <span class="pl-3.5 pr-2.5 text-[#9aa195]">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input
                            type="email"
                            name="email"
                            class="w-full h-[46px] pr-3 bg-transparent outline-none text-[#333] text-[14px] placeholder:text-[#a5a5a5]"
                            placeholder="Email aktif"
                            value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                    <div>
                        <label class="block text-[13px] font-bold text-gray-900 mb-2">Password <span class="text-[#d68a52]">*</span></label>
                        <div class="flex items-center bg-white/90 border border-white/60 rounded-[10px] overflow-hidden focus-within:border-[#466454] transition">
                            <span class="pl-3.5 pr-2.5 text-[#9aa195]">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input
                                type="password"
                                name="password"
                                class="w-full h-[46px] pr-3 bg-transparent outline-none text-[#333] text-[14px] placeholder:text-[#a5a5a5]"
                                placeholder="Min. 6 karakter" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-gray-900 mb-2">Konfirmasi Password <span class="text-[#d68a52]">*</span></label>
                        <div class="flex items-center bg-white/90 border border-white/60 rounded-[10px] overflow-hidden focus-within:border-[#466454] transition">
                            <span class="pl-3.5 pr-2.5 text-[#9aa195]">
                                <i class="bi bi-shield-lock"></i>
                            </span>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="w-full h-[46px] pr-3 bg-transparent outline-none text-[#333] text-[14px] placeholder:text-[#a5a5a5]"
                                placeholder="Ulangi password" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full h-[46px] bg-[#476f5b] hover:bg-[#3e624f] text-white rounded-[10px] font-bold text-[16px] transition">Daftar Sekarang</button>

                <div class="mt-4 text-center text-[13px] text-gray-800 font-medium flex items-center justify-center gap-2">
                    <span>Sudah punya akun?</span>
                    <a href="{{ route('login') }}" class="text-[#2b3a31] font-bold hover:underline">Masuk</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
