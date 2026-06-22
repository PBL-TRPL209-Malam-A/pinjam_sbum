<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\FasilitasRuangan;

class FasilitasTest extends TestCase
{
    public function test_admin_can_update_supporting_facilities(): void
    {
        // Find the admin user
        $admin = User::where('email', 'admin@gmail.com')->first();
        $this->assertNotNull($admin);

        // Find the first room
        $ruangan = Ruangan::first();
        $this->assertNotNull($ruangan);

        $id = $ruangan->id_ruangan;

        // Save original facilities to restore later
        $originalFacilities = FasilitasRuangan::where('id_ruangan', $id)->get()->toArray();

        // Clear existing facilities for this room first to set up a clean environment for testing the update logic
        FasilitasRuangan::where('id_ruangan', $id)->delete();

        // Create initial facilities for the room
        $initialFacility1 = FasilitasRuangan::create([
            'id_ruangan' => $id,
            'nama_fasilitas' => 'AC Lama',
            'jumlah' => 1,
            'keterangan' => 'Rusak',
        ]);
        $initialFacility2 = FasilitasRuangan::create([
            'id_ruangan' => $id,
            'nama_fasilitas' => 'Meja Lama',
            'jumlah' => 5,
            'keterangan' => 'Kayu',
        ]);

        $customKodeRuangan = 'RNG_TEST_' . rand(1000, 9999);

        // Prepare request data
        $payload = [
            'nama_ruangan' => $ruangan->nama_ruangan,
            'nama_gedung' => $ruangan->nama_gedung,
            'kode_ruangan' => $customKodeRuangan,
            'kapasitas' => 50,
            'lantai' => '3',
            'status_ruangan' => 'tersedia',
            'deskripsi_ruangan' => 'Deskripsi Baru',
            'fasilitas_items' => [
                [
                    'id_fasilitas' => $initialFacility1->id_fasilitas,
                    'nama_fasilitas' => 'AC Baru',
                    'jumlah' => 3,
                    'keterangan' => 'AC Ruangan Dingin',
                ],
                [
                    'nama_fasilitas' => 'Proyektor Baru',
                    'jumlah' => 2,
                    'keterangan' => 'Ultra HD',
                ]
            ],
        ];

        // Send PUT request as admin
        $response = $this->actingAs($admin)
            ->put(route('admin.fasilitas.update', $id), $payload);

        // Assert redirect back with success message
        $response->assertStatus(302);

        // Assert room info was updated (especially custom code)
        $this->assertDatabaseHas('ruangan', [
            'id_ruangan' => $id,
            'kode_ruangan' => $customKodeRuangan,
            'kapasitas' => 50,
            'lantai' => '3',
            'deskripsi_ruangan' => 'Deskripsi Baru',
        ]);
        
        // Assert updated facility persists and kept its ID
        $this->assertDatabaseHas('fasilitas_ruangan', [
            'id_fasilitas' => $initialFacility1->id_fasilitas,
            'id_ruangan' => $id,
            'nama_fasilitas' => 'AC Baru',
            'jumlah' => 3,
            'keterangan' => 'AC Ruangan Dingin',
        ]);
        
        // Assert new facility is created
        $this->assertDatabaseHas('fasilitas_ruangan', [
            'id_ruangan' => $id,
            'nama_fasilitas' => 'Proyektor Baru',
            'jumlah' => 2,
            'keterangan' => 'Ultra HD',
        ]);

        // Assert deleted/omitted facility is removed
        $this->assertDatabaseMissing('fasilitas_ruangan', [
            'id_fasilitas' => $initialFacility2->id_fasilitas,
        ]);

        // Restore original facilities and original room properties
        FasilitasRuangan::where('id_ruangan', $id)->delete();
        foreach ($originalFacilities as $orig) {
            unset($orig['id_fasilitas']); // remove PK
            FasilitasRuangan::create($orig);
        }

        $ruangan->update([
            'kode_ruangan' => $ruangan->getOriginal('kode_ruangan'),
            'kapasitas' => $ruangan->getOriginal('kapasitas'),
            'lantai' => $ruangan->getOriginal('lantai'),
            'deskripsi_ruangan' => $ruangan->getOriginal('deskripsi_ruangan'),
        ]);
    }

    public function test_admin_cannot_create_or_update_room_without_code(): void
    {
        // Find the admin user
        $admin = User::where('email', 'admin@gmail.com')->first();
        $this->assertNotNull($admin);

        // Try to store a room without a code
        $payloadStore = [
            'nama_ruangan' => 'Ruangan Tanpa Kode',
            'status_ruangan' => 'tersedia',
        ];

        $responseStore = $this->actingAs($admin)
            ->post(route('admin.fasilitas.store'), $payloadStore);

        $responseStore->assertSessionHasErrors(['kode_ruangan']);

        // Find the first room
        $ruangan = Ruangan::first();
        $this->assertNotNull($ruangan);

        // Try to update the room with an empty code
        $payloadUpdate = [
            'nama_ruangan' => $ruangan->nama_ruangan,
            'status_ruangan' => $ruangan->status_ruangan,
            'kode_ruangan' => '',
        ];

        $responseUpdate = $this->actingAs($admin)
            ->put(route('admin.fasilitas.update', $ruangan->id_ruangan), $payloadUpdate);

        $responseUpdate->assertSessionHasErrors(['kode_ruangan']);
    }
}
