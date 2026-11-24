<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $academies = $request->user()->academies()->get();

    return response()->json($academies, 200);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $academy = $request->user()->academies()->create($data);

        return response()->json($academy, 201);
    }

    /**
     * Display the specified resource.
     */
public function show(Request $request, string $id)
{
    $academy = Academy::findOrFail($id);

    if ($academy->user_id !== $request->user()->id) {
        return response()->json([
            'message' => 'No tienes permiso para consultar esta academia'
        ], 403);
    }

    return response()->json($academy, 200);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $academy = Academy::findOrFail($id);

        if ($academy->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para modificar esta academia'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:255',
        ]);

        $academy->update($data);

        return response()->json($academy, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $academy = Academy::findOrFail($id);

        if ($academy->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta academia'], 403);
        }

        $academy->delete();

        return response()->json(null, 204);
    }

    public function courses(Request $request, string $id)
{
    $academy = Academy::findOrFail($id);

    if ($academy->user_id !== $request->user()->id) {
        return response()->json([
            'message' => 'No tienes permiso para consultar los cursos de esta academia'
        ], 403);
    }

    $courses = $academy->courses()->get();

    return response()->json($courses, 200);
}
}