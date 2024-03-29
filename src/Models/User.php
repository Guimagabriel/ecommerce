<?php

namespace VirtualStore\Models;
use VirtualStore\Model;
use VirtualStore\Sql;
use VirtualStore\Exceptions\Users\UserNotFoundException;
use VirtualStore\Exceptions\Users\EmailNotFoundException;

class User extends Model
{
  const SESSION = "User";
  const ERROR = "UserError";
  const ERROR_REGISTER = "UserErrorRegister";

  public static function login($login, $password)
  {
    $sql = new Sql();

    $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b ON a.iduser = b.idperson WHERE a.deslogin = :LOGIN", [":LOGIN" => $login]);

    if (count($results) === 0) {
      throw new UserNotFoundException();
    }
      
    $data = $results[0];
      
    if (password_verify($password, $data["despassword"]) === false) {
      throw new UserNotFoundException();
    }
    
    $user = new User();

    $data['desperson'] = utf8_encode($data['desperson']);

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
      
      if($inadmin) {
          header("Location: /admin/login");
        }
      else {
        header("Location: /login");
        }
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
      ":desperson" => utf8_decode($this->getdesperson()),
      ":deslogin" => $this->getdesemail(),
      ":despassword" => User::getPasswordHash($this->getdespassword()),
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

    $data = $results[0];

    $data['desperson'] = utf8_encode($data['desperson']);
  
    return $this->setData($data);
  }

  public function update()
  {
    $sql = new Sql();
    $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", [
      ":iduser" => $this->getiduser(),
      ":desperson" => utf8_decode($this->getdesperson()),
      ":deslogin" => $this->getdesemail(),
      ":despassword" => User::getPasswordHash($this->getdespassword()),
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

  public static function setError($msg)
  {
    $_SESSION[User::ERROR] = $msg;
  }

  public static function getError()
  {
    $msg = (isset($_SESSION[User::ERROR]) && $_SESSION[User::ERROR]) ? $_SESSION[User::ERROR] : '';
    User::clearError();
    return $msg;
  }

  public static function clearError()
  {
    $_SESSION[User::ERROR] = NULL;
  }

  public static function getPasswordHash($password)
  {
      return password_hash($password, PASSWORD_DEFAULT);
  }

  public static function setErrorRegister($msg)
  {
    $_SESSION[User::ERROR_REGISTER] = $msg;
  }

  public static function getErrorRegister()
  {
    $msg = (isset($_SESSION[User::ERROR_REGISTER]) && $_SESSION[User::ERROR_REGISTER]) ? $_SESSION[User::ERROR_REGISTER] : '';
    User::clearErrorRegister();
    return $msg;
  }

  public static function clearErrorRegister()
  {
    $_SESSION[User::ERROR_REGISTER] = NULL;
  }

  public static function checkLoginExist($login)
  {
    $sql = new Sql();

    $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", [":LOGIN" => $login]);

    return (count($results) > 0);
  }

  public static function updateSessionData(int $iduser)
  {
    $user = new User();
    $userData = $user->get($iduser);

    if ($userData) {
      $_SESSION[User::SESSION] = $userData;
    }
  }

  public function getOrders()
  {
    $sql = new Sql();

    $results = $sql->select("SELECT * FROM tb_orders o 
      INNER JOIN tb_ordersstatus USING(idstatus)
      INNER JOIN tb_carts USING(idcart) 
      INNER JOIN tb_users u ON u.iduser = o.iduser
      INNER JOIN tb_addresses USING(idaddress)
      INNER JOIN tb_persons p ON p.idperson = u.idperson
      WHERE o.iduser = :iduser", [':iduser' => $this->getiduser()]);

    return $results;
  }

  public static function getPage(int $page = 1, int $itemsPerPage = 10)
  {
    $start = ($page - 1) * $itemsPerPage;

    $sql = new Sql();

    $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS *
      FROM tb_users a 
      INNER JOIN tb_persons b USING(idperson)
      ORDER BY b.desperson
      LIMIT $start, $itemsPerPage
    ");

    $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

    return [
      'data' => $results,
      'total' => (int)$resultsTotal[0]['nrtotal'],
      'pages' => ceil($resultsTotal[0]['nrtotal'] / $itemsPerPage)
    ];
  }

  public static function getPageSearch($search, int $page = 1, int $itemsPerPage = 10)
  {
    $start = ($page - 1) * $itemsPerPage;

    $sql = new Sql();

    $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS *
      FROM tb_users a 
      INNER JOIN tb_persons b USING(idperson)
      WHERE b.desperson LIKE :search OR b.desemail LIKE :search OR a.deslogin LIKE :search
      ORDER BY b.desperson
      LIMIT $start, $itemsPerPage
    ", [':search'=>'%'.$search.'%']);

    $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

    return [
      'data' => $results,
      'total' => (int)$resultsTotal[0]['nrtotal'],
      'pages' => ceil($resultsTotal[0]['nrtotal'] / $itemsPerPage)
    ];
  }

}