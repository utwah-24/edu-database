<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class AttendanceController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/attendances",
     *     tags={"Attendance"},
     *     summary="Register attendance for an event",
     *     description="Public endpoint for users to register they will attend an event.",
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
            'data' => $attendance,
        ], $statusCode);
    }
}
