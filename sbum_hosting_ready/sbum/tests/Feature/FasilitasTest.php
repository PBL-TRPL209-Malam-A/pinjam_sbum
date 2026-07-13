<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\FasilitasRuangan;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FasilitasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed standard roles
        $rMahasiswa = Role::create(['id_role' => 1, 'nama_role' => 'Mahasiswa']);
        $rDosen = Role::create(['id_role' => 2, 'nama_role' => 'Dosen']);
        $rAdmin = Role::create(['id_role' => 3, 'nama_role' => 'Admin SBUM']);

        // Seed admin user
        $admin = User::create([
            'nama_lengkap' => 'Admin SBUM',
            'nim' => 'A001',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $admin->roles()->attach($rAdmin->id_role);

        // Seed a sample room
        Ruangan::create([
            'id_ruangan' => 1,
            'nama_ruangan' => 'Ruang Seminar 1',
            'nama_gedung' => 'Gedung Utama',
            'kode_ruangan' => 'R101',
            'kapasitas' => 100,
            'lantai' => '1',
            'status_ruangan' => 'tersedia',
        ]);
    }

    public function test_admin_can_update_supporting_facilities(): void
    {
        // Find the admin user
        $admin = User::where('email', 'admin@gmail.com')->first();
        $this->assertNotNull($admin);

        // Find the first room
        $ruangan = Ruangan::first();
        $this->assertNotNull($ruangan);

        $id = $ruangan->id_ruangan;

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
            'pic_id' => $admin->id_user,
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
            'pic_id' => $admin->id_user,
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
            'pic_id' => $admin->id_user,
        ];

        $responseUpdate = $this->actingAs($admin)
            ->put(route('admin.fasilitas.update', $ruangan->id_ruangan), $payloadUpdate);

        $responseUpdate->assertSessionHasErrors(['kode_ruangan']);
    }
}
