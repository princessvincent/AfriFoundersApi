Task Management API

Version: Laravel 11
Authentication: Laravel Sanctum (Token-based API authentication)

Overview

The Task Management API is a web-based application that allows registered users to manage their daily tasks. Only authenticated users can access the system, ensuring that each user’s tasks remain private.

This API provides functionality to:

Register a new user

Log in and obtain an access token

Log out securely

Create, view, update, and delete tasks

Filter tasks by status (pending, in-progress, completed)

Paginate tasks for easy viewing

All actions are performed through secure API requests, making it suitable for integration with web or mobile applications.


Features
User Management

User Registration

Users can register by providing a name, email, and password.

The system securely hashes passwords before storing them.

Successful registration returns the newly created user details.

User Login

Registered users can log in using their email and password.

A secure API token is generated on login, which is required to access task-related features.

User Logout

Authenticated users can log out, which revokes their API token.

Task Management

Create Task

Authenticated users can create a task by providing a title, description, and optional status.

Default status is pending if not specified.

View Tasks

Users can view all their tasks.

Tasks can be filtered by status.

Results are paginated (10 tasks per page).

View Single Task

Users can retrieve a single task by its ID.

The system ensures users can only access their own tasks.

Update Task

Users can update the title, description, or status of a task.

Only the task owner can update a task.

Delete Task

Users can delete a task.

Only the task owner can delete their task.


API Endpoints
Public Endpoints (No Authentication Required)

| Endpoint              | Method | Description                              |
| --------------------- | ------ | ---------------------------------------- |
| `/api/register`       | POST   | Register a new user                      |
| `/api/user-login`     | POST   | Login and receive API token              |


Protected Endpoints (Authentication Required)

| Endpoint            | Method | Description                                    |
| ------------------- | ------ | ---------------------------------------------- |
| `/api/logout`       | POST   | Logout user and revoke token                   |
| `/api/user`         | GET    | Retrieve logged-in user information            |
| `/api/get/tasks`    | GET    | List all user tasks (supports `status` filter) |
| `/api/task`         | POST   | Create a new task                              |
| `/api/tasks/{task}` | GET    | View a specific task                           |
| `/api/tasks/{task}` | PUT    | Update a specific task                         |
| `/api/tasks/{task}` | DELETE | Delete a specific task                         |


Database Structure
Users Table

| Column     | Type      | Description                |
| ---------- | --------- | -------------------------- |
| id         | int       | Auto-increment primary key |
| name       | string    | Full name of user          |
| email      | string    | Email address              |
| password   | string    | Hashed password            |
| timestamps | timestamp | Created and updated time   |


Tasks Table

| Column      | Type      | Description                                        |
| ----------- | --------- | -------------------------------------------------- |
| id          | int       | Auto-increment primary key                         |
| user_id     | int       | ID of the task owner (foreign key)                 |
| title       | string    | Title of the task                                  |
| description | text      | Detailed description of the task                   |
| status      | enum      | Task status: `pending`, `in-progress`, `completed` |
| timestamps  | timestamp | Created and updated time                           |


Setup Instructions
Requirements

PHP >= 8.2
Composer
MySQL or PostgreSQL database
Laravel 11

Installation Steps

1. Clone the repository
  git clone https://github.com/princessvincent/AfriFoundersApi.git
  cd <repository-folder>

2. Install dependencies
   composer install

3. Configure environment
   Copy .env.example to .env
   Set your database credentials in .env

   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=task_manager
   DB_USERNAME=root
   DB_PASSWORD=secret

4. Generate application key
   php artisan key:generate

5. Run migrations
   php artisan migrate

6. Serve the application
   php artisan serve

The API will be accessible at http://localhost:8000.

Authentication

The application uses Laravel Sanctum for API token-based authentication.
After logging in, a user receives an access token.
All protected routes require the token to be sent in the Authorization header as follows:

Authorization: Bearer <access_token>

Testing
feature tests is included to ensure the API works correctly.

Example tests include user registration, login, and task creation.

Run tests using:

php artisan test

for specific test file

php artisan test tests/Feature/TaskApiTest.php


Error Handling

The API returns JSON responses for all errors.

Common error messages:

Unauthorized – Accessing a task not owned by the user

The provided credentials are incorrect – Invalid login

Validation errors – Missing or invalid input

Notes

Each user can only access their own tasks.

Tasks can have one of three statuses: pending, in-progress, completed.

API responses follow a consistent JSON structure for easier integration with web or mobile applications.

Example JSON Responses

Register User
{
"message": "User registered successfully.",
"user": {
"id": 1,
"name": "Prisca Eze",
"email": "prisca@example.com",
"created_at": "2025-11-08T12:00:00.000000Z",
"updated_at": "2025-11-08T12:00:00.000000Z"
}
}

Create Task
{
"message": "Task created successfully",
"task": {
"id": 1,
"user_id": 1,
"title": "Test Task",
"description": "This is a test task",
"status": "pending",
"created_at": "2025-11-08T12:10:00.000000Z",
"updated_at": "2025-11-08T12:10:00.000000Z"
}
}

Conclusion

This Laravel 11 Task Management API provides a secure, efficient, 
and user-friendly way for authenticated users to manage their tasks.
It’s fully tested, easy to set up, and ready to integrate with web or 
mobile applications.
