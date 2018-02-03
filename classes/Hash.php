<?php

class Hash{


  public static function salt(){
    return md5(uniqid(mt_rand(), true));
  }

  public static function make($string, $salt=''){
    return hash('sha256', $string . $salt);
  }

  public static function unique(){
    return self::make(uniqid());
  }


}
