<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    protected $fillable = [
        'doctor_id',
        'appointment_date',
        'status',
        'notes',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctors::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patients::class, 'id_patients');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function scheduledPatients()
    {
        return $this->belongsToMany(Patients::class, 'id_schedules', 'id_patients');
    }
}
