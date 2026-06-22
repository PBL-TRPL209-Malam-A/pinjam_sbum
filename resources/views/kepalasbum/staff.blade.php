@extends('layout.app')

@section('content')
<style>
    :root{
        --page-bg:#f5f2ec;
        --panel-bg:#fcfbf8;
        --soft-bg:#edf2ea;
        --line:#e7ddd1;
        --text-main:#33403b;
        --text-muted:#7b8681;
        --primary-main:#5d7d6b;
        --primary-dark:#496454;
        --soft-green:#dfe9df;
        --red-main:#b85c5c;
        --red-dark:#9c4a4a;
    }

    body{
        background:var(--page-bg);
        color:var(--text-main);
        font-family: Arial, Helvetica, sans-serif;
    }

    .app-shell{
        background:var(--panel-bg);
        border:1px solid var(--line);
        border-radius:2rem;
        overflow:hidden;
        min-height:calc(100vh - 3rem);
    }

    .sidebar-panel{
        min-height:100%;
        border-right:1px solid var(--line);
        background:rgba(255,255,255,.25);
    }

    .logo-box{
        display:flex;
        align-items:center;
        gap:.75rem;
    }

    .logo-box img{
        width:48px;
        height:auto;
        object-fit:contain;
    }

    .logo-text{
        font-size:1.2rem;
        font-weight:700;
        color:#55615b;
    }

    .sidebar-link{
        color:#55615b;
        border-radius:1rem;
        padding:.95rem 1rem;
        text-decoration:none;
        display:flex;
        align-items:center;
        gap:.75rem;
        transition:.2s ease;
    }

    .sidebar-link:hover{
        background:#f3f6f3;
        color:var(--primary-dark);
    }

    .sidebar-link.active{
        background:#edf3ee;
        color:var(--primary-dark);
        font-weight:600;
    }

    .sidebar-dot{
        width:1.35rem;
        height:1.35rem;
        border-radius:50%;
        background:#dfe7df;
        flex-shrink:0;
    }

    .page-caption{
        color:var(--text-muted);
        font-size:1.25rem;
        margin-bottom:1.2rem;
    }

    .page-heading{
        font-size:1.35rem;
        font-weight:500;
        margin-bottom:0;
    }

    .search-input{
        height:3rem;
        border-radius:1rem;
        border:1px solid #ddd2c5;
        background:#fffdfa;
    }

    .search-dot{
        width:2.25rem;
        height:2.25rem;
        background:#cfdacd;
        border-radius:50%;
        flex-shrink:0;
    }

    .intro-card{
        background:var(--soft-bg);
        border:1px solid #dfe7dc;
        border-radius:1.75rem;
    }

    .btn-main{
        background:var(--primary-main);
        border:0;
        border-radius:1rem;
        min-width:140px;
        height:44px;
        color:#fff;
        font-weight:600;
        transition: 0.2s;
    }

    .btn-main:hover{
        background:var(--primary-dark);
        color:#fff;
    }

    .btn-soft-filter{
        background:#fffdfa;
        border:1px solid #dfd4c8;
        border-radius:1rem;
        height:44px;
        min-width:140px;
        color:#5f6963;
        font-weight:500;
        transition: 0.2s;
    }

    .btn-soft-filter:hover{
        background:#f7f2eb;
    }

    .logout-btn{
        background:transparent;
        border:0;
        color:#5b635f;
        padding:0;
        font-size:16px;
    }

    .custom-table {
        width: 100%;
        margin-top: 1.5rem;
        border-collapse: separate;
        border-spacing: 0 0.5rem;
    }

    .custom-table th {
        color: var(--text-muted);
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--line);
    }

    .custom-table td {
        padding: 1.25rem 1rem;
        background: #fffdfa;
        border-top: 1px solid var(--line);
        border-bottom: 1px solid var(--line);
    }

    .custom-table tr td:first-child {
        border-left: 1px solid var(--line);
        border-top-left-radius: 1rem;
        border-bottom-left-radius: 1rem;
    }

    .custom-table tr td:last-child {
        border-right: 1px solid var(--line);
        border-top-right-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }

    .status-badge-active {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 100px;
        height: 28px;
        border-radius: 999px;
        background: #e5eee5;
        color: #557b58;
        border: 1px solid #d2dfd2;
        font-size: .85rem;
    }

    .status-badge-inactive {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 100px;
        height: 28px;
        border-radius: 999px;
        background: #fdf5e6;
        color: #b8860b;
        border: 1px solid #f5deb3;
        font-size: .85rem;
    }

    .btn-action-edit {
        background: #fffdfa;
        border: 1px solid #dfd4c8;
        border-radius: 0.75rem;
        padding: 0.25rem 1rem;
        color: #5f6963;
        font-weight: 500;
        text-decoration: none;
        transition: 0.2s;
        display: inline-block;
        font-size: 0.9rem;
    }

    .btn-action-edit:hover {
        background: #f7f2eb;
    }

    .btn-action-delete {
        background: var(--red-main);
        border: 0;
        border-radius: 0.75rem;
        padding: 0.25rem 1rem;
        color: #fff;
        font-weight: 500;
        transition: 0.2s;
        display: inline-block;
        font-size: 0.9rem;
    }

    .btn-action-delete:hover {
        background: var(--red-dark);
    }

    .bottom-help-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.25rem;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    @media (max-width: 991.98px){
        .sidebar-panel{
            border-right:0;
            border-bottom:1px solid var(--line);
        }
    }

    /* Modal Form Styles */
    .modal-content-custom {
        background-color: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
    }
    .modal-header-custom {
        background-color: #f7f3eb;
        border-bottom: 1px solid var(--line);
        border-top-left-radius: 1.5rem;
        border-top-right-radius: 1.5rem;
        padding: 1.25rem 1.5rem;
    }
    .modal-title-custom {
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0;
    }
    .modal-body-custom {
        padding: 1.5rem;
    }
    .modal-footer-custom {
        border-top: 1px solid var(--line);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }
    .form-label-custom {
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }
    .form-control-custom, .form-select-custom {
        border: 1px solid #dfd4c8;
        border-radius: 0.75rem;
        background-color: #fffdfa;
        padding: 0.6rem 1rem;
        color: var(--text-main);
        width: 100%;
    }
    .form-control-custom:focus, .form-select-custom:focus {
        border-color: var(--primary-main);
        box-shadow: 0 0 0 0.2rem rgba(93, 125, 107, 0.25);
        background-color: #fffdfa;
        outline: none;
    }
    .btn-cancel-custom {
        background: white;
        border: 1px solid var(--line);
        border-radius: 1rem;
        height: 44px;
        padding: 0 1.5rem;
        color: #5f6963;
        font-weight: 500;
        transition: 0.2s;
    }
    .btn-cancel-custom:hover {
        background: #f7f2eb;
    }
