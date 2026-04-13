<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'FeedController::index');
$routes->get('uploads/covers/(:segment)', 'UploadsController::cover/$1');
$routes->get('kategori/(:segment)', 'FeedController::category/$1');
$routes->get('artikel/(:segment)', 'FeedController::show/$1');
$routes->post('artikel/(:segment)/komentar', 'FeedController::storeComment/$1');
$routes->post('artikel/(:segment)/like', 'FeedController::toggleLike/$1');
$routes->post('artikel/(:segment)/bookmark', 'FeedController::toggleBookmark/$1');

$routes->get('masuk', 'AuthController::login');
$routes->post('masuk', 'AuthController::attemptLogin');
$routes->get('daftar', 'AuthController::register');
$routes->post('daftar', 'AuthController::registerUser');
$routes->post('keluar', 'AuthController::logout');

$routes->get('bookmark', 'BookmarkController::index');

$routes->get('kreator/dashboard', 'CreatorController::dashboard');
$routes->get('kreator/editor', 'CreatorController::editor');
$routes->get('kreator/editor/(:segment)', 'CreatorController::editor/$1');
$routes->post('kreator/editor', 'CreatorController::store');
