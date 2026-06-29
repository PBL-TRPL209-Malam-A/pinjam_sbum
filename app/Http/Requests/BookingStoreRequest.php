<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Schedule;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\DetailPeminjamanBarang;

class BookingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'facility_id' => 'required|string',
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
            'jumlah_peserta' => 'required|integer|min:1',
            'dosen_id' => 'required|integer|exists:user,id_user',
            'keterangan' => 'nullable|string',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $facility_id = $this->input('facility_id');
            $tanggal = $this->input('tanggal');
            $jam_mulai = $this->input('jam_mulai');
            $jam_selesai = $this->input('jam_selesai');
 
            if (!$facility_id || !$tanggal || !$jam_mulai || !$jam_selesai) {
                return;
            }
 
            $parts = explode('-', $facility_id, 2);
            if (count($parts) < 2) {
                $validator->errors()->add('facility_id', 'Fasilitas tidak valid.');
                return;
            }
 
            $type = strtolower($parts[0]);
            $id = $parts[1];
 
            // 1. Operational Hour limits check (08:00 to 18:00)
            $startHour = (int)explode(':', $jam_mulai)[0];
            $endHour = (int)explode(':', $jam_selesai)[0];
 
            if ($startHour < 8 || $endHour > 18 || $startHour >= $endHour) {
                $validator->errors()->add('facility_id', 'Kamu tidak bisa melakukan peminjaman dikarenakan jadwal sudah dipinjam atau meminjam ruangan melebihi batas operasional.');
                return;
            }
 
            if ($type === 'ruangan') {
                $ruangan = \App\Models\Ruangan::find($id);
                if (!$ruangan || $ruangan->status_ruangan === 'maintenance' || $ruangan->status_ruangan === 'tidak tersedia') {
                    $validator->errors()->add('facility_id', 'Fasilitas ini sedang dalam masa perawatan dan tidak dapat dipinjam.');
                    return;
                }
 
                // Double check active bookings in the peminjaman table using exact time overlap query
                // Exclude status ditolak, dibatalkan, selesai
                $activeStatuses = ['menunggu_dosen', 'menunggu_admin', 'menunggu_kepala', 'menunggu_pic', 'siap_digunakan', 'pending', 'disetujui'];
                
                $overlapExists = Peminjaman::whereHas('ruangan', function ($q) use ($id) {
                        $q->where('ruangan.id_ruangan', $id);
                    })
                    ->whereDate('tanggal_pengajuan', $tanggal)
                    ->whereIn('status', $activeStatuses)
                    ->where('jam_mulai', '<', $jam_selesai)
                    ->where('jam_selesai', '>', $jam_mulai)
                    ->exists();
 
                if ($overlapExists) {
                    $validator->errors()->add('facility_id', 'Kamu tidak bisa melakukan peminjaman dikarenakan jadwal sudah dipinjam atau meminjam ruangan melebihi batas operasional.');
                    return;
                }
 
                // Also double check Admin-configured schedules
                $slotOverlap = Schedule::where('ruangan_id', $id)
                    ->where('tanggal', $tanggal)
                    ->where('status', 'dipinjam')
                    ->where('jam_mulai', '<', $jam_selesai)
                    ->where('jam_selesai', '>', $jam_mulai)
                    ->exists();
 
                if ($slotOverlap) {
                    $validator->errors()->add('facility_id', 'Kamu tidak bisa melakukan peminjaman dikarenakan jadwal sudah dipinjam atau meminjam ruangan melebihi batas operasional.');
                    return;
                }
            } elseif ($type === 'inventaris') {
                $barang = Barang::find($id);
                if (!$barang) {
                    $validator->errors()->add('facility_id', 'Barang inventaris tidak ditemukan.');
                    return;
                }
 
                // Check simultaneous stock allocations for each hour in [startHour, endHour]
                $activeStatuses = ['menunggu_dosen', 'menunggu_admin', 'menunggu_kepala', 'menunggu_pic', 'siap_digunakan', 'pending', 'disetujui'];
 
                for ($hour = $startHour; $hour < $endHour; $hour++) {
                    $hourStr = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
                    $allocatedQty = DetailPeminjamanBarang::where('barang_id', $id)
                        ->whereHas('peminjaman', function ($q) use ($tanggal, $hourStr, $activeStatuses) {
                            $q->whereDate('tanggal_pengajuan', $tanggal)
                                ->whereIn('status', $activeStatuses)
                                ->where('jam_mulai', '<=', $hourStr)
                                ->where('jam_selesai', '>', $hourStr);
                        })
                        ->sum('jumlah');
 
                    if ($allocatedQty + 1 > $barang->stok_tersedia) {
                        $validator->errors()->add('facility_id', 'Kamu tidak bisa melakukan peminjaman dikarenakan jadwal sudah dipinjam atau meminjam ruangan melebihi batas operasional.');
                        return;
                    }
                }
            }
        });
    }
}
