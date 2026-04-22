<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSummary;
use App\Models\EventTheme;
use App\Models\EventProgramme;
use App\Models\EventResource;
use App\Models\Speaker;
use App\Models\FAQ;
use App\Models\Media;
use App\Models\Sponsor;
use App\Models\Gallery;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

class EventContentController extends Controller
{
    // ==================== EVENT SUMMARIES ====================

    /**
     * @OA\Get(
     *     path="/api/summaries",
     *     tags={"Event summaries"},
     *     summary="List summaries for an event",
     *     description="Query: `event_id` (event UUID).",
     *     @OA\Parameter(name="event_id", in="query", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/EventSummary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function indexSummaries(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'event_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = (string) $request->query('event_id');
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $items = EventSummary::query()
            ->where('event_id', $eventId)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(fn (EventSummary $s) => $this->formatSummaryRow($s))->values(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/summaries/{id}",
     *     tags={"Event summaries"},
     *     summary="Get summary by id",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/EventSummary")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Summary not found")
     * )
     */
    public function showSummary(string $id)
    {
        $summary = EventSummary::find($id);
        if (!$summary) {
            return response()->json(['success' => false, 'message' => 'Summary not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatSummaryRow($summary),
        ]);
    }
    
    /**
     * @OA\Post(
     *     path="/api/summaries",
     *     tags={"Event summaries"},
     *     summary="Create summary for an event",
     *     description="Body: `event_id` (UUID) and `summary` (string).",
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"event_id", "summary"},
     *         @OA\Property(property="event_id", type="string", format="uuid"),
     *         @OA\Property(property="summary", type="string")
     *     )),
     *     @OA\Response(
     *         response=201,
     *         description="Summary created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/EventSummary")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|uuid',
            'summary' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = $request->event_id;
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $summary = EventSummary::create([
            'event_id' => $eventId,
            'summary' => $request->summary,
        ]);

        return response()->json(['success' => true, 'data' => $this->formatSummaryRow($summary)], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/summaries/{id}",
     *     tags={"Event summaries"},
     *     summary="Update summary by id (Event summaries)",
     *     description="Body includes only `summary` (string).",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"summary"},
     *             @OA\Property(property="summary", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Summary updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/EventSummary")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Summary not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateSummary(Request $request, string $id)
    {
        $summary = EventSummary::find($id);
        if (!$summary) {
            return response()->json(['success' => false, 'message' => 'Summary not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'summary' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $summary->update(['summary' => $request->summary]);
        $summary->refresh();

        return response()->json([
            'success' => true,
            'data' => $this->formatSummaryRow($summary),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/summaries/{id}",
     *     tags={"Event summaries"},
     *     summary="Delete summary (Event summaries)",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="Summary deleted successfully"),
     *     @OA\Response(response=404, description="Summary not found")
     * )
     */
    public function destroySummary($id)
    {
        $summary = EventSummary::find($id);
        if (!$summary) {
            return response()->json(['success' => false, 'message' => 'Summary not found'], 404);
        }
        $summary->delete();
        return response()->json(['success' => true, 'message' => 'Summary deleted successfully']);
    }

    /**
     * @return array{id: string, event_id: string, summary: string, created_at: mixed, updated_at: mixed}
     */
    private function formatSummaryRow(EventSummary $row): array
    {
        return [
            'id' => $row->id,
            'event_id' => $row->event_id,
            'summary' => $row->summary,
            'created_at' => $row->created_at,
            'updated_at' => $row->updated_at,
        ];
    }

    // ==================== EVENT THEMES ====================

    /**
     * @OA\Get(
     *     path="/api/themes",
     *     tags={"Event themes"},
     *     summary="List all themes for an event",
     *     description="Query: `event_id` (event UUID). Returns theme rows for that event (UI title: **Event themes**). Each item: id, event_id, theme, description, created_at, updated_at.",
     *     @OA\Parameter(name="event_id", in="query", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/EventTheme")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function indexThemes(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'event_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = (string) $request->query('event_id');
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $items = EventTheme::query()
            ->where('event_id', $eventId)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(fn (EventTheme $t) => $this->formatThemeRow($t))->values(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/themes/{id}",
     *     tags={"Event themes"},
     *     summary="Get theme by id",
     *     description="Path `id` is the theme UUID.",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/EventTheme")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Theme not found")
     * )
     */
    public function showTheme(string $id)
    {
        $theme = EventTheme::find($id);
        if (!$theme) {
            return response()->json(['success' => false, 'message' => 'Theme not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatThemeRow($theme),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/events/{eventId}/themes",
     *     tags={"Event themes"},
     *     summary="Add theme to event (Event themes)",
     *     description="Creates a theme row. List themes for an event with GET /api/themes?event_id={eventUuid}. Single theme: GET /api/themes/{themeUuid}. Event detail may include `data.themes` when relations are loaded.",
     *     @OA\Parameter(name="eventId", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="theme", type="string"),
     *         @OA\Property(property="description", type="string")
     *     )),
     *     @OA\Response(
     *         response=201,
     *         description="Theme created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/EventTheme")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeTheme(Request $request, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'theme' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $theme = EventTheme::create([
            'event_id' => $eventId,
            'theme' => $request->theme,
            'description' => $request->description
        ]);

        return response()->json(['success' => true, 'data' => $theme], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/themes/{id}",
     *     tags={"Event themes"},
     *     summary="Update theme by id (Event themes)",
     *     description="Partial update: include `theme` and/or `description`.",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="theme", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Theme updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/EventTheme")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Theme not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateTheme(Request $request, string $id)
    {
        $theme = EventTheme::find($id);
        if (!$theme) {
            return response()->json(['success' => false, 'message' => 'Theme not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'theme' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $payload = $request->only(['theme', 'description']);
        if ($payload === []) {
            return response()->json([
                'success' => false,
                'message' => 'Provide at least one of: theme, description',
                'errors' => ['theme' => ['The theme or description field must be present.']],
            ], 422);
        }

        $theme->update($payload);
        $theme->refresh();

        return response()->json([
            'success' => true,
            'data' => $this->formatThemeRow($theme),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/themes/{id}",
     *     tags={"Event themes"},
     *     summary="Delete theme (Event themes)",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="Theme deleted successfully"),
     *     @OA\Response(response=404, description="Theme not found")
     * )
     */
    public function destroyTheme($id)
    {
        $theme = EventTheme::find($id);
        if (!$theme) {
            return response()->json(['success' => false, 'message' => 'Theme not found'], 404);
        }
        $theme->delete();
        return response()->json(['success' => true, 'message' => 'Theme deleted successfully']);
    }

    /**
     * @return array{id: string, event_id: string, theme: string, description: ?string, created_at: mixed, updated_at: mixed}
     */
    private function formatThemeRow(EventTheme $theme): array
    {
        return [
            'id' => $theme->id,
            'event_id' => $theme->event_id,
            'theme' => $theme->theme,
            'description' => $theme->description,
            'created_at' => $theme->created_at,
            'updated_at' => $theme->updated_at,
        ];
    }

    // ==================== EVENT PROGRAMMES ====================

    /**
     * @OA\Get(
     *     path="/api/programmes",
     *     tags={"Event programmes"},
     *     summary="List programmes for an event",
     *     description="Query: `event_id` (event UUID).",
     *     @OA\Parameter(name="event_id", in="query", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/EventProgramme")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function indexProgrammes(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'event_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = (string) $request->query('event_id');
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $items = EventProgramme::query()
            ->where('event_id', $eventId)
            ->orderBy('order')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(fn (EventProgramme $p) => $this->formatProgrammeRow($p))->values(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/programmes/{id}",
     *     tags={"Event programmes"},
     *     summary="Get programme by id",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/EventProgramme")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Programme not found")
     * )
     */
    public function showProgramme(string $id)
    {
        $programme = EventProgramme::find($id);
        if (!$programme) {
            return response()->json(['success' => false, 'message' => 'Programme not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatProgrammeRow($programme),
        ]);
    }
    
    /**
     * @OA\Post(
     *     path="/api/programmes",
     *     tags={"Event programmes"},
     *     summary="Create programme for an event",
     *     description="Body includes `event_id` (UUID) and programme fields.",
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"event_id", "title"},
     *         @OA\Property(property="event_id", type="string", format="uuid"),
     *         @OA\Property(property="title", type="string"),
     *         @OA\Property(property="description", type="string"),
     *         @OA\Property(property="start_time", type="string", format="date-time"),
     *         @OA\Property(property="end_time", type="string", format="date-time"),
     *         @OA\Property(property="location", type="string"),
     *         @OA\Property(property="speaker", type="string"),
     *         @OA\Property(property="event_pdf", type="string", format="uri", description="Google Drive link to the programme PDF"),
     *         @OA\Property(property="order", type="integer")
     *     )),
     *     @OA\Response(
     *         response=201,
     *         description="Programme created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/EventProgramme")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeProgramme(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|uuid',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'location' => 'nullable|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'event_pdf' => 'nullable|url|max:2048',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = $request->event_id;
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $programme = EventProgramme::create(array_merge(
            ['event_id' => $eventId],
            $request->only(['title', 'description', 'start_time', 'end_time', 'location', 'speaker', 'event_pdf', 'order'])
        ));

        return response()->json(['success' => true, 'data' => $this->formatProgrammeRow($programme)], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/programmes/{id}",
     *     tags={"Event programmes"},
     *     summary="Update programme by programme id",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="start_time", type="string", format="date-time"),
     *             @OA\Property(property="end_time", type="string", format="date-time"),
     *             @OA\Property(property="location", type="string"),
     *             @OA\Property(property="speaker", type="string"),
     *             @OA\Property(property="event_pdf", type="string", format="uri", nullable=true, description="Google Drive link to the programme PDF"),
     *             @OA\Property(property="order", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Programme updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/EventProgramme")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Programme not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateProgramme(Request $request, $id)
    {
        $programme = EventProgramme::find($id);
        if (!$programme) {
            return response()->json(['success' => false, 'message' => 'Programme not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'start_time' => 'sometimes|nullable|date',
            'end_time' => 'sometimes|nullable|date',
            'location' => 'sometimes|nullable|string|max:255',
            'speaker' => 'sometimes|nullable|string|max:255',
            'event_pdf' => 'sometimes|nullable|url|max:2048',
            'order' => 'sometimes|nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $payload = $request->only(['title', 'description', 'start_time', 'end_time', 'location', 'speaker', 'event_pdf', 'order']);
        if ($payload !== []) {
            $programme->fill($payload);
            if ($programme->start_time && $programme->end_time && $programme->end_time->lt($programme->start_time)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['end_time' => ['The end time must be after or equal to the start time.']],
                ], 422);
            }
            $programme->save();
        }

        $programme->refresh();

        return response()->json(['success' => true, 'data' => $this->formatProgrammeRow($programme)]);
    }

    /**
     * @OA\Delete(
     *     path="/api/programmes/{id}",
     *     tags={"Event programmes"},
     *     summary="Delete programme",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="Programme deleted successfully"),
     *     @OA\Response(response=404, description="Programme not found")
     * )
     */
    public function destroyProgramme($id)
    {
        $programme = EventProgramme::find($id);
        if (!$programme) {
            return response()->json(['success' => false, 'message' => 'Programme not found'], 404);
        }
        $programme->delete();
        return response()->json(['success' => true, 'message' => 'Programme deleted successfully']);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatProgrammeRow(EventProgramme $programme): array
    {
        return [
            'id' => $programme->id,
            'event_id' => $programme->event_id,
            'title' => $programme->title,
            'description' => $programme->description,
            'start_time' => $programme->start_time,
            'end_time' => $programme->end_time,
            'location' => $programme->location,
            'speaker' => $programme->speaker,
            'event_pdf' => $programme->event_pdf,
            'order' => $programme->order,
            'created_at' => $programme->created_at,
            'updated_at' => $programme->updated_at,
        ];
    }

    // ==================== SPEAKERS ====================

    /**
     * @OA\Get(
     *     path="/api/speakers",
     *     tags={"Speakers"},
     *     summary="List speakers for an event",
     *     description="Query: `event_id` (event UUID).",
     *     @OA\Parameter(name="event_id", in="query", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Speaker")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function indexSpeakers(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'event_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = (string) $request->query('event_id');
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $items = Speaker::query()
            ->where('event_id', $eventId)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(fn (Speaker $s) => $this->formatSpeakerRow($s))->values(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/speakers/{id}",
     *     tags={"Speakers"},
     *     summary="Get speaker by id",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Speaker")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Speaker not found")
     * )
     */
    public function showSpeaker(string $id)
    {
        $speaker = Speaker::find($id);
        if (!$speaker) {
            return response()->json(['success' => false, 'message' => 'Speaker not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatSpeakerRow($speaker),
        ]);
    }
    
    /**
     * @OA\Post(
     *     path="/api/speakers",
     *     tags={"Speakers"},
     *     summary="Create speaker for an event",
     *     description="Body includes `event_id` (UUID). Use multipart/form-data to upload `photo` as a file, or JSON without a file.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"event_id", "name"},
     *             @OA\Property(property="event_id", type="string", format="uuid"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="organization", type="string"),
     *             @OA\Property(property="bio", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="linkedin", type="string"),
     *             @OA\Property(property="twitter", type="string"),
     *             @OA\Property(property="topic_id", type="string", format="uuid"),
     *             @OA\Property(property="order", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Speaker created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Speaker")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeSpeaker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|uuid',
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:5120',
            'email' => 'nullable|email',
            'linkedin' => 'nullable|string',
            'twitter' => 'nullable|string',
            'topic_id' => 'nullable|uuid',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = $request->event_id;
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $payload = array_merge(
            ['event_id' => $eventId],
            $request->only(['name', 'title', 'organization', 'bio', 'email', 'linkedin', 'twitter', 'topic_id', 'order'])
        );

        if ($request->hasFile('photo')) {
            $payload['photo'] = $this->storeImageAndGetUrl($request->file('photo'), "speakers/{$eventId}");
        }

        $speaker = Speaker::create($payload);

        return response()->json(['success' => true, 'data' => $this->formatSpeakerRow($speaker)], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/speakers/{id}",
     *     tags={"Speakers"},
     *     summary="Update speaker by speaker id",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="organization", type="string"),
     *             @OA\Property(property="bio", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="linkedin", type="string"),
     *             @OA\Property(property="twitter", type="string"),
     *             @OA\Property(property="topic_id", type="string", format="uuid"),
     *             @OA\Property(property="order", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Speaker updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Speaker")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Speaker not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateSpeaker(Request $request, $id)
    {
        $speaker = Speaker::find($id);
        if (!$speaker) {
            return response()->json(['success' => false, 'message' => 'Speaker not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'title' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:5120',
            'email' => 'nullable|email',
            'linkedin' => 'nullable|string',
            'twitter' => 'nullable|string',
            'topic_id' => 'nullable|uuid',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $payload = $request->only(['name', 'title', 'organization', 'bio', 'email', 'linkedin', 'twitter', 'topic_id', 'order']);
        if ($request->hasFile('photo')) {
            $this->deleteIfLocalStorageUrl($speaker->photo);
            $payload['photo'] = $this->storeImageAndGetUrl($request->file('photo'), "speakers/{$speaker->event_id}");
        }

        if ($payload !== []) {
            $speaker->update($payload);
        }

        $speaker->refresh();

        return response()->json(['success' => true, 'data' => $this->formatSpeakerRow($speaker)]);
    }

    /**
     * @OA\Delete(
     *     path="/api/speakers/{id}",
     *     tags={"Speakers"},
     *     summary="Delete speaker",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="Speaker deleted successfully"),
     *     @OA\Response(response=404, description="Speaker not found")
     * )
     */
    public function destroySpeaker($id)
    {
        $speaker = Speaker::find($id);
        if (!$speaker) {
            return response()->json(['success' => false, 'message' => 'Speaker not found'], 404);
        }
        $this->deleteIfLocalStorageUrl($speaker->photo);
        $speaker->delete();
        return response()->json(['success' => true, 'message' => 'Speaker deleted successfully']);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatSpeakerRow(Speaker $speaker): array
    {
        return [
            'id' => $speaker->id,
            'event_id' => $speaker->event_id,
            'topic_id' => $speaker->topic_id,
            'name' => $speaker->name,
            'title' => $speaker->title,
            'organization' => $speaker->organization,
            'bio' => $speaker->bio,
            'photo' => $speaker->photo,
            'email' => $speaker->email,
            'linkedin' => $speaker->linkedin,
            'twitter' => $speaker->twitter,
            'order' => $speaker->order,
            'created_at' => $speaker->created_at,
            'updated_at' => $speaker->updated_at,
        ];
    }

    // ==================== SPONSORS ====================

    /**
     * @OA\Get(
     *     path="/api/sponsors",
     *     tags={"Sponsors"},
     *     summary="List sponsors for an event",
     *     description="Query: `event_id` (event UUID).",
     *     @OA\Parameter(name="event_id", in="query", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Sponsor")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function indexSponsors(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'event_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = (string) $request->query('event_id');
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $items = Sponsor::query()
            ->where('event_id', $eventId)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(fn (Sponsor $s) => $this->formatSponsorRow($s))->values(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/sponsors/{id}",
     *     tags={"Sponsors"},
     *     summary="Get sponsor by id",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Sponsor")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Sponsor not found")
     * )
     */
    public function showSponsor(string $id)
    {
        $sponsor = Sponsor::find($id);
        if (!$sponsor) {
            return response()->json(['success' => false, 'message' => 'Sponsor not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatSponsorRow($sponsor),
        ]);
    }
    
    /**
     * @OA\Post(
     *     path="/api/sponsors",
     *     tags={"Sponsors"},
     *     summary="Create sponsor for an event",
     *     description="Body includes `event_id` (UUID). Use multipart/form-data to upload `logo` as a file, or JSON without a file.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"event_id", "name"},
     *             @OA\Property(property="event_id", type="string", format="uuid"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="tier", type="string"),
     *             @OA\Property(property="website", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="order", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sponsor created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Sponsor")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeSponsor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|uuid',
            'name' => 'required|string|max:255',
            'tier' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:5120',
            'website' => 'nullable|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = $request->event_id;
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $payload = array_merge(
            ['event_id' => $eventId],
            $request->only(['name', 'tier', 'website', 'description', 'order'])
        );
        if ($request->hasFile('logo')) {
            $payload['logo'] = $this->storeImageAndGetUrl($request->file('logo'), "sponsors/{$eventId}");
        }

        $sponsor = Sponsor::create($payload);

        return response()->json(['success' => true, 'data' => $this->formatSponsorRow($sponsor)], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/sponsors/{id}",
     *     tags={"Sponsors"},
     *     summary="Update sponsor by sponsor id",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="tier", type="string"),
     *             @OA\Property(property="website", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="order", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sponsor updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Sponsor")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Sponsor not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateSponsor(Request $request, $id)
    {
        $sponsor = Sponsor::find($id);
        if (!$sponsor) {
            return response()->json(['success' => false, 'message' => 'Sponsor not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'tier' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:5120',
            'website' => 'nullable|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $payload = $request->only(['name', 'tier', 'website', 'description', 'order']);
        if ($request->hasFile('logo')) {
            $this->deleteIfLocalStorageUrl($sponsor->logo);
            $payload['logo'] = $this->storeImageAndGetUrl($request->file('logo'), "sponsors/{$sponsor->event_id}");
        }

        if ($payload !== []) {
            $sponsor->update($payload);
        }

        $sponsor->refresh();

        return response()->json(['success' => true, 'data' => $this->formatSponsorRow($sponsor)]);
    }

    /**
     * @OA\Delete(
     *     path="/api/sponsors/{id}",
     *     tags={"Sponsors"},
     *     summary="Delete sponsor",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="Sponsor deleted successfully"),
     *     @OA\Response(response=404, description="Sponsor not found")
     * )
     */
    public function destroySponsor($id)
    {
        $sponsor = Sponsor::find($id);
        if (!$sponsor) {
            return response()->json(['success' => false, 'message' => 'Sponsor not found'], 404);
        }
        $this->deleteIfLocalStorageUrl($sponsor->logo);
        $sponsor->delete();
        return response()->json(['success' => true, 'message' => 'Sponsor deleted successfully']);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatSponsorRow(Sponsor $sponsor): array
    {
        return [
            'id' => $sponsor->id,
            'event_id' => $sponsor->event_id,
            'name' => $sponsor->name,
            'tier' => $sponsor->tier,
            'logo' => $sponsor->logo,
            'website' => $sponsor->website,
            'description' => $sponsor->description,
            'order' => $sponsor->order,
            'created_at' => $sponsor->created_at,
            'updated_at' => $sponsor->updated_at,
        ];
    }

    // ==================== FAQS ====================

    /**
     * @OA\Get(
     *     path="/api/faqs",
     *     tags={"FAQ"},
     *     summary="List FAQs for an event",
     *     description="Query: `event_id` (event UUID).",
     *     @OA\Parameter(name="event_id", in="query", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function indexFaqs(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'event_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = (string) $request->query('event_id');
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $items = FAQ::query()
            ->where('event_id', $eventId)
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(fn (FAQ $f) => $this->formatFaqRow($f))->values(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/faqs",
     *     tags={"FAQ"},
     *     summary="Create FAQ for an event",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"event_id", "question", "answer"},
     *             @OA\Property(property="event_id", type="string", format="uuid"),
     *             @OA\Property(property="question", type="string"),
     *             @OA\Property(property="answer", type="string"),
     *             @OA\Property(property="order", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="FAQ created successfully"),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeFaq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|uuid',
            'question' => 'required|string',
            'answer' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        if (! Event::whereKey($request->event_id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $faq = FAQ::create([
            'event_id' => $request->event_id,
            'question' => $request->question,
            'answer' => $request->answer,
            'order' => $request->input('order', 0),
        ]);

        return response()->json(['success' => true, 'data' => $this->formatFaqRow($faq)], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/faqs/{id}",
     *     tags={"FAQ"},
     *     summary="Get FAQ by id",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="FAQ not found")
     * )
     */
    public function showFaq(string $id)
    {
        $faq = FAQ::find($id);
        if (!$faq) {
            return response()->json(['success' => false, 'message' => 'FAQ not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $this->formatFaqRow($faq)]);
    }

    /**
     * @OA\Put(
     *     path="/api/faqs/{id}",
     *     tags={"FAQ"},
     *     summary="Update FAQ",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="question", type="string"),
     *             @OA\Property(property="answer", type="string"),
     *             @OA\Property(property="order", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="FAQ updated"),
     *     @OA\Response(response=404, description="FAQ not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateFaq(Request $request, string $id)
    {
        $faq = FAQ::find($id);
        if (!$faq) {
            return response()->json(['success' => false, 'message' => 'FAQ not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'question' => 'sometimes|string',
            'answer' => 'sometimes|string',
            'order' => 'sometimes|nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $payload = $request->only(['question', 'answer', 'order']);
        if ($payload === []) {
            return response()->json([
                'success' => false,
                'message' => 'Provide at least one of: question, answer, order',
            ], 422);
        }

        $faq->update($payload);
        $faq->refresh();

        return response()->json(['success' => true, 'data' => $this->formatFaqRow($faq)]);
    }

    /**
     * @OA\Delete(
     *     path="/api/faqs/{id}",
     *     tags={"FAQ"},
     *     summary="Delete FAQ",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="FAQ deleted successfully"),
     *     @OA\Response(response=404, description="FAQ not found")
     * )
     */
    public function destroyFaq($id)
    {
        $faq = FAQ::find($id);
        if (!$faq) {
            return response()->json(['success' => false, 'message' => 'FAQ not found'], 404);
        }
        $faq->delete();
        return response()->json(['success' => true, 'message' => 'FAQ deleted successfully']);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatFaqRow(FAQ $faq): array
    {
        return [
            'id' => $faq->id,
            'event_id' => $faq->event_id,
            'question' => $faq->question,
            'answer' => $faq->answer,
            'order' => $faq->order,
            'created_at' => $faq->created_at,
            'updated_at' => $faq->updated_at,
        ];
    }

    // ==================== MEDIA (event videos / files — public fields only) ====================

    /**
     * @OA\Get(
     *     path="/api/media",
     *     tags={"Media"},
     *     summary="List media for an event",
     *     description="Query: `event_id` (event UUID). Each item includes `id`, `title`, `file_path`, and `description` only.",
     *     @OA\Parameter(name="event_id", in="query", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/EventMedia")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function indexMedia(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'event_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = (string) $request->query('event_id');
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $items = Media::query()
            ->where('event_id', $eventId)
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(fn (Media $m) => $this->formatMediaPublicRow($m))->values(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/media",
     *     tags={"Media"},
     *     summary="Create media for an event",
     *     description="`type` is optional (`image`, `video`, `document`); defaults to `document`. Response includes `id`, `title`, `file_path`, `description` only.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"event_id", "file_path"},
     *             @OA\Property(property="event_id", type="string", format="uuid"),
     *             @OA\Property(property="title", type="string", nullable=true),
     *             @OA\Property(property="file_path", type="string", example="/media/2026/promo.mp4"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="type", type="string", enum={"image", "video", "document"}),
     *             @OA\Property(property="order", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Media created"),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeMedia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|uuid',
            'title' => 'nullable|string|max:255',
            'file_path' => 'required|string|max:2048',
            'description' => 'nullable|string',
            'type' => ['nullable', 'string', Rule::in(['image', 'video', 'document'])],
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = (string) $request->event_id;
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $nextOrder = (int) Media::where('event_id', $eventId)->max('order');

        $media = Media::create([
            'event_id' => $eventId,
            'title' => $request->input('title'),
            'type' => $request->input('type', 'document'),
            'file_path' => $request->file_path,
            'thumbnail' => null,
            'description' => $request->input('description'),
            'order' => $request->input('order', $nextOrder + 1),
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->formatMediaPublicRow($media),
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/media/{id}",
     *     tags={"Media"},
     *     summary="Update media",
     *     description="Partial update of `title`, `file_path`, and/or `description`. Response includes those fields plus `id`.",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", nullable=true),
     *             @OA\Property(property="file_path", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Media updated"),
     *     @OA\Response(response=404, description="Media not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateMedia(Request $request, string $id)
    {
        $media = Media::find($id);
        if (! $media) {
            return response()->json(['success' => false, 'message' => 'Media not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|nullable|string|max:255',
            'file_path' => 'sometimes|string|max:2048',
            'description' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $payload = $request->only(['title', 'file_path', 'description']);
        if ($payload === []) {
            return response()->json([
                'success' => false,
                'message' => 'Provide at least one of: title, file_path, description',
            ], 422);
        }

        $media->fill($payload);
        $media->save();
        $media->refresh();

        return response()->json([
            'success' => true,
            'data' => $this->formatMediaPublicRow($media),
        ]);
    }

    /**
     * @return array{id: string, title: ?string, file_path: string, description: ?string}
     */
    private function formatMediaPublicRow(Media $media): array
    {
        return [
            'id' => $media->id,
            'title' => $media->title,
            'file_path' => $media->file_path,
            'description' => $media->description,
        ];
    }

    // ==================== EVENT RESOURCES (event_resources table) ====================

    /**
     * @OA\Get(
     *     path="/api/resources",
     *     tags={"Event Resources"},
     *     summary="List event resources",
     *     description="Returns id, event_id, title, description, file_path, file_type, url, created_at, updated_at. Optional filter: `event_id` (UUID) or `year`. If both omitted, returns all resources.",
     *     @OA\Parameter(name="event_id", in="query", required=false, @OA\Schema(type="string", format="uuid")),
     *     @OA\Parameter(name="year", in="query", required=false, @OA\Schema(type="integer", example=2026)),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/EventResourceRecord")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Event not found (when event_id is set)"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function indexResources(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'event_id' => 'nullable|uuid',
            'year' => 'nullable|integer|min:1900|max:2100',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $query = EventResource::query();

        if ($request->filled('event_id')) {
            $eventId = (string) $request->query('event_id');
            if (! Event::whereKey($eventId)->exists()) {
                return response()->json(['success' => false, 'message' => 'Event not found'], 404);
            }
            $query->where('event_id', $eventId);
        } elseif ($request->filled('year')) {
            $year = (int) $request->query('year');
            $query->whereHas('event', fn ($q) => $q->where('year', $year));
        }

        $items = $query->orderByDesc('created_at')->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(fn (EventResource $r) => $this->formatEventResourceRow($r))->values(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/resources",
     *     tags={"Event Resources"},
     *     summary="Create an event resource",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"event_id", "title"},
     *             @OA\Property(property="event_id", type="string", format="uuid"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="file_path", type="string", nullable=true),
     *             @OA\Property(property="file_type", type="string", nullable=true, example="PDF"),
     *             @OA\Property(property="url", type="string", format="uri", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created"),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeResource(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|uuid',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|string|max:2048',
            'file_type' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        if (! Event::whereKey($request->event_id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $resource = EventResource::create($request->only([
            'event_id', 'title', 'description', 'file_path', 'file_type', 'url',
        ]));

        return response()->json([
            'success' => true,
            'data' => $this->formatEventResourceRow($resource->fresh()),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/resources/{id}",
     *     tags={"Event Resources"},
     *     summary="Get event resource by id",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/EventResourceRecord")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Resource not found")
     * )
     */
    public function showResource(string $id)
    {
        $resource = EventResource::find($id);
        if (! $resource) {
            return response()->json(['success' => false, 'message' => 'Resource not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatEventResourceRow($resource),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/resources/{id}",
     *     tags={"Event Resources"},
     *     summary="Update an event resource",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="file_path", type="string", nullable=true),
     *             @OA\Property(property="file_type", type="string", nullable=true),
     *             @OA\Property(property="url", type="string", format="uri", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated"),
     *     @OA\Response(response=404, description="Resource not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateResource(Request $request, string $id)
    {
        $resource = EventResource::find($id);
        if (! $resource) {
            return response()->json(['success' => false, 'message' => 'Resource not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'file_path' => 'sometimes|nullable|string|max:2048',
            'file_type' => 'sometimes|nullable|string|max:255',
            'url' => 'sometimes|nullable|string|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $payload = $request->only(['title', 'description', 'file_path', 'file_type', 'url']);
        if ($payload === []) {
            return response()->json([
                'success' => false,
                'message' => 'Provide at least one of: title, description, file_path, file_type, url',
            ], 422);
        }

        $resource->fill($payload);
        $resource->save();
        $resource->refresh();

        return response()->json([
            'success' => true,
            'data' => $this->formatEventResourceRow($resource),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/resources/{id}",
     *     tags={"Event Resources"},
     *     summary="Delete an event resource",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="Deleted"),
     *     @OA\Response(response=404, description="Resource not found")
     * )
     */
    public function destroyResource(string $id)
    {
        $resource = EventResource::find($id);
        if (! $resource) {
            return response()->json(['success' => false, 'message' => 'Resource not found'], 404);
        }
        $resource->delete();

        return response()->json(['success' => true, 'message' => 'Resource deleted successfully']);
    }

    /**
     * @return array{
     *     id: string,
     *     event_id: string,
     *     title: string,
     *     description: ?string,
     *     file_path: ?string,
     *     file_type: ?string,
     *     url: ?string,
     *     created_at: mixed,
     *     updated_at: mixed
     * }
     */
    private function formatEventResourceRow(EventResource $resource): array
    {
        return [
            'id' => $resource->id,
            'event_id' => $resource->event_id,
            'title' => $resource->title,
            'description' => $resource->description,
            'file_path' => $resource->file_path,
            'file_type' => $resource->file_type,
            'url' => $resource->url,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }

    // ==================== GALLERY (image URLs per event) ====================

    /**
     * @OA\Get(
     *     path="/api/gallery",
     *     tags={"Gallery"},
     *     summary="List gallery images for an event",
     *     description="Query: `event_id` (event UUID).",
     *     @OA\Parameter(name="event_id", in="query", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function indexGallery(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'event_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = (string) $request->query('event_id');
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $items = Gallery::query()
            ->where('event_id', $eventId)
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(fn (Gallery $g) => $this->formatGalleryRow($g))->values(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/gallery",
     *     tags={"Gallery"},
     *     summary="Upload gallery images for an event",
     *     description="Multipart: `event_id` (text) and `images[]` files.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"event_id", "images"},
     *                 @OA\Property(property="event_id", type="string", format="uuid"),
     *                 @OA\Property(
     *                     property="images",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Images created"),
     *     @OA\Response(response=404, description="Event not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|uuid',
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $eventId = (string) $request->event_id;
        if (! Event::whereKey($eventId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $images = $request->file('images', []);
        $baseOrder = (int) Gallery::where('event_id', $eventId)->max('order');

        $created = [];
        foreach ($images as $i => $image) {
            $path = $this->storeImageWithSafeName($image, "gallery/{$eventId}");
            $url = Storage::disk('public')->url($path);
            $created[] = Gallery::create([
                'event_id' => $eventId,
                'url' => $url,
                'order' => $baseOrder + $i + 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => count($created) . ' image(s) added',
            'data' => collect($created)->map(fn (Gallery $g) => $this->formatGalleryRow($g))->values(),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/gallery/{id}",
     *     tags={"Gallery"},
     *     summary="Get gallery image by id",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function showGallery(string $id)
    {
        $row = Gallery::find($id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Gallery image not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $this->formatGalleryRow($row)]);
    }

    /**
     * @OA\Put(
     *     path="/api/gallery/{id}",
     *     tags={"Gallery"},
     *     summary="Update gallery image",
     *     description="JSON: `url` and/or `order`. Multipart: optional `image` file replaces stored file (and updates `url`).",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="url", type="string"),
     *                 @OA\Property(property="order", type="integer")
     *             )
     *         ),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="url", type="string"),
     *                 @OA\Property(property="order", type="integer"),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated"),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateGallery(Request $request, string $id)
    {
        $row = Gallery::find($id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Gallery image not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'url' => 'sometimes|nullable|string|max:2048',
            'order' => 'sometimes|nullable|integer',
            'image' => 'sometimes|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            $this->deleteGalleryStoredFile($row->url);
            $path = $this->storeImageWithSafeName($request->file('image'), "gallery/{$row->event_id}");
            $row->url = Storage::disk('public')->url($path);
        }

        if ($request->exists('url') && ! $request->hasFile('image')) {
            $row->url = (string) $request->input('url');
        }

        if ($request->has('order')) {
            $row->order = (int) $request->input('order');
        }

        if (! $request->hasFile('image')
            && ! ($request->exists('url') && ! $request->hasFile('image'))
            && ! $request->has('order')) {
            return response()->json([
                'success' => false,
                'message' => 'Provide at least one of: image (file), url, order',
            ], 422);
        }

        $row->save();

        return response()->json(['success' => true, 'data' => $this->formatGalleryRow($row->fresh())]);
    }

    /**
     * @OA\Delete(
     *     path="/api/gallery/{id}",
     *     tags={"Gallery"},
     *     summary="Remove a gallery image",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="Deleted"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroyGallery(string $id)
    {
        $row = Gallery::find($id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Gallery image not found'], 404);
        }

        $this->deleteGalleryStoredFile($row->url);

        $row->delete();

        return response()->json(['success' => true, 'message' => 'Gallery image deleted successfully']);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatGalleryRow(Gallery $gallery): array
    {
        return [
            'id' => $gallery->id,
            'event_id' => $gallery->event_id,
            'url' => $gallery->url,
            'order' => $gallery->order,
            'created_at' => $gallery->created_at,
            'updated_at' => $gallery->updated_at,
        ];
    }

    private function deleteGalleryStoredFile(?string $url): void
    {
        if (! filled($url)) {
            return;
        }

        if (str_starts_with($url, '/storage/')) {
            $relativePath = substr($url, strlen('/storage/'));
            Storage::disk('public')->delete($relativePath);
        }
    }

    private function storeImageAndGetUrl($file, string $directory): string
    {
        $path = $this->storeImageWithSafeName($file, $directory);

        return Storage::disk('public')->url($path);
    }

    private function storeImageWithSafeName($file, string $directory): string
    {
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBase = Str::slug($original);
        if (blank($safeBase)) {
            $safeBase = 'image';
        }

        $extension = $file->getClientOriginalExtension();
        $fileName = $safeBase . '-' . now()->format('YmdHis') . '-' . Str::random(6) . '.' . $extension;

        return $file->storeAs($directory, $fileName, 'public');
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
