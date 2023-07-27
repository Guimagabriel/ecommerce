<?php

function renderIndex($vars, $container)
{
    $vars['products'] = VirtualStore\Models\Product::listAll();
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('index', ['products' => VirtualStore\Models\Product::checkList($vars['products'])]);
}

function category($vars, $container)
{
    $page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);
    $category = $container->get(VirtualStore\Models\Category::class);
    $category->get((int) $vars['idcategory']);
    $productsPage = $category->getProductsPage($page);
    $pages = [];
    for ($i=1; $i <= $productsPage['pages']; $i++) {
        array_push($pages, [
            'link' => '/category'.'/'.$category->getidcategory().'?page='.$i,
            'page' => $i
        ]);
    }
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('category', ['category' => $category->getValues(), 'products' => $productsPage['data'], 'pages' => $pages]);

}

function productsDetails($vars, $container)
{
    $product = $container->get(VirtualStore\Models\Product::class);
    $product->getFromUrl($vars['desurl']);
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('product-detail', ['product' => $product->getValues(), 'categories' => $product->getCategories()]);
}

function orders($vars, $container)
{
    VirtualStore\Models\User::verifyLogin(false);
    $user = VirtualStore\Models\User::getFromSession();
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('profile-orders', ['orders'=> $user->getOrders()]);
}

function ordersDetails($vars, $container)
{
    VirtualStore\Models\User::verifyLogin(false);
    $order = $container->get(VirtualStore\Models\Order::class);
    $order->get((int)$vars['idorder']);

    $cart = $container->get(VirtualStore\Models\Cart::class);
    $cart->getCart((int)$order->getidcart());
    $cart->getCalculateTotal();

    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('profile-orders-detail', ['order'=>$order->getValues(), 'products'=>$cart->getProducts(), 'cart'=>$cart->getValues()]);
}
