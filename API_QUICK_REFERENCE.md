# Event Management API - Quick Reference

Base URL: `http://localhost:8000/api`

## 🚀 Events Endpoints

### List All Events
```http
GET /events
GET /events?published_only=true
```

### Get Current Year's Event
```http
GET /events/current
```

### Get Event by Year
```http
GET /events/year/2026
```

### Get Event by ID (with all related data)
```http
GET /events/1
```

### Create Event
```http
POST /events
Content-Type: application/json

{
  "year": 2026,
  "title": "Annual Tech Conference 2026",
  "location": "Convention Center",
  "start_date": "2026-06-01",
  "end_date": "2026-06-03",
  "is_published": true
}
```

### Update Event
```http
PUT /events/1
Content-Type: application/json

{
  "title": "Updated Title",
  "is_published": false
}
```

### Delete Event
```http
DELETE /events/1
```

## 📝 Event Content Endpoints

### Add Summary
```http
POST /events/{eventId}/summaries
Content-Type: application/json

{
  "summary": "This is the event description..."
}
```

### Add Theme
```http
POST /events/{eventId}/themes
Content-Type: application/json

{
  "theme": "Innovation & Technology",
  "description": "Exploring future trends"
}
```

### Add Programme Item
```http
POST /events/{eventId}/programmes
Content-Type: application/json

{
  "title": "Opening Keynote",
  "description": "Welcome address",
  "start_time": "2026-06-01 09:00:00",
  "end_time": "2026-06-01 10:00:00",
  "location": "Main Hall",
  "speaker": "Dr. John Doe",
  "order": 1
}
```

### Add Speaker
```http
POST /events/{eventId}/speakers
Content-Type: application/json

{
  "name": "Dr. Jane Smith",
  "title": "Chief Technology Officer",
  "organization": "Tech Corp",
  "bio": "Expert in AI and ML",
  "photo": "/images/speakers/jane-smith.jpg",
  "email": "jane@example.com",
  "linkedin": "https://linkedin.com/in/janesmith",
  "twitter": "@janesmith",
  "order": 1
}
```

### Add Sponsor
```http
POST /events/{eventId}/sponsors
Content-Type: application/json

{
  "name": "Tech Corporation",
  "tier": "Gold",
  "logo": "/images/sponsors/techcorp.png",
  "website": "https://techcorp.com",
  "description": "Leading tech provider",
  "order": 1
}
```

### Add FAQ
```http
POST /events/{eventId}/faqs
Content-Type: application/json

{
  "question": "What time does registration start?",
  "answer": "Registration opens at 8:00 AM on day 1",
  "order": 1
}
```

## ✏️ Update Content

### Update Programme
```http
PUT /programmes/1
Content-Type: application/json

{
  "title": "Updated Title",
  "order": 2
}
```

### Update Speaker
```http
PUT /speakers/1
Content-Type: application/json

{
  "bio": "Updated biography",
  "order": 3
}
```

## 🗑️ Delete Content

```http
DELETE /summaries/1
DELETE /themes/1
DELETE /programmes/1
DELETE /speakers/1
DELETE /sponsors/1
DELETE /faqs/1
```

## 📊 Response Format

### Success Response
```json
{
  "success": true,
  "data": { ... },
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

## 🧪 Testing with cURL

### Create Event
```bash
curl -X POST http://localhost:8000/api/events \
  -H "Content-Type: application/json" \
  -d '{"year":2026,"title":"Tech Summit 2026","is_published":true}'
```

### Get All Events
```bash
curl http://localhost:8000/api/events
```

### Add Speaker
```bash
curl -X POST http://localhost:8000/api/events/1/speakers \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","title":"CEO","organization":"Tech Inc"}'
```

## 🔍 Field Validation

### Event Fields
- `year` - required, integer, unique
- `title` - required, string, max 255
- `location` - optional, string, max 255
- `start_date` - optional, date
- `end_date` - optional, date, after_or_equal:start_date
- `is_published` - boolean

### Speaker Fields
- `name` - required, string, max 255
- `title` - optional, string, max 255
- `organization` - optional, string, max 255
- `bio` - optional, text
- `photo` - optional, string (path)
- `email` - optional, email
- `order` - optional, integer

### Programme Fields
- `title` - required, string, max 255
- `description` - optional, text
- `start_time` - optional, datetime
- `end_time` - optional, datetime, after_or_equal:start_time
- `location` - optional, string, max 255
- `speaker` - optional, string, max 255
- `order` - optional, integer

## 💡 Tips

1. **Ordering**: Use the `order` field to control display sequence
2. **Timestamps**: All records have `created_at` and `updated_at`
3. **Cascade Delete**: Deleting an event removes all related content
4. **Relationships**: Use `GET /events/{id}` to get full event with all content
5. **Current Event**: Use `/events/current` for homepage display
6. **Historical**: Use `/events/year/{year}` for past events archive

## 🎯 Common Workflows

### Setup New Event
1. POST /events (create event)
2. POST /events/{id}/summaries (add description)
3. POST /events/{id}/themes (add themes)
4. POST /events/{id}/programmes (add agenda items)
5. POST /events/{id}/speakers (add speakers)
6. POST /events/{id}/sponsors (add sponsors)
7. POST /events/{id}/faqs (add FAQs)

### Display Event on Website
1. GET /events/current (get current year)
2. Use returned data to display all event information

### Archive/History Page
1. GET /events (get all events)
2. Display as list with links
3. GET /events/year/{year} (when user clicks)
