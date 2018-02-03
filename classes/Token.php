<?php

class Token{

  public static function generate($name){
    return Session::put($name, md5(uniqid()));
  }

  public static function check($name, $value){
    if(Session::get($name) === $value){
      Session::delete($name);
      return true;
    }
    return false;
  }

  public static function CheckLike($name, $value){
    if(Session::get($name) === $value){
      return true;
    }
    return false;
  }


}
