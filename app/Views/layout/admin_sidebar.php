<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!-- Sidebar Brand -->
    <div class="sidebar-brand">
        <a href="<?= base_url('dashboard') ?>" class="brand-link">
            <!-- <img src="" alt="Logo" class="brand-image opacity-75 shadow"> -->
            <span class="brand-text fw-light">Adyatama School</span>
        </a>
    </div>

    <!-- Sidebar Wrapper -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                
                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= uri_string() == 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- MEDIA LIBRARY -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/media') ?>" class="nav-link <?= strpos(uri_string(), 'media') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-images"></i>
                        <p>Media Library</p>
                    </a>
                </li>

                <!-- MANAJEMEN POST (DROPDOWN) -->
                <li class="nav-item <?= (strpos(uri_string(), 'posts') !== false || strpos(uri_string(), 'categories') !== false || strpos(uri_string(), 'tags') !== false || strpos(uri_string(), 'galleries') !== false || strpos(uri_string(), 'extracurriculars') !== false || strpos(uri_string(), 'pages') !== false || strpos(uri_string(), 'comments') !== false) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (strpos(uri_string(), 'posts') !== false || strpos(uri_string(), 'categories') !== false || strpos(uri_string(), 'tags') !== false || strpos(uri_string(), 'galleries') !== false || strpos(uri_string(), 'extracurriculars') !== false || strpos(uri_string(), 'pages') !== false || strpos(uri_string(), 'comments') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Manajemen Post
                            <i class="nav-arrow fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/posts') ?>" class="nav-link <?= strpos(uri_string(), 'posts') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>Post</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/categories') ?>" class="nav-link <?= strpos(uri_string(), 'categories') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/tags') ?>" class="nav-link <?= strpos(uri_string(), 'tags') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-hashtag"></i>
                                <p>Tags</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/galleries') ?>" class="nav-link <?= strpos(uri_string(), 'galleries') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-photo-video"></i>
                                <p>Galeri</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/extracurriculars') ?>" class="nav-link <?= strpos(uri_string(), 'extracurriculars') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-running"></i>
                                <p>Ekstrakurikuler</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/pages') ?>" class="nav-link <?= strpos(uri_string(), 'pages') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Halaman</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/comments') ?>" class="nav-link <?= strpos(uri_string(), 'comments') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>Komentar</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- MANAJEMEN SEKOLAH (DROPDOWN) -->
                <li class="nav-item <?= (strpos(uri_string(), 'guru-staff') !== false || strpos(uri_string(), 'subscribers') !== false) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (strpos(uri_string(), 'guru-staff') !== false || strpos(uri_string(), 'subscribers') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-school"></i>
                        <p>
                            Manajemen Sekolah
                            <i class="nav-arrow fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/guru-staff') ?>" class="nav-link <?= strpos(uri_string(), 'guru-staff') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>Guru & Staff</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/subscribers') ?>" class="nav-link <?= strpos(uri_string(), 'subscribers') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Subscribers</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- USERS & ROLES (PENGGUNA) -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/users') ?>" class="nav-link <?= strpos(uri_string(), 'users') !== false ? 'active' : '' ?>">
                         <i class="nav-icon fas fa-user-shield"></i>
                         <p>Pengguna</p>
                    </a>
                </li>

                <!-- SETTINGS -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/settings') ?>" class="nav-link <?= strpos(uri_string(), 'settings') !== false ? 'active' : '' ?>">
                         <i class="nav-icon fas fa-cog"></i>
                         <p>Settings</p>
                    </a>
                </li>

                <!-- ACTIVITY LOG -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/activity-logs') ?>" class="nav-link <?= strpos(uri_string(), 'activity-logs') !== false ? 'active' : '' ?>">
                         <i class="nav-icon fas fa-history"></i>
                         <p>Activity Logs</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
