<?php

class Redirect{

  public static function refresh(){
    return header('Refresh:0');
  }

  public static function to($page){
    return header("Location: $page");
  }

}
