# Event Management API — Full Documentation

**Version:** 1.0.0  
**Base URL:** `http://localhost:8000/api`  
**Interactive Docs:** `http://127.0.0.1:8000/api/documentation` (Swagger UI)

---

## Table of Contents

1. [Overview](#overview)
2. [Data Models](#data-models)
3. [Response Format](#response-format)
4. [HTTP Status Codes](#http-status-codes)
5. [Events](#events)
6. [Event Content](#event-content)
   - [Summaries](#summaries)
   - [Themes](#themes)
   - [Programmes](#programmes)
   - [Speakers](#speakers)
   - [Sponsors](#sponsors)
   - [FAQs](#faqs)
7. [Topics](#topics)
8. [Field Validation Reference](#field-validation-reference)
9. [Common Workflows](#common-workflows)
10. [cURL Examples](#curl-examples)

---

## Overview

This API powers an **Event Management System** that manages yearly events and all their associated content. Each event can have:

- **Summaries** — descriptive text about the event
- **Themes** — thematic focus areas
- **Programmes** — agenda / schedule items
- **Speakers** — presenter profiles
- **Sponsors** — sponsoring organisations
- **FAQs** — frequently asked questions
- **Topics** — discussion topics (each topic can have multiple speakers)
- **Media**, **Galleries**, **Resources**, **Attendances** — additional event data

All IDs are **UUIDs** (e.g. `9b3d8f4e-7c1a-4d2b-9e5f-1a2b3c4d5e6f`).

---

## Data Models

### Event

| Field          | Type      | Required | Description                              |
|----------------|-----------|----------|------------------------------------------|
| `id`           | UUID      | auto     | Unique identifier                        |
| `year`         | integer   | yes      | Event year — must be unique              |
| `title`        | string    | yes      | Event title (max 255 chars)              |
| `location`     | string    | no       | Venue / location (max 255 chars)         |
| `start_date`   | date      | no       | Start date (`YYYY-MM-DD`)                |
| `end_date`     | date      | no       | End date — must be ≥ `start_date`        |
| `is_published` | boolean   | no       | Whether the event is publicly visible    |
| `created_at`   | datetime  | auto     | Record creation timestamp                |
| `updated_at`   | datetime  | auto     | Last update timestamp                    |

### Topic

| Field           | Type     | Required | Description                              |
|-----------------|----------|----------|------------------------------------------|
| `id`            | UUID     | auto     | Unique identifier                        |
| `event_id`      | UUID     | yes      | Parent event UUID                        |
| `title`         | string   | yes      | Topic title (max 255 chars)              |
| `topic_date`    | date     | yes      | Date of the topic (`YYYY-MM-DD`)         |
| `content`       | string   | no       | Full content / description               |
| `topic_picture` | string   | no       | Path to topic image                      |
| `order`         | integer  | no       | Display order                            |
| `created_at`    | datetime | auto     | Record creation timestamp                |
| `updated_at`    | datetime | auto     | Last update timestamp                    |

### Speaker

| Field          | Type     | Required | Description                              |
|----------------|----------|----------|------------------------------------------|
| `id`           | UUID     | auto     | Unique identifier                        |
| `event_id`     | UUID     | yes      | Parent event UUID                        |
| `name`         | string   | yes      | Full name (max 255 chars)                |
| `title`        | string   | no       | Job title (max 255 chars)                |
| `organization` | string   | no       | Organisation / company (max 255 chars)   |
| `bio`          | text     | no       | Biography                                |
| `photo`        | string   | no       | Path to photo                            |
| `email`        | string   | no       | Email address (must be valid format)     |
| `linkedin`     | string   | no       | LinkedIn URL or handle                   |
| `twitter`      | string   | no       | Twitter handle                           |
| `order`        | integer  | no       | Display order                            |

### Programme (Agenda Item)

| Field         | Type     | Required | Description                              |
|---------------|----------|----------|------------------------------------------|
| `id`          | UUID     | auto     | Unique identifier                        |
| `event_id`    | UUID     | yes      | Parent event UUID                        |
| `title`       | string   | yes      | Session title (max 255 chars)            |
| `description` | text     | no       | Session description                      |
| `start_time`  | datetime | no       | Session start (`YYYY-MM-DD HH:MM:SS`)    |
| `end_time`    | datetime | no       | Session end — must be ≥ `start_time`     |
| `location`    | string   | no       | Room / venue (max 255 chars)             |
| `speaker`     | string   | no       | Speaker name (plain text, max 255)       |
| `order`       | integer  | no       | Display order                            |

### Sponsor

| Field         | Type    | Required | Description                              |
|---------------|---------|----------|------------------------------------------|
| `id`          | UUID    | auto     | Unique identifier                        |
| `event_id`    | UUID    | yes      | Parent event UUID                        |
| `name`        | string  | yes      | Sponsor name (max 255 chars)             |
| `tier`        | string  | no       | Sponsorship tier (e.g. Gold, Silver)     |
| `logo`        | string  | no       | Path to logo image                       |
| `website`     | string  | no       | Website URL                              |
| `description` | text    | no       | Description                              |
| `order`       | integer | no       | Display order                            |

### FAQ

| Field      | Type    | Required | Description         |
|------------|---------|----------|---------------------|
| `id`       | UUID    | auto     | Unique identifier   |
| `event_id` | UUID    | yes      | Parent event UUID   |
| `question` | text    | yes      | The question        |
| `answer`   | text    | yes      | The answer          |
| `order`    | integer | no       | Display order       |

### Event Summary

| Field      | Type | Required | Description         |
|------------|------|----------|---------------------|
| `id`       | UUID | auto     | Unique identifier   |
| `event_id` | UUID | yes      | Parent event UUID   |
| `summary`  | text | yes      | Summary text        |

### Event Theme

| Field         | Type   | Required | Description                   |
|---------------|--------|----------|-------------------------------|
| `id`          | UUID   | auto     | Unique identifier             |
| `event_id`    | UUID   | yes      | Parent event UUID             |
| `theme`       | string | yes      | Theme name (max 255 chars)    |
| `description` | text   | no       | Theme description             |

---

## Response Format

All endpoints return JSON in the following structure.

### Success

```json
{
  "success": true,
  "data": { ... },
  "message": "Operation successful"
}
```

> Note: `message` is only present on write operations (create, update, delete). List and single-item reads return only `success` and `data`.

### Error

```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

> Note: `errors` is only present for validation failures (422).

---

## HTTP Status Codes

| Code | Meaning                                   |
|------|-------------------------------------------|
| 200  | OK — request succeeded                    |
| 201  | Created — resource created successfully   |
| 404  | Not Found — resource does not exist       |
| 422  | Unprocessable Entity — validation failed  |
| 500  | Internal Server Error                     |

---

## Events

### List All Events

Returns all events, ordered by year descending.

```
GET /api/events
```

**Query Parameters**

| Parameter        | Type    | Required | Description                     |
|------------------|---------|----------|---------------------------------|
| `published_only` | boolean | no       | If `true`, returns only published events |

**Example Request**
```http
GET /api/events?published_only=true
```

**Example Response**
```json
{
  "success": true,
  "data": [
    {
      "id": "9b3d8f4e-7c1a-4d2b-9e5f-1a2b3c4d5e6f",
      "year": 2026,
      "title": "Annual Tech Conference 2026",
      "location": "Convention Center",
      "start_date": "2026-06-01",
      "end_date": "2026-06-03",
      "is_published": true,
      "created_at": "2026-01-10T08:00:00.000000Z",
      "updated_at": "2026-01-10T08:00:00.000000Z"
    }
  ]
}
```

---

### Get Current Year's Event

Returns the current year's event (must be published). Includes all related content.

```
GET /api/events/current
```

**Includes:** summaries, themes, programmes, resources, speakers, FAQs, media, sponsors, galleries, attendances

**Example Response**
```json
{
  "success": true,
  "data": {
    "id": "9b3d8f4e-7c1a-4d2b-9e5f-1a2b3c4d5e6f",
    "year": 2026,
    "title": "Annual Tech Conference 2026",
    "is_published": true,
    "summaries": [...],
    "themes": [...],
    "programmes": [...],
    "speakers": [...],
    "sponsors": [...],
    "faqs": [...],
    "media": [...],
    "galleries": [...],
    "resources": [...],
    "attendances": [...]
  }
}
```

**Error (404)**
```json
{
  "success": false,
  "message": "No current event found"
}
```

---

### Get Event by Year

Returns an event for a specific year with all related content.

```
GET /api/events/year/{year}
```

**Path Parameters**

| Parameter | Type    | Description |
|-----------|---------|-------------|
| `year`    | integer | The year to look up (e.g. `2025`) |

**Example Request**
```http
GET /api/events/year/2025
```

**Error (404)**
```json
{
  "success": false,
  "message": "Event not found for year 2025"
}
```

---

### Get Event by ID

Returns a single event by its UUID, with all related content.

```
GET /api/events/{id}
```

**Path Parameters**

| Parameter | Type | Description     |
|-----------|------|-----------------|
| `id`      | UUID | The event UUID  |

**Includes:** summaries, themes, programmes, resources, speakers, FAQs, media, sponsors, galleries, attendances

---

### Create Event

Creates a new event.

```
POST /api/events
```

**Request Body**

```json
{
  "year": 2026,
  "title": "Annual Tech Conference 2026",
  "location": "Convention Center",
  "start_date": "2026-06-01",
  "end_date": "2026-06-03",
  "is_published": true
}
```

| Field          | Type    | Required | Rules                              |
|----------------|---------|----------|------------------------------------|
| `year`         | integer | yes      | Must be unique across all events   |
| `title`        | string  | yes      | Max 255 characters                 |
| `location`     | string  | no       | Max 255 characters                 |
| `start_date`   | date    | no       | Format: `YYYY-MM-DD`               |
| `end_date`     | date    | no       | Must be ≥ `start_date`             |
| `is_published` | boolean | no       | Defaults to `false`                |

**Response (201)**
```json
{
  "success": true,
  "message": "Event created successfully",
  "data": { ... }
}
```

---

### Update Event

Updates an existing event. All fields are optional — only send what you want to change.

```
PUT /api/events/{id}
```

**Path Parameters**

| Parameter | Type | Description    |
|-----------|------|----------------|
| `id`      | UUID | The event UUID |

**Request Body** (all fields optional)

```json
{
  "title": "Updated Conference Title",
  "is_published": false
}
```

**Response (200)**
```json
{
  "success": true,
  "message": "Event updated successfully",
  "data": { ... }
}
```

---

### Delete Event

Deletes an event and all its associated content (cascade delete).

```
DELETE /api/events/{id}
```

**Response (200)**
```json
{
  "success": true,
  "message": "Event deleted successfully"
}
```

---

## Event Content

All content is attached to an event via its `eventId` UUID. The full event (with all content) can be retrieved via `GET /api/events/{id}`.

---

### Summaries

#### Add Summary

```
POST /api/events/{eventId}/summaries
```

**Request Body**

```json
{
  "summary": "This is the full event description and overview..."
}
```

| Field     | Type | Required | Rules    |
|-----------|------|----------|----------|
| `summary` | text | yes      | Any text |

**Response (201)**
```json
{
  "success": true,
  "data": {
    "id": "...",
    "event_id": "...",
    "summary": "This is the full event description...",
    "created_at": "...",
    "updated_at": "..."
  }
}
```

#### Delete Summary

```
DELETE /api/summaries/{id}
```

---

### Themes

#### Add Theme

```
POST /api/events/{eventId}/themes
```

**Request Body**

```json
{
  "theme": "Innovation & Technology",
  "description": "Exploring future trends in the tech industry"
}
```

| Field         | Type   | Required | Rules             |
|---------------|--------|----------|-------------------|
| `theme`       | string | yes      | Max 255 chars     |
| `description` | text   | no       | Any text          |

**Response (201)**
```json
{
  "success": true,
  "data": {
    "id": "...",
    "event_id": "...",
    "theme": "Innovation & Technology",
    "description": "Exploring future trends...",
    "created_at": "...",
    "updated_at": "..."
  }
}
```

#### Delete Theme

```
DELETE /api/themes/{id}
```

---

### Programmes

#### Add Programme Item

```
POST /api/events/{eventId}/programmes
```

**Request Body**

```json
{
  "title": "Opening Keynote",
  "description": "Welcome address from the conference director",
  "start_time": "2026-06-01 09:00:00",
  "end_time": "2026-06-01 10:00:00",
  "location": "Main Hall",
  "speaker": "Dr. John Doe",
  "order": 1
}
```

| Field         | Type     | Required | Rules                          |
|---------------|----------|----------|--------------------------------|
| `title`       | string   | yes      | Max 255 chars                  |
| `description` | text     | no       | Any text                       |
| `start_time`  | datetime | no       | Format: `YYYY-MM-DD HH:MM:SS`  |
| `end_time`    | datetime | no       | Must be ≥ `start_time`         |
| `location`    | string   | no       | Max 255 chars                  |
| `speaker`     | string   | no       | Plain text name, max 255 chars |
| `order`       | integer  | no       | Controls display sequence      |

#### Update Programme

```
PUT /api/programmes/{id}
```

Send only the fields to update. Same field rules as creation.

#### Delete Programme

```
DELETE /api/programmes/{id}
```

---

### Speakers

#### Add Speaker

```
POST /api/events/{eventId}/speakers
```

**Request Body**

```json
{
  "name": "Dr. Jane Smith",
  "title": "Chief Technology Officer",
  "organization": "Tech Corporation",
  "bio": "Dr. Smith is a leading expert in artificial intelligence...",
  "photo": "/images/speakers/jane-smith.jpg",
  "email": "jane@example.com",
  "linkedin": "https://linkedin.com/in/janesmith",
  "twitter": "@janesmith",
  "order": 1
}
```

| Field          | Type    | Required | Rules                     |
|----------------|---------|----------|---------------------------|
| `name`         | string  | yes      | Max 255 chars             |
| `title`        | string  | no       | Job title, max 255 chars  |
| `organization` | string  | no       | Max 255 chars             |
| `bio`          | text    | no       | Any text                  |
| `photo`        | string  | no       | File path or URL          |
| `email`        | string  | no       | Must be valid email format|
| `linkedin`     | string  | no       | URL or handle             |
| `twitter`      | string  | no       | Handle (e.g. `@handle`)   |
| `order`        | integer | no       | Controls display sequence |

#### Update Speaker

```
PUT /api/speakers/{id}
```

Send only the fields to update. Same field rules as creation.

#### Delete Speaker

```
DELETE /api/speakers/{id}
```

---

### Sponsors

#### Add Sponsor

```
POST /api/events/{eventId}/sponsors
```

**Request Body**

```json
{
  "name": "Tech Corporation",
  "tier": "Gold",
  "logo": "/images/sponsors/techcorp.png",
  "website": "https://techcorp.com",
  "description": "Leading technology provider",
  "order": 1
}
```

| Field         | Type    | Required | Rules                                    |
|---------------|---------|----------|------------------------------------------|
| `name`        | string  | yes      | Max 255 chars                            |
| `tier`        | string  | no       | e.g. `Platinum`, `Gold`, `Silver`, `Bronze` |
| `logo`        | string  | no       | File path or URL                         |
| `website`     | string  | no       | URL                                      |
| `description` | text    | no       | Any text                                 |
| `order`       | integer | no       | Controls display sequence                |

#### Delete Sponsor

```
DELETE /api/sponsors/{id}
```

---

### FAQs

#### Add FAQ

```
POST /api/events/{eventId}/faqs
```

**Request Body**

```json
{
  "question": "What time does registration start?",
  "answer": "Registration opens at 8:00 AM on the first day.",
  "order": 1
}
```

| Field      | Type    | Required | Rules                     |
|------------|---------|----------|---------------------------|
| `question` | text    | yes      | Any text                  |
| `answer`   | text    | yes      | Any text                  |
| `order`    | integer | no       | Controls display sequence |

#### Delete FAQ

```
DELETE /api/faqs/{id}
```

---

## Topics

Topics are discussion subjects tied to an event. Each topic can have multiple speakers linked to it.

### List All Topics

```
GET /api/topics
```

**Query Parameters**

| Parameter  | Type | Required | Description                     |
|------------|------|----------|---------------------------------|
| `event_id` | UUID | no       | Filter topics by event UUID     |

Results are ordered by `topic_date` ascending.

**Example Request**
```http
GET /api/topics?event_id=9b3d8f4e-7c1a-4d2b-9e5f-1a2b3c4d5e6f
```

**Example Response**
```json
{
  "success": true,
  "data": [
    {
      "id": "8a2c7b3d-6e4f-5c1a-2b9e-3f4a5b6c7d8e",
      "event_id": "9b3d8f4e-7c1a-4d2b-9e5f-1a2b3c4d5e6f",
      "title": "The Future of Artificial Intelligence",
      "topic_date": "2026-06-01",
      "content": "Exploring how AI is transforming industries...",
      "topic_picture": "/images/topics/ai-future.jpg",
      "order": 1,
      "speakers": [...],
      "event": {...}
    }
  ]
}
```

---

### Get Topic by ID

```
GET /api/topics/{id}
```

**Includes:** speakers, event

---

### Create Topic

```
POST /api/topics
```

**Request Body**

```json
{
  "event_id": "9b3d8f4e-7c1a-4d2b-9e5f-1a2b3c4d5e6f",
  "title": "The Future of Artificial Intelligence",
  "topic_date": "2026-06-01",
  "content": "Exploring how AI is transforming industries...",
  "topic_picture": "/images/topics/ai-future.jpg",
  "order": 1
}
```

| Field           | Type    | Required | Rules                              |
|-----------------|---------|----------|------------------------------------|
| `event_id`      | UUID    | yes      | Must exist in the events table     |
| `title`         | string  | yes      | Max 255 chars                      |
| `topic_date`    | date    | yes      | Format: `YYYY-MM-DD`               |
| `content`       | text    | no       | Any text                           |
| `topic_picture` | string  | no       | File path or URL                   |
| `order`         | integer | no       | Controls display sequence          |

**Response (201)**
```json
{
  "success": true,
  "message": "Topic created successfully",
  "data": {
    "id": "...",
    "event_id": "...",
    "title": "The Future of Artificial Intelligence",
    "topic_date": "2026-06-01",
    "speakers": [],
    "event": { ... }
  }
}
```

---

### Update Topic

```
PUT /api/topics/{id}
```

Send only the fields to update. Same field rules as creation (all optional except `event_id` and `topic_date` which are validated if present).

**Response (200)**
```json
{
  "success": true,
  "message": "Topic updated successfully",
  "data": { ... }
}
```

---

### Delete Topic

```
DELETE /api/topics/{id}
```

> Note: Deleting a topic sets `topic_id` to `null` on any linked speakers (they are not deleted).

**Response (200)**
```json
{
  "success": true,
  "message": "Topic deleted successfully"
}
```

---

## Field Validation Reference

### Event Fields

| Field          | Rule                                          |
|----------------|-----------------------------------------------|
| `year`         | Required, integer, unique in `events` table   |
| `title`        | Required, string, max 255                     |
| `location`     | Optional, string, max 255                     |
| `start_date`   | Optional, valid date                          |
| `end_date`     | Optional, valid date, must be ≥ `start_date`  |
| `is_published` | Optional, boolean                             |

### Speaker Fields

| Field          | Rule                              |
|----------------|-----------------------------------|
| `name`         | Required, string, max 255         |
| `title`        | Optional, string, max 255         |
| `organization` | Optional, string, max 255         |
| `bio`          | Optional, text                    |
| `photo`        | Optional, string (path or URL)    |
| `email`        | Optional, valid email format      |
| `linkedin`     | Optional, string                  |
| `twitter`      | Optional, string                  |
| `order`        | Optional, integer                 |

### Programme Fields

| Field         | Rule                                         |
|---------------|----------------------------------------------|
| `title`       | Required, string, max 255                    |
| `description` | Optional, text                               |
| `start_time`  | Optional, valid datetime                     |
| `end_time`    | Optional, valid datetime, must be ≥ `start_time` |
| `location`    | Optional, string, max 255                    |
| `speaker`     | Optional, string, max 255                    |
| `order`       | Optional, integer                            |

### Topic Fields

| Field           | Rule                                      |
|-----------------|-------------------------------------------|
| `event_id`      | Required, UUID, must exist in events      |
| `title`         | Required, string, max 255                 |
| `topic_date`    | Required, valid date                      |
| `content`       | Optional, text                            |
| `topic_picture` | Optional, string (path or URL)            |
| `order`         | Optional, integer                         |

---

## Common Workflows

### Set Up a New Event (Full)

```
1. POST /api/events                          → create the event, note the returned UUID
2. POST /api/events/{id}/summaries           → add event description
3. POST /api/events/{id}/themes              → add one or more themes
4. POST /api/events/{id}/programmes          → add agenda items (repeat for each)
5. POST /api/events/{id}/speakers            → add speakers (repeat for each)
6. POST /api/events/{id}/sponsors            → add sponsors (repeat for each)
7. POST /api/events/{id}/faqs                → add FAQs (repeat for each)
8. POST /api/topics                          → add topics linked to the event
9. PUT  /api/events/{id}  is_published=true  → publish the event
```

### Display Current Event on a Website

```
1. GET /api/events/current   → returns the current year's published event with all content
2. Use data.summaries        → event description
3. Use data.themes           → thematic areas
4. Use data.programmes       → schedule / agenda
5. Use data.speakers         → speaker profiles
6. Use data.sponsors         → sponsors
7. Use data.faqs             → FAQ section
```

### Archive / History Page

```
1. GET /api/events                      → returns all events (ordered newest first)
2. Display as list
3. GET /api/events/year/{year}          → when a user clicks on a past event
```

### Manage Topics for an Event

```
1. POST /api/topics                     → create topic linked to event
2. GET  /api/topics?event_id={id}       → list all topics for an event
3. GET  /api/topics/{topicId}           → get a specific topic with speakers
4. PUT  /api/topics/{topicId}           → update topic
5. DELETE /api/topics/{topicId}         → delete topic (speakers kept, unlinked)
```

---

## cURL Examples

### Create an Event

```bash
curl -X POST http://localhost:8000/api/events \
  -H "Content-Type: application/json" \
  -d '{
    "year": 2026,
    "title": "Annual Tech Conference 2026",
    "location": "Convention Center",
    "start_date": "2026-06-01",
    "end_date": "2026-06-03",
    "is_published": true
  }'
```

### Get All Events

```bash
curl http://localhost:8000/api/events
```

### Get Published Events Only

```bash
curl "http://localhost:8000/api/events?published_only=true"
```

### Get Current Year's Event

```bash
curl http://localhost:8000/api/events/current
```

### Get Event by Year

```bash
curl http://localhost:8000/api/events/year/2025
```

### Add a Speaker to an Event

```bash
curl -X POST http://localhost:8000/api/events/{eventId}/speakers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Dr. Jane Smith",
    "title": "Chief Technology Officer",
    "organization": "Tech Corp",
    "bio": "Expert in AI and machine learning.",
    "email": "jane@techcorp.com",
    "linkedin": "https://linkedin.com/in/janesmith",
    "order": 1
  }'
```

### Add a Programme Item

```bash
curl -X POST http://localhost:8000/api/events/{eventId}/programmes \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Opening Keynote",
    "description": "Welcome address",
    "start_time": "2026-06-01 09:00:00",
    "end_time": "2026-06-01 10:00:00",
    "location": "Main Hall",
    "speaker": "Dr. Jane Smith",
    "order": 1
  }'
```

### Create a Topic

```bash
curl -X POST http://localhost:8000/api/topics \
  -H "Content-Type: application/json" \
  -d '{
    "event_id": "{eventId}",
    "title": "The Future of Artificial Intelligence",
    "topic_date": "2026-06-01",
    "content": "Exploring how AI is transforming industries.",
    "order": 1
  }'
```

### Update an Event

```bash
curl -X PUT http://localhost:8000/api/events/{eventId} \
  -H "Content-Type: application/json" \
  -d '{"is_published": true}'
```

### Delete a Speaker

```bash
curl -X DELETE http://localhost:8000/api/speakers/{speakerId}
```

### Delete an Event (and all its content)

```bash
curl -X DELETE http://localhost:8000/api/events/{eventId}
```

---

*Generated from source: `app/Http/Controllers/Api/` and `routes/api.php`*
