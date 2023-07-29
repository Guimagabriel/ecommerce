<?php

function adminUsers($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    
    $search = (isset($_GET['search']) ? $_GET['search'] : '');
    $page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);

    if($search != '') {
        $pagination = VirtualStore\Models\User::getPageSearch($search, $page);
    } else {
        $pagination = VirtualStore\Models\User::getPage($page);
    }

    
    $pages = [];

    for ($i = 0; $i < $pagination['pages']; $i++) {
        array_push($pages, [
            'href'=>'/admin/users?'.http_build_query([
                'page'=>$i+1,
                'search'=>$search
            ]),
            'text'=>$i+1
        ]);
    }

    $vars['users'] = $pagination['data'];
    $vars['pages'] = $pages;
    $pageAdmin = $container->get(VirtualStore\PageAdmin::class);
    $pageAdmin->renderPage('users', ['users'=>$vars['users'], 'pages'=>$vars['pages'], 'search'=>$search]);
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