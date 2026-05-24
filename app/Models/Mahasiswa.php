<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = [
        'user_id',
        'nim',
        'program_studi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
