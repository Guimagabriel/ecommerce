<?php

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

function adminCategoriesProducts($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $category = $container->get(VirtualStore\Models\Category::class);
    $category->get((int) $vars['idcategory']);
    $page = $container->get(VirtualStore\PageAdmin::class);
    $page->renderPage('categories-products', ['category'=>$category->getValues(), 'productsRelated'=>$category->getProducts(true), 'productsNotRelated'=>$category->getProducts()]);
}

function adminAddCategoriesProducts($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $category = $container->get(VirtualStore\Models\Category::class);
    $category->get((int) $vars['idcategory']);
    $product = $container->get(VirtualStore\Models\Product::class);
    $product->get((int) $vars['idproduct']);
    $category->addProduct($product);

    header("Location: /admin/categories/".$vars['idcategory']."/products");
    exit;
}

function adminRemoveCategoriesProducts($vars, $container)
{
    VirtualStore\Models\User::verifyLogin();
    $category = $container->get(VirtualStore\Models\Category::class);
    $category->get((int) $vars['idcategory']);
    $product = $container->get(VirtualStore\Models\Product::class);
    $product->get((int) $vars['idproduct']);
    $category->removeProduct($product);

    header("Location: /admin/categories/".$vars['idcategory']."/products");
    exit;
}
