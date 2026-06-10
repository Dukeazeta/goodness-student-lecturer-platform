# Pxxl Deployment Guide

This app is a plain PHP/MySQL project. It does not need Composer, Node, Laravel, or a build step.

## Before Deploying

The current local database has been backed up to:

```text
database/backups/goodness_platform_live_2026-06-10.sql
```

Keep that file private because it contains real database records. It is ignored by Git.

## Pxxl Settings

Use these settings when creating the PHP app on Pxxl:

```text
Runtime: PHP
Public directory / web root: public
Entry file: index.php
Build command: none
Start command: none
```

## Environment Variables

Set either `DATABASE_URL`:

```text
DATABASE_URL=mysql://username:password@host:3306/database_name?charset=utf8mb4
```

Or set the separate database variables:

```text
DB_HOST=your_mysql_host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
DB_CHARSET=utf8mb4
```

The app still works locally with XAMPP defaults if these variables are not set.

## Database Import

To preserve the current data, import the live backup into the Pxxl MySQL database:

```text
database/backups/goodness_platform_live_2026-06-10.sql
```

If you want a clean demo database instead, import these in order:

```text
database/schema.sql
database/seed.sql
```

Do not import `schema.sql` over the live database unless you intentionally want to reset it.

## Uploads

Course materials upload to:

```text
public/uploads/materials
```

Make sure this folder is writable on Pxxl. PHP execution is blocked in that folder by `.htaccess`.

## Smoke Test After Deploy

1. Open the deployed URL.
2. Click `Get Started`.
3. Sign in as admin.
4. Open Users, Courses, FAQ, and Materials.
5. Sign in as student and test the chatbot.
6. Upload a small material file as lecturer/admin and confirm it appears in the material list.
