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
use Illuminate\Support\Facades\Validator;

class EventContentController extends Controller
{
    // ==================== EVENT SUMMARIES ====================
    
    /**
     * @OA\Post(
     *     path="/api/events/{eventId}/summaries",
     *     tags={"Event Content"},
     *     summary="Add summary to event",
     *     @OA\Parameter(name="eventId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="summary", type="string")
     *     )),
     *     @OA\Response(response=201, description="Summary created successfully")
     * )
     */
    public function storeSummary(Request $request, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'summary' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $summary = EventSummary::create([
            'event_id' => $eventId,
            'summary' => $request->summary
        ]);

        return response()->json(['success' => true, 'data' => $summary], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/summaries/{id}",
     *     tags={"Event Content"},
     *     summary="Delete summary",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Summary deleted successfully")
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

    // ==================== EVENT THEMES ====================
    
    /**
     * @OA\Post(
     *     path="/api/events/{eventId}/themes",
     *     tags={"Event Content"},
     *     summary="Add theme to event",
     *     @OA\Parameter(name="eventId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="theme", type="string"),
     *         @OA\Property(property="description", type="string")
     *     )),
     *     @OA\Response(response=201, description="Theme created successfully")
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
     * @OA\Delete(
     *     path="/api/themes/{id}",
     *     tags={"Event Content"},
     *     summary="Delete theme",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Theme deleted successfully")
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

    // ==================== EVENT PROGRAMMES ====================
    
    /**
     * @OA\Post(
     *     path="/api/events/{eventId}/programmes",
     *     tags={"Event Content"},
     *     summary="Add programme to event",
     *     @OA\Parameter(name="eventId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="title", type="string"),
     *         @OA\Property(property="description", type="string"),
     *         @OA\Property(property="start_time", type="string", format="date-time"),
     *         @OA\Property(property="end_time", type="string", format="date-time"),
     *         @OA\Property(property="location", type="string"),
     *         @OA\Property(property="speaker", type="string"),
     *         @OA\Property(property="order", type="integer")
     *     )),
     *     @OA\Response(response=201, description="Programme created successfully")
     * )
     */
    public function storeProgramme(Request $request, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'location' => 'nullable|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'order' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $programme = EventProgramme::create(array_merge(
            ['event_id' => $eventId],
            $request->all()
        ));

        return response()->json(['success' => true, 'data' => $programme], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/programmes/{id}",
     *     tags={"Event Content"},
     *     summary="Update programme",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="title", type="string"),
     *         @OA\Property(property="order", type="integer")
     *     )),
     *     @OA\Response(response=200, description="Programme updated successfully")
     * )
     */
    public function updateProgramme(Request $request, $id)
    {
        $programme = EventProgramme::find($id);
        if (!$programme) {
            return response()->json(['success' => false, 'message' => 'Programme not found'], 404);
        }
        $programme->update($request->all());
        return response()->json(['success' => true, 'data' => $programme]);
    }

    /**
     * @OA\Delete(
     *     path="/api/programmes/{id}",
     *     tags={"Event Content"},
     *     summary="Delete programme",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Programme deleted successfully")
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

    // ==================== SPEAKERS ====================
    
    /**
     * @OA\Post(
     *     path="/api/events/{eventId}/speakers",
     *     tags={"Event Content"},
     *     summary="Add speaker to event",
     *     @OA\Parameter(name="eventId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="title", type="string"),
     *         @OA\Property(property="organization", type="string"),
     *         @OA\Property(property="bio", type="string"),
     *         @OA\Property(property="photo", type="string"),
     *         @OA\Property(property="email", type="string"),
     *         @OA\Property(property="order", type="integer")
     *     )),
     *     @OA\Response(response=201, description="Speaker created successfully")
     * )
     */
    public function storeSpeaker(Request $request, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|string',
            'email' => 'nullable|email',
            'linkedin' => 'nullable|string',
            'twitter' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $speaker = Speaker::create(array_merge(
            ['event_id' => $eventId],
            $request->all()
        ));

        return response()->json(['success' => true, 'data' => $speaker], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/speakers/{id}",
     *     tags={"Event Content"},
     *     summary="Update speaker",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Speaker updated successfully")
     * )
     */
    public function updateSpeaker(Request $request, $id)
    {
        $speaker = Speaker::find($id);
        if (!$speaker) {
            return response()->json(['success' => false, 'message' => 'Speaker not found'], 404);
        }
        $speaker->update($request->all());
        return response()->json(['success' => true, 'data' => $speaker]);
    }

    /**
     * @OA\Delete(
     *     path="/api/speakers/{id}",
     *     tags={"Event Content"},
     *     summary="Delete speaker",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Speaker deleted successfully")
     * )
     */
    public function destroySpeaker($id)
    {
        $speaker = Speaker::find($id);
        if (!$speaker) {
            return response()->json(['success' => false, 'message' => 'Speaker not found'], 404);
        }
        $speaker->delete();
        return response()->json(['success' => true, 'message' => 'Speaker deleted successfully']);
    }

    // ==================== SPONSORS ====================
    
    /**
     * @OA\Post(
     *     path="/api/events/{eventId}/sponsors",
     *     tags={"Event Content"},
     *     summary="Add sponsor to event",
     *     @OA\Parameter(name="eventId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="tier", type="string"),
     *         @OA\Property(property="logo", type="string"),
     *         @OA\Property(property="website", type="string")
     *     )),
     *     @OA\Response(response=201, description="Sponsor created successfully")
     * )
     */
    public function storeSponsor(Request $request, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'tier' => 'nullable|string|max:255',
            'logo' => 'nullable|string',
            'website' => 'nullable|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $sponsor = Sponsor::create(array_merge(
            ['event_id' => $eventId],
            $request->all()
        ));

        return response()->json(['success' => true, 'data' => $sponsor], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/sponsors/{id}",
     *     tags={"Event Content"},
     *     summary="Delete sponsor",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Sponsor deleted successfully")
     * )
     */
    public function destroySponsor($id)
    {
        $sponsor = Sponsor::find($id);
        if (!$sponsor) {
            return response()->json(['success' => false, 'message' => 'Sponsor not found'], 404);
        }
        $sponsor->delete();
        return response()->json(['success' => true, 'message' => 'Sponsor deleted successfully']);
    }

    // ==================== FAQS ====================
    
    /**
     * @OA\Post(
     *     path="/api/events/{eventId}/faqs",
     *     tags={"Event Content"},
     *     summary="Add FAQ to event",
     *     @OA\Parameter(name="eventId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="question", type="string"),
     *         @OA\Property(property="answer", type="string")
     *     )),
     *     @OA\Response(response=201, description="FAQ created successfully")
     * )
     */
    public function storeFaq(Request $request, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required|string',
            'order' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $faq = FAQ::create(array_merge(
            ['event_id' => $eventId],
            $request->all()
        ));

        return response()->json(['success' => true, 'data' => $faq], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/faqs/{id}",
     *     tags={"Event Content"},
     *     summary="Delete FAQ",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="FAQ deleted successfully")
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
}
