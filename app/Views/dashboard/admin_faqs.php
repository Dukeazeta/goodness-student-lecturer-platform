<header class="page-header">
    <div>
        <p class="eyebrow">Hybrid chatbot</p>
        <h1>Manage FAQ answers</h1>
    </div>
</header>

<section class="panel">
    <h2>Create FAQ entry</h2>
    <form class="form-grid" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="create">
        <label><span>Category</span><input name="category" value="General" required></label>
        <label><span>Question</span><input name="question" required></label>
        <label class="wide"><span>Keywords</span><input name="keywords" placeholder="fees, timetable, course registration" required></label>
        <label class="wide"><span>Answer</span><textarea name="answer" rows="4" required></textarea></label>
        <label class="checkbox"><input type="checkbox" name="is_active" checked> Active</label>
        <button class="btn primary" type="submit">Create FAQ</button>
    </form>
</section>

<section class="panel table-panel">
    <h2>FAQ entries</h2>
    <?php foreach ($faqs as $faq): ?>
        <form class="faq-row" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e((string) $faq['id']) ?>">
            <input name="category" value="<?= e($faq['category']) ?>" required>
            <input name="question" value="<?= e($faq['question']) ?>" required>
            <input name="keywords" value="<?= e($faq['keywords']) ?>" required>
            <textarea name="answer" rows="3" required><?= e($faq['answer']) ?></textarea>
            <label class="checkbox"><input type="checkbox" name="is_active" <?= $faq['is_active'] ? 'checked' : '' ?>> Active</label>
            <div class="actions">
                <button class="btn small" name="action" value="update">Save</button>
                <button class="btn small danger" name="action" value="delete" onclick="return confirm('Delete this FAQ?')">Delete</button>
            </div>
        </form>
    <?php endforeach; ?>
</section>
