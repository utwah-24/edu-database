<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events",
     *     tags={"Events"},
     *     summary="Get all events",
     *     description="Returns a list of all events",
     *     @OA\Parameter(
     *         name="published_only",
     *         in="query",
     *         description="Filter only published events",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Event"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->has('published_only') && $request->published_only) {
            $query->where('is_published', true);
        }

        $events = $query->orderBy('year', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/events",
     *     tags={"Events"},
     *     summary="Create a new event",
     *     description="Creates a new event. Optional cover: JSON field `cover_image` (URL or path string), or `multipart/form-data` with `cover_image` file upload.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EventRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Event created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|unique:events,year',
            'title' => 'required|string|max:255',
            'cover_image' => $request->hasFile('cover_image')
                ? ['nullable', 'image', 'max:5120']
                : ['nullable', 'string', 'max:2048'],
            'location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_published' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $payload = $request->except('cover_image');
        if ($request->hasFile('cover_image')) {
            $payload['cover_image'] = $this->storeCoverImageAndGetUrl($request->file('cover_image'), (string) $request->year);
        } elseif ($request->exists('cover_image')) {
            $payload['cover_image'] = filled($request->input('cover_image'))
                ? (string) $request->input('cover_image')
                : null;
        }

        $event = Event::create($payload);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     tags={"Events"},
     *     summary="Get event by ID",
     *     description="Returns a single event with all related data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Event UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(response=404, description="Event not found")
     * )
     */
    public function show(string $id)
    {
        $event = Event::with([
            'summaries',
            'themes',
            'topics.speakers',
            'programmes',
            'resources',
            'speakers',
            'faqs',
            'media',
            'sponsors',
            'galleries',
            'attendances'
        ])->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/events/year/{year}",
     *     tags={"Events"},
     *     summary="Get event by year",
     *     description="Returns an event for a specific year with all related data",
     *     @OA\Parameter(
     *         name="year",
     *         in="path",
     *         description="Event year",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(response=404, description="Event not found")
     * )
     */
    public function getByYear(string $year)
    {
        $event = Event::with([
            'summaries',
            'themes',
            'topics.speakers',
            'programmes',
            'resources',
            'speakers',
            'faqs',
            'media',
            'sponsors',
            'galleries',
            'attendances'
        ])->where('year', $year)->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found for year ' . $year
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/events/current",
     *     tags={"Events"},
     *     summary="Get current year event",
     *     description="Returns the current year's event with all related data",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(response=404, description="Current event not found")
     * )
     */
    public function current()
    {
        $currentYear = date('Y');
        $event = Event::with([
            'summaries',
            'themes',
            'topics.speakers',
            'programmes',
            'resources',
            'speakers',
            'faqs',
            'media',
            'sponsors',
            'galleries',
            'attendances'
        ])->where('year', $currentYear)
          ->where('is_published', true)
          ->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'No current event found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/events/{id}",
     *     tags={"Events"},
     *     summary="Update an event",
     *     description="Updates an existing event. Optional cover: JSON `cover_image` (URL/path string or null to clear), or multipart file `cover_image`. Omit the field to leave unchanged.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Event UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EventRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'year' => 'sometimes|integer|unique:events,year,' . $id,
            'title' => 'sometimes|string|max:255',
            'cover_image' => $request->hasFile('cover_image')
                ? ['nullable', 'image', 'max:5120']
                : ['nullable', 'string', 'max:2048'],
            'location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_published' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $payload = $request->except('cover_image');
        if ($request->hasFile('cover_image')) {
            $this->deleteIfLocalStorageUrl($event->cover_image);
            $eventYear = (string) ($payload['year'] ?? $event->year);
            $payload['cover_image'] = $this->storeCoverImageAndGetUrl($request->file('cover_image'), $eventYear);
        } elseif ($request->exists('cover_image')) {
            $this->deleteIfLocalStorageUrl($event->cover_image);
            $payload['cover_image'] = filled($request->input('cover_image'))
                ? (string) $request->input('cover_image')
                : null;
        }

        $event->update($payload);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'data' => $event
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/events/{id}",
     *     tags={"Events"},
     *     summary="Delete an event",
     *     description="Deletes an event and all related data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Event UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(response=200, description="Event deleted successfully"),
     *     @OA\Response(response=404, description="Event not found")
     * )
     */
    public function destroy(string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $this->deleteIfLocalStorageUrl($event->cover_image);
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }

    private function storeCoverImageAndGetUrl($file, string $year): string
    {
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBase = Str::slug($original);
        if (blank($safeBase)) {
            $safeBase = 'cover-image';
        }

        $extension = $file->getClientOriginalExtension();
        $fileName = $safeBase . '-' . now()->format('YmdHis') . '-' . Str::random(6) . '.' . $extension;
        $path = $file->storeAs("events/{$year}", $fileName, 'public');

        return Storage::disk('public')->url($path);
    }

    private function deleteIfLocalStorageUrl(?string $url): void
    {
        if (!filled($url)) {
            return;
        }

        if (str_starts_with($url, '/storage/')) {
            $relativePath = substr($url, strlen('/storage/'));
            Storage::disk('public')->delete($relativePath);
        }
    }
}
