<?php

namespace VirtualStore\Models;
use VirtualStore\Model;
use VirtualStore\Sql;

class Category extends Model
{
  public static function listAll()
    {
      $sql = new Sql();
      return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
    }

    public function save()
    {
      $sql = new Sql();
      $results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", [
      ":idcategory" => $this->getidcategory(),
      ":descategory" => $this->getdescategory(),
      ]);

      $this->setData($results[0]);
      Category::updateFile();
    }

    public function get($idcategory) {
      $sql = new Sql();
      $results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", [
        ":idcategory"=>$idcategory
      ]);

      $this->setData($results[0]);
    }

    public function delete()
    {
      $sql = new Sql();
      $sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", [
        ":idcategory"=>$this->getidcategory()
      ]);

      Category::updateFile();
    }

    public static function updateFile()
    {
      $categories = Category::listAll();
      $html = [];

      foreach ($categories as $category) {
        array_push($html, '<li><a href="/category/'.$category['idcategory'].'">'.$category['descategory'].'</a></li>');
      }

      file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "categories-menu.php", implode("", $html));
    }
}