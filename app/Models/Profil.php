<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $fillable = [
        'user_id',
        'foto_upload_id',
        'hp',
        'alamat',
        'jenis_kelamin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class, 'foto_upload_id');
    }
}
