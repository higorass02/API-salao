<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use Illuminate\Http\Request;

class CollaboratorController extends Controller
{
    public function index()
    {
        return response()->json(Collaborator::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'active' => 'boolean',
        ]);

        $collaborator = Collaborator::create($validated);

        return response()->json($collaborator, 201);
    }

    public function show(Collaborator $collaborator)
    {
        return response()->json($collaborator);
    }

    public function update(Request $request, Collaborator $collaborator)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'role' => 'sometimes|string|max:255',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i',
            'active' => 'boolean',
        ]);

        $collaborator->update($validated);

        return response()->json($collaborator);
    }

    public function destroy(Collaborator $collaborator)
    {
        $collaborator->delete();
        return response()->json(null, 204);
    }
}
