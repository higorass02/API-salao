<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return response()->json(Appointment::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'collaborator_id' => 'required|exists:collaborators,id',
            'service_id' => 'required|exists:services,id',
            'dt_appointment' => 'required|date_format:Y-m-d H:i:s',
            'status' => 'required|string|in:pending,confirmed,canceled,completed',
        ]);

        $appointment = Appointment::create($validated);

        return response()->json($appointment, 201);
    }

    public function show(Appointment $appointment)
    {
        return response()->json($appointment);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'dt_appointment' => 'sometimes|date_format:Y-m-d H:i:s',
            'status' => 'sometimes|string|in:pending,confirmed,canceled,completed',
        ]);

        $appointment->update($validated);

        return response()->json($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(null, 204);
    }
}
