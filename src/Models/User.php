<?php

namespace VirtualStore\Models;
use VirtualStore\Model;
use VirtualStore\Sql;
use VirtualStore\Exceptions\Users\UserNotFoundeException;

class User extends Model
{
  const SESSION = "User";

  public function login($login, $password)
  {
    $sql = new Sql();

    $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", [":LOGIN" => $login]);

    if(count($results) === 0) {
      throw new UserNotFoundException();
    }
      
    $data = $results[0];
      
    if(password_verify($password, $data["despassword"]) === false) {
      throw new UserNotFoundException();
    }
    
    $user = new User();

    $user->setData($data);

    $_SESSION[User::SESSION] = $user->getValues();

    return $user;
  }

  public function verifyLogin($inadmin = true)
  {
    if (!isset($_SESSION[User::SESSION])
        ||
        !$_SESSION[User::SESSION]
        ||
        !(int)$_SESSION[User::SESSION]["iduser"] > 0
        || (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin) {
      
        header("Location: /admin/login");
        exit;
    }
  }

  public function logout()
  {
    $_SESSION[User::SESSION] = NULL;
  }
  
}