<?php

use VirtualStore\Models\User;

function formatPrice($vlprice)
{
  if($vlprice == 0 || "") {
    return "0,00";
  } else {
  $result = number_format($vlprice, 2, ",", ".");
  }
  return $result;
}

function checkLogin($inadmin = true)
{
  return User::checkLogin($inadmin);
}

function getUserName()
{
  $user = User::getFromSession();

  return $user->getdesperson();  
}

function getCartNrQtd()
{
  $cart = VirtualStore\Models\Cart::getFromSession();
  $totals = $cart->getProductsTotals();

  return $totals['nrqtd'];
}

function getCartSubTotal()
{
  $cart = VirtualStore\Models\Cart::getFromSession();
  $totals = $cart->getProductsTotals();

  return formatPrice($totals['vlprice']);
}

function formatDate($date)
{
  return date('d/m/Y', strtotime($date));
}