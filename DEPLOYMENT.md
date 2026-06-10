# InfinityFree Deployment Guide

This project is a plain PHP/MySQL app. InfinityFree is a good fit because it supports PHP, MySQL, phpMyAdmin, and `.htaccess`.

## What To Upload

Upload the contents of this project folder into InfinityFree's `htdocs` folder:

```text
goodness-student-lecturer-platform/
```

Do not upload:

```text
.git/
database/backups/
.env
```

The root `.htaccess` file redirects visitors into `public/`, so the site can still work even though InfinityFree does not let you change the web root.

## Database Setup

1. Open InfinityFree / VistaPanel.
2. Create a MySQL database.
3. Open phpMyAdmin from that database.
4. Import the current backup if you want to preserve local data:

```text
database/backups/goodness_platform_live_2026-06-10.sql
```

If you want a clean demo database instead, import these in order:

```text
database/schema.sql
database/seed.sql
```

Do not import `schema.sql` over a database that already has the current project data unless you intentionally want to reset it.

## Database Config On InfinityFree

Copy this file:

```text
config/database.local.example.php
```

Rename the copy to:

```text
config/database.local.php
```

Then edit it with the database details from VistaPanel:

```php
<?php

return [
    'host' => 'sql000.infinityfree.com',
    'port' => 3306,
    'database' => 'if0_00000000_goodness_platform',
    'username' => 'if0_00000000',
    'password' => 'your_vistapanel_password',
    'charset' => 'utf8mb4',
];
```

`config/database.local.php` is ignored by Git so real database credentials do not get committed.

## Uploads

Course materials upload to:

```text
public/uploads/materials
```

Keep the `.htaccess` file inside that folder. It blocks PHP files from running inside uploads.

## Smoke Test

1. Visit the InfinityFree URL.
2. Confirm the landing page loads.
3. Sign in as admin.
4. Open Users, Courses, FAQ, and Materials.
5. Sign in as student and test the chatbot.
6. Upload a small material file as lecturer/admin and ask the chatbot about it.

## Notes

InfinityFree does not support remote MySQL access. That is fine for this project because the PHP app and MySQL database will both run inside InfinityFree. Use phpMyAdmin for database imports and exports.
