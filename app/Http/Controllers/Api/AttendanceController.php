<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

class AttendanceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/attendances",
     *     tags={"Attendances"},
     *     summary="List attendances for an event",
     *     description="Query: `event_id` (event UUID).",
     *     @OA\Parameter(name="event_id", in="query", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'event_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $eventId = (string) $request->query('event_id');
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found',
            ], 404);
        }

        $items = Attendance::query()
            ->where('event_id', $eventId)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(fn (Attendance $a) => $this->formatAttendanceRow($a))->values(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/attendances",
     *     tags={"Attendances"},
     *     summary="Register or update attendance for an event",
     *     description="Upserts by `event_id` + `email`: creates a new row or updates the existing registration for that pair.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"event_id","name","email"},
     *             @OA\Property(property="event_id", type="string", format="uuid"),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", nullable=true),
     *             @OA\Property(property="organization", type="string", nullable=true),
     *             @OA\Property(property="registration_type", type="string", nullable=true, example="Regular")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Attendance registered successfully"),
     *     @OA\Response(response=200, description="Attendance already existed and was updated"),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'registration_type' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $event = Event::find($request->event_id);
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found',
            ], 404);
        }

        $attendance = Attendance::firstOrNew([
            'event_id' => $request->event_id,
            'email' => $request->email,
        ]);

        $attendance->name = $request->name;
        $attendance->phone = $request->phone;
        $attendance->organization = $request->organization;
        $attendance->registration_type = $request->registration_type;
        $attendance->save();

        $statusCode = $attendance->wasRecentlyCreated ? 201 : 200;

        return response()->json([
            'success' => true,
            'message' => $attendance->wasRecentlyCreated
                ? 'Attendance registered successfully'
                : 'Attendance updated successfully',
            'data' => $this->formatAttendanceRow($attendance->fresh()),
        ], $statusCode);
    }

    public function show(string $id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatAttendanceRow($attendance),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('attendances')->where('event_id', $attendance->event_id)->ignore($attendance->id),
            ],
            'phone' => 'sometimes|nullable|string|max:255',
            'organization' => 'sometimes|nullable|string|max:255',
            'registration_type' => 'sometimes|nullable|string|max:255',
            'checked_in' => 'sometimes|boolean',
            'checked_in_at' => 'sometimes|nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $fieldKeys = ['name', 'email', 'phone', 'organization', 'registration_type', 'checked_in', 'checked_in_at'];
        $hasAny = false;
        foreach ($fieldKeys as $key) {
            if ($request->exists($key)) {
                $hasAny = true;
                break;
            }
        }

        if (! $hasAny) {
            return response()->json([
                'success' => false,
                'message' => 'Provide at least one field to update',
            ], 422);
        }

        foreach (['name', 'email', 'phone', 'organization', 'registration_type'] as $key) {
            if ($request->exists($key)) {
                $attendance->{$key} = $request->input($key);
            }
        }

        if ($request->exists('checked_in')) {
            $attendance->checked_in = $request->boolean('checked_in');
            if (! $attendance->checked_in) {
                $attendance->checked_in_at = null;
            } elseif ($request->exists('checked_in_at')) {
                $attendance->checked_in_at = $request->input('checked_in_at');
            } else {
                $attendance->checked_in_at = now();
            }
        } elseif ($request->exists('checked_in_at')) {
            $attendance->checked_in_at = $request->input('checked_in_at');
        }

        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully',
            'data' => $this->formatAttendanceRow($attendance->fresh()),
        ]);
    }

    public function destroy(string $id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance not found',
            ], 404);
        }

        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance deleted successfully',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatAttendanceRow(Attendance $attendance): array
    {
        return [
            'id' => $attendance->id,
            'event_id' => $attendance->event_id,
            'name' => $attendance->name,
            'email' => $attendance->email,
            'phone' => $attendance->phone,
            'organization' => $attendance->organization,
            'registration_type' => $attendance->registration_type,
            'checked_in' => $attendance->checked_in,
            'checked_in_at' => $attendance->checked_in_at,
            'created_at' => $attendance->created_at,
            'updated_at' => $attendance->updated_at,
        ];
    }
}
