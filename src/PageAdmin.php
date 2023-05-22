<?php

namespace VirtualStore;

use League\Plates\Engine;

class PageAdmin 
{

  private $options;

  public function __construct($options = true)
  {
    $this->options = $options;
    
    $this->templates = new Engine($_SERVER['DOCUMENT_ROOT'].'/views/admin');

  }

  public function renderPage($name)
  {
    if ($this->options == true) {
      echo $this->templates->render('header');
    }

    echo $this->templates->render($name);
  }

  public function __destruct()
  {
    if ($this->options == true) {
    echo $this->templates->render('footer');
    }
  }

  public function setOptions(bool $options)
  {
    $this->options = $options;
  }

}

