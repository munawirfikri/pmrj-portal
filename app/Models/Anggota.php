<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Anggota extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'anggota';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'golongan_darah',
        'nik',
        'pekerjaan',
        'alamat_jakarta',
        'kota_bagian',
        'asal_ikk',
        'no_hp',
        'foto_ktp',
        'foto',
        'status_rumah',
        'no_anggota',
        'status',
        'is_verified',
        'role'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_verified' => 'boolean',
    ];

    public function ikk()
    {
        return $this->belongsTo(Ikk::class, 'asal_ikk', 'nama');
    }

    public function generateNoAnggota()
    {
        $ikk = Ikk::where('nama', $this->asal_ikk)->first();
        $ikkCode = $ikk ? $ikk->kode : '00';
        
        // Get all members with valid no_anggota and extract the highest number
        $members = self::whereNotNull('no_anggota')
                      ->where('no_anggota', '!=', '')
                      ->where('no_anggota', 'LIKE', 'PMRJ-%')
                      ->pluck('no_anggota');
        
        $maxNumber = 0;
        foreach ($members as $memberNumber) {
            if (preg_match('/PMRJ-\d{2}-(\d{4})/', $memberNumber, $matches)) {
                $number = intval($matches[1]);
                if ($number > $maxNumber) {
                    $maxNumber = $number;
                }
            }
        }
        
        $newNumber = $maxNumber + 1;
        
        return "PMRJ-{$ikkCode}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
