<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalMengajar extends Model
{
    protected $guarded = ['id'];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function slotWaktuMulai()
    {
        return $this->belongsTo(
            SlotWaktu::class,
            'id',
            'slot_mulai_id'
        );
    }

    public function slotWaktuSelesai()
    {
        return $this->belongsTo(
            SlotWaktu::class,
            'id',
            'slot_selesai_id'
        );
    }
}
