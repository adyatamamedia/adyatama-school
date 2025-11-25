<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Auth Routes
$routes->get('auth/login', 'Auth::login'); // Fallback for direct access
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// Dashboard Routes (Protected)
$routes->group('dashboard', ['filter' => 'auth', 'namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('/', 'Dashboard::index');

    // Categories Routes
    $routes->get('categories', 'Categories::index');
    $routes->get('categories/new', 'Categories::new');
    $routes->post('categories/create', 'Categories::create');
    $routes->get('categories/edit/(:num)', 'Categories::edit/$1');
    $routes->post('categories/update/(:num)', 'Categories::update/$1');
    $routes->get('categories/delete/(:num)', 'Categories::delete/$1');
    // Posts Routes
    $routes->get('posts', 'Posts::index');
    $routes->get('posts/new', 'Posts::new');
    $routes->post('posts/create', 'Posts::create');
    $routes->get('posts/edit/(:num)', 'Posts::edit/$1');
    $routes->post('posts/update/(:num)', 'Posts::update/$1');
    $routes->get('posts/delete/(:num)', 'Posts::delete/$1');
    // Media Routes
    $routes->get('media', 'Media::index');
    $routes->post('media/upload', 'Media::upload');
    $routes->post('media/update/(:num)', 'Media::update/$1');
    $routes->get('media/delete/(:num)', 'Media::delete/$1');
    $routes->get('api/media', 'Media::getMediaJson');

    // Guru-Staff Routes
    $routes->get('guru-staff', 'GuruStaff::index');
    $routes->get('guru-staff/new', 'GuruStaff::new');
    $routes->post('guru-staff/create', 'GuruStaff::create');
    $routes->get('guru-staff/edit/(:num)', 'GuruStaff::edit/$1');
    $routes->post('guru-staff/update/(:num)', 'GuruStaff::update/$1');
    $routes->get('guru-staff/delete/(:num)', 'GuruStaff::delete/$1');

    // Extracurriculars Routes
    $routes->get('extracurriculars', 'Extracurriculars::index');
    $routes->get('extracurriculars/new', 'Extracurriculars::new');
    $routes->post('extracurriculars/create', 'Extracurriculars::create');
    $routes->get('extracurriculars/edit/(:num)', 'Extracurriculars::edit/$1');
    $routes->post('extracurriculars/update/(:num)', 'Extracurriculars::update/$1');
    $routes->get('extracurriculars/delete/(:num)', 'Extracurriculars::delete/$1');

    // Subscribers Routes
    $routes->get('subscribers', 'Subscribers::index');
    $routes->get('subscribers/toggle/(:num)', 'Subscribers::toggle/$1');
    $routes->get('subscribers/delete/(:num)', 'Subscribers::delete/$1');

    // Static Pages Routes
    $routes->get('pages', 'Pages::index');
    $routes->get('pages/new', 'Pages::new');
    $routes->post('pages/create', 'Pages::create');
    $routes->get('pages/edit/(:num)', 'Pages::edit/$1');
    $routes->post('pages/update/(:num)', 'Pages::update/$1');
    $routes->get('pages/delete/(:num)', 'Pages::delete/$1');
    // Galleries Routes
    $routes->get('galleries', 'Galleries::index');
    $routes->get('galleries/new', 'Galleries::new');
    $routes->post('galleries/create', 'Galleries::create');
    $routes->get('galleries/edit/(:num)', 'Galleries::edit/$1');
    $routes->post('galleries/update/(:num)', 'Galleries::update/$1');
    $routes->get('galleries/delete/(:num)', 'Galleries::delete/$1');
    // Gallery Items
    $routes->get('galleries/items/(:num)', 'Galleries::items/$1');
    $routes->post('galleries/items/add/(:num)', 'Galleries::addItem/$1');
    $routes->get('galleries/items/delete/(:num)', 'Galleries::deleteItem/$1');

    // Tags Routes (General View)
    $routes->get('tags', 'Tags::index');
    $routes->get('tags/delete/(:segment)', 'Tags::delete/$1');

    // User Management
    $routes->get('users', 'Users::index');
    $routes->get('users/new', 'Users::new');
    $routes->post('users/create', 'Users::create');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
    $routes->get('users/delete/(:num)', 'Users::delete/$1');

    // Settings Routes
    $routes->get('settings', 'Settings::index');
    $routes->post('settings/update', 'Settings::update');
    $routes->get('settings/seed', 'Settings::seedDefaults');

    // Activity Logs
    $routes->get('activity-logs', 'ActivityLogs::index');

    // Comments Routes
    $routes->get('comments', 'Comments::index');
    $routes->get('comments/approve/(:num)', 'Comments::approve/$1');
    $routes->get('comments/spam/(:num)', 'Comments::spam/$1');
    $routes->get('comments/delete/(:num)', 'Comments::delete/$1');
});

