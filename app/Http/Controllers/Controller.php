<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

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
 *         url="http://eduevent.e-saloon.online",
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
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="summaries",
 *         type="array",
 *         description="Event summaries (use the UI title **Event summaries**). Included when the event is loaded with relations (e.g. GET /api/events/{id}).",
 *         @OA\Items(ref="#/components/schemas/EventSummary")
 *     ),
 *     @OA\Property(
 *         property="programmes",
 *         type="array",
 *         description="Event programmes (schedule items). Included when the event is loaded with relations.",
 *         @OA\Items(ref="#/components/schemas/EventProgramme")
 *     ),
 *     @OA\Property(
 *         property="speakers",
 *         type="array",
 *         description="Speakers for this event. Included when the event is loaded with relations.",
 *         @OA\Items(ref="#/components/schemas/Speaker")
 *     ),
 *     @OA\Property(
 *         property="sponsors",
 *         type="array",
 *         description="Sponsors for this event. Included when the event is loaded with relations.",
 *         @OA\Items(ref="#/components/schemas/Sponsor")
 *     ),
 *     @OA\Property(
 *         property="themes",
 *         type="array",
 *         description="Event themes (use the UI title **Event themes**). Included when the event is loaded with relations (e.g. GET /api/events/{id}).",
 *         @OA\Items(ref="#/components/schemas/EventTheme")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="EventSummary",
 *     type="object",
 *     title="Event summaries",
 *     description="One summary row. Lists use the UI title **Event summaries**; payload field is `summary` (text).",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="event_id", type="string", format="uuid"),
 *     @OA\Property(property="summary", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="EventTheme",
 *     type="object",
 *     title="Event themes",
 *     description="One theme row. Lists on the event use the UI title **Event themes**.",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="event_id", type="string", format="uuid"),
 *     @OA\Property(property="theme", type="string", example="Innovation"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Focus on emerging technology"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="EventResourceRecord",
 *     type="object",
 *     title="Event Resources",
 *     description="One row from the event_resources table.",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="event_id", type="string", format="uuid"),
 *     @OA\Property(property="title", type="string", example="Event Program Guide"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="file_path", type="string", nullable=true, example="/resources/2026/guide.pdf"),
 *     @OA\Property(property="file_type", type="string", nullable=true, example="PDF"),
 *     @OA\Property(property="url", type="string", nullable=true, description="External link when no file_path"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="EventMedia",
 *     type="object",
 *     title="Media",
 *     description="Event media item as returned by GET/POST/PUT /api/media (id plus title, file_path, description only).",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="title", type="string", nullable=true),
 *     @OA\Property(property="file_path", type="string", example="/media/2026/promo.mp4"),
 *     @OA\Property(property="description", type="string", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="EventProgramme",
 *     type="object",
 *     title="Event programmes",
 *     description="One programme (schedule) row for an event.",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="event_id", type="string", format="uuid"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="start_time", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="end_time", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="location", type="string", nullable=true),
 *     @OA\Property(property="speaker", type="string", nullable=true),
 *     @OA\Property(property="event_pdf", type="string", format="uri", nullable=true, description="Google Drive link to the programme PDF"),
 *     @OA\Property(property="order", type="integer", nullable=true),
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
 *     schema="Speaker",
 *     type="object",
 *     title="Speakers",
 *     description="Event speaker",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="event_id", type="string", format="uuid"),
 *     @OA\Property(property="topic_id", type="string", format="uuid", nullable=true),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="title", type="string", nullable=true),
 *     @OA\Property(property="organization", type="string", nullable=true),
 *     @OA\Property(property="bio", type="string", nullable=true),
 *     @OA\Property(property="photo", type="string", nullable=true),
 *     @OA\Property(property="email", type="string", nullable=true),
 *     @OA\Property(property="linkedin", type="string", nullable=true),
 *     @OA\Property(property="twitter", type="string", nullable=true),
 *     @OA\Property(property="order", type="integer", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Sponsor",
 *     type="object",
 *     title="Sponsors",
 *     description="Event sponsor",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="event_id", type="string", format="uuid"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="tier", type="string", nullable=true),
 *     @OA\Property(property="logo", type="string", nullable=true),
 *     @OA\Property(property="website", type="string", nullable=true),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="order", type="integer", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
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
 *     name="FAQ",
 *     description="Event FAQs: list by event UUID, create, update, delete by id"
 * )
 *
 * @OA\Tag(
 *     name="Gallery",
 *     description="Event gallery images: list by event UUID, upload, update, delete by id"
 * )
 *
 * @OA\Tag(
 *     name="Media",
 *     description="Event media (videos, documents, images): list by event UUID; create and update; responses expose id, title, file_path, description"
 * )
 *
 * @OA\Tag(
 *     name="Attendances",
 *     description="Event attendance registrations: list by event UUID, register (upsert), get, update, delete by id"
 * )
 *
 * @OA\Tag(
 *     name="Event Resources",
 *     description="Event resource files and links (event_resources): list by event_id or year; create, read, update, delete by id"
 * )
 *
 * @OA\Tag(
 *     name="Sponsors",
 *     description="Event sponsors: list and create by event id, update and delete by sponsor id"
 * )
 *
 * @OA\Tag(
 *     name="Speakers",
 *     description="Event speakers: list and create by event id, update and delete by speaker id"
 * )
 *
 * @OA\Tag(
 *     name="Event programmes",
 *     description="Schedule items: list and create by event id, update and delete by programme id"
 * )
 *
 * @OA\Tag(
 *     name="Event summaries",
 *     description="Event summary text (list by event, update by id)"
 * )
 *
 * @OA\Tag(
 *     name="Event themes",
 *     description="Event themes and descriptions (list, create, update, delete)"
 * )
 *
 * @OA\PathItem(
 *     path="/api/events"
 * )
 */
abstract class Controller
{
    //
}
