# Educational Database API - Quick Start Guide

## ✅ Setup Complete!

Your Educational Database Management System is now fully configured and running!

## 🎉 What Has Been Created

### 1. Database Structure
- **6 tables** with complete relationships:
  - `departments` - Academic departments
  - `teachers` - Faculty information
  - `students` - Student records
  - `courses` - Course catalog
  - `enrollments` - Course registrations
  - `grades` - Assignment and exam grades

### 2. RESTful API
- **42 API endpoints** for full CRUD operations
- Comprehensive data validation
- Relationship-based queries
- Filter and search capabilities

### 3. Swagger Documentation
- Interactive API documentation
- Test endpoints directly from browser
- Complete request/response schemas
- Parameter descriptions

### 4. Eloquent Models
- 6 models with full relationships
- Automatic data casting
- Mass assignment protection
- Timestamps management

## 🚀 Accessing Your Application

### Swagger API Documentation (Interactive UI)
```
http://localhost:8000/api-docs
```

**This is your main interface to:**
- Browse all API endpoints
- See request/response formats
- Test API calls interactively
- View parameter requirements

### API Base URL
```
http://localhost:8000/api
```

### Example Endpoints

#### View All Departments
```
GET http://localhost:8000/api/departments
```

#### View All Courses
```
GET http://localhost:8000/api/courses
```

#### View All Students
```
GET http://localhost:8000/api/students
```

## 📋 Quick Test Examples

### 1. Create a Department
Using any HTTP client (Postman, cURL, or Swagger UI):

```bash
POST http://localhost:8000/api/departments
Content-Type: application/json

{
    "name": "Computer Science",
    "code": "CS",
    "description": "Department of Computer Science",
    "head": "Dr. John Smith",
    "email": "cs@university.edu",
    "phone": "+1234567890"
}
```

### 2. Create a Teacher
```bash
POST http://localhost:8000/api/teachers
Content-Type: application/json

{
    "name": "Dr. Jane Doe",
    "email": "jane.doe@university.edu",
    "password": "password123",
    "department_id": 1,
    "employee_id": "EMP001",
    "hire_date": "2020-09-01",
    "specialization": "Artificial Intelligence"
}
```

### 3. Create a Student
```bash
POST http://localhost:8000/api/students
Content-Type: application/json

{
    "name": "Alice Johnson",
    "email": "alice@example.com",
    "password": "password123",
    "student_id": "STU001",
    "date_of_birth": "2002-05-15",
    "gender": "female",
    "guardian_name": "Bob Johnson",
    "guardian_phone": "+1234567890",
    "admission_date": "2024-09-01"
}
```

## 🗄️ Database Information

Your database configuration (from `.env`):

```
Database: edu_database
Host: 127.0.0.1
Port: 3306
Username: root
Password: (empty)
```

## 📖 Complete Documentation

For detailed information about all endpoints and features, see:
- `API_DOCUMENTATION.md` - Complete API reference
- Swagger UI at `http://localhost:8000/api-docs` - Interactive documentation

## 🎯 Next Steps

1. **Explore Swagger UI** - Visit http://localhost:8000/api-docs
2. **Create some data** - Use POST endpoints to create departments, teachers, students
3. **Test relationships** - Create courses and enrollments to see how data connects
4. **Try filters** - Use query parameters to filter data (e.g., `?department_id=1`)

## 🛠️ Common Commands

### Start the server
```bash
php artisan serve
```

### View all routes
```bash
php artisan route:list
```

### Check database status
```bash
php artisan migrate:status
```

### Rollback and refresh database
```bash
php artisan migrate:refresh
```

## 📊 Database Schema Overview

```
Users (authentication)
  └─ Teachers (1:1)
  └─ Students (1:1)

Departments
  ├─ Teachers (1:many)
  └─ Courses (1:many)

Teachers
  └─ Courses (1:many)

Courses
  └─ Enrollments (1:many)

Students
  └─ Enrollments (1:many)

Enrollments
  └─ Grades (1:many)
```

## 🎓 Features Implemented

✅ Complete CRUD operations for all entities  
✅ Relationship-based queries  
✅ Data validation and error handling  
✅ Swagger/OpenAPI documentation  
✅ Interactive API testing interface  
✅ Query parameter filtering  
✅ Foreign key constraints  
✅ Timestamps on all records  

## 🔧 Technology Stack

- **Framework**: Laravel 12
- **Database**: MySQL
- **API Documentation**: L5-Swagger (OpenAPI)
- **ORM**: Eloquent
- **Authentication**: Built-in Laravel Auth (ready for Sanctum)

## 📱 Testing the API

**Option 1: Swagger UI (Recommended)**
- Visit http://localhost:8000/api-docs
- Click "Try it out" on any endpoint
- Fill in parameters
- Click "Execute"

**Option 2: Postman/Insomnia**
- Import endpoints from http://localhost:8000/api-docs.json

**Option 3: cURL**
```bash
curl http://localhost:8000/api/departments
```

## 🎉 You're All Set!

Your Educational Database API is ready to use. Start by visiting the Swagger documentation to explore all available endpoints!

**Swagger UI**: http://localhost:8000/api-docs
