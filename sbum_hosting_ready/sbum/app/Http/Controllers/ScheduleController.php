<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\Barang;
use App\Models\Schedule;
use App\Models\Peminjaman;
use App\Models\DetailPeminjamanBarang;

class ScheduleController extends Controller
{
    /**
     * Get dynamic scheduling slot data for the student view.
     */
    public function getSlots(Request $request)
    {
        try {
            $facility_id = $request->query('facility_id');
            $tanggal = $request->query('tanggal');

            if (!$facility_id || !$tanggal) {
                return response()->json(['message' => 'Fasilitas dan tanggal harus dipilih.'], 400);
            }

            $parts = explode('-', $facility_id, 2);
            if (count($parts) < 2) {
                return response()->json(['message' => 'Fasilitas tidak valid.'], 400);
            }

            $type = strtolower($parts[0]);
            $id = $parts[1];

            $slotsData = [];
            // Standard time slots starting from 08.00 to 14.00 (which spans up to 15.00)
            $defaultTimes = [
                ['start' => '08:00:00', 'end' => '09:00:00', 'label' => '08.00'],
                ['start' => '09:00:00', 'end' => '10:00:00', 'label' => '09.00'],
                ['start' => '10:00:00', 'end' => '11:00:00', 'label' => '10.00'],
                ['start' => '11:00:00', 'end' => '12:00:00', 'label' => '11.00'],
                ['start' => '12:00:00', 'end' => '13:00:00', 'label' => '12.00'],
                ['start' => '13:00:00', 'end' => '14:00:00', 'label' => '13.00'],
                ['start' => '14:00:00', 'end' => '15:00:00', 'label' => '14.00'],
            ];

            if ($type === 'ruangan') {
                // 1. Get active schedules configured by the Admin
                $slots = Schedule::with('peminjaman')
                    ->where('ruangan_id', $id)
                    ->where('tanggal', $tanggal)
                    ->orderBy('jam_mulai')
                    ->get();

                // 2. Fetch active bookings (peminjaman) for this room on this date
                $peminjamans = Peminjaman::whereHas('ruangan', function($q) use ($id) {
                        $q->where('ruangan.id_ruangan', $id);
                    })
                    ->whereDate('tanggal_pengajuan', $tanggal)
                    ->whereIn('status', ['menunggu_dosen', 'menunggu_admin', 'menunggu_kepala', 'menunggu_pic', 'siap_digunakan'])
                    ->get();

                if ($slots->isEmpty()) {
                    // Generate default slots
                    foreach ($defaultTimes as $t) {
                        $status = 'tersedia';
                        $associatedBooking = null;
                        
                        $startHour = (int)explode(':', $t['start'])[0];
                        $endHour = (int)explode(':', $t['end'])[0];

                        foreach ($peminjamans as $p) {
                            $pHourStart = (int)explode(':', $p->jam_mulai)[0];
                            $pHourEnd = (int)explode(':', $p->jam_selesai)[0];
                            
                            // Check overlap: max(start1, start2) < min(end1, end2)
                            if (max($pHourStart, $startHour) < min($pHourEnd, $endHour)) {
                                $status = $p->status === 'siap_digunakan' ? 'dipinjam' : 'pending';
                                $associatedBooking = $p;
                                break;
                            }
                        }

                        $slotsData[] = [
                            'jam_mulai' => $t['start'],
                            'jam_selesai' => $t['end'],
                            'label' => $t['label'],
                            'status' => $status,
                            'peminjaman' => $associatedBooking ? [
                                'id_peminjaman' => $associatedBooking->id_peminjaman,
                                'nama_kegiatan' => $associatedBooking->nama_kegiatan,
                                'keterangan' => $associatedBooking->keterangan,
                                'jam_mulai' => $associatedBooking->jam_mulai,
                                'jam_selesai' => $associatedBooking->jam_selesai,
                            ] : null,
                        ];
                    }
                } else {
                    foreach ($slots as $s) {
                        $status = $s->status;
                        $associatedBooking = $s->peminjaman;
                        
                        $sHourStart = (int)explode(':', $s->jam_mulai)[0];
                        $sHourEnd = (int)explode(':', $s->jam_selesai)[0];

                        // Check if any active booking conflicts or occupies this slot
                        foreach ($peminjamans as $p) {
                            $pHourStart = (int)explode(':', $p->jam_mulai)[0];
                            $pHourEnd = (int)explode(':', $p->jam_selesai)[0];

                            // Check overlap
                            if (max($pHourStart, $sHourStart) < min($pHourEnd, $sHourEnd)) {
                                $status = $p->status === 'siap_digunakan' ? 'dipinjam' : 'pending';
                                $associatedBooking = $p;
                                break;
                            }
                        }

                        $slotsData[] = [
                            'jam_mulai' => $s->jam_mulai,
                            'jam_selesai' => $s->jam_selesai,
                            'label' => date('H.i', strtotime($s->jam_mulai)),
                            'status' => $status,
                            'peminjaman' => $associatedBooking ? [
                                'id_peminjaman' => $associatedBooking->id_peminjaman,
                                'nama_kegiatan' => $associatedBooking->nama_kegiatan,
                                'keterangan' => $associatedBooking->keterangan,
                                'jam_mulai' => $associatedBooking->jam_mulai,
                                'jam_selesai' => $associatedBooking->jam_selesai,
                            ] : null,
                        ];
                    }
                }
            } elseif ($type === 'inventaris') {
                $barang = Barang::find($id);
                if (!$barang) {
                    return response()->json(['message' => 'Barang tidak ditemukan.'], 404);
                }

                // Fetch active bookings for this barang on the selected date
                $activeBookings = DetailPeminjamanBarang::where('barang_id', $id)
                    ->whereHas('peminjaman', function ($q) use ($tanggal) {
                        $q->whereDate('tanggal_pengajuan', $tanggal)
                            ->whereIn('status', ['menunggu_dosen', 'menunggu_admin', 'menunggu_kepala', 'menunggu_pic', 'siap_digunakan']);
                    })
                    ->with('peminjaman')
                    ->get();

                foreach ($defaultTimes as $t) {
                    $startHour = (int)explode(':', $t['start'])[0];
                    $endHour = (int)explode(':', $t['end'])[0];
                    
                    $approvedQty = 0;
                    $pendingQty = 0;
                    $associatedBooking = null;

                    foreach ($activeBookings as $detail) {
                        if ($detail->peminjaman) {
                            $pHourStart = (int)explode(':', $detail->peminjaman->jam_mulai)[0];
                            $pHourEnd = (int)explode(':', $detail->peminjaman->jam_selesai)[0];
                            
                            if (max($pHourStart, $startHour) < min($pHourEnd, $endHour)) {
                                if ($detail->peminjaman->status === 'siap_digunakan') {
                                    $approvedQty += $detail->jumlah;
                                } else {
                                    $pendingQty += $detail->jumlah;
                                }
                                $associatedBooking = $detail->peminjaman;
                            }
                        }
                    }

                    $totalBooked = $approvedQty + $pendingQty;
                    $status = 'tersedia';
                    if ($totalBooked >= $barang->stok_tersedia) {
                        $status = 'dipinjam';
                    } elseif ($totalBooked > 0) {
                        $status = 'pending';
                    } else {
                        $status = 'tersedia';
                    }

                    $slotsData[] = [
                        'jam_mulai' => $t['start'],
                        'jam_selesai' => $t['end'],
                        'label' => $t['label'],
                        'status' => $status,
                        'peminjaman' => $associatedBooking ? [
                            'id_peminjaman' => $associatedBooking->id_peminjaman,
                            'nama_kegiatan' => $associatedBooking->nama_kegiatan,
                            'keterangan' => $associatedBooking->keterangan,
                            'jam_mulai' => $associatedBooking->jam_mulai,
                            'jam_selesai' => $associatedBooking->jam_selesai,
                        ] : null,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'slots' => $slotsData,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('getSlots student error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error: ' . $e->getMessage()], 500);
        }
    }
}
