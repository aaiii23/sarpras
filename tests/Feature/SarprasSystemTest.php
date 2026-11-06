<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SarprasSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function createSiswa(): User
    {
        return User::create([
            'name' => 'Budi Santoso',
            'email' => uniqid('siswa') . '@gmail.com',
            'role' => 'siswa',
            'nis' => (string) random_int(10000000, 99999999),
            'password' => 'password123',
        ]);
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SARPRAS SEKOLAH');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-cache', $response->headers->get('Cache-Control'));
        $response->assertHeader('Pragma', 'no-cache');
    }

    public function test_admin_can_login_and_access_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $dashboardResponse = $this->actingAs(User::where('email', 'admin@gmail.com')->first())->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard Administrator');
        $dashboardResponse->assertSee('Total Semua Aduan');
    }

    public function test_database_seed_contains_admin_and_student_demo_accounts(): void
    {
        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'guru@gmail.com',
            'role' => 'guru',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'siswa@gmail.com',
            'role' => 'siswa',
            'nis' => '12345678',
        ]);
        $this->get('/login')
            ->assertSee('Admin Demo')
            ->assertSee('Guru Demo')
            ->assertSee('Siswa Demo')
            ->assertSee('requestSubmit()');
    }

    public function test_demo_student_can_login(): void
    {
        $response = $this->post('/login', [
            'email' => 'siswa@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs(User::where('email', 'siswa@gmail.com')->first());
    }

    public function test_demo_guru_can_login_and_access_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'guru@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $guru = User::where('email', 'guru@gmail.com')->first();
        $this->assertAuthenticatedAs($guru);

        $dashboardResponse = $this->actingAs($guru)->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Bapak Guru Pembina');
        $dashboardResponse->assertSee('Buat Aduan');
    }

    public function test_siswa_can_register_with_nis_and_gmail_and_access_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'nis' => '87654321',
            'email' => 'budi.santoso@gmail.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Budi Santoso',
            'nis' => '87654321',
            'email' => 'budi.santoso@gmail.com',
            'role' => 'siswa',
        ]);

        $dashboardResponse = $this->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard Siswa');
        $dashboardResponse->assertSee('Buat Laporan Aduan');
    }

    public function test_registration_rejects_non_gmail_addresses(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Budi Santoso',
            'nis' => '12345678',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'budi@example.com']);
    }

    public function test_siswa_cannot_access_admin_routes(): void
    {
        $siswa = $this->createSiswa();
        $response = $this->actingAs($siswa)->get('/admin/complaints');
        $response->assertStatus(403);
    }

    public function test_siswa_can_submit_new_complaint_without_inputting_urgency(): void
    {
        Storage::fake('public');

        $siswa = $this->createSiswa();
        $category = Category::first();
        $location = Location::first();

        $response = $this->actingAs($siswa)->post('/complaints', [
            'title' => 'Lampu Ruang Lab Padam',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'description' => 'Dua unit lampu neon di bagian depan ruang lab padam saat pelajaran dimulai.',
            'photo_evidence' => UploadedFile::fake()->image('lampu_rusak.jpg'),
        ]);

        $this->assertDatabaseHas('complaints', [
            'title' => 'Lampu Ruang Lab Padam',
            'user_id' => $siswa->id,
            'status' => 'pending',
            'urgency' => null,
        ]);

        $complaint = Complaint::where('title', 'Lampu Ruang Lab Padam')->first();
        $response->assertRedirect('/complaints/' . $complaint->id);
    }

    public function test_siswa_can_submit_complaint_with_custom_room_name(): void
    {
        Storage::fake('public');

        $siswa = $this->createSiswa();
        $category = Category::first();
        $location = Location::create(['name' => 'Ruang Musik Lt 3']);

        $response = $this->actingAs($siswa)->post('/complaints', [
            'title' => 'Kipas Angin Rusak di Ruang Musik Lt 3',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'description' => 'Kipas angin mengeluarkan bunyi mendengung keras dan baling-baling tidak berputar.',
            'photo_evidence' => UploadedFile::fake()->image('kipas_angin.jpg'),
        ]);

        $this->assertDatabaseHas('complaints', [
            'title' => 'Kipas Angin Rusak di Ruang Musik Lt 3',
            'user_id' => $siswa->id,
            'location_id' => $location->id,
            'status' => 'pending',
            'urgency' => null,
        ]);
    }

    public function test_complaint_form_uses_a_location_dropdown_and_disables_page_cache(): void
    {
        $siswa = $this->createSiswa();
        $location = Location::first();

        $response = $this->actingAs($siswa)->get('/complaints/create');

        $response->assertOk();
        $response->assertSee('name="location_id"', false);
        $response->assertSee('value="' . $location->id . '"', false);
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
    }

    public function test_admin_can_update_status_and_validate_damage_condition_with_optional_notes(): void
    {
        $admin = User::where('role', 'admin')->first();
        $siswa = $this->createSiswa();
        $complaint = Complaint::create([
            'ticket_number' => 'TKT-20261020-0001',
            'user_id' => $siswa->id,
            'category_id' => Category::first()->id,
            'location_id' => Location::first()->id,
            'title' => 'Lampu Ruang Kelas Padam',
            'description' => 'Lampu tidak menyala.',
            'status' => 'pending',
        ]);

        // Testing update with empty notes (optional)
        $response = $this->actingAs($admin)->put("/admin/complaints/{$complaint->id}/status", [
            'status' => 'in_progress',
            'urgency' => 'tinggi',
            'notes' => '',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'in_progress',
            'urgency' => 'tinggi',
        ]);
        $this->assertDatabaseHas('complaint_logs', [
            'complaint_id' => $complaint->id,
            'status_to' => 'in_progress',
        ]);
    }

    public function test_admin_can_crud_master_categories(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Create
        $response = $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'Fasilitas Perpustakaan',
            'icon' => 'bi-book-fill',
            'description' => 'Rak buku, meja baca, komputer pencarian.',
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['name' => 'Fasilitas Perpustakaan']);

        $category = Category::where('name', 'Fasilitas Perpustakaan')->first();

        // Update
        $this->actingAs($admin)->put("/admin/categories/{$category->id}", [
            'name' => 'Fasilitas Perpustakaan Digital',
            'icon' => 'bi-book',
            'description' => 'Updated desc',
        ]);
        $this->assertDatabaseHas('categories', ['name' => 'Fasilitas Perpustakaan Digital']);

        // Delete
        $this->actingAs($admin)->delete("/admin/categories/{$category->id}");
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_reports_page_and_print_view(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/reports');
        $response->assertStatus(200);
        $response->assertSee('Laporan Rekapitulasi Sarpras');

        $printResponse = $this->actingAs($admin)->get('/admin/reports/print');
        $printResponse->assertStatus(200);
        $printResponse->assertSee('LAPORAN REKAPITULASI PENGADUAN SARPRAS');
        $printResponse->assertSee('Kepala Sekolah');
    }

    public function test_public_ticket_tracker_is_removed(): void
    {
        $this->get('/track')->assertNotFound();
    }
}
