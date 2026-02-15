<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/departments",
     *     summary="Get all departments",
     *     tags={"Departments"},
     *     @OA\Parameter(
     *         name="is_active",
     *         in="query",
     *         description="Filter by active status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Department")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Department::query();

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $departments = $query->with(['teachers', 'courses'])->get();

        return response()->json($departments);
    }

    /**
     * @OA\Post(
     *     path="/departments",
     *     summary="Create a new department",
     *     tags={"Departments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DepartmentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Department created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Department")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'code' => 'required|string|max:10|unique:departments',
            'head' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $department = Department::create($validated);

        return response()->json($department, 201);
    }

    /**
     * @OA\Get(
     *     path="/departments/{id}",
     *     summary="Get a specific department",
     *     tags={"Departments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Department")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Department not found"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $department = Department::with(['teachers', 'courses'])->findOrFail($id);
        return response()->json($department);
    }

    /**
     * @OA\Put(
     *     path="/departments/{id}",
     *     summary="Update a department",
     *     tags={"Departments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DepartmentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Department updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Department")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Department not found"
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255|unique:departments,name,' . $id,
            'description' => 'nullable|string',
            'code' => 'string|max:10|unique:departments,code,' . $id,
            'head' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $department->update($validated);

        return response()->json($department);
    }

    /**
     * @OA\Delete(
     *     path="/departments/{id}",
     *     summary="Delete a department",
     *     tags={"Departments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Department deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Department not found"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/departments/{id}/teachers",
     *     summary="Get all teachers in a department",
     *     tags={"Departments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function teachers(string $id): JsonResponse
    {
        $department = Department::findOrFail($id);
        $teachers = $department->teachers()->with('user')->get();

        return response()->json($teachers);
    }

    /**
     * @OA\Get(
     *     path="/departments/{id}/courses",
     *     summary="Get all courses in a department",
     *     tags={"Departments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
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
        $department = Department::findOrFail($id);
        $courses = $department->courses()->with(['teacher', 'students'])->get();

        return response()->json($courses);
    }
}

/**
 * @OA\Schema(
 *     schema="Department",
 *     type="object",
 *     title="Department",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Computer Science"),
 *         @OA\Property(property="description", type="string", example="Department of Computer Science and Engineering"),
 *         @OA\Property(property="code", type="string", example="CS"),
 *         @OA\Property(property="head", type="string", example="Dr. John Doe"),
 *         @OA\Property(property="email", type="string", example="cs@university.edu"),
 *         @OA\Property(property="phone", type="string", example="+1234567890"),
 *         @OA\Property(property="is_active", type="boolean", example=true),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time")
 *     }
 * )
 * @OA\Schema(
 *     schema="DepartmentRequest",
 *     type="object",
 *     required={"name", "code"},
 *     properties={
 *         @OA\Property(property="name", type="string", example="Computer Science"),
 *         @OA\Property(property="description", type="string", example="Department of Computer Science"),
 *         @OA\Property(property="code", type="string", example="CS"),
 *         @OA\Property(property="head", type="string", example="Dr. John Doe"),
 *         @OA\Property(property="email", type="string", example="cs@university.edu"),
 *         @OA\Property(property="phone", type="string", example="+1234567890"),
 *         @OA\Property(property="is_active", type="boolean", example=true)
 *     }
 * )
 */
