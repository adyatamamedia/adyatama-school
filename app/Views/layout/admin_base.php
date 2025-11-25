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
    
    <style>
        /* Custom overrides if needed */
        .content-wrapper {
            background-color: #f4f6f9;
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

</div>

<!-- AdminLTE 4 & Bootstrap 5 JS -->
<!-- Note: AdminLTE 4 bundle includes Bootstrap 5 JS usually, or relies on it -->
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/adminlte/js/adminlte.min.js') ?>"></script>

<!-- OverlayScrollbars if needed (usually included in AdminLTE plugins) -->
<!-- For now, basic functionality -->

</body>
</html>
