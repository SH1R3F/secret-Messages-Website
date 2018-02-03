<?php
class DB{
  private static $_instance = null;
  private $_pdo      = null,
          $_query,
          $_result,
          $_count,
          $_isLogged = false;

  private function __construct(){
    try{
      $this->_pdo = new PDO('mysql:host=' . Config::get('database/host') . ';dbname=' . Config::get('database/users_db'), Config::get('database/username'), Config::get('database/password'));
    }catch(PDOException $e){
      die('Error Connecting To Database -> ' . Config::get('contact/email'));
    }
  }

  public static function getInstance(){
    if(!self::$_instance){
      self::$_instance = new DB();
    }
    return self::$_instance;
  }

  public function query($sql){
    if($this->_query = $this->_pdo->prepare($sql)){
      if($this->_query->execute()){
        $this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
        $this->_count  = $this->_query->rowCount();
        return $this;
      }
    }
    return false;
  }

  public function GetData($table, $cond = array()){
    if(count($cond)){
      $sql = "SELECT * FROM $table WHERE $cond[0]='$cond[1]'";
      return $this->query($sql);
    }
    return false;
  }

  public function IsTable($name = null){
    if($name){
      $sql = "SELECT 1 FROM $name";
      return $this->query($sql);
    }
    return false;
  }

  public function CreateTable($name, $fields = array()){
    if(count($fields)){
      $string = '';
      foreach($fields as $field=>$value){
        $string .= "$field $value, ";
      }
      $string = substr($string, 0, strlen($string)-2);
      $sql = "CREATE TABLE IF NOT EXISTS $name ( $string )";
      return $this->query($sql);
    }
  }

  public function InsertNew($table, $fields = array()){
    if(count($fields)){
      $field  = array_keys($fields);
      $values = array_values($fields);
      $sql = "INSERT INTO $table ( " . implode(', ', $field) . " ) VALUES ( '" . implode("', '", $values) . "' )";
      return $this->_pdo->query($sql);
    }
  }

  public function CheckValue($table, $cond = array()){
    if(count($cond)){
      $sql = "SELECT * FROM $table WHERE $cond[0]='$cond[1]'";
      return $this->query($sql)->count();
    }
  }

  public function delete($table, $fields = array()){
    $sql = "DELETE FROM $table WHERE $fields[0]='$fields[1]'";
    return ($this->query($sql)) ? true : false;
  }

  public function update($table, $id, $fields=array()){
    if(count($fields)){
      $str = '';
      foreach($fields as $field => $value){
        $str .= "$field = '$value', ";
      }
      $str = substr($str, 0, strlen($str)-2);
      $sql = "UPDATE $table SET $str WHERE id = $id";
      return $this->query($sql);
    }
  }

  public function count(){
    return $this->_count;
  }

  public function result(){
    return $this->_result;
  }

  public function IsLoggedIn(){
    return $this->_isLogged;
  }

  public function first(){
    return $this->result()[0];
  }

}
