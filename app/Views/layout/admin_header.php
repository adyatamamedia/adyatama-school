<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <!-- Start Navbar Links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="<?= base_url() ?>" class="nav-link" target="_blank">View Site</a>
            </li>
        </ul>

        <!-- End Navbar Links -->
        <ul class="navbar-nav ms-auto">
            <!-- User Menu -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode(current_user()->fullname ?? 'Admin') ?>&background=random" class="user-image rounded-circle shadow" alt="User Image">
                    <span class="d-none d-md-inline"><?= esc(current_user()->fullname ?? 'User') ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!-- User Image -->
                    <li class="user-header text-bg-primary">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode(current_user()->fullname ?? 'Admin') ?>&background=random" class="rounded-circle shadow" alt="User Image">
                        <p>
                            <?= esc(current_user()->fullname ?? 'User') ?>
                            <small><?= esc(current_user()->role ?? 'Guest') ?></small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                        <a href="<?= base_url('logout') ?>" class="btn btn-default btn-flat float-end">Sign out</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
