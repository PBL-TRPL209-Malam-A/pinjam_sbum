@extends('layout.app')

@section('content')
<style>
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #1f1f1f;
    }

    .login-page {
        min-height: 100vh;
        background: #1f1f1f;
        padding: 18px;
    }

    .page-label {
        color: #7a7a7a;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .login-wrapper {
        position: relative;
        min-height: calc(100vh - 70px);
        background: #f6f1e9;
        border-radius: 24px;
        border: 1px solid #e8dfd1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 40px 20px;
    }

    .login-card {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 520px;
        background: #fbfaf7;
        border-radius: 28px;
        padding: 40px 34px 30px;
        border: 1px solid #e7dfd3;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
    }

    .login-title {
        text-align: center;
        font-size: 32px;
        font-weight: 800;
        color: #2b3a31;
        margin-bottom: 8px;
    }

    .login-subtitle {
        text-align: center;
        color: #8e948e;
        font-size: 14px;
        margin-bottom: 28px;
    }

    .custom-label {
        font-size: 14px;
        font-weight: 700;
        color: #2f2f2f;
        margin-bottom: 8px;
    }

    .custom-input-group {
        border: 1px solid #ddd6cb;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }

    .custom-input-group .input-group-text {
        background: #fff;
        border: 0;
        color: #9aa195;
        padding-left: 12px;
        padding-right: 6px;
    }

    .custom-input-group .form-control {
        border: 0;
        box-shadow: none !important;
        height: 50px;
        font-size: 15px;
        color: #333;
    }

    .custom-input-group .form-control::placeholder {
        color: #a7a7a7;
    }

    .forgot-password {
        text-align: center;
        margin: 16px 0 12px;
    }

    .forgot-password a {
        color: #ff3b30;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
    }

    .login-btn {
        height: 50px;
        border: 0;
        border-radius: 12px;
        background-color: #476f5b;
        color: #fff;
        font-size: 16px;
        font-weight: 700;
    }

    .login-btn:hover {
        background-color: #3d614f;
        color: #fff;
    }

    .register-text {
        margin-top: 16px;
        text-align: center;
        font-size: 14px;
        color: #8a8a8a;
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .register-text a {
        text-decoration: none;
        color: #476f5b;
        font-weight: 700;
    }

    .bg-circle {
        position: absolute;
        border-radius: 50%;
        background: #edf3ec;
        z-index: 1;
    }

    .circle-top {
        width: 220px;
        height: 220px;
        top: -10px;
        right: 70px;
    }

    .circle-bottom {
        width: 240px;
        height: 240px;
        left: -30px;
        bottom: -40px;
    }

    @media (max-width: 768px) {
        .login-card {
            padding: 30px 20px 24px;
            border-radius: 22px;
        }

        .login-title {
            font-size: 28px;
        }

        .circle-top {
            width: 150px;
            height: 150px;
            right: -25px;
            top: 10px;
        }

        .circle-bottom {
            width: 170px;
            height: 170px;
            left: -45px;
            bottom: -45px;
        }
    }
</style>

<div class="login-page">
    <div class="page-label">Login</div>

    <div class="login-wrapper">
        <div class="bg-circle circle-top"></div>
        <div class="bg-circle circle-bottom"></div>

        <div class="login-card">
            <h1 class="login-title">Selamat Datang</h1>
            <p class="login-subtitle">Masuk ke sistem peminjaman ruangan dan fasilitas</p>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-4">
                    <label class="form-label custom-label">Email / NIM / NIP</label>
                    <div class="input-group custom-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input
                            type="text"
                            name="login"
                            class="form-control"
                            placeholder="Masukkan email, NIM, atau NIK/NIP"
                            value="{{ old('login') }}"
                            autocomplete="username">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label custom-label">Password</label>
                    <div class="input-group custom-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            autocomplete="current-password">
                    </div>
                </div>

                <div class="forgot-password">
                    <a href="#">Lupa password?</a>
                </div>

                <button type="submit" class="btn login-btn w-100">Masuk</button>

                <div class="register-text">
                    <span>Belum punya akun?</span>
                    <a href="{{ route('register') }}">Daftar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
