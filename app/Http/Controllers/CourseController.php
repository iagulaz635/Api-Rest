<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courses = Course::whereHas('academy', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->get();

        return response()->json($courses, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'academy_id' => 'required|exists:academies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'capacity' => 'required|integer|min:1',
        ]);

        $academy = Academy::findOrFail($data['academy_id']);

        if ($academy->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No puedes crear cursos en una academia que no es tuya'], 403);
        }

        $course = $academy->courses()->create($data);

        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
{
    $course = Course::findOrFail($id);

    if ($course->academy->user_id !== $request->user()->id) {
        return response()->json([
            'message' => 'No tienes permiso para consultar este curso'
        ], 403);
    }

    return response()->json($course, 200);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $course = Course::findOrFail($id);

    if ($course->academy->user_id !== $request->user()->id) {
        return response()->json([
            'message' => 'No tienes permiso para modificar este curso'
        ], 403);
    }

    $data = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'sometimes|required|date',
        'end_date' => 'sometimes|required|date',
        'capacity' => 'sometimes|required|integer|min:1',
    ]);

    $startDate = $data['start_date'] ?? $course->start_date;
    $endDate = $data['end_date'] ?? $course->end_date;

    if ($startDate && $endDate &&
        strtotime((string) $endDate) < strtotime((string) $startDate)) {
        return response()->json([
            'message' => 'La fecha de fin no puede ser anterior a la fecha de inicio'
        ], 422);
    }

    $course->update($data);

    return response()->json($course, 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $course = Course::findOrFail($id);

        if ($course->academy->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para eliminar este curso'], 403);
        }

        $course->delete();

        return response()->json(null, 204);
    }
}