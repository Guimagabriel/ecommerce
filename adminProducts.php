<?php

function adminProducts($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();

  $search = (isset($_GET['search']) ? $_GET['search'] : '');
    $page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);

    if($search != '') {
        $pagination = VirtualStore\Models\Product::getPageSearch($search, $page);
    } else {
        $pagination = VirtualStore\Models\Product::getPage($page);
    }

    
    $pages = [];

    for ($i = 0; $i < $pagination['pages']; $i++) {
        array_push($pages, [
            'href'=>'/admin/products?'.http_build_query([
                'page'=>$i+1,
                'search'=>$search
            ]),
            'text'=>$i+1
        ]);
    }

  $vars['products'] = VirtualStore\Models\Product::listAll();
  $page = $container->get(VirtualStore\PageAdmin::class);
  $page->renderPage('products', ['products'=>$pagination['data'], 'pages'=>$pages, 'search'=>$search]);
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
  $product->setPhoto($_FILES['file']);  
  
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