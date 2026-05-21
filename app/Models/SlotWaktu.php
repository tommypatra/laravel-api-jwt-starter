<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotWaktu extends Model
{
    protected $guarded = ['id'];

    public function jadwalMengajarMulai()
    {
        return $this->belongsTo(
            JadwalMengajar::class,
            'slot_mulai_id',
            'id'
        );
    }

    public function jadwalMengajarSelesai()
    {
        return $this->belongsTo(
            JadwalMengajar::class,
            'slot_selesai_id',
            'id'
        );
    }
}
