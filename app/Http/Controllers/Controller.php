<?php

namespace App\Http\Controllers;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Event Management API",
 *         version="1.0.0",
 *         description="API documentation for the Event Management System. This system allows managing yearly events with all their associated content including summaries, themes, programmes, speakers, sponsors, FAQs, media, galleries, and attendances.",
 *         @OA\Contact(
 *             email="admin@example.com"
 *         )
 *     ),
 *     @OA\Server(
 *         url="http://localhost:8000",
 *         description="Local Development Server"
 *     ),
 *     @OA\Server(
 *         url="http://localhost",
 *         description="Production Server"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Event",
 *     type="object",
 *     title="Event",
 *     description="Event model",
 *     @OA\Property(property="id", type="string", format="uuid", example="9b3d8f4e-7c1a-4d2b-9e5f-1a2b3c4d5e6f"),
 *     @OA\Property(property="year", type="integer", example=2026),
 *     @OA\Property(property="title", type="string", example="Annual Tech Conference 2026"),
 *     @OA\Property(property="location", type="string", example="Convention Center"),
 *     @OA\Property(property="start_date", type="string", format="date", example="2026-06-01"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2026-06-03"),
 *     @OA\Property(property="is_published", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="EventRequest",
 *     type="object",
 *     required={"year", "title"},
 *     @OA\Property(property="year", type="integer", example=2026),
 *     @OA\Property(property="title", type="string", example="Annual Tech Conference 2026"),
 *     @OA\Property(property="location", type="string", example="Convention Center"),
 *     @OA\Property(property="start_date", type="string", format="date", example="2026-06-01"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2026-06-03"),
 *     @OA\Property(property="is_published", type="boolean", example=true)
 * )
 * 
 * @OA\Schema(
 *     schema="Topic",
 *     type="object",
 *     title="Topic",
 *     description="Topic model with speakers",
 *     @OA\Property(property="id", type="string", format="uuid", example="8a2c7b3d-6e4f-5c1a-2b9e-3f4a5b6c7d8e"),
 *     @OA\Property(property="event_id", type="string", format="uuid", example="9b3d8f4e-7c1a-4d2b-9e5f-1a2b3c4d5e6f"),
 *     @OA\Property(property="title", type="string", example="The Future of Artificial Intelligence"),
 *     @OA\Property(property="topic_date", type="string", format="date", example="2026-11-10"),
 *     @OA\Property(property="content", type="string", example="Exploring how AI is transforming industries..."),
 *     @OA\Property(property="topic_picture", type="string", example="/images/topics/ai-future.jpg"),
 *     @OA\Property(property="order", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="TopicRequest",
 *     type="object",
 *     required={"event_id", "title", "topic_date"},
 *     @OA\Property(property="event_id", type="string", format="uuid", example="9b3d8f4e-7c1a-4d2b-9e5f-1a2b3c4d5e6f"),
 *     @OA\Property(property="title", type="string", example="The Future of Artificial Intelligence"),
 *     @OA\Property(property="topic_date", type="string", format="date", example="2026-11-10"),
 *     @OA\Property(property="content", type="string", example="Exploring how AI is transforming industries..."),
 *     @OA\Property(property="topic_picture", type="string", example="/images/topics/ai-future.jpg"),
 *     @OA\Property(property="order", type="integer", example=1)
 * )
 * 
 * @OA\Tag(
 *     name="Events",
 *     description="Event management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Topics",
 *     description="Topic management endpoints - one topic can have multiple speakers"
 * )
 * 
 * @OA\Tag(
 *     name="Event Content",
 *     description="Event content management endpoints (summaries, themes, speakers, etc.)"
 * )
 */
abstract class Controller
{
    //
}
