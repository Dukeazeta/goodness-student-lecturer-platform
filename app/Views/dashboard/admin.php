<header class="page-header">
    <div>
        <p class="eyebrow">Administration</p>
        <h1>System overview</h1>
    </div>
    <a class="btn primary" href="<?= e(url('admin/users')) ?>">Manage users</a>
</header>

<section class="metric-grid">
    <div class="metric"><span><?= e((string) $counts['student']) ?></span><small>Students</small></div>
    <div class="metric"><span><?= e((string) $counts['lecturer']) ?></span><small>Lecturers</small></div>
    <div class="metric"><span><?= e((string) count($courses)) ?></span><small>Courses</small></div>
    <div class="metric"><span><?= e((string) count($faqs)) ?></span><small>FAQ entries</small></div>
</section>

<section class="two-column">
    <div class="panel">
        <h2>Recent announcements</h2>
        <?php foreach (array_slice($announcements, 0, 5) as $announcement): ?>
            <article class="list-item">
                <strong><?= e($announcement['title']) ?></strong>
                <span><?= e($announcement['course_code'] ?? 'General') ?> by <?= e($announcement['author_name']) ?></span>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="panel">
        <h2>Course allocation</h2>
        <?php foreach (array_slice($courses, 0, 6) as $course): ?>
            <article class="list-item">
                <strong><?= e($course['code']) ?> - <?= e($course['title']) ?></strong>
                <span><?= e($course['lecturer_name'] ?? 'No lecturer assigned') ?></span>
            </article>
        <?php endforeach; ?>
    </div>
</section>
