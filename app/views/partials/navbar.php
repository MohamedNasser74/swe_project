<!-- Navigation Partial - Dynamic Menu -->
<?php
// Initialize the MenuHelper to render dynamic navigation
$menuHelper = new MenuHelper();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?= APP_URL ?>">
            <i class="fas fa-graduation-cap me-2"></i><?= APP_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Dynamic Menu Items from Database -->
                <?= $menuHelper->renderMenu() ?>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?= $_SESSION['user_name'] ?? 'User' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/admin/dashboard">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                            <?php elseif ($_SESSION['user_role'] === 'counselor'): ?>
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/counselor/dashboard">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                            <?php elseif ($_SESSION['user_role'] === 'student'): ?>
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/student/dashboard">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= APP_URL ?>/auth/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/auth/login">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/auth/register">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    </li>
                <?php endif; ?>
                
                <!-- Dark Mode Toggle -->
                <li class="nav-item">
                    <button class="btn btn-sm btn-outline-light ms-2" id="darkModeToggle" title="Toggle Dark Mode">
                        <i class="fas fa-moon" id="darkModeIcon"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
/* Dropdown submenu support for multi-level menus */
.dropdown-submenu {
    position: relative;
}

.dropdown-submenu > .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -1px;
}

.dropdown-submenu:hover > .dropdown-menu {
    display: block;
}

/* Arrow indicator for submenu items */
.dropdown-submenu > .dropdown-toggle::after {
    display: inline-block;
    margin-left: 0.5em;
    vertical-align: middle;
    content: "";
    border-top: 0.3em solid transparent;
    border-right: 0;
    border-bottom: 0.3em solid transparent;
    border-left: 0.3em solid;
}
</style>
