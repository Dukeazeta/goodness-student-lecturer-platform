<?php
$roleCounts = ['admin' => 0, 'lecturer' => 0, 'student' => 0];
$activeCount = 0;
foreach ($users as $user) {
    if (isset($roleCounts[$user['role']])) {
        $roleCounts[$user['role']]++;
    }
    if ($user['status'] === 'active') {
        $activeCount++;
    }
}
?>

<header class="page-header">
    <div>
        <p class="eyebrow">Admin</p>
        <h1>Manage users</h1>
        <p class="page-subtitle">Create accounts, update access levels, and keep student and lecturer records tidy.</p>
    </div>
</header>

<section class="user-stats" aria-label="User summary">
    <article class="stat-tile">
        <span><?= e((string) count($users)) ?></span>
        <small>Total users</small>
    </article>
    <article class="stat-tile">
        <span><?= e((string) $activeCount) ?></span>
        <small>Active accounts</small>
    </article>
    <article class="stat-tile">
        <span><?= e((string) $roleCounts['student']) ?></span>
        <small>Students</small>
    </article>
    <article class="stat-tile">
        <span><?= e((string) $roleCounts['lecturer']) ?></span>
        <small>Lecturers</small>
    </article>
</section>

<section class="panel users-create-panel">
    <div class="section-heading">
        <div>
            <h2>Create account</h2>
            <p>Add a student, lecturer, or administrator with the correct access level.</p>
        </div>
    </div>
    <form class="users-create-form" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="create">
        <label><span>Full name</span><input name="full_name" required autocomplete="name"></label>
        <label><span>Email address</span><input type="email" name="email" required autocomplete="email"></label>
        <label><span>Password</span><input type="password" name="password" required autocomplete="new-password"></label>
        <label><span>Role</span><select name="role"><option>student</option><option>lecturer</option><option>admin</option></select></label>
        <label><span>Matric number</span><input name="matric_number"></label>
        <label><span>Department</span><input name="department" value="Computer Science"></label>
        <label><span>Status</span><select name="status"><option>active</option><option>inactive</option></select></label>
        <button class="btn primary" type="submit">Create user</button>
    </form>
</section>

<section class="panel users-directory-panel">
    <div class="section-heading">
        <div>
            <h2>User directory</h2>
            <p>Edit account details directly, then save only the row you changed.</p>
        </div>
        <span class="record-count"><?= e((string) count($users)) ?> records</span>
    </div>

    <div class="users-table" role="list">
        <div class="users-table-head" aria-hidden="true">
            <span>Account</span>
            <span>Access</span>
            <span>Academic details</span>
            <span></span>
        </div>

        <?php foreach ($users as $row): ?>
            <?php $initial = strtoupper(substr(trim($row['full_name']), 0, 1) ?: 'U'); ?>
            <form class="users-table-row" method="post" role="listitem">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= e((string) $row['id']) ?>">

                <div class="account-cell">
                    <span class="user-initial"><?= e($initial) ?></span>
                    <div>
                        <label><span>Full name</span><input name="full_name" value="<?= e($row['full_name']) ?>" required></label>
                        <label><span>Email address</span><input type="email" name="email" value="<?= e($row['email']) ?>" required></label>
                    </div>
                </div>

                <div class="stacked-fields access-cell">
                    <label><span>Role</span><select name="role">
                        <?php foreach (['student', 'lecturer', 'admin'] as $role): ?>
                            <option value="<?= e($role) ?>" <?= $row['role'] === $role ? 'selected' : '' ?>><?= e($role) ?></option>
                        <?php endforeach; ?>
                    </select></label>
                    <label><span>Status</span><select name="status"><option <?= $row['status'] === 'active' ? 'selected' : '' ?>>active</option><option <?= $row['status'] === 'inactive' ? 'selected' : '' ?>>inactive</option></select></label>
                </div>

                <div class="stacked-fields academic-cell">
                    <label><span>Matric number</span><input name="matric_number" value="<?= e($row['matric_number']) ?>"></label>
                    <label><span>Department</span><input name="department" value="<?= e($row['department']) ?>"></label>
                </div>

                <label class="password-cell"><span>New password</span><input type="password" name="password"></label>

                <div class="row-actions">
                    <button class="btn small" name="action" value="update">Save</button>
                    <button class="btn small danger" name="action" value="delete" onclick="return confirm('Delete this user?')">Delete</button>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
</section>
