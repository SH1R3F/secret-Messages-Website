<?php
class Input{

  public static function exists($method = 'post'){
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      return true;
    }

    switch($method){
      case 'post':
        return ($_SERVER['REQUEST_METHOD'] === 'POST') ? true : false ;
      break;
      case 'get':
        return ($_SERVER['REQUEST_METHOD'] === 'GET') ? true : false ;
      break;
      default:
        return false;
      break;
    }

  }

  public static function get($name = null, $method = 'post'){
    if($name){
      switch($method){
        case 'post':
          if(isset($_POST[$name])){
            return scape($_POST[$name]);
          }
        break;
        case 'get':
          if(isset($_GET[$name])){
            return scape($_GET[$name]);
          }
        break;
        default:
          return false;
        break;
      }
    }
    return false;
  }
}
