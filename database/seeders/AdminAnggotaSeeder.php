<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Anggota;
use App\Models\Ikk;
use Illuminate\Support\Facades\Hash;

class AdminAnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure at least one IKK exists
        $ikk = Ikk::first();
        if (!$ikk) {
            $ikk = Ikk::create([
                'kode' => '00',
                'nama' => 'PMRJ PUSAT'
            ]);
        }

        // Create the admin member
        $admin = Anggota::updateOrCreate(
            ['email' => 'admin@pmrj.or.id'],
            [
                'nama_lengkap' => 'Pengurus Pusat PMRJ',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
                'is_verified' => true,
                'asal_ikk' => $ikk->nama,
                'nik' => '9999000011112222',
                'jenis_kelamin' => 'Laki-laki',
                'kota_bagian' => 'Jakarta Pusat',
                'alamat_jakarta' => 'Kantor Pengurus PMRJ, Jakarta'
            ]
        );

        // Generate member ID if empty
        if (empty($admin->no_anggota)) {
            $admin->no_anggota = $admin->generateNoAnggota();
            $admin->save();
        }
    }
}
