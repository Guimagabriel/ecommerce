<?php

  use VirtualStore\PageAdmin;
  use VirtualStore\Models\User;

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
  