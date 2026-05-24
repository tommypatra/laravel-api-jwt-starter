<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'original_name',
        'path',
        'disk',
        'mime_type',
        'size',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profil()
    {
        return $this->hasMany(Profil::class, 'foto_upload_id');
    }
}
