<?php

namespace VirtualStore\Models;

use VirtualStore\Sql;
use VirtualStore\Model;
use VirtualStore\Models\Cart;

class Order extends Model
{
  public function save()
  {
    $sql = new Sql();

    $results = $sql->select("CALL sp_orders_save(:idorder, :idcart, :iduser, :idstatus, :idaddress, :vltotal)", [
      ':idorder' => $this->getidorder(),
      ':idcart' => $this->getidcart(),
      ':iduser' => $this->getiduser(),
      ':idstatus' => $this->getidstatus(),
      ':idaddress' => $this->getidaddress(),
      ':vltotal' => $this->getvltotal()      
    ]);

    if (count($results) > 0) {
      $this->setData($results[0]);
    }
  }

  public function get(int $idorder)
  {
    $sql = new Sql();

    $results = $sql->select("SELECT * FROM tb_orders o 
      INNER JOIN tb_ordersstatus USING(idstatus)
      INNER JOIN tb_carts USING(idcart) 
      INNER JOIN tb_users u ON u.iduser = o.iduser
      INNER JOIN tb_addresses USING(idaddress)
      INNER JOIN tb_persons p ON p.idperson = u.idperson
      WHERE o.idorder = :idorder", [':idorder' => $idorder]);

    if (count($results) > 0) {
      $this->setData($results[0]);
    }
  }

  public static function listAll()
  {
    $sql = new Sql();

    return $sql->select("SELECT * FROM tb_orders o 
    INNER JOIN tb_ordersstatus USING(idstatus)
    INNER JOIN tb_carts USING(idcart) 
    INNER JOIN tb_users u ON u.iduser = o.iduser
    INNER JOIN tb_addresses USING(idaddress)
    INNER JOIN tb_persons p ON p.idperson = u.idperson
    GROUP BY o.dtregister DESC");
  }

  public function delete()
  {
    $sql = new Sql();

    $sql->query("DELETE FROM tb_orders WHERE idorder = :idorder", [":idorder" => $this->getidorder()]);
  }

  public function getOrderCart():Cart
  {
    $cart = new Cart();
    
    $cart->getCart((int)$this->getidcart());

    return $cart;
  }
}