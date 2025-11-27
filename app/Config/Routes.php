<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Redirect root to dashboard
$routes->addRedirect('/', 'dashboard');

// Auth Routes
$routes->get('auth/login', 'Auth::login'); // Fallback for direct access
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// Dashboard Routes (Protected)
$routes->group('dashboard', ['filter' => 'auth', 'namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->get('/', 'DashboardNew::index');
    $routes->get("dev", "DashboardNew::indexDev"); // Development dengan mock data

    // Categories Routes
    $routes->get('categories', 'Categories::index');
    $routes->get('categories/new', 'Categories::new');
    $routes->post('categories/create', 'Categories::create');
    $routes->get('categories/edit/(:num)', 'Categories::edit/$1');
    $routes->post('categories/update/(:num)', 'Categories::update/$1');
    $routes->get('categories/delete/(:num)', 'Categories::delete/$1');
    $routes->post('categories/bulk-delete', 'Categories::bulkDelete');
    // Posts Routes
    $routes->get('posts', 'Posts::index');
    $routes->get('posts/new', 'Posts::new');
    $routes->post('posts/create', 'Posts::create');
    $routes->get('posts/edit/(:num)', 'Posts::edit/$1');
    $routes->post('posts/update/(:num)', 'Posts::update/$1');
    $routes->get('posts/delete/(:num)', 'Posts::delete/$1');
    $routes->post('posts/bulk-delete', 'Posts::bulkDelete');
    $routes->post('posts/bulk-draft', 'Posts::bulkDraft');
    $routes->post('posts/bulk-publish', 'Posts::bulkPublish');
    // Media Routes
    $routes->get('media', 'Media::index');
    $routes->post('media/upload', 'Media::upload');
    $routes->post('media/upload-multiple', 'Media::uploadMultiple');
    $routes->post('media/bulk-delete', 'Media::bulkDelete');
    $routes->post('media/update/(:num)', 'Media::update/$1');
    $routes->get('media/delete/(:num)', 'Media::delete/$1');
    $routes->get('api/media', 'Media::getMediaJson');

    // Summernote Upload Routes
    $routes->post('summernote/upload', 'SummernoteUpload::upload');

    // Guru-Staff Routes
    $routes->get('guru-staff', 'GuruStaff::index');
    $routes->get('guru-staff/new', 'GuruStaff::new');
    $routes->post('guru-staff/create', 'GuruStaff::create');
    $routes->get('guru-staff/edit/(:num)', 'GuruStaff::edit/$1');
    $routes->post('guru-staff/update/(:num)', 'GuruStaff::update/$1');
    $routes->get('guru-staff/delete/(:num)', 'GuruStaff::delete/$1');
    $routes->post('guru-staff/bulk-delete', 'GuruStaff::bulkDelete');

    // Extracurriculars Routes
    $routes->get('extracurriculars', 'Extracurriculars::index');
    $routes->get('extracurriculars/new', 'Extracurriculars::new');
    $routes->post('extracurriculars/create', 'Extracurriculars::create');
    $routes->get('extracurriculars/edit/(:num)', 'Extracurriculars::edit/$1');
    $routes->post('extracurriculars/update/(:num)', 'Extracurriculars::update/$1');
    $routes->get('extracurriculars/delete/(:num)', 'Extracurriculars::delete/$1');
    $routes->post('extracurriculars/bulk-delete', 'Extracurriculars::bulkDelete');

    // Subscribers Routes
    $routes->get('subscribers', 'Subscribers::index');
    $routes->get('subscribers/toggle/(:num)', 'Subscribers::toggle/$1');
    $routes->get('subscribers/delete/(:num)', 'Subscribers::delete/$1');
    $routes->post('subscribers/bulk-delete', 'Subscribers::bulkDelete');

    // Static Pages Routes
    $routes->get('pages', 'Pages::index');
    $routes->get('pages/new', 'Pages::new');
    $routes->post('pages/create', 'Pages::create');
    $routes->get('pages/edit/(:num)', 'Pages::edit/$1');
    $routes->post('pages/update/(:num)', 'Pages::update/$1');
    $routes->get('pages/delete/(:num)', 'Pages::delete/$1');
    $routes->post('pages/bulk-delete', 'Pages::bulkDelete');
    $routes->post('pages/bulk-draft', 'Pages::bulkDraft');
    $routes->post('pages/bulk-publish', 'Pages::bulkPublish');
    // Galleries Routes
    $routes->get('galleries', 'Galleries::index');
    $routes->get('galleries/new', 'Galleries::new');
    $routes->post('galleries/create', 'Galleries::create');
    $routes->get('galleries/edit/(:num)', 'Galleries::edit/$1');
    $routes->post('galleries/update/(:num)', 'Galleries::update/$1');
    $routes->get('galleries/delete/(:num)', 'Galleries::delete/$1');
    $routes->post('galleries/bulk-delete', 'Galleries::bulkDelete');
    // Gallery Items
    $routes->get('galleries/items/(:num)', 'Galleries::items/$1');
    $routes->post('galleries/items/add/(:num)', 'Galleries::addItem/$1');
    $routes->get('galleries/items/delete/(:num)', 'Galleries::deleteItem/$1');

    // Student Applications (Pendaftaran) Routes
    $routes->get('pendaftaran', 'StudentApplications::index');
    $routes->get('pendaftaran/view/(:num)', 'StudentApplications::view/$1');
    $routes->post('pendaftaran/update-status/(:num)', 'StudentApplications::updateStatus/$1');
    $routes->get('pendaftaran/delete/(:num)', 'StudentApplications::delete/$1');
    $routes->post('pendaftaran/bulk-delete', 'StudentApplications::bulkDelete');
    $routes->get('pendaftaran/export-excel', 'StudentApplications::exportExcel');
    $routes->get('pendaftaran/export-doc/(:num)', 'StudentApplications::exportDoc/$1');

    // Tags Routes (General View)
    $routes->get('tags', 'Tags::index');
    $routes->get('tags/delete/(:segment)', 'Tags::delete/$1');
    $routes->post('tags/bulk-delete', 'Tags::bulkDelete');

    // User Management
    $routes->get('users', 'Users::index');
    $routes->get('users/new', 'Users::new');
    $routes->post('users/create', 'Users::create');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
    $routes->get('users/delete/(:num)', 'Users::delete/$1');
    $routes->post('users/bulk-delete', 'Users::bulkDelete');

    // Settings Routes
    $routes->get('settings', 'Settings::index');
    $routes->post('settings/update', 'Settings::update');
    $routes->get('settings/seed-enhanced', 'Settings::seedDefaults');
    $routes->get('settings/add-missing', 'Settings::addMissingGroups');
    $routes->get('settings/debug', 'Settings::debugUpload');
    $routes->get('settings/debug-general', 'DebugGeneralSettings::index');
    $routes->get('settings/remap-groups', 'RemapSettingsGroups::index');
    $routes->get('settings/reset-and-seed', 'ResetAndSeedSettings::index');
    $routes->get('settings/export', 'ExportSettings::index');
    $routes->post('settings/import', 'ImportSettings::index');
    $routes->get('settings/delete-image/(:num)', 'Settings::deleteImage/$1');
    $routes->get('settings/fix', 'FixUploadController::index');
    $routes->post('settings/test-upload', 'FixUploadController::testUpload');

    // Simple Upload Routes
    $routes->get('settings-upload', 'SettingsUpload::index');
    $routes->post('settings-upload/update', 'SettingsUpload::update');
    $routes->get('settings-upload/delete-image/(:segment)', 'SettingsUpload::deleteImage/$1');

    // Debug Routes
    $routes->get('debug-settings', 'DebugSettings::index');
    $routes->post('debug-settings/update', 'DebugSettings::update');

    // Activity Logs
    $routes->get('activity-logs', 'ActivityLogs::index');

    // Comments Routes
    $routes->get('comments', 'Comments::index');
    $routes->get('comments/approve/(:num)', 'Comments::approve/$1');
    $routes->get('comments/spam/(:num)', 'Comments::spam/$1');
    $routes->get('comments/delete/(:num)', 'Comments::delete/$1');
});

// Test routes
$routes->get('test-upload', 'TestUpload::index');
$routes->post('test-upload/process', 'TestUpload::process');
$routes->get('check-settings', 'CheckSettings::index');
$routes->get('add-missing-settings', 'AddMissingSettings::index');
$routes->get('debug-images', 'DebugImageSettings::index');
$routes->get('debug-images/fix-hero', 'DebugImageSettings::fixHero');