</style>

<div class="container-fluid py-3 py-lg-4 px-2 px-lg-4">
    <div class="app-shell">
        <div class="row g-0">
            <aside class="col-lg-3 col-xl-2 sidebar-panel p-3 p-lg-4 d-flex flex-column">
                <div class="logo-box mb-4">
                    <img src="{{ asset('assets/images/logo-sbum-icon.png') }}" alt="SBUM">
                    <div class="logo-text">SBUM</div>
                </div>

                <nav class="nav flex-column gap-2">
                    <a href="{{ route('kepalasbum.dashboard') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Dashboard</span>
                    </a>
                    <a href="{{ route('kepalasbum.staff') }}" class="sidebar-link active">
                        <span class="sidebar-dot"></span><span>Data Staff SBUM</span>
                    </a>
                    <a href="{{ route('kepalasbum.persetujuan') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Persetujuan Akhir</span>
                    </a>
                    <a href="{{ route('kepalasbum.laporan') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Laporan Peminjaman</span>
                    </a>
                    <a href="{{ route('kepalasbum.laporan-pengembalian') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Laporan Pengembalian</span>
                    </a>
                    <a href="{{ route('kepalasbum.profil') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Profil</span>
                    </a>
                </nav>

                <div class="mt-auto pt-5 pt-lg-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </aside>

            <main class="col-lg-9 col-xl-10 p-3 p-md-4 p-xl-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">
                    <div>
                        <div class="page-caption">Kepala SBUM</div>
                        <h1 class="page-heading">Kelola Data Staff SBUM</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data" id="searchInput">
                        <button id="searchButton" class="btn search-dot d-flex align-items-center justify-content-center" style="border: none; padding: 0; cursor: pointer;" title="Cari">
                            <i class="bi bi-search" style="color: #496454; font-size: 1.1rem; font-weight: bold;"></i>
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 1rem; background-color: #e5eee5; color: #557b58; border-color: #d2dfd2;">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 1rem; background-color: #fcebeb; color: #8b3c3c; border-color: #f5d6d6;">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card intro-card shadow-none mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fs-5 fw-semibold mb-3">Kelola akun dan data staff SBUM</h2>
                        <p class="mb-0 text-secondary">
                            Layar ini dibuat sebagai penyesuaian kebutuhan baru agar Kepala SBUM bisa mengatur staff, role, dan status akun operasional.
                        </p>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-3 align-items-center my-3">
                    <button class="btn btn-main" data-bs-toggle="modal" data-bs-target="#tambahStaffModal">Tambah Staff</button>
                    
                    <select class="btn btn-soft-filter" id="roleFilter" style="border-radius: 1rem; padding: 0 1.25rem; text-align: left; appearance: none; -webkit-appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 fill=%22%235f6963%22 viewBox=%220 0 16 16%22><path d=%22M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z%22/></svg>'); background-repeat: no-repeat; background-position: right 1.25rem center; background-size: 10px; cursor: pointer;">
                        <option value="all">Filter Role (Semua)</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->nama_role }}">{{ $role->nama_role }}</option>
                        @endforeach
                    </select>
                    
                    <select class="btn btn-soft-filter" id="statusFilter" style="border-radius: 1rem; padding: 0 1.25rem; text-align: left; appearance: none; -webkit-appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 fill=%22%235f6963%22 viewBox=%220 0 16 16%22><path d=%22M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z%22/></svg>'); background-repeat: no-repeat; background-position: right 1.25rem center; background-size: 10px; cursor: pointer;">
                        <option value="all">Status Akun (Semua)</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Nama Staff</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th style="text-align: right; padding-right: 2rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staff as $s)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $s->nama_lengkap }}
                                    @if($s->nik)
                                        <div class="text-muted small fw-normal">NIP/NIK: {{ $s->nik }}</div>
                                    @elseif($s->nim)
                                        <div class="text-muted small fw-normal">NIM: {{ $s->nim }}</div>
                                    @endif
                                </td>
                                <td>
                                    @foreach($s->roles as $role)
                                        <span class="d-block">{{ $role->nama_role }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $s->email }}</td>
                                <td>
                                    @if($s->id_user != 6) {{-- Just for mockup variations --}}
                                        <span class="status-badge-active">Aktif</span>
                                    @else
                                        <span class="status-badge-inactive">Nonaktif</span>
                                    @endif
                                </td>
                                <td style="text-align: right; padding-right: 1.5rem;">
                                    <div class="d-inline-flex gap-2 align-items-center">
                                        <button class="btn-action-edit" data-bs-toggle="modal" data-bs-target="#editStaffModal{{ $s->id_user }}">Ubah</button>
                                        
                                        <form action="{{ route('kepalasbum.staff.destroy', $s->id_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus staf {{ $s->nama_lengkap }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-delete">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center;">Belum ada data staf.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Tambah Staff Modal -->
                <div class="modal fade" id="tambahStaffModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content modal-content-custom">
                            <div class="modal-header modal-header-custom">
                                <h5 class="modal-title modal-title-custom">Tambah Staf Baru</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('kepalasbum.staff.store') }}" method="POST">
                                @csrf
                                <div class="modal-body modal-body-custom" style="text-align: left;">
                                    <div class="mb-3">
                                        <label class="form-label form-label-custom">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" class="form-control form-control-custom" placeholder="Masukkan nama lengkap staf" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label form-label-custom">NIK / NIP (Nomor Induk Pegawai)</label>
                                        <input type="text" name="nik" class="form-control form-control-custom" placeholder="Masukkan NIK atau NIP" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label form-label-custom">Role</label>
                                        <select name="role_id" class="form-select form-select-custom" required>
                                            <option value="" disabled selected>Pilih Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id_role }}">{{ $role->nama_role }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label form-label-custom">Email</label>
                                        <input type="email" name="email" class="form-control form-control-custom" placeholder="staf@sbum.ac.id" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label form-label-custom">Password</label>
                                        <input type="password" name="password" class="form-control form-control-custom" placeholder="Masukkan password (min. 6 karakter)" required>
                                    </div>
                                </div>
                                <div class="modal-footer modal-footer-custom">
                                    <button type="button" class="btn btn-cancel-custom" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-main" style="min-width: 120px;">Tambah Staf</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Staff Modals -->
                @foreach($staff as $s)
                <div class="modal fade" id="editStaffModal{{ $s->id_user }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content modal-content-custom">
                            <div class="modal-header modal-header-custom">
                                <h5 class="modal-title modal-title-custom">Ubah Data Staf</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('kepalasbum.staff.update', $s->id_user) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body modal-body-custom" style="text-align: left;">
                                    <div class="mb-3">
                                        <label class="form-label form-label-custom">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" class="form-control form-control-custom" value="{{ $s->nama_lengkap }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label form-label-custom">NIK / NIP (Nomor Induk Pegawai)</label>
                                        <input type="text" name="nik" class="form-control form-control-custom" value="{{ $s->nik }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label form-label-custom">Role</label>
                                        <select name="role_id" class="form-select form-select-custom" required>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id_role }}" {{ $s->roles->contains('id_role', $role->id_role) ? 'selected' : '' }}>
                                                    {{ $role->nama_role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label form-label-custom">Email</label>
                                        <input type="email" name="email" class="form-control form-control-custom" value="{{ $s->email }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label form-label-custom">Password Baru</label>
                                        <input type="password" name="password" class="form-control form-control-custom" placeholder="Kosongkan jika tidak ingin mengubah password">
                                    </div>
                                </div>
                                <div class="modal-footer modal-footer-custom">
                                    <button type="button" class="btn btn-cancel-custom" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-main" style="min-width: 120px;">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="bottom-help-card">
                    <h3 class="fs-6 fw-bold mb-2 text-secondary">Hak Akses Staff</h3>
                    <p class="mb-0 text-secondary" style="font-size: 0.95rem;">
                        Role yang bisa dikelola misalnya Admin SBUM, PIC, Pamdal, dan staff lain yang terlibat di operasional peminjaman.
                    </p>
                </div>
            </main>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    function filterTable() {
        const searchQuery = searchInput.value.toLowerCase().trim();
        const selectedRole = roleFilter.value;
        const selectedStatus = statusFilter.value;
        const tbody = document.querySelector('.custom-table tbody');
        const tableRows = tbody.querySelectorAll('tr');

        let visibleCount = 0;

        tableRows.forEach(row => {
            // Abaikan baris "tidak ada hasil" yang mungkin dibuat dinamis
            if (row.id === 'noResultsRow') {
                row.remove();
                return;
            }
            
            // Abaikan baris default "Belum ada data staf"
            if (row.cells.length === 1 && row.cells[0].colSpan === 5 && row.innerText.includes('Belum ada data')) {
                return;
            }

            const nameText = row.cells[0].innerText.toLowerCase();
            const emailText = row.cells[2].innerText.toLowerCase();
            const roleText = row.cells[1].innerText.trim();
            const statusText = row.cells[3].innerText.trim();

            const matchesSearch = nameText.includes(searchQuery) || emailText.includes(searchQuery);
            const matchesRole = (selectedRole === 'all') || roleText.includes(selectedRole);
            const matchesStatus = (selectedStatus === 'all') || (statusText === selectedStatus);

            if (matchesSearch && matchesRole && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Kelola tampilan baris jika tidak ada hasil
        const existingNoResults = document.getElementById('noResultsRow');
        if (visibleCount === 0) {
            if (!existingNoResults) {
                const tr = document.createElement('tr');
                tr.id = 'noResultsRow';
                tr.innerHTML = '<td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">Tidak ada data staf yang cocok dengan kriteria pencarian/filter.</td>';
                tbody.appendChild(tr);
            }
        } else {
            if (existingNoResults) {
                existingNoResults.remove();
            }
        }
    }

    // Trigger pencarian hanya ketika tombol Enter ditekan pada kolom pencarian
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterTable();
        }
    });

    // Trigger pencarian ketika tombol kaca pembesar diklik
    const searchButton = document.getElementById('searchButton');
    if (searchButton) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            filterTable();
        });
    }

    // Filter status & role tetap terpicu secara instan saat opsi diubah
    roleFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>

@endsection
