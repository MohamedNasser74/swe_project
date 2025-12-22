<?php ob_start(); ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1"><?= $page_title ?></h1>
            <p class="text-muted mb-0">Manage navigation menu items (Dynamic Menu with Self-Reference)</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMenuModal">
            <i class="fas fa-plus me-2"></i>Add Menu Item
        </button>
    </div>

    <!-- Menu Items Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order</th>
                            <th>Title</th>
                            <th>URL</th>
                            <th>Icon</th>
                            <th>Parent</th>
                            <th>Role Access</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($menus)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-bars fa-2x mb-2"></i>
                                    <p>No menu items found.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($menus as $menu): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary"><?= $menu->sort_order ?></span>
                                    </td>
                                    <td>
                                        <?php if (!empty($menu->parent_id)): ?>
                                            <span class="text-muted me-2">└──</span>
                                        <?php endif; ?>
                                        <strong><?= htmlspecialchars($menu->title) ?></strong>
                                    </td>
                                    <td>
                                        <code class="small"><?= htmlspecialchars($menu->url) ?></code>
                                    </td>
                                    <td>
                                        <?php if (!empty($menu->icon)): ?>
                                            <i class="<?= htmlspecialchars($menu->icon) ?>"></i>
                                            <small class="text-muted ms-1"><?= htmlspecialchars($menu->icon) ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($menu->parent_title)): ?>
                                            <span class="badge bg-info"><?= htmlspecialchars($menu->parent_title) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Top Level</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $menu->role_access === 'all' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($menu->role_access) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($menu->is_active): ?>
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i class="fas fa-times me-1"></i>Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editMenuModal<?= $menu->id ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteMenuModal<?= $menu->id ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Modal for this menu item -->
                                <div class="modal fade" id="editMenuModal<?= $menu->id ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="<?= APP_URL ?>/admin/update-menu/<?= $menu->id ?>">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Menu Item</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Title *</label>
                                                        <input type="text" class="form-control" name="title" 
                                                               value="<?= htmlspecialchars($menu->title) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">URL *</label>
                                                        <input type="text" class="form-control" name="url" 
                                                               value="<?= htmlspecialchars($menu->url) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Icon (Font Awesome class)</label>
                                                        <input type="text" class="form-control" name="icon" 
                                                               value="<?= htmlspecialchars($menu->icon ?? '') ?>"
                                                               placeholder="e.g., fas fa-home">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Parent Menu</label>
                                                            <select class="form-select" name="parent_id">
                                                                <option value="">— No Parent (Top Level) —</option>
                                                                <?php foreach ($menus as $parentMenu): ?>
                                                                    <?php if ($parentMenu->id != $menu->id && empty($parentMenu->parent_id)): ?>
                                                                        <option value="<?= $parentMenu->id ?>" 
                                                                            <?= $menu->parent_id == $parentMenu->id ? 'selected' : '' ?>>
                                                                            <?= htmlspecialchars($parentMenu->title) ?>
                                                                        </option>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Sort Order</label>
                                                            <input type="number" class="form-control" name="sort_order" 
                                                                   value="<?= $menu->sort_order ?>" min="0">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Role Access</label>
                                                        <select class="form-select" name="role_access">
                                                            <option value="all" <?= $menu->role_access === 'all' ? 'selected' : '' ?>>All Users</option>
                                                            <option value="guest" <?= $menu->role_access === 'guest' ? 'selected' : '' ?>>Guests Only</option>
                                                            <option value="student" <?= $menu->role_access === 'student' ? 'selected' : '' ?>>Students Only</option>
                                                            <option value="counselor" <?= $menu->role_access === 'counselor' ? 'selected' : '' ?>>Counselors Only</option>
                                                            <option value="admin" <?= $menu->role_access === 'admin' ? 'selected' : '' ?>>Admins Only</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active_<?= $menu->id ?>"
                                                               <?= $menu->is_active ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="is_active_<?= $menu->id ?>">Active</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save me-2"></i>Save Changes
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal for this menu item -->
                                <div class="modal fade" id="deleteMenuModal<?= $menu->id ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="<?= APP_URL ?>/admin/delete-menu/<?= $menu->id ?>">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">Delete Menu Item</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                                        <p>Are you sure you want to delete "<strong><?= htmlspecialchars($menu->title) ?></strong>"?</p>
                                                        <p class="text-danger"><small>This will also delete all child menu items!</small></p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Menu Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= APP_URL ?>/admin/create-menu">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" class="form-control" name="title" required placeholder="e.g., About Us">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL *</label>
                        <input type="text" class="form-control" name="url" required placeholder="e.g., /home/about">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon (Font Awesome class)</label>
                        <input type="text" class="form-control" name="icon" placeholder="e.g., fas fa-info-circle">
                        <div class="form-text">Visit <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a> for icon list</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Parent Menu</label>
                            <select class="form-select" name="parent_id">
                                <option value="">— No Parent (Top Level) —</option>
                                <?php foreach ($menus as $parentMenu): ?>
                                    <?php if (empty($parentMenu->parent_id)): ?>
                                        <option value="<?= $parentMenu->id ?>">
                                            <?= htmlspecialchars($parentMenu->title) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="0" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role Access</label>
                        <select class="form-select" name="role_access">
                            <option value="all">All Users</option>
                            <option value="guest">Guests Only</option>
                            <option value="student">Students Only</option>
                            <option value="counselor">Counselors Only</option>
                            <option value="admin">Admins Only</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active_new" checked>
                        <label class="form-check-label" for="is_active_new">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Menu Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
