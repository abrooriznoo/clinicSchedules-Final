<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientsSchedules extends Model
{
    protected $fillable = [
        'id_patients',
        'id_schedules',
        'status',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patients::class, 'id_patients');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctors::class, 'doctor_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'id_patients');
    }

    public function schedules()
    {
        return $this->belongsTo(Schedules::class, 'id_schedules');
    }
}
