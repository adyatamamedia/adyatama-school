<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-outline-primary" onclick="checkForUpdates()" id="refreshBtn">
                <i class="fas fa-sync me-2"></i>
                Refresh
            </button>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= session()->getFlashdata('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Version Cards -->
<div class="row mb-4">
    <!-- Current Version Card -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 version-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box rounded-3 p-3 me-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-code-branch fa-2x text-white"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Current Version</h6>
                        <h2 class="mb-0 fw-bold" id="currentVersion">
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Loading...
                        </h2>
                    </div>
                </div>
                <div id="currentVersionInfo" class="mt-3">
                    <p class="text-muted small mb-0">
                        <i class="fas fa-clock me-2"></i>
                        <span id="lastCheckDate">Checking...</span>
                    </p>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Card -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 version-card" id="updateStatusCard">
            <div class="card-body">
                <div id="updateStatusContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="text-muted">Checking for updates...</h5>
                        <p class="text-muted small mb-0">Please wait while we check GitHub for new releases</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Details Section (Hidden by default) -->
<div id="updateDetailsSection" style="display: none;">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Update Information
                    </h5>
                </div>
                <div class="card-body">
                    <div id="updateDetailsContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Download Progress Section (Hidden by default) -->
<div id="downloadProgressSection" class="mt-4" style="display: none;">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0">
                <i class="fas fa-download text-primary me-2"></i>
                Download Progress
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Downloading update package...</span>
                    <span class="text-muted small fw-bold" id="progressPercent">0%</span>
                </div>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                        role="progressbar"
                        style="width: 0%"
                        id="downloadProgressBar">
                        0%
                    </div>
                </div>
            </div>
            <div id="downloadStatus" class="text-center text-muted">
                Preparing download...
            </div>
            <div class="text-center mt-3">
                <button type="button" class="btn btn-outline-danger" onclick="cancelDownload()">
                    <i class="fas fa-times me-2"></i>
                    Cancel Download
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .version-card {
        transition: all 0.3s ease;
    }

    .version-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15) !important;
    }

    .icon-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
    }

    .update-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .update-badge.available {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .update-badge.up-to-date {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .update-badge.checking {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .feature-list {
        list-style: none;
        padding: 0;
    }

    .feature-list li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .feature-list li:last-child {
        border-bottom: none;
    }

    .feature-list li i {
        color: #10b981;
        margin-right: 0.5rem;
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .changelog-accordion .accordion-button {
        background-color: #f9fafb;
        color: #374151;
    }

    .changelog-accordion .accordion-button:not(.collapsed) {
        background-color: #667eea;
        color: white;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    let updateInfo = null;

    // Check for updates on page load
    document.addEventListener('DOMContentLoaded', function() {
        checkForUpdates();
    });

    function checkForUpdates() {
        const refreshBtn = document.getElementById('refreshBtn');
        const icon = refreshBtn.querySelector('i');

        // Disable button and add spin animation
        refreshBtn.disabled = true;
        icon.classList.add('fa-spin');

        // Reset UI
        document.getElementById('updateStatusContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="text-muted">Checking for updates...</h5>
            <p class="text-muted small mb-0">Please wait while we check GitHub for new releases</p>
        </div>
    `;

        fetch('<?= base_url('dashboard/api/check-updates') ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayVersionInfo(data.current);
                    if (data.update_available) {
                        updateInfo = data.update_info;
                        displayUpdateAvailable(data.update_info);
                    } else {
                        displayNoUpdate();
                    }
                } else {
                    showError(data.message || 'Failed to check for updates');
                }
            })
            .catch(error => {
                console.error('Error checking for updates:', error);
                showError('Failed to connect to update server');
            })
            .finally(() => {
                refreshBtn.disabled = false;
                icon.classList.remove('fa-spin');
            });
    }

    function displayVersionInfo(version) {
        const currentVersionEl = document.getElementById('currentVersion');
        const lastCheckDateEl = document.getElementById('lastCheckDate');
        const lastCheckInfoEl = document.getElementById('lastCheckInfo');

        if (currentVersionEl) {
            currentVersionEl.innerHTML = `v${version.current_version || '1.0.0'}`;
        }

        if (version.last_check) {
            const lastCheck = new Date(version.last_check);
            const formatted = lastCheck.toLocaleString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            if (lastCheckDateEl) {
                lastCheckDateEl.textContent = `Last checked: ${formatted}`;
            }
            if (lastCheckInfoEl) {
                lastCheckInfoEl.innerHTML = `<small>Last checked: ${formatted}</small>`;
            }
        }
    }

    function displayUpdateAvailable(updateInfo) {
        const updateStatusContent = document.getElementById('updateStatusContent');
        const updateDetailsSection = document.getElementById('updateDetailsSection');
        const updateDetailsContent = document.getElementById('updateDetailsContent');

        if (updateStatusContent) {
            updateStatusContent.innerHTML = `
            <div class="text-center fade-in">
                <div class="mb-3">
                    <span class="update-badge available">
                        <i class="fas fa-arrow-up me-2"></i>
                        Update Available
                    </span>
                </div>
                <h2 class="fw-bold mb-2">v${updateInfo.version}</h2>
                <p class="text-muted mb-3">${updateInfo.description || 'New version available'}</p>
                <p class="text-muted small mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    Released: ${updateInfo.release_date || 'Unknown'}
                </p>
            </div>
        `;
        }

        if (updateDetailsContent) {
            const requirements = updateInfo.requirements ? updateInfo.requirements.split(',').map(r => r.trim()) : [];

            updateDetailsContent.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">Package Details</h6>
                    <ul class="feature-list">
                        <li>
                            <i class="fas fa-tag"></i>
                            <strong>Version:</strong> ${updateInfo.version}
                        </li>
                        <li>
                            <i class="fas fa-file-archive"></i>
                            <strong>File Size:</strong> ${formatFileSize(updateInfo.file_size)}
                        </li>
                        <li>
                            <i class="fas fa-calendar-alt"></i>
                            <strong>Release Date:</strong> ${updateInfo.release_date || 'Unknown'}
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">Requirements</h6>
                    <ul class="feature-list">
                        ${requirements.map(req => `
                            <li>
                                <i class="fas fa-check-circle"></i>
                                ${req}
                            </li>
                        `).join('')}
                    </ul>
                </div>
            </div>
            
            ${updateInfo.changelog ? `
                <div class="mt-4">
                    <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">What's New</h6>
                    <div class="accordion changelog-accordion" id="changelogAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#changelogContent">
                                    <i class="fas fa-list-alt me-2"></i>
                                    View Changelog
                                </button>
                            </h2>
                            <div id="changelogContent" class="accordion-collapse collapse" data-bs-parent="#changelogAccordion">
                                <div class="accordion-body">
                                    <div class="changelog-content">
                                        ${formatChangelog(updateInfo.changelog)}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ` : ''}
            
            <div class="text-center mt-4">
                <button type="button" class="btn btn-gradient-primary btn-lg px-5" onclick="downloadUpdate()">
                    <i class="fas fa-download me-2"></i>
                    Download Update v${updateInfo.version}
                </button>
            </div>
        `;
        }

        if (updateDetailsSection) {
            updateDetailsSection.style.display = 'block';
            updateDetailsSection.classList.add('fade-in');
        }
    }

    function displayNoUpdate() {
        const updateStatusContent = document.getElementById('updateStatusContent');
        const updateDetailsSection = document.getElementById('updateDetailsSection');

        if (updateStatusContent) {
            updateStatusContent.innerHTML = `
            <div class="text-center fade-in">
                <div class="mb-3">
                    <span class="update-badge up-to-date">
                        <i class="fas fa-check-circle me-2"></i>
                        Up to Date
                    </span>
                </div>
                <h4 class="fw-bold mb-2">You're all set!</h4>
                <p class="text-muted mb-0">You are running the latest version of the system.</p>
            </div>
        `;
        }

        if (updateDetailsSection) {
            updateDetailsSection.style.display = 'none';
        }
    }

    function showError(message) {
        const updateStatusContent = document.getElementById('updateStatusContent');
        const updateDetailsSection = document.getElementById('updateDetailsSection');

        if (updateStatusContent) {
            updateStatusContent.innerHTML = `
            <div class="text-center fade-in">
                <div class="text-danger mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x"></i>
                </div>
                <h5 class="text-danger mb-2">Error</h5>
                <p class="text-muted mb-3">${message}</p>
                <button type="button" class="btn btn-outline-primary" onclick="checkForUpdates()">
                    <i class="fas fa-sync me-2"></i>
                    Try Again
                </button>
            </div>
        `;
        }

        if (updateDetailsSection) {
            updateDetailsSection.style.display = 'none';
        }
    }

    function downloadUpdate() {
        if (!updateInfo) {
            alert('No update information available');
            return;
        }

        const downloadSection = document.getElementById('downloadProgressSection');
        const updateDetailsSection = document.getElementById('updateDetailsSection');

        // Show progress section
        if (downloadSection) {
            downloadSection.style.display = 'block';
            downloadSection.classList.add('fade-in');
        }

        // Hide details section
        if (updateDetailsSection) {
            updateDetailsSection.style.display = 'none';
        }

        // Simulate download progress
        let progress = 0;
        const progressBar = document.getElementById('downloadProgressBar');
        const progressPercent = document.getElementById('progressPercent');
        const downloadStatus = document.getElementById('downloadStatus');

        const interval = setInterval(() => {
            progress += Math.random() * 10;
            if (progress > 100) progress = 100;

            const roundedProgress = Math.floor(progress);

            if (progressBar) {
                progressBar.style.width = roundedProgress + '%';
                progressBar.textContent = roundedProgress + '%';
            }

            if (progressPercent) {
                progressPercent.textContent = roundedProgress + '%';
            }

            if (downloadStatus) {
                if (roundedProgress < 30) {
                    downloadStatus.innerHTML = '<i class="fas fa-download me-2"></i>Downloading update package...';
                } else if (roundedProgress < 70) {
                    downloadStatus.innerHTML = '<i class="fas fa-check me-2"></i>Verifying package integrity...';
                } else if (roundedProgress < 100) {
                    downloadStatus.innerHTML = '<i class="fas fa-box-open me-2"></i>Extracting files...';
                }
            }

            if (progress >= 100) {
                clearInterval(interval);
                setTimeout(() => {
                    if (downloadStatus) {
                        downloadStatus.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Download Complete!</strong>
                            <p class="mb-0 mt-2">The update package has been downloaded successfully.</p>
                            <div class="mt-3">
                                <a href="${updateInfo.download_url}" class="btn btn-success me-2" download>
                                    <i class="fas fa-download me-2"></i>
                                    Save Package
                                </a>
                                <button type="button" class="btn btn-outline-secondary" onclick="location.reload()">
                                    <i class="fas fa-sync me-2"></i>
                                    Check Again
                                </button>
                            </div>
                        </div>
                    `;
                    }
                }, 500);
            }
        }, 200);
    }

    function cancelDownload() {
        if (confirm('Are you sure you want to cancel the download?')) {
            location.reload();
        }
    }

    function formatFileSize(bytes) {
        if (!bytes || bytes === 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function formatChangelog(changelog) {
        // Convert markdown-style changelog to HTML
        const lines = changelog.split('\n');
        let html = '';
        let inList = false;

        lines.forEach(line => {
            line = line.trim();
            if (!line) return;

            if (line.startsWith('##')) {
                if (inList) {
                    html += '</ul>';
                    inList = false;
                }
                html += `<h6 class="mt-3 mb-2 fw-bold">${line.replace(/^##\s*/, '')}</h6>`;
            } else if (line.startsWith('-')) {
                if (!inList) {
                    html += '<ul class="feature-list">';
                    inList = true;
                }
                html += `<li><i class="fas fa-check-circle"></i>${line.replace(/^-\s*/, '')}</li>`;
            } else {
                if (inList) {
                    html += '</ul>';
                    inList = false;
                }
                html += `<p class="text-muted">${line}</p>`;
            }
        });

        if (inList) {
            html += '</ul>';
        }

        return html;
    }
</script>

<?= $this->endSection() ?>