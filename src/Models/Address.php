<?php

namespace VirtualStore\Models;

use VirtualStore\Sql;
use VirtualStore\Model;

class Address extends Model 
{
  public static function getCEP(string $nrcep)
  {
    $nrcep = str_replace("-", "", $nrcep);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$nrcep/json/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
    $data = json_decode(curl_exec($ch), true);

    curl_close($ch);

    return $data;
  }

  public function loadFromCEP($nrcep)
  {
    $data = Address::getCEP($nrcep);
    if (isset($data['logradouro']) && $data['logradouro']) {

      $this->setdesaddress($data['logradouro']);
      $this->setdescomplement($data['complemento']);
      $this->setdesdistrict($data['bairro']);
      $this->setdescity($data['localidade']);
      $this->setdesstate($data['uf']);
      $this->setdescountry('Brasil');
      $this->setdeszipcode($nrcep);
    }
  }

  public function save()
  {
    $sql = new Sql();

    $results = $sql->select("CALL sp_addresses_save(:idaddress, :idperson, :desaddress, :desnumber, :descomplement, :descity, :desstate, :descountry, :deszipcode, :desdistrict)", [
      ':idaddress' => $this->getidaddress(),
      ':idperson' => $this->getidperson(),
      ':desaddress' => $this->getdesaddress(),
      ':desnumber' => $this->getdesnumber(),
      ':descomplement' => $this->getdescomplement(),
      ':descity' => $this->getdescity(),
      ':desstate' => $this->getdesstate(),
      ':descountry' => $this->getdescountry(),
      ':deszipcode' => $this->getdeszipcode(),
      ':desdistrict' => $this->getdesdistrict()
    ]);

    if (count($results) > 0) {
      $this->setData($results[0]);
    }

  }

}
