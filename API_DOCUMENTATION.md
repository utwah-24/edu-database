# Educational Database API Documentation

## Overview

This is a comprehensive Educational Database Management System API built with Laravel 12. The system manages departments, teachers, students, courses, enrollments, and grades.

## Database Structure

### Tables Created

1. **departments** - Academic departments
2. **teachers** - Faculty members
3. **students** - Student information
4. **courses** - Course catalog
5. **enrollments** - Student course enrollments
6. **grades** - Individual assignment/exam grades

### Relationships

- Each **Department** has many **Teachers** and **Courses**
- Each **Teacher** belongs to a **Department** and teaches many **Courses**
- Each **Student** can enroll in many **Courses**
- Each **Course** belongs to a **Department** and is taught by a **Teacher**
- Each **Enrollment** connects a **Student** to a **Course** and has many **Grades**
- Each **Grade** belongs to an **Enrollment**

## API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Departments

- `GET /api/departments` - Get all departments
- `POST /api/departments` - Create a new department
- `GET /api/departments/{id}` - Get a specific department
- `PUT /api/departments/{id}` - Update a department
- `DELETE /api/departments/{id}` - Delete a department
- `GET /api/departments/{id}/teachers` - Get all teachers in a department
- `GET /api/departments/{id}/courses` - Get all courses in a department

### Teachers

- `GET /api/teachers` - Get all teachers
- `POST /api/teachers` - Create a new teacher
- `GET /api/teachers/{id}` - Get a specific teacher
- `PUT /api/teachers/{id}` - Update a teacher
- `DELETE /api/teachers/{id}` - Delete a teacher
- `GET /api/teachers/{id}/courses` - Get all courses taught by a teacher

### Students

- `GET /api/students` - Get all students
- `POST /api/students` - Create a new student
- `GET /api/students/{id}` - Get a specific student
- `PUT /api/students/{id}` - Update a student
- `DELETE /api/students/{id}` - Delete a student
- `GET /api/students/{id}/enrollments` - Get all enrollments for a student
- `GET /api/students/{id}/courses` - Get all courses for a student

### Courses

- `GET /api/courses` - Get all courses
- `POST /api/courses` - Create a new course
- `GET /api/courses/{id}` - Get a specific course
- `PUT /api/courses/{id}` - Update a course
- `DELETE /api/courses/{id}` - Delete a course
- `GET /api/courses/{id}/students` - Get all students enrolled in a course
- `GET /api/courses/{id}/enrollments` - Get all enrollments for a course

### Enrollments

- `GET /api/enrollments` - Get all enrollments
- `POST /api/enrollments` - Create a new enrollment
- `GET /api/enrollments/{id}` - Get a specific enrollment
- `PUT /api/enrollments/{id}` - Update an enrollment
- `DELETE /api/enrollments/{id}` - Delete an enrollment
- `GET /api/enrollments/{id}/grades` - Get all grades for an enrollment

### Grades

- `GET /api/grades` - Get all grades
- `POST /api/grades` - Create a new grade
- `GET /api/grades/{id}` - Get a specific grade
- `PUT /api/grades/{id}` - Update a grade
- `DELETE /api/grades/{id}` - Delete a grade

## Swagger UI Documentation

Access the interactive Swagger UI documentation at:

```
http://localhost:8000/api-docs
```

The Swagger UI provides a complete interactive interface to:
- View all API endpoints
- See request/response schemas
- Test API calls directly from the browser
- View detailed parameter descriptions

## Example API Requests

### Create a Department

```bash
POST /api/departments
Content-Type: application/json

{
    "name": "Computer Science",
    "description": "Department of Computer Science and Engineering",
    "code": "CS",
    "head": "Dr. John Doe",
    "email": "cs@university.edu",
    "phone": "+1234567890",
    "is_active": true
}
```

### Create a Teacher

