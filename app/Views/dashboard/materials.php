<header class="page-header">
    <div>
        <p class="eyebrow">Course materials</p>
        <h1>Materials for chatbot answers</h1>
    </div>
</header>

<section class="panel">
    <h2>Add material</h2>
    <form class="form-grid" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="create">
        <label>
            <span>Course</span>
            <select name="course_id" required>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= e((string) $course['id']) ?>"><?= e($course['code']) ?> - <?= e($course['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            <span>Title</span>
            <input name="title" required>
        </label>
        <label>
            <span>Optional file</span>
            <input type="file" name="material_file" accept=".pdf,.doc,.docx,.txt">
        </label>
        <label class="wide">
            <span>Description</span>
            <input name="description" placeholder="Short note about the material">
        </label>
        <label class="wide">
            <span>Searchable material text</span>
            <textarea name="searchable_text" rows="7" required placeholder="Paste the important notes here so the chatbot can answer from them."></textarea>
        </label>
        <label class="checkbox"><input type="checkbox" name="is_active" checked> Active</label>
        <button class="btn primary" type="submit">Add material</button>
    </form>
</section>

<section class="panel">
    <h2>Saved materials</h2>
    <div class="admin-list">
        <?php foreach ($materials as $material): ?>
            <article class="admin-row material-row">
                <div>
                    <strong><?= e($material['title']) ?></strong>
                    <span><?= e($material['course_code']) ?> - <?= e($material['course_title']) ?></span>
                </div>
                <div>
                    <strong><?= e($material['uploader_name']) ?></strong>
                    <span><?= $material['is_active'] ? 'Active' : 'Inactive' ?><?= $material['file_name'] ? ' - File: ' . e($material['file_name']) : '' ?></span>
                </div>
                <div class="actions">
                    <?php if ($material['file_path']): ?>
                        <a class="btn small" href="<?= e(asset($material['file_path'])) ?>" target="_blank" rel="noopener">Open file</a>
                    <?php endif; ?>
                    <form method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e((string) $material['id']) ?>">
                        <button class="btn small" name="action" value="toggle" type="submit"><?= $material['is_active'] ? 'Deactivate' : 'Activate' ?></button>
                    </form>
                    <form method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e((string) $material['id']) ?>">
                        <button class="btn small danger" name="action" value="delete" type="submit" onclick="return confirm('Delete this material?')">Delete</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
