<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - Adyatama School CMS</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE 4 CSS -->
    <link href="<?= base_url('assets/vendor/adminlte/css/adminlte.min.css') ?>" rel="stylesheet">
    <!-- Summernote CSS (Lite version for better BS5 compatibility) -->
    <link href="/assets/vendor/summernote/css/summernote-lite.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    </div>
</body>

</html>