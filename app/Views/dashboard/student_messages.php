<header class="page-header">
    <div>
        <p class="eyebrow">Student</p>
        <h1>Message lecturer</h1>
    </div>
</header>

<section class="panel">
    <h2>New message</h2>
    <form class="form-grid" method="post">
        <?= csrf_field() ?>
        <label><span>Lecturer</span><select name="lecturer_id" required><?php foreach ($lecturers as $lecturer): ?><option value="<?= e((string) $lecturer['id']) ?>"><?= e($lecturer['full_name']) ?></option><?php endforeach; ?></select></label>
        <label><span>Course</span><select name="course_id"><option value="">General</option><?php foreach ($courses as $course): ?><option value="<?= e((string) $course['id']) ?>"><?= e($course['code']) ?> - <?= e($course['title']) ?></option><?php endforeach; ?></select></label>
        <label class="wide"><span>Subject</span><input name="subject" required></label>
        <label class="wide"><span>Message</span><textarea name="body" rows="4" required></textarea></label>
        <button class="btn primary" type="submit">Send message</button>
    </form>
</section>

<section class="message-stack">
    <?php foreach ($messages as $message): ?>
        <article class="panel message-card">
            <strong><?= e($message['subject']) ?></strong>
            <span><?= e($message['lecturer_name']) ?> - <?= e($message['course_code'] ?? 'General') ?> - <?= e($message['status']) ?></span>
            <p><?= e($message['body']) ?></p>
            <?php if ($message['reply']): ?>
                <div class="reply-box"><?= e($message['reply']) ?></div>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</section>
