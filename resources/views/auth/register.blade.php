@extends('layout.app')

@section('content')
<style>
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #1f1f1f;
    }

    .register-page {
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

    .register-wrapper {
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

    .register-card {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 560px;
        background: #fbfaf7;
        border-radius: 24px;
        padding: 36px 34px 28px;
        border: 1px solid #e7dfd3;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
    }

    .register-title {
        text-align: center;
        font-size: 28px;
        font-weight: 800;
        color: #2b3a31;
        margin-bottom: 8px;
    }

    .register-subtitle {
        text-align: center;
        color: #8e948e;
        font-size: 14px;
        margin-bottom: 28px;
    }

    .custom-label {
        font-size: 13px;
        font-weight: 700;
        color: #2f2f2f;
        margin-bottom: 8px;
    }

    .custom-label span {
        color: #d9864c;
    }

    .custom-input-group {
        border: 1px solid #ddd6cb;
        border-radius: 10px;
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
        height: 46px;
        font-size: 14px;
        color: #333;
    }

    .custom-input-group .form-control::placeholder {
        color: #a7a7a7;
    }

    .register-btn {
        height: 46px;
        border: 0;
        border-radius: 10px;
        background-color: #476f5b;
        color: #fff;
        font-size: 16px;
        font-weight: 700;
    }

    .register-btn:hover {
        background-color: #3d614f;
        color: #fff;
    }

    .login-text {
        margin-top: 12px;
        text-align: center;
        font-size: 13px;
        color: #8a8a8a;
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .login-text a {
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
        width: 170px;
        height: 170px;
        top: 24px;
        right: 65px;
    }

    .circle-bottom {
        width: 220px;
        height: 220px;
        left: -40px;
        bottom: -55px;
    }
</style>

<div class="register-page">


    <div class="register-wrapper">
        <div class="bg-circle circle-top"></div>
        <div class="bg-circle circle-bottom"></div>

        <div class="register-card">
            <h1 class="register-title">Daftar Akun</h1>
            <p class="register-subtitle">
                Lengkapi biodata singkat untuk membuat akun SBUM
            </p>

            <form action="#" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label custom-label">Nama Lengkap <span>*</span></label>
                    <div class="input-group custom-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Masukkan nama lengkap">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label custom-label">NIM <span>*</span></label>
                    <div class="input-group custom-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-card-text"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Masukkan NIM">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label custom-label">Email <span>*</span></label>
                    <div class="input-group custom-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" class="form-control" placeholder="Masukkan email">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label custom-label">Password <span>*</span></label>
                    <div class="input-group custom-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" class="form-control" placeholder="Masukkan password">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label custom-label">Konfirmasi Password <span>*</span></label>
                    <div class="input-group custom-input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" class="form-control" placeholder="Masukkan konfirmasi password">
                    </div>
                </div>

                <button type="submit" class="btn register-btn w-100">
                    Daftar
                </button>

                <div class="login-text">
                    <span>Sudah punya akun?</span>
                    <a href="login.php">Masuk</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
