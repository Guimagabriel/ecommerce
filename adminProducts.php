<?php

function adminProducts($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();
  $vars['products'] = VirtualStore\Models\Product::listAll();
  $page = $container->get(VirtualStore\PageAdmin::class);
  $page->renderPage('products', $vars);
}

function adminProductsCreate($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();
  $page = $container->get(VirtualStore\PageAdmin::class);
  $page->renderPage('products-create');
}

function adminPostProductsCreate($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();
  $product = $container->get(VirtualStore\Models\Product::class);
  $product->setData($_POST);
  $product->save();

  header("Location: /admin/products");
  exit;
}

function adminProductsUpdate($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();
  $product = $container->get(VirtualStore\Models\Product::class);
  $product->get((int) $vars['idproduct']);
  $page = $container->get(VirtualStore\PageAdmin::class);
  $page->renderPage('products-update', ['product' => $product->getValues()]);
}

function adminPostProductsUpdate($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();
  $product = $container->get(VirtualStore\Models\Product::class);
  $product->get((int) $vars['idproduct']);
  $product->setData($_POST);
  $product->save();
  $product->checkPhoto();
  
  header("Location: /admin/products");
  exit;
}

function adminDeleteProducts($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();
  $product = $container->get(VirtualStore\Models\Product::class);
  $product->get((int) $vars['idproduct']);
  $product->delete();
  
  header("Location: /admin/products");
  exit;
}