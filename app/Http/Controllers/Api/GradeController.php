<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GradeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/grades",
     *     summary="Get all grades",
     *     tags={"Grades"},
     *     @OA\Parameter(
     *         name="enrollment_id",
     *         in="query",
     *         description="Filter by enrollment",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="assignment_type",
     *         in="query",
     *         description="Filter by assignment type",
     *         required=false,
     *         @OA\Schema(type="string", enum={"homework", "quiz", "midterm", "final", "project", "participation"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Grade::with('enrollment');

        if ($request->has('enrollment_id')) {
            $query->where('enrollment_id', $request->enrollment_id);
        }

        if ($request->has('assignment_type')) {
            $query->where('assignment_type', $request->assignment_type);
        }

        $grades = $query->get();
        return response()->json($grades);
    }

    /**
     * @OA\Post(
     *     path="/grades",
     *     summary="Create a new grade",
     *     tags={"Grades"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GradeRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Grade created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'assignment_name' => 'required|string|max:255',
            'assignment_type' => 'required|in:homework,quiz,midterm,final,project,participation',
            'grade' => 'required|numeric|min:0',
            'max_grade' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0|max:100',
            'grade_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $grade = Grade::create($validated);

        return response()->json($grade->load('enrollment'), 201);
    }

    /**
     * @OA\Get(
     *     path="/grades/{id}",
     *     summary="Get a specific grade",
     *     tags={"Grades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $grade = Grade::with(['enrollment.student', 'enrollment.course'])->findOrFail($id);
        return response()->json($grade);
    }

    /**
     * @OA\Put(
     *     path="/grades/{id}",
     *     summary="Update a grade",
     *     tags={"Grades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GradeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Grade updated successfully"
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $grade = Grade::findOrFail($id);

        $validated = $request->validate([
            'assignment_name' => 'string|max:255',
            'assignment_type' => 'in:homework,quiz,midterm,final,project,participation',
            'grade' => 'numeric|min:0',
            'max_grade' => 'numeric|min:0',
            'weight' => 'numeric|min:0|max:100',
            'grade_date' => 'date',
            'remarks' => 'nullable|string',
        ]);

        $grade->update($validated);

        return response()->json($grade->load('enrollment'));
    }

    /**
     * @OA\Delete(
     *     path="/grades/{id}",
     *     summary="Delete a grade",
     *     tags={"Grades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Grade deleted successfully"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return response()->json(null, 204);
    }
}

/**
 * @OA\Schema(
 *     schema="GradeRequest",
 *     type="object",
 *     required={"enrollment_id", "assignment_name", "assignment_type", "grade", "max_grade", "weight", "grade_date"},
 *     properties={
 *         @OA\Property(property="enrollment_id", type="integer", example=1),
 *         @OA\Property(property="assignment_name", type="string", example="Midterm Exam"),
 *         @OA\Property(property="assignment_type", type="string", enum={"homework", "quiz", "midterm", "final", "project", "participation"}, example="midterm"),
 *         @OA\Property(property="grade", type="number", format="float", example=85.5),
 *         @OA\Property(property="max_grade", type="number", format="float", example=100),
 *         @OA\Property(property="weight", type="number", format="float", example=30),
 *         @OA\Property(property="grade_date", type="string", format="date", example="2024-10-15"),
 *         @OA\Property(property="remarks", type="string", example="Good performance")
 *     }
 * )
 */
