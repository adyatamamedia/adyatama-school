<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="h3">Dashboard</h1>
        <p class="text-muted">Welcome back, <?= esc(current_user()->fullname) ?>!</p>
    </div>
</div>

<div class="row">
    <!-- Stats Card 1 -->
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Total Posts</h6>
                <h2 class="fw-bold text-primary"><?= $stats['posts'] ?? 0 ?></h2>
                <p class="small text-muted mb-0">Published articles</p>
            </div>
        </div>
    </div>

    <!-- Stats Card 2 -->
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Registered Users</h6>
                <h2 class="fw-bold text-success"><?= $stats['users'] ?? 0 ?></h2>
                <p class="small text-muted mb-0">Teachers & Staff</p>
            </div>
        </div>
    </div>

    <!-- Stats Card 3 -->
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Total Views</h6>
                <h2 class="fw-bold text-info"><?= $stats['views'] ?? 0 ?></h2>
                <p class="small text-muted mb-0">This month</p>
            </div>
        </div>
    </div>
    
    <!-- Stats Card 4 -->
     <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">System Status</h6>
                <h2 class="fw-bold text-warning">OK</h2>
                <p class="small text-muted mb-0">All services running</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Recent Activities</h5>
            </div>
            <div class="card-body">
                <p class="text-muted text-center py-5">No recent activities found.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action">Create New Post</a>
                <a href="#" class="list-group-item list-group-item-action">Upload Media</a>
                <a href="#" class="list-group-item list-group-item-action">Manage Users</a>
                <a href="#" class="list-group-item list-group-item-action text-danger">System Settings</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
