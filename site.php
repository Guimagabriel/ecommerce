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

function cart($vars, $container)
{
    $cart = VirtualStore\Models\Cart::getFromSession();
    $error = VirtualStore\Models\Cart::getMsgError();
    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('cart', ['cart' => $cart->getValues(), 'products' => $cart->getProducts(), 'error' => $error]);
}

function cartAddProduct($vars, $container)
{
    $product = $container->get(VirtualStore\Models\Product::class);
    $product->get((int) $vars['idproduct']);
    $cart = VirtualStore\Models\Cart::getFromSession();
    $qtd = (isset($_GET['qtd'])) ? (int)$_GET['qtd'] : 1;
    for ($i = 0; $i<$qtd ; $i++) {
        $cart->addProduct($product);
    }

    header("Location: /cart");
    exit;
}

function cartRemoveProduct($vars, $container)
{
    $product = $container->get(VirtualStore\Models\Product::class);
    $product->get((int) $vars['idproduct']);
    $cart = VirtualStore\Models\Cart::getFromSession();
    $cart->removeProduct($product);

    header("Location: /cart");
    exit;
}

function cartRemoveAllProduct($vars, $container)
{
    $product = $container->get(VirtualStore\Models\Product::class);
    $product->get((int) $vars['idproduct']);
    $cart = VirtualStore\Models\Cart::getFromSession();
    $cart->removeProduct($product, true);

    header("Location: /cart");
    exit;
}

function postFreight($vars, $container)
{
    $cart = VirtualStore\Models\Cart::getFromSession();
    $cart->setFreight($_POST['zipcode']);
    
    header("Location: /cart");
    exit;
}