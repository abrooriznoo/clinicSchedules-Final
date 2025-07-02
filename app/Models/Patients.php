<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patients extends Model
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

    public function doctor()
    {
        return $this->belongsTo(Doctors::class, 'id_doctors');
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedules::class, 'patients_schedules', 'id_patients', 'id_schedules')
            ->with('doctor'); // optional: eager load doctor
    }
}
