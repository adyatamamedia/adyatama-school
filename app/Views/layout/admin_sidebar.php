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

                <!-- MANAJEMEN TERMS (DROPDOWN) -->
                <li class="nav-item <?= (strpos(uri_string(), 'categories') !== false || strpos(uri_string(), 'tags') !== false || strpos(uri_string(), 'extracurriculars') !== false) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (strpos(uri_string(), 'categories') !== false || strpos(uri_string(), 'tags') !== false || strpos(uri_string(), 'extracurriculars') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            Manajemen Terms
                            <i class="nav-arrow fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/categories') ?>" class="nav-link <?= strpos(uri_string(), 'categories') !== false ? 'active' : '' ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/tags') ?>" class="nav-link <?= strpos(uri_string(), 'tags') !== false ? 'active' : '' ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Tags</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/extracurriculars') ?>" class="nav-link <?= strpos(uri_string(), 'extracurriculars') !== false ? 'active' : '' ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Ekstrakurikuler</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- MANAJEMEN ARTIKEL (DROPDOWN) -->
                <li class="nav-item <?= strpos(uri_string(), 'posts') !== false ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos(uri_string(), 'posts') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>
                            Manajemen Artikel
                            <i class="nav-arrow fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/posts') ?>" class="nav-link <?= uri_string() == 'dashboard/posts' ? 'active' : '' ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Daftar Artikel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/posts/new') ?>" class="nav-link <?= uri_string() == 'dashboard/posts/new' ? 'active' : '' ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Buat Artikel</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- MANAJEMEN HALAMAN (DROPDOWN) -->
                <li class="nav-item <?= strpos(uri_string(), 'pages') !== false ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos(uri_string(), 'pages') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Manajemen Halaman
                            <i class="nav-arrow fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/pages') ?>" class="nav-link <?= uri_string() == 'dashboard/pages' ? 'active' : '' ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Daftar Halaman</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/pages/new') ?>" class="nav-link <?= uri_string() == 'dashboard/pages/new' ? 'active' : '' ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Buat Halaman</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- MANAJEMEN GALERI (DROPDOWN) -->
                <li class="nav-item <?= strpos(uri_string(), 'galleries') !== false ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos(uri_string(), 'galleries') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-photo-video"></i>
                        <p>
                            Manajemen Galeri
                            <i class="nav-arrow fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/galleries') ?>" class="nav-link <?= uri_string() == 'dashboard/galleries' ? 'active' : '' ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Daftar Galeri</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/galleries/new') ?>" class="nav-link <?= uri_string() == 'dashboard/galleries/new' ? 'active' : '' ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Tambah Galeri</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- PENDAFTARAN -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/pendaftaran') ?>" class="nav-link <?= strpos(uri_string(), 'pendaftaran') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <p>Pendaftaran</p>
                    </a>
                </li>

                <!-- MANAJEMEN GURU/STAFF (DROPDOWN) -->
                <li class="nav-item <?= strpos(uri_string(), 'guru-staff') !== false ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos(uri_string(), 'guru-staff') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>
                            Manajemen Guru/Staff
                            <i class="nav-arrow fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/guru-staff') ?>" class="nav-link <?= uri_string() == 'dashboard/guru-staff' ? 'active' : '' ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Daftar Guru/Staff</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/guru-staff/new') ?>" class="nav-link <?= uri_string() == 'dashboard/guru-staff/new' ? 'active' : '' ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Input Guru/Staff</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- KOMENTAR -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/comments') ?>" class="nav-link <?= strpos(uri_string(), 'comments') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Komentar</p>
                    </a>
                </li>

                <!-- SUBSCRIBER -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/subscribers') ?>" class="nav-link <?= strpos(uri_string(), 'subscribers') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Subscriber</p>
                    </a>
                </li>

                <!-- PENGGUNA -->
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
                         <p>Activity Log</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<style>
/* Indent dropdown items */
.nav-treeview .nav-link {
    padding-left: 2.5rem !important;
}

.nav-treeview .nav-icon {
    font-size: 0.8rem;
    opacity: 0.7;
}

/* Fix dropdown when sidebar is collapsed */
.sidebar-collapse .nav-treeview {
    display: none !important;
}

.sidebar-collapse .nav-arrow {
    display: none !important;
}

.sidebar-collapse .nav-item.menu-open > .nav-link {
    background-color: transparent !important;
}

/* Show dropdown on hover when collapsed */
.sidebar-collapse .nav-item:hover > .nav-treeview {
    display: block !important;
    position: fixed;
    left: 60px;
    background: var(--bs-body-bg);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 0.25rem;
    padding: 0.5rem 0;
    min-width: 200px;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    z-index: 1050;
}

.sidebar-collapse .nav-item:hover > .nav-treeview .nav-link {
    padding-left: 1rem !important;
}

.sidebar-collapse .nav-item:hover > .nav-treeview .nav-item {
    width: 100%;
}
</style>
