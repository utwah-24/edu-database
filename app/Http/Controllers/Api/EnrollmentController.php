<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EnrollmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/enrollments",
     *     summary="Get all enrollments",
     *     tags={"Enrollments"},
     *     @OA\Parameter(
     *         name="student_id",
     *         in="query",
     *         description="Filter by student",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="course_id",
     *         in="query",
     *         description="Filter by course",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"enrolled", "dropped", "completed", "failed"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Enrollment::with(['student.user', 'course']);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $enrollments = $query->get();
        return response()->json($enrollments);
    }

    /**
     * @OA\Post(
     *     path="/enrollments",
     *     summary="Create a new enrollment",
     *     tags={"Enrollments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EnrollmentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Enrollment created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'enrollment_date' => 'required|date',
            'status' => 'in:enrolled,dropped,completed,failed',
            'final_grade' => 'nullable|numeric|min:0|max:100',
            'letter_grade' => 'nullable|in:A+,A,A-,B+,B,B-,C+,C,C-,D,F',
            'notes' => 'nullable|string',
        ]);

        $enrollment = Enrollment::create($validated);

        return response()->json($enrollment->load(['student', 'course']), 201);
    }

    /**
     * @OA\Get(
     *     path="/enrollments/{id}",
     *     summary="Get a specific enrollment",
     *     tags={"Enrollments"},
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
        $enrollment = Enrollment::with(['student.user', 'course', 'grades'])->findOrFail($id);
        return response()->json($enrollment);
    }

    /**
     * @OA\Put(
     *     path="/enrollments/{id}",
     *     summary="Update an enrollment",
     *     tags={"Enrollments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EnrollmentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Enrollment updated successfully"
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $enrollment = Enrollment::findOrFail($id);

        $validated = $request->validate([
            'enrollment_date' => 'date',
            'status' => 'in:enrolled,dropped,completed,failed',
            'final_grade' => 'nullable|numeric|min:0|max:100',
            'letter_grade' => 'nullable|in:A+,A,A-,B+,B,B-,C+,C,C-,D,F',
            'notes' => 'nullable|string',
        ]);

        $enrollment->update($validated);

        return response()->json($enrollment->load(['student', 'course']));
    }

    /**
     * @OA\Delete(
     *     path="/enrollments/{id}",
     *     summary="Delete an enrollment",
     *     tags={"Enrollments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Enrollment deleted successfully"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/enrollments/{id}/grades",
     *     summary="Get all grades for an enrollment",
     *     tags={"Enrollments"},
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
    public function grades(string $id): JsonResponse
    {
        $enrollment = Enrollment::findOrFail($id);
        $grades = $enrollment->grades;

        return response()->json($grades);
    }
}

/**
 * @OA\Schema(
 *     schema="EnrollmentRequest",
 *     type="object",
 *     required={"student_id", "course_id", "enrollment_date"},
 *     properties={
 *         @OA\Property(property="student_id", type="integer", example=1),
 *         @OA\Property(property="course_id", type="integer", example=1),
 *         @OA\Property(property="enrollment_date", type="string", format="date", example="2024-09-01"),
 *         @OA\Property(property="status", type="string", enum={"enrolled", "dropped", "completed", "failed"}, example="enrolled"),
 *         @OA\Property(property="final_grade", type="number", format="float", example=85.5),
 *         @OA\Property(property="letter_grade", type="string", enum={"A+", "A", "A-", "B+", "B", "B-", "C+", "C", "C-", "D", "F"}, example="B+"),
 *         @OA\Property(property="notes", type="string", example="Good performance")
 *     }
 * )
 */
