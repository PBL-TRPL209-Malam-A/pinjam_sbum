@extends('layout.app_tailwind')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-6">
    <div>
        <div class="text-[19px] text-[#7d8781] mb-1">Kepala SBUM</div>
        <h1 class="text-[32px] font-bold text-[#466454] leading-tight mb-2">Kelola Data Staff SBUM</h1>
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

                <div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-[#466454] mb-2">Kelola akun dan data staff SBUM</h2>
                    <p class="mb-0 text-[#54615b]">
                        Layar ini dibuat sebagai penyesuaian kebutuhan baru agar Kepala SBUM bisa mengatur staff, role, dan status akun operasional.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 items-center mb-6">
                    <button class="bg-[#466454] hover:bg-[#395244] text-white px-5 py-2.5 rounded-[14px] font-semibold transition" data-bs-toggle="modal" data-bs-target="#tambahStaffModal">Tambah Staff</button>
                    
                    <select class="bg-white border border-[#e6ddd2] text-[#54615b] px-4 py-2.5 rounded-[14px] font-medium focus:outline-none focus:border-[#466454]" id="roleFilter">
                        <option value="all">Filter Role (Semua)</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->nama_role }}">{{ $role->nama_role }}</option>
                        @endforeach
                    </select>
                    
                    <select class="bg-white border border-[#e6ddd2] text-[#54615b] px-4 py-2.5 rounded-[14px] font-medium focus:outline-none focus:border-[#466454]" id="statusFilter">
                        <option value="all">Status Akun (Semua)</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] overflow-hidden shadow-sm mb-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-[#f5f2ec] text-[#466454]">
                                <tr>
                                    <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Nama Staff</th>
                                    <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Role</th>
                                    <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Email</th>
                                    <th class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Status</th>
                                    <th style="text-align: right; padding-right: 2rem;" class="p-4 font-semibold text-sm border-b border-[#e6ddd2]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staff as $s)
                                <tr class="staff-row" data-role="{{ $s->roles->first()->nama_role ?? '' }}" data-status="{{ $s->status ? 'Aktif' : 'Nonaktif' }}">
                                    <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">
                                        <div class="font-semibold text-[#466454]">{{ $s->nama_lengkap }}</div>
                                        @if($s->nik)
                                            <div class="text-[#7d8781] text-sm mt-1">NIP/NIK: {{ $s->nik }}</div>
                                        @elseif($s->nim)
                                            <div class="text-[#7d8781] text-sm mt-1">NIM: {{ $s->nim }}</div>
                                        @endif
                                    </td>
                                    <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">
                                        @foreach($s->roles as $role)
                                            <span class="block">{{ $role->nama_role }}</span>
                                        @endforeach
                                    </td>
                                    <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">{{ $s->email }}</td>
                                    <td class="p-4 border-b border-[#e6ddd2] text-[#54615b]">
                                        @if($s->status)
                                            <span class="inline-block bg-[#d1fae5] text-[#065f46] text-[13px] font-semibold px-3 py-1 rounded-full">Aktif</span>
                                        @else
                                            <span class="inline-block bg-[#f3f4f6] text-[#4b5563] text-[13px] font-semibold px-3 py-1 rounded-full">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right; padding-right: 1.5rem;" class="p-4 border-b border-[#e6ddd2] text-[#54615b]">
                                        <div class="inline-flex gap-2 items-center">
                                            <button class="border border-[#e6ddd2] text-[#466454] px-4 py-2 rounded-xl font-semibold hover:bg-[#f5f2ec] transition" data-bs-toggle="modal" data-bs-target="#editStaffModal{{ $s->id_user }}">Ubah</button>
                                            
                                            <form action="{{ route('kepalasbum.staff.destroy', $s->id_user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus staf {{ $s->nama_lengkap }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-semibold transition">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align: center;" class="p-4 border-b border-[#e6ddd2] text-[#7d8781]">Belum ada data staf.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tambah Staff Modal -->
                <div class="modal fade" id="tambahStaffModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-[24px] border-0 shadow-lg">
                            <div class="modal-header border-b border-[#e6ddd2] p-6">
                                <h5 class="text-xl font-bold text-[#466454]">Tambah Staf Baru</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('kepalasbum.staff.store') }}" method="POST">
                                @csrf
                                <div class="modal-body p-6" style="text-align: left;">
                                    <div class="mb-4">
                                        <label class="block text-[#54615b] font-medium mb-2 text-sm">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" class="w-full bg-[#fcfbf8] border border-[#e6ddd2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#466454] transition" placeholder="Masukkan nama lengkap staf" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-[#54615b] font-medium mb-2 text-sm">NIK / NIP</label>
                                        <input type="text" name="nik" class="w-full bg-[#fcfbf8] border border-[#e6ddd2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#466454] transition" placeholder="Masukkan NIK atau NIP" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-[#54615b] font-medium mb-2 text-sm">Role</label>
                                        <select name="role_id" class="w-full bg-[#fcfbf8] border border-[#e6ddd2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#466454] transition" required>
                                            <option value="" disabled selected>Pilih Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id_role }}">{{ $role->nama_role }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-[#54615b] font-medium mb-2 text-sm">Email</label>
                                        <input type="email" name="email" class="w-full bg-[#fcfbf8] border border-[#e6ddd2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#466454] transition" placeholder="staf@sbum.ac.id" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-[#54615b] font-medium mb-2 text-sm">Password</label>
                                        <input type="password" name="password" class="w-full bg-[#fcfbf8] border border-[#e6ddd2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#466454] transition" placeholder="Masukkan password (min. 6 karakter)" required>
                                    </div>
                                </div>
                                <div class="modal-footer border-t border-[#e6ddd2] p-6">
                                    <button type="button" class="border border-[#e6ddd2] text-[#466454] px-4 py-2 rounded-xl font-semibold hover:bg-[#f5f2ec] transition" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block">Tambah Staf</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Staff Modals -->
                @foreach($staff as $s)
                <div class="modal fade" id="editStaffModal{{ $s->id_user }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-[24px] border-0 shadow-lg">
                            <div class="modal-header border-b border-[#e6ddd2] p-6">
                                <h5 class="text-xl font-bold text-[#466454]">Ubah Data Staf</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('kepalasbum.staff.update', $s->id_user) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body p-6" style="text-align: left;">
                                    <div class="mb-4">
                                        <label class="block text-[#54615b] font-medium mb-2 text-sm">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" class="w-full bg-[#fcfbf8] border border-[#e6ddd2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#466454] transition" value="{{ $s->nama_lengkap }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-[#54615b] font-medium mb-2 text-sm">NIK / NIP</label>
                                        <input type="text" name="nik" class="w-full bg-[#fcfbf8] border border-[#e6ddd2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#466454] transition" value="{{ $s->nik }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-[#54615b] font-medium mb-2 text-sm">Role</label>
                                        <select name="role_id" class="w-full bg-[#fcfbf8] border border-[#e6ddd2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#466454] transition" required>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id_role }}" {{ $s->roles->contains('id_role', $role->id_role) ? 'selected' : '' }}>
                                                    {{ $role->nama_role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-[#54615b] font-medium mb-2 text-sm">Email</label>
                                        <input type="email" name="email" class="w-full bg-[#fcfbf8] border border-[#e6ddd2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#466454] transition" value="{{ $s->email }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-[#54615b] font-medium mb-2 text-sm">Status Akun</label>
                                        <select name="status" class="w-full bg-[#fcfbf8] border border-[#e6ddd2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#466454] transition" required>
                                            <option value="1" {{ $s->status ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ !$s->status ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-[#54615b] font-medium mb-2 text-sm">Password Baru</label>
                                        <input type="password" name="password" class="w-full bg-[#fcfbf8] border border-[#e6ddd2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#466454] transition" placeholder="Kosongkan jika tidak ingin mengubah">
                                    </div>
                                </div>
                                <div class="modal-footer border-t border-[#e6ddd2] p-6">
                                    <button type="button" class="border border-[#e6ddd2] text-[#466454] px-4 py-2 rounded-xl font-semibold hover:bg-[#f5f2ec] transition" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="bg-[#fcfbf8] border border-[#e6ddd2] rounded-[24px] p-6 mb-6">
                                    <h3 class="text-xl font-bold text-[#466454] mb-2">Hak Akses Staff</h3>
                                    <p class="text-[#7d8781]">
                                        Role yang bisa dikelola misalnya Admin SBUM, PIC, Pamdal, dan staff lain yang terlibat di operasional peminjaman.
                                    </p>
                                </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('.staff-row');

    function filterTable() {
        const selectedRole = roleFilter.value;
        const selectedStatus = statusFilter.value;

        rows.forEach(row => {
            const role = row.getAttribute('data-role');
            const status = row.getAttribute('data-status');

            const roleMatch = (selectedRole === 'all' || role === selectedRole);
            const statusMatch = (selectedStatus === 'all' || status === selectedStatus);

            if (roleMatch && statusMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    if (roleFilter) roleFilter.addEventListener('change', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);
});
</script>
@endsection
