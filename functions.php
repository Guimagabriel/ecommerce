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