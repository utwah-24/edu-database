<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * @OA\Get(
     *     path="/teachers",
     *     summary="Get all teachers",
     *     tags={"Teachers"},
     *     @OA\Parameter(
     *         name="department_id",
     *         in="query",
     *         description="Filter by department",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="is_active",
     *         in="query",
     *         description="Filter by active status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Teacher::with(['user', 'department']);

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $teachers = $query->get();
        return response()->json($teachers);
    }

    /**
     * @OA\Post(
     *     path="/teachers",
     *     summary="Create a new teacher",
     *     tags={"Teachers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TeacherRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Teacher created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'department_id' => 'required|exists:departments,id',
            'employee_id' => 'required|string|unique:teachers,employee_id',
            'phone' => 'nullable|string',
            'specialization' => 'nullable|string',
            'hire_date' => 'required|date',
            'employment_type' => 'in:full-time,part-time,contract',
            'bio' => 'nullable|string',
            'office_location' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $teacher = Teacher::create(array_merge(
            $validated,
            ['user_id' => $user->id]
        ));

        return response()->json($teacher->load(['user', 'department']), 201);
    }

    /**
     * @OA\Get(
     *     path="/teachers/{id}",
     *     summary="Get a specific teacher",
     *     tags={"Teachers"},
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
        $teacher = Teacher::with(['user', 'department', 'courses'])->findOrFail($id);
        return response()->json($teacher);
    }

    /**
     * @OA\Put(
     *     path="/teachers/{id}",
     *     summary="Update a teacher",
     *     tags={"Teachers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TeacherRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Teacher updated successfully"
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $teacher = Teacher::findOrFail($id);

        $validated = $request->validate([
            'department_id' => 'exists:departments,id',
            'employee_id' => 'string|unique:teachers,employee_id,' . $id,
            'phone' => 'nullable|string',
            'specialization' => 'nullable|string',
            'hire_date' => 'date',
            'employment_type' => 'in:full-time,part-time,contract',
            'bio' => 'nullable|string',
            'office_location' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $teacher->update($validated);

        return response()->json($teacher->load(['user', 'department']));
    }

    /**
     * @OA\Delete(
     *     path="/teachers/{id}",
     *     summary="Delete a teacher",
     *     tags={"Teachers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Teacher deleted successfully"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/teachers/{id}/courses",
     *     summary="Get all courses taught by a teacher",
     *     tags={"Teachers"},
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
    public function courses(string $id): JsonResponse
    {
        $teacher = Teacher::findOrFail($id);
        $courses = $teacher->courses()->with('department')->get();

        return response()->json($courses);
    }
}

/**
 * @OA\Schema(
 *     schema="TeacherRequest",
 *     type="object",
 *     required={"name", "email", "department_id", "employee_id", "hire_date"},
 *     properties={
 *         @OA\Property(property="name", type="string", example="Dr. Jane Smith"),
 *         @OA\Property(property="email", type="string", example="jane.smith@university.edu"),
 *         @OA\Property(property="password", type="string", example="password123"),
 *         @OA\Property(property="department_id", type="integer", example=1),
 *         @OA\Property(property="employee_id", type="string", example="EMP2024001"),
 *         @OA\Property(property="phone", type="string", example="+1234567890"),
 *         @OA\Property(property="specialization", type="string", example="Machine Learning"),
 *         @OA\Property(property="hire_date", type="string", format="date", example="2020-09-01"),
 *         @OA\Property(property="employment_type", type="string", enum={"full-time", "part-time", "contract"}),
 *         @OA\Property(property="bio", type="string", example="Expert in Machine Learning"),
 *         @OA\Property(property="office_location", type="string", example="Room 301, Building A"),
 *         @OA\Property(property="is_active", type="boolean", example=true)
 *     }
 * )
 */
