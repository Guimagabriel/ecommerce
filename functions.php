<?php

function formatPrice($vlprice)
{
  if($vlprice == 0 || "") {
    return "0,00";
  } else {
  $result = number_format($vlprice, 2, ",", ".");
  }
  return $result;
}