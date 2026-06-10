<header class="page-header">
    <div>
        <p class="eyebrow">Communication</p>
        <h1>Announcements</h1>
    </div>
</header>

<section class="panel">
    <h2>Post announcement</h2>
    <form class="form-grid" method="post">
        <?= csrf_field() ?>
        <label><span>Course</span><select name="course_id"><option value="">General announcement</option><?php foreach ($courses as $course): ?><option value="<?= e((string) $course['id']) ?>"><?= e($course['code']) ?> - <?= e($course['title']) ?></option><?php endforeach; ?></select></label>
        <label><span>Title</span><input name="title" required></label>
        <label class="wide"><span>Message</span><textarea name="body" rows="4" required></textarea></label>
        <button class="btn primary" type="submit">Post announcement</button>
    </form>
</section>

<section class="panel">
    <h2>Published announcements</h2>
    <?php foreach ($announcements as $announcement): ?>
        <article class="announcement">
            <div>
                <strong><?= e($announcement['title']) ?></strong>
                <span><?= e($announcement['course_code'] ?? 'General') ?> - <?= e($announcement['created_at']) ?></span>
                <p><?= e($announcement['body']) ?></p>
            </div>
            <form method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= e((string) $announcement['id']) ?>">
                <button class="btn small danger" name="action" value="delete">Delete</button>
            </form>
        </article>
    <?php endforeach; ?>
</section>
