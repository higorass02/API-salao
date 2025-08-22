<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use Illuminate\Http\Request;

class CollaboratorController extends Controller
{
    public function index()
    {
        $collaborators = Collaborator::with('user')->get();

        $result = $collaborators->map(function($collab) {
            return [
                'id' => $collab->id,
                'bio' => $collab->bio,
                'specialities' => $collab->specialities,
                'user_id' => $collab->user_id,
                'user_name' => $collab->user ? $collab->user->name . ' ('. $collab->user->id . ')' : null,
            ];
        });

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bio' => 'required|string|max:255',
            'specialities' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
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
            'bio' => 'sometimes|string|max:255',
            'specialities' => 'sometimes|string|max:255',
            'status' => 'sometimes|boolean',
            'user_id' => 'required|exists:users,id',
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
