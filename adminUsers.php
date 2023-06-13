<?php

  use VirtualStore\Models\User;
  use VirtualStore\PageAdmin;


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