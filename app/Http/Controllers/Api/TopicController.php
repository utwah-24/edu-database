<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/topics",
     *     tags={"Topics"},
     *     summary="Get all topics",
     *     description="Returns a list of all topics with their speakers",
     *     @OA\Parameter(
     *         name="event_id",
     *         in="query",
     *         description="Filter topics by event UUID",
     *         required=false,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Topic"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Topic::with(['speakers', 'event']);

        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $topics = $query->orderBy('topic_date')->get();

        return response()->json([
            'success' => true,
            'data' => $topics
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/topics",
     *     tags={"Topics"},
     *     summary="Create a new topic",
     *     description="Creates a new topic for an event",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TopicRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Topic created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Topic")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'topic_date' => 'required|date',
            'content' => 'nullable|string',
            'topic_picture' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $topic = Topic::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Topic created successfully',
            'data' => $topic->load(['speakers', 'event'])
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/topics/{id}",
     *     tags={"Topics"},
     *     summary="Get topic by ID",
     *     description="Returns a single topic with all speakers",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Topic UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Topic")
     *     ),
     *     @OA\Response(response=404, description="Topic not found")
     * )
     */
    public function show(string $id)
    {
        $topic = Topic::with(['speakers', 'event'])->find($id);

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $topic
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/topics/{id}",
     *     tags={"Topics"},
     *     summary="Update a topic",
     *     description="Updates an existing topic",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Topic UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TopicRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Topic updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Topic")
     *     ),
     *     @OA\Response(response=404, description="Topic not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, string $id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'event_id' => 'sometimes|exists:events,id',
            'title' => 'sometimes|string|max:255',
            'topic_date' => 'sometimes|date',
            'content' => 'nullable|string',
            'topic_picture' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $topic->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Topic updated successfully',
            'data' => $topic->load(['speakers', 'event'])
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/topics/{id}",
     *     tags={"Topics"},
     *     summary="Delete a topic",
     *     description="Deletes a topic (speakers will have topic_id set to null)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Topic UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(response=200, description="Topic deleted successfully"),
     *     @OA\Response(response=404, description="Topic not found")
     * )
     */
    public function destroy(string $id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }

        $topic->delete();

        return response()->json([
            'success' => true,
            'message' => 'Topic deleted successfully'
        ]);
    }
}
