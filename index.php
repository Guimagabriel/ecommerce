<?php

session_start();
require_once ("dependencies.php");
require_once ("site.php");
require_once ("siteAdmin.php");
require_once ("adminUsers.php");
require_once ("adminCategories.php");
require_once ("adminForgot.php");

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'renderIndex');
    $r->addRoute('GET', '/admin', 'renderIndexAdmin');
    $r->addRoute('GET', '/admin/login', 'renderLoginAdmin');
    $r->addRoute('POST', '/admin/login', 'renderLogin');
    $r->addRoute('GET', '/admin/logout', 'logout');
    $r->addRoute('GET', '/admin/users', 'adminUsers');
    $r->addRoute('GET', '/admin/users/create', 'adminUsersCreate');
    $r->addRoute('POST', '/admin/users/create', 'adminPostUsersCreate');
    $r->addRoute('GET', '/admin/users/{iduser}', 'adminUsersUpdate');
    $r->addRoute('POST', '/admin/users/{iduser}', 'adminPostUsersUpdate');
    $r->addRoute('GET', '/admin/users/{iduser}/delete', 'adminDeleteUsers');
    $r->addRoute('GET', '/admin/forgot', 'adminForgot');
    $r->addRoute('POST', '/admin/forgot', 'adminPostForgot');
    $r->addRoute('GET', '/admin/categories', 'adminCategories');
    $r->addRoute('GET', '/admin/categories/create', 'adminCategoriesCreate');
    $r->addRoute('POST', '/admin/categories/create', 'adminPostCategoriesCreate');
    $r->addRoute('GET', '/admin/categories/{idcategory}/delete', 'adminDeleteCategories');
    $r->addRoute('GET', '/admin/categories/{idcategory}', 'adminCategoriesUpdate');
    $r->addRoute('POST', '/admin/categories/{idcategory}', 'adminPostCategoriesUpdate');
    $r->addRoute('GET', '/category/{idcategory}', 'category');
    
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        call_user_func($handler, $vars, $container);
        break;
}