<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctors extends Model
{
    protected $fillable = [
        'id_users',
        'id_roles',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function roles()
    {
        return $this->belongsTo(Roles::class, 'id_roles');
    }
}
