<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Ikk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminAnggotaRoleTest extends TestCase
{
    use DatabaseTransactions;

    private $ikk;
    private $admin;
    private $member;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test Ikk if none exists
        $this->ikk = Ikk::firstOrCreate(['nama' => 'Kota Pekanbaru'], ['kode' => '01']);

        // Create test Admin
        $this->admin = Anggota::create([
            'nama_lengkap' => 'Test Admin User',
            'email' => 'testadmin@pmrj.or.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'active',
            'is_verified' => true,
            'asal_ikk' => $this->ikk->nama,
            'nik' => '1234567890123456',
            'no_hp' => '081234567890'
        ]);

        // Create test Member
        $this->member = Anggota::create([
            'nama_lengkap' => 'Test Member User',
            'email' => 'testmember@pmrj.or.id',
            'password' => bcrypt('password123'),
            'role' => 'member',
            'status' => 'active',
            'is_verified' => false,
            'asal_ikk' => $this->ikk->nama,
            'nik' => '1234567890123457',
            'no_hp' => '081234567891'
        ]);
    }

    /** @test */
    public function edit_page_contains_role_dropdown()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.anggota.edit', $this->member->id));

        $response->assertStatus(200);
        $response->assertSee('Hak Akses (Role)');
        $response->assertSee('value="member"', false);
        $response->assertSee('value="admin"', false);
    }

    /** @test */
    public function show_page_displays_current_role()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.anggota.show', $this->member->id));

        $response->assertStatus(200);
        $response->assertSee('Hak Akses (Role)');
        $response->assertSee('ANGGOTA BIASA');

        $adminResponse = $this->actingAs($this->admin)
            ->get(route('admin.anggota.show', $this->admin->id));

        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('ADMINISTRATOR');
    }

    /** @test */
    public function admin_can_update_member_role_to_admin()
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.anggota.update', $this->member->id), [
                'nama_lengkap' => $this->member->nama_lengkap,
                'email' => $this->member->email,
                'nik' => $this->member->nik,
                'asal_ikk' => $this->member->asal_ikk,
                'no_hp' => $this->member->no_hp,
                'role' => 'admin'
            ]);

        $response->assertRedirect(route('admin.anggota.show', $this->member->id));
        $response->assertSessionHas('success', 'Profil anggota berhasil diperbarui.');

        $this->assertEquals('admin', $this->member->fresh()->role);
    }

    /** @test */
    public function admin_cannot_demote_themselves()
    {
        $response = $this->actingAs($this->admin)
            ->from(route('admin.anggota.edit', $this->admin->id))
            ->put(route('admin.anggota.update', $this->admin->id), [
                'nama_lengkap' => $this->admin->nama_lengkap,
                'email' => $this->admin->email,
                'nik' => $this->admin->nik,
                'asal_ikk' => $this->admin->asal_ikk,
                'no_hp' => $this->admin->no_hp,
                'role' => 'member' // Attempt to demote
            ]);

        $response->assertRedirect(route('admin.anggota.edit', $this->admin->id));
        $response->assertSessionHasErrors(['role']);
        
        $this->assertEquals('admin', $this->admin->fresh()->role);
    }
}
