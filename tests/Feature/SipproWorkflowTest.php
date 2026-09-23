<?php

namespace Tests\Feature;

use App\Models\Proposal;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SipproWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_halaman_beranda_dapat_diakses(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SIPPRO');
        $response->assertSee('Murung Raya');
    }

    public function test_pelacakan_proposal_publik_berfungsi(): void
    {
        $response = $this->post('/lacak', [
            'nomor_registrasi' => 'PROP-202609-0001',
        ]);
        $response->assertStatus(200);
        $response->assertSee('PROP-202609-0001');
    }

    public function test_halaman_login_tersedia(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Wakil Bupati');
        $response->assertSee('Staf Administrasi');
        $response->assertSee('Pengusul');
    }

    public function test_quick_login_wabup_berhasil_ke_dasbor(): void
    {
        $response = $this->get('/quick-login/wabup');
        $response->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->assertEquals('wabup', auth()->user()->role);

        $dashboard = $this->get('/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('Wakil Bupati Murung Raya');
    }

    public function test_quick_login_pengusul_berhasil_ke_dasbor(): void
    {
        $response = $this->get('/quick-login/pengusul');
        $response->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->assertEquals('pengusul', auth()->user()->role);

        $dashboard = $this->get('/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('Budi Santoso');
    }

    public function test_halaman_daftar_proposal_dan_detail_berfungsi(): void
    {
        $user = User::where('role', 'wabup')->first();
        $this->actingAs($user);

        $response = $this->get('/proposals');
        $response->assertStatus(200);
        $response->assertSee('PROP-202609-0001');

        $prop = Proposal::first();
        $detail = $this->get('/proposals/'.$prop->id);
        $detail->assertStatus(200);
        $detail->assertSee($prop->nomor_registrasi);
    }

    public function test_halaman_registrasi_dan_buat_proposal_dapat_diakses(): void
    {
        $register = $this->get('/register');
        $register->assertStatus(200);
        $register->assertSee('Pendaftaran Akun Pengusul');

        $user = User::where('role', 'pengusul')->first();
        $this->actingAs($user);

        $create = $this->get('/proposals/create');
        $create->assertStatus(200);
        $create->assertSee('Formulir Pengajuan Proposal Baru');
    }

    public function test_staf_admin_tidak_bisa_memberikan_keputusan_proposal(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $prop = Proposal::first();

        // Staf Admin dilarang menyetujui / menolak proposal (harus 403 Forbidden)
        $response = $this->post('/proposals/'.$prop->id.'/review', [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Catatan staf admin',
        ]);

        $response->assertStatus(403);
    }

    public function test_wakil_bupati_bisa_memberikan_keputusan_proposal(): void
    {
        $wabup = User::where('role', 'wabup')->first();
        $this->actingAs($wabup);

        $prop = Proposal::where('status', 'diajukan')->first();

        $response = $this->post('/proposals/'.$prop->id.'/review', [
            'keputusan' => 'disetujui',
            'catatan_pimpinan' => 'Disetujui penuh oleh Wakil Bupati Murung Raya untuk diterbitkan rekomendasi.',
        ]);

        $response->assertRedirect('/proposals/'.$prop->id);
        $this->assertDatabaseHas('proposals', [
            'id' => $prop->id,
            'status' => 'disetujui',
        ]);
    }
}
