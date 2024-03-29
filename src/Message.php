<?php

namespace VirtualStore;

class Message 
{
  public static function setMessage($msg, $type)
  {
    $_SESSION['msg'] = $msg;
    $_SESSION['type'] = $type;
  }

  public static function getMessage()
  {
    if(!isset($_SESSION['msg']) || !$_SESSION['msg']) {
      return false;
    } else {
      $message = [
        'msg' => $_SESSION['msg'],
        'type' => $_SESSION['type']
      ];
      
      Message::clearMessage();
      return $message;
    }

  }

  public static function clearMessage()
  {
    $_SESSION['msg'] = '';
    $_SESSION['type'] = '';
  }

}