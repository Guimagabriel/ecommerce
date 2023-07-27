<?php

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