```bash
POST /api/teachers
Content-Type: application/json

{
    "name": "Dr. Jane Smith",
    "email": "jane.smith@university.edu",
    "password": "password123",
    "department_id": 1,
    "employee_id": "EMP2024001",
    "phone": "+1234567890",
    "specialization": "Machine Learning",
    "hire_date": "2020-09-01",
    "employment_type": "full-time",
    "is_active": true
}
```

### Create a Student

```bash
POST /api/students
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "password123",
    "student_id": "STU2024001",
    "date_of_birth": "2000-01-15",
    "gender": "male",
    "phone": "+1234567890",
    "address": "123 Main St, City",
    "guardian_name": "Jane Doe",
    "guardian_phone": "+1234567891",
    "guardian_email": "jane.doe@example.com",
    "admission_date": "2024-09-01",
    "enrollment_status": "active"
}
```

### Create a Course

```bash
POST /api/courses
Content-Type: application/json

{
    "code": "CS101",
    "name": "Introduction to Computer Science",
    "description": "Basic concepts of computer science",
    "credits": 3,
    "department_id": 1,
    "teacher_id": 1,
    "semester": "Fall",
    "academic_year": "2025-2026",
    "max_students": 30,
    "level": "undergraduate",
    "room": "Room 101",
    "schedule": {
        "monday": "10:00-12:00",
        "wednesday": "10:00-12:00"
    },
    "is_active": true
}
```

### Create an Enrollment

```bash
POST /api/enrollments
Content-Type: application/json

{
    "student_id": 1,
    "course_id": 1,
    "enrollment_date": "2024-09-01",
    "status": "enrolled"
}
```

### Create a Grade

```bash
POST /api/grades
Content-Type: application/json

{
    "enrollment_id": 1,
    "assignment_name": "Midterm Exam",
    "assignment_type": "midterm",
    "grade": 85.5,
    "max_grade": 100,
    "weight": 30,
    "grade_date": "2024-10-15",
    "remarks": "Good performance"
}
```

## Query Parameters

### Filtering

Many endpoints support filtering via query parameters:

- **Departments**: `?is_active=true`
- **Teachers**: `?department_id=1&is_active=true`
- **Students**: `?enrollment_status=active`
- **Courses**: `?department_id=1&semester=Fall&academic_year=2025-2026`
- **Enrollments**: `?student_id=1&course_id=1&status=enrolled`
- **Grades**: `?enrollment_id=1&assignment_type=midterm`

## Database Configuration

The database configuration is stored in `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edu_database
DB_USERNAME=root
DB_PASSWORD=
```

## Running the Application

1. Start your MySQL server
2. Run migrations (if not already done):
   ```bash
   php artisan migrate
   ```

3. Start the Laravel development server:
   ```bash
   php artisan serve
   ```

4. Access the application:
   - Main application: http://localhost:8000
   - Swagger API Documentation: http://localhost:8000/api-docs
   - API endpoints: http://localhost:8000/api/*

## Features

### Data Validation

All endpoints include comprehensive validation:
- Required fields
- Unique constraints (emails, IDs, codes)
- Data type validation
- Foreign key relationships

### Relationships

The API returns related data automatically:
- Departments include their teachers and courses
- Teachers include their department and courses
- Students include their enrollments and courses
- Courses include their department, teacher, and enrolled students
- Enrollments include student and course information
- Grades include enrollment details

### Response Format

All successful responses return JSON:

```json
{
    "id": 1,
    "name": "Computer Science",
    "code": "CS",
    "created_at": "2024-02-16T10:00:00.000000Z",
    "updated_at": "2024-02-16T10:00:00.000000Z"
}
```

Error responses include appropriate HTTP status codes and error messages.

## Technologies Used

- **Laravel 12** - PHP Framework
- **MySQL** - Database
- **L5-Swagger** - API Documentation
- **Swagger UI** - Interactive API Documentation Interface
- **Eloquent ORM** - Database relationships and queries

## Support

For issues or questions, please refer to the Swagger documentation at `/api-docs` for detailed endpoint information and testing capabilities.
