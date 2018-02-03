<?php


class Session{

  public static function exists($name){
    return (isset($_SESSION[$name])) ? true : false;
  }

  public static function put($name, $value){
    return $_SESSION[$name] = $value;
  }

  public static function get($name){
    if(isset($_SESSION[$name])){
      return $_SESSION[$name];
    }
  }

  public static function delete($name){
    unset($_SESSION[$name]);
  }
}
