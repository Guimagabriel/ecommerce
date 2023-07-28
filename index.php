<?php

session_start();
require_once ("dependencies.php");

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'renderIndex');
    $r->addRoute('GET', '/login', 'login');
    $r->addRoute('POST', '/login', 'loginPost');
    $r->addRoute('POST', '/register', 'createPost');
    $r->addRoute('GET', '/profile', 'profile');
    $r->addRoute('POST', '/profile', 'profilePost');
    $r->addRoute('GET', '/profile/orders', 'orders');
    $r->addRoute('GET', '/profile/orders/{idorder}', 'ordersDetails');
    $r->addRoute('GET', '/profile/change-password', 'changePassword');
    $r->addRoute('POST', '/profile/change-password', 'changePasswordPost');
    $r->addRoute('GET', '/order/{idorder}', 'order');
    $r->addRoute('GET', '/boleto/{idorder}', 'boleto');
    $r->addRoute('GET', '/logout', 'logout');
    $r->addRoute('GET', '/checkout', 'checkout');
    $r->addRoute('POST', '/checkout', 'checkoutPost');
    $r->addRoute('GET', '/cart', 'cart');
    $r->addRoute('GET', '/cart/{idproduct}/add', 'cartAddProduct');
    $r->addRoute('GET', '/cart/{idproduct}/minus', 'cartRemoveProduct');
    $r->addRoute('GET', '/cart/{idproduct}/remove', 'cartRemoveAllProduct');
    $r->addRoute('POST', '/cart/freight', 'postFreight');
    $r->addRoute('GET', '/admin', 'renderIndexAdmin');
    $r->addRoute('GET', '/admin/login', 'renderLoginAdmin');
    $r->addRoute('POST', '/admin/login', 'renderPostLoginAdmin');
    $r->addRoute('GET', '/admin/logout', 'logoutAdmin');
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
    $r->addRoute('GET', '/admin/categories/{idcategory}/products', 'adminCategoriesProducts');
    $r->addRoute('GET', '/admin/categories/{idcategory}/products/{idproduct}/add', 'adminAddCategoriesProducts');
    $r->addRoute('GET', '/admin/categories/{idcategory}/products/{idproduct}/remove', 'adminRemoveCategoriesProducts');
    $r->addRoute('GET', '/category/{idcategory}', 'category');
    $r->addRoute('GET', '/products/{desurl}', 'productsDetails');
    $r->addRoute('GET', '/admin/products', 'adminProducts');
    $r->addRoute('GET', '/admin/products/create', 'adminProductsCreate');
    $r->addRoute('POST', '/admin/products/create', 'adminPostProductsCreate');
    $r->addRoute('GET', '/admin/products/{idproduct}', 'adminProductsUpdate');
    $r->addRoute('POST', '/admin/products/{idproduct}', 'adminPostProductsUpdate');
    $r->addRoute('GET', '/admin/products/{idproduct}/delete', 'adminDeleteProducts');    
    
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