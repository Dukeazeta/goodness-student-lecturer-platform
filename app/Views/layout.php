<?php

use App\Core\Auth;

$currentUser = Auth::user();
$pageTitle = $title ?? 'Student-Lecturer Platform';

if (!function_exists('ui_icon')) {
    function ui_icon(string $name): string
    {
        $icons = [
            'dashboard' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 13h7V4H4v9Zm9 7h7V4h-7v16ZM4 20h7v-5H4v5Z"/></svg>',
            'users' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8.5 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7ZM2.5 20c.7-4 3-6 5.5-6s4.8 2 5.5 6h-11Zm11.5 0c.4-2 1.2-3.6 2.3-4.7 2.2.2 4.1 1.9 4.7 4.7h-7Z"/></svg>',
            'courses' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h11a3 3 0 0 1 3 3v13H7a3 3 0 0 1-3-3V5a1 1 0 0 1 1-1Zm2 13h10V7a1 1 0 0 0-1-1H6v11a1 1 0 0 0 1 1Zm1-8h7v2H8V9Zm0 4h6v2H8v-2Z"/></svg>',
            'faq' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3a9 9 0 1 0 9 9 9 9 0 0 0-9-9Zm0 14a1.2 1.2 0 1 1 1.2-1.2A1.2 1.2 0 0 1 12 17Zm1-4h-2v-.8c0-1.1.7-1.7 1.5-2.2.7-.5 1.2-.8 1.2-1.5 0-.8-.6-1.3-1.6-1.3-1 0-1.7.5-2.4 1.3L8.4 7.2A4.5 4.5 0 0 1 12.2 5c2.2 0 3.8 1.2 3.8 3.2 0 1.7-1 2.5-1.9 3.1-.7.5-1.1.8-1.1 1.5v.2Z"/></svg>',
            'announcements' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 10v4h3l7 4V6l-7 4H4Zm13.5-2.5-1.4 1.4A4.3 4.3 0 0 1 18 12c0 1.2-.5 2.3-1.3 3.1l1.4 1.4A6.3 6.3 0 0 0 20 12c0-1.7-.7-3.3-2.5-4.5Z"/></svg>',
            'materials' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h12a3 3 0 0 1 3 3v15H7a3 3 0 0 1-3-3V4a1 1 0 0 1 1-1Zm2 14a1 1 0 0 0 1 1h10V6a1 1 0 0 0-1-1H6v12h1Zm2-9h7v2H9V8Zm0 4h6v2H9v-2Z"/></svg>',
            'messages' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H9l-5 4v-4H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Zm2 4v2h12V9H6Zm0 4v2h8v-2H6Z"/></svg>',
            'chatbot' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M11 2h2v3h4a3 3 0 0 1 3 3v7a3 3 0 0 1-3 3h-1.5L12 22l-3.5-4H7a3 3 0 0 1-3-3V8a3 3 0 0 1 3-3h4V2Zm-4 8.5A1.5 1.5 0 1 0 8.5 9 1.5 1.5 0 0 0 7 10.5Zm8.5 0A1.5 1.5 0 1 0 17 9a1.5 1.5 0 0 0-1.5 1.5ZM9 14v2h6v-2H9Z"/></svg>',
            'logout' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 3h9v2H6v14h7v2H4V3Zm11.6 4.4L20.2 12l-4.6 4.6-1.4-1.4 2.2-2.2H10v-2h6.4l-2.2-2.2 1.4-1.4Z"/></svg>',
        ];

        return $icons[$name] ?? '';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - ClassBridge</title>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body>
<div class="app-shell">
    <button class="mobile-menu-button" type="button" data-sidebar-toggle aria-label="Open navigation">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v2H4V7Zm0 4h16v2H4v-2Zm0 4h16v2H4v-2Z"/></svg>
    </button>
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="sidebar" id="dashboardSidebar">
        <a class="brand" href="<?= e(url('dashboard')) ?>">
            <img class="brand-logo" src="<?= e(asset('img/classbridge-logo.png')) ?>" alt="ClassBridge">
            <span class="sidebar-text">
                <strong>ClassBridge</strong>
                <small>Student-Lecturer Platform</small>
            </span>
        </a>

        <?php if ($currentUser): ?>
            <nav class="nav">
                <a href="<?= e(url('dashboard')) ?>"><span class="nav-icon"><?= ui_icon('dashboard') ?></span><span class="sidebar-text">Dashboard</span></a>
                <?php if ($currentUser['role'] === 'admin'): ?>
                    <a href="<?= e(url('admin/users')) ?>"><span class="nav-icon"><?= ui_icon('users') ?></span><span class="sidebar-text">Users</span></a>
                    <a href="<?= e(url('admin/courses')) ?>"><span class="nav-icon"><?= ui_icon('courses') ?></span><span class="sidebar-text">Courses</span></a>
                    <a href="<?= e(url('admin/faqs')) ?>"><span class="nav-icon"><?= ui_icon('faq') ?></span><span class="sidebar-text">Chatbot FAQ</span></a>
                    <a href="<?= e(url('materials')) ?>"><span class="nav-icon"><?= ui_icon('materials') ?></span><span class="sidebar-text">Materials</span></a>
                    <a href="<?= e(url('lecturer/announcements')) ?>"><span class="nav-icon"><?= ui_icon('announcements') ?></span><span class="sidebar-text">Announcements</span></a>
                <?php elseif ($currentUser['role'] === 'lecturer'): ?>
                    <a href="<?= e(url('lecturer/messages')) ?>"><span class="nav-icon"><?= ui_icon('messages') ?></span><span class="sidebar-text">Student Messages</span></a>
                    <a href="<?= e(url('materials')) ?>"><span class="nav-icon"><?= ui_icon('materials') ?></span><span class="sidebar-text">Materials</span></a>
                    <a href="<?= e(url('lecturer/announcements')) ?>"><span class="nav-icon"><?= ui_icon('announcements') ?></span><span class="sidebar-text">Announcements</span></a>
                <?php else: ?>
                    <a href="<?= e(url('student/messages')) ?>"><span class="nav-icon"><?= ui_icon('messages') ?></span><span class="sidebar-text">Message Lecturer</span></a>
                    <a href="<?= e(url('student/chatbot')) ?>"><span class="nav-icon"><?= ui_icon('chatbot') ?></span><span class="sidebar-text">Chatbot</span></a>
                <?php endif; ?>
            </nav>

            <div class="profile-block">
                <span class="avatar"><?= e(strtoupper(substr($currentUser['full_name'], 0, 1))) ?></span>
                <div class="sidebar-text">
                    <strong><?= e($currentUser['full_name']) ?></strong>
                    <small><?= e(ucfirst($currentUser['role'])) ?></small>
                </div>
            </div>
            <a class="logout" href="<?= e(url('logout')) ?>"><span class="nav-icon"><?= ui_icon('logout') ?></span><span class="sidebar-text">Sign out</span></a>
        <?php endif; ?>
    </aside>

    <main class="main">
        <?php if ($message = flash('success')): ?>
            <div class="notice success"><?= e($message) ?></div>
        <?php endif; ?>
        <?php if ($message = flash('error')): ?>
            <div class="notice error"><?= e($message) ?></div>
        <?php endif; ?>

        <?php require $viewPath; ?>
    </main>
</div>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
