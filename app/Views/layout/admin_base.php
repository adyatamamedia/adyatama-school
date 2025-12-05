<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - Adyatama School CMS</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('favicon_adyatama1.png') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon_adyatama1.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('favicon_adyatama1.png') ?>">

    <!-- Google Font: Inter -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    <!-- Font Awesome 6 - Multiple CDN fallback -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" />
    <!-- AdminLTE 4 CSS -->
    <link href="<?= base_url('assets/vendor/adminlte/css/adminlte.min.css') ?>" rel="stylesheet">
    <!-- Custom Sidebar CSS -->
    <link rel="stylesheet" href="<?= base_url('css/admin_sidebar.css') ?>">
    <!-- Summernote CSS (Lite version for better BS5 compatibility) -->
    <link href="/assets/vendor/summernote/css/summernote-lite.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        /* ===== Global Font: Inter ===== */
        :root {
            --bs-primary: #667eea;
            --bs-primary-rgb: 102, 126, 234;
            --bs-link-color: #667eea;
            --bs-link-hover-color: #5a67d8;
        }
        
        /* Override Bootstrap Button Variables for consistency */
        .btn-primary {
            --bs-btn-bg: #667eea;
            --bs-btn-border-color: #667eea;
            --bs-btn-hover-bg: #5a67d8;
            --bs-btn-hover-border-color: #5a67d8;
            --bs-btn-active-bg: #4c51bf;
            --bs-btn-active-border-color: #4c51bf;
        }

        .bg-primary {
            background-color: #667eea !important;
        }

        .text-primary {
            color: #667eea !important;
        }

        /* Compact Table Font */
        .table {
            font-size: 0.85rem;
        }
        .table th {
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
            font-weight: 400;
            font-size: 0.9rem; /* Compact global font size */
            font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Force Inter on all elements */
        h1, h2, h3, h4, h5, h6,
        p, span, div, a, button, input, textarea, select,
        .btn, .form-control, .card, .modal, .dropdown-menu,
        .nav-link, .navbar, .sidebar, .brand-text {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }

        /* ===== FontAwesome Fix for Global Font Override ===== */
        /* Reset font-family for icons that were overridden by the global rule above */
        .fa, .fas, .fa-solid, .nav-icon.fas, .nav-icon.fa-solid {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;
        }
        
        .far, .fa-regular, .nav-icon.far, .nav-icon.fa-regular {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 400 !important;
        }
        
        .fab, .fa-brands, .nav-icon.fab, .nav-icon.fa-brands {
            font-family: "Font Awesome 6 Brands" !important;
            font-weight: 400 !important;
        }
    </style>
    
    <script>
        // Ensure jQuery is globally available immediately
        if (typeof jQuery !== 'undefined') {
            window.jQuery = jQuery;
            window.$ = jQuery;
        } else {
            console.error('jQuery failed to load!');
        }

        // Wait for document to be ready
        $(document).ready(function() {
            console.log('jQuery document ready triggered');
            console.log('jQuery version:', $.fn.jquery);
        });
    </script>

    <style>
        /* Fix fullscreen mode background */
        .note-editor.note-frame.fullscreen {
            background-color: white !important;
        }
        
        /* Fix double dropdown arrow in Summernote Lite */
        .note-btn.dropdown-toggle::after {
            display: none;
        }
    </style>
    
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary sidebar-mini"> <!-- AdminLTE 4 Body Classes -->

    <div class="app-wrapper">

        <!-- Header -->
        <?= $this->include('layout/admin_header') ?>

        <!-- Sidebar -->
        <?= $this->include('layout/admin_sidebar') ?>

        <!-- Main Content -->
        <main class="app-main">

            <!-- App Content Header -->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0"><?= $title ?? 'Dashboard' ?></h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?= $title ?? 'Dashboard' ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- App Content -->
            <div class="app-content">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>

        </main>

        <!-- Footer -->
        <?= $this->include('layout/admin_footer') ?>

    </div><!-- /.app-wrapper -->

    <!-- Required JavaScript Libraries -->
    <!-- Bootstrap JS -->
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        if (typeof bootstrap !== 'undefined') {
            console.log('Bootstrap JS loaded successfully');
        } else {
            console.error('Bootstrap JS failed to load!');
        }
    </script>

    <!-- AdminLTE JS -->
    <script src="/assets/vendor/adminlte/js/adminlte.min.js"></script>
    <script>
        // AdminLTE 4 doesn't create global AdminLTE object, so we check differently
        if (typeof bootstrap !== 'undefined' && jQuery) {
            console.log('AdminLTE JS loaded successfully (detected via jQuery and Bootstrap)');
            // Initialize AdminLTE if available
            if (typeof $.AdminLTE === 'function') {
                $.AdminLTE.start();
                console.log('AdminLTE initialized');
            }
        } else {
            console.warn('AdminLTE JS may not be fully loaded, but continuing...');
        }
    </script>

    <!-- Custom Sidebar JS -->
    <script src="<?= base_url('js/admin_sidebar.js') ?>"></script>
    
    <!-- Summernote JS (Lite version) -->
    <script src="/assets/vendor/summernote/js/summernote-lite.min.js"></script>
    <script>
        if (typeof $.fn.summernote !== 'undefined') {
            console.log('Summernote Lite JS loaded successfully');
        } else {
            console.error('Summernote Lite JS failed to load!');
            // Try CDN fallback for Lite version
            console.log('Trying CDN fallback...');
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js';
            script.onload = function() {
                console.log('Summernote Lite CDN loaded successfully');
                // Reinitialize Summernote if needed
                setTimeout(initializeSummernote, 500);
            };
            script.onerror = function() {
                console.error('CDN fallback also failed!');
            };
            document.head.appendChild(script);
        }
    </script>

</body>

</html>