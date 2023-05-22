<?php

session_start();
require_once ("dependencies.php");

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'renderIndex');
    $r->addRoute('GET', '/admin', 'renderIndexAdmin');
    $r->addRoute('GET', '/admin/login', 'renderLoginAdmin');
    $r->addRoute('POST', '/admin/login', 'renderLogin');
    $r->addRoute('GET', '/admin/logout', 'logout');
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

function renderIndex($vars, $container)
{
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('index');
}

function renderIndexAdmin($vars, $container)
{
    $user = $container->get(VirtualStore\Models\User::class);
    $user->verifyLogin();
    $page = $container->get(VirtualStore\PageAdmin::class);
    $page->renderPage('index');
}

function renderLoginAdmin($vars, $container)
{
    $page = $container->get(VirtualStore\PageAdmin::class);
    $page->setOptions(false);
    $page->renderPage('login');
}

function renderLogin($vars, $container)
{
    $user = $container->get(VirtualStore\Models\User::class);
    $user->login($_POST['login'], $_POST['password']);

    header('Location: /admin');
    exit;
}

function logout($vars, $container)
{
    $user = $container->get(VirtualStore\Models\User::class);
    $user->logout();

    header('Location: /admin/login');
    exit;
}