<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $guarded = ['id'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function matakuliah()
    {
        return $this->hasMany(MataKuliah::class);
    }

    public function distribusiMengajar()
    {
        return $this->hasMany(DistribusiMengajar::class);
    }
}
