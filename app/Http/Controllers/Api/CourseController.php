<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/courses",
     *     summary="Get all courses",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="department_id",
     *         in="query",
     *         description="Filter by department",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="semester",
     *         in="query",
     *         description="Filter by semester",
     *         required=false,
     *         @OA\Schema(type="string", enum={"Fall", "Spring", "Summer"})
     *     ),
     *     @OA\Parameter(
     *         name="academic_year",
     *         in="query",
     *         description="Filter by academic year",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Course::with(['department', 'teacher']);

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $courses = $query->get();
        return response()->json($courses);
    }

    /**
     * @OA\Post(
     *     path="/courses",
     *     summary="Create a new course",
     *     tags={"Courses"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CourseRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Course created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:courses',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
            'department_id' => 'required|exists:departments,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'semester' => 'required|in:Fall,Spring,Summer',
            'academic_year' => 'required|string',
            'max_students' => 'integer|min:1',
            'level' => 'in:undergraduate,graduate,doctoral',
            'room' => 'nullable|string',
            'schedule' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $course = Course::create($validated);

        return response()->json($course->load(['department', 'teacher']), 201);
    }

    /**
     * @OA\Get(
     *     path="/courses/{id}",
     *     summary="Get a specific course",
     *     tags={"Courses"},
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
        $course = Course::with(['department', 'teacher', 'enrollments.student'])->findOrFail($id);
        return response()->json($course);
    }

    /**
     * @OA\Put(
     *     path="/courses/{id}",
     *     summary="Update a course",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CourseRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course updated successfully"
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'code' => 'string|unique:courses,code,' . $id,
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'credits' => 'integer|min:1|max:10',
            'department_id' => 'exists:departments,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'semester' => 'in:Fall,Spring,Summer',
            'academic_year' => 'string',
            'max_students' => 'integer|min:1',
            'level' => 'in:undergraduate,graduate,doctoral',
            'room' => 'nullable|string',
            'schedule' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $course->update($validated);

        return response()->json($course->load(['department', 'teacher']));
    }

    /**
     * @OA\Delete(
     *     path="/courses/{id}",
     *     summary="Delete a course",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Course deleted successfully"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/courses/{id}/students",
     *     summary="Get all students enrolled in a course",
     *     tags={"Courses"},
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
    public function students(string $id): JsonResponse
    {
        $course = Course::findOrFail($id);
        $students = $course->students;

        return response()->json($students);
    }

    /**
     * @OA\Get(
     *     path="/courses/{id}/enrollments",
     *     summary="Get all enrollments for a course",
     *     tags={"Courses"},
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
    public function enrollments(string $id): JsonResponse
    {
        $course = Course::findOrFail($id);
        $enrollments = $course->enrollments()->with('student')->get();

        return response()->json($enrollments);
    }
}

/**
 * @OA\Schema(
 *     schema="CourseRequest",
 *     type="object",
 *     required={"code", "name", "credits", "department_id", "semester", "academic_year"},
 *     properties={
 *         @OA\Property(property="code", type="string", example="CS101"),
 *         @OA\Property(property="name", type="string", example="Introduction to Computer Science"),
 *         @OA\Property(property="description", type="string", example="Basic concepts of computer science"),
 *         @OA\Property(property="credits", type="integer", example=3),
 *         @OA\Property(property="department_id", type="integer", example=1),
 *         @OA\Property(property="teacher_id", type="integer", example=1),
 *         @OA\Property(property="semester", type="string", enum={"Fall", "Spring", "Summer"}, example="Fall"),
 *         @OA\Property(property="academic_year", type="string", example="2025-2026"),
 *         @OA\Property(property="max_students", type="integer", example=30),
 *         @OA\Property(property="level", type="string", enum={"undergraduate", "graduate", "doctoral"}),
 *         @OA\Property(property="room", type="string", example="Room 101"),
 *         @OA\Property(property="schedule", type="object", example={"monday": "10:00-12:00", "wednesday": "10:00-12:00"}),
 *         @OA\Property(property="is_active", type="boolean", example=true)
 *     }
 * )
 */
