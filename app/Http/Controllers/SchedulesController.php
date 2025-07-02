<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\Patients;
use App\Models\Schedules;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctors::with('users')->whereHas('users', function ($query) {
            $query->where('role_id', 2); // Assuming role_id 2 is for doctors
        })->get();
        $patients = Patients::with('users')->whereHas('users', function ($query) {
            $query->where('role_id', 3); // Assuming role_id 3 is for patients
        })->get();
        $schedules = Schedules::with(['doctor.users'])->get();

        return view('admin.schedule', compact('schedules', 'doctors', 'patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Schedules::create([
            'doctor_id' => $request->id_doctors,
            'appointment_date' => $request->appointment_date,
            'status' => 1,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Schedule created successfully.');
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
        $schedule = Schedules::findOrFail($id);
        $schedule->update([
            'doctor_id' => $request->id_doctors,
            'appointment_date' => $request->appointment_date,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = Schedules::findOrFail($id);
        $schedule->delete();

        return redirect()->back()->with('success', 'Schedule deleted successfully.');
    }
}
