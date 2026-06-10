# Goodness Student-Lecturer Interaction Platform

A PHP/MySQL coursework web app for a student-lecturer interaction platform with chatbot integration.

## Features

- Admin, lecturer, and student login
- Role-based dashboards
- User management
- Course management
- Student-to-lecturer messaging
- Lecturer announcements
- FAQ and course-material chatbot with fallback logging
- Course material upload and searchable material text
- MySQL schema and seed data for demo use

## Demo Accounts

All demo accounts use this password:

```text
password
```

| Role | Email |
| --- | --- |
| Admin | `admin@example.com` |
| Lecturer | `lecturer@example.com` |
| Student | `student@example.com` |

## XAMPP Setup

1. Install and open XAMPP.
2. Start Apache and MySQL.
3. Copy this folder to:

```text
C:\xampp\htdocs\goodness-student-lecturer-platform
```

4. Open phpMyAdmin at:

```text
http://localhost/phpmyadmin
```

5. Import `database/schema.sql`.
6. Import `database/seed.sql`.
7. Confirm `config/database.php` matches your MySQL settings. XAMPP usually uses:

```php
'host' => '127.0.0.1',
'port' => 3307,
'database' => 'goodness_platform',
'username' => 'root',
'password' => '',
```

This machine uses port `3307` for XAMPP MySQL because another MySQL service is already using port `3306`.

8. Visit:

```text
http://localhost/goodness-student-lecturer-platform/public/
```

## Deployment

See `DEPLOYMENT.md` for Pxxl deployment steps.

For production, set database credentials through environment variables such as:

```text
DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD
```

The app also supports a single `DATABASE_URL` value.

Do not import `database/schema.sql` into a database that already contains records unless you want to reset it. Use a current SQL backup when preserving live data.

## Project Structure

```text
app/
  Controllers/   Request handling for each role
  Core/          Database, auth, and base controller
  Models/        PDO database operations
  Views/         PHP templates
config/          Database and optional AI settings
database/        MySQL schema and seed files
public/          Entry point, CSS, and JavaScript
```

## Chatbot Design

The chatbot is hybrid-ready. In this version, it first handles simple greetings and common small-talk questions, then checks the FAQ table and searchable course materials using keyword and similarity matching. If no good match is found, it stores the question in `chat_logs` and returns a polite fallback. The optional AI configuration is kept in `config/ai.php` but disabled by default so the demo works without API keys or internet access.

## Suggested Demo Flow

1. Sign in as admin and show users, courses, and FAQ entries.
2. Sign in as lecturer and post an announcement.
3. Reply to a student message.
4. Sign in as student and view announcements.
5. Send a message to a lecturer.
6. Ask the chatbot: `hello`
7. Ask the chatbot about an uploaded course material.
8. Ask an unknown question and show that it is saved in `chat_logs`.
