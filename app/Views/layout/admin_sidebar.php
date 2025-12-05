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
                
                <!-- DASHBOARD (All roles) -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= nav_active('dashboard', 'exact') ?>">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- MEDIA LIBRARY (Admin & Operator only) -->
                <?php if (can_access_menu('media')): ?>
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/media') ?>" class="nav-link <?= nav_active('media') ?>">
                        <i class="nav-icon fas fa-photo-video"></i>
                        <p>Media Library</p>
                    </a>
                </li>
                <?php endif; ?>

                <!-- MANAJEMEN TERMS (DROPDOWN) (Admin & Operator only) -->
                <?php if (can_access_menu('categories')): ?>
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
                <?php endif; ?>

                <!-- MANAJEMEN ARTIKEL (DROPDOWN) (All roles) -->
                <?php if (can_access_menu('posts')): ?>
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
                <?php endif; ?>

                <!-- MANAJEMEN HALAMAN (DROPDOWN) (Admin & Operator only) -->
                <?php if (can_access_menu('pages')): ?>
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
                <?php endif; ?>

                <!-- MANAJEMEN GALERI (DROPDOWN) (All roles) -->
                <?php if (can_access_menu('galleries')): ?>
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
                <?php endif; ?>

                <!-- MANAJEMEN GURU/STAFF (DROPDOWN) (Admin & Operator only) -->
                <?php if (can_access_menu('guru-staff')): ?>
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
                <?php endif; ?>

                <!-- PENDAFTARAN (Admin & Operator only) -->
                <?php if (can_access_menu('pendaftaran')): ?>
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/pendaftaran') ?>" class="nav-link <?= nav_active('pendaftaran') ?>">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Pendaftaran</p>
                    </a>
                </li>
                <?php endif; ?>

                <!-- KOMENTAR (Admin & Operator only) -->
                <?php if (can_access_menu('comments')): ?>
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/comments') ?>" class="nav-link <?= nav_active('comments') ?>">
                        <i class="nav-icon fas fa-comment-dots"></i>
                        <p>Komentar</p>
                    </a>
                </li>
                <?php endif; ?>

                <!-- SUBSCRIBER (Admin & Operator only) -->
                <?php if (can_access_menu('subscribers')): ?>
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/subscribers') ?>" class="nav-link <?= nav_active('subscribers') ?>">
                        <i class="nav-icon fas fa-envelope-open-text"></i>
                        <p>Subscriber</p>
                    </a>
                </li>
                <?php endif; ?>

                <!-- PENGGUNA (Admin & Operator) -->
                <?php if (in_array(current_user()->role, ['admin', 'operator'])): ?>
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/users') ?>" class="nav-link <?= nav_active('users') ?>">
                         <i class="nav-icon fas fa-users-cog"></i>
                         <p>Pengguna</p>
                    </a>
                </li>
                <?php endif; ?>

                <!-- SETTINGS (Admin & Operator only) -->
                <?php if (can_access_menu('settings')): ?>
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/settings') ?>" class="nav-link <?= nav_active('settings') ?>">
                         <i class="nav-icon fas fa-sliders"></i>
                         <p>Settings</p>
                    </a>
                </li>
                <?php endif; ?>

                <!-- ACTIVITY LOG (Admin & Operator only) -->
                <?php if (can_access_menu('activity-logs')): ?>
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/activity-logs') ?>" class="nav-link <?= nav_active('activity-logs') ?>">
                         <i class="nav-icon fas fa-clock"></i>
                         <p>Activity Log</p>
                    </a>
                </li>
                <?php endif; ?>

                <!-- SYSTEM UPDATE (Admin only) -->
                <?php if (current_user()->role === 'admin'): ?>
                <li class="nav-item">
                    <a href="<?= base_url('dashboard/update') ?>" class="nav-link <?= nav_active('update') ?>">
                        <i class="nav-icon fas fa-sync-alt"></i>
                        <p>System Update</p>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>
