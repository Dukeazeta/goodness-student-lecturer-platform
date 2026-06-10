<header class="page-header">
    <div>
        <p class="eyebrow">Lecturer workspace</p>
        <h1>Welcome, <?= e($user['full_name']) ?></h1>
    </div>
    <a class="btn primary" href="<?= e(url('lecturer/messages')) ?>">Review messages</a>
</header>

<section class="metric-grid">
    <div class="metric"><span><?= e((string) count($courses)) ?></span><small>Assigned courses</small></div>
    <div class="metric"><span><?= e((string) count($messages)) ?></span><small>Student messages</small></div>
    <div class="metric"><span><?= e((string) count($announcements)) ?></span><small>Announcements</small></div>
</section>

<section class="two-column">
    <div class="panel">
        <h2>My courses</h2>
        <?php foreach ($courses as $course): ?>
            <article class="list-item">
                <strong><?= e($course['code']) ?> - <?= e($course['title']) ?></strong>
                <span><?= e($course['description'] ?? 'No description yet') ?></span>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="panel">
        <h2>Newest messages</h2>
        <?php foreach (array_slice($messages, 0, 5) as $message): ?>
            <article class="list-item">
                <strong><?= e($message['subject']) ?></strong>
                <span><?= e($message['student_name']) ?> - <?= e($message['status']) ?></span>
            </article>
        <?php endforeach; ?>
    </div>
</section>
