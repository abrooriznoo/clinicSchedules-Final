<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\Patients;
use App\Models\PatientsSchedules;
use App\Models\Schedules;
use Illuminate\Http\Request;

class PatientsSchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ensure the 'users' relationship exists in Doctors and Schedules models,
        // and 'doctor' and 'patient' relationships exist in Patients model.
        $doctors = Doctors::with('users')->whereHas('users', function ($query) {
            $query->where('role_id', 2); // Assuming role_id 2 is for doctors
        })->get();

        $patients = Patients::with('users')->whereHas('users', function ($query) {
            $query->where('role_id', 3); // Assuming role_id 3 is for patients
        })->get();

        $schedules = Schedules::with('doctor.users')->whereHas('doctor', function ($query) {
            $query->where('status', 1);
        })->get();
        // Make sure the Schedules model has a 'doctor' relationship defined like:
        // public function doctor() { return $this->belongsTo(Doctors::class, 'doctor_id'); }

        $patientSchedules = PatientsSchedules::with([
            'doctor.users',
            'schedules.doctor.users',
            'users'
        ])->get(); // Assuming status 1 means 'active' or 'scheduled'

        return view('admin.patient_schedules', compact('schedules', 'doctors', 'patientSchedules', 'patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function confirm(Request $request)
    {
        $patientSchedule = PatientsSchedules::findOrFail($request->id);
        $patientSchedule->update([
            'status' => $request->status,
        ]);
        return redirect()->back()->with('success', 'Patient schedule status updated successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        PatientsSchedules::create([
            'id_patients' => $request->id_patients,
            // Cari id_schedules berdasarkan appointment_date yang diinput
            'id_schedules' => Schedules::where('appointment_date', $request->appointment_date)->value('id'),
            'status' => 1, // Assuming status 1 means 'active' or 'scheduled'
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Patient schedule created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patientSchedule = PatientsSchedules::findOrFail($id);
        $patientSchedule->update([
            'id_patients' => $request->id_patients,
            // Update id_schedules based on the appointment_date input
            'id_schedules' => Schedules::where('appointment_date', $request->appointment_date)->value('id'),
            'status' => $request->status, // Assuming status is being updated
            'notes' => $request->notes, // Assuming notes can be updated
        ]);

        return redirect()->back()->with('success', 'Patient schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patientSchedule = PatientsSchedules::findOrFail($id);
        $patientSchedule->delete();

        return redirect()->back()->with('success', 'Patient schedule deleted successfully.');
    }
}
