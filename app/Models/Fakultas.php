<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $fillable = [
        'fakultas_siakad_id',
        'nama_fakultas',
        'fakultas_singkatan',
        'is_aktif',
        'updated_at_siakad',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'updated_at_siakad' => 'datetime',
    ];

    public function programStudi()
    {
        return $this->hasMany(ProgramStudi::class);
    }
}
