<header class="page-header">
    <div>
        <p class="eyebrow">Student portal</p>
        <h1>Welcome, <?= e($user['full_name']) ?></h1>
    </div>
    <a class="btn primary" href="<?= e(url('student/chatbot')) ?>">Ask chatbot</a>
</header>

<section class="metric-grid">
    <div class="metric"><span><?= e((string) count($courses)) ?></span><small>Enrolled courses</small></div>
    <div class="metric"><span><?= e((string) count($messages)) ?></span><small>Messages</small></div>
    <div class="metric"><span><?= e((string) count($announcements)) ?></span><small>Announcements</small></div>
</section>

<section class="two-column">
    <div class="panel">
        <h2>Announcements</h2>
        <?php foreach (array_slice($announcements, 0, 6) as $announcement): ?>
            <article class="list-item">
                <strong><?= e($announcement['title']) ?></strong>
                <span><?= e($announcement['course_code'] ?? 'General') ?> - <?= e($announcement['body']) ?></span>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="panel">
        <h2>Recent messages</h2>
        <?php if (!$messages): ?>
            <article class="list-item">
                <strong>No messages yet</strong>
                <span>Messages you send to lecturers will appear here.</span>
            </article>
        <?php endif; ?>
        <?php foreach (array_slice($messages, 0, 4) as $message): ?>
            <article class="list-item">
                <strong><?= e($message['subject']) ?></strong>
                <span><?= e($message['lecturer_name']) ?> - <?= e($message['status']) ?></span>
                <?php if ($message['reply']): ?>
                    <span>Reply: <?= e($message['reply']) ?></span>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
        <a class="btn small" href="<?= e(url('student/messages')) ?>">View all messages</a>
    </div>
</section>

<section class="panel">
        <h2>My courses</h2>
        <?php foreach ($courses as $course): ?>
            <article class="list-item">
                <strong><?= e($course['code']) ?> - <?= e($course['title']) ?></strong>
                <span>Lecturer: <?= e($course['lecturer_name'] ?? 'Pending') ?></span>
            </article>
        <?php endforeach; ?>
</section>
