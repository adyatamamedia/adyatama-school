<!-- Custom Sidebar CSS -->
<link rel="stylesheet" href="<?= base_url('css/admin_sidebar.css') ?>">

<aside class="app-sidebar modern-sidebar shadow" data-bs-theme="dark">
    <!-- Sidebar Brand -->
    <div class="sidebar-brand">
        <a href="<?= base_url('dashboard') ?>" class="brand-link">
            <img src="<?= base_url('favicon_adyatama1.png') ?>" alt="Adyatama School Logo" class="brand-image shadow">
            <span class="brand-text fw-light">Adyatama School</span>
        </a>
    </div>

    <!-- Sidebar Wrapper -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                
                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= nav_active('dashboard', 'exact') ?>">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- MEDIA LIBRARY -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/media') ?>" class="nav-link <?= nav_active('media') ?>">
                        <i class="nav-icon fas fa-photo-video"></i>
                        <p>Media Library</p>
                    </a>
                </li>

                <!-- MANAJEMEN TERMS (DROPDOWN) -->
                <li class="nav-item <?= nav_menu_open(['categories', 'tags', 'extracurriculars']) ?>">
                    <a href="#" class="nav-link <?= nav_active(['categories', 'tags', 'extracurriculars']) ?>">
                        <i class="nav-icon fas fa-bookmark"></i>
                        <p>
                            Manajemen Terms
                            <i class="nav-arrow fas fa-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/categories') ?>" class="nav-link <?= nav_active('categories') ?>">
                                <i class="nav-icon fas fa-folder"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/tags') ?>" class="nav-link <?= nav_active('tags') ?>">
                                <i class="nav-icon fas fa-tag"></i>
                                <p>Tags</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/extracurriculars') ?>" class="nav-link <?= nav_active('extracurriculars') ?>">
                                <i class="nav-icon fas fa-futbol"></i>
                                <p>Ekstrakurikuler</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- MANAJEMEN ARTIKEL (DROPDOWN) -->
                <li class="nav-item <?= nav_menu_open('posts') ?>">
                    <a href="#" class="nav-link <?= nav_active('posts') ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Manajemen Artikel
                            <i class="nav-arrow fas fa-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/posts') ?>" class="nav-link <?= nav_active('dashboard/posts', 'exact') ?>">
                                <i class="nav-icon fas fa-list"></i>
                                <p>Daftar Artikel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/posts/new') ?>" class="nav-link <?= nav_active('dashboard/posts/new', 'exact') ?>">
                                <i class="nav-icon fas fa-plus-circle"></i>
                                <p>Buat Artikel</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- MANAJEMEN HALAMAN (DROPDOWN) -->
                <li class="nav-item <?= nav_menu_open('pages') ?>">
                    <a href="#" class="nav-link <?= nav_active('pages') ?>">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Manajemen Halaman
                            <i class="nav-arrow fas fa-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/pages') ?>" class="nav-link <?= nav_active('dashboard/pages', 'exact') ?>">
                                <i class="nav-icon fas fa-list"></i>
                                <p>Daftar Halaman</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/pages/new') ?>" class="nav-link <?= nav_active('dashboard/pages/new', 'exact') ?>">
                                <i class="nav-icon fas fa-plus-circle"></i>
                                <p>Buat Halaman</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- MANAJEMEN GALERI (DROPDOWN) -->
                <li class="nav-item <?= nav_menu_open('galleries') ?>">
                    <a href="#" class="nav-link <?= nav_active('galleries') ?>">
                        <i class="nav-icon fas fa-images"></i>
                        <p>
                            Manajemen Galeri
                            <i class="nav-arrow fas fa-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/galleries') ?>" class="nav-link <?= nav_active('dashboard/galleries', 'exact') ?>">
                                <i class="nav-icon fas fa-list"></i>
                                <p>Daftar Galeri</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/galleries/new') ?>" class="nav-link <?= nav_active('dashboard/galleries/new', 'exact') ?>">
                                <i class="nav-icon fas fa-plus-circle"></i>
                                <p>Tambah Galeri</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- PENDAFTARAN -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/pendaftaran') ?>" class="nav-link <?= nav_active('pendaftaran') ?>">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Pendaftaran</p>
                    </a>
                </li>

                <!-- MANAJEMEN GURU/STAFF (DROPDOWN) -->
                <li class="nav-item <?= nav_menu_open('guru-staff') ?>">
                    <a href="#" class="nav-link <?= nav_active('guru-staff') ?>">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>
                            Manajemen Guru/Staff
                            <i class="nav-arrow fas fa-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/guru-staff') ?>" class="nav-link <?= nav_active('dashboard/guru-staff', 'exact') ?>">
                                <i class="nav-icon fas fa-list"></i>
                                <p>Daftar Guru/Staff</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard/guru-staff/new') ?>" class="nav-link <?= nav_active('dashboard/guru-staff/new', 'exact') ?>">
                                <i class="nav-icon fas fa-plus-circle"></i>
                                <p>Input Guru/Staff</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- KOMENTAR -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/comments') ?>" class="nav-link <?= nav_active('comments') ?>">
                        <i class="nav-icon fas fa-comment-dots"></i>
                        <p>Komentar</p>
                    </a>
                </li>

                <!-- SUBSCRIBER -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/subscribers') ?>" class="nav-link <?= nav_active('subscribers') ?>">
                        <i class="nav-icon fas fa-envelope-open-text"></i>
                        <p>Subscriber</p>
                    </a>
                </li>

                <!-- PENGGUNA -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/users') ?>" class="nav-link <?= nav_active('users') ?>">
                         <i class="nav-icon fas fa-users-cog"></i>
                         <p>Pengguna</p>
                    </a>
                </li>

                <!-- SETTINGS -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/settings') ?>" class="nav-link <?= nav_active('settings') ?>">
                         <i class="nav-icon fas fa-sliders"></i>
                         <p>Settings</p>
                    </a>
                </li>

                <!-- ACTIVITY LOG -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/activity-logs') ?>" class="nav-link <?= nav_active('activity-logs') ?>">
                         <i class="nav-icon fas fa-clock"></i>
                         <p>Activity Log</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- Custom Sidebar JS -->
<script src="<?= base_url('js/admin_sidebar.js') ?>"></script>
