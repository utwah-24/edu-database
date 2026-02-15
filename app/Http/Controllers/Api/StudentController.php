<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/students",
     *     summary="Get all students",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="enrollment_status",
     *         in="query",
     *         description="Filter by enrollment status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"active", "inactive", "graduated", "suspended"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Student::with('user');

        if ($request->has('enrollment_status')) {
            $query->where('enrollment_status', $request->enrollment_status);
        }

        $students = $query->get();
        return response()->json($students);
    }

    /**
     * @OA\Post(
     *     path="/students",
     *     summary="Create a new student",
     *     tags={"Students"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StudentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Student created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'student_id' => 'required|string|unique:students,student_id',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'guardian_name' => 'required|string',
            'guardian_phone' => 'required|string',
            'guardian_email' => 'nullable|email',
            'admission_date' => 'required|date',
            'enrollment_status' => 'in:active,inactive,graduated,suspended',
            'blood_group' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $student = Student::create(array_merge(
            $validated,
            ['user_id' => $user->id]
        ));

        return response()->json($student->load('user'), 201);
    }

    /**
     * @OA\Get(
     *     path="/students/{id}",
     *     summary="Get a specific student",
     *     tags={"Students"},
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
        $student = Student::with(['user', 'enrollments.course'])->findOrFail($id);
        return response()->json($student);
    }

    /**
     * @OA\Put(
     *     path="/students/{id}",
     *     summary="Update a student",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StudentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student updated successfully"
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'student_id' => 'string|unique:students,student_id,' . $id,
            'date_of_birth' => 'date',
            'gender' => 'in:male,female,other',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'guardian_name' => 'string',
            'guardian_phone' => 'string',
            'guardian_email' => 'nullable|email',
            'admission_date' => 'date',
            'enrollment_status' => 'in:active,inactive,graduated,suspended',
            'blood_group' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
        ]);

        $student->update($validated);

        return response()->json($student->load('user'));
    }

    /**
     * @OA\Delete(
     *     path="/students/{id}",
     *     summary="Delete a student",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Student deleted successfully"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/students/{id}/enrollments",
     *     summary="Get all enrollments for a student",
     *     tags={"Students"},
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
        $student = Student::findOrFail($id);
        $enrollments = $student->enrollments()->with('course')->get();

        return response()->json($enrollments);
    }

    /**
     * @OA\Get(
     *     path="/students/{id}/courses",
     *     summary="Get all courses for a student",
     *     tags={"Students"},
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
        $student = Student::findOrFail($id);
        $courses = $student->courses;

        return response()->json($courses);
    }
}

/**
 * @OA\Schema(
 *     schema="StudentRequest",
 *     type="object",
 *     required={"name", "email", "student_id", "date_of_birth", "gender", "guardian_name", "guardian_phone", "admission_date"},
 *     properties={
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *         @OA\Property(property="password", type="string", example="password123"),
 *         @OA\Property(property="student_id", type="string", example="STU2024001"),
 *         @OA\Property(property="date_of_birth", type="string", format="date", example="2000-01-15"),
 *         @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
 *         @OA\Property(property="phone", type="string", example="+1234567890"),
 *         @OA\Property(property="address", type="string", example="123 Main St, City"),
 *         @OA\Property(property="guardian_name", type="string", example="Jane Doe"),
 *         @OA\Property(property="guardian_phone", type="string", example="+1234567891"),
 *         @OA\Property(property="guardian_email", type="string", example="jane.doe@example.com"),
 *         @OA\Property(property="admission_date", type="string", format="date", example="2024-09-01"),
 *         @OA\Property(property="enrollment_status", type="string", enum={"active", "inactive", "graduated", "suspended"}),
 *         @OA\Property(property="blood_group", type="string", example="A+"),
 *         @OA\Property(property="medical_conditions", type="string", example="None")
 *     }
 * )
 */
