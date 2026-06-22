@extends('layout.admin')

@section('page_caption', 'Atur Jadwal Penggunaan Fasilitas')
@section('page_heading', 'Admin SBUM')

@section('admin_content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .slot-row {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background-color: #f7f3eb;
        border-radius: 1rem;
        padding: 0.75rem 1rem;
        text-align: center;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 1.5rem;
    }
    .slot-block-container {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .slot-block {
        border-radius: 1rem;
        padding: 1.5rem 1rem;
        text-align: center;
        font-weight: bold;
        transition: 0.2s;
    }
    .slot-available {
        background-color: #e2f0d9;
        color: #385723;
        border: 1px solid #c5e1b5;
    }
    .slot-booked {
        background-color: #fcebeb;
        color: #8b3c3c;
        border: 1px solid #f7d1d1;
    }
    .slot-pending {
        background-color: #fcf1d3;
        color: #7d6006;
        border: 1px solid #f9e2ae;
    }
    .btn-save-schedule {
        background-color: var(--primary-main);
        color: white;
        font-weight: 600;
        border-radius: 1rem;
        height: 48px;
        padding: 0 2.5rem;
        border: none;
        transition: 0.2s;
    }
    .btn-save-schedule:hover {
        background-color: var(--primary-dark);
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Kelola jadwal agar tidak bentrok</h2>
        <p class="mb-0 text-secondary text-wrap" style="max-width: 650px;">
            Admin mengatur slot penggunaan fasilitas dan memvalidasi konflik jadwal sebelum menyimpan.
        </p>
    </div>
</div>

<!-- Selector Form -->
<form action="{{ route('admin.jadwal') }}" method="GET">
    <div class="card border-0 rounded-4 p-3 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
        <div class="row align-items-end g-3">
            <div class="col-md-4">
                <label class="form-label text-secondary small fw-bold">Fasilitas</label>
                <select name="ruangan_id" class="form-select border-0 bg-light rounded-3" style="height: 44px;" onchange="this.form.submit()">
                    @foreach($ruangan as $room)
                        <option value="{{ $room->id_ruangan }}" {{ $room->id_ruangan == $selectedRuanganId ? 'selected' : '' }}>
                            {{ $room->nama_ruangan }}
                        </option>
                    @endforeach
                    @if(count($ruangan) == 0)
                        <option value="1">Aula Utama</option>
                        <option value="2">Lab Komputer 1</option>
                    @endif
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label text-secondary small fw-bold">Tanggal</label>
                <input type="date" name="tanggal" class="form-control border-0 bg-light rounded-3" style="height: 44px;" value="{{ $selectedDate }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-4 text-md-end">
                <button type="button" class="btn btn-main w-100 w-md-auto" style="height: 44px;">Tambah Slot</button>
            </div>
        </div>
    </div>
</form>

<!-- Calendar Slot Area -->
<div class="mb-3 fw-semibold text-secondary">Calendar Slot</div>
<div class="card border-0 rounded-4 p-4 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
    <!-- Time Slots Header -->
    <div class="slot-row">
        <div>08.00</div>
        <div>09.00</div>
        <div>10.00</div>
        <div>11.00</div>
        <div>12.00</div>
        <div>13.00</div>
        <div>14.00</div>
    </div>

    <!-- Availability Status Blocks -->
    <div class="slot-block-container">
        <div class="slot-block slot-available">Tersedia</div>
        <div class="slot-block slot-booked">Dipinjam</div>
        <div class="slot-block slot-booked">Dipinjam</div>
        <div class="slot-block slot-pending">Pending</div>
        <div class="slot-block slot-available">Tersedia</div>
        <div class="slot-block slot-available">Tersedia</div>
        <div class="slot-block slot-available">Tersedia</div>
    </div>
</div>

<!-- Validasi Konflik Warning Banner -->
<form action="{{ route('admin.jadwal.store') }}" method="POST">
    @csrf
    <div class="mb-3 fw-semibold text-secondary">Validasi Konflik</div>
    <div class="card border-0 rounded-4 p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" style="background: #fffdfa; border: 1px solid var(--line) !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="alert border-0 rounded-3 p-2 d-flex align-items-center mb-0" style="background-color: #fdf1d3; color: #7d6006;">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <span class="fw-semibold small">Warning: Jadwal Bentrok</span>
            </div>
            <div class="text-muted small">Jika ada slot bentrok, sistem menampilkan warning sebelum admin menyimpan jadwal.</div>
        </div>
        <button type="submit" class="btn btn-save-schedule">Simpan Jadwal</button>
    </div>
</form>
@endsection
