<?php

function adminOrder($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();
  $pageAdmin = $container->get(VirtualStore\PageAdmin::class);
  $pageAdmin->renderPage('orders', ['orders'=>VirtualStore\Models\Order::listAll()]);
}

function adminOrderDelete($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();

  $order = $container->get(VirtualStore\Models\Order::class);
  $order->get((int)$vars['idorder']);

  $order->delete();

  header("Location: /admin/orders");
  exit;
}

function adminOrderDetails($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();

  $order = $container->get(VirtualStore\Models\Order::class);
  $order->get((int)$vars['idorder']);

  $cart = $order->getOrderCart();

  $pageAdmin = $container->get(VirtualStore\PageAdmin::class);
  $pageAdmin->renderPage('order', ['order'=>$order->getValues(), 'cart'=>$cart->getValues(), 'products'=>$cart->getProducts()]);
}

function adminOrderStatus($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();

  $order = $container->get(VirtualStore\Models\Order::class);
  $order->get((int)$vars['idorder']);

  $pageAdmin = $container->get(VirtualStore\PageAdmin::class);
  $pageAdmin->renderPage('order-status', ['order'=>$order->getValues(), 'status'=>VirtualStore\Models\OrderStatus::listAll(), 'msg'=>VirtualStore\Message::getMessage()]);
}

function adminPostOrderStatus($vars, $container)
{
  VirtualStore\Models\User::verifyLogin();

  if(!isset($_POST['idstatus']) || !(int)$_POST['idstatus'] > 0){
    VirtualStore\Message::setMessage('Informe o status atual', 'error'); 
    header("Location: /admin/orders/".$vars['idorder']."/status");
    exit;
  }

  $order = $container->get(VirtualStore\Models\Order::class);
  $order->get((int)$vars['idorder']);
  $order->setidstatus($_POST['idstatus']);
  $order->save();

  VirtualStore\Message::setMessage('Status atualizado', 'success');
  header("Location: /admin/orders/".$vars['idorder']."/status");
  exit;

}