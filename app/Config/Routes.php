<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.


$routes->group(
    '',
    ['filter' => 'auth'],
    static function ($routes) {
        $routes->post('posts/create', 'PostController::create');
        $routes->get('posts/create', 'PostController::createView');
        $routes->get('posts/list', 'PostController::list', ['as' => 'postList']);
        $routes->get('posts/delete/(:num)', 'PostController::delete/$1', ['as' => 'postDelete']);
        $routes->post('posts/update/(:segment)', 'PostController::update/$1');
        $routes->get('posts/update/(:segment)', 'PostController::updateView/$1');
        $routes->get('users/reset-password', 'users/reset_password');
        $routes->get('categories/delete/(:num)', 'Categories::delete/$1', ['as' => 'categoryDelete']);
        $routes->get('categories/create', 'Categories::createView');
        $routes->post('categories/create', 'Categories::create');
        $routes->get('categories/update/(:segment)', 'Categories::updateView/$1', ['as' => 'categoryUpdateView']);
        $routes->post('categories/update/(:segment)', 'Categories::update/$1');
        $routes->get('categories/list', 'Categories::list', ['as' => 'categoryList']);
        $routes->get('logout', 'Users::logout');
    }
);
$routes->get('login', 'Users::login');
$routes->post('login', 'Users::loginValidate');

$routes->get('/', 'PostController::index');
$routes->get('posts/search', 'PostController::public_post_search');
$routes->get('post/(:segment)', 'PostController::view/$1');
$routes->get('posts', 'PostController::public_post_list');

$routes->get('categories', 'Categories::index');

$routes->get('categories/(:segment)/posts', 'PostController::posts_by_category/$1');

// $routes('(:segment)', 'pages/view/)$1');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
