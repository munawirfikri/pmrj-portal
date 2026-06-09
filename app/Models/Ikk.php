<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ikk extends Model
{
    use HasFactory;

    protected $table = 'ikk';

    protected $fillable = [
        'kode',
        'nama'
    ];
}
