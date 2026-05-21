<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = ['id'];

    public function roleUser()
    {
        return $this->hasMany(RoleUser::class);
    }
}
