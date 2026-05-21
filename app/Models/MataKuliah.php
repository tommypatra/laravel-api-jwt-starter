<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $guarded = ['id'];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function distribusiMengajar()
    {
        return $this->belongsTo(DistribusiMengajar::class);
    }
}
