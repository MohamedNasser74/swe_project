<?php ob_start(); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-0"><?= $page_title ?? 'User Management' ?></h1>
            <p class="text-muted mb-0">View and manage all platform users.</p>
        </div>
        <div class="text-muted">
            <i class="fas fa-users me-2"></i>
            <?= $total_users ?? (is_array($users ?? null) ? count($users) : 0) ?> users
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-users-cog me-2 text-primary"></i>
                Users
            </h5>
            <form class="d-flex gap-2" method="get" action="<?= APP_URL ?>/admin/users">
                <select name="role" class="form-select form-select-sm">
                    <option value="">All Roles</option>
                    <option value="student" <?= ($filter_role ?? '') === 'student' ? 'selected' : '' ?>>Student</option>
                    <option value="counselor" <?= ($filter_role ?? '') === 'counselor' ? 'selected' : '' ?>>Counselor</option>
                    <option value="admin" <?= ($filter_role ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" <?= ($filter_status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($filter_status ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
                <button class="btn btn-sm btn-primary" type="submit">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
            </form>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($users)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $index => $user): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></td>
                                    <td><?= htmlspecialchars($user->email) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user->role === 'student' ? 'success' : ($user->role === 'counselor' ? 'info' : 'primary') ?>">
                                            <?= ucfirst($user->role) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($user->created_at)) ?></td>
                                    <td class="text-end">
                                        <a href="<?= APP_URL ?>/admin/userDetails/<?= $user->id ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <form method="post" action="<?= APP_URL ?>/admin/changeUserRole/<?= $user->id ?>" class="d-inline">
                                            <select name="role" class="form-select form-select-sm d-inline w-auto">
                                                <option value="student" <?= $user->role === 'student' ? 'selected' : '' ?>>Student</option>
                                                <option value="counselor" <?= $user->role === 'counselor' ? 'selected' : '' ?>>Counselor</option>
                                                <option value="admin" <?= $user->role === 'admin' ? 'selected' : '' ?>>Admin</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-secondary ms-1">
                                                Update
                                            </button>
                                        </form>
                                        <form method="post" action="<?= APP_URL ?>/admin/toggleUserStatus/<?= $user->id ?>" class="d-inline">
                                            <button type="submit" class="btn btn-sm <?= ($user->status ?? 'active') === 'active' ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                                <?= ($user->status ?? 'active') === 'active' ? 'Deactivate' : 'Activate' ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <p class="mb-0">No users found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>


