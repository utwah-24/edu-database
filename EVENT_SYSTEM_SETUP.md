# Event Management System - Setup Guide

## ✅ What Has Been Completed

### 1. Database Migrations
Created comprehensive migrations for the Event Management System:
- `events` - Main events table with year, title, location, dates
- `event_summaries` - Event summary content
- `event_themes` - Event themes and descriptions
- `event_programmes` - Event programme/schedule items
- `event_resources` - Event resources (files, links)
- `speakers` - Speaker information
- `faqs` - Frequently asked questions
- `media` - Media files (images, videos)
- `sponsors` - Event sponsors
- `galleries` - Image gallery
- `attendances` - Attendance tracking

### 2. Models with Relationships
All models have been created with proper:
- Fillable fields
- Eloquent relationships (hasMany, belongsTo)
- Type casting for dates and booleans
- Ordering for related content

### 3. API Controllers
- **EventController**: Full CRUD operations for events
  - List all events
  - Get event by ID
  - Get event by year
  - Get current year event
  - Create, update, delete events
  
- **EventContentController**: Manage event-related content
  - Add/remove summaries, themes
  - Add/update/delete programmes
  - Add/update/delete speakers
  - Add/delete sponsors, FAQs

### 4. API Routes
Created `/routes/api.php` with all endpoints:
- Public endpoints for viewing events
- Admin endpoints for managing content
- Nested routes for event content

### 5. Swagger/OpenAPI Setup
- Installed `darkaonline/l5-swagger` package
- Published Swagger configuration
- Added base OpenAPI annotations
- Configured Swagger UI

## 🚀 Next Steps to Complete Setup

### Step 1: Start MySQL Database Server

**The database server is currently not running.** You need to start MySQL first:

**Option A - Using XAMPP:**
1. Open XAMPP Control Panel
2. Click "Start" next to MySQL
3. Wait for it to show "Running" status

**Option B - Using MySQL Service:**
```bash
# Windows Command Prompt (Run as Administrator)
net start MySQL80
```

**Option C - Using WAMP/MAMP:**
- Start the MySQL service from your local server control panel

### Step 2: Run Database Migrations

Once MySQL is running, execute:

```bash
php artisan migrate:fresh
```

This will create all the event management tables in your `edu_database` database.

### Step 3: Generate Swagger Documentation

Generate the API documentation:

```bash
php artisan l5-swagger:generate
```

### Step 4: Start Laravel Development Server

```bash
php artisan serve
```

The server will start at `http://localhost:8000`

### Step 5: Access Swagger UI

Open your browser and visit:

```
http://localhost:8000/api/documentation
```

This will display the interactive Swagger UI where you can:
- View all API endpoints
- Test API requests directly
- See request/response examples
- View data models and schemas

## 📚 API Endpoints Overview

### Events
- `GET /api/events` - List all events
- `GET /api/events/current` - Get current year event
- `GET /api/events/year/{year}` - Get event by year
- `GET /api/events/{id}` - Get event with all related data
- `POST /api/events` - Create new event
- `PUT /api/events/{id}` - Update event
- `DELETE /api/events/{id}` - Delete event

### Event Content
- `POST /api/events/{eventId}/summaries` - Add summary
- `POST /api/events/{eventId}/themes` - Add theme
- `POST /api/events/{eventId}/programmes` - Add programme
- `POST /api/events/{eventId}/speakers` - Add speaker
- `POST /api/events/{eventId}/sponsors` - Add sponsor
- `POST /api/events/{eventId}/faqs` - Add FAQ

### Content Management
- `PUT /api/programmes/{id}` - Update programme
- `PUT /api/speakers/{id}` - Update speaker
- `DELETE /api/summaries/{id}` - Delete summary
- `DELETE /api/themes/{id}` - Delete theme
- `DELETE /api/programmes/{id}` - Delete programme
- `DELETE /api/speakers/{id}` - Delete speaker
- `DELETE /api/sponsors/{id}` - Delete sponsor
- `DELETE /api/faqs/{id}` - Delete FAQ

## 🔧 Configuration

### Database Configuration (.env)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edu_database
DB_USERNAME=root
DB_PASSWORD=
```

### Swagger Configuration
```env
SWAGGER_GENERATE_ALWAYS=true
```

## 📝 Testing the API

### Example: Create an Event

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

### Example: Get Current Event

```bash
curl http://localhost:8000/api/events/current
```

### Example: Add a Speaker

```bash
curl -X POST http://localhost:8000/api/events/1/speakers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "title": "Senior Developer",
    "organization": "Tech Corp",
    "bio": "Expert in software development",
    "email": "john@example.com"
  }'
```

## 🎯 Features Implemented

1. **Yearly Event Management**: Each year gets its own event with complete isolation
2. **Historical Data**: Previous years' events are preserved and accessible
3. **Rich Content Support**: Summaries, themes, programmes, speakers, sponsors, etc.
4. **Media Management**: Support for images, videos, and documents
5. **Attendance Tracking**: Track event registrations and check-ins
6. **Flexible Ordering**: Content items can be ordered for display
7. **Comprehensive API**: Full CRUD operations with validation
8. **API Documentation**: Interactive Swagger UI for testing and documentation

## 🔐 Security Notes

Currently, all routes are public. For production, you should:
1. Add authentication middleware to admin routes
2. Implement Laravel Sanctum or Passport for API authentication
3. Add authorization policies for content management
4. Validate file uploads for media/resources
5. Implement rate limiting

## 📂 Project Structure

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           ├── EventController.php
│           └── EventContentController.php
├── Models/
│   ├── Event.php
│   ├── EventSummary.php
│   ├── EventTheme.php
│   ├── EventProgramme.php
│   ├── EventResource.php
│   ├── Speaker.php
│   ├── FAQ.php
│   ├── Media.php
│   ├── Sponsor.php
│   ├── Gallery.php
│   └── Attendance.php
database/
└── migrations/
    └── 2026_02_16_*.php (11 migration files)
routes/
└── api.php
config/
└── l5-swagger.php
```

## ❓ Troubleshooting

### Database Connection Error
- Ensure MySQL is running
- Verify credentials in `.env` file
- Check if `edu_database` exists (create it if needed)

### Swagger Not Generating
- Clear config cache: `php artisan config:clear`
- Regenerate documentation: `php artisan l5-swagger:generate`
- Check annotations syntax in controllers

### Routes Not Working
- Clear route cache: `php artisan route:clear`
- Verify API routes are registered in `bootstrap/app.php`
- Check `.htaccess` if using Apache

## 📖 Additional Resources

- Laravel Documentation: https://laravel.com/docs
- Swagger/OpenAPI Specification: https://swagger.io/specification/
- L5-Swagger Package: https://github.com/DarkaOnLine/L5-Swagger

---

**System Status**: Ready for database migration and testing!
**Next Action**: Start MySQL server and run `php artisan migrate:fresh`
