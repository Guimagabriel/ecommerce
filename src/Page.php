<?php

namespace VirtualStore;

use League\Plates\Engine;

class Page 
{

  private $templates;

  public function __construct()
  {
    $this->templates = new Engine($_SERVER['DOCUMENT_ROOT'].'/views');

    echo $this->templates->render('header');
  }

  public function renderPage(string $name, array $data = [])
  {    
    echo $this->templates->render($name, $data);
  }

  public function __destruct()
  {
    echo $this->templates->render('footer');
  }
}