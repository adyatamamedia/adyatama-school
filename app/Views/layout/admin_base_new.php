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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <style>
        /* Custom dashboard styles */
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1rem 1rem;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card .card-body {
            padding: 1.5rem;
            position: relative;
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2.5rem;
            opacity: 0.2;
        }

        .stat-card.primary { background: linear-gradient(45deg, #007bff, #0056b3); color: white; }
        .stat-card.success { background: linear-gradient(45deg, #28a745, #1e7e34); color: white; }
        .stat-card.info { background: linear-gradient(45deg, #17a2b8, #117a8b); color: white; }
        .stat-card.warning { background: linear-gradient(45deg, #ffc107, #d39e00); color: white; }

        .chart-container {
            position: relative;
            height: 300px;
            margin: 1rem 0;
        }

        .activity-item {
            border-left: 3px solid #007bff;
            padding-left: 1rem;
            margin-bottom: 1rem;
        }

        .quick-action-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .quick-action-card:hover {
            text-decoration: none;
            color: inherit;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .metric-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
        }

        .metric-positive { background-color: #d4edda; color: #155724; }
        .metric-negative { background-color: #f8d7da; color: #721c24; }
        .metric-neutral { background-color: #e2e3e5; color: #383d41; }

        /* Fix fullscreen mode background */
        .note-editor.note-frame.fullscreen {
            background-color: white !important;
        }

        /* Fix double dropdown arrow in Summernote Lite */
        .note-btn.dropdown-toggle::after {
            display: none;
        }

        /* Animated numbers */
        .counter {
            display: inline-block;
            font-size: 2rem;
            font-weight: bold;
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
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

        <!-- Custom Dashboard JavaScript -->
        <script>
            // Animated Counter Function
            function animateCounter(element, target, duration = 2000) {
                const start = 0;
                const increment = target / (duration / 16);
                let current = start;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    element.textContent = Math.floor(current).toLocaleString();
                }, 16);
            }

            // Initialize counters on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Animate statistics counters
                document.querySelectorAll('.counter').forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target'));
                    if (!isNaN(target)) {
                        animateCounter(counter, target);
                    }
                });

                // Initialize all charts
                initCharts();
            });

            // Chart initialization function
            function initCharts() {
                // Chart configuration defaults
                Chart.defaults.font.family = "'Source Sans Pro', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
                Chart.defaults.color = '#6c757d';

                console.log('Dashboard initialized with Chart.js');
            }

            // Auto-refresh dashboard data (every 30 seconds)
            setInterval(function() {
                console.log('Auto-refreshing dashboard data...');
                // Add AJAX call here to refresh data
            }, 30000);

            // Utility function to format numbers
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // Add loading states
            function showLoading(element) {
                element.classList.add('skeleton');
                element.style.pointerEvents = 'none';
            }

            function hideLoading(element) {
                element.classList.remove('skeleton');
                element.style.pointerEvents = 'auto';
            }
        </script>

    </div>
</body>

</html>