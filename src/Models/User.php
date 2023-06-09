<?php

namespace VirtualStore\Models;
use VirtualStore\Model;
use VirtualStore\Sql;
use VirtualStore\Exceptions\Users\UserNotFoundException;
use VirtualStore\Exceptions\Users\EmailNotFoundException;

class User extends Model
{
  const SESSION = "User";

  public function login(string $login, string $password)
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

  public static function getFromSession()
  {
    $user = new User();

    if (isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['iduser'] > 0) {
      $user->setData($_SESSION[User::SESSION]);
    }

    return $user;
  }

  public static function checkLogin(bool $inadmin = true)
  {
    if (!isset($_SESSION[User::SESSION])
    ||
    !$_SESSION[User::SESSION]
    ||
    !(int)$_SESSION[User::SESSION]["iduser"] > 0) {

      return false;
    
    } else {
        if ($inadmin === true && (bool)$_SESSION[User::SESSION]["inadmin"] === true) {
          return true;          
        } else if ($inadmin === false) {
            return true;
        } else {
            return false;           
        }
    }
  }

  public static function verifyLogin(bool $inadmin = true)
  {
    if (!User::checkLogin($inadmin)) { 
          header("Location: /admin/login");
          exit;
        }
  }

  public static function logout()
  {
    $_SESSION[User::SESSION] = NULL;
  }
 
  public static function listAll()
  {
    $sql = new Sql();
    return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
  }

  public function save()
  {
    $sql = new Sql();
    $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", [
      ":desperson" => $this->getdesperson(),
      ":deslogin" => $this->getdeslogin(),
      ":despassword" => $this->getdespassword(),
      ":desemail" => $this->getdesemail(),
      ":nrphone" => $this->getnrphone(),
      ":inadmin" => $this->getinadmin()
    ]);

    $this->setData($results[0]);
  }

  public function get(int $iduser)
  {
    $sql = new Sql();
    $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser",
    [":iduser" => $iduser]);

  
    $this->setData($results[0]);
  }

  public function update()
  {
    $sql = new Sql();
    $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", [
      ":iduser" => $this->getiduser(),
      ":desperson" => $this->getdesperson(),
      ":deslogin" => $this->getdeslogin(),
      ":despassword" => $this->getdespassword(),
      ":desemail" => $this->getdesemail(),
      ":nrphone" => $this->getnrphone(),
      ":inadmin" => $this->getinadmin()
    ]);

    $this->setData($results[0]);

  }

  public function delete()
  {
    $sql = new Sql();
    $sql->query("CALL sp_users_delete(:iduser)", [":iduser" => $this->getiduser()]);
  }

  public static function getForgot($email)
  {
    $sql = new Sql();
    $results = $sql->select("SELECT * FROM tb_persons a INNER JOIN tb_users b WHERE a.desemail = :email",
    [":email" => $email]);
    
    if(count($results) === 0){
      throw new EmailNotFoundException();
    } else {
      $data = $results[0];
      $res =  $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", 
      ["iduser" => $data["iduser"], ":desip" => $_SERVER["REMOTE_ADDR"]]);

      if(count($res) === 0) {
        throw new EmailNotFoundException();
      } else {
        $dataRecovery = $res[0];
      }
    }
  }

}