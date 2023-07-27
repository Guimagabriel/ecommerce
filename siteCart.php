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

function checkout($vars, $container)
{
    VirtualStore\Models\User::verifyLogin(false);
    $address = $container->get(VirtualStore\Models\Address::class);
    $cart = VirtualStore\Models\Cart::getFromSession();

    if (isset($_GET['zipcode'])) {
        $_GET['zipcode'] = $cart->getdeszipcode();
    
        $address->loadFromCEP($_GET['zipcode']);
        
        $cart->setdeszipcode($_GET['zipcode']);
        $cart->save();
        $cart->getCalculateTotal();
    }

    if (!$address->getdesaddress()) $address->setdesaddress('');
    if (!$address->getdescomplement()) $address->setdescomplement('');
    if (!$address->getdesdistrict()) $address->setdesdistrict('');
    if (!$address->getdescity()) $address->setdescity('');
    if (!$address->getdesstate()) $address->setdesstate('');
    if (!$address->getdescountry()) $address->setdescountry('');
    if (!$address->getdeszipcode()) $address->setdeszipcode('');


    $page = $container->get(VirtualStore\Page::class);
    $page->renderPage('checkout', ['cart' => $cart->getValues(), 'address' => $address->getValues(), 'products' => $cart->getProducts(), 'error' => VirtualStore\Message::getMessage()]);
}

function checkoutPost($vars, $container) {
    VirtualStore\Models\User::verifyLogin(false);

    $fields = ['zipcode', 'desaddress', 'desdistrict', 'descity', 'desstate', 'descountry'];
    $friendlyMsg = [
        'zipcode' => 'CEP',
        'desaddress' => 'endereço',
        'desdistrict' => 'bairro',
        'descity' => 'cidade',
        'desstate' => 'estado',
        'descountry' => 'país'
    ];

    foreach($fields as $field) {
        if(!isset($_POST[$field]) || $_POST[$field] === '') {
            $msg = $friendlyMsg[$field];
            $errorMsg = 'Informe o(a) '. $msg;
            VirtualStore\Message::setMessage($errorMsg, 'error');
            header("Location: /checkout");
            exit;
        }
    }

    $user = VirtualStore\Models\User::getFromSession();

    $address = $container->get(VirtualStore\Models\Address::class);

    $_POST['deszipcode'] = $_POST['zipcode'];
    $_POST['idperson'] = $user->getidperson();

    $address->setData($_POST);
    $address->save();

    $cart = VirtualStore\Models\Cart::getFromSession();   
    $values = $cart->getValues();
    $total = $values['vltotal'];

    $order = $container->get(VirtualStore\Models\Order::class);
    $order->setData([
        'idcart' => $cart->getidcart(),
        'idaddress' => $address->getidaddress(),
        'iduser' => $user->getiduser(),
        'idstatus' => VirtualStore\Models\OrderStatus::EM_ABERTO,
        'vltotal' => $total
    ]);

    $order->save();

    header("Location: /order/".$order->getidorder());
    exit;    
}