<header class="page-header">
    <div>
        <p class="eyebrow">Admin</p>
        <h1>Manage courses</h1>
    </div>
</header>

<section class="panel">
    <h2>Create course</h2>
    <form class="form-grid" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="create">
        <label><span>Course code</span><input name="code" required></label>
        <label><span>Course title</span><input name="title" required></label>
        <label><span>Lecturer</span><select name="lecturer_id"><option value="">Unassigned</option><?php foreach ($lecturers as $lecturer): ?><option value="<?= e((string) $lecturer['id']) ?>"><?= e($lecturer['full_name']) ?></option><?php endforeach; ?></select></label>
        <label class="wide"><span>Description</span><textarea name="description" rows="3"></textarea></label>
        <button class="btn primary" type="submit">Create course</button>
    </form>
</section>

<section class="panel">
    <h2>Assign courses to students</h2>
    <form class="form-grid" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="enroll">
        <label>
            <span>Student</span>
            <select name="student_id" required>
                <?php foreach ($students as $student): ?>
                    <option value="<?= e((string) $student['id']) ?>"><?= e($student['full_name']) ?><?= $student['matric_number'] ? ' - ' . e($student['matric_number']) : '' ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            <span>Course</span>
            <select name="course_id" required>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= e((string) $course['id']) ?>"><?= e($course['code']) ?> - <?= e($course['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button class="btn primary" type="submit">Assign course</button>
    </form>
</section>

<section class="panel">
    <h2>Student course assignments</h2>
    <div class="admin-list">
        <?php foreach ($enrollments as $enrollment): ?>
            <form class="admin-row enrollment-row" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="unenroll">
                <input type="hidden" name="student_id" value="<?= e((string) $enrollment['student_id']) ?>">
                <input type="hidden" name="course_id" value="<?= e((string) $enrollment['course_id']) ?>">
                <div>
                    <strong><?= e($enrollment['student_name']) ?></strong>
                    <span><?= e($enrollment['matric_number'] ?? 'No matric number') ?></span>
                </div>
                <div>
                    <strong><?= e($enrollment['course_code']) ?></strong>
                    <span><?= e($enrollment['course_title']) ?></span>
                </div>
                <div class="actions">
                    <button class="btn small danger" type="submit">Remove</button>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
</section>

<section class="panel">
    <h2>Courses</h2>
    <div class="admin-list">
        <?php foreach ($courses as $course): ?>
            <form class="admin-row course-row" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= e((string) $course['id']) ?>">
                <input name="code" value="<?= e($course['code']) ?>" required>
                <input name="title" value="<?= e($course['title']) ?>" required>
                <select name="lecturer_id">
                    <option value="">Unassigned</option>
                    <?php foreach ($lecturers as $lecturer): ?>
                        <option value="<?= e((string) $lecturer['id']) ?>" <?= (int) $course['lecturer_id'] === (int) $lecturer['id'] ? 'selected' : '' ?>><?= e($lecturer['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <textarea name="description" rows="2"><?= e($course['description']) ?></textarea>
                <div class="actions">
                    <button class="btn small" name="action" value="update">Save</button>
                    <button class="btn small danger" name="action" value="delete" onclick="return confirm('Delete this course?')">Delete</button>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
</section>
