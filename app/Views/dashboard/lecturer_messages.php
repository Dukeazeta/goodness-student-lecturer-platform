<header class="page-header">
    <div>
        <p class="eyebrow">Lecturer</p>
        <h1>Student messages</h1>
    </div>
</header>

<section class="message-stack">
    <?php foreach ($messages as $message): ?>
        <article class="panel message-card">
            <div class="message-head">
                <div>
                    <strong><?= e($message['subject']) ?></strong>
                    <span><?= e($message['student_name']) ?> - <?= e($message['course_code'] ?? 'General') ?> - <?= e($message['status']) ?></span>
                </div>
            </div>
            <p><?= e($message['body']) ?></p>
            <?php if ($message['reply']): ?>
                <div class="reply-box"><?= e($message['reply']) ?></div>
            <?php endif; ?>
            <form method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= e((string) $message['id']) ?>">
                <label><span>Reply</span><textarea name="reply" rows="3" required><?= e($message['reply']) ?></textarea></label>
                <button class="btn primary" type="submit">Send reply</button>
            </form>
        </article>
    <?php endforeach; ?>
</section>
