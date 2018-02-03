<?php

class Messages{
    private static $_instance = null;
    private $_pdo      = null,
            $_query,
            $_result,
            $_count;

    private function __construct(){
      try{
        $this->_pdo = new PDO('mysql:host=' . Config::get('database/host2') . ';dbname=' . Config::get('database/messg_db'), Config::get('database/msgUsrNm'), Config::get('database/msgPsWrd'));
      }catch(PDOException $e){
        die('ERROR CONNECTING TO DATABASE. PLEASE CONTACT ADMIN -> ' . Config::get('contact/email'));
      }
    }

    public static function getInstance(){
      if(!self::$_instance){
        self::$_instance = new Messages();
      }
      return self::$_instance;
    }

    public function query($sql){
      if($this->_query = $this->_pdo->prepare($sql)){
        if($this->_query->execute()){
          $this->_result = $this->_query->fetchAll();
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

    public function GetAllData($id){
      $sql = "SELECT * FROM user_$id ORDER BY id DESC";
      return $this->query($sql);
    }

    public function CountMessages($id){
      $sql = "SELECT * FROM user_$id";
      return $this->query($sql)->count();
    }

    public function IsTable($id = null){
      if($id){
        $sql = "SELECT 1 FROM user_$id";
        return $this->query($sql);
      }
      return false;
    }

    public function CreateTable($table, $fields = array()){
      if(count($fields)){
        $string = '';
        foreach($fields as $field=>$value){
          $string .= "$field $value, ";
        }
        $string = substr($string, 0, strlen($string)-2);
        $sql = "CREATE TABLE IF NOT EXISTS $table ( $string )";
        return $this->query($sql);
      }
    }

    public function InsertNew($table, $fields = array()){
      if(count($fields)){
        $field  = array_keys($fields);
        $values = array_values($fields);
        $sql = "INSERT INTO $table ( " . implode(', ', $field) . " ) VALUES ( '" . implode("', '", $values) . "' )";
        return $this->query($sql);
      }
    }

    public function update($id, $msg_id, $fields=array()){
      if(count($fields)){
        $str = '';
        foreach($fields as $field => $value){
          $str .= "$field = '$value', ";
        }
        $str = substr($str, 0, strlen($str)-2);
        $sql = "UPDATE user_$id SET $str WHERE id = $msg_id";
        return $this->query($sql);
      }
    }

    public function delete($id, $msg_id){
      $sql = "DELETE FROM user_$id WHERE id='$msg_id'";
      return ($this->query($sql)) ? true : false;
    }

    public function isLiked($id, $msg_id){
      $sql = "SELECT category FROM user_$id WHERE id='$msg_id'";
      if($this->query($sql)->result()[0]['category']){
        return ($this->update($id, $msg_id, array( 'category' => '0' ))) ? 0 : false ;
      }else{
        return ($this->update($id, $msg_id, array( 'category' => '1' ))) ? 1 : false ;
      }
    }

    /*

    if(Messages::getInstance()->InsertNew("user_".$user->data()->id, array(
      'message'  => 'tesahsdjkashkdjashkjdht',
      'SentDate' => date('Y-m-d H:i:s'),
      'category' => 1
    ))){
      echo 'test';
    }else{
      echo 'u'.$user->data()->id;
    }


    */

    public function count(){
      return $this->_count;
    }

    public function result(){
      return $this->_result;
    }



}
