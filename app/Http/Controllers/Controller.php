<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Event Management API",
 *     version="1.0.0",
 *     description="API documentation for the Event Management System. This system allows managing yearly events with all their associated content including summaries, themes, programmes, speakers, sponsors, FAQs, media, galleries, and attendances.",
 *     @OA\Contact(
 *         email="admin@example.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Development Server"
 * )
 * 
 * @OA\Server(
 *     url="http://localhost",
 *     description="Production Server"
 * )
 * 
 * @OA\Schema(
 *     schema="Event",
 *     type="object",
 *     title="Event",
 *     description="Event model",
 *     @OA\Property(property="id", type="integer", example=1),
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
 * @OA\Tag(
 *     name="Events",
 *     description="Event management endpoints"
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
