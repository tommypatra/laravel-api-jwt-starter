<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hari extends Model
{
    protected $guarded = ['id'];

    public function jadwalMengajar()
    {
        return $this->hasMany(JadwalMengajar::class);
    }
}
