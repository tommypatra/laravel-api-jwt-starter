<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribusiMengajar extends Model
{
    protected $guarded = ['id'];

    public function periodePenjadwalan()
    {
        return $this->hasMany(PeriodePenjadwalan::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
