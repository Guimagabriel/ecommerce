<?php

session_start();
require_once ("dependencies.php");

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

function renderIndex($vars, $container)
{
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('index');
}

function renderIndexAdmin($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
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
    VirtualStore\Models\User::logout();

    header('Location: /admin/login');
    exit;
}

function adminUsers($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $vars['users'] = VirtualStore\Models\User::listAll();
    $page = $container->get(VirtualStore\PageAdmin::class);
    $page->renderPage('users', $vars);
}

function adminUsersCreate($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $page = $container->get(VirtualStore\PageAdmin::class);
    $page->renderPage('users-create');
}

function adminUsersUpdate($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $user = $container->get(VirtualStore\Models\User::class);
    $user->get((int)$vars['iduser']);
    $page = $container->get(VirtualStore\PageAdmin::class);
    $page->renderPage('users-update', ["user" => $user->getValues()]);
}

function adminPostUsersCreate($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $user = $container->get(VirtualStore\Models\User::class);
    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
    $user->setData($_POST);
    $user->save();

    header("Location: /admin/users");
    exit;
}

function adminPostUsersUpdate($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $user = $container->get(VirtualStore\Models\User::class);
    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
    $user->get((int)$vars['iduser']);
    $user->setData($_POST);
    $user->update();

    header("Location: /admin/users");
    exit;
}

function adminDeleteUsers($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $user = $container->get(VirtualStore\Models\User::class);
    $user->get((int)$vars['iduser']);
    $user->delete();

    header("Location: /admin/users");
    exit;
}

function adminForgot($vars, $container)
{
    $page = $container->get(VirtualStore\PageAdmin::class);
    $page->setOptions(false);
    $page->renderPage('forgot');
}

function adminPostForgot($vars, $container)
{
    $user = VirtualStore\Models\User::getForgot($_POST['email']);
}

function adminCategories($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $category = $container->get(VirtualStore\Models\Category::class);
    $vars['categories'] = $category->listAll();
    $page = $container->get(VirtualStore\PageAdmin::class);
    $page->renderPage('categories', $vars);
}

function adminCategoriesCreate($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $page = $container->get(VirtualStore\PageAdmin::class);
    $page->renderPage('categories-create');
}

function adminPostCategoriesCreate($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $category = $container->get(VirtualStore\Models\Category::class);
    $category->setData($_POST);
    $category->save();
    
    header("Location: /admin/categories");
    exit;
}

function adminDeleteCategories($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $category = $container->get(VirtualStore\Models\Category::class);
    $category->get((int) $vars['idcategory']);
    $category->delete();

    header("Location: /admin/categories");
    exit;
}

function adminCategoriesUpdate($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $category = $container->get(VirtualStore\Models\Category::class);
    $category->get((int) $vars['idcategory']);
    $page = $container->get(VirtualStore\PageAdmin::class);
    $page->renderPage('categories-update', ['category' => $category->getValues()]);
}

function adminPostCategoriesUpdate($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $category = $container->get(VirtualStore\Models\Category::class);
    $category->get((int) $vars['idcategory']);
    $category->setData($_POST);
    $category->save();

    header("Location: /admin/categories");
    exit;
}

function category($vars, $container)
{
    $category = $container->get(VirtualStore\Models\Category::class);
    $category->get((int) $vars['idcategory']);
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('category', ['category' => $category->getValues(), 'products' => []]);

}