@extends('layout.auth')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center py-10 px-4 relative">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        <img src="{{ asset('images/gedungpoli.jpg') }}" alt="Background" class="w-full h-full object-cover">
    </div>

    <div class="w-full max-w-[520px] bg-white/50 backdrop-blur-xl border border-white/60 rounded-[28px] p-8 md:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.12)] relative overflow-hidden mx-auto z-20">
        
        <div class="relative z-10">
            <h1 class="text-[32px] font-extrabold text-center text-gray-900 mb-2">Selamat Datang</h1>
            <p class="text-center text-gray-800 font-medium text-[14px] mb-8">Masuk ke sistem peminjaman ruangan dan fasilitas</p>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                @if(session('error'))
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-5">
                    <label class="block text-[14px] font-bold text-gray-900 mb-2">Email / NIM / NIP</label>
                    <div class="flex items-center bg-white/90 border border-white/60 rounded-xl overflow-hidden focus-within:border-[#466454] transition">
                        <span class="pl-4 pr-3 text-[#9aa195]">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input
                            type="text"
                            name="login"
                            class="w-full h-[50px] pr-4 bg-transparent outline-none text-[#333] text-[15px] placeholder:text-[#a7a7a7]"
                            placeholder="Masukkan email, NIM, atau NIK/NIP"
                            value="{{ old('login') }}"
                            autocomplete="username">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-[14px] font-bold text-gray-900 mb-2">Password</label>
                    <div class="flex items-center bg-white/90 border border-white/60 rounded-xl overflow-hidden focus-within:border-[#466454] transition">
                        <span class="pl-4 pr-3 text-[#9aa195]">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            class="w-full h-[50px] pr-4 bg-transparent outline-none text-[#333] text-[15px] placeholder:text-[#a7a7a7]"
                            placeholder="Masukkan password"
                            autocomplete="current-password">
                    </div>
                </div>

                <div class="text-center mb-6 mt-4">
                    <a href="#" class="text-[#ff3b30] hover:text-red-700 font-bold text-[14px] transition">Lupa password?</a>
                </div>

                <button type="submit" class="w-full h-[50px] bg-[#476f5b] hover:bg-[#3d614f] text-white rounded-xl font-bold text-[16px] transition">Masuk</button>

                <div class="mt-6 text-center text-[14px] text-gray-800 font-medium flex items-center justify-center gap-2">
                    <span>Belum punya akun?</span>
                    <a href="{{ route('register') }}" class="text-[#2b3a31] font-bold hover:underline">Daftar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
